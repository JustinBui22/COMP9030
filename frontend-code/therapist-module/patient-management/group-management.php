<?php
require_once "../../common/inc/dbconn.inc.php";

// Fetch all groups
$sql = "SELECT 
            g.id, 
            g.name, 
            g.description, 
            g.status, 
            COALESCE(schedules.title, 'No Schedule') AS upcoming_schedule, 
            COUNT(group_members.patient_id) AS member_count
        FROM groups g
        LEFT JOIN schedules ON schedules.group_id = g.id
        LEFT JOIN group_members ON group_members.group_id = g.id
        GROUP BY g.id, g.name, g.description, g.status, schedules.title";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/group-management.css">
    <title>Group Management</title>
</head>
<body>
    <h2>Group Management</h2>

    <!-- Group Cards Container -->
    <div class="group-container">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="group-card">
            <div class="group-header">
                <img src="../../common/assets/images/group_icon.png" alt="Group Thumbnail" class="group-thumb">
                <div>
                    <h3><?php echo $row['name']; ?></h3>
                    <a href="patient-list.php?group_id=<?php echo $row['id']; ?>">Members: <span><?php echo $row['member_count']; ?></span></a>
                </div>
            </div>
            <div class="group-body">
                <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
                <p><strong>Status:</strong> <?php echo $row['status']; ?></p>
                <p><strong>Upcoming Schedule:</strong> <?php echo $row['upcoming_schedule']; ?></p>
            </div>
            <div class="group-footer">
                <button onclick="openEditModal(<?php echo $row['id']; ?>)">Edit Info</button>
                <button onclick="openAddMemberModal(<?php echo $row['id']; ?>)">Add Member</button>
                <button onclick="openViewModal(<?php echo $row['id']; ?>)">View Info</button>
                <button onclick="openScheduleModal(<?php echo $row['id']; ?>)">Schedule Event</button> <!-- Schedule Event button -->
            </div>
        </div>
        <?php } ?>
    </div>

    <!-- Edit Group Modal -->
    <div id="edit-info-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('edit-info-modal')">&times;</span>
            <h3>Edit Group Information</h3>
            <form id="edit-group-form">
                <input type="hidden" id="group-id" value="">
                <label for="group-name">Group Name:</label>
                <input type="text" id="group-name" value="">
                <label for="group-desc">Group Description:</label>
                <textarea id="group-desc"></textarea>
                <button type="button" onclick="saveGroupInfo()">Save</button>
            </form>
        </div>
    </div>

    <!-- View Group Modal -->
    <div id="view-info-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('view-info-modal')">&times;</span>
            <h3>View Group Information</h3>
            <form>
                <label for="group-name-view">Group Name:</label>
                <input type="text" id="group-name-view" disabled>
                <label for="group-desc-view">Group Description:</label>
                <textarea id="group-desc-view" disabled></textarea>
            </form>
        </div>
    </div>

    <!-- Add Member Modal -->
    <div id="add-member-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('add-member-modal')">&times;</span>
            <h3>Add Member</h3>
            <form id="add-member-form">
                <input type="hidden" id="group-id-add" value="">
                <label for="member-select">Select a patient:</label>
                <select id="member-select">
                    <?php
                    $patients = mysqli_query($conn, "SELECT id, name FROM patients");
                    while ($patient = mysqli_fetch_assoc($patients)) {
                        echo "<option value='" . $patient['id'] . "'>" . $patient['name'] . "</option>";
                    }
                    ?>
                </select>
                <button type="button" onclick="addMember()">Add</button>
            </form>
        </div>
    </div>
    <!-- Schedule Event Modal -->
    <div id="schedule-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('schedule-modal')">&times;</span>
            <h3>Schedule Event</h3>
            <form id="schedule-event-form">
                <input type="hidden" id="group-id-schedule" value="">
                <label for="schedule-title">Title:</label>
                <input type="text" id="schedule-title" placeholder="Event Title">
                <label for="schedule-date">Date:</label>
                <input type="date" id="schedule-date">
                <label for="schedule-description">Description:</label>
                <textarea id="schedule-description" placeholder="Event Details"></textarea>
                <button type="button" onclick="saveSchedule()">Save</button>
            </form>
        </div>
    </div>

    <script src="scripts/group-management.js"></script>
</body>
</html>

<?php mysqli_close($conn); ?>
