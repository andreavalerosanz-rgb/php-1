@extends('layouts.app')

@section('content')
<div class="alert alert-success text-center" role="alert">
    <h3>¡AUTENTICACIÓN EXITOSA!</h3>
</div>
<div class="card">
    <div class="card-header bg-danger text-white">
        PANEL DE ADMINISTRACIÓN
    </div>
    <div class="card-body">
        <h5 class="card-title">Bienvenido, {{ Auth::guard('admin')->user()->nombre }}</h5>
        <p class="card-text">Rol: Administrador. Aquí gestionarás las reservas, tarifas y comisiones de los hoteles.</p>
        <hr>
<h4 class="mt-3">Resumen del sistema</h4>

<div class="row text-center mt-3">
    <div class="col-md-3">
        <div class="p-3 bg-light border rounded">
            <h2>{{ $stats['reservasTotales'] }}</h2>
            <p>Reservas Totales</p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="p-3 bg-light border rounded">
            <h2>{{ $stats['viajerosTotales'] }}</h2>
            <p>Viajeros Totales</p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="p-3 bg-light border rounded">
            <h2>{{ $stats['hotelesTotales'] }}</h2>
            <p>Hoteles Registrados</p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="p-3 bg-light border rounded">
            <h2>{{ $stats['usuariosTotales'] }}</h2>
            <p>Usuarios Totales</p>
        </div>
    </div>
</div>
        <a href="#" class="btn btn-danger">Gestión de Comisiones</a>
        <a href="{{ route('mis_reservas') }}" class="btn btn-secondary">Ver Reservas</a>
        <a href="{{ route('calendar.index') }}" class="btn btn-success">Calendario</a>
    </div>
</div>
@endsection