<?php
// routes/web.php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransferController; 
use App\Http\Controllers\CalendarController;

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

// Calendario
Route::get('/calendario', [CalendarController::class, 'index'])->name('calendar.index');
Route::get('/calendario/events', [CalendarController::class, 'events'])->name('calendar.events');

// REGISTRO DE USUARIOS PARTICULARES (VIAJEROS)
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']); 

// FLUJO DE RESERVA DE TRASLADOS (Acceso público hasta el formulario, luego se usa Auth en el Controller)
Route::prefix('transfer')->group(function () {
    Route::get('/select-type', [TransferController::class, 'showTypeSelection'])->name('transfer.select-type');
    Route::post('/select-type', [TransferController::class, 'postTypeSelection'])->name('transfer.select-type.post');
    
    Route::get('/reserve/{type}', [TransferController::class, 'showReservationForm'])->name('transfer.reserve.form');
    
    Route::post('/confirm', [TransferController::class, 'confirmReservation'])->name('transfer.reserve.confirm')->middleware('auth:web,corporate');
}); // <-- CIERRE CORRECTO del grupo 'transfer'

// -----------------------------------------------------
// RUTAS PROTEGIDAS (Requieren Viajero, Corporativo o Admin logueado)
// -----------------------------------------------------

Route::middleware(['auth:admin,corporate,web'])->group(function () {

    // Logout (Usamos el guard que esté activo)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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