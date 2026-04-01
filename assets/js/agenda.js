document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Dia',
            list: 'Lista'
        },
        events: {
            url: 'index.php?c=cita&a=calendar',
            method: 'GET'
        },
        editable: false,
        selectable: true,
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            if (info.event.url) {
                window.location.href = info.event.url;
            }
        },
        select: function(info) {
            // Open new appointment form with selected date
            var dateStr = info.startStr;
            window.location.href = 'index.php?c=cita&a=create&fecha=' + dateStr;
        },
        height: 'auto',
        firstDay: 1, // Monday
        navLinks: true,
        dayMaxEvents: true,
    });

    calendar.render();
});
