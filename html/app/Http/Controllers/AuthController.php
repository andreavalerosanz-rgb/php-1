<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Viajero; 
use App\Models\Hotel; 
use App\Models\Admin; 

class AuthController extends Controller
{
    // ------------------------------------------------------------------
    // VISTAS DE AUTENTICACIÓN
    // ------------------------------------------------------------------
    
    public function showLogin()
    {
        return view('login');
    }
    
    public function showRegister()
    {
        return view('register');
    }

    // ------------------------------------------------------------------
    // PROCESOS DE AUTENTICACIÓN (LOGIN)
    // ------------------------------------------------------------------
    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $email = $credentials['email'];
        $password = $credentials['password'];

        // 1. INTENTAR LOGIN COMO ADMINISTRADOR (Guarda 'admin')
        if (Auth::guard('admin')->attempt(['email_admin' => $email, 'password' => $password])) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        // 2. INTENTAR LOGIN COMO CORPORATIVO (Guarda 'corporate')
        if (Auth::guard('corporate')->attempt(['email_hotel' => $email, 'password' => $password])) {
            $request->session()->regenerate();
            return redirect()->intended(route('corporate.dashboard'));
        }
        
        // 3. INTENTAR LOGIN COMO VIAJERO (Guarda 'web')
        if (Auth::guard('web')->attempt(['email_viajero' => $email, 'password' => $password])) {
            $request->session()->regenerate();
            return redirect()->intended(route('user.dashboard'));
        }

        // SI TODOS FALLAN
        throw ValidationException::withMessages([
            'email' => __('Las credenciales proporcionadas no coinciden con nuestros registros.'),
        ]);
    }
    
    // ------------------------------------------------------------------
    // PROCESOS DE AUTENTICACIÓN (REGISTRO MULTI-ROL)
    // ------------------------------------------------------------------
    
    public function register(Request $request)
    {
        // 1. Validar los campos. Usamos reglas 'nullable' para campos que no todos los roles necesitan
        $request->validate([
            'role' => 'required|in:viajero,hotel',
            'nombre' => 'required|string|max:100', 
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|min:6|confirmed',
            
            // Campos de dirección (obligatorios para Viajero, por lo que deben ser 'required_if')
            'apellido1' => 'required_if:role,viajero|nullable|string|max:100', 
            'apellido2' => 'required_if:role,viajero|nullable|string|max:100',
            'direccion' => 'required_if:role,viajero|nullable|string|max:100',
            'codigoPostal' => 'required_if:role,viajero|nullable|string|max:100',
            'ciudad' => 'required_if:role,viajero|nullable|string|max:100',
            'pais' => 'required_if:role,viajero|nullable|string|max:100',
        ]);
        
        $email = $request->email;
        $password = Hash::make($request->password);
        $role = $request->role;
        $guard = 'web';

        if ($role === 'viajero') {
            // Comprobación de unicidad de email manual para el modelo Viajero
            if (Viajero::where('email_viajero', $email)->exists()) {
                 throw ValidationException::withMessages(['email' => 'Este email ya está registrado como Viajero.']);
            }
            
            // Crear el Viajero con todos los campos obligatorios del formulario
            $user = Viajero::create([
                'nombre' => $request->nombre,
                'email_viajero' => $email,
                'password' => $password,
                'apellido1' => $request->apellido1, 
                'apellido2' => $request->apellido2, 
                'direccion' => $request->direccion, 
                'codigoPostal' => $request->codigoPostal, 
                'ciudad' => $request->ciudad, 
                'pais' => $request->pais, 
            ]);

            $guard = 'web'; 
            
        } elseif ($role === 'hotel') {
            // Comprobación de unicidad de email manual para el modelo Hotel
            if (Hotel::where('email_hotel', $email)->exists()) {
                 throw ValidationException::withMessages(['email' => 'Este email ya está registrado como Hotel.']);
            }
            
            // Crear el Hotel
            $user = Hotel::create([
                'nombre' => $request->nombre, 
                'email_hotel' => $email,
                'password' => $password,
                'Comision' => 0,          
                'id_zona' => 1,           
            ]);

            $guard = 'corporate'; 
        }
        
        // Autenticar y redirigir
        Auth::guard($guard)->login($user);

        return $this->redirectDashboard(); 
    }

    // ------------------------------------------------------------------
    // LOGOUT Y REDIRECCIÓN
    // ------------------------------------------------------------------

    public function logout(Request $request)
    {
        // Forzamos el cierre de sesión en todos los guards
        Auth::guard('admin')->logout();
        Auth::guard('corporate')->logout();
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
    
    /**
     * Redirige al dashboard correcto al acceder a /dashboard.
     */
    public function redirectDashboard()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        if (Auth::guard('corporate')->check()) {
            return redirect()->route('corporate.dashboard');
        }
        if (Auth::guard('web')->check()) {
            return redirect()->route('user.dashboard');
        }
        // Fallback
        return redirect()->route('home');
    }
}