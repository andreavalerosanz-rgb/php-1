<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminHotelController extends Controller
{
    /**
     * Mostrar listado de hoteles corporativos
     */
    public function index()
    {
        $hoteles = Hotel::orderBy('nombre')->paginate(15);

        return view('admin.gestionhoteles', compact('hoteles'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('admin.gestionhoteles');
    }

    /**
     * Guardar el nuevo usuario corporativo
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email_hotel' => 'required|email|unique:hoteles,email_hotel',
            'Comision' => 'required|numeric|min:0|max:100',
            'id_zona' => 'required|integer',
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
