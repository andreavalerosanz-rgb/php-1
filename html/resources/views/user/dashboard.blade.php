@extends('layouts.app')

@section('content')
<style>
    /* ====== SAME STYLE AS ADMIN DASHBOARD ====== */
    .dashboard-container {
        background: radial-gradient(circle at 50% -20%, #e0f2f1 0%, #f0fdfa 100%);
        min-height: 100vh;
        padding: 2rem 1.5rem;
        border-radius: 1rem;
    }

    .glass-panel {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        border-radius: 1.25rem;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        height: 100%;
    }

    .glass-panel:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.08);
    }

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
        font-size: 0.9rem;
        color: #0f766e;
        text-transform: uppercase;
        letter-spacing: .05em;
        margin: 0;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .panel-body { padding: 1.5rem; }

    .welcome-banner {
        background: linear-gradient(135deg, #0f9f9a, #0f766e);
        border-radius: 1.25rem;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 12px 30px rgba(15, 118, 110, 0.25);
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        padding: 1.5rem;
    }

    .stat-icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-bg-blue { background: rgba(13,110,253,.1); color:#0d6efd; }

    .stat-value { font-size: 1.8rem; font-weight: 800; }
    .stat-label { font-size: .85rem; color:#64748b; }

    .create-res-btn {
        display: flex;
        align-items: center;
        gap: 1rem;
        width: 100%;
        padding: 1rem;
        border: 1px solid #e2e8f0;
        background: white;
        border-radius: .75rem;
        margin-bottom: .75rem;
        transition: all .2s;
        text-align: left;
    }

    .create-res-btn:hover {
        border-color: #0f9f9a;
        background: #f0fdfa;
        transform: translateX(4px);
    }

    .create-res-icon {
        width: 36px;
        height: 36px;
        background: #ccfbf1;
        color: #0f766e;
        border-radius: 50%;
        display:flex;
        align-items:center;
        justify-content:center;
    }

    .quick-link {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: .75rem;
    padding: 1.75rem 1rem;
    border-radius: 1.25rem;
    background: rgba(255,255,255,.85);
    border: 1px solid rgba(226,232,240,.8);
    text-decoration: none;
    color: #0f172a;
    box-shadow: 0 8px 20px rgba(15,23,42,.05);
    transition: all .25s ease;
}

.quick-link:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 30px rgba(15,23,42,.12);
    background: #f0fdfa;
}

.quick-link-icon {
    width: 54px;
    height: 54px;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quick-link span {
    font-size: .95rem;
    font-weight: 700;
}

/* Color variants */
.quick-transfer .quick-link-icon {
    background: rgba(14,165,233,.15);
    color: #0284c7;
}

.quick-reservas .quick-link-icon {
    background: rgba(59,130,246,.15);
    color: #2563eb;
}

.quick-calendar .quick-link-icon {
    background: rgba(16,185,129,.15);
    color: #059669;
}

.quick-profile .quick-link-icon {
    background: rgba(100,116,139,.15);
    color: #475569;
}
</style>

<div class="dashboard-container">
    {{-- WELCOME --}}
    <div class="welcome-banner">
        <div class="d-flex justify-content-between align-items-end">
            <div>
                <h2 class="fw-bold mb-1">
                    {{ Auth::guard('web')->user()->nombre }} {{ Auth::guard('web')->user()->apellido1 }}
                </h2>
                <p class="mb-0 opacity-75">
                    Panel de usuario particular
                </p>
            </div>
            <span class="badge bg-white text-dark fw-bold px-3 py-2 rounded-pill">
                VIAJERO
            </span>
        </div>
    </div>

    {{-- STATS --}}
    <div class="row g-4 justify-content-center mb-5">
        <div class="col-md-4">
            <div class="glass-panel stat-card">
                <div class="stat-icon-wrapper stat-bg-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" />
                    </svg>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['totalReservas'] ?? 0 }}</div>
                    <div class="stat-label">Reservas Totales</div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="row g-4">
        {{-- QUICK LINKS --}}
        <div class="col-12">
            <div class="glass-panel">
                <div class="panel-header">
                    <h5 class="panel-title text-center">Accesos rápidos</h5>
                </div>

                <div class="panel-body">

    <div class="row g-4 justify-content-center">

        <div class="col-md-4 col-6">
            <a href="{{ route('transfer.select-type') }}"
               class="quick-link quick-transfer">
                <div class="quick-link-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </div>
                <span>Nuevo Traslado</span>
            </a>
        </div>

        <div class="col-md-4 col-6">
            <a href="{{ route('mis_reservas') }}"
               class="quick-link quick-reservas">
                <div class="quick-link-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </div>
                <span>Mis Reservas</span>
            </a>
        </div>

        <div class="col-md-4 col-6">
            <a href="{{ route('calendar.index') }}"
               class="quick-link quick-calendar">
                <div class="quick-link-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span>Calendario</span>
            </a>
        </div>

    </div>
</div>
            </div>
        </div>
    </div>
</div>
@endsection
