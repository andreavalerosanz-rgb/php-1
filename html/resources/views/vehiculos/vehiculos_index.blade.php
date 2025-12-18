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

/* TABLE WRAPPER */
.table-responsive {
    overflow-x: auto;
}

/* TABLE */
.custom-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

/* CABECERAS  */
.custom-table thead th {
    background-color: #f1f5f9;
    color: #475569;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.72rem;
    letter-spacing: 0.05em;
    padding: 1rem 0.75rem;
    border-bottom: 2px solid #e2e8f0;
    text-align: center;
    vertical-align: middle;
}

/*  CELDAS */
.custom-table tbody td {
    padding: 0.6rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.9rem;
    color: #334155;
}

.custom-table td:nth-child(1),
.custom-table td:nth-last-child(1) {
    white-space: nowrap;
}

/* TEXTOS */
.fw-medium { font-weight: 600; }
.text-teal { color: #0f766e; }

/* ESTADOS */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.25rem 0.6rem;
    border-radius: 999px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
}
.status-success { background: #dcfce7; color: #166534; }
.status-danger  { background: #fee2e2; color: #991b1b; }

/* BOTONES */
.btn-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    transition: 0.15s;
    text-decoration: none;
}

.btn-edit {
    background-color: #facc6b;
    color: #78350f;
}

.btn-toggle-on {
    background-color: #dcfce7;
    color: #166534;
}

.btn-toggle-off {
    background-color: #fee2e2;
    color: #991b1b;
}

.btn-add {
    background: #0f766e;
    border: none;
    color: white;
    font-weight: 700;
    border-radius: 12px;
    padding: 0.6rem 1rem;
    transition: 0.15s;
}
.btn-add:hover {
    background: #0b5f59;
    color: white;
}

/* ICONOS */
.td-icon {
    width: 16px;
    height: 16px;
    color: #94a3b8;
    margin-right: 0.35rem;
}

.alert-success-custom {
    background: #ecfeff;
    border: 1px solid #a5f3fc;
    color: #155e75;
    border-radius: 12px;
    padding: 0.75rem 1rem;
    margin: 1rem 2rem 0 2rem;
}
</style>

<div class="dashboard-wrapper">
    <div class="container-fluid px-lg-5">
        <div class="glass-card">

            {{-- Header --}}
            <div class="card-header-custom">
                <div class="d-flex align-items-center gap-2">
                    {{-- Icon: Car (minimal) --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor"
                        style="width:24px;height:24px;">
                    <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5.25 11.25h13.5l-1.2-3.6A1.5 1.5 0 0016.125 6H7.875a1.5 1.5 0 00-1.425 1.05l-1.2 3.6zM4.5 11.25v4.5A1.5 1.5 0 006 17.25h12a1.5 1.5 0 001.5-1.5v-4.5M7.5 17.25a.75.75 0 100-1.5.75.75 0 000 1.5zm9 0a.75.75 0 100-1.5.75.75 0 000 1.5z" />
                    </svg>

                    <h5 class="mb-0 fw-bold">Listado de Vehículos</h5>
                </div>

                <div class="small opacity-75">
                    {{ $vehiculos->count() ?? count($vehiculos) }} Registros
                </div>
            </div>

            {{-- Mensaje success --}}
            @if(session('success'))
                <div class="alert-success-custom">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Acciones header --}}
            <div class="px-4 pt-4 d-flex justify-content-start">
                <a href="{{ route('admin.vehiculos.create') }}" class="btn-add">
                    + Añadir Vehículo
                </a>
            </div>

            {{-- Tabla --}}
            <div class="table-responsive p-4">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descripción</th>
                            <th>Email Conductor</th>
                            <th>Matrícula</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($vehiculos as $v)
                            <tr>
                                <td class="text-center">
                                    <span class="small text-muted">#{{ $v->id_vehiculo }}</span>
                                </td>

                                <td>
                                    <span class="fw-medium text-teal">{{ $v->descripcion }}</span>
                                </td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        {{-- Icon: Mail --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="td-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 7.5v9a2.25 2.25 0 01-2.25 2.25H4.5A2.25 2.25 0 012.25 16.5v-9A2.25 2.25 0 014.5 5.25h15A2.25 2.25 0 0121.75 7.5z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 7.5l-9.75 6.75L2.25 7.5" />
                                        </svg>
                                        <span>{{ $v->email_conductor }}</span>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-light text-dark border" style="font-family: monospace;">
                                        {{ $v->password }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    @if($v->activo)
                                        <span class="status-badge status-success">
                                            {{-- Icon: Check --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Activo
                                        </span>
                                    @else
                                        <span class="status-badge status-danger">
                                            {{-- Icon: X --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Inhabilitado
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">

                                        {{-- Edit --}}
                                        <a href="{{ route('admin.vehiculos.edit', $v->id_vehiculo) }}" class="btn-icon btn-edit" title="Editar">
                                            {{-- Pencil --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>

                                        {{-- Toggle Active --}}
                                        @if($v->activo)
                                            <form method="POST" action="{{ route('admin.vehiculos.disable', $v->id_vehiculo) }}" style="display:inline-block"
                                                  onsubmit="return confirm('¿Seguro que quieres inhabilitar este vehículo?');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn-icon btn-toggle-off" title="Inhabilitar">
                                                    {{-- Lock --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V7.875a4.5 4.5 0 10-9 0V10.5m-.75 0h10.5A2.25 2.25 0 0119.5 12.75v6A2.25 2.25 0 0117.25 21H6.75A2.25 2.25 0 014.5 18.75v-6A2.25 2.25 0 016.75 10.5z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.vehiculos.enable', $v->id_vehiculo) }}" style="display:inline-block"
                                                  onsubmit="return confirm('¿Seguro que quieres habilitar este vehículo?');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn-icon btn-toggle-on" title="Habilitar">
                                                    {{-- Unlock --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H6.75A2.25 2.25 0 004.5 12.75v6A2.25 2.25 0 006.75 21h10.5a2.25 2.25 0 002.25-2.25v-6A2.25 2.25 0 0017.25 10.5h-.75V7.875a4.5 4.5 0 00-8.681-1.5" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 42px; height: 42px; opacity: 0.5;">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l6.75-6.75M4.5 12l6.75 6.75" />
                                        </svg>
                                    </div>
                                    <h6 class="text-secondary">No hay vehículos registrados</h6>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
