@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-lg">
            <div class="card-header bg-success text-white text-center">
                <h4>Registro de Nuevo Usuario</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    {{-- 1. Selector de Rol --}}
                    <div class="mb-4">
                        <label class="form-label d-block fw-bold">Tipo de Usuario</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="role" id="roleViajero" value="viajero" {{ old('role', 'viajero') == 'viajero' ? 'checked' : '' }}>
                            <label class="form-check-label" for="roleViajero">Viajero (Particular)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="role" id="roleHotel" value="hotel" {{ old('role') == 'hotel' ? 'checked' : '' }}>
                            <label class="form-check-label" for="roleHotel">Hotel (Corporativo)</label>
                        </div>
                        @error('role')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nombre (Común a ambos roles) --}}
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre / Nombre del Hotel</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Contenedor de Campos de Viajero (se oculta para Hotel) --}}
                    <div id="viajero-fields">
                        
                        <h5 class="mt-4 mb-3 text-secondary">Apellidos y Dirección del Viajero</h5>

                        <div class="row">
                            {{-- Apellidos --}}
                            <div class="col-md-6 mb-3">
                                <label for="apellido1" class="form-label">Primer Apellido</label>
                                <input type="text" class="form-control @error('apellido1') is-invalid @enderror" id="apellido1" name="apellido1" value="{{ old('apellido1') }}" required>
                                @error('apellido1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellido2" class="form-label">Segundo Apellido</label>
                                <input type="text" class="form-control @error('apellido2') is-invalid @enderror" id="apellido2" name="apellido2" value="{{ old('apellido2') }}" required>
                                @error('apellido2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Dirección --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion') }}" required>
                                @error('direccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="codigoPostal" class="form-label">Código Postal</label>
                                <input type="text" class="form-control @error('codigoPostal') is-invalid @enderror" id="codigoPostal" name="codigoPostal" value="{{ old('codigoPostal') }}" required>
                                @error('codigoPostal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control @error('ciudad') is-invalid @enderror" id="ciudad" name="ciudad" value="{{ old('ciudad') }}" required>
                                @error('ciudad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pais" class="form-label">País</label>
                                <input type="text" class="form-control @error('pais') is-invalid @enderror" id="pais" name="pais" value="{{ old('pais') }}" required>
                                @error('pais')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div> {{-- Fin del Contenedor de Viajero --}}


                    <h5 class="mt-3 mb-3 text-secondary">Datos de Acceso</h5>
                    
                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Contraseña --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-success btn-lg">Registrarse</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión aquí</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roleRadios = document.querySelectorAll('input[name="role"]');
        const viajeroFields = document.getElementById('viajero-fields');
        const nombreInput = document.getElementById('nombre');
        
        // Obtener todos los campos que son requeridos para Viajero
        const requiredViajeroInputs = viajeroFields.querySelectorAll('[required]');

        function toggleFields() {
            const selectedRole = document.querySelector('input[name="role"]:checked').value;
            
            if (selectedRole === 'hotel') {
                viajeroFields.style.display = 'none';
                nombreInput.placeholder = 'Ej: Hotel Gran Playa';

                requiredViajeroInputs.forEach(input => {
                    input.removeAttribute('required');
                });
                
            } else {
                viajeroFields.style.display = 'block';
                nombreInput.placeholder = 'Ej: Juan Antonio';

                requiredViajeroInputs.forEach(input => {
                    if (!input.hasAttribute('required')) {
                        input.setAttribute('required', 'required');
                    }
                });
            }
        }

        roleRadios.forEach(radio => {
            radio.addEventListener('change', toggleFields);
        });

        // Ejecutar al cargar la página para establecer el estado inicial
        toggleFields();
    });
</script>
@endsection