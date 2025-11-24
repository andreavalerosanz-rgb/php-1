@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h4>Reservar Nuevo Traslado</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('transfer.search.post') }}">
                    @csrf
                    
                    <h5 class="mb-3 text-primary">Detalles de la Búsqueda</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="origen" class="form-label">Origen (Aeropuerto/Puerto)</label>
                            {{-- Podría ser un desplegable con opciones predefinidas --}}
                            <input type="text" class="form-control @error('origen') is-invalid @enderror" id="origen" name="origen" value="{{ old('origen', 'Aeropuerto de Mallorca') }}" required>
                            @error('origen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="destino" class="form-label">Destino (Hotel/Zona)</label>
                            {{-- Podría ser un desplegable que mapee a transfer_zonas o transfer_hoteles --}}
                            <input type="text" class="form-control @error('destino') is-invalid @enderror" id="destino" name="destino" value="{{ old('destino', 'Zona 1') }}" required>
                            @error('destino')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha" class="form-label">Fecha de Recogida</label>
                            <input type="date" class="form-control @error('fecha') is-invalid @enderror" id="fecha" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
                            @error('fecha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="pax" class="form-label">Número de Pasajeros (Pax)</label>
                            <input type="number" class="form-control @error('pax') is-invalid @enderror" id="pax" name="pax" value="{{ old('pax', 1) }}" min="1" required>
                            @error('pax')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Buscar Traslados</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('home') }}">Volver al Inicio</a>
            </div>
        </div>
    </div>
</div>
@endsection