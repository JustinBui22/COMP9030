
function showcnotification(type, message) {
    const container = document.getElementById('cnotification-container');
    const cnotification = document.createElement('div');
    cnotification.classList.add('cnotification', type);
    cnotification.textContent = message;
    container.appendChild(cnotification);
    setTimeout(() => {
        cnotification.remove();
    }, 2000);
}
document.addEventListener('DOMContentLoaded', function () {
    
    const userData = JSON.parse(localStorage.getItem('userData'));   
// Prefill form with current user data
 document.getElementById('loggedinusername').value = userData.username;

    loadSleepTrend();
});

let sleepTrendChart;

function loadSleepTrend() {
    var user_id= document.getElementById('loggedinusername').value;
    $.ajax({
        url: 'backend.php', // Your PHP function to fetch sleep data
        type: 'POST',
        dataType: 'json',
        data: { action: 'getSleepData',user_id:user_id},

        success: function (data) {
            console.log("Response from DB:", data);
            if (data.success) {
                drawSleepTrendChart(data.sleepData);
                updateAverageDuration(data.averageHours, data.averageMinutes);
            } else {
                console.log('Failed to load sleep data');
            }
        },
        error: function (xhr, status, error) {
            console.log('Error: ' + error);
        }
    });
}

// Function to draw the bar chart

function drawSleepTrendChart(sleepData) {
    var canvas = document.getElementById('sleepTrendChart');
    var ctx = canvas.getContext('2d');

    // Clear the canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Format dates as yy/mm/dd
    var labels = sleepData.map(function (item) {
        var dateParts = item.date.split('-');
        return dateParts[0].slice(2) + '/' + dateParts[1] + '/' + dateParts[2];
    });

    var sleepHours = sleepData.map(function (item) {
        var hours = parseInt(item.hours, 10) || 0;
        var minutes = parseInt(item.minutes, 10) || 0;
        return hours + (minutes / 60);
    });

    // Chart dimensions
    var chartWidth = canvas.width - 100;
    var chartHeight = canvas.height - 100;
    var barWidth = chartWidth / sleepHours.length;
    var maxSleep = Math.max(...sleepHours);

    // Drawing axis
    ctx.beginPath();
    ctx.moveTo(50, 50);
    ctx.lineTo(50, chartHeight + 50);
    ctx.lineTo(chartWidth + 50, chartHeight + 50);
    ctx.stroke();

    // Draw the bars
    for (var i = 0; i < sleepHours.length; i++) {
        var barHeight = (sleepHours[i] / maxSleep) * chartHeight;

        // Bar color
        ctx.fillStyle = 'rgba(75, 192, 192, 0.6)';
        ctx.fillRect(50 + (i * barWidth), (chartHeight + 50) - barHeight, barWidth - 10, barHeight);

        // Sleep hours label on top of each bar
        ctx.fillStyle = '#000';
        ctx.font = '12px Arial';
        ctx.fillText(sleepHours[i].toFixed(2), 50 + (i * barWidth) + (barWidth / 2 - 10), (chartHeight + 50) - barHeight - 5);
    }

    // Y-axis labels (without 'h')
    for (var j = 0; j <= maxSleep; j += 2) {
        var yPos = chartHeight + 50 - (j / maxSleep) * chartHeight;
        ctx.fillText(j, 20, yPos);  // Just print the number without 'h'
        ctx.beginPath();
        ctx.moveTo(45, yPos);
        ctx.lineTo(50, yPos);
        ctx.stroke();
    }

    // Axis Labels
    // Y-axis label (Hours)
    ctx.save();
    ctx.translate(10, canvas.height / 2);
    ctx.rotate(-Math.PI / 2);
    ctx.font = '16px Arial';
    ctx.fillText('Hours', 0, 0);
    ctx.restore();

    // X-axis labels (Date - horizontal format yyyy/mm/dd)
    ctx.font = '12px Arial';
    for (var i = 0; i < labels.length; i++) {
        ctx.fillText(labels[i], 50 + (i * barWidth) + (barWidth / 2 - 10), chartHeight + 70);
    }

    // X-axis label (Date)
    ctx.font = '16px Arial';
    ctx.fillText('Date', canvas.width / 2 - 20, canvas.height - 20);
}
function updateAverageDuration(hours, minutes) {
    // Calculate the total hours as a decimal
    var totalHours = hours + (minutes / 60);

    // Update the average duration text
    $('#average-duration').text('Average sleep hours this week: ' + hours + ' hrs ' + minutes + ' mins');

    // Determine sleep quality and select the corresponding button
    if (totalHours < 5) {
        selectQuality('poor');
    } else if (totalHours >= 5 && totalHours < 6) {
        selectQuality('fair');
    } else {
        selectQuality('good');
    }
}


// Handle form submission
$('#manualEntryForm').on('submit', function (event) {
    event.preventDefault();
    const saveButton = $('#saveButton');
    saveButton.prop('disabled', true).text('Saving...');
    var user_id= document.getElementById('loggedinusername').value;

    // Gather form data
    const formData = {
        action: 'save_sleep_entry',
        date: $('#date').val(),
        hours: $('#hours').val(),
        user_id: $('#user_id').val(),
        minutes: $('#minutes').val(),
        user_id:user_id,
    };

    // Send the AJAX request
    $.ajax({
        url: 'backend.php',
        method: 'POST',
        data: formData,
        success: function (response) {
            if (response.success) {
                showcnotification('success', response.message);
                closeManualEntryModal();
                loadSleepTrend();
            } else {
                showcnotification('error', response.message);;
            }
        },
        error: function () {
            showcnotification('error', 'Error saving data!');

        },
        complete: function () {
            saveButton.prop('disabled', false).text('Add');
            closeManualEntryModal();
        }
    });
});


