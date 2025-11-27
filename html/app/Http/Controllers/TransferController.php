<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; 
use Illuminate\Validation\ValidationException;
use App\Models\Reserva;
use App\Models\Precio;
use App\Models\Hotel;

class TransferController extends Controller
{
    // === Paso 1: Selección del Tipo de Reserva ===
    public function showTypeSelection()
    {
        return view('transfers.type');
    }

    public function postTypeSelection(Request $request)
    {
        $request->validate([
            'reservation_type' => 'required|in:airport_to_hotel,hotel_to_airport,round_trip',
        ]);

        return redirect()->route('transfer.reserve.form', ['type' => $request->reservation_type]);
    }

    // === Paso 2: Mostrar el Formulario Específico ===
    public function showReservationForm($type)
    {
        // 1. Validar Tipo
        if (!in_array($type, ['airport_to_hotel', 'hotel_to_airport', 'round_trip'])) {
            return redirect()->route('transfer.select-type')->with('error', 'Tipo de reserva no válido.');
        }

        // 2. Obtener Datos
        $user = Auth::user();

        // 3. Restricción de 48 Horas
        $minDate = Carbon::now()->addHours(48)->format('Y-m-d H:i');
        
        // 4. Obtener lista de hoteles desde la BDD
        $hotels = Hotel::all();
        
        // 5. Mapear Vista y Datos
        $viewMap = [
            'airport_to_hotel' => 'transfers.airport-to-hotel',
            'hotel_to_airport' => 'transfers.hotel-to-airport',
            'round_trip' => 'transfers.round-trip',
        ];

        return view($viewMap[$type], compact('user', 'minDate', 'hotels'));
    }

    // === Paso 3: Confirmar la Reserva ===
    public function confirmReservation(Request $request)
    {
        // 1. Validaciones
        $rules = [
            'reservation_type' => 'required|in:airport_to_hotel,hotel_to_airport,round_trip',
            'pax' => 'required|integer|min:1',
            'email_contacto' => 'required|email',
            'nombre_contacto' => 'required|string',
            'telefono' => 'required|string',
        ];
        
        $minDate = Carbon::now()->addHours(48)->format('Y-m-d');
        
        // Reglas para tramos de IDA (Aeropuerto -> Hotel)
        if ($request->reservation_type === 'airport_to_hotel') {
            $rules['fecha_llegada'] = 'required|date_format:Y-m-d|after_or_equal:' . $minDate;
            $rules['hora_llegada'] = 'required|date_format:H:i';
            $rules['id_hotel_destino'] = 'required|integer';
            $rules['num_vuelo'] = 'required|string';
        }

        // Reglas para tramos de VUELTA (Hotel -> Aeropuerto)
        if ($request->reservation_type === 'hotel_to_airport') {
            $rules['fecha_vuelo_salida'] = 'required|date_format:Y-m-d|after_or_equal:' . $minDate;
            $rules['hora_vuelo_salida'] = 'required|date_format:H:i';
            $rules['id_hotel_recogida'] = 'required|integer';
            $rules['hora_recogida'] = 'required|date_format:H:i'; 
        }
        
        // ✅ FIX VALIDACIÓN: Reglas para IDA Y VUELTA (ROUND_TRIP)
        if ($request->reservation_type === 'round_trip') {
            // TRAMO IDA
            $rules['fecha_llegada'] = 'required|date_format:Y-m-d|after_or_equal:' . $minDate;
            $rules['hora_llegada'] = 'required|date_format:H:i';
            $rules['id_hotel_destino'] = 'required|integer';
            $rules['num_vuelo_ida'] = 'required|string';
            
            // TRAMO VUELTA
            $rules['fecha_vuelo_salida'] = 'required|date_format:Y-m-d|after_or_equal:' . $minDate;
            $rules['hora_vuelo_salida'] = 'required|date_format:H:i';
            $rules['id_hotel_recogida'] = 'required|integer';
            $rules['hora_recogida_vuelta'] = 'required|date_format:H:i'; 
        }

        $request->validate($rules);

        // 2. Crear el registro en transfer_reservas con datos de la BDD
        $localizador = $this->createReservationRecord($request);
        
        // 3. Mostrar la Confirmación (Punto 11)
        return view('transfers.confirmation', compact('localizador'));
    }
    
