// Sample patient data
const patients = [
    { name: "John Doe", status: "Active", group: "group_a" },
    { name: "Jane Smith", status: "Inactive", group: "group_b" },
    { name: "Bob Brown", status: "Active", group: "group_a" },
    // Add more sample patients
];

// Load initial data and apply URL filters
window.onload = function () {
    const urlParams = new URLSearchParams(window.location.search);
    const groupFilter = urlParams.get('group') || 'all';
    const fromGroupManagement = urlParams.get('from') === 'group-management'; // Check if accessed from Group Management

    // Display return buttons if from Group Management page
    if (fromGroupManagement) {
        document.getElementById('return-button').style.display = 'inline-block';
    }

    document.getElementById('group-filter').value = groupFilter;
    loadPatients(groupFilter);
};

// Load patients based on the selected group filter
function loadPatients(group) {
    const tbody = document.getElementById('patient-tbody');
    tbody.innerHTML = ''; // Clear current rows

    const filteredPatients = patients.filter(patient => {
        return group === 'all' || patient.group === group;
    });

    filteredPatients.forEach(patient => {
        const row = `<tr>
            <td>${patient.name}</td>
            <td>${patient.status}</td>
            <td>${patient.group}</td>
            <td>
                <button onclick="viewPatient('${patient.name}')">View</button>
                <button onclick="editPatient('${patient.name}')">Edit</button>
            </td>
        </tr>`;
        tbody.insertAdjacentHTML('beforeend', row);
    });
}

// Filter patients when group filter is changed
function filterPatients() {
    const selectedGroup = document.getElementById('group-filter').value;
    loadPatients(selectedGroup);
}

// Sort patients based on the selected option
function sortPatients() {
    const sortBy = document.getElementById('sort-options').value;
    patients.sort((a, b) => {
        if (a[sortBy] < b[sortBy]) return -1;
        if (a[sortBy] > b[sortBy]) return 1;
        return 0;
    });
    const selectedGroup = document.getElementById('group-filter').value;
    loadPatients(selectedGroup);
}

// Function to add a new patient
function addPatient() {
    alert('Add Patient functionality');
    // Implement logic to add a new patient (e.g., open a form)
}

// Function to export the patient list
function exportPatients() {
    alert('Export Patients functionality');
    // Implement logic to export the patient list (e.g., generate CSV)
}

// Placeholder functions for patient actions
function viewPatient(name) {
    window.location.href = `../patient-management/patient-details.html?id=${encodeURIComponent(name)}`;
}

function editPatient(name) {
    alert('Editing patient: ' + name);
}

// Return to the Group Management page
function goBack() {
    window.history.back(); // Go back to the previous page
}
