<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use App\Models\Reserva;
use App\Models\Vehiculo;
use App\Models\Hotel;
use App\Models\Viajero;

class TransferController extends Controller
{
    // ============================================================
    // 1) SELECCIÓN DEL TIPO DE RESERVA
    // ============================================================
    public function showTypeSelection()
    {
        return view('transfers.type');
    }

    public function postTypeSelection(Request $request)
    {
        $request->validate([
            'reservation_type' => 'required|in:airport_to_hotel,hotel_to_airport,round_trip',
        ]);

        return redirect()->route('transfer.reserve.form', [
            'type' => $request->reservation_type
        ]);
    }

    // ============================================================
    // 2) MOSTRAR FORMULARIO DE RESERVA
    // ============================================================
    public function showReservationForm($type)
    {
        if (!in_array($type, ['airport_to_hotel', 'hotel_to_airport', 'round_trip'])) {
            return redirect()->route('transfer.select-type')
                ->with('error', 'Tipo de reserva no válido.');
        }

        $user = Auth::user();

// Solo los usuarios tipo viajeros y hoteles tienen restricción 48h para reservar
$isAdmin = Auth::guard('admin')->check();

// Admin: hoy | Hotel/Viajero: +48h
$minDate = $isAdmin
    ? Carbon::today()->format('Y-m-d')
    : Carbon::now()->addHours(48)->format('Y-m-d');


$hotelLogado = null;

if (Auth::guard('corporate')->check()) {
    $hotelId = Auth::guard('corporate')->user()->id_hotel;

    $hotelLogado = Hotel::where('activo', 1)
        ->where('id_hotel', $hotelId)
        ->first(); // 1 solo

    // Para reutilizar el Blade si quieres seguir con $hotels
    $hotels = collect();
    if ($hotelLogado) {
        $hotels = collect([$hotelLogado]);
    }

} else {
    // Admin o viajero -> todos
    $hotels = Hotel::where('activo', 1)
        ->orderBy('nombre')   // IMPORTANTE: orden estable
        ->get();
}


        $vehiculos = Vehiculo::where('activo', 1)
    ->orderBy('descripcion')
    ->get();

        $viajeros = collect();
        if (Auth::guard('admin')->check() || Auth::guard('corporate')->check()) {
            $viajeros = Viajero::orderBy('nombre')->get();
        }

        $viewMap = [
            'airport_to_hotel' => 'transfers.airport-to-hotel',
            'hotel_to_airport' => 'transfers.hotel-to-airport',
            'round_trip'       => 'transfers.round-trip',
        ];

        return view($viewMap[$type], compact(
            'user',
            'minDate',
            'hotels',
            'vehiculos',
            'viajeros',
            'hotelLogado'
        ));
    }

    // ============================================================
    // 3) CONFIRMAR RESERVA
    // ============================================================
    public function confirmReservation(Request $request)
    {
        $rules = [
            'reservation_type' => 'required|in:airport_to_hotel,hotel_to_airport,round_trip',
            'pax'              => 'required|integer|min:1',
            'email_contacto'   => 'required|email',
            'nombre_contacto'  => 'required|string',
            'telefono'         => 'required|string',
            'id_vehiculo'      => 'required|integer',
        ];

        // Admin u hotel → seleccionar viajero
        if (Auth::guard('admin')->check() || Auth::guard('corporate')->check()) {
            $rules['id_viajero'] = 'required|exists:transfer_viajeros,id_viajero';
        }

        $isAdmin = Auth::guard('admin')->check();

// Admin: hoy | Hotel y Viajero: +48h
$minDate = $isAdmin
    ? Carbon::today()->format('Y-m-d')
    : Carbon::now()->addHours(48)->format('Y-m-d');


        // 🔧 NORMALIZACIÓN round_trip (hotel recogida = destino)
        if (
            $request->reservation_type === 'round_trip'
            && empty($request->id_hotel_recogida)
            && !empty($request->id_hotel_destino)
        ) {
            $request->merge([
                'id_hotel_recogida' => $request->id_hotel_destino
            ]);
        }

        // VALIDACIONES POR TIPO
        if ($request->reservation_type === 'airport_to_hotel') {
    $rules += [
        'aeropuerto_origen' => 'required|string',
        'fecha_llegada'     => "required|date|after_or_equal:$minDate",
        'hora_llegada'      => 'required',
        'num_vuelo'         => 'required|string',
        'id_hotel_destino'  => 'required|integer',
    ];
}


        if ($request->reservation_type === 'hotel_to_airport') {
    $rules += [
        'origen_vuelo_salida' => 'required|string',
        'fecha_vuelo_salida'  => "required|date|after_or_equal:$minDate",
        'hora_vuelo_salida'   => 'required',
        'num_vuelo_salida'    => 'required|string',
        'id_hotel_recogida'   => 'required|integer',
        'hora_recogida'       => 'required',
    ];
}



        if ($request->reservation_type === 'round_trip') {
    $rules += [
        'origen_vuelo_entrada' => 'required|string',
        'fecha_llegada'        => "required|date|after_or_equal:$minDate",
        'hora_llegada'         => 'required',
        'num_vuelo_ida'        => 'required|string',
        'id_hotel_destino'     => 'required|integer',

        'origen_vuelo_salida'  => 'required|string',
        'fecha_vuelo_salida'   => "required|date|after_or_equal:$minDate",
        'hora_vuelo_salida'    => 'required',
        'num_vuelo_salida'     => 'required|string',
        'hora_recogida_vuelta' => 'required',
        'id_hotel_recogida'    => 'required|integer',
    ];
}



        $request->validate($rules);

        $localizador = $this->createReservationRecord($request);

        return view('transfers.confirmation', compact('localizador'));
    }

