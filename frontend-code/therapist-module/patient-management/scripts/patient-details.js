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

// Function to open the Add Note modal
function openAddNoteModal() {
    document.getElementById('addNoteModal').style.display = 'block';
}

// Function to close the Add Note modal
function closeAddNoteModal() {
    document.getElementById('addNoteModal').style.display = 'none';
}

// Function to save the note (simple alert for now)
function saveNote() {
    const noteContent = document.getElementById('noteContent').value;
    if (noteContent) {
        alert("Note saved: " + noteContent);
        closeAddNoteModal(); // Close the modal after saving
    } else {
        alert("Please enter a note.");
    }
}
