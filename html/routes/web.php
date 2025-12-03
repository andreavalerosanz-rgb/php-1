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
    // 3.B) ADMIN → CREAR USUARIOS CORPORATIVOS DESDE SU PERFIL
    // ---------------------------------------------------------------
    Route::prefix('admin/perfil')
        ->name('admin.profile.')
        ->middleware('auth:admin')
        ->group(function () {

            Route::get('/corporativo/crear', [ProfileController::class, 'createCorporate'])
                ->name('corporate.create');

            Route::post('/corporativo', [ProfileController::class, 'storeCorporate'])
                ->name('corporate.store');
        });

    // ---------------------------------------------------------------
    // 3.C) CONFIRMACIÓN RESERVA
    // ---------------------------------------------------------------
    Route::post('transfer/confirm', [TransferController::class, 'confirmReservation'])
        ->name('transfer.reserve.confirm');

    // ---------------------------------------------------------------
    // 3.D) MIS RESERVAS (CRUD)
    // ---------------------------------------------------------------
    Route::prefix('mis_reservas')->group(function () {
        Route::get('/', [MisReservasController::class, 'index'])->name('mis_reservas');
        Route::get('{id}/edit', [MisReservasController::class, 'edit'])->name('reserva.edit');
        Route::put('{id}', [MisReservasController::class, 'update'])->name('reserva.update');
        Route::delete('{id}', [MisReservasController::class, 'destroy'])->name('reserva.destroy');
    });

    // ---------------------------------------------------------------
    // 3.E) PANEL ADMIN
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
        });

    // ---------------------------------------------------------------
    // 3.F) PANEL CORPORATIVO
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
    // 3.G) PANEL VIAJERO
    // ---------------------------------------------------------------
    Route::get('/user/dashboard', [DashboardController::class, 'user'])
        ->middleware('auth:web')
        ->name('user.dashboard');

    Route::get('/user/reservations', [TransferController::class, 'listUserReservations'])
        ->name('user.reservations');
});
