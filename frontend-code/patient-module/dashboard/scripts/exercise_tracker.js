document.getElementById('exerciseDate').addEventListener('change', function () {
    const selectedDate = this.value;
    const patientID = document.getElementById('patientID').value;

    if (selectedDate) {
        // Fetch the daily data for the selected date
        fetchDailyData(patientID, selectedDate);
        // Fetch the weekly totals for the selected date's week
        fetchWeeklyTotals(patientID, selectedDate);
    }
});

// Function to fetch daily data
function fetchDailyData(patientID, date) {
    fetch(`patient_page.php?action=getDailyData&patient_id=${patientID}&date=${date}`)
        .then(response => response.json())
        .then(data => {
            // Populate the fields with the fetched data
            document.getElementById('runningHours').value = data.runningHours || 0;
            document.getElementById('cyclingHours').value = data.cyclingHours || 0;
            document.getElementById('otherHours').value = data.otherHours || 0;
            document.querySelector('textarea[name="affirmation"]').value = data.affirmation || '';
        })
        .catch(error => {
            console.error("Error fetching daily data: ", error);
        });
}

// Function to fetch weekly totals
function fetchWeeklyTotals(patientID, date) {
    fetch(`patient_page.php?action=getWeeklyTotals&patient_id=${patientID}&date=${date}`)
        .then(response => response.json())
        .then(data => {
            // Populate the weekly totals fields
            document.getElementById('totalRunning').value = data.totalRunning || 0;
            document.getElementById('totalCycling').value = data.totalCycling || 0;
            document.getElementById('totalOthers').value = data.totalOthers || 0;
        })
        .catch(error => {
            console.error("Error fetching weekly totals: ", error);
        });
}

// Form submission with AJAX
document.getElementById('exerciseForm').addEventListener('submit', function (event) {
    event.preventDefault();  // Prevent default form submission

    const formData = new FormData(this);

    // Submit the form data via fetch
    fetch('patient_page.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);  // Display success or error message
        // Re-fetch weekly totals after submission
        const selectedDate = document.getElementById('exerciseDate').value;
        const patientID = document.getElementById('patientID').value;
        fetchWeeklyTotals(patientID, selectedDate);
    })
    .catch(error => {
        console.error("Error submitting data: ", error);
    });
});