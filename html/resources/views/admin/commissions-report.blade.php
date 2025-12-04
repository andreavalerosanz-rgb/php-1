<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-lg">
            <div class="card-header bg-danger text-white">
                {{-- Usamos date() nativo de PHP para el título, que no requiere importación --}}
                <h4>Cálculo de Comisiones a Pagar ({{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }})</h4>
            </div>
            <div class="card-body">
                
                <p class="text-muted">Listado de las reservas realizadas por cada hotel y cálculo de la comisión total a pagar cada mes.</p>

                <div class="mb-4">
                    {{-- Formulario para filtrar por mes/año --}}
                    <form method="GET" action="{{ route('admin.commissions') }}" class="row g-3">
                        <input type="hidden" name="tab" value="comisiones">
                        <div class="col-auto">
                            <label for="month" class="visually-hidden">Mes</label>
                            <select name="month" id="month" class="form-select">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-auto">
                            <label for="year" class="visually-hidden">Año</label>
                            <select name="year" id="year" class="form-select">
                                {{-- SOLUCIÓN: Usar \Carbon\Carbon con la barra invertida inicial --}}
                                @for ($i = \Carbon\Carbon::now()->year - 2; $i <= \Carbon\Carbon::now()->year + 1; $i++)
                                    <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Calcular</button>
                        </div>
                    </form>
                </div>

                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Hotel</th>
                            <th>Nombre Hotel</th>
                            <th>Ingresos Generados</th>
                            <th>Comisión Total a Pagar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commissionReport as $report)
                            <tr>
                                <td>{{ $report['hotel_id'] }}</td>
                                <td>{{ $report['nombre_hotel'] }}</td>
                                <td>{{ number_format($report['total_ingresos'], 2) }} €</td>
                                <td class="fw-bold text-success">{{ number_format($report['total_comision'], 2) }} €</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No se encontraron comisiones para este periodo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end fw-bold">TOTAL GENERAL DE COMISIONES:</td>
                            <td class="fw-bold text-success">{{ number_format($commissionReport->sum('total_comision'), 2) }} €</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>