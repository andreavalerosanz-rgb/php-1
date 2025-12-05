@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h2 class="mb-4">Listado de Vehículos</h2>

    <a href="{{ route('admin.vehiculos.create') }}" class="btn btn-primary mb-3">
        Añadir Vehículo
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Descripción</th>
                <th>Email Conductor</th>
                <th>Matrícula</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach($vehiculos as $v)
                <tr>
                    <td>{{ $v->id_vehiculo }}</td>
                    <td>{{ $v->descripcion }}</td>
                    <td>{{ $v->email_conductor }}</td>
                    <td>{{ $v->password }}</td>
                    <td>
                        <a href="{{ route('admin.vehiculos.edit', $v->id_vehiculo) }}" class="btn btn-sm btn-warning">
                            Editar
                        </a>

                        <form action="{{ route('admin.vehiculos.destroy', $v->id_vehiculo) }}"
                              method="POST"
                              style="display:inline-block">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Eliminar este vehículo?')">
                                Borrar
                            </button>
                        </form>

                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
</div>
@endsection
