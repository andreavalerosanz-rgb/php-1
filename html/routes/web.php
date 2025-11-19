<?php
// routes/web.php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


// -----------------------------------------------------
// RUTAS PÚBLICAS
// -----------------------------------------------------

// Página de inicio (Front-End estático)
Route::get('/', function () {
    return view('home'); 
})->name('home');

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// REGISTRO DE USUARIOS PARTICULARES (VIAJEROS)
Route::get('/register', [AuthController::class, 'showRegister'])->name('register'); // Muestra el formulario
Route::post('/register', [AuthController::class, 'register']); // Procesa el registro

// -----------------------------------------------------
// RUTAS PROTEGIDAS
// -----------------------------------------------------

// Logout (Usamos el guard que esté activo)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Paneles
// Nota: Utilizamos middleware que chequea si el usuario está autenticado en CUALQUIERA de los guards.
Route::middleware(['auth:admin,corporate,web'])->group(function () {

    // Redirige al panel correcto basado en el guard activo (Controlador)
    Route::get('/dashboard', [AuthController::class, 'redirectDashboard'])->name('dashboard');

    // Panel Administrador (Protegido por el guard 'admin')
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->middleware('auth:admin')->name('admin.dashboard');

    // Panel Corporativo (Protegido por el guard 'corporate')
    Route::get('/corporate/dashboard', function () {
        return view('corporate.dashboard');
    })->middleware('auth:corporate')->name('corporate.dashboard');
    
    // Panel de Viajero (Protegido por el guard 'web' - por defecto)
    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->middleware('auth:web')->name('user.dashboard');
});