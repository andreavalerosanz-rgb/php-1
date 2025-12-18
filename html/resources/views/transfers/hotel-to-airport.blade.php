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
    line-height: 1.2;
    min-height: 46px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* Cancelar */
.btn-cancel {
    color: #475569;
    border: 1px solid #e2e8f0;
    background-color: #f8fafc;
}

.btn-cancel:hover {
    background-color: #f1f5f9;
    color: #0f172a;
}

/* Confirmar */
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
                <h4>Reservar Traslado: Hotel → Aeropuerto</h4>
            </div>

            <form method="POST" action="{{ route('transfer.reserve.confirm') }}">
                @csrf
                <input type="hidden" name="reservation_type" value="hotel_to_airport">

                {{-- ALERTAS --}}
                <div class="form-section">
                    @error('hotel')
                        <div class="alert alert-danger text-center">
                            {{ $message }}
                        </div>
                    @enderror

                    @if(!Auth::guard('admin')->check())
    <div class="alert alert-warning small mb-0">
        Reserva mínima con <strong>48h de antelación</strong>.
        Fecha mínima: <strong>{{ \Carbon\Carbon::parse($minDate)->format('d/m/Y') }}</strong>
    </div>
@endif
                </div>

                {{-- DATOS DEL VUELO --}}
                <div class="form-section">
                    <div class="section-title">Datos del Vuelo</div>

                    <div class="row">
                        {{-- Aeropuerto --}}
                        <div class="col-md-6 mb-3">
                            <label for="origen_vuelo_salida" class="form-label">Aeropuerto de Destino</label>
                            <input type="text"
                                   class="form-control @error('origen_vuelo_salida') is-invalid @enderror"
                                   id="origen_vuelo_salida"
                                   name="origen_vuelo_salida"
                                   placeholder="Ej: Madrid (MAD)"
                                   value="{{ old('origen_vuelo_salida') }}"
                                   required>
                            @error('origen_vuelo_salida')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Número de vuelo --}}
                        <div class="col-md-6 mb-3">
                            <label for="num_vuelo_salida" class="form-label">Número de Vuelo</label>
                            <input type="text"
                                   class="form-control @error('num_vuelo_salida') is-invalid @enderror"
                                   id="num_vuelo_salida"
                                   name="num_vuelo_salida"
                                   placeholder="Ej: VY6239"
                                   value="{{ old('num_vuelo_salida') }}"
                                   required>
                            @error('num_vuelo_salida')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Fecha --}}
                        <div class="col-md-6 mb-3">
                            <label for="fecha_vuelo_salida" class="form-label">Día del Vuelo</label>
                            <input type="date"
                                   class="form-control @error('fecha_vuelo_salida') is-invalid @enderror"
                                   id="fecha_vuelo_salida"
                                   name="fecha_vuelo_salida"
      min="{{ $minDate }}"
                                   required>
                            @error('fecha_vuelo_salida')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Hora --}}
                        <div class="col-md-6 mb-3">
                            <label for="hora_vuelo_salida" class="form-label">Hora de Salida del Vuelo</label>
                            <input type="time"
                                   class="form-control @error('hora_vuelo_salida') is-invalid @enderror"
                                   id="hora_vuelo_salida"
                                   name="hora_vuelo_salida"
                                   value="{{ old('hora_vuelo_salida') }}"
                                   required>
                            @error('hora_vuelo_salida')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- RECOGIDA --}}
                <div class="form-section">
                    <div class="section-title">Recogida</div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_hotel_recogida" class="form-label">Hotel de Recogida</label>

                            @if(Auth::guard('corporate')->check())
                                <input type="text" class="form-control bg-light"
                                       value="{{ $hotels->first()->nombre }}" readonly>
                                <input type="hidden" name="id_hotel_recogida"
                                       value="{{ $hotels->first()->id_hotel }}">
                            @else
                                <select class="form-select @error('id_hotel_recogida') is-invalid @enderror"
                                        id="id_hotel_recogida"
                                        name="id_hotel_recogida"
                                        required>
                                    <option value="">-- Seleccione el Hotel --</option>
                                    @foreach($hotels as $hotel)
                                        <option value="{{ $hotel->id_hotel }}"
                                            @if(old('id_hotel_recogida') == $hotel->id_hotel) selected @endif>
                                            {{ $hotel->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_hotel_recogida')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="hora_recogida" class="form-label">Hora de Recogida Estimada</label>
                            <input type="time"
                                   class="form-control @error('hora_recogida') is-invalid @enderror"
                                   id="hora_recogida"
                                   name="hora_recogida"
                                   value="{{ old('hora_recogida') }}"
                                   required>
                            <small class="text-muted">Recomendado: 3–4 horas antes del vuelo.</small>
                            @error('hora_recogida')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- VEHÍCULO Y PASAJEROS --}}
                <div class="form-section">
                    <div class="section-title">Vehículo y Pasajeros</div>

                    {{-- Vehículo --}}
                    <div class="mb-3">
                        <label for="id_vehiculo" class="form-label">Vehículo</label>
                        <select class="form-select @error('id_vehiculo') is-invalid @enderror"
                                name="id_vehiculo"
                                id="id_vehiculo"
                                required>
                            <option value="">-- Seleccione un vehículo --</option>
                            @foreach($vehiculos as $vehiculo)
                                <option value="{{ $vehiculo->id_vehiculo }}">
                                    {{ $vehiculo->descripcion }} — {{ $vehiculo->precio_final }} €
                                </option>
                            @endforeach
                        </select>
                        @error('id_vehiculo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Pasajeros --}}
                    <div class="mb-3">
                        <label for="pax" class="form-label">Número de Pasajeros</label>
                        <input type="number"
                               class="form-control @error('pax') is-invalid @enderror"
                               id="pax"
                               name="pax"
                               value="{{ old('pax', 1) }}"
                               min="1"
                               required>
                        @error('pax')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                    {{-- 3. Datos Personales --}}
                     <div class="form-section">
                    <div class="section-title">Datos del Contacto</div>
                    
                    {{-- Lógica robusta para obtener datos según el Guard activo --}}
                    @php
    // Por defecto, si hay errores anteriores, mantenerlos
    $nombre = old('nombre_contacto');
    $email = old('email_contacto');

    // Si es un viajero web, sí rellenamos automáticamente
    if (Auth::guard('web')->check()) {
        $u = Auth::guard('web')->user();
        $nombre = $u->nombre . ' ' . ($u->apellido1 ?? '');
        $email = $u->email_viajero;
    }

    // Si es hotel o admin, NO rellenamos nada.
    // Los datos deben ser siempre los del viajero seleccionado.
@endphp


                    @if(Auth::guard('corporate')->check() || Auth::guard('admin')->check())
    <div class="row mb-3">
        <div class="col-md-12">
            <label for="id_viajero" class="form-label">Asignar reserva al viajero</label>
            <select name="id_viajero" id="id_viajero" class="form-select @error('id_viajero') is-invalid @enderror" required>
                <option value="">-- Seleccione un viajero --</option>
                @foreach($viajeros as $v)
                    <option value="{{ $v->id_viajero }}">{{ $v->nombre }} {{ $v->apellido1 }} — {{ $v->email_viajero }}</option>
                @endforeach
            </select>
            @error('id_viajero')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
@endif

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="nombre_contacto" class="form-label">Nombre</label>
                            <input type="text" class="form-control @error('nombre_contacto') is-invalid @enderror" 
                                   id="nombre_contacto" name="nombre_contacto" value="{{ $nombre }}" required>
                            @error('nombre_contacto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="email_contacto" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email_contacto') is-invalid @enderror" 
                                   id="email_contacto" name="email_contacto" value="{{ $email }}" required>
                            @error('email_contacto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control @error('telefono') is-invalid @enderror" 
                                   id="telefono" name="telefono" value="{{ old('telefono') }}" required>
                            @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                                    </div>

                <div class="form-section d-flex justify-content-between align-items-center">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-cancel">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-confirm btn-lg">
                        Confirmar Reserva
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
