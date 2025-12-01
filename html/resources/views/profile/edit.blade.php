@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- PERFIL GENERAL --}}
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <span>Editar perfil</span>
                    <span class="badge bg-secondary text-uppercase">
                        {{ strtoupper($guard ?? '') }}
                    </span>
                </div>

                <div class="card-body bg-light">
                    @if(session('status'))
                    <div class="alert alert-success small mb-3">
                        <i class="fas fa-check-circle"></i> {{ session('status') }}
                    </div>
                    @endif


                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        {{-- Nombre --}}
                        <div class="mb-3">
                            <label class="form-label">Nombre completo</label>
                            <input type="text"
                                   name="nombre"
                                   class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{ old('nombre', $user->nombre ?? '') }}">
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email (campo depende del tipo de usuario) --}}
                        <div class="mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email',
                                        $user->email_admin  ??
                                        $user->email_hotel  ??
                                        $user->email_viajero ?? ''
                                   ) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Contraseña --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nueva contraseña</label>
                                <input type="password"
                                       name="password"
                                       class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirmar contraseña</label>
                                <input type="password"
                                       name="password_confirmation"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                Volver al panel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- 🔴 SOLO ADMIN: CREAR USUARIO CORPORATIVO DESDE EL PERFIL --}}
            @if(($guard ?? '') === 'admin')
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                        <span>Crear nuevo usuario corporativo (hotel)</span>
                        <span class="badge bg-light text-danger">ADMIN</span>
                    </div>

                    <div class="card-body bg-light">
                        <form method="POST" action="{{ route('admin.profile.corporate.store') }}">
                            @csrf

                            {{-- Nombre del hotel --}}
                            <div class="mb-3">
                                <label class="form-label">Nombre del hotel</label>
                                <input type="text"
                                       name="nombre"
                                       class="form-control @error('nombre') is-invalid @enderror"
                                       value="{{ old('nombre') }}">
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email del hotel --}}
                            <div class="mb-3">
                                <label class="form-label">Correo electrónico del hotel</label>
                                <input type="email"
                                       name="email_hotel"
                                       class="form-control @error('email_hotel') is-invalid @enderror"
                                       value="{{ old('email_hotel') }}">
                                @error('email_hotel')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Comisión y zona (muy básico, puedes mejorarlo luego) --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Comisión (%)</label>
                                    <input type="number"
                                           name="Comision"
                                           class="form-control @error('Comision') is-invalid @enderror"
                                           value="{{ old('Comision', 10) }}"
                                           min="0" max="100">
                                    @error('Comision')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ID de Zona</label>
                                    <input type="number"
                                           name="id_zona"
                                           class="form-control @error('id_zona') is-invalid @enderror"
                                           value="{{ old('id_zona') }}">
                                    @error('id_zona')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Password --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Contraseña</label>
                                    <input type="password"
                                           name="password"
                                           class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirmar contraseña</label>
                                    <input type="password"
                                           name="password_confirmation"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-hotel"></i> Crear usuario corporativo
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
