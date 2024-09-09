const submitBtn = document.getElementById('submitBtn');
const runningInput = document.getElementById('running');
const cyclingInput = document.getElementById('cycling');
const otherExerciseInput = document.getElementById('otherExercise');
const totalRunningInput = document.getElementById('totalRunning');
const totalCyclingInput = document.getElementById('totalCycling');
const totalOtherInput = document.getElementById('totalOther');

submitBtn.addEventListener('click', () => {
    // Calculate total hours for each exercise type
    const totalRunning = parseFloat(runningInput.value) || 0;
    const totalCycling = parseFloat(cyclingInput.value) || 0;
    const totalOther = parseFloat(otherExerciseInput.value) || 0;

    // Update the readonly fields with calculated totals
    totalRunningInput.value = totalRunning;
    totalCyclingInput.value = totalCycling;
    totalOtherInput.value = totalOther;
});