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

    .panel-header h4 { margin: 0; font-weight: 700; }

    .form-section {
        padding: 2rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .form-section:last-child { border-bottom: none; }

    .section-title {
        font-weight: 700;
        color: #0f766e;
        margin-bottom: 1.25rem;
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
        min-height: 46px;
    }

    .btn-cancel {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
    }

    .btn-confirm {
        background-color: #0f766e;
        border: 1px solid #0f766e;
        color: white;
    }
</style>

<div class="dashboard-container">
<div class="container-fluid px-lg-4">
<div class="glass-panel">

{{-- HEADER --}}
<div class="panel-header">
    <h4>Editar Reserva: Ida y Vuelta</h4>
</div>
@if ($errors->any())
    <div class="p-3">
        <div class="alert alert-danger mb-0">
            <strong>Revisa el formulario:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif


<form method="POST" action="{{ route('reserva.update', $reserva->id_reserva) }}">
@csrf
@method('PUT')
<input type="hidden" name="reservation_type" value="round_trip">

{{-- IDA --}}
<div class="form-section">
<div class="section-title">IDA · Aeropuerto → Hotel</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Aeropuerto de Origen</label>
        <input type="text" class="form-control"
               name="origen_vuelo_entrada"
               value="{{ old('origen_vuelo_entrada', $reserva->origen_vuelo_entrada) }}"
               required>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Número de Vuelo</label>
        <input type="text" class="form-control"
       name="numero_vuelo_entrada"
       value="{{ old('numero_vuelo_entrada', $reserva->numero_vuelo_entrada) }}"
       required>

    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Día de Llegada</label>
        <input type="date" class="form-control"
               name="fecha_entrada"
       min="{{ $minDate }}"
       value="{{ old('fecha_entrada', $reserva->fecha_entrada) }}"
       required>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Hora de Llegada</label>
        <input type="time" class="form-control"
               name="hora_entrada"
               value="{{ old('hora_entrada', $reserva->hora_entrada) }}"
               required>
    </div>

    {{-- HOTEL DESTINO --}}
    <div class="col-md-8 mb-3">
        <label class="form-label">Hotel Destino</label>

        @if(Auth::guard('corporate')->check())
            <input type="text" class="form-control bg-light"
                   value="{{ $hotels->first()->nombre }}" readonly>
            <input type="hidden" name="id_hotel_destino"
                   value="{{ $hotels->first()->id_hotel }}">
        @else
            <select class="form-select" name="id_hotel_destino" id="id_hotel_destino" required>
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
</div>
</div>

{{-- VUELTA --}}
<div class="form-section">
<div class="section-title">VUELTA · Hotel → Aeropuerto</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Aeropuerto de Destino</label>
        <input type="text" class="form-control"
               name="origen_vuelo_salida"
               value="{{ old('origen_vuelo_salida', $reserva->origen_vuelo_salida) }}"
               required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Día de Salida</label>
        <input type="date" class="form-control"
              name="fecha_vuelo_salida"
       min="{{ $minDate }}"
       value="{{ old('fecha_vuelo_salida', $reserva->fecha_vuelo_salida) }}"
       required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Hora del Vuelo</label>
        <input type="time" class="form-control"
               name="hora_vuelo_salida"
               value="{{ old('hora_vuelo_salida', $reserva->hora_vuelo_salida) }}"
               required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Número de Vuelo</label>
        <input type="text" class="form-control"
            name="numero_vuelo_salida"
            value="{{ old('numero_vuelo_salida', $reserva->numero_vuelo_salida) }}"
            required>
    </div>


    <div class="col-md-6 mb-3">
        <label class="form-label">Hora de Recogida en Hotel</label>
        <input type="time" class="form-control"
               name="hora_recogida_hotel"
               value="{{ old('hora_recogida_hotel', $reserva->hora_recogida_hotel) }}"
               required>
               <small class="text-muted">
        Recomendado: 3–4 horas antes del vuelo.
    </small>
    </div>

    {{-- HOTEL RECOGIDA (SINCRONIZADO) --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Hotel de Recogida</label>
        <input type="text" id="hotel_recogida_nombre"
               class="form-control bg-light"
               value="{{ $reserva->hotel->nombre }}"
               readonly>
        <input type="hidden" name="id_hotel_recogida" id="id_hotel_recogida"
               value="{{ $reserva->id_hotel }}">
    </div>
</div>
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

{{-- CONTACTO --}}
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
<div class="form-section d-flex justify-content-between">
    <a href="{{ route('mis_reservas') }}" class="btn btn-cancel">Cancelar</a>
    <button class="btn btn-confirm">Guardar Cambios</button>
</div>

</form>
</div>
</div>
</div>

@if(!Auth::guard('corporate')->check())
<script>
document.addEventListener('DOMContentLoaded', function () {
    const destino = document.getElementById('id_hotel_destino');
    const recogidaNombre = document.getElementById('hotel_recogida_nombre');
    const recogidaId = document.getElementById('id_hotel_recogida');

    if (!destino) return;

    function syncHotel() {
        const opt = destino.options[destino.selectedIndex];
        recogidaNombre.value = opt.text;
        recogidaId.value = opt.value;
    }

    destino.addEventListener('change', syncHotel);
});
</script>
@endif

@endsection
