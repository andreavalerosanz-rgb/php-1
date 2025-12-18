@extends('layouts.app')

@section('content')
<style>
    .dashboard-container {
        min-height: 100vh;
        padding: 2rem 1.5rem;
    }

    .glass-panel {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        border-radius: 1.25rem;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .panel-header-brand {
        background: linear-gradient(90deg, #0f9f9a, #0f766e);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: white;
    }

    .custom-table {
        margin-bottom: 0;
        width: 100%;
        vertical-align: middle;
    }

    .custom-table thead th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1rem 1rem;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }

    .custom-table tbody td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
        color: #334155;
    }

    .custom-table tbody tr:hover {
        background-color: #f1f5f9;
    }

    .text-teal { color: #0f766e; font-weight: 700; }
    .text-success { color: #16a34a; font-weight: 700; }

    .form-select, .btn-teal {
        border-radius: 0.5rem;
    }

    .btn-teal {
        background-color: #0f766e;
        color: white;
        font-weight: 600;
        border: none;
    }

    .btn-teal:hover {
        background-color: #0f9f9a;
    }

    .role-card {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    .role-card input[type="radio"] {
        display: none;
    }
    .role-card label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .role-card input[type="radio"]:checked + label {
        background-color: #0f766e;
        color: white;
        border-color: #0f766e;
    }
</style>

<div class="dashboard-container">
    <div class="container-fluid px-lg-4">

        {{-- TAB NAV --}}
        <ul class="nav nav-tabs mb-4" id="hotelTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ request('tab') !== 'comisiones' ? 'active' : '' }}" 
                        id="hoteles-tab" data-bs-toggle="tab" data-bs-target="#hoteles">
                    <i class="fas fa-hotel"></i> Gestión de Hoteles
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ request('tab') === 'comisiones' ? 'active' : '' }}" 
                        id="comisiones-tab" data-bs-toggle="tab" data-bs-target="#comisiones">
                    <i class="fas fa-euro-sign"></i> Gestión de Comisiones
                </button>
            </li>
        </ul>

        <div class="tab-content" id="hotelTabsContent">

            {{-- TAB HOTELES --}}
            <div class="tab-pane fade {{ request('tab') !== 'comisiones' ? 'show active' : '' }}" id="hoteles">
                
                {{-- LISTADO DE HOTELES --}}
                <div class="glass-panel">
                    <div class="panel-header-brand">
                        <h5 class="mb-0 fw-bold">Hoteles registrados</h5>
                        <div class="badge bg-white text-teal bg-opacity-90 shadow-sm text-dark">
                            Total: {{ $hoteles->count() }}
                        </div>
                    </div>

                    <div class="table-responsive p-3">
                        @if($hoteles->isEmpty())
                            <p class="text-muted">No existen hoteles registrados.</p>
                        @else
                            <table class="table custom-table text-center align-middle">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Zona</th>
                                        <th>Comisión</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hoteles as $hotel)
                                        <tr>
                                            <td>{{ $hotel->id_hotel }}</td>
                                            <td>{{ $hotel->nombre }}</td>
                                            <td>{{ $hotel->email_hotel }}</td>
                                            <td>{{ $hotel->zona->descripcion ?? 'Sin zona' }}</td>
                                            <td>{{ $hotel->Comision }}%</td>
                                            <td>
                                                @if($hotel->activo)
                                                    <span class="badge bg-success">Activo</span>
                                                @else
                                                    <span class="badge bg-danger">Inhabilitado</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($hotel->activo)
                                                    <form method="POST" action="{{ route('admin.hoteles.disable', $hotel->id_hotel) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <button class="btn btn-warning btn-sm">Inhabilitar</button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('admin.hoteles.enable', $hotel->id_hotel) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <button class="btn btn-success btn-sm">Habilitar</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>

                {{-- FORMULARIO CREAR HOTEL --}}
                <div class="glass-panel mt-4">
                    <div class="panel-header-brand">
                        <h5 class="mb-0 fw-bold">Crear usuario corporativo - Hotel</h5>
                        <span class="badge bg-secondary text-uppercase">ADMIN</span>
                    </div>

                    <div class="p-4">
                        <form method="POST" action="{{ route('admin.hoteles.store') }}">
                            @csrf

                            {{-- Role: Hotel --}}
                            <div class="role-card">
                                <input type="radio" name="role" id="roleHotel" value="hotel" checked>
                            </div>

                            {{-- Nombre --}}
                            <div class="mb-3">
                                <label class="form-label">Nombre del hotel</label>
                                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}">
                                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label class="form-label">Correo electrónico del hotel</label>
                                <input type="email" name="email_hotel" class="form-control @error('email_hotel') is-invalid @enderror" value="{{ old('email_hotel') }}">
                                @error('email_hotel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Comisión --}}
                            <div class="mb-3">
                                <label class="form-label">Comisión (%)</label>
                                <input type="number" name="Comision" class="form-control @error('Comision') is-invalid @enderror" value="{{ old('Comision') }}" min="0" max="100">
                                @error('Comision') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Zona --}}
                            <div class="mb-3">
                                <label class="form-label">Asignar zona</label>
                                <select name="id_zona" class="form-select @error('id_zona') is-invalid @enderror">
                                    <option value="">Seleccione una zona</option>
                                    @foreach($zonas as $zona)
                                        <option value="{{ $zona->id_zona }}" {{ old('id_zona') == $zona->id_zona ? 'selected' : '' }}>
                                            {{ $zona->id_zona }} – {{ $zona->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_zona') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Contraseña --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Contraseña</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirmar contraseña</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-teal w-100">Crear usuario corporativo</button>
                        </form>
                    </div>
                </div>

            </div>

            {{-- TAB COMISIONES --}}
            <div class="tab-pane fade {{ request('tab') === 'comisiones' ? 'show active' : '' }}" id="comisiones">
                @include('admin.partials.commissions-table')
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const activeTab = "{{ request('tab') }}";

    if (activeTab === "comisiones") {
        new bootstrap.Tab(document.querySelector('#comisiones-tab')).show();
    } else {
        new bootstrap.Tab(document.querySelector('#hoteles-tab')).show();
    }
});
</script>
@endsection
