// Open modal function
function openModal(modalId, isViewOnly = false) {
    if (isViewOnly) {
        // View Details: Populate modal with read-only values
        document.getElementById('group-name-view').value = "Group A"; // Example group name
        document.getElementById('group-desc-view').value = "This group focuses on rehabilitation and therapy.";
    }
    document.getElementById(modalId).style.display = 'block';
}

// Close modal function
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Add member function (to be extended)
function addMember() {
    const selectedMember = document.getElementById('member-select').value;
    alert(`Added ${selectedMember} to the group!`);
    closeModal('add-member-modal');
}

// Save group info (to be extended)
function saveGroupInfo() {
    const groupName = document.getElementById('group-name').value;
    const groupDesc = document.getElementById('group-desc').value;
    alert(`Group info saved: ${groupName} - ${groupDesc}`);
    closeModal('edit-info-modal');
}