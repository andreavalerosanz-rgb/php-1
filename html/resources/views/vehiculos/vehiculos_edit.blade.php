@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h2 class="mb-4">Editar Vehículo</h2>

    <form action="{{ route('admin.vehiculos.update', $vehiculo->id_vehiculo) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <input type="text" name="descripcion" class="form-control"
                   value="{{ $vehiculo->descripcion }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email del Conductor</label>
            <input type="email" name="email_conductor" class="form-control"
                   value="{{ $vehiculo->email_conductor }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Matrícula</label>
            <input type="text" name="password" class="form-control"
                   value="{{ $vehiculo->password }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('admin.vehiculos.index') }}" class="btn btn-secondary">Volver</a>

    </form>
</div>
@endsection
