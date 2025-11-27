@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="p-5 mb-4 bg-light rounded-3">
            <div class="container-fluid py-5">
                <h1 class="display-5 fw-bold">Bienvenido al Producto 3 (Laravel)</h1>
                <p class="col-md-8 fs-4">Migración de la aplicación de traslados a Laravel con mejora gráfica y gestión de comisiones.</p>
                <p>Usa la opción Login en el menú para acceder a los paneles de Administrador, Corporativo o Viajero.</p>
                <a class="btn btn-primary btn-lg" href="{{ route('login') }}" role="button">Ir a Login</a>
            </div>
        </div>
    </div>
</div>
@endsection