@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4>Comisiones de {{ Auth::guard('corporate')->user()->hotel?->nombre ?? 'Hotel desconocido' }}
                    ({{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }})
                </h4>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('corporate.comissions') }}" class="row g-3 mb-4">
                    <div class="col-auto">
                        <select name="month" class="form-select">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                    {{ date('F', mktime(0,0,0,$i,10)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="year" class="form-select">
                            @for ($i = \Carbon\Carbon::now()->year - 2; $i <= \Carbon\Carbon::now()->year + 1; $i++)
                                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </div>
                </form>

                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Reserva</th>
                            <th>Fecha</th>
                            <th>Precio Total</th>
                            <th>Comisión Hotel</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commissionReport as $report)
                            <tr>
                                <td>{{ $report['localizador'] }}</td>
                                <td>{{ $report['fecha_reserva'] }}</td>
                                <td>{{ number_format($report['precio_total'], 2) }} €</td>
                                <td class="fw-bold text-success">{{ number_format($report['comision_hotel'], 2) }} €</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No se encontraron reservas para este periodo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end fw-bold">TOTAL COMISIÓN:</td>
                            <td class="fw-bold text-success">{{ number_format($totalComision, 2) }} €</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
