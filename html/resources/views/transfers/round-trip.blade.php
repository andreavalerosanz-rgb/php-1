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

                    {{-- ID VIAJERO --}}
                    <input type="hidden" name="id_viajero" value="{{ Auth::user()->id_viajero ?? 'guest_' . time() }}">

                    {{-- RESTRICCIÓN 48 HORAS --}}
                    <div class="alert alert-warning small">
                        <i class="fas fa-clock"></i> **Nota:** La reserva debe realizarse con al menos **48 horas de antelación** para ambos trayectos. La fecha mínima de IDA es: **{{ Carbon\Carbon::parse($minDate)->format('d/m/Y') }}**.
                    </div>
                    
                    {{-- SECCIÓN IDA: Aeropuerto -> Hotel --}}
                    <h4 class="mb-4 text-success"><i class="fas fa-road"></i> IDA: Aeropuerto → Hotel</h4>
                    
                    <h5 class="mb-3 text-success">Datos del Vuelo (Llegada)</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="fecha_llegada" class="form-label">Día de Llegada</label>
                            <input type="date" class="form-control @error('fecha_llegada') is-invalid @enderror" 
                                   id="fecha_llegada" name="fecha_llegada" 
                                   value="{{ old('fecha_llegada') }}" 
                                   min="{{ Carbon\Carbon::parse($minDate)->format('Y-m-d') }}" required>
                            @error('fecha_llegada')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="hora_llegada" class="form-label">Hora de Llegada Estimada</label>
                            <input type="time" class="form-control @error('hora_llegada') is-invalid @enderror" 
                                   id="hora_llegada" name="hora_llegada" 
                                   value="{{ old('hora_llegada') }}" required>
                            @error('hora_llegada')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="num_vuelo_ida" class="form-label">Número de Vuelo (Ida)</label>
                            <input type="text" class="form-control @error('num_vuelo_ida') is-invalid @enderror" 
                                   id="num_vuelo_ida" name="num_vuelo_ida" 
                                   value="{{ old('num_vuelo_ida') }}" required>
                            @error('num_vuelo_ida')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- HOTEL DESTINO PARA IDA --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_hotel_destino" class="form-label">Hotel Destino (Ida)</label>
                            <select class="form-select @error('id_hotel_destino') is-invalid @enderror" 
                                    id="id_hotel_destino" name="id_hotel_destino" required>
                                <option value="">Seleccione un hotel</option>
                                @foreach($hotels as $hotel)
                                    {{-- USAR CONSISTENTEMENTE id_hotel o id --}}
                                    <option value="{{ $hotel->id_hotel ?? $hotel->id }}" 
                                            {{ old('id_hotel_destino') == ($hotel->id_hotel ?? $hotel->id) ? 'selected' : '' }}>
                                        {{ $hotel->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_hotel_destino')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-5">

                    {{-- SECCIÓN VUELTA: Hotel -> Aeropuerto --}}
                    <h4 class="mb-4 text-warning"><i class="fas fa-car-side"></i> VUELTA: Hotel → Aeropuerto</h4>
                    
                    <h5 class="mt-4 mb-3 text-primary"><i class="fas fa-plane"></i> Datos del Vuelo (Vuelta / Salida)</h5>
                    <div class="row">
                        {{-- Campo: Fecha Vuelo Salida --}}
                        <div class="col-md-6 mb-3">
                            <label for="fecha_vuelo_salida" class="form-label">Día de Salida</label>
                            <input type="date" class="form-control @error('fecha_vuelo_salida') is-invalid @enderror" 
                                   id="fecha_vuelo_salida" name="fecha_vuelo_salida" 
                                   value="{{ old('fecha_vuelo_salida') }}" 
                                   min="{{ Carbon\Carbon::parse($minDate)->format('Y-m-d') }}" required>
                            @error('fecha_vuelo_salida') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>

                        {{-- Campo: Hora Vuelo Salida --}}
                        <div class="col-md-6 mb-3">
                            <label for="hora_vuelo_salida" class="form-label">Hora de Vuelo Salida</label>
                            <input type="time" class="form-control @error('hora_vuelo_salida') is-invalid @enderror" 
                                   id="hora_vuelo_salida" name="hora_vuelo_salida" 
                                   value="{{ old('hora_vuelo_salida') }}" required>
                            @error('hora_vuelo_salida') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3 text-warning">Recogida para Vuelta</h5>
                    <div class="row">
                        {{-- CRÍTICO: Selección de Hotel de Recogida --}}
                        <div class="col-md-6 mb-3">
                            <label for="id_hotel_recogida" class="form-label">Hotel de Recogida (Vuelta)</label>
                            <select class="form-control @error('id_hotel_recogida') is-invalid @enderror" 
                                    id="id_hotel_recogida" name="id_hotel_recogida" required>
                                <option value="">Seleccione su Hotel de Recogida</option>
                                @foreach($hotels as $hotel)
                                    <option value="{{ $hotel->id_hotel ?? $hotel->id }}" 
                                            @if(old('id_hotel_recogida') == ($hotel->id_hotel ?? $hotel->id)) selected @endif>
                                        {{ $hotel->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_hotel_recogida') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>

                        {{-- CRÍTICO: Hora de Recogida VUELTA --}}
                        <div class="col-md-6 mb-3">
                            <label for="hora_recogida_vuelta" class="form-label">Hora de Recogida (Vuelta)</label>
                            <input type="time" class="form-control @error('hora_recogida_vuelta') is-invalid @enderror" 
                                   id="hora_recogida_vuelta" name="hora_recogida_vuelta" 
                                   value="{{ old('hora_recogida_vuelta') }}" required>
                            @error('hora_recogida_vuelta') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>
                    </div>

                    {{-- Número de Pasajeros --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pax" class="form-label">Número de Pasajeros</label>
                            <input type="number" class="form-control @error('pax') is-invalid @enderror" 
                                   id="pax" name="pax" value="{{ old('pax', 1) }}" min="1" required>
                            @error('pax')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- 4. Datos Personales (Común) --}}
                    <h5 class="mt-4 mb-3 text-primary"><i class="fas fa-user"></i> Datos del Contacto</h5>
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
                            @error('nombre_contacto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="email_contacto" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email_contacto') is-invalid @enderror" 
                                   id="email_contacto" name="email_contacto" value="{{ $email }}" required>
                            @error('email_contacto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control @error('telefono') is-invalid @enderror" 
                                   id="telefono" name="telefono" value="{{ old('telefono') }}" required>
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- DEBUG: Mostrar errores de validación --}}
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

{{-- JavaScript para debugging --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
        console.log('Formulario enviado');
        console.log('Datos del formulario:', new FormData(form));
    });
    
    // Mostrar todos los valores en consola
    console.log('Valores del formulario:');
    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(input => {
        console.log(`${input.name}: ${input.value}`);
    });
});
</script>
@endsection