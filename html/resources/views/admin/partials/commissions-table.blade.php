<div class="glass-panel">
    <div class="panel-header-brand">
        <h5 class="mb-0 fw-bold">
    Gestión de Comisiones —
    @if($month !== 'all' && $year !== 'all')
        {{ ucfirst(\Carbon\Carbon::createFromDate($year, $month, 1)->locale('es')->isoFormat('MMMM YYYY')) }}
    @elseif($year !== 'all')
        Año {{ $year }}
    @else
        Todas
    @endif
</h5>

        <span class="badge bg-white text-teal bg-opacity-90 shadow-sm">
            Reporte mensual
        </span>
    </div>

    <div class="p-4">

        <p class="text-muted mb-4">
            Consulta agregada o detallada de las comisiones por hotel.
        </p>

        {{-- FORMULARIO DE FILTRO --}}
        <form method="GET" action="{{ route('admin.hoteles.index') }}" class="row g-3 mb-4">

            <input type="hidden" name="tab" value="comisiones">

            <div class="col-md-3">
                <label class="form-label fw-semibold text-teal">Mes</label>
                <select name="month" class="form-select">
    <option value="all" {{ $month === 'all' ? 'selected' : '' }}>
        Todos los meses
    </option>

    @for ($i = 1; $i <= 12; $i++)
        <option value="{{ $i }}" {{ (string)$month === (string)$i ? 'selected' : '' }}>
            {{ ucfirst(\Carbon\Carbon::createFromDate(2000, $i, 1)
                ->locale('es')
                ->isoFormat('MMMM')) }}
        </option>
    @endfor
</select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold text-teal">Año</label>
                <select name="year" class="form-select">
    <option value="all" {{ $year === 'all' ? 'selected' : '' }}>
        Todos los años
    </option>

    @for ($i = now()->year - 5; $i <= now()->year + 1; $i++)
        <option value="{{ $i }}" {{ (string)$year === (string)$i ? 'selected' : '' }}>
            {{ $i }}
        </option>
    @endfor
</select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold text-teal">Hotel</label>
                <select name="hotel_id" class="form-select">
                    <option value="">Todos los hoteles</option>
                    @foreach ($hoteles as $h)
                        <option value="{{ $h->id_hotel }}"
                            {{ ($hotelFilter == $h->id_hotel) ? 'selected' : '' }}>
                            {{ $h->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end gap-2">
    <button class="btn btn-teal w-100">
        <i class="fas fa-filter me-1"></i> Aplicar
    </button>
</div>
        </form>

        {{-- TABLA GENERAL --}}
        @if (!$hotelFilter)

            <div class="table-responsive">
                <table class="table custom-table text-center align-middle">
                    <thead>
                        <tr>
                            <th>ID Hotel</th>
                            <th>Nombre</th>
                            <th>Reservas</th>
                            <th>Ingresos</th>
                            <th>Comisión</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($commissionReport as $report)
                            <tr>
                                <td>{{ $report['hotel_id'] }}</td>
                                <td>{{ $report['nombre_hotel'] }}</td>
                                <td>{{ $report['total_reservas'] }}</td>
                                <td>{{ number_format($report['total_ingresos'], 2) }} €</td>
                                <td class="text-success">
                                    {{ number_format($report['total_comision'], 2) }} €
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-light">
                            <td colspan="4" class="text-end fw-bold">
                                TOTAL GENERAL
                            </td>
                            <td class="fw-bold text-success">
                                {{ number_format($commissionReport->sum('total_comision'), 2) }} €
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        @else

        {{-- DETALLE POR HOTEL --}}
            <h6 class="fw-bold text-teal mb-3">
                Detalle de Reservas — 
                {{ $hoteles->where('id_hotel', $hotelFilter)->first()->nombre }}
            </h6>

            <div class="table-responsive">
                <table class="table custom-table align-middle">
                    <thead>
                        <tr>
                            <th>Localizador</th>
                            <th>Vehículo</th>
                            <th>Traslado</th>
                            <th>Precio</th>
                            <th>Comisión</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservasDetalladas as $reserva)
                            <tr>
                                <td>{{ $reserva->localizador }}</td>
                                <td>{{ $reserva->vehiculo->descripcion }}</td>
                                <td>{{ $reserva->tipo_traslado_nombre }}</td>
                                <td>{{ number_format($reserva->precio_total, 2) }} €</td>
                                <td class="text-success">
                                    {{ number_format($reserva->comision_ganada, 2) }} €
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-light">
                            <td colspan="4" class="text-end fw-bold">
                                TOTAL HOTEL
                            </td>
                            <td class="fw-bold text-success">
                                {{ number_format($reservasDetalladas->sum('comision_ganada'), 2) }} €
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        @endif

    </div>
</div>