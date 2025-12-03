@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">

        {{-- Mensaje de Bienvenida --}}
        <div class="alert alert-danger text-center shadow-sm" role="alert">
            <h4 class="alert-heading mb-0"><i class="fas fa-user-shield"></i> Panel de Administración</h4>
        </div>

        <div class="card shadow-lg mb-4">
            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                <span class="h5 mb-0">Bienvenido, {{ Auth::guard('admin')->user()->nombre }}</span>
                <span class="badge bg-light text-danger">Rol: Administrador</span>
            </div>
            <div class="card-body">
                <p class="card-text text-muted">Desde aquí gestionas la plataforma global: reservas, tarifas, hoteles y liquidación de comisiones.</p>
                <hr>

                {{-- 1. SECCIÓN DE ESTADÍSTICAS (Tu código original) --}}
                <h5 class="text-danger mb-3"><i class="fas fa-chart-line"></i> Resumen del Sistema</h5>
                <div class="row text-center mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-light border rounded h-100 shadow-sm">
                            <h2 class="text-danger">{{ $stats['reservasTotales'] ?? 0 }}</h2>
                            <p class="mb-0 fw-bold">Reservas Totales</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-light border rounded h-100 shadow-sm">
                            <h2 class="text-primary">{{ $stats['viajerosTotales'] ?? 0 }}</h2>
                            <p class="mb-0 fw-bold">Viajeros Totales</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-light border rounded h-100 shadow-sm">
                            <h2 class="text-success">{{ $stats['hotelesTotales'] ?? 0 }}</h2>
                            <p class="mb-0 fw-bold">Hoteles Registrados</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-light border rounded h-100 shadow-sm">
                            <h2 class="text-secondary">{{ $stats['usuariosTotales'] ?? 0 }}</h2>
                            <p class="mb-0 fw-bold">Usuarios Totales</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- 2. NUEVA SECCIÓN: CREAR RESERVAS (Requerimiento) --}}
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 border-danger">
                            <div class="card-header bg-danger text-white fw-bold">
                                <i class="fas fa-plus-circle"></i> Nueva Reserva Manual
                            </div>
                            <div class="card-body">
                                <p class="small text-muted">Crear una reserva en nombre de un cliente:</p>
                                <div class="d-grid gap-2">
                                    {{-- Botón 1: Aeropuerto -> Hotel --}}
                                    <form method="POST" action="{{ route('transfer.select-type.post') }}">
                                        @csrf <input type="hidden" name="reservation_type" value="airport_to_hotel">
                                        <button class="btn btn-outline-danger w-100 text-start">
                                            <i class="fas fa-plane-arrival"></i> Aeropuerto → Hotel
                                        </button>
                                    </form>

                                    {{-- Botón 2: Hotel -> Aeropuerto --}}
                                    <form method="POST" action="{{ route('transfer.select-type.post') }}">
                                        @csrf <input type="hidden" name="reservation_type" value="hotel_to_airport">
                                        <button class="btn btn-outline-danger w-100 text-start">
                                            <i class="fas fa-plane-departure"></i> Hotel → Aeropuerto
                                        </button>
                                    </form>

                                    {{-- Botón 3: Ida y Vuelta --}}
                                    <form method="POST" action="{{ route('transfer.select-type.post') }}">
                                        @csrf <input type="hidden" name="reservation_type" value="round_trip">
                                        <button class="btn btn-outline-danger w-100 text-start">
                                            <i class="fas fa-exchange-alt"></i> Ida y Vuelta
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. SECCIÓN DE GESTIÓN (Enlaces actualizados) --}}
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-secondary text-white fw-bold">
                                <i class="fas fa-cogs"></i> Gestión y Reportes
                            </div>
                            <div class="card-body d-grid gap-2 align-content-center">
                                {{-- Enlace a Comisiones (Ruta nueva del AdminController) --}}
                                <a href="{{ route('admin.commissions') }}" class="btn btn-danger">
                                    <i class="fas fa-euro-sign"></i> Gestión de Comisiones
                                </a>
{{-- Gestión de Hoteles --}}
<a href="{{ route('admin.hoteles.index') }}" class="btn btn-primary">
    <i class="fas fa-hotel"></i> Gestión de Hoteles
</a>

                                {{-- Enlace al Listado Global de Reservas (Ruta nueva del AdminController) --}}
                                <a href="{{ route('admin.reservations.list') }}" class="btn btn-dark">
                                    <i class="fas fa-list"></i> Listado Global de Reservas
                                </a>

                                {{-- Enlace a Mis Reservas (Reservas hechas por el admin) --}}
                                <a href="{{ route('mis_reservas') }}" class="btn btn-secondary">
                                    <i class="fas fa-user-clock"></i> Mis Reservas Creadas
                                </a>

                                {{-- Ajustes de cuenta / Perfil --}}
                                <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-user-cog"></i> Ajustes de Cuenta
                                </a>

                                {{-- Enlace al Calendario --}}
                                <a href="{{ route('calendar.index') }}" class="btn btn-success">
                                    <i class="fas fa-calendar-alt"></i> Ver Calendario
                                </a>

                                {{-- Enlace a Gestión de Vehículos --}}
                                <a href="{{ route('admin.vehiculos.index') }}" class="btn btn-warning">
                                    <i class="fas fa-car"></i> Gestión de Vehículos
                                </a>
                            </div>
                        </div>
                    </div>
                </div> {{-- Fin Row --}}

            </div>
        </div>
    </div>
</div>
@endsection
