<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Viajero; 
use App\Models\Hotel; 
use App\Models\Admin; 
use Illuminate\Database\QueryException; 

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
    // Obtener todas las zonas para el selector de hoteles
    $zonas = \DB::table('transfer_zonas')->get();
    
    return view('register', compact('zonas'));
}
    // ------------------------------------------------------------------
    // PROCESOS DE AUTENTICACIÓN (LOGIN)
    // ------------------------------------------------------------------
    
    public function login(Request $request)
{
    // 0) Obtener los valores sin validar aún
    $email = $request->input('email');
    $password = $request->input('password');

    // 1) Comprobar si es un hotel y si está inactivo ANTES DE VALIDAR
    $hotel = Hotel::where('email_hotel', $email)->first();

    if ($hotel && $hotel->activo == 0) {
        return back()->withErrors([
            'auth_error' => 'Su usuario está inhabilitado. Contacte con la empresa para su gestión.',
        ])->withInput();
    }

    // 2) Validar credenciales
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $email = $credentials['email'];
    $password = $credentials['password'];

    // 3) Login admin
    if (Auth::guard('admin')->attempt(['email_admin' => $email, 'password' => $password])) {
        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
    }

    // 4) Login corporate (hotel)
    if (Auth::guard('corporate')->attempt(['email_hotel' => $email, 'password' => $password])) {
        $request->session()->regenerate();
        return redirect()->intended(route('corporate.dashboard'));
    }

    // 5) Login viajero
    if (Auth::guard('web')->attempt(['email_viajero' => $email, 'password' => $password])) {
        $request->session()->regenerate();
        return redirect()->intended(route('user.dashboard'));
    }

    // 6) Credenciales incorrectas
    throw ValidationException::withMessages([
        'email' => __('Las credenciales no coinciden. Inténtalo de nuevo'),
    ]);
}
    
    // ------------------------------------------------------------------
    // PROCESOS DE AUTENTICACIÓN (REGISTRO MULTI-ROL)
    // ------------------------------------------------------------------
    
public function register(Request $request)
{
    $baseRules = [
        'role' => 'required|in:viajero,hotel',
        'nombre' => 'required|string|max:100', 
        'email' => 'required|string|email|max:100',
        'password' => 'required|string|min:6|confirmed',
    ];

    if ($request->role === 'viajero') {
        $viajeroRules = [
            'apellido1' => 'required|string|max:100', 
            'apellido2' => 'required|string|max:100',
            'direccion' => 'required|string|max:100',
            'codigoPostal' => 'required|string|max:100',
            'ciudad' => 'required|string|max:100',
            'pais' => 'required|string|max:100',
        ];
        $request->validate(array_merge($baseRules, $viajeroRules));
    } else {
        $hotelRules = [
            'comision' => 'required|integer|min:0|max:100',
            'id_zona' => 'required|exists:transfer_zonas,id_zona',
        ];
        $request->validate(array_merge($baseRules, $hotelRules));
    }
    
    $email = $request->email;
    $password = Hash::make($request->password);
    $role = $request->role;

    try {
        if ($role === 'viajero') {
            // Comprobación de unicidad de email para Viajero
            if (Viajero::where('email_viajero', $email)->exists()) {
                throw ValidationException::withMessages(['email' => 'Este email ya está registrado como Viajero.']);
            }
            
            // Crear el Viajero
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
            // Comprobación de unicidad de email para Hotel
            if (Hotel::where('email_hotel', $email)->exists()) {
                throw ValidationException::withMessages(['email' => 'Este email ya está registrado como Hotel.']);
            }
            
            // Crear el Hotel
            $user = Hotel::create([
                'nombre' => $request->nombre, 
                'email_hotel' => $email,
                'password' => $password,
                'Comision' => $request->comision,          
                'id_zona' => $request->id_zona,           
            ]);

            $guard = 'corporate'; 
        }
        
        // Autenticar y redirigir
        Auth::guard($guard)->login($user);
        return $this->redirectDashboard(); 

    } catch (QueryException $e) {
        \Log::error('Error en registro: ' . $e->getMessage());
        
        $errorMessage = 'Error al registrar usuario. ';
        
        if ($e->getCode() == 23000) {
            $errorMessage .= 'Posible problema con las restricciones de base de datos.';
        } else {
            $errorMessage .= 'Error interno del sistema.';
        }
        
        throw ValidationException::withMessages([
            'registro_error' => $errorMessage . ' Detalles: ' . $e->getMessage()
        ]);
    } catch (ValidationException $e) {
        throw $e;
    } catch (\Exception $e) {
        \Log::error('Error inesperado en registro: ' . $e->getMessage());
        throw ValidationException::withMessages([
            'registro_error' => 'Error inesperado: ' . $e->getMessage()
        ]);
    }
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