////Diary Modal
function openDiaryModalNew() {

    loadDiaryEntries();
    $('#diaryModal').show();
}

function loadDiaryEntries() {
    var user_id= document.getElementById('loggedinusername').value;
    $.ajax({
        url: 'backend.php',
        type: 'POST',
        dataType: 'json',
        data: { action: 'getSleepData',user_id:user_id },
        success: function (data) {
            if (data.success) {
                const entries = data.sleepData;
                const diaryEntries = $('#diaryEntries');
                diaryEntries.empty();

                // Loop through entries and append them to the table
                entries.forEach(entry => {
                    diaryEntries.append(`
                        <tr>
                            <td>${entry.date}</td>
                            <td>${entry.hours}</td>
                            <td>${entry.minutes}</td>
                            <td>                               
                                <a style="text-decoration:underline; color: red;" onclick="deleteSleepEntry('${entry.date}')">Delete</a>
                            </td>
                        </tr>
                    `);
                });
            } else {
                console.log('Failed to load sleep data');
            }
        },
        error: function (xhr, status, error) {
            console.log('Error: ' + error);
        }
    });
}
////CRUD
function updateSleepEntry(date) {
    var user_id= document.getElementById('loggedinusername').value;
    const formData = {
        action: 'update_sleep_entry', // Define this action in PHP
        date: $('#date').val(),
        hours: $('#hours').val(),
        minutes: $('#minutes').val(),
        user_id:user_id,
    };

    $.ajax({
        url: 'backend.php',
        method: 'POST',
        data: formData,
        success: function (response) {
            if (response.success) {
                showcnotification('success', response.message);;
                closeDiaryModal();
                loadDiaryEntries(); // Reload the entries
            } else {
                showcnotification('error', response.message);;
            }
        },
        error: function () {
            showcnotification('error', 'Error updating data!');

        }
    });
}

function deleteSleepEntry(date) {
    var user_id= document.getElementById('loggedinusername').value;
    const formData = {
        action: 'delete_sleep_entry', // Define this action in PHP
        date: date,
        user_id:user_id,
    };

    $.ajax({
        url: 'backend.php',
        method: 'POST',
        data: formData,
        success: function (response) {
            if (response.success) {
                showcnotification('success', response.message);;
                loadDiaryEntries();
                loadSleepTrend();
            } else {
                showcnotification('error', response.message);;
            }
        },
        error: function () {
            showcnotification('error', 'Error deleting entry!');

        }
    });
}
function openManualEntryModal() {
    clearManualEntryFields();
    document.getElementById('manualEntryModal').style.display = 'block';
}

function closeManualEntryModal() {
    setTimeout(function () {
        document.getElementById('manualEntryModal').style.display = 'none';
        clearManualEntryFields();
    }, 1000);
}

function clearManualEntryFields() {
    document.getElementById('date').value = '';
    document.getElementById('hours').value = '';
    document.getElementById('minutes').value = '';
}

function closeDiaryModal() {
    document.getElementById('diaryModal').style.display = 'none';
}

////Timer Counter
let timerInterval;
let totalSeconds = 0;

function updateTimer() {
    totalSeconds++;
    const hours = Math.floor(totalSeconds / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = totalSeconds % 60;
    // Update the timer display
    const timerDisplay = document.getElementById('timer');
    timerDisplay.textContent = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
}

function play() {
    if (!timerInterval) {
        timerInterval = setInterval(updateTimer, 1000);
    }
}

function pause() {
    clearInterval(timerInterval);
    timerInterval = null;
}

function stopAndSave() {
    pause(); // Pause the timer
    const hours = Math.floor(totalSeconds / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const date = new Date().toISOString().split('T')[0];
    saveTimerEntry(date, hours, minutes);
}

function rewind() {
    totalSeconds -= 600;
    if (totalSeconds < 0) {
        totalSeconds = 0;
    }
    updateTimerDisplay();
}

function forward() {
    totalSeconds += 600;
    updateTimerDisplay();
}

function updateTimerDisplay() {
    const hours = Math.floor(totalSeconds / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = totalSeconds % 60;

    // Update the timer display
    const timerDisplay = document.getElementById('timer');
    timerDisplay.textContent = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
}

function saveTimerEntry(date, hours, minutes) {

    var user_id= document.getElementById('loggedinusername').value;
    const saveButton = $('#saveButton');
    saveButton.prop('disabled', true).text('Saving...');

    // Gather data for AJAX request
    const formData = {
        action: 'save_sleep_entry',
        date: date,
        hours: hours,
        minutes: minutes,
        user_id:user_id,
    };

    // Send the AJAX request
    $.ajax({
        url: 'backend.php',
        method: 'POST',
        data: formData,
        success: function (response) {
            if (response.success) {
                showcnotification('success', response.message);
                resetTimer();
                loadSleepTrend();
            } else {
                showcnotification('error', response.message);
            }
        },
        error: function () {
            showcnotification('error', 'Error saving data!');

        },
        complete: function () {
            saveButton.prop('disabled', false).text('Add');
        }
    });
}

function resetTimer() {
    clearInterval(timerInterval);
    timerInterval = null;
    totalSeconds = 0;
    document.getElementById('timer').textContent = '00:00:00';
}

function selectQuality(selectedId) {
    document.querySelectorAll('.quality-button').forEach(button => {
        button.classList.remove('selected');
    });

    document.getElementById(selectedId).classList.add('selected');
}

