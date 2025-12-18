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
    background: #ffffff;
    transition: 0.15s ease;
}

.input-soft:focus {
    border-color: #0f766e;
    box-shadow: 0 0 0 0.2rem rgba(15, 118, 110, 0.15);
}

/* HELP TEXT */
.form-hint {
    color: #64748b;
    font-size: 0.85rem;
    margin-top: 0.35rem;
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

/* ICONS */
.header-icon {
    width: 24px;
    height: 24px;
}
</style>

<div class="dashboard-wrapper">
    <div class="container-fluid px-lg-5">
        <div class="glass-card">

            {{-- Header --}}
            <div class="card-header-custom">
                <div class="d-flex align-items-center gap-2">
                    {{-- Icon: Pencil --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="header-icon" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l9.932-8.931z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.125L16.875 4.5" />
                    </svg>
                    <h5 class="mb-0 fw-bold">Editar Vehículo</h5>
                </div>

                <div class="small opacity-75">
                    ID: #{{ $vehiculo->id_vehiculo }}
                </div>
            </div>

            {{-- Form --}}
            <div class="form-wrap">
                <form action="{{ route('admin.vehiculos.update', $vehiculo->id_vehiculo) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        <div class="col-12">
                            <label class="form-label-custom">Descripción</label>
                            <input
                                type="text"
                                name="descripcion"
                                class="form-control input-soft"
                                value="{{ $vehiculo->descripcion }}"
                                required
                                placeholder="Ej: Minivan VIP Deluxe"
                            >
                            <div class="form-hint">Nombre visible del vehículo en el sistema.</div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label-custom">Email del Conductor</label>
                            <input
                                type="email"
                                name="email_conductor"
                                class="form-control input-soft"
                                value="{{ $vehiculo->email_conductor }}"
                                required
                                placeholder="conductor@empresa.com"
                            >
                            <div class="form-hint">Se usa para identificar el conductor asociado.</div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label-custom">Matrícula</label>
                            <input
                                type="text"
                                name="password"
                                class="form-control input-soft"
                                value="{{ $vehiculo->password }}"
                                required
                                placeholder="1234ABC"
                            >
                            <div class="form-hint">Campo “password” en BD (matrícula del vehículo).</div>
                        </div>

                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn-main">
                            Actualizar
                        </button>
                        <a href="{{ route('admin.vehiculos.index') }}" class="btn-secondary-soft">
                            Volver
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
