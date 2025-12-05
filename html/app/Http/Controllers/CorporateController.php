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
        $user = Auth::guard('corporate')->user();
        if (!$user) {
            abort(403);
        }

        $month = $request->input('month', Carbon::now()->month);
        $year  = $request->input('year', Carbon::now()->year);

        // Traemos todas las reservas del hotel logueado en el mes/año seleccionado
        $reservas = Reserva::where('id_hotel', $user->id_hotel)
                            ->whereYear('fecha_reserva', $year)
                            ->whereMonth('fecha_reserva', $month)
                            ->get();

        // Calculamos ingresos y comisión según el porcentaje del hotel
        $commissionReport = $reservas->map(function($reserva) use ($user) {
            $comisionHotel = $reserva->precio_total * ($user->Comision / 100);

            return [
                'reserva_id'       => $reserva->id_reserva,
                'localizador'      => $reserva->localizador,
                'fecha_reserva'    => $reserva->fecha_reserva,
                'precio_total'     => $reserva->precio_total,
                'comision_hotel'   => $comisionHotel,
            ];
        });

        $totalComision = $commissionReport->sum('comision_hotel');

        return view('corporate.comissions', compact('commissionReport', 'month', 'year', 'totalComision'));
    }
}
