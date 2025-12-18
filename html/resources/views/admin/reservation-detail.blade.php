@extends('layouts.app')

@section('content')

<style>
.dashboard-container {
    background: #ffffff;
    min-height: 100vh;
    padding: 2rem 1.5rem;
}

.section-box {
    background: rgba(255,255,255,.95);
    border-radius: 1rem;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 6px 18px rgba(15,23,42,.06);
    height: 100%;
}

.section-box h4 {
    color: #0f766e;
    font-weight: 700;
    margin-bottom: .75rem;
}

.section-box p {
    margin-bottom: .35rem;
    color: #334155;
}

.header-box {
    text-align: center;
    margin-bottom: 2rem;
}

.header-box h2 {
    font-weight: 800;
    margin-bottom: .25rem;
}

.header-box .localizador {
    color: #64748b;
    font-size: .95rem;
}

.back-btn {
    background: #0f766e;
    border: none;
    color: white;
    font-weight: 600;
    border-radius: .75rem;
    padding: .6rem 1.5rem;
    text-decoration: none;
    display: inline-block;
}

.back-btn:hover {
    background: #0d9488;
    color: white;
}
</style>

@php
    use Carbon\Carbon;

    $esIda        = $reserva->id_tipo_reserva == 1;
    $esVuelta     = $reserva->id_tipo_reserva == 2;
    $esIdaVuelta  = $reserva->id_tipo_reserva == 3;
@endphp

<div class="dashboard-container">
    <div class="container">

        {{-- CABECERA --}}
        <div class="header-box">
            <h2>Reserva #{{ $reserva->id_reserva }}</h2>
            <div class="localizador">
                Localizador: <strong>{{ $reserva->localizador }}</strong>
            </div>
        </div>

        <div class="row g-4">

            {{-- DATOS GENERALES --}}
            <div class="col-md-6">
                <div class="section-box">
                    <h4>Datos generales</h4>

                    <p><strong>Tipo de traslado:</strong> {{ $reserva->tipo_traslado_nombre }}</p>
                    <p><strong>Email cliente:</strong> {{ $reserva->email_cliente }}</p>
                    <p><strong>Número de viajeros:</strong> {{ $reserva->num_viajeros }}</p>
                    <p><strong>Zona:</strong>
    {{ $reserva->zona->descripcion
        ?? $reserva->hotel->zona->descripcion
        ?? 'Sin destino' }}
</p>
                    <p><strong>Hotel:</strong> {{ $reserva->hotel->nombre ?? 'Sin hotel' }}</p>
                    <p><strong>Vehículo:</strong> {{ $reserva->vehiculo->descripcion ?? 'Sin vehículo' }}</p>
                </div>
            </div>

            {{-- FECHAS Y VUELOS --}}
            <div class="col-md-6">
                <div class="section-box">
                    <h4>Fechas y vuelos</h4>

                    {{-- IDA --}}
                    @if($esIda || $esIdaVuelta)
                        <p><strong>Fecha del traslado:</strong>
                            {{ Carbon::parse($reserva->fecha_entrada)->format('d-m-Y') }}
                        </p>
                        <p><strong>Hora del traslado:</strong> {{ substr($reserva->hora_entrada, 0, 5) }}</p>
                        <p><strong>Nº vuelo:</strong> {{ $reserva->numero_vuelo_entrada }}</p>
                        <p><strong>Origen vuelo:</strong> {{ $reserva->origen_vuelo_entrada }}</p>
                    @endif

                    {{-- separador solo si hay ida y vuelta --}}
                    @if($esIdaVuelta)
                        <hr>
                    @endif

                    {{-- VUELTA --}}
                    @if($esVuelta || $esIdaVuelta)
                        <p><strong>Fecha vuelo salida:</strong>
                            {{ Carbon::parse($reserva->fecha_vuelo_salida)->format('d-m-Y') }}
                        </p>
                        <p><strong>Hora vuelo salida:</strong> {{ substr($reserva->hora_vuelo_salida, 0, 5) }}</p>
                        <p><strong>Nº vuelo salida:</strong> {{ $reserva->numero_vuelo_salida }}</p>
                        <p><strong>Origen vuelo salida:</strong> {{ $reserva->origen_vuelo_salida }}</p>
                        <p><strong>Hora recogida hotel:</strong> {{ $reserva->hora_recogida_hotel }}</p>
                    @endif
                </div>
            </div>

            {{-- INFORMACIÓN ECONÓMICA --}}
            <div class="col-md-6">
                <div class="section-box">
                    <h4>Información económica</h4>

                    <p><strong>Precio total:</strong> {{ number_format($reserva->precio_total, 2) }} €</p>
                    <p><strong>Comisión ganada:</strong> {{ number_format($reserva->comision_ganada, 2) }} €</p>
                    <p><strong>Comisión liquidada:</strong> {{ number_format($reserva->comision_liquidada, 2) }} €</p>
                </div>
            </div>

            {{-- CONTROL Y SISTEMA --}}
<div class="col-md-6">
    <div class="section-box">
        <h4>Control y sistema</h4>

        <p>
            <strong>Fecha de reserva:</strong>
            {{ Carbon::parse($reserva->fecha_reserva)->format('d-m-Y') }}
        </p>

        <p>
            <strong>Fecha de modificación:</strong>
            {{ Carbon::parse($reserva->fecha_modificacion)->format('d-m-Y') }}
        </p>

        <hr>

        <p>
    <strong>Reserva creada por:</strong>
    {{ $reserva->creador_etiqueta }} –
    "{{ $reserva->creador_nombre ?? 'No encontrado' }}"
</p>

<p>
    <strong>Asignada a:</strong>
    {{ $reserva->owner->nombre ?? 'No encontrado' }}
</p>
    </div>
</div>

        </div>

        {{-- BOTÓN VOLVER --}}
        <div class="text-center mt-4">
            <a href="{{ route('admin.reservations.list') }}" class="back-btn">
                Volver al listado
            </a>
        </div>

    </div>
</div>

@endsection