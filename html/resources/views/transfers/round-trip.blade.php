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
                <h4>Reservar Traslado: Ida y Vuelta</h4>
            </div>

            <form method="POST" action="{{ route('transfer.reserve.confirm') }}">
                @csrf
                <input type="hidden" name="reservation_type" value="round_trip">

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

                {{-- IDA --}}
                <div class="form-section">
                    <div class="section-title">IDA · Aeropuerto → Hotel</div>

                    <div class="row">
    {{-- Aeropuerto de Origen --}}
    <div class="col-md-4 mb-3">
        <label for="origen_vuelo_entrada" class="form-label">Aeropuerto de Origen</label>
        <input type="text"
               name="origen_vuelo_entrada"
               id="origen_vuelo_entrada"
               class="form-control @error('origen_vuelo_entrada') is-invalid @enderror"
               placeholder="Ej: Madrid Barajas (MAD)"
               value="{{ old('origen_vuelo_entrada') }}"
               required>
        @error('origen_vuelo_entrada')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Nº Vuelo --}}
    <div class="col-md-4 mb-3">
        <label for="num_vuelo_ida" class="form-label">Nº Vuelo</label>
        <input type="text"
               class="form-control"
               id="num_vuelo_ida"
               name="num_vuelo_ida"
               placeholder="Ej: VY6239"
               value="{{ old('num_vuelo_ida') }}"
               required>
    </div>

    {{-- Día Llegada --}}
    <div class="col-md-4 mb-3">
        <label for="fecha_llegada" class="form-label">Día de Llegada</label>
        <input type="date"
               class="form-control"
               id="fecha_llegada"
                name="fecha_llegada"
       min="{{ $minDate }}"
               required>
    </div>

    {{-- Hora Llegada --}}
    <div class="col-md-4 mb-3">
        <label for="hora_llegada" class="form-label">Hora de Llegada</label>
        <input type="time"
               class="form-control"
               id="hora_llegada"
               name="hora_llegada"
               value="{{ old('hora_llegada') }}"
               required>
    </div>

    {{-- Hotel Destino (ALINEADO) --}}
    <div class="col-md-8 mb-3">
        <label for="id_hotel_destino" class="form-label">Hotel Destino</label>

        @if(Auth::guard('corporate')->check())
            <input type="text"
                   class="form-control bg-light"
                   value="{{ $hotels->first()->nombre }}"
                   readonly>
            <input type="hidden"
                   name="id_hotel_destino"
                   value="{{ $hotels->first()->id_hotel }}">
        @else
            <select class="form-select"
                    id="id_hotel_destino"
                    name="id_hotel_destino"
                    required>
                <option value="">-- Seleccione un Hotel --</option>
                @foreach($hotels as $hotel)
                    <option value="{{ $hotel->id_hotel }}"
                        @if(old('id_hotel_destino') == $hotel->id_hotel) selected @endif>
                        {{ $hotel->nombre }}
                    </option>
                @endforeach
            </select>
        @endif
    </div>
