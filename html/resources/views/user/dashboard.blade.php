@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="alert alert-success text-center" role="alert">
            <h3>¡AUTENTICACIÓN EXITOSA!</h3>
        </div>
        <div class="card shadow-lg">
            <div class="card-header bg-success text-white">
                PANEL DE VIAJERO (USUARIO PARTICULAR)
            </div>
            <div class="card-body">
                {{-- Usamos Auth::guard('web') para acceder a los datos del Viajero --}}
                <h5 class="card-title">
                    Bienvenido/a, {{ Auth::guard('web')->user()->nombre }} {{ Auth::guard('web')->user()->apellido1 }}
                </h5>
                <hr>
<h4>Tu resumen de actividad</h4>

<div class="alert alert-success text-center mt-3">
    <h2>{{ $stats['totalReservas'] }}</h2>
    <p>Reservas totales</p>
</div>
                <p class="card-text">
                    Rol: Viajero. Aquí puedes gestionar tus datos personales, hacer nuevas búsquedas de traslados y revisar tus reservas existentes.
                </p>
                <hr>
                
                <h6>Tus Datos de Contacto y Dirección</h6>
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item">
                        **Email:** {{ Auth::guard('web')->user()->email_viajero }}
                    </li>
                    <li class="list-group-item">
                        **Nombre Completo:** {{ Auth::guard('web')->user()->nombre }} {{ Auth::guard('web')->user()->apellido1 }} {{ Auth::guard('web')->user()->apellido2 }}
                    </li>
                    <li class="list-group-item">
                        **Dirección:** {{ Auth::guard('web')->user()->direccion }}, {{ Auth::guard('web')->user()->ciudad }} ({{ Auth::guard('web')->user()->codigoPostal }}), {{ Auth::guard('web')->user()->pais }}
                    </li>
                </ul>
                
                <div class="mt-2">
                    {{-- ENLACE MODIFICADO: Apunta al selector de tipo de reserva --}}
                    <a href="{{ route('transfer.select-type') }}" class="btn btn-success">Reservar Nuevo Traslado</a>
                    <a href="#" class="btn btn-secondary">Ver Mis Reservas</a>
                    <a href="#" class="btn btn-outline-secondary">Editar Perfil</a>
                    <a href="{{ route('calendar.index') }}" class="btn btn-primary">Calendario</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection