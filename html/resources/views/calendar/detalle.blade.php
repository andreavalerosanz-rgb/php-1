@extends('layouts.app')

@section('content')

<style>
.badge-tipo {
    padding: 4px 8px;
    border-radius: 6px;
    color: white;
    font-weight: 600;
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
@endphp


<div class="container py-4">

    <div class="card shadow rounded-3">
        <div class="card-header text-center py-3">
    
    <h2 class="fw-bold mb-2">
        Reserva #{{ $reserva->id_reserva }}
    </h2>

    <h5 class="text-muted mb-3">
        Localizador: <strong>{{ $reserva->localizador }}</strong>
    </h5>

    <span class="badge-tipo" style="{{ $tipoColor[$reserva->id_tipo_reserva] }}">
        {{ $tipoLabels[$reserva->id_tipo_reserva] }}
    </span>
</div>
        <div class="card-body p-4">

            @php
                $esIdaVuelta = $reserva->id_tipo_reserva == 3;
            @endphp

            @if($esIdaVuelta)

                <div class="row g-4">

                    {{-- IDA --}}
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-white">
                            <h4 class="fw-semibold mb-2">Ida</h4>

                            <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') }}</p>
                            <p><strong>Hora:</strong> {{ substr($reserva->hora_entrada,0,5) }}</p>

                            <p><strong>Número de vuelo:</strong> {{ $reserva->numero_vuelo_entrada }}</p>
                            <p><strong>Origen del vuelo:</strong> {{ $reserva->origen_vuelo_entrada }}</p>

                            <p><strong>Hotel:</strong> {{ $hotel ? $hotel->nombre : 'Sin hotel' }}</p>
                            <p><strong>Vehículo:</strong> {{ $vehiculo ? $vehiculo->descripcion : 'Sin vehículo' }}</p>
                        </div>
                    </div>

                    {{-- VUELTA --}}
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-white">
                            <h4 class="fw-semibold mb-2">Vuelta</h4>

                            <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reserva->fecha_vuelo_salida)->format('d/m/Y') }}</p>
                            <p><strong>Hora:</strong> {{ substr($reserva->hora_vuelo_salida,0,5) }}</p>

                            <p><strong>Número de vuelo:</strong> {{ $reserva->numero_vuelo_salida }}</p>
                            <p><strong>Origen del vuelo:</strong> {{ $reserva->origen_vuelo_salida }}</p>

                            <p><strong>Hotel:</strong> {{ $hotel ? $hotel->nombre : 'Sin hotel' }}</p>
                            <p><strong>Vehículo:</strong> {{ $vehiculo ? $vehiculo->descripcion : 'Sin vehículo' }}</p>
                        </div>
                    </div>

                    {{-- PASAJEROS --}}
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-white">
                            <h4 class="fw-semibold mb-2">Pasajeros</h4>

                            <p><strong>Email:</strong> {{ $reserva->email_cliente }}</p>
                            <p><strong>Viajeros:</strong> {{ $reserva->num_viajeros }}</p>
                        </div>
                    </div>

                </div>

            @else
            
                <div class="row g-4">

                    {{-- BLOQUE INFORMACIÓN --}}
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-white">

                            <h4 class="fw-semibold mb-3">Información del traslado</h4>

                            @if($reserva->id_tipo_reserva == 1)
                                {{-- SOLO IDA --}}
                                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') }}</p>
                                <p><strong>Hora:</strong> {{ substr($reserva->hora_entrada,0,5) }}</p>
                                <p><strong>Número vuelo:</strong> {{ $reserva->numero_vuelo_entrada }}</p>
                                <p><strong>Origen vuelo:</strong> {{ $reserva->origen_vuelo_entrada }}</p>
                            @endif


                            @if($reserva->id_tipo_reserva == 2)
                                {{-- SOLO VUELTA --}}
                                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reserva->fecha_vuelo_salida)->format('d/m/Y') }}</p>
                                <p><strong>Hora:</strong> {{ substr($reserva->hora_vuelo_salida,0,5) }}</p>
                                <p><strong>Número vuelo:</strong> {{ $reserva->numero_vuelo_salida }}</p>
                                <p><strong>Origen vuelo:</strong> {{ $reserva->origen_vuelo_salida }}</p>
                            @endif


                            <p><strong>Hotel:</strong> {{ $hotel ? $hotel->nombre : 'Sin hotel' }}</p>
                            <p><strong>Vehículo:</strong> {{ $vehiculo ? $vehiculo->descripcion : 'Sin vehículo' }}</p>

                        </div>
                    </div>


                    {{-- PASAJEROS --}}
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-white">
                            <h4 class="fw-semibold mb-3">Datos del pasajero</h4>

                            <p><strong>Email:</strong> {{ $reserva->email_cliente }}</p>
                            <p><strong>Viajeros:</strong> {{ $reserva->num_viajeros }}</p>
                        </div>
                    </div>

                </div>
            @endif


            <div class="text-center mt-4">
                <a href="{{ url('/calendario') }}" class="btn btn-success px-4 py-2">
                    Volver al calendario
                </a>
            </div>

        </div>

    </div>

</div>

@endsection
