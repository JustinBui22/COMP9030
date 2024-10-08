// Tab functionality
function openTab(evt, tabName) {
    var i, tabcontent, tablinks;

    // Hide all tab contents
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Remove active class from all tab links
    tablinks = document.getElementsByClassName("tablink");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].style.backgroundColor = "";
        tablinks[i].style.color = "";
    }

    // Show the selected tab content
    document.getElementById(tabName).style.display = "block";

    // Set active class on clicked tab
    evt.currentTarget.style.backgroundColor = "#007bff";
    evt.currentTarget.style.color = "white";
}

// Default tab to be opened on page load
document.getElementById("defaultOpen").click();

// "More" click event for sticky notes
document.querySelectorAll('.more').forEach(function(element) {
    element.addEventListener('click', function() {
        let note = this.closest('.sticky-note').querySelector('.note-content');
        if (note) {
            if (note.style.overflow === 'hidden') {
                note.style.overflow = 'visible';
                note.style.height = 'auto';
                this.textContent = 'less';
            } else {
                note.style.overflow = 'hidden';
                note.style.height = '80px';
                this.textContent = '...more';
            }
        }
    });
});
// Function to go back to the previous page
function goBack() {
    window.history.back();
}

// Function to get query parameters from the URL
function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

// Save note functionality
function saveNote() {
    const noteContent = document.getElementById('noteContent').value;
    const patientId = getQueryParam('id');  // Get patient ID from the URL

    if (noteContent) {
        fetch('add_note.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ patientId: patientId, noteContent: noteContent })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Note added successfully!');
                closeAddNoteModal();
                location.reload(); // Reload page to reflect new note
            } else {
                alert('Error: ' + data.message);
            }
        });
    } else {
        alert('Please enter a note.');
    }
}

// Function to open the Add Note modal
function openAddNoteModal() {
    document.getElementById('addNoteModal').style.display = 'block';
}

// Function to close the Add Note modal
function closeAddNoteModal() {
    document.getElementById('addNoteModal').style.display = 'none';
}