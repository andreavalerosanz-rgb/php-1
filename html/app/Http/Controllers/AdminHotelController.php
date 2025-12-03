<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminHotelController extends Controller
{
    /**
     * Mostrar listado de hoteles corporativos + formulario
     */
    public function index()
    {
        $hoteles = Hotel::orderBy('nombre')->paginate(15);
        $zonas = DB::table('transfer_zonas')->get(); // ← CARGA ZONAS

        return view('admin.gestionhoteles', compact('hoteles', 'zonas'));
    }

    /**
     * Mostrar formulario de creación (usa la misma vista)
     */
    public function create()
    {
        $hoteles = Hotel::orderBy('nombre')->paginate(15);
        $zonas = DB::table('transfer_zonas')->get(); // ← CARGA ZONAS

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
}
