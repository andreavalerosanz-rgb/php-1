@extends('layouts.app')

@section('content')
<style>
    .dashboard-container { min-height: 100vh; padding: 2rem 1.5rem; }

    .glass-panel {
        background: rgba(255,255,255,.96);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,.6);
        border-radius: 1.25rem;
        box-shadow: 0 10px 30px rgba(15,23,42,.06);
        overflow: hidden;
    }

    .panel-header {
        background: linear-gradient(90deg,#0f9f9a,#0f766e);
        color: white;
        padding: 1.5rem 2rem;
    }

    .panel-header h4 {
        margin: 0;
        font-weight: 700;
    }

    .form-section {
        padding: 2rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .form-section:last-child { border-bottom: none; }

    .section-title {
        font-weight: 700;
        color: #0f766e;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: .5rem;
        font-size: 1.05rem;
    }

    .form-control,
    .form-select {
        border-radius: .75rem;
        border: 1px solid #e2e8f0;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0f766e;
        box-shadow: 0 0 0 .2rem rgba(15,118,110,.15);
    }

    .btn-cancel,
    .btn-confirm {
        border-radius: .75rem;
        font-weight: 700;
        padding: .75rem 2.25rem;
        font-size: 0.95rem;
        min-height: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-cancel {
        color: #475569;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
    }

    .btn-cancel:hover {
        background-color: #f1f5f9;
        color: #0f172a;
    }

    .btn-confirm {
        background-color: #0f766e;
        border: 1px solid #0f766e;
        color: #ffffff;
    }

    .btn-confirm:hover {
        background-color: #0d9488;
        border-color: #0d9488;
    }
</style>

<div class="dashboard-container">
    <div class="container-fluid px-lg-4">
        <div class="glass-panel">

            {{-- HEADER --}}
            <div class="panel-header">
                <h4>Editar Reserva: Aeropuerto → Hotel</h4>
            </div>

            <form method="POST" action="{{ route('reserva.update', $reserva->id_reserva) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="reservation_type" value="airport_to_hotel">

                {{-- DATOS DEL VUELO --}}
                <div class="form-section">
                    <div class="section-title">Datos del Vuelo (Llegada)</div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Aeropuerto de Origen</label>
                            <input type="text" class="form-control"
                                   name="origen_vuelo_entrada"
                                   value="{{ old('origen_vuelo_entrada', $reserva->origen_vuelo_entrada) }}"
                                   required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Número de Vuelo</label>
                            <input type="text" class="form-control"
                                   name="numero_vuelo_entrada"
                                   value="{{ old('numero_vuelo_entrada', $reserva->numero_vuelo_entrada) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Día de Llegada</label>
                            <input type="date"
       class="form-control"
       name="fecha_entrada"
       value="{{ old('fecha_entrada', $reserva->fecha_entrada) }}"
       min="{{ $minDate }}"
       required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hora de Llegada</label>
                            <input type="time" class="form-control"
                                   name="hora_entrada"
                                   value="{{ old('hora_entrada', $reserva->hora_entrada) }}"
                                   required>
                        </div>
                    </div>
                </div>

                {{-- DESTINO --}}
                <div class="form-section">
                    <div class="section-title">Destino</div>

                    <label class="form-label">Hotel Destino</label>

                    @if(Auth::guard('corporate')->check())
                        <input type="text" class="form-control bg-light"
                               value="{{ $hotels->first()->nombre }}" readonly>
                        <input type="hidden" name="id_hotel_destino"
                               value="{{ $hotels->first()->id_hotel }}">
                    @else
                        <select name="id_hotel_destino" class="form-select" required>
                            <option value="">-- Seleccione un hotel --</option>
                            @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id_hotel }}"
                                    @selected($hotel->id_hotel == $reserva->id_hotel)>
                                    {{ $hotel->nombre }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                {{-- VEHÍCULO Y PASAJEROS --}}
                <div class="form-section">
                    <div class="section-title">Vehículo y Pasajeros</div>

                    <div class="mb-3">
                        <label class="form-label">Vehículo</label>
                        <select name="id_vehiculo" class="form-select" required>
                            @foreach($vehiculos as $vehiculo)
                               <option value="{{ $vehiculo->id_vehiculo }}"
    @selected($vehiculo->id_vehiculo == $reserva->id_vehiculo)>
    {{ $vehiculo->descripcion }} — {{ number_format($vehiculo->precio, 2, ',', '.') }} €
</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Número de Pasajeros</label>
                        <input type="number" class="form-control"
                               name="num_viajeros"
                               value="{{ old('num_viajeros', $reserva->num_viajeros) }}"
                               min="1" required>
                    </div>
                </div>

                {{-- DATOS CONTACTO --}}
                <div class="form-section">
                    <div class="section-title">Datos del Contacto</div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control"
                                   name="nombre_contacto"
                                   value="{{ old('nombre_contacto', $reserva->nombre_contacto) }}"
                                   required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control"
                                   name="email_contacto"
                                   value="{{ old('email_contacto', $reserva->email_cliente) }}"
                                   required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-control"
                                   name="telefono"
                                   value="{{ old('telefono', $reserva->telefono) }}"
                                   required>
                        </div>
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="form-section d-flex justify-content-between align-items-center">
                    <a href="{{ route('mis_reservas') }}" class="btn btn-cancel">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-confirm">
                        Guardar Cambios
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection