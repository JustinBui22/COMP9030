// Function to handle "View Details" button click
function viewDetails(patientId) {
    // Redirect to the patient details page with the patient ID as a URL query parameter
    window.location.href = `../patient-management/patient-details.php?id=${encodeURIComponent(patientId)}`;
}// Allow the drag to happen
function allowDrop(event) {
    event.preventDefault();
}

// Start dragging the card
function drag(event) {
    const cardId = event.target.id;  // Use event.target.id to get the element's ID
    event.dataTransfer.setData("text", cardId);
}

// Handle drop event on tabs
function dropOnTab(event, groupName) {
    event.preventDefault();
    const cardId = event.dataTransfer.getData("text");
    const cardElement = document.getElementById(cardId);
    const patientId = cardElement.id.replace('card', '');  // Extract patient ID from card ID
    
    // Update patient's group in the database
    updatePatientGroup(patientId, groupName);
    
    // Show an alert after the patient is assigned to the group
    alert(`${cardElement.querySelector('h3').innerText} has been assigned to ${groupName}`);

    window.location.href = `dashboard.php?group=${encodeURIComponent(groupName)}`;
}

// Function to update the patient's group in the database
function updatePatientGroup(patientId, groupName) {
    fetch('update_group.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ patientId: patientId, groupName: groupName })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log("Group updated successfully.");
        } else {
            console.error("Error updating group:", data.message);
        }
    })
    .catch(error => {
        console.error('Error updating patient group:', error);
    });
}

// Filter patients by group
function filterByGroup(groupName) {
    window.location.href = `dashboard.php?group=${encodeURIComponent(groupName)}`;
}

// Function to open the Add Note modal
function openAddNoteModal(patientId) {
    document.getElementById('addNoteModal').style.display = 'block';
    document.getElementById('saveNoteBtn').setAttribute('data-patient-id', patientId);
}

// Function to close the Add Note modal
function closeAddNoteModal() {
    document.getElementById('addNoteModal').style.display = 'none';
}

// Function to save the note
function saveNote() {
    const noteContent = document.getElementById('noteContent').value;
    const patientId = document.getElementById('saveNoteBtn').getAttribute('data-patient-id');

    if (noteContent) {
        // Save note to the database
        fetch('add_note.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ patientId: patientId, noteContent: noteContent })
        })
        .then(response => response.text())
        .then(data => {
            alert("Note saved: " + data);
            closeAddNoteModal(); // Close the modal after saving
        })
        .catch(error => {
            console.error('Error saving note:', error);
        });
    } else {
        alert("Please enter a note.");
    }
}