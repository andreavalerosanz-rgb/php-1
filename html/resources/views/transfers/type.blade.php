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
        background: rgba(255,255,255,.9);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,.6);
        border-radius: 1.25rem;
        box-shadow: 0 10px 30px rgba(15,23,42,.05);
        overflow: hidden;
    }

    .panel-header {
        background: rgba(248,250,252,.6);
        border-bottom: 1px solid rgba(226,232,240,.6);
        padding: 1rem 1.5rem;
    }

    .panel-title {
        font-weight: 700;
        font-size: .95rem;
        color: #0f766e;
        text-transform: uppercase;
        letter-spacing: .05em;
        margin: 0;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .panel-body { padding: 1.75rem; }

    /* Reservation option */
    .reservation-option {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        background: white;
        cursor: pointer;
        transition: all .2s ease;
    }

    .reservation-option:hover {
        border-color: #0f9f9a;
        background: #f0fdfa;
        transform: translateX(4px);
    }

    .reservation-option input { display: none; }

    .reservation-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: #f0fdfa;
        color: #0f766e;
    }

    .reservation-option input:checked + .reservation-icon {
        background: #0f766e;
        color: white;
    }

    .reservation-label {
        font-weight: 700;
        color: #0f172a;
    }

    .reservation-desc {
        font-size: .85rem;
        color: #64748b;
    }

    .btn-confirm {
        border-radius: .75rem;
        font-weight: 700;
        padding: .75rem 2.25rem;
        background: #0f766e;
        color: white;
        border: none;
    }

    .btn-confirm:hover {
        background: #0d9488;
    }
</style>

<div class="dashboard-container">
    <div class="container-fluid px-lg-4">
        <div class="glass-panel">

            <div class="panel-header">
                <h5 class="panel-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    Selecciona el tipo de reserva
                </h5>
            </div>

            <form method="POST" action="{{ route('transfer.select-type.post') }}">
                @csrf

                <div class="panel-body d-grid gap-3">

                    {{-- Airport → Hotel --}}
                    <label class="reservation-option">
                        <input type="radio" name="reservation_type" value="airport_to_hotel" checked required>
                        <div class="reservation-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        </div>
                        <div>
                            <div class="reservation-label">Aeropuerto → Hotel</div>
                            <div class="reservation-desc">Llegada a la isla</div>
                        </div>
                    </label>

                    {{-- Hotel → Airport --}}
                    <label class="reservation-option">
                        <input type="radio" name="reservation_type" value="hotel_to_airport" required>
                        <div class="reservation-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                        </div>
                        <div>
                            <div class="reservation-label">Hotel → Aeropuerto</div>
                            <div class="reservation-desc">Salida de la isla</div>
                        </div>
                    </label>

                    {{-- Round trip --}}
                    <label class="reservation-option">
                        <input type="radio" name="reservation_type" value="round_trip" required>
                        <div class="reservation-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <div>
                            <div class="reservation-label">Ida y Vuelta</div>
                            <div class="reservation-desc">Trayecto completo</div>
                        </div>
                    </label>

                    @error('reservation_type')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="panel-body d-flex justify-content-end pt-0">
                    <button type="submit" class="btn btn-confirm">
                        Continuar
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection