@extends('layouts.app')

@section('content')
<div class="alert alert-success text-center" role="alert">
    <h3>¡AUTENTICACIÓN EXITOSA!</h3>
</div>
<div class="card">
    <div class="card-header bg-success text-white">
        PANEL DE VIAJERO (USUARIO PARTICULAR)
    </div>
    <div class="card-body">
        <h5 class="card-title">Bienvenido/a, {{ Auth::guard('web')->user()->nombre }}</h5>
        <p class="card-text">Rol: Viajero. Aquí puedes gestionar tus reservas, hacer nuevas búsquedas de traslados y revisar tu perfil.</p>
        
        <a href="#" class="btn btn-success">Buscar Traslado</a>
        <a href="#" class="btn btn-secondary">Ver Mis Reservas</a>
    </div>
</div>
@endsection