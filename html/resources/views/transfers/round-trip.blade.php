@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-lg">
            <div class="card-header bg-success text-white text-center">
                <h4>Reservar Traslado: Ida y Vuelta</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('transfer.reserve.confirm') }}">
                    @csrf
                    <input type="hidden" name="reservation_type" value="round_trip">

                    @error('hotel')
                        <div class="alert alert-danger text-center">{{ $message }}</div>
                    @enderror

                    <div class="alert alert-warning small">
                        <i class="fas fa-clock"></i> **Nota:** La reserva debe realizarse con al menos **48 horas de antelación**.
                    </div>
                    
                    {{-- SECCIÓN IDA --}}
                    <h4 class="mb-4 text-success"><i class="fas fa-road"></i> IDA: Aeropuerto → Hotel</h4>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="fecha_llegada" class="form-label">Día de Llegada</label>
                            <input type="date" class="form-control" id="fecha_llegada" name="fecha_llegada" value="{{ old('fecha_llegada') }}" min="{{ Carbon\Carbon::parse($minDate)->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="hora_llegada" class="form-label">Hora Llegada</label>
                            <input type="time" class="form-control" id="hora_llegada" name="hora_llegada" value="{{ old('hora_llegada') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="num_vuelo_ida" class="form-label">Nº Vuelo (Ida)</label>
                            <input type="text" class="form-control" id="num_vuelo_ida" name="num_vuelo_ida" value="{{ old('num_vuelo_ida') }}" required>
                        </div>
                    </div>

                    {{-- HOTEL DESTINO (IDA) --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_hotel_destino" class="form-label">Hotel Destino (Ida)</label>
                            
                            {{-- LOGICA CORPORATIVA --}}
                            @if(Auth::guard('corporate')->check())
                                <input type="text" class="form-control bg-light" value="{{ $hotels->first()->nombre }}" readonly>
                                <input type="hidden" name="id_hotel_destino" value="{{ $hotels->first()->id_hotel }}">
                            @else
                                <select class="form-select" id="id_hotel_destino" name="id_hotel_destino" required>
                                    <option value="">Seleccione un hotel</option>
                                    @foreach($hotels as $hotel)
                                        <option value="{{ $hotel->id_hotel }}" @if(old('id_hotel_destino') == $hotel->id_hotel) selected @endif>{{ $hotel->nombre }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>
                    
                    <hr class="my-5">

                    {{-- SECCIÓN VUELTA --}}
                    <h4 class="mb-4 text-warning"><i class="fas fa-car-side"></i> VUELTA: Hotel → Aeropuerto</h4>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_vuelo_salida" class="form-label">Día Salida</label>
                            <input type="date" class="form-control" id="fecha_vuelo_salida" name="fecha_vuelo_salida" value="{{ old('fecha_vuelo_salida') }}" min="{{ Carbon\Carbon::parse($minDate)->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="hora_vuelo_salida" class="form-label">Hora Vuelo Salida</label>
                            <input type="time" class="form-control" id="hora_vuelo_salida" name="hora_vuelo_salida" value="{{ old('hora_vuelo_salida') }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_hotel_recogida" class="form-label">Hotel de Recogida</label>
                            
                            {{-- LOGICA CORPORATIVA (Mismo hotel) --}}
                            @if(Auth::guard('corporate')->check())
                                <input type="text" class="form-control bg-light" value="{{ $hotels->first()->nombre }}" readonly>
                                <input type="hidden" name="id_hotel_recogida" value="{{ $hotels->first()->id_hotel }}">
                            @else
                                <select class="form-control" id="id_hotel_recogida" name="id_hotel_recogida" required>
                                    <option value="">Seleccione su Hotel</option>
                                    @foreach($hotels as $hotel)
                                        <option value="{{ $hotel->id_hotel }}" @if(old('id_hotel_recogida') == $hotel->id_hotel) selected @endif>{{ $hotel->nombre }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="hora_recogida_vuelta" class="form-label">Hora de Recogida</label>
                            <input type="time" class="form-control" id="hora_recogida_vuelta" name="hora_recogida_vuelta" value="{{ old('hora_recogida_vuelta') }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pax" class="form-label">Número de Pasajeros</label>
                            <input type="number" class="form-control" id="pax" name="pax" value="{{ old('pax', 1) }}" min="1" required>
                        </div>
                    </div>

                    {{-- 4. Datos Personales --}}
                    <h5 class="mt-4 mb-3 text-primary"><i class="fas fa-user"></i> Datos del Contacto</h5>
                    @php
                        $nombre = old('nombre_contacto');
                        $email = old('email_contacto');
                        if (Auth::guard('web')->check()) {
                            $u = Auth::guard('web')->user();
                            $nombre = $u->nombre . ' ' . ($u->apellido1 ?? '');
                            $email = $u->email_viajero;
                        } elseif (Auth::guard('corporate')->check()) {
                            $u = Auth::guard('corporate')->user();
                            $nombre = $u->nombre;
                            $email = $u->email_hotel;
                        } elseif (Auth::guard('admin')->check()) {
                            $u = Auth::guard('admin')->user();
                            $nombre = $u->nombre;
                            $email = $u->email_admin;
                        }
                    @endphp
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

                    <div class="d-flex justify-content-between mt-4">
                        {{-- CAMBIO: Redirige a la ruta 'dashboard' que gestiona la redirección según el rol --}}
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success btn-lg">Confirmar Reserva</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection