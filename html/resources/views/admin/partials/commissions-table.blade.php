<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card shadow-lg">

            <div class="card-header bg-danger text-white">
                <h4>
                    Gestión de Comisiones — {{ date('F Y', mktime(0,0,0,$month,1,$year)) }}
                </h4>
            </div>

            <div class="card-body">

                <p class="text-muted">Consulta agregada o detallada de las comisiones por hotel.</p>

                {{-- FORMULARIO DE FILTRO --}}
                <form method="GET" action="{{ route('admin.hoteles.index') }}" class="row g-3 mb-4">

                    {{-- Mantener pestaña activa --}}
                    <input type="hidden" name="tab" value="comisiones">

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Mes</label>
                        <select name="month" class="form-select">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
    {{ ucfirst(\Carbon\Carbon::createFromDate($year, $i, 1)->locale('es')->isoFormat('MMMM')) }}
</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Año</label>
                        <select name="year" class="form-select">
                            @for ($i = now()->year - 2; $i <= now()->year + 1; $i++)
                                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Filtrar por Hotel</label>
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

                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary w-100">Aplicar</button>
                    </div>

                </form>

                {{-- SI NO HAY FILTRO → TABLA GENERAL --}}
                @if (!$hotelFilter)

                    <table class="table table-bordered table-striped text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID Hotel</th>
                                <th>Nombre</th>
                                <th>Total Reservas</th>
                                <th>Ingresos</th>
                                <th>Comisión a Pagar</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($commissionReport as $report)
                                <tr>
                                    <td>{{ $report['hotel_id'] }}</td>
                                    <td>{{ $report['nombre_hotel'] }}</td>
                                    <td>{{ $report['total_reservas'] }}</td>
                                    <td>{{ number_format($report['total_ingresos'], 2) }} €</td>
                                    <td class="fw-bold text-success">
                                        {{ number_format($report['total_comision'], 2) }} €
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end fw-bold">TOTAL GENERAL:</td>
                                <td class="fw-bold text-success">
                                    {{ number_format($commissionReport->sum('total_comision'), 2) }} €
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                @else

                {{-- SI HAY FILTRO → DETALLE DEL HOTEL --}}
                    <h5 class="mt-4 mb-3 fw-bold text-danger">
                        Detalle de Reservas — 
                        {{ $hoteles->where('id_hotel', $hotelFilter)->first()->nombre }}
                    </h5>

                    <table class="table table-striped table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Localizador</th>
                                <th>Vehículo</th>
                                <th>Tipo Traslado</th>
                                <th>Precio Total</th>
                                <th>Comisión</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($reservasDetalladas as $reserva)
                                <tr>
                                    <td>{{ $reserva->localizador }}</td>
                                    <td>{{ $reserva->vehiculo->Descripción ?? '-' }}</td>
                                    <td>{{ $reserva->tipo_traslado_nombre }}</td>
                                    <td>{{ number_format($reserva->precio_total, 2) }} €</td>
                                    <td class="fw-bold text-success">
                                        {{ number_format($reserva->comision_ganada, 2) }} €
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot class="table-light">
        <tr>
            <td colspan="4" class="text-end fw-bold">COMISIÓN TOTAL DEL HOTEL:</td>
            <td class="fw-bold text-success">
                {{ number_format($reservasDetalladas->sum('comision_ganada'), 2) }} €
            </td>
        </tr>
    </tfoot>
                    </table>

                @endif

            </div>
        </div>
    </div>
</div>