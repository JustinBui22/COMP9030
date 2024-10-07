// Function to open the edit modal and populate fields
function openEditModal(groupId) {
    document.getElementById('group-id').value = groupId;

    // Fetch group info via AJAX
    fetch(`group-actions.php?group_id=${groupId}&action=getGroupDetails`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('group-name').value = data.name;
            document.getElementById('group-desc').value = data.description;
        })
        .catch(error => console.error('Error:', error)); // Catch any errors

    document.getElementById('edit-info-modal').style.display = 'block';
}

// Function to open the view modal and populate fields
function openViewModal(groupId) {
    // Fetch group info via AJAX
    fetch(`group-actions.php?group_id=${groupId}&action=getGroupDetails`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('group-name-view').value = data.name;
            document.getElementById('group-desc-view').value = data.description;
        })
        .catch(error => console.error('Error:', error)); // Catch any errors

    document.getElementById('view-info-modal').style.display = 'block';
}

// Function to save group info
function saveGroupInfo() {
    const groupId = document.getElementById('group-id').value;
    const groupName = document.getElementById('group-name').value;
    const groupDesc = document.getElementById('group-desc').value;

    const formData = new FormData();
    formData.append('action', 'editGroup');
    formData.append('group_id', groupId);
    formData.append('group_name', groupName);
    formData.append('group_desc', groupDesc);

    fetch('group-actions.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        closeModal('edit-info-modal');
        window.location.reload();  // Reload the page to refresh the group details
    })
    .catch(error => console.error('Error:', error)); // Catch any errors
}

// Function to open the Add Member modal
function openAddMemberModal(groupId) {
    document.getElementById('group-id-add').value = groupId;
    document.getElementById('add-member-modal').style.display = 'block';
}

// Function to add a member to the group
function addMember() {
    const groupId = document.getElementById('group-id-add').value;
    const memberId = document.getElementById('member-select').value;

    const formData = new FormData();
    formData.append('action', 'addMember');
    formData.append('group_id', groupId);
    formData.append('member_id', memberId);

    fetch('group-actions.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        closeModal('add-member-modal');
        window.location.reload();  // Reload the page to update member count
    })
    .catch(error => console.error('Error:', error)); // Catch any errors
}

// Function to close modals
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Function to open the schedule modal and populate fields
function openScheduleModal(groupId) {
    document.getElementById('group-id-schedule').value = groupId;
    document.getElementById('schedule-modal').style.display = 'block';
}

// Function to save a new schedule for the group
function saveSchedule() {
    const groupId = document.getElementById('group-id-schedule').value;
    const title = document.getElementById('schedule-title').value;
    const scheduleDate = document.getElementById('schedule-date').value;
    const description = document.getElementById('schedule-description').value;

    const formData = new FormData();
    formData.append('action', 'scheduleEvent');
    formData.append('group_id', groupId);
    formData.append('title', title);
    formData.append('schedule_date', scheduleDate);
    formData.append('description', description);

    fetch('group-actions.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        closeModal('schedule-modal');
        window.location.reload();  // Reload to update the upcoming schedule
    })
    .catch(error => console.error('Error:', error)); // Catch any errors
}
