<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Hotel;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Muestra el listado de TODAS las reservas realizadas.
     */
    public function listReservations(Request $request)
    {
        // Obtener todas las reservas con sus hoteles relacionados para mostrar el destino
        $reservas = Reserva::with('hotel')->orderBy('fecha_reserva', 'desc')->paginate(15);

        return view('admin.reservations-list', compact('reservas'));
    }

    /**
     * Muestra el listado de reservas realizadas por cada hotel y calcula la comisión total.
     * Este es el requerimiento específico del Producto 3.
     */
    public function showCommissions(Request $request)
    {
        // 1. Obtener el mes y año actual (o del filtro)
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // 2. Consulta para obtener las reservas y agrupar por hotel
        // Usamos una consulta cruda porque 'id_hotel' está directamente en Reserva, 
        // y necesitamos datos del hotel.
        $commissions = Reserva::selectRaw('id_hotel, SUM(precio_total) as total_ingresos, SUM(comision_ganada) as total_comision')
            ->whereYear('fecha_reserva', $year)
            ->whereMonth('fecha_reserva', $month)
            ->where('comision_ganada', '>', 0) // Solo reservas con comisión
            ->groupBy('id_hotel')
            ->get();
            
        // 3. Mapear y obtener el nombre del hotel
        $commissionReport = $commissions->map(function ($item) {
            $hotel = Hotel::find($item->id_hotel);
            
            return [
                'hotel_id' => $item->id_hotel,
                'nombre_hotel' => $hotel ? $hotel->nombre : 'Hotel Eliminado',
                'total_ingresos' => $item->total_ingresos,
                'total_comision' => $item->total_comision,
                'comision_a_pagar' => $item->total_comision, // La comisión ganada es lo que se paga
            ];
        });

        return view('admin.commissions-report', compact('commissionReport', 'month', 'year'));
    }

    public function getCommissionData($month, $year, $hotelFilter = null)
{
    $query = Reserva::whereYear('fecha_reserva', $year)
        ->whereMonth('fecha_reserva', $month)
        ->where('comision_ganada', '>', 0);

    if ($hotelFilter) {
        $query->where('id_hotel', $hotelFilter);
    }

    if (!$hotelFilter) {
        $commissions = $query->selectRaw('
                id_hotel, 
                COUNT(*) as total_reservas,
                SUM(precio_total) as total_ingresos, 
                SUM(comision_ganada) as total_comision
            ')
            ->groupBy('id_hotel')
            ->get();

        return $commissions->map(function ($item) {
            $hotel = Hotel::find($item->id_hotel);

            return [
                'hotel_id' => $item->id_hotel,
                'nombre_hotel' => $hotel ? $hotel->nombre : 'Hotel Eliminado',
                'total_reservas' => $item->total_reservas,
                'total_ingresos' => $item->total_ingresos,
                'total_comision' => $item->total_comision,
            ];
        });
    }

    return $query->with(['vehiculo', 'hotel'])->get();
}

}