@extends('layouts.app')

@section('content')

<style>
/* =========================
   CONTENEDOR
   ========================= */
.dashboard-container {
    background: #ffffff;
    min-height: 100vh;
    padding: 2rem 1.5rem;
}

.calendar-wrapper {
    max-width: 1300px;
    margin: auto;
}

/* =========================
   TARJETA
   ========================= */
.calendar-card {
    background: #ffffff;
    border-radius: 1.25rem;
    padding: 1.75rem;
    box-shadow: 0 10px 30px rgba(15,23,42,.06);
}

/* =========================
   TOOLBAR
   ========================= */
.fc-toolbar-title {
    font-size: 1.4rem !important;
    font-weight: 700;
    color: #0f172a;
}

.fc .fc-button {
    padding: 7px 15px !important;
    border-radius: 10px !important;
    margin: 0 6px;
    border: none !important;
}

.fc-button-primary {
    background-color: #0f766e !important;
    color: #ffffff !important;
}

.fc .fc-button:hover {
    opacity: 0.9;
}

/* =========================
   CABECERA DÍAS
   ========================= */
.fc-theme-standard th {
    border: none !important;
}

.fc-col-header-cell {
    background: #0f766e;
    border-radius: 10px;
}

.fc-col-header-cell a {
    color: #ffffff !important;
    font-weight: 600;
    text-decoration: none !important;
    padding: 6px 0;
    display: block;
}

/* =========================
   CUADRÍCULA MENSUAL
   ========================= */
.fc-scrollgrid {
    border: none !important;
}

.fc-theme-standard td {
    border: none !important;
}

.fc-daygrid-day {
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    transition: background .15s ease, box-shadow .15s ease;
}

.fc-daygrid-day:hover {
    background: #f1f5f9;
    box-shadow: inset 0 0 0 1px #0f766e22;
}

.fc-daygrid-day-frame {
    padding: 8px;
    min-height: 130px;
}

/* =========================
   NÚMERO DE DÍA
   ========================= */
.fc-daygrid-day-number {
    font-weight: 600;
    color: #334155;
    text-decoration: none !important;
}

/* =========================
   HOY
   ========================= */
.fc-day-today {
    background-color: #ecfdf5 !important;
    border-color: #0f766e !important;
}

/* =========================
   EVENTOS
   ========================= */
.fc-daygrid-event {
    white-space: normal !important;
    padding: 6px 8px !important;
    border-radius: 6px !important;
    font-size: 0.85rem;
    border: none !important;
}

/* =========================
   + VER MÁS (solo mensual)
   ========================= */
.fc-daygrid-more-link {
    color: #0f766e !important;
    font-weight: 600;
    text-decoration: none !important;
}

/* Ocultar "ver más" fuera de mensual */
.fc-timegrid .fc-daygrid-more-link,
.fc-daygrid-day.fc-daygrid-day-top .fc-daygrid-more-link {
    display: none !important;
}
</style>

<div class="dashboard-container">
    <div class="calendar-wrapper">
        <div class="calendar-card">

            <h2 class="fw-bold mb-3">Calendario de Reservas</h2>

            <div id="calendar"></div>

        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const calendar = new FullCalendar.Calendar(
        document.getElementById('calendar'),
        {
            locale: 'es',
            firstDay: 1,
            initialView: 'dayGridMonth',
            height: 'auto',
            views: {
                dayGridMonth: {
                    dayMaxEvents: 2,
                    moreLinkText: 'ver más'
                },
                dayGridWeek: {
                    dayMaxEvents: false
                },
                dayGridDay: {
                    dayMaxEvents: false
                }
            },

            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día'
            },

            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek,dayGridDay'
            },

            events: function(info, successCallback) {
                fetch(`{{ route('calendar.events') }}?from=${info.startStr}&to=${info.endStr}`)
                    .then(r => r.json())
                    .then(data => successCallback(
                        data.map(ev => ({
                            id: ev.id,
                            title: ev.title,
                            start: ev.start,
                            allDay: true,
                            backgroundColor:
                                ev.tipo === 1 ? '#22c55e' :
                                ev.tipo === 2 ? '#3b82f6' :
                                                '#f97316',
                            textColor: '#ffffff'
                        }))
                    ));
            },

            moreLinkClick: function(info) {
                calendar.changeView('dayGridDay', info.date);
            },

            eventClick: function(info) {
                window.location.href = `/calendario/reserva/${info.event.id}`;
            }
        }
    );

    calendar.render();
});
</script>

@endsection
