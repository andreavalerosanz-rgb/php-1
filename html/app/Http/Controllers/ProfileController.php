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

        // Base de validación (común)
        $rules = [
            'nombre'   => ['required', 'string', 'max:255'],
            'email'    => [
                'required',
                'email',
                "unique:$table,$emailField," . $user->{$idColumn} . ",$idColumn",
            ],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ];

        // Extra SOLO para viajero (web)
        if ($guard === 'web') {
            $rules = array_merge($rules, [
                'apellido1'     => ['nullable', 'string', 'max:255'],
                'apellido2'     => ['nullable', 'string', 'max:255'],
                'direccion'     => ['nullable', 'string', 'max:255'],
                'codigoPostal'  => ['nullable', 'string', 'max:20'],
                'ciudad'        => ['nullable', 'string', 'max:255'],
                'pais'          => ['nullable', 'string', 'max:255'],
            ]);
        }

        $validated = $request->validate($rules);

        // Actualización común
        $user->nombre        = $validated['nombre'];
        $user->{$emailField} = $validated['email'];

        // Actualización extra (viajero)
        if ($guard === 'web') {
            $user->apellido1    = $validated['apellido1']    ?? $user->apellido1;
            $user->apellido2    = $validated['apellido2']    ?? $user->apellido2;
            $user->direccion    = $validated['direccion']    ?? $user->direccion;
            $user->codigoPostal = $validated['codigoPostal'] ?? $user->codigoPostal;
            $user->ciudad       = $validated['ciudad']       ?? $user->ciudad;
            $user->pais         = $validated['pais']         ?? $user->pais;
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('profile.edit')
            ->with('status', 'Perfil actualizado correctamente.');
    }
}
