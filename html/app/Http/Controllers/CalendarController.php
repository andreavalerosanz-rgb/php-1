<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reserva;
use App\Models\Hotel;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar.calendar');
    }

    public function events(Request $request)
    {
        $from = $request->query('from');
        $to   = $request->query('to');
        $fromDate = substr($from, 0, 10);
        $toDate   = substr($to, 0, 10);
        $userWeb     = Auth::guard('web')->user();        // viajero
        $userHotel   = Auth::guard('corporate')->user();  // hotel
        $userAdmin   = Auth::guard('admin')->user();      // admin

        $query = Reserva::query();

        if ($userWeb) {
            // VIAJERO → solo sus reservas
            $query->where('tipo_owner', 'user')
                  ->where('id_owner', $userWeb->id_viajero);

        } elseif ($userHotel) {
            // HOTEL → reservas cuyo id_hotel coincide con el hotel logueado
            $query->where('id_hotel', $userHotel->id_hotel);

        } elseif ($userAdmin) {
            // ADMIN → sin filtro
        } else {
            // No logueado
            return response()->json([]);
        }

        $query->where(function ($q) use ($fromDate, $toDate) {
            $q->whereBetween('fecha_entrada', [$fromDate, $toDate])
              ->orWhereBetween('fecha_vuelo_salida', [$fromDate, $toDate]);
        });

        // Ejecutar consulta
        $reservas = $query->get()->map(function ($r) {

            $start = null;

            if (in_array($r->id_tipo_reserva, [1, 3]) && $r->fecha_entrada) {
                $start = $r->fecha_entrada;
                if ($r->hora_entrada) {
                    $start .= ' ' . $r->hora_entrada;
                }
            }

            if (!$start && in_array($r->id_tipo_reserva, [2, 3]) && $r->fecha_vuelo_salida) {
                $start = $r->fecha_vuelo_salida;
                if ($r->hora_vuelo_salida) {
                    $start .= ' ' . $r->hora_vuelo_salida;
                }
            }

            if (!$start) {
                $start = $r->fecha_reserva;
            }

            $hotel = Hotel::find($r->id_hotel);
            $hotelName = $hotel ? $hotel->nombre : 'Hotel';

            switch ($r->id_tipo_reserva) {
                case 1: // Aeropuerto → Hotel
                    $airport = $r->origen_vuelo_entrada ?: "Aeropuerto";
                    $title = "Traslado $airport → $hotelName";
                    break;

                case 2: // Hotel → Aeropuerto
                    $airport = $r->origen_vuelo_salida ?: "Aeropuerto";
                    $title = "Traslado $hotelName → $airport";
                    break;

                case 3: // Ida y vuelta
                    $airportIda = $r->origen_vuelo_entrada ?: "Aeropuerto";
                    $title = "Ida y vuelta: $airportIda ⇄ $hotelName";
                    break;

                default:
                    $title = "Traslado";
            }

            return [
                'id'    => $r->id_reserva,
                'title' => $title,
                'start' => $start,
                'tipo'  => $r->id_tipo_reserva,
            ];
        });

        return response()->json($reservas);
    }

    //Detalle de las reservas en el Calendario

    public function show($id)
{
    $reserva = Reserva::find($id);

    if (!$reserva) {
        abort(404, 'Reserva no encontrada');
    }

    $hotel = \App\Models\Hotel::find($reserva->id_hotel);
    $vehiculo = \App\Models\Vehiculo::find($reserva->id_vehiculo);

    return view('calendar.detalle', compact('reserva', 'hotel', 'vehiculo'));
}

}