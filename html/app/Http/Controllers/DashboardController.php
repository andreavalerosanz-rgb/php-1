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


        return view('admin.dashboard', compact('stats'));
    }

    public function hotel()
    {
        $hotel = Auth::guard('corporate')->user();

        $reservas = Reserva::where('id_hotel', $hotel->id_hotel)->count();

        $stats = [
            'totalTraslados' => $reservas,
        ];

        return view('corporate.dashboard', compact('stats'));
    }

    public function user()
{
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
