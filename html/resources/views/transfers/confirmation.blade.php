@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-lg border-success">
            <div class="card-header bg-success text-white text-center">
                <h2>¡Reserva Confirmada con Éxito! 🎉</h2>
            </div>
            <div class="card-body text-center">
                <i class="fas fa-check-circle fa-5x text-success mb-4"></i>
                
                <p class="lead">Tu traslado ha sido reservado. Recibirás un correo electrónico con todos los detalles.</p>
                
                <h3 class="mt-4">Localizador de Reserva</h3>
                <div class="p-3 bg-light border rounded d-inline-block">
                    <strong class="h4 text-primary">{{ $localizador }}</strong>
                </div>
                
                <p class="mt-4">
                    **Resumen:** (Aquí iría la tabla de precios, vehículo, fechas y horas seleccionadas)
                    <br>
                    *La confirmación final con el vehículo y precio se adjuntará al correo.*
                </p>

                <hr class="my-4">

                <div class="d-grid gap-2 d-md-block">
                    <a href="{{ route('transfer.select-type') }}" class="btn btn-primary btn-lg"><i class="fas fa-plus-circle"></i> Nueva Reserva</a>
                    <a href="#" class="btn btn-secondary btn-lg"><i class="fas fa-calendar-alt"></i> Ver Mi Calendario</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection