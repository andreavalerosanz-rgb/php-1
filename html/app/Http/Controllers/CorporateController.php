<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Precio;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Reserva;
use App\Models\Hotel;

class CorporateController extends Controller
{

    /**
     * Mostrar comisiones del hotel.
     */
    public function commissions(Request $request)
{
    Reserva::sincronizarReservasFinalizadas();
    $user = Auth::guard('corporate')->user();
    if (!$user) {
        abort(403);
    }

    $all   = $request->has('all');
$month = $all ? null : $request->input('month', Carbon::now()->month);
$year  = $request->input('year', Carbon::now()->year);


    // 🔹 Reservas del hotel filtradas por FECHA DE TRASLADO
    $query = Reserva::where('id_hotel', $user->id_hotel);

if (!$all) {
    $query->where(function ($q) use ($month, $year) {

        $q->where(function ($q2) use ($month, $year) {
            $q2->where('id_tipo_reserva', 1)
               ->whereYear('fecha_entrada', $year)
               ->whereMonth('fecha_entrada', $month);
        })
        ->orWhere(function ($q2) use ($month, $year) {
            $q2->where('id_tipo_reserva', 2)
               ->whereYear('fecha_vuelo_salida', $year)
               ->whereMonth('fecha_vuelo_salida', $month);
        })
        ->orWhere(function ($q2) use ($month, $year) {
            $q2->where('id_tipo_reserva', 3)
               ->whereYear('fecha_entrada', $year)
               ->whereMonth('fecha_entrada', $month);
        });

    });
}

$reservas = $query->get();

    // 🔹 Construimos el reporte
    $commissionReport = $reservas->map(function ($reserva) use ($user) {

        // Fecha REAL del traslado
        $fechaTraslado = match ($reserva->id_tipo_reserva) {
            1 => $reserva->fecha_entrada,
            2 => $reserva->fecha_vuelo_salida,
            3 => $reserva->fecha_entrada,
            default => null,
        };

        return [
            'reserva_id'     => $reserva->id_reserva,
            'localizador'    => $reserva->localizador,
            'fecha_traslado' => $fechaTraslado,
            'precio_total'   => $reserva->precio_total,
            'comision_hotel' => $reserva->comision_ganada,
        ];
    });

    $totalComision = $commissionReport->sum('comision_hotel');

    return view('corporate.comissions', compact(
        'commissionReport',
        'month',
        'year',
        'totalComision'
    ));
}
}
