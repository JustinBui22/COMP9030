const datePicker = document.getElementById('datePicker');
const selectedDate = document.getElementById('selectedDate');
const journalText = document.getElementById('journalText');
const moodTracker = document.getElementById('moodTracker');
const moodNotes = document.getElementById('moodNotes');
const saveJournalButton = document.getElementById('saveJournal');  // Save button for journal
const saveMoodNotesButton = document.getElementById('saveMoodNotes');  // Save button for mood notes
const editJournalButton = document.getElementById('editJournal');  // Edit button for journal
const editMoodNotesButton = document.getElementById('editMoodNotes');  // Edit button for mood notes

// Load saved data
window.onload = function() {
    datePicker.value = localStorage.getItem('date') || new Date().toISOString().split('T')[0];
    selectedDate.textContent = "Today : " + datePicker.value;

    journalText.value = localStorage.getItem('journal') || '';
    moodNotes.value = localStorage.getItem('moodNotes') || '';
    
    const savedMood = localStorage.getItem('mood');
    if (savedMood) {
        document.querySelector(`img[data-mood="${savedMood}"]`).classList.add('selected');
    }
};

// Save date
datePicker.addEventListener('change', function() {
    localStorage.setItem('date', datePicker.value);
    selectedDate.textContent = "Today : " + datePicker.value;
});

// Enable editing for journal text
editJournalButton.addEventListener('click', function() {
    journalText.disabled = false;
    saveJournalButton.disabled = false;  // Enable the save button
    journalText.focus();  // Focus on the textarea
});

// Function to send data to the PHP file via POST request
function saveToDatabase() {
    const date = document.getElementById('datePicker').value;
    const journalText = document.getElementById('journalText').value;
    const mood = localStorage.getItem('mood');  // Get the selected mood
    const moodNotes = document.getElementById('moodNotes').value;

    // Create form data to send
    const formData = new FormData();
    formData.append('date', date);
    formData.append('journal_entry', journalText);
    formData.append('mood', mood);
    formData.append('mood_notes', moodNotes);

    // Send the data using fetch API
    fetch('journalentry_save.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())  // Handle the response from the PHP file
    .then(data => {
        alert(data);  // Display the response (success or error)
    })
    .catch(error => {
        console.error('Error:', error);  // Log any errors to the console
    });
}

// Save journal entry when "Save Journal" button is clicked
saveJournalButton.addEventListener('click', function() {
    localStorage.setItem('journal', journalText.value);
    journalText.disabled = true;  // Disable editing after saving
    saveJournalButton.disabled = true;  // Disable the save button after saving
    alert('Journal entry saved locally!');  // Feedback to the user
    
    // Send data to the database
    saveToDatabase();
});

// Save mood notes when "Save Mood Notes" button is clicked
saveMoodNotesButton.addEventListener('click', function() {
    localStorage.setItem('moodNotes', moodNotes.value);
    moodNotes.disabled = true;  // Disable editing after saving
    saveMoodNotesButton.disabled = true;  // Disable the save button after saving
    alert('Mood notes saved locally!');  // Feedback to the user
    
    // Send data to the database
    saveToDatabase();
});


// Enable editing for mood notes
editMoodNotesButton.addEventListener('click', function() {
    moodNotes.disabled = false;
    saveMoodNotesButton.disabled = false;  // Enable the save button
    moodNotes.focus();  // Focus on the textarea
});



// Save mood when a mood is selected
moodTracker.addEventListener('click', function(e) {
    if (e.target.tagName === 'IMG') {
        document.querySelectorAll('.mood-tracker img').forEach(img => img.classList.remove('selected'));
        e.target.classList.add('selected');
        localStorage.setItem('mood', e.target.dataset.mood);
        alert('Mood saved!');  // Feedback to the user
    }
});
