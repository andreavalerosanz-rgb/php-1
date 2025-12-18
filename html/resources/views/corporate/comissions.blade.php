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
        padding: 1rem;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }

    .custom-table tbody td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
        color: #334155;
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
</style>

<div class="dashboard-container">
    <div class="container-fluid px-lg-4">
        <div class="glass-panel">

            {{-- HEADER --}}
            <div class="panel-header-brand">
                <h5 class="mb-0 fw-bold">
                    Comisiones de su Hotel
                    @if(is_numeric($month))
    ({{ ucfirst(\Carbon\Carbon::createFromDate($year, $month, 1)
        ->locale('es')
        ->isoFormat('MMMM YYYY')) }})
@else
    (todas)
@endif
                </h5>

                <div class="badge bg-white text-dark fw-bold">
                    Total: {{ count($commissionReport) }}
                </div>
            </div>

            {{-- FILTRO --}}
            <form method="GET" action="{{ route('corporate.comissions') }}" class="row g-3 p-3">

                {{-- MES --}}
                <div class="col-auto">
                    <select name="month" class="form-select">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                {{ ucfirst(\Carbon\Carbon::createFromDate($year, $i, 1)->locale('es')->isoFormat('MMMM')) }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- AÑO --}}
                <div class="col-auto">
                    <select name="year" class="form-select">
                        @for ($i = now()->year - 2; $i <= now()->year + 1; $i++)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- BOTÓN FILTRAR --}}
                <div class="col-auto">
                    <button type="submit" class="btn btn-teal">
                        Filtrar
                    </button>
                </div>

                {{-- BOTÓN VER TODAS --}}
                <div class="col-auto">
                    <a href="{{ route('corporate.comissions', ['all' => 1]) }}"
                       class="btn btn-outline-secondary">
                        Ver todas
                    </a>
                </div>
            </form>

            {{-- TABLA --}}
            <div class="table-responsive p-3">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>Reserva</th>
                            <th>Fecha traslado</th>
                            <th class="text-end">Precio</th>
                            <th class="text-end">Comisión</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commissionReport as $report)
                            <tr>
                                <td>
                                    <span class="loc-code">{{ $report['localizador'] }}</span>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($report['fecha_traslado'])->format('d-m-Y') }}
                                </td>
                                <td class="text-end text-teal">
                                    {{ number_format($report['precio_total'], 2) }} €
                                </td>
                                <td class="text-end text-success">
                                    {{ number_format($report['comision_hotel'], 2) }} €
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    No se encontraron comisiones.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3"></td>
                            <td class="text-end fw-bold">
                                TOTAL: {{ number_format($totalComision, 2) }} €
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection