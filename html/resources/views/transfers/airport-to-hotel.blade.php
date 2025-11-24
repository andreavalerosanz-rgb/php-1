@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h4>Reservar Traslado: Aeropuerto → Hotel</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('transfer.reserve.confirm') }}">
                    @csrf
                    <input type="hidden" name="reservation_type" value="airport_to_hotel">
                    
                    {{-- 🚨 CRÍTICO: ID VIAJERO --}}
                    <input type="hidden" name="id_viajero" value="{{ Auth::user()->id_viajero ?? Auth::user()->id ?? 'guest_' . time() }}">

                    @error('hotel')
                        <div class="alert alert-danger text-center" role="alert">
                            <h5 class="text-danger">¡ERROR EN LA RESERVA!</h5>
                            {{ $message }}
                        </div>
                    @enderror

                    {{-- RESTRICCIÓN 48 HORAS --}}
                    <div class="alert alert-warning small">
                        <i class="fas fa-clock"></i> **Nota:** La reserva debe realizarse con al menos **48 horas de antelación**. La fecha mínima es: **{{ Carbon\Carbon::parse($minDate)->format('d/m/Y') }}**.
                    </div>
                    
                    {{-- 1. Datos del Vuelo --}}
                    <h5 class="mb-3 text-primary"><i class="fas fa-plane"></i> Datos del Vuelo (Llegada)</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_llegada" class="form-label">Día de Llegada</label>
                            <input type="date" class="form-control @error('fecha_llegada') is-invalid @enderror" 
                                   id="fecha_llegada" name="fecha_llegada" 
                                   value="{{ old('fecha_llegada') }}" 
                                   min="{{ Carbon\Carbon::parse($minDate)->format('Y-m-d') }}" required>
                            @error('fecha_llegada')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="hora_llegada" class="form-label">Hora de Llegada Estimada</label>
                            <input type="time" class="form-control @error('hora_llegada') is-invalid @enderror" 
                                   id="hora_llegada" name="hora_llegada" 
                                   value="{{ old('hora_llegada') }}" required>
                            @error('hora_llegada')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="num_vuelo" class="form-label">Número de Vuelo</label>
                            <input type="text" class="form-control @error('num_vuelo') is-invalid @enderror" 
                                   id="num_vuelo" name="num_vuelo" 
                                   value="{{ old('num_vuelo') }}" required>
                            @error('num_vuelo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="aeropuerto_origen" class="form-label">Aeropuerto de Origen</label>
                            <input type="text" class="form-control @error('aeropuerto_origen') is-invalid @enderror" 
                                   id="aeropuerto_origen" name="aeropuerto_origen" 
                                   value="{{ old('aeropuerto_origen', 'Aeropuerto de Origen') }}" required>
                            @error('aeropuerto_origen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- 2. Destino y Pasajeros --}}
                    <h5 class="mt-4 mb-3 text-primary"><i class="fas fa-hotel"></i> Destino y Pasajeros</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_hotel_destino" class="form-label">Selección de Hotel Destino</label>
                            <select class="form-control @error('id_hotel_destino') is-invalid @enderror" 
                                    id="id_hotel_destino" name="id_hotel_destino" required>
                                <option value="">Seleccione su Hotel Destino</option>
                                @foreach($hotels as $hotel)
                                    {{-- 🚨 CONSISTENCIA: Usar id_hotel en todos los formularios --}}
                                    <option value="{{ $hotel->id_hotel }}" 
                                            @if(old('id_hotel_destino') == $hotel->id_hotel) selected @endif>
                                        {{ $hotel->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_hotel_destino')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="pax" class="form-label">Número de Pasajeros</label>
                            <input type="number" class="form-control @error('pax') is-invalid @enderror" 
                                   id="pax" name="pax" value="{{ old('pax', 1) }}" min="1" required>
                            @error('pax')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- 3. Datos Personales --}}
                    <h5 class="mt-4 mb-3 text-primary"><i class="fas fa-user"></i> Datos del Contacto</h5>
                    @php
                        $user = Auth::user();
                        $nombre = $user->nombre ?? old('nombre_contacto');
                        $email = Auth::guard('web')->check() ? ($user->email_viajero ?? $user->email) : (Auth::guard('corporate')->check() ? $user->email_hotel : old('email_contacto'));
                    @endphp
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

                    {{-- DEBUG: Mostrar errores --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <h6>Errores de validación:</h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('transfer.select-type') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success btn-lg">Confirmar Reserva</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection