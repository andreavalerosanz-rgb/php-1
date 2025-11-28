@extends('layouts.app')

@section('content')

<style>

.calendar-wrapper {
    max-width: 1300px;
    margin: auto;
    padding: 30px;
}

.calendar-card {
    background: white;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}

.fc-toolbar-title {
    font-size: 22px !important;
    font-weight: 600;
}

.fc .fc-button {
    padding: 7px 15px !important;
    border-radius: 10px !important;
    margin: 0 6px;
    border: none !important;
}

.fc-button-primary {
    background-color: #16a34a !important;
    color: white !important;
}

.fc .fc-button:hover {
    opacity: 0.9;
}

.fc-day-today {
    background-color: #dcfce7 !important;
}

.fc-daygrid-event,
.fc-timegrid-event {
    white-space: normal !important;
    height: auto !important;
    padding: 6px 8px !important;
    line-height: 1.3 !important;
    border-radius: 6px !important;
    overflow: visible !important;
}

.fc-daygrid-event-harness,
.fc-timegrid-event-harness,
.fc-event,
.fc-event-main,
.fc-event-main-frame {
    height: auto !important;
    overflow: visible !important;
    border: none !important;
}

.fc-event-title,
.fc-event-time {
    white-space: normal !important;
}
</style>


<div class="calendar-wrapper">
    <div class="calendar-card">
        <h2 class="fw-bold mb-3">Calendario de Reservas</h2>

        <div id="calendar"></div>
        <div id="dayDetails" 
             class="mt-4 p-3 border rounded shadow-sm bg-white" 
             style="display:none;">
            <h4 id="dayTitle" class="fw-semibold mb-3"></h4>
            <div id="eventsList"></div>
        </div>
    </div>
</div>


<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const calendarEl = document.getElementById('calendar');
    const panel = document.getElementById('dayDetails');
    const panelTitle = document.getElementById('dayTitle');
    const panelEvents = document.getElementById('eventsList');

    const calendar = new FullCalendar.Calendar(calendarEl, {

        locale: 'es',
        firstDay: 1,
        initialView: 'dayGridMonth',
        height: "auto",
        eventDisplay: "block",
        dayMaxEventRows: false,
        dayMaxEvents: false,

        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día',
        },

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek,dayGridDay'
        },

        events: function(info, successCallback) {
            fetch("{{ route('calendar.events') }}?from=" + info.startStr + "&to=" + info.endStr)
                .then(response => response.json())
                .then(data => successCallback(
                    data.map(ev => ({
                        id: ev.id,
                        title: ev.title,
                        start: ev.start,
                        allDay: true,

                        display: "block",

                        backgroundColor:
                            ev.tipo === 1 ? '#22c55e' :
                            ev.tipo === 2 ? '#3b82f6' :
                                            '#f97316',

                        textColor: "white",
                        borderColor: "transparent"
                    }))
                ));
        },

        dateClick: function(info) {
            const fecha = new Date(info.dateStr);
            panel.style.display = "block";

            panelTitle.textContent = fecha.toLocaleDateString("es-ES", { 
                weekday: "long",
                day: "numeric",
                month: "long",
                year: "numeric"
            });

            const events = calendar.getEvents().filter(e => e.startStr.startsWith(info.dateStr));

            if (events.length === 0) {
                panelEvents.innerHTML = "<p class='text-muted'>No hay traslados programados.</p>";
                return;
            }

            panelEvents.innerHTML = events.map(e => `
                <div onclick="window.location='/calendario/reserva/${e.id}'"
                     style="cursor:pointer;background:${e.backgroundColor}"
                     class="p-2 rounded mb-2 text-white">
                    <strong>${e.title}</strong><br>
                    ${new Date(e.start).toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' })}
                </div>
            `).join("");
        },

        eventClick: function(info) {
            window.location.href = '/calendario/reserva/' + info.event.id;
        }
    });

    calendar.render();
});
</script>

@endsection