</div>

                </div>

                {{-- VUELTA --}}
                <div class="form-section">
    <div class="section-title">VUELTA · Hotel → Aeropuerto</div>

    <div class="row">
        {{-- Aeropuerto destino --}}
        <div class="col-md-6 mb-3">
            <label for="origen_vuelo_salida" class="form-label">Aeropuerto de Destino</label>
            <input type="text"
                   class="form-control"
                   id="origen_vuelo_salida"
                   name="origen_vuelo_salida"
                   placeholder="Ej: Barcelona - El Prat (BCN)"
                   value="{{ old('origen_vuelo_salida') }}"
                   required>
        </div>

        {{-- Fecha salida --}}
        <div class="col-md-6 mb-3">
            <label for="fecha_vuelo_salida" class="form-label">Día de Salida</label>
            <input type="date"
                   class="form-control"
                   id="fecha_vuelo_salida"
                   name="fecha_vuelo_salida"
       min="{{ $minDate }}"
                   required>
        </div>

        {{-- Hora vuelo --}}
        <div class="col-md-6 mb-3">
            <label for="hora_vuelo_salida" class="form-label">Hora del Vuelo</label>
            <input type="time"
                   class="form-control"
                   id="hora_vuelo_salida"
                   name="hora_vuelo_salida"
                   required>
        </div>

        {{-- Número vuelo --}}
        <div class="col-md-6 mb-3">
            <label for="num_vuelo_salida" class="form-label">Número de Vuelo</label>
            <input type="text"
                   class="form-control @error('num_vuelo_salida') is-invalid @enderror"
                   id="num_vuelo_salida"
                   name="num_vuelo_salida"
                   placeholder="Ej: VY6240"
                   value="{{ old('num_vuelo_salida') }}"
                   required>
            @error('num_vuelo_salida')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- HOTEL RECOGIDA (izquierda) --}}
        <div class="col-md-6 mb-3">
            <label for="id_hotel_recogida" class="form-label">Hotel de Recogida</label>

            @if(Auth::guard('corporate')->check())
                <input type="text"
                       class="form-control bg-light"
                       value="{{ $hotels->first()->nombre }}"
                       readonly>
                <input type="hidden"
                       name="id_hotel_recogida"
                       value="{{ $hotels->first()->id_hotel }}">
            @else
                <input type="text"
                       class="form-control bg-light fst-italic text-muted"
                       id="hotel_recogida_nombre"
                       value="Será el mismo que el de destino"
                       readonly>
                <input type="hidden"
                       name="id_hotel_recogida"
                       id="id_hotel_recogida">
            @endif
        </div>

        {{-- HORA RECOGIDA (derecha) --}}
        <div class="col-md-6 mb-3">
            <label for="hora_recogida_vuelta" class="form-label">Hora de Recogida en Hotel</label>
            <input type="time"
                   class="form-control @error('hora_recogida_vuelta') is-invalid @enderror"
                   id="hora_recogida_vuelta"
                   name="hora_recogida_vuelta"
                   value="{{ old('hora_recogida_vuelta') }}"
                   required>
            <small class="text-muted">Recomendado: 3–4 horas antes del vuelo.</small>
            @error('hora_recogida_vuelta')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

                {{-- VEHÍCULO Y PASAJEROS --}}
                
                <div class="form-section">
                    <div class="section-title">Vehículo y Pasajeros</div>

                    <div class="mb-3">
                        <label for="id_vehiculo" class="form-label">Vehículo</label>
                        <select class="form-select @error('id_vehiculo') is-invalid @enderror"
                                name="id_vehiculo"
                                id="id_vehiculo"
                                required>
                            <option value="">-- Seleccione un vehículo --</option>
                            @foreach($vehiculos as $vehiculo)
                                <option value="{{ $vehiculo->id_vehiculo }}">
                                    {{ $vehiculo->descripcion }} — {{ $vehiculo->precio }} €
                                </option>
                            @endforeach
                        </select>
                        @error('id_vehiculo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

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

                {{-- DATOS CONTACTO --}}
                <div class="form-section">
                    <div class="section-title">Datos del Contacto</div>

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
                            <input type="text" class="form-control" id="nombre_contacto" name="nombre_contacto" value="{{ $nombre }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="email_contacto" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email_contacto" name="email_contacto" value="{{ $email }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}" required>
                        </div>
                    </div>

                   <div class="form-section d-flex justify-content-between align-items-center">
                    <a href="{{ route('dashboard') }}" class="btn btn-cancel">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-confirm">
                        Confirmar Reserva
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fechaIda = document.getElementById('fecha_llegada');
    const fechaVuelta = document.getElementById('fecha_vuelo_salida');

    if (!fechaIda || !fechaVuelta) return;

    function syncFechaVuelta() {
        const idaValue = fechaIda.value;

        if (!idaValue) return;

        // La vuelta NO puede ser anterior a la ida
        fechaVuelta.min = idaValue;

        // Si ya había una fecha de vuelta anterior → resetear
        if (fechaVuelta.value && fechaVuelta.value < idaValue) {
            fechaVuelta.value = idaValue;
        }
    }

    // Al cambiar la IDA
    fechaIda.addEventListener('change', syncFechaVuelta);

    // Al cargar la página (por si hay old())
    syncFechaVuelta();
});
</script>

@if(!Auth::guard('corporate')->check())
<script>
document.addEventListener('DOMContentLoaded', function () {
    const destinoSelect = document.getElementById('id_hotel_destino');
    const recogidaInput = document.getElementById('hotel_recogida_nombre');
    const recogidaHidden = document.getElementById('id_hotel_recogida');

    if (!destinoSelect || !recogidaInput || !recogidaHidden) return;

    function syncHotelRecogida() {
        const selectedOption = destinoSelect.options[destinoSelect.selectedIndex];

        // Si no hay hotel seleccionado
        if (!selectedOption || selectedOption.value === '') {
            recogidaInput.value = 'Será el mismo que el de destino';
            recogidaInput.classList.add('fst-italic', 'text-muted');
            recogidaHidden.value = '';
            return;
        }

        // Hotel seleccionado → reflejar nombre real
        recogidaInput.value = selectedOption.text;
        recogidaInput.classList.remove('fst-italic', 'text-muted');
        recogidaHidden.value = selectedOption.value;
    }

    // Inicializar al cargar (por si hay old())
    syncHotelRecogida();

    // Escuchar cambios
    destinoSelect.addEventListener('change', syncHotelRecogida);
});
</script>
@endif

@endsection