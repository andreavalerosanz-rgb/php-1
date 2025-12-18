@extends('layouts.app')

@section('content')
<style>
    .auth-page-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
    }

    .auth-card {
        background: rgba(255, 255, 255, 0.96);
        border-radius: 1.5rem;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.12);
        overflow: hidden;
        width: 100%;
        max-width: 800px;
    }

    .auth-header {
        background: linear-gradient(135deg, #0f9f9a, #0f766e);
        padding: 2.5rem 2rem 2rem;
        text-align: center;
        color: white;
        position: relative;
    }
    
    .auth-header::after {
        content: "";
        position: absolute;
        bottom: -20px; left: 0; right: 0; height: 40px;
        background: white;
        border-radius: 50% 50% 0 0 / 100% 100% 0 0;
    }

    .auth-body { padding: 2.5rem 3rem 3rem; }

    /* Inputs */
    .form-control, .form-select {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        transition: all 0.2s;
    }
    .form-control:focus, .form-select:focus {
        background-color: #fff;
        border-color: #0f9f9a;
        box-shadow: 0 0 0 4px rgba(15, 159, 154, 0.1);
    }
    .form-label { font-size: 0.85rem; font-weight: 600; color: #334155; }

    .role-selector-wrapper {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .role-card {
        flex: 1;
        position: relative;
    }
    .role-card input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0; height: 0;
    }
    .role-card label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1.5rem 1rem;
        background: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        height: 100%;
        text-align: center;
        color: #64748b;
    }
    .role-card label:hover {
        border-color: #94a3b8;
        transform: translateY(-2px);
    }
    
    .role-icon {
        display: block;
        margin-bottom: 0.75rem;
        color: #94a3b8;
        transition: color 0.2s ease;
    }
    .role-icon svg {
        width: 40px;
        height: 40px;
    }

    .role-card input:checked + label {
        border-color: #0f9f9a;
        background-color: #f0fdfa;
        color: #0f766e;
        box-shadow: 0 10px 15px -3px rgba(15, 159, 154, 0.1);
    }
    
    .role-card input:checked + label .role-icon {
        color: #0f766e;
    }

    .role-name { font-weight: 700; display: block; }

    .btn-auth-primary {
        background: #0f766e; border: none; color: #fff;
        font-weight: 600; border-radius: 999px; padding: 0.9rem;
        font-size: 1.1rem; width: 100%;
        box-shadow: 0 4px 12px rgba(15, 118, 110, 0.3);
        transition: all 0.2s;
    }
    .btn-auth-primary:hover {
        background: #115e59; transform: translateY(-2px);
    }
    
    .section-divider {
        border-top: 1px dashed #cbd5e1;
        margin: 2rem 0;
        position: relative;
    }
    .section-divider span {
        position: absolute;
        top: -12px; left: 50%; transform: translateX(-50%);
        background: #fff; padding: 0 1rem;
        color: #94a3b8; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;
    }

    @media(max-width: 768px) {
        .auth-body { padding: 1.5rem; }
    }
</style>

<div class="auth-page-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h4 class="fw-bold m-0">Crear Cuenta</h4>
            <p class="small opacity-75 mb-0">Únete a Isla Transfers</p>
        </div>

        <div class="auth-body">
            
            @if($errors->has('registro_error'))
                <div class="alert alert-danger rounded-3 shadow-sm mb-4">
                    {{ $errors->first('registro_error') }}
                </div>
            @endif

            @if($errors->any() && !$errors->has('registro_error'))
                <div class="alert alert-warning rounded-3 shadow-sm mb-4">
                    <ul class="mb-0 small ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- FORZAR ROL VIAJERO --}}
                <input type="hidden" name="role" value="viajero">
                
                {{-- 1. Selector de Rol (Visual Cards with SVGs) --}}
                <div class="mb-3 text-center">
                    <label class="form-label d-block mb-3 text-muted">Tipo de cuenta</label>
                    <div class="role-selector-wrapper">
                        <div class="role-card">
                            <input type="radio" name="role" id="roleViajero" value="viajero" 
                                {{ old('role', 'viajero') == 'viajero' ? 'checked' : '' }}>
                            <label for="roleViajero">
                                <span class="role-icon">
                                    {{-- Heroicons: User --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </span>
                                <span class="role-name">Viajero</span>
                                <span class="small" style="font-size: 0.8rem; opacity: 0.8;">Particular</span>
                            </label>
                        </div>
                        
                        <div class="role-card d-none">
                            <input type="radio" name="role" id="roleHotel" value="hotel" 
                                {{ old('role') == 'hotel' ? 'checked' : '' }}>
                            <label for="roleHotel">
                                <span class="role-icon">
                                    {{-- Heroicons: Building Office --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                                    </svg>
                                </span>
                                <span class="role-name">Hotel</span>
                                <span class="small" style="font-size: 0.8rem; opacity: 0.8;">Corporativo</span>
                            </label>
                        </div>
                    </div>
                    @error('role')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Datos Principales --}}
                <div class="mb-4">
                    <label for="nombre" class="form-label">Nombre / Nombre del Hotel</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                           id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                </div>

                {{-- Contenedor de Campos de Viajero --}}
                <div id="viajero-fields">
                    <div class="section-divider"><span>Datos Personales</span></div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="apellido1" class="form-label">Primer Apellido</label>
                            <input type="text" class="form-control" id="apellido1" name="apellido1" value="{{ old('apellido1') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="apellido2" class="form-label">Segundo Apellido</label>
                            <input type="text" class="form-control" id="apellido2" name="apellido2" value="{{ old('apellido2') }}">
                        </div>
                        
                        <div class="col-12">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion') }}">
                        </div>
                        
                        <div class="col-md-4">
                            <label for="codigoPostal" class="form-label">Código Postal</label>
                            <input type="text" class="form-control" id="codigoPostal" name="codigoPostal" value="{{ old('codigoPostal') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="ciudad" class="form-label">Ciudad</label>
                            <input type="text" class="form-control" id="ciudad" name="ciudad" value="{{ old('ciudad') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="pais" class="form-label">País</label>
                            <input type="text" class="form-control" id="pais" name="pais" value="{{ old('pais') }}">
                        </div>
                    </div>
                </div>

                {{-- Contenedor de Campos de Hotel --}}
                <div id="hotel-fields" style="display: none;">
                    <div class="section-divider"><span>Datos del Alojamiento</span></div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="comision" class="form-label">Comisión (%)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="comision" name="comision" 
                                       value="{{ old('comision', 0) }}" min="0" max="100" step="1">
                                <span class="input-group-text bg-white text-muted">%</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="id_zona" class="form-label">Zona</label>
                            <select class="form-select" id="id_zona" name="id_zona">
                                <option value="">Selecciona una zona...</option>
                                @foreach($zonas ?? [] as $zona)
                                    <option value="{{ $zona->id_zona }}" {{ old('id_zona') == $zona->id_zona ? 'selected' : '' }}>
                                        {{ $zona->descripcion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="section-divider"><span>Seguridad</span></div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}" required>
                </div>

                {{-- Contraseña --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirmar</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>

                <div class="d-grid mt-5">
                    <button type="submit" class="btn btn-auth-primary btn-lg">
                        Completar Registro
                    </button>
                </div>

                <div class="text-center mt-3">
                    <span class="text-muted small">¿Ya tienes cuenta?</span>
                    <a href="{{ route('login') }}" class="text-decoration-none fw-bold ms-1" style="color: #0f766e;">
                        Inicia sesión
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleRadios = document.querySelectorAll('input[name="role"]');
    const viajeroFields = document.getElementById('viajero-fields');
    const hotelFields = document.getElementById('hotel-fields');
    const nombreInput = document.getElementById('nombre');
    
    // Obtener todos los campos
    const viajeroInputs = viajeroFields.querySelectorAll('input');
    const hotelInputs = hotelFields.querySelectorAll('input, select');

    function toggleFields() {
        const selectedRadio = document.querySelector('input[name="role"]:checked');
        if (!selectedRadio) return; 

        const selectedRole = selectedRadio.value;
        
        if (selectedRole === 'hotel') {
            viajeroFields.style.display = 'none';
            hotelFields.style.display = 'block';
            nombreInput.placeholder = 'Ej: Hotel Gran Playa';

            viajeroInputs.forEach(input => {
                input.disabled = true;
                input.removeAttribute('required');
            });
            hotelInputs.forEach(input => {
                input.disabled = false;
                input.setAttribute('required', 'required');
            });
            
        } else {
            viajeroFields.style.display = 'block';
            hotelFields.style.display = 'none';
            nombreInput.placeholder = 'Ej: Juan Antonio';

            viajeroInputs.forEach(input => {
                input.disabled = false;
                input.setAttribute('required', 'required');
            });
            hotelInputs.forEach(input => {
                input.disabled = true;
                input.removeAttribute('required');
            });
        }
    }

    roleRadios.forEach(radio => {
        radio.addEventListener('change', toggleFields);
    });

    toggleFields();
});
</script>
@endsection