    // Método para crear el registro y obtener el localizador
    private function createReservationRecord(Request $request)
    {
        $type = $request->reservation_type;
        $now = Carbon::now();
        
        // 1. Mapear el tipo de reserva de string a ID 
        $tipoReservaId = match ($type) {
            'airport_to_hotel' => 1,
            'hotel_to_airport' => 2,
            'round_trip' => 3,
            default => 1,
        };

        // 2. Identificar el Hotel de referencia y Viajero logueado
        $hotelId = $request->id_hotel_destino ?? $request->id_hotel_recogida;
        
        $idViajero = null;
        $authGuards = ['web', 'corporate']; // Guards permitidos en routes/web.php
        
        foreach ($authGuards as $guard) {
            if (Auth::guard($guard)->check()) {
                $idViajero = Auth::guard($guard)->id();
                break; 
            }
        }
        
        // Aseguramos que idViajero sea NULL o INT
        $idViajero = (is_numeric($idViajero)) ? (int)$idViajero : 0;
        
        
        // 3. LÓGICA DE PRECIOS Y VEHÍCULOS DESDE transfer_precios
        
        $transferPrice = Precio::where('id_hotel', $hotelId)
                               ->orderBy('Precio', 'asc')
                               ->first();

        if (!$transferPrice) {
            throw ValidationException::withMessages(['hotel' => 'El hotel seleccionado no tiene tarifas configuradas. Por favor, seleccione otro hotel.']);
        }

        $idVehiculo = $transferPrice->id_vehiculo;
        $precioUnitario = $transferPrice->Precio;
        
        // Calcular precio total (duplicar si es ida y vuelta)
        $multiplicador = ($type === 'round_trip') ? 2 : 1;
        $precioTotal = $precioUnitario * $multiplicador;

        // Lógica de Comisión (Simulada al 10%)
        $comisionRate = 0.10; 
        $comisionGanada = round($precioTotal * $comisionRate, 2);
        
        // 4. Mapeo de datos para la base de datos
        $data = [
            'localizador' => strtoupper(uniqid('TR-')),
            'id_tipo_reserva' => $tipoReservaId,
            'email_cliente' => $request->email_contacto,
            'id_owner' => $idViajero,
            'tipo_owner' => 'user',  // porque es un viajero
            'fecha_reserva' => $now,
            'fecha_modificacion' => $now,
            'id_hotel' => $hotelId,
            'id_destino' => $hotelId,
            'num_viajeros' => $request->pax,
            

            'id_vehiculo' => $idVehiculo,       
            'precio_total' => $precioTotal,  
            'comision_ganada' => $comisionGanada, 
            'comision_liquidada' => 0,
            
            'fecha_entrada' => null,
            'hora_entrada' => null,
            'numero_vuelo_entrada' => null,
            'origen_vuelo_entrada' => null,
            'hora_vuelo_salida' => null,
            'fecha_vuelo_salida' => null,
            'numero_vuelo_salida' => null,
            'origen_vuelo_salida' => null,
            'hora_recogida_hotel' => null,
        ];
        
        // 5. Llenar campos específicos según el tipo de reserva
        if ($type === 'airport_to_hotel' || $type === 'round_trip') {
            $data['fecha_entrada'] = $request->fecha_llegada;
            $data['hora_entrada'] = $request->hora_llegada;
            // Mapeo: num_vuelo para ida o num_vuelo_ida para round_trip
            $data['numero_vuelo_entrada'] = $request->num_vuelo ?? $request->num_vuelo_ida; 
            $data['origen_vuelo_entrada'] = $request->aeropuerto_origen ?? 'Aeropuerto de Origen';
        }

        if ($type === 'hotel_to_airport' || $type === 'round_trip') {
            $data['fecha_vuelo_salida'] = $request->fecha_vuelo_salida;
            
            // Combinar la fecha y hora para el campo TIMESTAMP (hora_vuelo_salida)
            $fechaSalida = $request->fecha_vuelo_salida;
            $horaSalida = $request->hora_vuelo_salida;
            if ($fechaSalida && $horaSalida) {
                $data['hora_vuelo_salida'] = Carbon::parse("$fechaSalida $horaSalida"); 
            }
        }

        // 6. Guardar en la Base de Datos
        $reserva = Reserva::create($data);

        // 7. Devolver el localizador único
        return $reserva->localizador;
    }
}