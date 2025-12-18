@extends('layouts.app')

@section('content')
<style>
    .dashboard-container { min-height: 100vh; padding: 2rem 1.5rem; }
    .glass-panel {
        background: rgba(255,255,255,.96);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,.6);
        border-radius: 1.25rem;
        box-shadow: 0 10px 30px rgba(15,23,42,.06);
        overflow: hidden;
    }
    .panel-header {
        background: linear-gradient(90deg,#0f9f9a,#0f766e);
        color: white;
        padding: 1.75rem 2rem;
        text-align: center;
    }
    .btn-confirm,
    .btn-cancel {
        border-radius: .75rem;
        font-weight: 700;
        padding: .75rem 2.25rem;
        font-size: 0.95rem;
        min-height: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-confirm {
        background-color: #0f766e;
        border: 1px solid #0f766e;
        color: #ffffff;
    }
    .btn-confirm:hover {
        background-color: #0d9488;
        border-color: #0d9488;
        color: #ffffff;
    }
    .btn-cancel {
        color: #475569;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
    }
    .btn-cancel:hover {
        background-color: #f1f5f9;
        color: #0f172a;
    }
</style>

<div class="dashboard-container">
    <div class="container-fluid px-lg-4">
        <div class="glass-panel text-center">

            <div class="panel-header">
                <h2 class="mb-0">¡Reserva Actualizada Correctamente!</h2>
            </div>

            <div class="p-5">
                <i class="fas fa-sync-alt fa-5x text-success mb-4"></i>

                <p class="lead mb-4">
                    Los datos de tu traslado se han actualizado correctamente.<br>
                    A partir de ahora se aplicarán los nuevos detalles.
                </p>

                <div class="mb-4">
                    <h5 class="fw-bold text-muted">Localizador de Reserva</h5>
                    <div class="d-inline-block px-4 py-2 bg-light border rounded">
                        <span class="h4 fw-bold text-teal">
                            {{ $reserva->localizador }}
                        </span>
                    </div>
                </div>

                <p class="text-muted fst-italic">
                    Si el cambio afecta a fecha, vehículo o precio, el sistema lo tendrá en cuenta automáticamente.
                </p>

                <hr class="my-5">

                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('mis_reservas') }}" class="btn btn-confirm">
                        Ver Mis Reservas
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-cancel">
                        Volver al Inicio
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