    // ============================================================
    // 4) CREAR REGISTRO EN BBDD
    // ============================================================
    private function createReservationRecord(Request $request)
    {
        $type = $request->reservation_type;
        $now  = Carbon::now();

        // OWNER → SIEMPRE VIAJERO
        if (Auth::guard('web')->check()) {
            $idOwner = Auth::guard('web')->user()->id_viajero;
        } else {
            $idOwner = (int) $request->id_viajero;
            if (!$idOwner) {
                throw ValidationException::withMessages([
                    'id_viajero' => 'Debe seleccionar un viajero.',
                ]);
            }
        }

        $tipoOwner = 'user';

       // CREADOR REAL DE LA RESERVA
if (Auth::guard('admin')->check()) {
    $createdByType = 'admin';
    $createdById   = Auth::guard('admin')->user()->id_admin;

} elseif (Auth::guard('corporate')->check()) {
    $createdByType = 'hotel';
    $createdById   = Auth::guard('corporate')->user()->id_hotel;

} else {
    $createdByType = 'user';
    $createdById   = $idOwner; // id_viajero
}


        // DESTINO DEL TRASLADO
        $idDestino = $request->id_hotel_destino ?? $request->id_hotel_recogida;

        // HOTEL ASOCIADO A LA RESERVA (CREADOR)
        $idHotel = $createdByType === 'hotel'
            ? $createdById
            : $idDestino;

        // =====================
// PRECIO DEL VEHÍCULO
// =====================
$vehiculo = \App\Models\Vehiculo::findOrFail($request->id_vehiculo);

$precioBase = $vehiculo->precio;

// Ida y vuelta = doble trayecto
$precioFinal = $precioBase * ($type === 'round_trip' ? 2 : 1);

// =====================
// COMISIÓN DEL HOTEL
// =====================
$hotel = \App\Models\Hotel::findOrFail($idDestino);

// Comisión en porcentaje (ej: 10 = 10%)
$porcentajeComision = $hotel->Comision ?? 0;

$comisionGanada = round(
    $precioFinal * ($porcentajeComision / 100),
    2
);

        // DATOS BASE
        $data = [
            'localizador' => strtoupper(uniqid('TR-')),
            'id_tipo_reserva' => [
                'airport_to_hotel' => 1,
                'hotel_to_airport' => 2,
                'round_trip'       => 3,
            ][$type],

            'email_cliente' => $request->email_contacto,

            'id_owner'   => $idOwner,
            'tipo_owner' => $tipoOwner,

            'created_by_type' => $createdByType,
            'created_by_id'   => $createdById,

            'fecha_reserva'       => $now,
            'fecha_modificacion' => $now,

            'id_hotel'   => $idHotel,
            'id_destino' => $idDestino,

            'num_viajeros' => $request->pax,
            'id_vehiculo'  => $request->id_vehiculo,

            'precio_total'       => $precioFinal,
'comision_ganada'    => $comisionGanada,
'comision_liquidada' => 0,

            // Defaults
            'fecha_entrada'        => null,
            'hora_entrada'         => null,
            'numero_vuelo_entrada' => null,
            'origen_vuelo_entrada' => null,
            'fecha_vuelo_salida'   => null,
            'hora_vuelo_salida'    => null,
            'numero_vuelo_salida'  => null,
            'origen_vuelo_salida'  => null,
            'hora_recogida_hotel'  => null,
        ];

        // MAPEO POR TIPO
        if ($type === 'airport_to_hotel' || $type === 'round_trip') {
            $data['origen_vuelo_entrada'] = $request->origen_vuelo_entrada ?? $request->aeropuerto_origen;
            $data['fecha_entrada']        = $request->fecha_llegada;
            $data['hora_entrada']         = $request->hora_llegada;
            $data['numero_vuelo_entrada'] = $request->num_vuelo ?? $request->num_vuelo_ida;
        }

        if ($type === 'hotel_to_airport' || $type === 'round_trip') {
            $data['origen_vuelo_salida'] = $request->origen_vuelo_salida;
            $data['fecha_vuelo_salida']  = $request->fecha_vuelo_salida;
            $data['hora_vuelo_salida']   = $request->hora_vuelo_salida;
            $data['numero_vuelo_salida'] = $request->num_vuelo_salida ?? null;
            $data['hora_recogida_hotel'] = $request->hora_recogida ?? $request->hora_recogida_vuelta;
        }

        Reserva::create($data);

        return $data['localizador'];
    }
}