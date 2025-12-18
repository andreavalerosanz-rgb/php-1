<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Reserva;
use App\Models\Hotel;
use App\Models\Vehiculo;

class MisReservasController extends Controller
{
    /**
     * Listado de reservas
     */
    public function index()
    {
        Reserva::sincronizarReservasFinalizadas();

        if (Auth::guard('admin')->check()) {
            $rol = 'admin';
            $user = Auth::guard('admin')->user();
        } elseif (Auth::guard('corporate')->check()) {
            $rol = 'hotel';
            $user = Auth::guard('corporate')->user();
        } elseif (Auth::guard('web')->check()) {
            $rol = 'user';
            $user = Auth::guard('web')->user();
        } else {
            abort(403);
        }

        $query = Reserva::with(['hotel', 'owner', 'zona', 'vehiculo'])
            ->orderByRaw("
                CASE
                    WHEN id_tipo_reserva = 1 THEN fecha_entrada
                    WHEN id_tipo_reserva = 2 THEN fecha_vuelo_salida
                    WHEN id_tipo_reserva = 3 THEN fecha_entrada
                END DESC
            ");

        if ($rol === 'hotel') {
            $query->where('id_hotel', $user->id_hotel);
        }

        if ($rol === 'user') {
            $query->where('tipo_owner', 'user')
                  ->where('id_owner', $user->id_viajero);
        }

        $reservas = $query->paginate(8);

        return view('mis_reservas.mis_reservas', compact('reservas', 'rol'));
    }

    /**
     * Formulario edición
     */
    public function edit($id)
    {
        $reserva = Reserva::findOrFail($id);

        $rol = Auth::guard('admin')->check()
            ? 'admin'
            : (Auth::guard('corporate')->check() ? 'hotel' : 'user');

        if (!$reserva->puedeSerModificadaPor($rol)) {
            return redirect()->route('mis_reservas')
                ->with('error', 'No se puede modificar esta reserva.');
        }

        // 🔹 Fecha mínima (igual que reserva original)
        $isAdmin = Auth::guard('admin')->check();
        $minDate = $isAdmin
            ? Carbon::today()->format('Y-m-d')
            : Carbon::now()->addHours(48)->format('Y-m-d');

        // 🔹 Hoteles SOLO activos
        $hotels = Hotel::where('activo', 1)->get();

        $vehiculos = Vehiculo::where('activo', 1)
    ->orderBy('descripcion')
    ->get();

        $vista = match ((int) $reserva->id_tipo_reserva) {
            1 => 'edit_airport_to_hotel',
            2 => 'edit_hotel_to_airport',
            3 => 'edit_round_trip',
            default => abort(404),
        };

        return view("mis_reservas.$vista", compact(
            'reserva',
            'hotels',
            'vehiculos',
            'minDate'
        ));
    }

    /**
     * Actualizar reserva
     */
    public function update(Request $request, $id)
{
    $reserva = Reserva::findOrFail($id);

    // Rol activo
    $rol = Auth::guard('admin')->check()
        ? 'admin'
        : (Auth::guard('corporate')->check() ? 'hotel' : 'user');

    if (!$reserva->puedeSerModificadaPor($rol)) {
        return redirect()->route('mis_reservas')
            ->with('error', 'Esta reserva ya no puede modificarse.');
    }

    // Fecha mínima (misma lógica que crear reserva)
    $isAdmin = Auth::guard('admin')->check();
    $minDate = $isAdmin
        ? Carbon::today()->format('Y-m-d')
        : Carbon::now()->addHours(48)->format('Y-m-d');

    /*
    |--------------------------------------------------------------------------
    | VALIDACIÓN BASE (solo campos reales)
    |--------------------------------------------------------------------------
    */
    $rules = [
        'email_contacto' => 'required|email',
        'num_viajeros'   => 'required|integer|min:1',
        'id_vehiculo'    => 'required|integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | VALIDACIÓN POR TIPO DE RESERVA (BBDD)
    |--------------------------------------------------------------------------
    */
    if ($reserva->id_tipo_reserva == 1) { // Aeropuerto → Hotel
        $rules += [
            'origen_vuelo_entrada' => 'required|string',
            'fecha_entrada'        => "required|date|after_or_equal:$minDate",
            'hora_entrada'         => 'required',
            'numero_vuelo_entrada' => 'required|string',
            'id_hotel_destino'     => 'required|integer',
        ];
    }

    if ($reserva->id_tipo_reserva == 2) { // Hotel → Aeropuerto
        $rules += [
            'origen_vuelo_salida' => 'required|string',
            'fecha_vuelo_salida'  => "required|date|after_or_equal:$minDate",
            'hora_vuelo_salida'   => 'required',
            'numero_vuelo_salida' => 'required|string',
            'hora_recogida_hotel' => 'required',
            'id_hotel_recogida'   => 'required|integer',
        ];
    }

    if ($reserva->id_tipo_reserva == 3) { // Ida y Vuelta
        $rules += [
            // IDA
            'origen_vuelo_entrada' => 'required|string',
            'fecha_entrada'        => "required|date|after_or_equal:$minDate",
            'hora_entrada'         => 'required',
            'numero_vuelo_entrada' => 'required|string',
            'id_hotel_destino'     => 'required|integer',

            // VUELTA
            'origen_vuelo_salida'  => 'required|string',
            'fecha_vuelo_salida'   => "required|date|after_or_equal:$minDate",
            'hora_vuelo_salida'    => 'required',
            'numero_vuelo_salida'  => 'required|string',
            'hora_recogida_hotel'  => 'required',
        ];
    }

    $request->validate($rules);

    /*
    |--------------------------------------------------------------------------
    | HOTEL / DESTINO REAL
    |--------------------------------------------------------------------------
    */
    $idHotel = $request->id_hotel_destino
        ?? $request->id_hotel_recogida
        ?? $reserva->id_hotel;
    if ($rol === 'hotel') {
    $idHotel = Auth::guard('corporate')->user()->id_hotel;
    }


    // =====================
    // RECÁLCULO DE PRECIO
    // =====================
    $vehiculo = Vehiculo::findOrFail($request->id_vehiculo);
    $precioBase = $vehiculo->precio;

    // Ida y vuelta = doble
    $precioFinal = $precioBase * ($reserva->id_tipo_reserva == 3 ? 2 : 1);

    // Comisión del hotel
    $hotel = Hotel::findOrFail($idHotel);
    $porcentajeComision = $hotel->Comision ?? 0;

    $comisionGanada = round(
        $precioFinal * ($porcentajeComision / 100),
        2
    );

    /*
    |--------------------------------------------------------------------------
    | UPDATE REAL (solo columnas existentes)
    |--------------------------------------------------------------------------
    */
    $reserva->update([
    'email_cliente' => $request->input('email_contacto') ?? $request->input('email_cliente'),

    'num_viajeros' => $request->num_viajeros,
    'id_vehiculo'  => $request->id_vehiculo,

    'origen_vuelo_entrada' => $request->origen_vuelo_entrada,
    'fecha_entrada'        => $request->fecha_entrada,
    'hora_entrada'         => $request->hora_entrada,
    'numero_vuelo_entrada' => $request->numero_vuelo_entrada,

    'origen_vuelo_salida'  => $request->origen_vuelo_salida,
    'fecha_vuelo_salida'   => $request->fecha_vuelo_salida,
    'hora_vuelo_salida'    => $request->hora_vuelo_salida,
    'numero_vuelo_salida'  => $request->numero_vuelo_salida,
    'hora_recogida_hotel'  => $request->hora_recogida_hotel,

    // HOTEL REAL
    'id_hotel'   => $idHotel,
    'id_destino' => $idHotel,


    // PRECIOS
    'precio_total'    => $precioFinal,
    'comision_ganada' => $comisionGanada,

    'fecha_modificacion' => now(),
]);


    return view('mis_reservas.update_confirmation', [
    'reserva' => $reserva
]);
}
/**
 * Anular reserva (NO borramos por inconsistencia de datos)
 */
public function destroy($id)
{
    $reserva = Reserva::findOrFail($id);

    $rol = Auth::guard('admin')->check()
        ? 'admin'
        : (Auth::guard('corporate')->check() ? 'hotel' : 'user');

    if (!$reserva->puedeSerModificadaPor($rol)) {
        return redirect()->route('mis_reservas')
            ->with('error', 'No tienes permiso para anular esta reserva.');
    }

    if ($reserva->estado !== 'confirmada') {
        return redirect()->route('mis_reservas')
            ->with('error', 'Solo se pueden anular reservas confirmadas.');
    }

    $reserva->update([
        'estado' => 'anulada',
        'fecha_modificacion' => now(),
    ]);

    return redirect()->route('mis_reservas')
        ->with('success', 'Reserva anulada correctamente.');
}



}
