@extends('layouts.app')

@section('content')
<style>

   .dashboard-wrapper {
    min-height: 90vh;
    padding: 2rem 0;
}

.glass-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 1.5rem;
    box-shadow: 0 10px 40px rgba(15, 23, 42, 0.08);
    overflow: hidden;
}

/* HEADER */
.card-header-custom {
    background: linear-gradient(90deg, #0f9f9a, #0f766e);
    padding: 1.25rem 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: white;
}

/* TABLE WRAPPER */
.table-responsive {
    overflow-x: auto;
}

/* TABLE */
.custom-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

/* CABECERAS  */
.custom-table thead th {
    background-color: #f1f5f9;
    color: #475569;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.72rem;
    letter-spacing: 0.05em;
    padding: 1rem 0.75rem; 
    border-bottom: 2px solid #e2e8f0;
    text-align: center;      
    vertical-align: middle;
}

/*  CELDAS */
.custom-table tbody td {
    padding: 0.6rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.9rem;
    color: #334155;
}

.custom-table td:nth-child(1),
.custom-table td:nth-last-child(1),
.custom-table td:nth-child(7) {
    white-space: nowrap;
}

/* TEXTOS */
.fw-medium { font-weight: 600; }
.text-teal { color: #0f766e; }
.text-gold { color: #d97706; }

/* LOCALIZADOR */
.loc-code {
    font-family: monospace;
    background: #f1f5f9;
    padding: 0.15rem 0.45rem;
    border-radius: 4px;
    font-weight: 600;
}

/* ESTADOS */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.25rem 0.6rem;
    border-radius: 999px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-success { background: #dcfce7; color: #166534; }
.status-danger  { background: #fee2e2; color: #991b1b; }
.status-gray    { background: #f1f5f9; color: #475569; }

/* BOTONES */
.btn-icon {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    transition: 0.15s;
}

.btn-edit {
    background-color: #facc6b;
    color: #78350f;
}

.btn-delete {
    background-color: #fee2e2;
    color: #dc2626;
}

.disabled-action {
    opacity: 0.35;
    cursor: not-allowed;
}

/* ICONOS */
.td-icon {
    width: 15px;
    height: 15px;
    color: #94a3b8;
    margin-right: 0.3rem;
}

/* PRECIO */
.price-tag {
    font-weight: 700;
    color: #0f766e;
}

/* PAGINACIÓN  */

.pagination {
    gap: 0.35rem;
}

.page-item .page-link {
    border: none;
    border-radius: 8px;
    padding: 0.35rem 0.65rem;
    font-size: 0.85rem;
    font-weight: 600;
    color: #0f766e;
    background-color: #f1f5f9;
    transition: all 0.15s ease;
}

.page-item .page-link:hover {
    background-color: #ccfbf1;
    color: #0f766e;
}

.page-item.active .page-link {
    background-color: #0f766e;
    color: white;
}

.page-item.disabled .page-link {
    background-color: transparent;
    color: #94a3b8;
    cursor: not-allowed;
}

</style>

<div class="dashboard-wrapper">
    <div class="container-fluid px-lg-5"> <div class="glass-card">
            {{-- Header --}}
            <div class="card-header-custom">
                <div class="d-flex align-items-center gap-2">
                    {{-- Icon: Clipboard Document List --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:24px;height:24px;">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                    </svg>
                    <h5 class="mb-0 fw-bold">Gestión de Reservas</h5>
                </div>
                <div class="small opacity-75">
                    {{ $reservas->total() }} Registros
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>Ref / Localizador</th>
                            <th>Hotel / Cliente</th>
                            <th>Fecha Traslado</th>
                            <th>Ruta</th>
                            <th>Vehículo</th>
                            <th class="text-center">Pasajeros</th>
                            <th class="text-end">Precio</th>
                            @if($rol !== 'user')
    <th class="text-end">Comisión</th>
@endif
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservas as $reserva)
                        @php
    $puede_modificar = $reserva->puedeSerModificadaPor($rol);

                            $origen  = '';
                            $destino = '';
                            $hotelNombre = $reserva->hotel->nombre ?? 'Sin hotel';

                            switch ($reserva->id_tipo_reserva) {
                                case 1:
                                    $origen  = $reserva->origen_vuelo_entrada ?: 'Aeropuerto';
                                    $destino = $hotelNombre;
                                    break;
                                case 2:
                                    $origen  = $hotelNombre;
                                    $destino = $reserva->origen_vuelo_salida ?: 'Aeropuerto';
                                    break;
                                case 3:
                                    $origen  = ($reserva->origen_vuelo_entrada ?: 'Aeropuerto') . ' / ' . $hotelNombre;
                                    $destino = $hotelNombre . ' / ' . ($reserva->origen_vuelo_salida ?: 'Aeropuerto');
                                    break;
                                default:
                                    $origen  = 'Desconocido';
                                    $destino = 'Desconocido';
                                    break;
                            }
                            
                            // Determinar Clase Estado
                            $badgeClass = 'status-success';
                            $iconStatus = ''; 
                            if($reserva->estado == 'anulada') {
                                $badgeClass = 'status-danger';
                            } elseif($reserva->estado == 'finalizada') {
                                $badgeClass = 'status-gray';
                            }
                        @endphp
                        <tr
    onclick="if (!event.target.closest('.acciones')) {
        window.location='{{ route('calendar.show', $reserva->id_reserva) }}?from=mis_reservas'
    }"
    style="cursor:pointer"
>
                            {{-- REF & LOC --}}
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="small text-muted mb-1">ID: #{{ $reserva->id_reserva }}</span>
                                    <span class="loc-code">{{ $reserva->localizador }}</span>
                                </div>
                            </td>

                            {{-- HOTEL & CLIENT --}}
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-medium text-teal">{{ $reserva->hotel->nombre ?? 'Sin hotel' }}</span>
                                    <span class="small text-muted" title="{{ $reserva->email_cliente }}">
                                        {{ Str::limit($reserva->email_cliente, 20) }}
                                    </span>
                                </div>
                            </td>

                            {{-- FECHA --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    {{-- Icon: Calendar --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="td-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    @if($reserva->id_tipo_reserva == 1)
                                        {{ 
    \Carbon\Carbon::createFromFormat(
        'Y-m-d H:i:s',
        $reserva->fecha_entrada.' '.$reserva->hora_entrada
    )->format('d/m/Y H:i')
}}

                                    @elseif($reserva->id_tipo_reserva == 2)
                                        {{ 
    \Carbon\Carbon::createFromFormat(
        'Y-m-d H:i:s',
        $reserva->fecha_vuelo_salida.' '.$reserva->hora_vuelo_salida
    )->format('d/m/Y H:i')
}}

                                    @elseif($reserva->id_tipo_reserva == 3)
                                        <div class="d-flex flex-column small">
    <span>
        IDA:
        {{
            \Carbon\Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $reserva->fecha_entrada.' '.$reserva->hora_entrada
            )->format('d/m H:i')
        }}
    </span>
    <span>
        VTA:
        {{
            \Carbon\Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $reserva->fecha_vuelo_salida.' '.$reserva->hora_vuelo_salida
            )->format('d/m H:i')
        }}
    </span>
</div>

                                    @endif
                                </div>
                            </td>

                            {{-- RUTA & ZONA --}}
                            <td>
                                <div class="small">
                                    <div class="mb-1">
                                        <span class="text-muted">De:</span> <strong>{{ Str::limit($origen, 15) }}</strong>
                                    </div>
                                    <div>
                                        <span class="text-muted">A:</span> <strong>{{ Str::limit($destino, 15) }}</strong>
                                    </div>
                                    <div class="mt-1 badge bg-light text-secondary border">
                                        {{ $reserva->zona->descripcion ?? 'N/A' }}
                                    </div>
                                </div>
                            </td>

                            {{-- VEHICULO --}}
                            <td>
                                <div class="d-flex align-items-center">
                                     {{-- Icon: Truck/Car --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="td-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $reserva->vehiculo->descripcion ?? '-' }}
                                </div>
                            </td>

                            {{-- PAX --}}
                            <td class="text-center">
                                <span class="badge bg-white text-dark border">
                                    {{ $reserva->num_viajeros }} <span class="text-muted">👤</span>
                                </span>
                            </td>

                            {{-- PRECIO --}}
                            <td class="text-end">
                                <span class="price-tag">{{ number_format($reserva->precio_total, 2) }} €</span>
                            </td>

                            {{-- COMISIÓN --}}
                            @if($rol !== 'user')
    <td class="text-end">
        <span class="text-gold fw-bold">
            +{{ number_format($reserva->comision_ganada, 2) }} €
        </span>
    </td>
@endif

                            {{-- ESTADO --}}
                            <td class="text-center">
                                <span class="status-badge {{ $badgeClass }}">
                                    @if($reserva->estado == 'anulada')
                                        {{-- Icon: X Circle --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    @elseif($reserva->estado == 'finalizada')
                                        {{-- Icon: Check Circle --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    @else
                                        {{-- Icon: Clock/Active --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    @endif
                                    {{ $reserva->estado }}
                                </span>
                            </td>

                            {{-- ACCIONES --}}
<td class="text-center acciones">
    @if($puede_modificar && $reserva->estado === 'confirmada')
        <div class="d-flex gap-1 justify-content-center">

            {{-- Edit Button --}}
            <a
                href="{{ route('reserva.edit', $reserva->id_reserva) }}"
                class="btn-icon btn-edit"
                title="Modificar Reserva"
                onclick="event.stopPropagation();"
            >
                {{-- Icon: Pencil --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
            </a>

            {{-- Delete / Anular --}}
            <form
                action="{{ route('reserva.destroy', $reserva->id_reserva) }}"
                method="POST"
                style="display:inline-block;"
                onsubmit="event.stopPropagation(); return confirm('¿Estás seguro de querer anular esta reserva?');"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="btn-icon btn-delete"
                    title="Anular Reserva"
                    onclick="event.stopPropagation();"
                >
                    {{-- Icon: Trash --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                              a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6
                              m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>

        </div>
    @else
        <span class="small text-muted fst-italic disabled-action">Bloqueado</span>
    @endif
</td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> {{-- End Table Responsive --}}
            @if ($reservas->hasPages())
    <div class="p-4 border-top d-flex justify-content-center">
        {{ $reservas->links() }}
    </div>
@endif

            @if($reservas->total() === 0)
                <div class="text-center py-5">
                    <div class="mb-3 text-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 48px; height: 48px; opacity: 0.5;">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>
                    <h6 class="text-secondary">No hay reservas registradas</h6>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection