@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">

                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <span>Crear usuario corporativo</span>
                    <span class="badge bg-secondary text-uppercase">ADMIN</span>
                </div>

                <div class="card-body bg-light">
                    {{-- Mensaje opcional --}}
                    @if(session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.profile.corporate.store') }}">
                        @csrf

                        {{-- Nombre del hotel --}}
                        <div class="mb-3">
                            <label class="form-label">Nombre del hotel</label>
                            <input type="text"
                                   name="nombre"
                                   class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{ old('nombre') }}"
                                   placeholder="Ej: Hotel Gran Playa">
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
                                   value="{{ old('email_hotel') }}"
                                   placeholder="hotel@demo.com">
                            @error('email_hotel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Comisión --}}
                        <div class="mb-3">
                            <label class="form-label">Comisión (%)</label>
                            <input type="number"
                                   name="Comision"
                                   class="form-control @error('Comision') is-invalid @enderror"
                                   value="{{ old('Comision', 10) }}"
                                   min="0" max="100" step="1">
                            @error('Comision')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Porcentaje de comisión que aplicará el hotel.
                            </small>
                        </div>

                        {{-- Zona (si ya tenéis listado de zonas, puedes sustituir por un select) --}}
                        <div class="mb-3">
                            <label class="form-label">ID de Zona</label>
                            <input type="number"
                                   name="id_zona"
                                   class="form-control @error('id_zona') is-invalid @enderror"
                                   value="{{ old('id_zona') }}"
                                   placeholder="Ej: 1">
                            @error('id_zona')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Contraseña --}}
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

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                                Volver al panel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                Crear usuario corporativo
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
