@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow-lg">
            <div class="card-header bg-danger text-white">
                <h4>Listado General de Reservas</h4>
            </div>
            <div class="card-body">
                <p>Mostrando {{ $reservas->total() }} reservas en total.</p>

                <table class="table table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Localizador</th>
                            <th>Tipo</th>
                            <th>Destino</th>
                            <th>Fecha/Hora Entrada</th>
                            <th>Pax</th>
                            <th>Precio Total</th>
                            <th>Comisión</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservas as $reserva)
                            <tr>
                                <td>{{ $reserva->localizador }}</td>
                                <td>{{ $reserva->id_tipo_reserva }}</td>
                                <td>{{ $reserva->hotel->nombre ?? 'N/A' }}</td>
                                <td>{{ $reserva->fecha_entrada ?? 'N/A' }} {{ $reserva->hora_entrada }}</td>
                                <td>{{ $reserva->num_viajeros }}</td>
                                <td>{{ number_format($reserva->precio_total, 2) }} €</td>
                                <td>{{ number_format($reserva->comision_ganada, 2) }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $reservas->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection