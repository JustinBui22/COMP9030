// Allow the drag to happen
function allowDrop(event) {
    event.preventDefault();
}

// Start dragging the card
function drag(event) {
    event.dataTransfer.setData("text", event.target.id);
}

// Handle drop event on tabs
function dropOnTab(event, groupName) {
    event.preventDefault();
    var cardId = event.dataTransfer.getData("text");
    var cardElement = document.getElementById(cardId);
    
    // Show an alert when a card is dropped on a group tab
    alert(`${cardElement.querySelector('h3').innerText} has been assigned to ${groupName}`);
}
// Function to handle "View Details" button click
function viewDetails(patientId) {
    // Redirect to the patient details page with the patient ID as a URL query parameter
    window.location.href = `../patient-management/patient-details.html?id=${encodeURIComponent(patientId)}`;
}