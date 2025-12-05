@extends('layouts.app')

@section('content')
<div class="container py-5">

{{-- NAV TABS --}}
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
    <div class="tab-pane fade {{ request('tab') !== 'comisiones' ? 'show active' : '' }}" id="hoteles">
    <h2 class="mb-4">Gestión de Hoteles</h2>

    {{-- MENSAJE DE CONFIRMACIÓN --}}
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    {{-- LISTADO DE HOTELES --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <strong>Hoteles registrados</strong>
        </div>

        <div class="card-body">

            @if($hoteles->isEmpty())
                <p class="text-muted">No existen hoteles registrados.</p>
            @else
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-secondary">
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
                                <td>{{ $hotel->id_zona }}</td>
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
    <div class="card shadow-lg border-0 rounded-4 mt-4">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <span>Crear usuario corporativo - Hotel</span>
            <span class="badge bg-secondary text-uppercase">ADMIN</span>
        </div>

        <div class="card-body bg-light">

            <form method="POST" action="{{ route('admin.hoteles.store') }}">
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

                {{-- Email --}}
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

                {{-- Comisión --}}
                <div class="mb-3">
                    <label class="form-label">Comisión (%)</label>
                    <input type="number"
                           name="Comision"
                           class="form-control @error('Comision') is-invalid @enderror"
                           value="{{ old('Comision') }}"
                           min="0" max="100">
                    @error('Comision')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Zona --}}
                <div class="mb-3">
                    <label class="form-label">Asignar zona</label>
                    <select name="id_zona" class="form-select @error('id_zona') is-invalid @enderror">
                        <option value="">Seleccione una zona</option>

                        @foreach($zonas as $zona)
                            <option value="{{ $zona->id_zona }}"
                                {{ old('id_zona') == $zona->id_zona ? 'selected' : '' }}>
                                {{ $zona->id_zona }} – {{ $zona->descripcion }}
                            </option>
                        @endforeach
                    </select>
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

                <button type="submit" class="btn btn-primary px-4 w-100">
                    Crear usuario corporativo
                </button>
            </form>
        </div>
    </div>
</div>
    <div class="tab-pane fade {{ request('tab') === 'comisiones' ? 'show active' : '' }}" id="comisiones">
    @include('admin.partials.commissions-table')
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