@extends('layouts.app')

@section('content')
<style>
  .dashboard-wrapper {
    min-height: 90vh;
    padding: 2rem 0;
  }

  .glass-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 1.5rem;
    box-shadow: 0 10px 40px rgba(15, 23, 42, 0.08);
    overflow: hidden;
  }

  /* HEADER */
  .card-header-custom {
    background: linear-gradient(90deg, #0f9f9a, #0f766e);
    padding: 1.25rem 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: white;
  }

  .role-pill {
    background: rgba(255, 255, 255, 0.18);
    border: 1px solid rgba(255, 255, 255, 0.25);
    padding: 0.25rem 0.65rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
  }

  /* FORM */
  .form-wrap {
    padding: 2rem;
  }

  .form-label-custom {
    font-weight: 700;
    color: #334155;
    margin-bottom: 0.4rem;
  }

  .input-soft {
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    padding: 0.75rem 1rem;
    background: #fff;
    transition: 0.15s ease;
  }

  .input-soft:focus {
    border-color: #0f766e;
    box-shadow: 0 0 0 0.2rem rgba(15, 118, 110, 0.15);
  }

  .form-hint {
    color: #64748b;
    font-size: 0.85rem;
    margin-top: 0.35rem;
  }

  .divider {
    height: 1px;
    background: #eef2f7;
    margin: 1.25rem 0;
  }

  /* ALERT */
  .alert-success-custom {
    background: #ecfeff;
    border: 1px solid #a5f3fc;
    color: #155e75;
    border-radius: 12px;
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
    font-weight: 600;
  }

  /* BUTTONS */
  .btn-main {
    background: #0f766e;
    border: none;
    color: white;
    font-weight: 800;
    border-radius: 14px;
    padding: 0.65rem 1rem;
    transition: 0.15s;
  }
  .btn-main:hover {
    background: #0b5f59;
    color: white;
  }

  .btn-secondary-soft {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    color: #0f766e;
    font-weight: 800;
    border-radius: 14px;
    padding: 0.65rem 1rem;
    transition: 0.15s;
    text-decoration: none;
  }
  .btn-secondary-soft:hover {
    background: #ccfbf1;
    color: #0f766e;
  }

  .header-icon { width: 24px; height: 24px; }
</style>

<div class="dashboard-wrapper">
  <div class="container-fluid px-lg-5">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-9 col-xl-8">

        <div class="glass-card">

          {{-- Header --}}
          <div class="card-header-custom">
            <div class="d-flex align-items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="header-icon" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.118a7.5 7.5 0 0115 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.5-1.632z" />
              </svg>
              <h5 class="mb-0 fw-bold">Editar perfil</h5>
            </div>

            <span class="role-pill">{{ strtoupper($guard ?? '') }}</span>
          </div>

          <div class="form-wrap">

            @if(session('status'))
              <div class="alert-success-custom">
                ✅ {{ session('status') }}
              </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
              @csrf
              @method('PUT')

              <div class="row g-4">

                {{-- Nombre --}}
                <div class="col-12">
                  <label class="form-label-custom">Nombre</label>
                  <input
                    type="text"
                    name="nombre"
                    class="form-control input-soft @error('nombre') is-invalid @enderror"
                    value="{{ old('nombre', $user->nombre ?? '') }}"
                    placeholder="Tu nombre"
                  >
                  @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                {{-- Email --}}
                <div class="col-12">
                  <label class="form-label-custom">Correo electrónico</label>
                  <input
                    type="email"
                    name="email"
                    class="form-control input-soft @error('email') is-invalid @enderror"
                    value="{{ old('email', $user->email_admin ?? $user->email_hotel ?? $user->email_viajero ?? '') }}"
                    placeholder="tu@email.com"
                  >
                  @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                {{-- CAMPOS EXTRA SOLO PARA VIAJERO --}}
                @if(($guard ?? '') === 'web')
                  <div class="col-12 col-md-6">
                    <label class="form-label-custom">Primer apellido</label>
                    <input
                      type="text"
                      name="apellido1"
                      class="form-control input-soft @error('apellido1') is-invalid @enderror"
                      value="{{ old('apellido1', $user->apellido1 ?? '') }}"
                      placeholder="Primer apellido"
                    >
                    @error('apellido1')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="col-12 col-md-6">
                    <label class="form-label-custom">Segundo apellido</label>
                    <input
                      type="text"
                      name="apellido2"
                      class="form-control input-soft @error('apellido2') is-invalid @enderror"
                      value="{{ old('apellido2', $user->apellido2 ?? '') }}"
                      placeholder="Segundo apellido"
                    >
                    @error('apellido2')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="col-12">
                    <label class="form-label-custom">Dirección</label>
                    <input
                      type="text"
                      name="direccion"
                      class="form-control input-soft @error('direccion') is-invalid @enderror"
                      value="{{ old('direccion', $user->direccion ?? '') }}"
                      placeholder="Calle, número, piso..."
                    >
                    <div class="form-hint">Dirección asociada a tu perfil.</div>
                    @error('direccion')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="col-12 col-md-4">
                    <label class="form-label-custom">Código Postal</label>
                    <input
                      type="text"
                      name="codigoPostal"
                      class="form-control input-soft @error('codigoPostal') is-invalid @enderror"
                      value="{{ old('codigoPostal', $user->codigoPostal ?? '') }}"
                      placeholder="00000"
                    >
                    @error('codigoPostal')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="col-12 col-md-4">
                    <label class="form-label-custom">Ciudad</label>
                    <input
                      type="text"
                      name="ciudad"
                      class="form-control input-soft @error('ciudad') is-invalid @enderror"
                      value="{{ old('ciudad', $user->ciudad ?? '') }}"
                      placeholder="Ciudad"
                    >
                    @error('ciudad')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="col-12 col-md-4">
                    <label class="form-label-custom">País</label>
                    <input
                      type="text"
                      name="pais"
                      class="form-control input-soft @error('pais') is-invalid @enderror"
                      value="{{ old('pais', $user->pais ?? '') }}"
                      placeholder="País"
                    >
                    @error('pais')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                @endif

              </div>

              <div class="divider"></div>

              <div class="row g-4">
                {{-- Password --}}
                <div class="col-12 col-md-6">
                  <label class="form-label-custom">Nueva contraseña</label>
                  <input
                    type="password"
                    name="password_confirmation"
                    class="form-control input-soft"
                    placeholder="••••••••"
                    autocomplete="new-password"
                    />

                  <div class="form-hint">Déjalo vacío si no quieres cambiarla.</div>
                  @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label-custom">Confirmar contraseña</label>
                  <input
                    type="password"
                    name="password_confirmation"
                    class="form-control input-soft"
                    placeholder="••••••••"
                  >
                </div>
              </div>

              <div class="d-flex gap-2 mt-4">
                <a href="{{ route('dashboard') }}" class="btn-secondary-soft">
                  Volver al panel
                </a>
                <button type="submit" class="btn-main">
                  Guardar cambios
                </button>
              </div>

            </form>

          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
