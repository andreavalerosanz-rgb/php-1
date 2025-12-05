@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h4>Editar Reserva: Aeropuerto → Hotel</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('reserva.update', $reserva->id_reserva) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_owner" value="{{ $reserva->id_owner }}">
                    <input type="hidden" name="id_hotel" value="{{ $reserva->id_hotel }}">

                    {{-- Datos del Vuelo (Entrada) --}}
                    <h5 class="mb-3 text-primary"><i class="fas fa-plane"></i> Datos del Vuelo (Entrada)</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_entrada" class="form-label">Día de Entrada</label>
                            <input type="date" class="form-control" name="fecha_entrada"
                                   value="{{ old('fecha_entrada', $reserva->fecha_entrada) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="hora_entrada" class="form-label">Hora de Entrada</label>
                            <input type="time" class="form-control" name="hora_entrada"
                                   value="{{ old('hora_entrada', $reserva->hora_entrada) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="numero_vuelo_entrada" class="form-label">Número de Vuelo</label>
                            <input type="text" class="form-control" name="numero_vuelo_entrada"
                                   value="{{ old('numero_vuelo_entrada', $reserva->numero_vuelo_entrada) }}">
                        </div>
                    </div>

                    {{-- Pasajeros y Contacto --}}
                    <h5 class="mt-4 mb-3 text-primary"><i class="fas fa-user"></i> Datos del Cliente</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_owner" class="form-label">Nombre (ID Owner)</label>
                            <input type="text" class="form-control" value="{{ $reserva->owner->nombre ?? 'Sin propietario' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email_cliente" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email_cliente"
                                   value="{{ old('email_cliente', $reserva->email_cliente) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="num_viajeros" class="form-label">Número de Pasajeros</label>
                            <input type="number" class="form-control" name="num_viajeros"
                                   value="{{ old('num_viajeros', $reserva->num_viajeros) }}" min="1" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('mis_reservas') }}" class="btn btn-secondary btn-lg">Cancelar</a>
                        <button type="submit" class="btn btn-success btn-lg">Actualizar Reserva</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
