@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Mis Reservas</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Localizador</th>
                <th>Hotel</th>
                <th>Email Cliente</th>
                <th>Fecha Reserva</th>
                <th>Destino</th>
                <th>Num Viajeros</th>
                <th>Precio Total</th>
                <th>Comisión</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservas as $reserva)
            @php
                $reserva_fecha = \Carbon\Carbon::parse($reserva->fecha_reserva);
                $puede_modificar = $reserva_fecha->diffInHours($now, false) > 48 || $rol == 'admin';
            @endphp
            <tr>
                <td>{{ $reserva->id_reserva }}</td>
                <td>{{ $reserva->localizador }}</td>
                <td>{{ $reserva->hotel->nombre ?? 'Sin hotel' }}</td>
                <td>{{ $reserva->email_cliente }}</td>
                <td>{{ $reserva->fecha_reserva }}</td>
                <td>{{ $reserva->zona->descripcion ?? 'Sin zona' }}</td>
                <td>{{ $reserva->num_viajeros }}</td>
                <td>{{ $reserva->precio_total }} €</td>
                <td>{{ $reserva->comision_ganada }} €</td>
                <td>
                    @if($puede_modificar)
                        <a href="{{ route('reserva.edit', $reserva->id_reserva) }}" class="btn btn-sm btn-primary">Modificar</a>
                        <form action="{{ route('reserva.destroy', $reserva->id_reserva) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    @else
                        <span class="text-muted">No disponible</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
