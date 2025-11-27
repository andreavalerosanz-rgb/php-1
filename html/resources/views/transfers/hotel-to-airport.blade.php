@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-lg">
            <div class="card-header bg-secondary text-white text-center">
                <h4>Reservar Traslado: Hotel → Aeropuerto</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('transfer.reserve.confirm') }}">
                    @csrf
                    <input type="hidden" name="reservation_type" value="hotel_to_airport">
                    
                    {{-- 🚨 CRÍTICO: ID VIAJERO --}}
                    <input type="hidden" name="id_viajero" value="{{ Auth::user()->id_viajero ?? Auth::user()->id ?? 'guest_' . time() }}">

                    {{-- RESTRICCIÓN 48 HORAS --}}
                    <div class="alert alert-warning small">
                        <i class="fas fa-clock"></i> **Nota:** La reserva debe realizarse con al menos **48 horas de antelación**. La fecha mínima es: **{{ Carbon\Carbon::parse($minDate)->format('d/m/Y') }}**.
                    </div>
                    
                    {{-- 1. Datos del Vuelo (Salida) --}}
                    <h5 class="mb-3 text-secondary"><i class="fas fa-plane-departure"></i> Datos del Vuelo (Salida)</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_vuelo_salida" class="form-label">Día del Vuelo</label>
                            <input type="date" class="form-control @error('fecha_vuelo_salida') is-invalid @enderror" 
                                   id="fecha_vuelo_salida" name="fecha_vuelo_salida" 
                                   value="{{ old('fecha_vuelo_salida') }}" 
                                   min="{{ Carbon\Carbon::parse($minDate)->format('Y-m-d') }}" required>
                            @error('fecha_vuelo_salida')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="hora_vuelo_salida" class="form-label">Hora de Salida del Vuelo</label>
                            <input type="time" class="form-control @error('hora_vuelo_salida') is-invalid @enderror" 
                                   id="hora_vuelo_salida" name="hora_vuelo_salida" 
                                   value="{{ old('hora_vuelo_salida') }}" required>
                            @error('hora_vuelo_salida')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="num_vuelo_salida" class="form-label">Número de Vuelo</label>
                            <input type="text" class="form-control @error('num_vuelo_salida') is-invalid @enderror" 
                                   id="num_vuelo_salida" name="num_vuelo_salida" 
                                   value="{{ old('num_vuelo_salida') }}" required>
                            @error('num_vuelo_salida')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- 2. Recogida y Pasajeros --}}
                    <h5 class="mt-4 mb-3 text-secondary"><i class="fas fa-map-marker-alt"></i> Recogida y Pasajeros</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_hotel_recogida" class="form-label">Hotel de Recogida</label>
                            <select class="form-select @error('id_hotel_recogida') is-invalid @enderror" 
                                    id="id_hotel_recogida" name="id_hotel_recogida" required>
                                <option value="">Seleccione un hotel</option>
                                @foreach($hotels as $hotel)
                                    {{-- 🚨 CONSISTENCIA: Usar id_hotel en todos los formularios --}}
                                    <option value="{{ $hotel->id_hotel }}" 
                                            {{ old('id_hotel_recogida') == $hotel->id_hotel ? 'selected' : '' }}>
                                        {{ $hotel->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_hotel_recogida')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="hora_recogida" class="form-label">Hora de Recogida Estimada</label>
                            <input type="time" class="form-control @error('hora_recogida') is-invalid @enderror" 
                                   id="hora_recogida" name="hora_recogida" 
                                   value="{{ old('hora_recogida') }}" required>
                            @error('hora_recogida')
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
                    <h5 class="mt-4 mb-3 text-secondary"><i class="fas fa-user"></i> Datos del Contacto</h5>
                    @php
                        $user = Auth::user();
                        $nombre = $user->nombre ?? old('nombre_contacto');
                        $email = Auth::guard('web')->check() ? $user->email_viajero : (Auth::guard('corporate')->check() ? $user->email_hotel : old('email_contacto'));
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