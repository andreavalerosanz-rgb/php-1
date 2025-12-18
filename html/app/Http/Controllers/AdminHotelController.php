<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Reserva;

class AdminHotelController extends Controller
{
    /**
     * Mostrar listado de hoteles corporativos + formulario
     */
    public function index()
    {
        Reserva::sincronizarReservasFinalizadas();
        
        $hoteles = Hotel::orderBy('nombre')->get();
        $zonas   = DB::table('transfer_zonas')->get();

        // Filtros (permiten 'all')
        $month = request('month', now()->month);
        $year  = request('year', now()->year);
        $hotelFilter = request('hotel_id');

        $filterMonth = $month !== 'all';
        $filterYear  = $year  !== 'all';

        /*
        |--------------------------------------------------------------------------
        | COMISIONES AGRUPADAS POR HOTEL
        |--------------------------------------------------------------------------
        */
        $commissionRaw = Reserva::selectRaw("
                id_hotel,
                COUNT(*) as total_reservas,
                SUM(precio_total) as total_ingresos,
                SUM(comision_ganada) as total_comision
            ")
            ->when($filterMonth || $filterYear, function ($q) use ($month, $year, $filterMonth, $filterYear) {
                $q->where(function ($q2) use ($month, $year, $filterMonth, $filterYear) {

                    // IDA
                    $q2->where(function ($q3) use ($month, $year, $filterMonth, $filterYear) {
                        if ($filterYear) {
                            $q3->whereYear('fecha_entrada', $year);
                        }
                        if ($filterMonth) {
                            $q3->whereMonth('fecha_entrada', $month);
                        }
                    })

                    // VUELTA
                    ->orWhere(function ($q3) use ($month, $year, $filterMonth, $filterYear) {
                        if ($filterYear) {
                            $q3->whereYear('fecha_vuelo_salida', $year);
                        }
                        if ($filterMonth) {
                            $q3->whereMonth('fecha_vuelo_salida', $month);
                        }
                    });

                });
            })
            ->groupBy('id_hotel')
            ->get();

        $commissionReport = $commissionRaw->map(function ($item) {
            $hotel = Hotel::find($item->id_hotel);

            return [
                'hotel_id'       => $item->id_hotel,
                'nombre_hotel'   => $hotel ? $hotel->nombre : 'Hotel eliminado',
                'total_reservas' => $item->total_reservas,
                'total_ingresos' => $item->total_ingresos,
                'total_comision' => $item->total_comision,
            ];
        });

        /*
        |--------------------------------------------------------------------------
        | DETALLE DE RESERVAS POR HOTEL
        |--------------------------------------------------------------------------
        */
        $reservasDetalladas = collect();

        if ($hotelFilter) {
            $reservasDetalladas = Reserva::with('vehiculo')
                ->where('id_hotel', $hotelFilter)
                ->when($filterMonth || $filterYear, function ($q) use ($month, $year, $filterMonth, $filterYear) {
                    $q->where(function ($q2) use ($month, $year, $filterMonth, $filterYear) {

                        $q2->where(function ($q3) use ($month, $year, $filterMonth, $filterYear) {
                            if ($filterYear) {
                                $q3->whereYear('fecha_entrada', $year);
                            }
                            if ($filterMonth) {
                                $q3->whereMonth('fecha_entrada', $month);
                            }
                        })

                        ->orWhere(function ($q3) use ($month, $year, $filterMonth, $filterYear) {
                            if ($filterYear) {
                                $q3->whereYear('fecha_vuelo_salida', $year);
                            }
                            if ($filterMonth) {
                                $q3->whereMonth('fecha_vuelo_salida', $month);
                            }
                        });

                    });
                })
                ->get();
        }

        return view('admin.gestionhoteles', compact(
            'hoteles',
            'zonas',
            'commissionReport',
            'reservasDetalladas',
            'hotelFilter',
            'month',
            'year'
        ));
    }

    /**
     * Mostrar formulario de creación (usa la misma vista)
     */
    public function create()
    {
        $hoteles = Hotel::orderBy('nombre')->paginate(15);
        $zonas = DB::table('transfer_zonas')->get();

        return view('admin.gestionhoteles', compact('hoteles', 'zonas'));
    }

    /**
     * Guardar el nuevo hotel corporativo
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email_hotel' => 'required|email|unique:transfer_hoteles,email_hotel',
            'Comision' => 'required|numeric|min:0|max:100',
            'id_zona' => 'required|integer|exists:transfer_zonas,id_zona',
            'password' => 'required|min:6|confirmed',
        ]);

        Hotel::create([
            'nombre' => $request->nombre,
            'email_hotel' => $request->email_hotel,
            'Comision' => $request->Comision,
            'id_zona' => $request->id_zona,
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('admin.hoteles.index')
            ->with('status', 'Hotel corporativo creado correctamente.');
    }

    public function disable($id)
    {
        $hotel = Hotel::findOrFail($id);
        $hotel->activo = 0;
        $hotel->save();

        return back()->with('status', 'Hotel inhabilitado correctamente.');
    }

    public function enable($id)
    {
        $hotel = Hotel::findOrFail($id);
        $hotel->activo = 1;
        $hotel->save();

        return back()->with('status', 'Hotel habilitado correctamente.');
    }
}
