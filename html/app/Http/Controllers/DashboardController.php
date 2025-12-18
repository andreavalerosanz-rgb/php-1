<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Reserva;
use App\Models\Hotel;
use App\Models\Viajero;
use App\Models\Admin;

class DashboardController extends Controller
{
    public function admin()
    {
        Reserva::sincronizarReservasFinalizadas();
        $usuariosTotales = Viajero::count();
$hotelesTotales  = Hotel::count();
$adminsTotales   = Admin::count();
$reservas = Reserva::all();

        $viajerosTotales = $reservas->sum('num_viajeros');

        $stats = [
    'reservasTotales' => $reservas->count(),
    'viajerosTotales' => $viajerosTotales,
    'hotelesTotales'  => $hotelesTotales,
    'usuariosTotales' => $usuariosTotales,  
    'adminsTotales'   => $adminsTotales,   
];

// Resumen por zonas para generar JSON

        $totalReservas = Reserva::count();

    $zonas = \DB::table('transfer_zonas AS z')
        ->leftJoin('transfer_hoteles AS h', 'h.id_zona', '=', 'z.id_zona')
        ->leftJoin('transfer_reservas AS r', 'r.id_hotel', '=', 'h.id_hotel')
        ->selectRaw('
            z.descripcion AS zona,
            COUNT(r.id_reserva) AS num_traslados
        ')
        ->groupBy('z.descripcion')
        ->get()
        ->map(function ($item) use ($totalReservas) {
            $item->porcentaje = $totalReservas > 0 
                ? round(($item->num_traslados / $totalReservas) * 100, 2)
                : 0;
            return $item;
        });

    return view('admin.dashboard', compact('stats', 'zonas'));
    }

    public function hotel()
    {
        Reserva::sincronizarReservasFinalizadas();
        $hotel = Auth::guard('corporate')->user();

        $reservas = Reserva::where('id_hotel', $hotel->id_hotel)->count();

        $stats = [
            'totalTraslados' => $reservas,
        ];

        return view('corporate.dashboard', compact('stats'));
    }

    public function user()
{
    Reserva::sincronizarReservasFinalizadas();
    $user = Auth::guard('web')->user();   
    $viajeroId = $user->id_viajero;               

    $reservas = Reserva::where('tipo_owner', 'user')
        ->where('id_owner', $viajeroId)
        ->count();

    $stats = [
        'totalReservas' => $reservas
    ];

    return view('user.dashboard', compact('stats'));
}

}
