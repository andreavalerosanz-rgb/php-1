@extends('layouts.app')

@section('content')
<style>

    .dashboard-container {

        background: radial-gradient(circle at 50% -20%, #e0f2f1 0%, #f0fdfa 100%);
        min-height: 100vh;
        padding: 2rem 1.5rem;
        border-radius: 1rem;
    }


    .glass-panel {
        background: rgba(255, 255, 255, 0.90);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        border-radius: 1.25rem;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        height: 100%;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .glass-panel:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.08);
    }

    /* Headings */
    .panel-header {
        background: rgba(248, 250, 252, 0.5);
        border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .panel-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: #0f766e;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .panel-body { padding: 1.5rem; }


    .welcome-banner {
        background: linear-gradient(135deg, #0f9f9a, #0f766e);
        border-radius: 1.25rem;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 12px 30px rgba(15, 118, 110, 0.25);
        position: relative;
        overflow: hidden;
    }

    .welcome-banner::after {
        content: "";
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
        border-radius: 50%;
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        padding: 1.5rem;
    }
    .stat-icon-wrapper {
        width: 56px; height: 56px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    .stat-bg-red { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
    .stat-bg-blue { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }
    .stat-bg-green { background: rgba(25, 135, 84, 0.1); color: #198754; }
    .stat-bg-purple { background: rgba(111, 66, 193, 0.1); color: #6f42c1; }

    .stat-value { font-size: 1.75rem; font-weight: 800; line-height: 1.1; color: #0f172a; }
    .stat-label { font-size: 0.85rem; color: #64748b; font-weight: 500; }


    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1rem;
    }

    .action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 1.25rem 1rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        text-decoration: none;
        color: #475569;
        transition: all 0.2s ease;
        height: 100%;
    }
    .action-btn:hover {
        border-color: #0f9f9a;
        background: #f0fdfa;
        color: #0f766e;
        transform: translateY(-2px);
    }
    .action-btn svg { width: 28px; height: 28px; margin-bottom: 0.5rem; opacity: 0.8; }

    .create-res-btn {
        display: flex;
        align-items: center;
        gap: 1rem;
        width: 100%;
        padding: 1rem;
        border: 1px solid #e2e8f0;
        background: white;
        border-radius: 0.75rem;
        margin-bottom: 0.75rem;
        transition: all 0.2s;
        text-align: left;
    }
    .create-res-btn:hover {
        border-color: #facc6b;
        background: #fffbeb;
        transform: translateX(4px);
    }
    .create-res-icon {
        width: 36px; height: 36px;
        background: #fef3c7;
        color: #d97706;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
    }

</style>

<div class="dashboard-container">

    {{-- 1. WELCOME HERO --}}
    <div class="welcome-banner">
        <div class="d-flex justify-content-between align-items-end">
            <div>
                <h2 class="fw-bold mb-1">Hola, {{ Auth::guard('admin')->user()->nombre }}</h2>
                <p class="mb-0 opacity-75">
                    Bienvenido a tu panel de administración global.
                </p>
            </div>
            <span class="badge bg-white text-black px-3 py-2 rounded-pill fw-bold shadow-sm">
                ADMINISTRADOR
            </span>
        </div>
    </div>

    {{-- 2. KEY METRICS (Glass Cards) --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="glass-panel stat-card">
                <div class="stat-icon-wrapper stat-bg-red">
                    {{-- Icon: Clipboard Check --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['reservasTotales'] ?? 0 }}</div>
                    <div class="stat-label">Reservas Totales</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-panel stat-card">
                <div class="stat-icon-wrapper stat-bg-blue">
                    {{-- Icon: User Group --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['viajerosTotales'] ?? 0 }}</div>
                    <div class="stat-label">Viajeros Totales</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-panel stat-card">
                <div class="stat-icon-wrapper stat-bg-green">
                    {{-- Icon: Office Building --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['hotelesTotales'] ?? 0 }}</div>
                    <div class="stat-label">Hoteles</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-panel stat-card">
                <div class="stat-icon-wrapper stat-bg-purple">
                    {{-- Icon: Users --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['usuariosTotales'] ?? 0 }}</div>
                    <div class="stat-label">Usuarios</div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. MAIN CONTENT ROW --}}
    <div class="row g-4 mb-5">

        {{-- LEFT: ZONES ANALYSIS --}}
        <div class="col-lg-7">
            <div class="glass-panel">
                <div class="panel-header">
                    <h5 class="panel-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg>
                        Resumen por Zonas
                    </h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Exportar
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ url('/api/resumen-zonas') }}" target="_blank">Ver JSON</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.descargarJsonZonas') }}">Descargar JSON</a></li>
                        </ul>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Zona</th>
                                            <th class="text-center">Traslados</th>
                                            <th class="text-end">% Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($zonas as $z)
                                            <tr>
                                                <td class="fw-medium">{{ $z->zona }}</td>
                                                <td class="text-center">{{ $z->num_traslados }}</td>
                                                <td class="text-end text-muted small">{{ $z->porcentaje }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-5">
                             {{-- CHARTJS CANVAS --}}
                             <div style="position: relative; height: 200px; width: 100%;">
                                <canvas id="chartZonas"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: MANUAL RESERVATION --}}
        <div class="col-lg-5">
            <div class="glass-panel">
                <div class="panel-header">
                    <h5 class="panel-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Nueva Reserva Manual
                    </h5>
                </div>
                <div class="panel-body">
                    <p class="small text-muted mb-4">Selecciona el tipo de trayecto para crear una reserva en nombre de un cliente:</p>

                    {{-- Button 1 --}}
                    <form method="POST" action="{{ route('transfer.select-type.post') }}">
                        @csrf <input type="hidden" name="reservation_type" value="airport_to_hotel">
                        <button class="create-res-btn">
                            <div class="create-res-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
                            </div>
                            <div class="text-start">
                                <div class="fw-bold text-dark">Aeropuerto → Hotel</div>
                                <div class="small text-muted">Llegada a la isla</div>
                            </div>
                        </button>
                    </form>

                    {{-- Button 2 --}}
                    <form method="POST" action="{{ route('transfer.select-type.post') }}">
                        @csrf <input type="hidden" name="reservation_type" value="hotel_to_airport">
                        <button class="create-res-btn">
                            <div class="create-res-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                            </div>
                            <div class="text-start">
                                <div class="fw-bold text-dark">Hotel → Aeropuerto</div>
                                <div class="small text-muted">Salida de la isla</div>
                            </div>
                        </button>
                    </form>

                    {{-- Button 3 --}}
                    <form method="POST" action="{{ route('transfer.select-type.post') }}">
                        @csrf <input type="hidden" name="reservation_type" value="round_trip">
                        <button class="create-res-btn mb-0">
                            <div class="create-res-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                            </div>
                            <div class="text-start">
                                <div class="fw-bold text-dark">Ida y Vuelta</div>
                                <div class="small text-muted">Trayecto completo</div>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. QUICK LINKS GRID --}}
    <div class="glass-panel">
        <div class="panel-header">
            <h5 class="panel-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                Gestión y Reportes
            </h5>
        </div>
        <div class="panel-body">
            <div class="action-grid">

                <a href="{{ route('admin.hoteles.index') }}" class="action-btn">
                    {{-- Icon: Office Building --}}
                    <svg class="text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    <span class="fw-bold small">Hoteles</span>
                </a>

                <a href="{{ route('admin.reservations.list') }}" class="action-btn">
                    {{-- Icon: List Bullet --}}
                    <svg class="text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                    <span class="fw-bold small">Todas las Reservas</span>
                </a>

                <a href="{{ route('mis_reservas') }}" class="action-btn">
                    {{-- Icon: User --}}
                    <svg class="text-info" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    <span class="fw-bold small">Mis Reservas</span>
                </a>

                <a href="{{ route('calendar.index') }}" class="action-btn">
                    {{-- Icon: Calendar --}}
                    <svg class="text-warning" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    <span class="fw-bold small">Calendario</span>
                </a>

                <a href="{{ route('admin.vehiculos.index') }}" class="action-btn">
                    {{-- Icon: Truck --}}
                    <svg class="text-danger" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
                    <span class="fw-bold small">Vehículos</span>
                </a>

                 

            </div>
        </div>
    </div>
</div>

{{-- Scripts for Chart --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('chartZonas');
        if(ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($zonas->pluck('zona')) !!},
                    datasets: [{
                        data: {!! json_encode($zonas->pluck('num_traslados')) !!},
                        backgroundColor: [
                            '#0f9f9a', '#facc6b', '#3b82f6', '#ef4444',
                            '#8b5cf6', '#10b981', '#f59e0b', '#6b7280'
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                boxWidth: 12,
                                font: { size: 11 }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
