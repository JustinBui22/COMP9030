let timerInterval;
let seconds = 0;

function formatTime(seconds) {
    const hrs = Math.floor(seconds / 3600).toString().padStart(2, '0');
    const mins = Math.floor((seconds % 3600) / 60).toString().padStart(2, '0');
    const secs = (seconds % 60).toString().padStart(2, '0');
    return `${hrs}:${mins}:${secs}`;
}

function updateTimer() {
    document.getElementById('timer').innerText = formatTime(seconds);
}

function play() {
    if (!timerInterval) {
        timerInterval = setInterval(() => {
            seconds++;
            updateTimer();
        }, 1000);
    }
}

function stop() {
    clearInterval(timerInterval);
    timerInterval = null;
    seconds = 0;
    updateTimer();
}

function pause() {
    clearInterval(timerInterval);
    timerInterval = null;
}

function rewind() {
    seconds = Math.max(0, seconds - 10); 
    updateTimer();
}

function forward() {
    seconds += 10; 
    updateTimer();
}

function selectQuality(selectedId) {
    document.querySelectorAll('.quality-button').forEach(button => {
        button.classList.remove('selected');
    });

    document.getElementById(selectedId).classList.add('selected');
}

document.addEventListener('DOMContentLoaded', () => {
    function generateRandomData(numPoints) {
        return Array.from({ length: numPoints }, () => Math.floor(Math.random() * 10) + 1);
    }

    const ctx = document.getElementById('sleepTrendChart').getContext('2d');
    const sleepTrendChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            datasets: [{
                label: 'Sleep Duration (hrs)',
                data: generateRandomData(7),
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 1,
                fill: true
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Hours'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Days of the Week'
                    }
                }
            }
        }
    });
});
