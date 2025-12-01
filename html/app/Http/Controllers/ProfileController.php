<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Hotel;
use App\Models\Viajero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Devuelve el usuario actual y el guard que lo ha autenticado.
     * Si hay varios logueados, dará prioridad:
     *  - admin
     *  - corporate
     *  - web (viajero)
     */
    private function resolveUserAndGuard(): array
    {
        foreach (['admin', 'corporate', 'web'] as $guard) {
            if (Auth::guard($guard)->check()) {
                return [
                    'guard' => $guard,
                    'user'  => Auth::guard($guard)->user(),
                ];
            }
        }

        abort(401); // nadie logueado
    }

    /**
     * Muestra el formulario de edición de perfil.
     */
    public function edit()
    {
        $data = $this->resolveUserAndGuard();
        $user  = $data['user'];
        $guard = $data['guard'];

        return view('profile.edit', compact('user', 'guard'));
    }

    /**
     * Actualiza los datos del perfil (nombre, email, contraseña).
     */
    public function update(Request $request)
    {
        $data  = $this->resolveUserAndGuard();
        $user  = $data['user'];
        $guard = $data['guard'];

        // Campos según el tipo de usuario
        switch ($guard) {
            case 'admin':
                $table      = 'transfer_admin';
                $idColumn   = 'id_admin';
                $emailField = 'email_admin';
                break;

            case 'corporate':
                $table      = 'transfer_hoteles';
                $idColumn   = 'id_hotel';
                $emailField = 'email_hotel';
                break;

            default: // web → viajero
                $table      = 'transfer_viajeros';
                $idColumn   = 'id_viajero';
                $emailField = 'email_viajero';
        }

        // Validación
        $request->validate([
            'nombre'   => ['required', 'string', 'max:255'],
            'email'    => [
                'required',
                'email',
                "unique:$table,$emailField," . $user->{$idColumn} . ",$idColumn",
            ],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        // Actualización de datos comunes
        $user->nombre        = $request->input('nombre');
        $user->{$emailField} = $request->input('email');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return redirect()
            ->route('profile.edit')
            ->with('status', 'Perfil actualizado correctamente.');
    }

    /**
     * Formulario para crear usuario corporativo (solo ADMIN, rúbrica).
     */

    public function createCorporate()
    {
        // Usuario administrador autenticado
        $admin = Auth::guard('admin')->user();
        $guard = 'admin';

        return view('profile.create_corporate', [
            'user' => $admin,          // <-- variable correcta
            'guard' => $guard,
            'showCorporateForm' => true
        ]);
    }

    /**
     * Guarda el nuevo usuario corporativo (Hotel) creado por el admin.
     */
    public function storeCorporate(Request $request)
    {
        $request->validate([
            'nombre'     => ['required', 'string', 'max:255'],
            'email_hotel'=> ['required', 'email', 'unique:transfer_hoteles,email_hotel'],
            'password'   => ['required', 'string', 'min:6', 'confirmed'],
            'id_zona'    => ['required', 'integer'],
            'Comision'   => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        Hotel::create([
            'nombre'      => $request->nombre,
            'email_hotel' => $request->email_hotel,
            'password'    => Hash::make($request->password),
            'id_zona'     => $request->id_zona,
            'Comision'    => $request->Comision,
        ]);

        return redirect()
            ->route('admin.profile.corporate.create')
            ->with('status', 'Usuario corporativo creado correctamente.');
    }
}
