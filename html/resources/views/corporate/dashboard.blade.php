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
    .stat-bg-green { background: rgba(25,135,84,.1); color:#198754; }
    .stat-bg-purple { background: rgba(111,66,193,.1); color:#6f42c1; }

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
    background: rgba(255,255,255,.88);
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
    text-align: center;
}

/* Variantes de color */
.quick-reservas .quick-link-icon {
    background: rgba(59,130,246,.15);
    color: #2563eb;
}

.quick-calendar .quick-link-icon {
    background: rgba(16,185,129,.15);
    color: #059669;
}

.quick-comissions .quick-link-icon {
    background: rgba(245,158,11,.2);
    color: #b45309;
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
                    {{ Auth::guard('corporate')->user()->nombre }}
                </h2>
                <p class="mb-0 opacity-75">
                    Panel corporativo del hotel
                </p>
            </div>
            <span class="badge bg-white text-dark fw-bold px-3 py-2 rounded-pill">
                HOTEL
            </span>
        </div>
    </div>

    {{-- STATS --}}
    <div class="row g-4 mb-5">
    {{-- Reservas --}}
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
                <div class="stat-value">{{ $stats['totalTraslados'] ?? 0 }}</div>
                <div class="stat-label">Reservas Totales</div>
            </div>
        </div>
    </div>

    {{-- Comisión --}}
    <div class="col-md-4">
        <div class="glass-panel stat-card">
            <div class="stat-icon-wrapper stat-bg-green">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 11.625h4.5m-4.5 2.25h4.5m2.121 1.527c-1.171 1.464-3.07 1.464-4.242 0-1.172-1.465-1.172-3.84 0-5.304 1.171-1.464 3.07-1.464 4.242 0M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
            </div>
            <div>
                <div class="stat-value">
                    {{ Auth::guard('corporate')->user()->Comision }}%
                </div>
                <div class="stat-label">Comisión aplicada</div>
            </div>
        </div>
    </div>

    {{-- Zona --}}
    <div class="col-md-4">
        <div class="glass-panel stat-card">
            <div class="stat-icon-wrapper stat-bg-purple">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <div class="stat-value">
    {{ Auth::guard('corporate')->user()->zona->descripcion ?? 'Sin zona' }}
</div>
                <div class="stat-label">Zona asignada</div>
            </div>
        </div>
    </div>
    </div> {{-- END STATS ROW --}}

    {{-- MAIN CONTENT --}}
    <div class="row g-4">

        {{-- CREATE RESERVATION --}}
        <div class="col-lg-6">
            <div class="glass-panel">
                <div class="panel-header">
                    <h5 class="panel-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                             fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0"/>
                        </svg>
                        Nueva reserva para huésped
                    </h5>
                </div>

                <div class="panel-body">

                    {{-- Aeropuerto → Hotel --}}
                    <form method="POST" action="{{ route('transfer.select-type.post') }}">
                        @csrf
                        <input type="hidden" name="reservation_type" value="airport_to_hotel">
                        <button class="create-res-btn">
                            <div class="create-res-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                     fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                            </div>
                            <div>
                                <div class="fw-bold">Aeropuerto → Hotel</div>
                                <div class="small text-muted">Llegada del cliente</div>
                            </div>
                        </button>
                    </form>

                    {{-- Hotel → Aeropuerto --}}
                    <form method="POST" action="{{ route('transfer.select-type.post') }}">
                        @csrf
                        <input type="hidden" name="reservation_type" value="hotel_to_airport">
                        <button class="create-res-btn">
                            <div class="create-res-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                     fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                </svg>
                            </div>
                            <div>
                                <div class="fw-bold">Hotel → Aeropuerto</div>
                                <div class="small text-muted">Salida del cliente</div>
                            </div>
                        </button>
                    </form>

                    {{-- Ida y vuelta --}}
                    <form method="POST" action="{{ route('transfer.select-type.post') }}">
                        @csrf
                        <input type="hidden" name="reservation_type" value="round_trip">
                        <button class="create-res-btn mb-0">
                            <div class="create-res-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                     fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M8 7h12l-4-4m4 4l-4 4M16 17H4l4 4m-4-4l4-4"/>
                                </svg>
                            </div>
                            <div>
                                <div class="fw-bold">Ida y vuelta</div>
                                <div class="small text-muted">Trayecto completo</div>
                            </div>
                        </button>
                    </form>

                </div>
            </div>
        </div>

       {{-- QUICK LINKS --}}
        <div class="col-lg-6">
            <div class="glass-panel">
                <div class="panel-header">
                    <h5 class="panel-title text-center">Accesos rápidos</h5>
                </div>

                <div class="panel-body">
                    <div class="row g-4 justify-content-center">

                        <div class="col-12 col-md-6">
                            <a href="{{ route('mis_reservas') }}"
                            class="quick-link quick-reservas">
                                <div class="quick-link-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26"
                                        fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                    </svg>
                                </div>
                                <span>Mis Reservas</span>
                            </a>
                        </div>

                        <div class="col-12 col-md-6">
                            <a href="{{ route('calendar.index') }}"
                            class="quick-link quick-calendar">
                                <div class="quick-link-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26"
                                        fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 7V3m8 4V3m-9 8h10
                                            M5 21h14a2 2 0 002-2V7
                                            a2 2 0 00-2-2H5a2 2 0
                                            00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span>Calendario</span>
                            </a>
                        </div>

                        <div class="col-12 col-md-6">
                            <a href="{{ route('corporate.comissions') }}"
                            class="quick-link quick-comissions">
                                <div class="quick-link-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26"
                                        fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375
                                            3.375 0 0 0-3.375-3.375h-1.5
                                            A1.125 1.125 0 0 1 13.5 7.125v-1.5
                                            a3.375 3.375 0 0 0-3.375-3.375H8.25
                                            m0 11.625h4.5m-4.5 2.25h4.5
                                            m2.121 1.527c-1.171 1.464-3.07
                                            1.464-4.242 0-1.172-1.465-1.172-3.84
                                            0-5.304 1.171-1.464 3.07-1.464 4.242 0M10.5 2.25H5.625
                                            c-.621 0-1.125.504-1.125 1.125v17.25
                                            c0 .621.504 1.125 1.125 1.125h12.75
                                            c.621 0 1.125-.504 1.125-1.125V11.25
                                            a9 9 0 0 0-9-9Z"/>
                                    </svg>
                                </div>
                                <span>Comisiones</span>
                            </a>
                        </div>



                    </div>
</div>
            </div>
        </div>

    </div>
</div>
@endsection
