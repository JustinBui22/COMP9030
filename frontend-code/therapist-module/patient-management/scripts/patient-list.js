// Load initial data and apply URL filters
window.onload = function () {
    const urlParams = new URLSearchParams(window.location.search);
    const groupFilter = urlParams.get('group') || 'all';
    const fromGroupManagement = urlParams.get('from') === 'group-management';

    // Display return button if from Group Management page
    if (fromGroupManagement) {
        document.getElementById('return-button').style.display = 'inline-block';
    }

    document.getElementById('group-filter').value = groupFilter;
};

// Filter patients when the group filter is changed
function filterPatients() {
    const selectedGroup = document.getElementById('group-filter').value;
    window.location.href = `patient-list.php?group=${selectedGroup}`;
}

// Sort patients based on the selected option
function sortPatients() {
    const table = document.getElementById("patient-table");
    const rows = Array.from(table.rows).slice(1); // Ignore the header row
    const sortBy = document.getElementById('sort-options').value;

    rows.sort((rowA, rowB) => {
        const cellA = rowA.querySelector(`td:nth-child(${getSortIndex(sortBy)})`).innerText;
        const cellB = rowB.querySelector(`td:nth-child(${getSortIndex(sortBy)})`).innerText;
        return cellA.localeCompare(cellB);
    });

    rows.forEach(row => table.appendChild(row)); // Reattach rows in sorted order
}

function getSortIndex(sortBy) {
    switch (sortBy) {
        case 'name': return 1;
        case 'status': return 2;
        case 'group': return 3;
    }
}

// Function to open the add patient modal
function openAddPatientModal() {
    document.getElementById('patient-id').value = ''; // Clear the ID for new entry
    document.getElementById('patient-name').value = '';
    document.getElementById('patient-status').value = 'Active';
    document.getElementById('patient-group').value = '';
    document.getElementById('patient-modal-title').innerText = 'Add Patient';
    document.getElementById('patient-modal').style.display = 'block';
}

// Function to open the edit patient modal and populate fields
function openEditPatientModal(patientId) {
    document.getElementById('patient-modal-title').innerText = 'Edit Patient';

    // Fetch patient info via AJAX
    fetch(`patient-actions.php?action=getPatient&patient_id=${patientId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('patient-id').value = data.id;
            document.getElementById('patient-name').value = data.name;
            document.getElementById('patient-status').value = data.status;
            document.getElementById('patient-group').value = data.patient_group;
        })
        .catch(error => console.error('Error:', error));

    document.getElementById('patient-modal').style.display = 'block';
}

// Function to save patient info (add/edit)
function savePatient() {
    const patientId = document.getElementById('patient-id').value;
    const patientName = document.getElementById('patient-name').value;
    const patientStatus = document.getElementById('patient-status').value;
    const patientGroup = document.getElementById('patient-group').value;

    const formData = new FormData();
    formData.append('action', patientId ? 'editPatient' : 'addPatient');
    formData.append('patient_id', patientId);
    formData.append('name', patientName);
    formData.append('status', patientStatus);
    formData.append('group_id', patientGroup);

    fetch('patient-actions.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        displayNotification(data.message); // Display floating notification
        closeModal('patient-modal');
        window.location.reload();  // Reload the page to refresh the patient list
    })
    .catch(error => console.error('Error:', error));
}

// Function to close modals
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Display floating notification
function displayNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.innerText = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000); // Remove notification after 3 seconds
}

// Function to view a patient
function viewPatient(name) {
    window.location.href = `../patient-management/patient-details.php?id=${encodeURIComponent(name)}`;
}

// Function to go back to Group Management
function goBack() {
    window.history.back(); // Go back to the previous page
}