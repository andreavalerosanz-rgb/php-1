@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="alert alert-info text-center" role="alert">
            <h3>¡AUTENTICACIÓN CORPORATIVA EXITOSA!</h3>
        </div>
        <div class="card shadow-lg">
            <div class="card-header bg-info text-white">
                PANEL DE HOTEL (USUARIO CORPORATIVO)
            </div>
            <div class="card-body">
                {{-- Datos del Usuario Corporativo --}}
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">
                            Bienvenido/a, {{ Auth::guard('corporate')->user()->nombre }}
                        </h5>
                        <p class="card-text text-muted">
                            Email de Contacto: {{ Auth::guard('corporate')->user()->email_hotel }}
                        </p>
                    </div>
                    <span class="badge bg-secondary">Comisión: {{ Auth::guard('corporate')->user()->Comision }}%</span>
                </div>
                <hr>

                {{-- Sección de Estadísticas --}}
                <h4>Resumen de traslados hacia tu hotel</h4>
                <div class="alert alert-light border border-info text-center mt-3">
                    <h2 class="text-info fw-bold">{{ $stats['totalTraslados'] ?? 0 }}</h2>
                    <p class="mb-0">Reservas asociadas a tu alojamiento</p>
                </div>

                {{-- NUEVA SECCIÓN: CREAR RESERVAS (Requerimiento) --}}
                <div class="card mt-4 border-info">
                    <div class="card-header bg-light text-info fw-bold">
                        <i class="fas fa-plus-circle"></i> Nueva Reserva (Gestión para Huéspedes)
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-3">Seleccione el tipo de traslado para su cliente. El destino/origen se fijará automáticamente en <strong>{{ Auth::guard('corporate')->user()->nombre }}</strong>.</p>
                        
                        <div class="row g-2">
                            <div class="col-md-4">
                                <form method="POST" action="{{ route('transfer.select-type.post') }}">
                                    @csrf
                                    <input type="hidden" name="reservation_type" value="airport_to_hotel">
                                    <button type="submit" class="btn btn-outline-info w-100 py-3 h-100">
                                        <i class="fas fa-plane-arrival fa-2x mb-2 d-block"></i>
                                        Aeropuerto → Hotel
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <form method="POST" action="{{ route('transfer.select-type.post') }}">
                                    @csrf
                                    <input type="hidden" name="reservation_type" value="hotel_to_airport">
                                    <button type="submit" class="btn btn-outline-info w-100 py-3 h-100">
                                        <i class="fas fa-plane-departure fa-2x mb-2 d-block"></i>
                                        Hotel → Aeropuerto
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <form method="POST" action="{{ route('transfer.select-type.post') }}">
                                    @csrf
                                    <input type="hidden" name="reservation_type" value="round_trip">
                                    <button type="submit" class="btn btn-outline-info w-100 py-3 h-100">
                                        <i class="fas fa-exchange-alt fa-2x mb-2 d-block"></i>
                                        Ida y Vuelta
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Información adicional y enlaces --}}
                <div class="mt-4">
                    <p class="card-text text-muted small">
                        Rol: Hotel. Desde aquí puedes gestionar las reservas de tus clientes y revisar tu historial de comisiones.
                        <br>
                        ID de Zona Asignada: <strong>{{ Auth::guard('corporate')->user()->id_zona }}</strong>
                    </p>
                </div>
                
                <hr>
                
                <div class="d-flex gap-2">
                    <a href="{{ route('mis_reservas') }}" class="btn btn-primary">
                        <i class="fas fa-list"></i> Mis Reservas
                    </a>
                    <a href="{{ route('calendar.index') }}" class="btn btn-success">
                        <i class="fas fa-calendar-alt"></i> Calendario
                    </a>
                    {{-- Enlace placeholder para ajustes --}}
                    <button class="btn btn-secondary" disabled>Ajustes de Cuenta</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection