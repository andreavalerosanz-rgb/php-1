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
        'descripcion'     => 'required|string|max:255',
        'email_conductor' => 'required|email|max:255|unique:transfer_vehiculos,email_conductor',
        'password'        => 'required|string|max:255',
    ]);

    Vehiculo::create([
        'descripcion'     => $request->descripcion,
        'email_conductor' => $request->email_conductor,
        'password'        => $request->password,
        // 👇 NO precio → usa DEFAULT 50
    ]);

    return redirect()
        ->route('admin.vehiculos.index')
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
    'precio'           => 'required|numeric|min:0',
]);

$vehiculo->update([
    'descripcion'     => $request->descripcion,
    'email_conductor' => $request->email_conductor,
    'password'        => $request->password,
    'precio'          => $request->precio,
]);
        return redirect()->route('admin.vehiculos.index')
                         ->with('success', 'Vehículo actualizado correctamente.');
    }

    /**
     * Inhabilitar Vehiculos
     */
    public function disable($id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        $vehiculo->activo = 0;
        $vehiculo->save();

        return back()->with('success', 'Vehículo inhabilitado correctamente.');
    }

    public function enable($id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        $vehiculo->activo = 1;
        $vehiculo->save();

        return back()->with('success', 'Vehículo habilitado correctamente.');
    }

}
