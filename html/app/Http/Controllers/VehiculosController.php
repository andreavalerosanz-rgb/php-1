<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehiculo;

class VehiculosController extends Controller
{
    /**
     * Mostrar lista de vehículos.
     */
    public function index()
    {
        $vehiculos = Vehiculo::all();

        return view('vehiculos.vehiculos_index', compact('vehiculos'));
    }

    /**
     * Mostrar formulario para crear vehículo.
     */
    public function create()
    {
        return view('vehiculos.vehiculos_creation');
    }

    /**
     * Guardar vehículo nuevo.
     */
    public function store(Request $request)
    {
        $request->validate([
            'descripcion'      => 'required|string|max:255',
            'email_conductor'  => 'required|email|max:255|unique:transfer_vehiculos,email_conductor',
            'password'         => 'required|string|max:255',
        ]);

        Vehiculo::create([
            'Descripción'     => $request->descripcion,
            'email_conductor' => $request->email_conductor,
            'password'        => $request->password, // Puedes encriptarlo si quieres
        ]);

        return redirect()->route('admin.vehiculos.index')
                         ->with('success', 'Vehículo creado correctamente.');
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $vehiculo = Vehiculo::findOrFail($id);

        return view('vehiculos.vehiculos_edit', compact('vehiculo'));
    }

    /**
     * Actualizar vehículo.
     */
    public function update(Request $request, $id)
    {
        $vehiculo = Vehiculo::findOrFail($id);

        $request->validate([
            'descripcion'      => 'required|string|max:255',
            'email_conductor'  => 'required|email|max:255|unique:transfer_vehiculos,email_conductor,' 
                                 . $vehiculo->id_vehiculo . ',id_vehiculo',
            'password'         => 'required|string|max:255',
        ]);

        $vehiculo->update([
            'Descripción'     => $request->descripcion,
            'email_conductor' => $request->email_conductor,
            'password'        => $request->password,
        ]);

        return redirect()->route('admin.vehiculos.index')
                         ->with('success', 'Vehículo actualizado correctamente.');
    }

    /**
     * Eliminar vehículo.
     */
    public function destroy($id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        $vehiculo->delete();

        return redirect()->route('admin.vehiculos.index')
                         ->with('success', 'Vehículo eliminado correctamente.');
    }
}
