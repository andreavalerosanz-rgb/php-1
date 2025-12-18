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

.badge-tipo {
    padding: .45rem .85rem;
    border-radius: 999px;
    color: white;
    font-weight: 700;
    font-size: .75rem;
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
    text-decoration: none; 
}
</style>

@php
    $tipoLabels = [
        1 => "Aeropuerto → Hotel",
        2 => "Hotel → Aeropuerto",
        3 => "Ida y Vuelta",
    ];

    $tipoColor = [
        1 => "background:#22c55e",
        2 => "background:#3b82f6",
        3 => "background:#f97316",
    ];

    $esIdaVuelta = $reserva->id_tipo_reserva == 3;
@endphp

<div class="dashboard-container">
    <div class="container">

        {{-- CABECERA --}}
        <div class="header-box">
            <h2>Reserva #{{ $reserva->id_reserva }}</h2>

            <div class="localizador mb-2">
                Localizador: <strong>{{ $reserva->localizador }}</strong>
            </div>

            <span class="badge-tipo" style="{{ $tipoColor[$reserva->id_tipo_reserva] }}">
                {{ $tipoLabels[$reserva->id_tipo_reserva] }}
            </span>
        </div>

        {{-- CONTENIDO --}}
        @if($esIdaVuelta)

            <div class="row g-4">

                {{-- IDA --}}
                <div class="col-md-4">
                    <div class="section-box">
                        <h4>Ida · Llegada</h4>

                        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') }}</p>
                        <p><strong>Hora:</strong> {{ substr($reserva->hora_entrada,0,5) }}</p>

                        <hr>

                        <p><strong>Vuelo:</strong> {{ $reserva->numero_vuelo_entrada }}</p>
                        <p><strong>Origen:</strong> {{ $reserva->origen_vuelo_entrada }}</p>

                        <hr>

                        <p><strong>Hotel:</strong> {{ $hotel?->nombre ?? 'Sin hotel' }}</p>
                        <p><strong>Vehículo:</strong> {{ $vehiculo?->descripcion ?? 'Sin vehículo' }}</p>
                    </div>
                </div>

                {{-- VUELTA --}}
                <div class="col-md-4">
                    <div class="section-box">
                        <h4>Vuelta · Salida</h4>

                        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reserva->fecha_vuelo_salida)->format('d/m/Y') }}</p>
                        <p><strong>Hora:</strong> {{ substr($reserva->hora_vuelo_salida,0,5) }}</p>

                        <hr>

                        <p><strong>Vuelo:</strong> {{ $reserva->numero_vuelo_salida }}</p>
                        <p><strong>Destino:</strong> {{ $reserva->origen_vuelo_salida }}</p>

                        <hr>

                        <p><strong>Hotel:</strong> {{ $hotel?->nombre ?? 'Sin hotel' }}</p>
                        <p><strong>Vehículo:</strong> {{ $vehiculo?->descripcion ?? 'Sin vehículo' }}</p>
                    </div>
                </div>

                {{-- PASAJEROS --}}
                <div class="col-md-4">
                    <div class="section-box">
                        <h4>Pasajeros</h4>

                        <p><strong>Email:</strong> {{ $reserva->email_cliente }}</p>
                        <p><strong>Número de viajeros:</strong> {{ $reserva->num_viajeros }}</p>
                    </div>
                </div>

            </div>

        @else

            <div class="row g-4">

                {{-- INFORMACIÓN TRASLADO --}}
                <div class="col-md-6">
                    <div class="section-box">
                        <h4>Información del traslado</h4>

                        @if($reserva->id_tipo_reserva == 1)
                            <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') }}</p>
                            <p><strong>Hora:</strong> {{ substr($reserva->hora_entrada,0,5) }}</p>
                            <hr>
                            <p><strong>Vuelo:</strong> {{ $reserva->numero_vuelo_entrada }}</p>
                            <p><strong>Origen:</strong> {{ $reserva->origen_vuelo_entrada }}</p>
                        @endif

                        @if($reserva->id_tipo_reserva == 2)
                            <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reserva->fecha_vuelo_salida)->format('d/m/Y') }}</p>
                            <p><strong>Hora:</strong> {{ substr($reserva->hora_vuelo_salida,0,5) }}</p>
                            <hr>
                            <p><strong>Vuelo:</strong> {{ $reserva->numero_vuelo_salida }}</p>
                            <p><strong>Destino:</strong> {{ $reserva->origen_vuelo_salida }}</p>
                        @endif

                        <hr>

                        <p><strong>Hotel:</strong> {{ $hotel?->nombre ?? 'Sin hotel' }}</p>
                        <p><strong>Vehículo:</strong> {{ $vehiculo?->descripcion ?? 'Sin vehículo' }}</p>
                    </div>
                </div>

                {{-- PASAJEROS --}}
                <div class="col-md-6">
                    <div class="section-box">
                        <h4>Datos del pasajero</h4>

                        <p><strong>Email:</strong> {{ $reserva->email_cliente }}</p>
                        <p><strong>Número de viajeros:</strong> {{ $reserva->num_viajeros }}</p>
                    </div>
                </div>

            </div>

        @endif

        {{-- BOTÓN VOLVER --}}
        <div class="text-center mt-4">
            @php
    $from = request()->query('from');
@endphp

<div class="text-center mt-4">
    @if ($from === 'mis_reservas')
        <a href="{{ route('mis_reservas') }}" class="back-btn">
            Volver a Mis reservas
        </a>
    @else
        <a href="{{ route('calendar.index') }}" class="back-btn">
            Volver al Calendario
        </a>
    @endif
</div>
        </div>

    </div>
</div>

@endsection