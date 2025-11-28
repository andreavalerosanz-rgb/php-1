@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="alert alert-info text-center" role="alert">
            <h3>¡AUTENTICACIÓN CORPORATIVA EXITOSA!</h3>
        </div>
        <div class="card shadow-lg">
            <div class="card-header bg-info text-white">
                PANEL DE HOTEL (USUARIO CORPORATIVO)
            </div>
            <div class="card-body">
                {{-- Usamos Auth::guard('corporate') para acceder a los datos del Hotel --}}
                <h5 class="card-title">
                    Bienvenido/a, {{ Auth::guard('corporate')->user()->nombre }}
                </h5>
                <p class="card-text">
                    Email de Contacto: {{ Auth::guard('corporate')->user()->email_hotel }}
                </p>
                <p class="card-text">
                    Rol: Hotel. Desde aquí puedes gestionar las reservas de tus clientes y revisar tu historial de comisiones.
                </p>
                <hr>
                <h6>Información Clave (Producto 3)</h6>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        ID de Zona: {{ Auth::guard('corporate')->user()->id_zona }}
                    </li>
                    <li class="list-group-item">
                        Comisión Aplicada: {{ Auth::guard('corporate')->user()->Comision }}%
                    </li>
                </ul>
                
                <div class="mt-4">
                    <a href="{{ route('mis_reservas') }}" class="btn btn-secondary">Mis Reservas</a>
                    <a href="#" class="btn btn-secondary">Ajustes de Cuenta</a>
                    <a href="{{ route('calendar.index') }}" class="btn btn-success">Calendario</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection