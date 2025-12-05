<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\MisReservasController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CorporateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehiculosController;

// =====================================================================
// 1. RUTAS PÚBLICAS Y DE AUTENTICACIÓN
// =====================================================================

// Home
Route::get('/', function () {
    return view('home');
})->name('home');

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Registro (viajero + corporativo)
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Calendario
Route::get('/calendario', [CalendarController::class, 'index'])->name('calendar.index');
Route::get('/calendario/events', [CalendarController::class, 'events'])->name('calendar.events');
Route::get('/calendario/reserva/{id}', [CalendarController::class, 'show'])->name('calendar.show');


// =====================================================================
// 2. FLUJO DE RESERVA (parte pública)
// =====================================================================
Route::prefix('transfer')->group(function () {
    Route::get('/select-type', [TransferController::class, 'showTypeSelection'])
        ->name('transfer.select-type');

    Route::post('/select-type', [TransferController::class, 'postTypeSelection'])
        ->name('transfer.select-type.post');

    Route::get('/reserve/{type}', [TransferController::class, 'showReservationForm'])
        ->name('transfer.reserve.form');
});


// =====================================================================
// 3. RUTAS PROTEGIDAS (requieren login con cualquier rol)
// =====================================================================
Route::middleware(['auth:admin,corporate,web'])->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Redirección al dashboard según rol
    Route::get('/dashboard', [AuthController::class, 'redirectDashboard'])->name('dashboard');

    // ---------------------------------------------------------------
    // 3.A) PERFIL (común para Admin, Hotel y Viajero)
    // ---------------------------------------------------------------
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');

    // ---------------------------------------------------------------
    // 3.B) CONFIRMACIÓN RESERVA
    // ---------------------------------------------------------------
    Route::post('transfer/confirm', [TransferController::class, 'confirmReservation'])
        ->name('transfer.reserve.confirm');

    // ---------------------------------------------------------------
    // 3.C) MIS RESERVAS (CRUD)
    // ---------------------------------------------------------------
    Route::prefix('mis_reservas')->group(function () {
        Route::get('/', [MisReservasController::class, 'index'])->name('mis_reservas');
        Route::get('{id}/edit', [MisReservasController::class, 'edit'])->name('reserva.edit');
        Route::put('{id}', [MisReservasController::class, 'update'])->name('reserva.update');
        Route::delete('{id}', [MisReservasController::class, 'destroy'])->name('reserva.destroy');
    });

    // ---------------------------------------------------------------
    // 3.D) PANEL ADMIN
    // ---------------------------------------------------------------
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('auth:admin')
        ->group(function () {

            Route::get('/dashboard', [DashboardController::class, 'admin'])
                ->name('dashboard');

            Route::get('/reservations/list', [AdminController::class, 'listReservations'])
                ->name('reservations.list');

            Route::get('/commissions', [AdminController::class, 'showCommissions'])
                ->name('commissions');

            // Rutas para la gestión de vehículos
            Route::get('vehiculos', [VehiculosController::class, 'index'])
                ->name('vehiculos.index');

            Route::get('vehiculos/crear', [VehiculosController::class, 'create'])
                ->name('vehiculos.create');

            Route::post('vehiculos', [VehiculosController::class, 'store'])
                ->name('vehiculos.store');

            Route::get('vehiculos/{id}/editar', [VehiculosController::class, 'edit'])
                ->name('vehiculos.edit');

            Route::put('vehiculos/{id}', [VehiculosController::class, 'update'])
                ->name('vehiculos.update');

            Route::delete('vehiculos/{id}', [VehiculosController::class, 'destroy'])
                ->name('vehiculos.destroy');
            
            
// ---------------------------------------------------------------
// 3.D.2) ADMIN → GESTIÓN DE HOTELES
// ---------------------------------------------------------------
Route::prefix('hoteles')->name('hoteles.')->group(function () {

    // Listado de hoteles
    Route::get('/', [App\Http\Controllers\AdminHotelController::class, 'index'])
        ->name('index');

    // Formulario de creación
    Route::get('/crear', [App\Http\Controllers\AdminHotelController::class, 'create'])
        ->name('create');

    // Guardar nuevo hotel
    Route::post('/crear', [App\Http\Controllers\AdminHotelController::class, 'store'])
        ->name('store');

         // Inhabilitar hotel
    Route::put('/{id}/inhabilitar', [App\Http\Controllers\AdminHotelController::class, 'disable'])
        ->name('disable');

    // Habilitar hotel
    Route::put('/{id}/habilitar', [App\Http\Controllers\AdminHotelController::class, 'enable'])
        ->name('enable');
});
        });

    // ---------------------------------------------------------------
    // 3.E) PANEL CORPORATIVO
    // ---------------------------------------------------------------
    Route::prefix('corporate')
        ->name('corporate.')
        ->middleware('auth:corporate')
        ->group(function () {

            Route::get('/dashboard', [DashboardController::class, 'hotel'])
                ->name('dashboard');

            Route::get('/reservations/my', [CorporateController::class, 'listMyReservations'])
                ->name('reservations.my');

            Route::get('/comissions', [CorporateController::class, 'commissions'])
                ->name('comissions');
        });

    // ---------------------------------------------------------------
    // 3.F) PANEL VIAJERO
    // ---------------------------------------------------------------
    Route::get('/user/dashboard', [DashboardController::class, 'user'])
        ->middleware('auth:web')
        ->name('user.dashboard');

    Route::get('/user/reservations', [TransferController::class, 'listUserReservations'])
        ->name('user.reservations');
});
