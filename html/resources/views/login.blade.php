@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-lg">
            <div class="card-header bg-secondary text-white text-center">
                <h4>Inicio de Sesión</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
    @csrf

    {{-- MENSAJE ÚNICO DE ERROR --}}
    @if ($errors->has('auth_error'))
        <div class="alert alert-danger">
            {{ $errors->first('auth_error') }}
        </div>
    @elseif ($errors->has('email'))
        <div class="alert alert-danger">
            {{ $errors->first('email') }}
        </div>
    @endif
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">Iniciar Sesión</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection