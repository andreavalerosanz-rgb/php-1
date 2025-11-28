<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransferController; 
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\MisReservasController;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\CorporateController; 
use App\Http\Controllers\DashboardController; 


// =========================================================================
// 1. RUTAS PÚBLICAS Y DE AUTENTICACIÓN
// =========================================================================

// Página de inicio (Front-End estático)
Route::get('/', function () {
    return view('home'); 
})->name('home');

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Registro (Usuarios Particulares y Corporativos)
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']); 

// Calendario (General / Producto 2)
Route::get('/calendario', [CalendarController::class, 'index'])->name('calendar.index');
Route::get('/calendario/events', [CalendarController::class, 'events'])->name('calendar.events');
Route::get('/calendario/reserva/{id}', [CalendarController::class, 'show'])->name('calendar.show');


// =========================================================================
// 2. FLUJO DE RESERVA DE TRASLADOS (Parte Pública)
// =========================================================================
// GET: Accesible para ver el formulario. 
// POST: Se protege más abajo en el grupo 'auth' para asegurar la sesión.

Route::prefix('transfer')->group(function () {
    Route::get('/select-type', [TransferController::class, 'showTypeSelection'])->name('transfer.select-type');
    Route::post('/select-type', [TransferController::class, 'postTypeSelection'])->name('transfer.select-type.post');
    
    Route::get('/reserve/{type}', [TransferController::class, 'showReservationForm'])->name('transfer.reserve.form');
}); 


// =========================================================================
// 3. RUTAS PROTEGIDAS (Requieren Viajero, Corporativo o Admin logueado)
// =========================================================================

Route::middleware(['auth:admin,corporate,web'])->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Redirección inteligente al dashboard correcto según el rol
    Route::get('/dashboard', [AuthController::class, 'redirectDashboard'])->name('dashboard');

    // --- CONFIRMACIÓN DE RESERVA (POST) ---
    // Protegida para obtener el ID del usuario logueado correctamente
    Route::post('transfer/confirm', [TransferController::class, 'confirmReservation'])
        ->name('transfer.reserve.confirm');


    // --- GESTIÓN DE "MIS RESERVAS" (CRUD General) ---
    Route::prefix('mis_reservas')->group(function() {
        Route::get('/', [MisReservasController::class, 'index'])->name('mis_reservas');
        Route::get('{id}/edit', [MisReservasController::class, 'edit'])->name('reserva.edit');
        Route::put('{id}', [MisReservasController::class, 'update'])->name('reserva.update');
        Route::delete('{id}', [MisReservasController::class, 'destroy'])->name('reserva.destroy');
    });


    // --- PANEL ADMINISTRADOR (AdminController + DashboardController) ---
    Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
        // Dashboard principal
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        
        // Funcionalidades P3:
        Route::get('/reservations/list', [AdminController::class, 'listReservations'])->name('reservations.list'); // Listado global
        Route::get('/commissions', [AdminController::class, 'showCommissions'])->name('commissions'); // Gestión de comisiones
    });


    // --- PANEL CORPORATIVO (CorporateController + DashboardController) ---
    Route::prefix('corporate')->name('corporate.')->middleware('auth:corporate')->group(function () {
        // Dashboard principal
        Route::get('/dashboard', [DashboardController::class, 'hotel'])->name('dashboard');
        
        // Funcionalidades P3:
        Route::get('/reservations/my', [CorporateController::class, 'listMyReservations'])->name('reservations.my'); // Reservas del hotel
        Route::get('/commissions/list', [CorporateController::class, 'listCommissions'])->name('commissions.list'); // Sus comisiones
    });
    

    // --- PANEL VIAJERO (DashboardController) ---
    // Dashboard principal
    Route::get('/user/dashboard', [DashboardController::class, 'user'])
        ->middleware('auth:web')
        ->name('user.dashboard');
    
    // Ruta adicional para listado específico (si se requiere aparte de mis_reservas)
    Route::get('/user/reservations', [TransferController::class, 'listUserReservations'])->name('user.reservations');
});