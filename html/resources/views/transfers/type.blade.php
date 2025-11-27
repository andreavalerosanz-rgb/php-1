@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h4>Paso 1: Selecciona el Tipo de Reserva</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('transfer.select-type.post') }}">
                    @csrf
                    
                    <div class="row">
                        {{-- Aeropuerto -> Hotel --}}
                        <div class="col-md-4 mb-3">
                            <label class="d-block card-type-label">
                                <input type="radio" name="reservation_type" value="airport_to_hotel" required checked>
                                <div class="card text-center border-primary shadow-sm h-100 p-2">
                                    <div class="card-body">
                                        <i class="fas fa-plane-arrival fa-3x text-primary mb-2"></i>
                                        <p class="fw-bold">Aeropuerto → Hotel</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                        
                        {{-- Hotel -> Aeropuerto --}}
                        <div class="col-md-4 mb-3">
                            <label class="d-block card-type-label">
                                <input type="radio" name="reservation_type" value="hotel_to_airport" required>
                                <div class="card text-center border-secondary shadow-sm h-100 p-2">
                                    <div class="card-body">
                                        <i class="fas fa-plane-departure fa-3x text-secondary mb-2"></i>
                                        <p class="fw-bold">Hotel → Aeropuerto</p>
                                    </div>
                                </div>
                            </label>
                        </div>

                        {{-- Ida y Vuelta --}}
                        <div class="col-md-4 mb-3">
                            <label class="d-block card-type-label">
                                <input type="radio" name="reservation_type" value="round_trip" required>
                                <div class="card text-center border-success shadow-sm h-100 p-2">
                                    <div class="card-body">
                                        <i class="fas fa-exchange-alt fa-3x text-success mb-2"></i>
                                        <p class="fw-bold">Ida y Vuelta</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    @error('reservation_type')
                        <div class="alert alert-danger mt-3">{{ $message }}</div>
                    @enderror

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Continuar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
    .card-type-label input[type="radio"] { display: none; }
    .card-type-label input[type="radio"]:checked + .card {
        border: 3px solid #0d6efd !important;
        box-shadow: 0 0 10px rgba(13, 110, 253, 0.5) !important;
    }
</style>
@endsection