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
        
        <a href="#" class="btn btn-danger">Gestión de Comisiones</a>
        <a href="#" class="btn btn-secondary">Ver Reservas</a>
        <a href="{{ route('calendar.index') }}" class="btn btn-success">Calendario</a>
    </div>
</div>
@endsection