<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Page</title>
    <link rel="stylesheet" href="styles/patient-list.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css">
    <style>
        /* Adjust calendar size */
        #calendar {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <!-- Return Button -->
    <button onclick="window.history.back()">Return</button>

    <h2>Group Event Schedule</h2>

    <!-- Calendar -->
    <div id="calendar"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    {
                        title: 'Therapy Session',
                        start: '2024-08-12',
                    },
                    {
                        title: 'Group Meeting',
                        start: '2024-08-15',
                    }
                ]
            });

            calendar.render();
        });
    </script>
</body>
</html>