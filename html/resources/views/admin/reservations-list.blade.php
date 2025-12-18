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
    }

    .panel-header-brand {
        background: linear-gradient(90deg, #0f9f9a, #0f766e);
        padding: 1.5rem 2rem;
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
    
    .loc-code {
        font-family: monospace;
        background: #f1f5f9;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        color: #0f172a;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .text-teal { color: #0f766e; font-weight: 700; }
    .text-gold { color: #d97706; font-weight: 700; }

    .btn-back {
        color: #0f766e;
        text-decoration: none;
        display: inline-flex; align-items: center; gap: 0.5rem;
        font-weight: 600; margin-bottom: 1.5rem;
        transition: transform 0.2s;
    }
    .btn-back:hover { transform: translateX(-4px); color: #0d9488; }
    
    .pagination {
        margin-bottom: 0;
        justify-content: center;
    }
    .page-link {
        color: #0f766e;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 0.75rem;
    }
    .page-item.active .page-link {
        background-color: #0f766e;
        border-color: #0f766e;
        color: white;
    }
    .page-link:hover {
        background-color: #f0fdfa;
        color: #0f766e;
    }
    .glass-panel {
    overflow-x: hidden;
}
.custom-table tbody tr.reserva-confirmada td {
    background-color: #eff6ff !important; /* azul */
}

.custom-table tbody tr.reserva-finalizada td {
    background-color: #f0fdf4 !important; /* verde */
}

.custom-table tbody tr.reserva-anulada td {
    background-color: #fef2f2 !important; /* rojo */
}

/* Hover respetando estado */
.custom-table tbody tr.reserva-confirmada:hover td {
    background-color: #dbeafe !important;
}

.custom-table tbody tr.reserva-finalizada:hover td {
    background-color: #dcfce7 !important;
}

.custom-table tbody tr.reserva-anulada:hover td {
    background-color: #fee2e2 !important;
}

.legend {
    display: flex;
    gap: 1.25rem;
    align-items: center;
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 700;
    color: #1f2937;
}

.legend-color {
    width: 14px;
    height: 14px;
    border-radius: 4px;
}

.legend-confirmada {
    background-color: #3b82f6; /* azul */
}

.legend-finalizada {
    background-color: #22c55e; /* verde */
}

.legend-anulada {
    background-color: #ef4444; /* rojo */
}
</style>

<div class="dashboard-container">
    <div class="container-fluid px-lg-4">

        <div class="glass-panel">
            {{-- Header --}}
            <div class="panel-header-brand">
                <div class="d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" />
                    </svg>
                    <h5 class="mb-0 fw-bold">Listado General de Reservas</h5>
                </div>
                <div class="badge bg-white text-teal bg-opacity-90 shadow-sm text-dark">
                    Total: {{ $reservas->total() }}
                </div>
            </div>

            {{-- ========================= --}}
            {{-- FILTROS EN VISTA --}}
            {{-- ========================= --}}
            <div class="p-3 border-bottom">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-teal">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="">Todos</option>
                            <option value="confirmada" {{ request('estado')=='confirmada' ? 'selected' : '' }}>Confirmada</option>
<option value="finalizada" {{ request('estado')=='finalizada' ? 'selected' : '' }}>Finalizada</option>
<option value="anulada" {{ request('estado')=='anulada' ? 'selected' : '' }}>Anulada</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-teal">Desde</label>
                        <input
    type="date"
    id="fecha_desde"
    name="fecha_desde"
    class="form-control"
    value="{{ request('fecha_desde') }}"
>
                    </div>

                    <div class="col-md-3">
    <label class="form-label fw-semibold text-teal">Hasta</label>
    <input
    type="date"
    id="fecha_hasta"
    name="fecha_hasta"
    class="form-control"
    value="{{ request('fecha_hasta') }}"
>
</div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-teal w-100">Filtrar</button>
                    </div>
                </form>
                {{-- LEYENDA ESTADOS --}}
<div class="px-3 pt-3">
    <div class="legend">
        <div class="legend-item">
            <span class="legend-color legend-confirmada"></span>
            Confirmada
        </div>
        <div class="legend-item">
            <span class="legend-color legend-finalizada"></span>
            Finalizada
        </div>
        <div class="legend-item">
            <span class="legend-color legend-anulada"></span>
            Anulada
        </div>
    </div>
</div>

            </div>

            {{-- ========================= --}}
            {{-- ORDEN + FILTRO --}}
            {{-- ========================= --}}
            @php
                $reservasOrdenadas = $reservas->getCollection()->sortBy(function ($reserva) {
                    return $reserva->fechaLimite();
                });

                $reservasFiltradas = $reservasOrdenadas->filter(function ($reserva) {
                    if (request('estado') && $reserva->estado !== request('estado')) {
                        return false;
                    }

                    $fecha = optional($reserva->fechaLimite())->format('Y-m-d');

                    if (request('fecha_desde') && $fecha < request('fecha_desde')) {
                        return false;
                    }

                    if (request('fecha_hasta') && $fecha > request('fecha_hasta')) {
                        return false;
                    }

                    return true;
                });
            @endphp

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>Localizador</th>
                            <th>Tipo</th>
                            <th>Vehículo</th>
                            <th>Hotel</th>
                            <th>Zona</th>
                            <th>Fecha Traslado</th>
                            <th>Pasajeros</th>
                            <th>Precio Total</th>
                            <th>Comisión</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservasFiltradas as $reserva)
                             <tr class="
        @if($reserva->estado === 'confirmada')
            reserva-confirmada
        @elseif($reserva->estado === 'finalizada')
            reserva-finalizada
        @elseif($reserva->estado === 'anulada')
            reserva-anulada
        @endif
    ">
                                <td><span class="loc-code">{{ $reserva->localizador }}</span></td>

                                <td>
                                    <span class="badge bg-light text-secondary border fw-normal">
                                        {{ $reserva->tipo_traslado_nombre }}
                                    </span>
                                </td>

                                <td>{{ $reserva->vehiculo->descripcion ?? '-' }}</td>

                                <td class="text-teal">{{ $reserva->hotel->nombre ?? 'N/A' }}</td>

                                <td>
                                    {{ $reserva->zona->descripcion
                                        ?? $reserva->hotel->zona->descripcion
                                        ?? 'N/A' }}
                                </td>

                                <td>
                                    <div class="d-flex flex-column" style="line-height:1.2">
                                        <span>{{ optional($reserva->fechaLimite())->format('Y-m-d') ?? 'N/A' }}</span>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-white text-dark border">
                                        {{ $reserva->num_viajeros }}
                                    </span>
                                </td>

                                <td class="text-end text-teal">
                                    {{ number_format($reserva->precio_total, 2) }} €
                                </td>

                                <td class="text-end text-gold">
                                    +{{ number_format($reserva->comision_ganada, 2) }} €
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.reserva.detalle', $reserva->id_reserva) }}"
                                       class="btn btn-sm btn-light text-teal fw-bold border">
                                        Ver Detalle
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            <div class="p-4 border-top">
                {{ $reservas->links() }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const desde = document.getElementById('fecha_desde');
    const hasta = document.getElementById('fecha_hasta');

    if (!desde || !hasta) return;

    function syncFechas() {
        if (desde.value) {
            // Bloquear fechas anteriores en "hasta"
            hasta.min = desde.value;

            // Si la fecha hasta es anterior, la borramos
            if (hasta.value && hasta.value < desde.value) {
                hasta.value = '';
            }
        } else {
            hasta.removeAttribute('min');
        }
    }

    // Al cambiar "desde"
    desde.addEventListener('change', syncFechas);

    // Al cargar la página (caso filtros activos)
    syncFechas();
});
</script>

@endsection