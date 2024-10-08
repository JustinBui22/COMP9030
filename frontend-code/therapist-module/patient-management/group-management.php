<?php
require_once "../../common/inc/dbconn.inc.php";

// Fetch all groups and calculate member count from patients table
$sql = "SELECT 
            g.id, 
            g.name, 
            g.description, 
            g.status, 
            COALESCE(schedules.title, 'No Schedule') AS upcoming_schedule, 
            (SELECT COUNT(*) FROM patients WHERE patients.patient_group = g.id) AS member_count
        FROM groups g
        LEFT JOIN schedules ON schedules.group_id = g.id
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
                    <a href="patient-list.php?group=<?php echo $row['id']; ?>">Members: <span><?php echo $row['member_count']; ?></span></a>
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
                <button onclick="openScheduleModal(<?php echo $row['id']; ?>)">Schedule Event</button>
            </div>
        </div>
        <?php } ?>
    </div>

    <!-- Add Group Button at the bottom -->
    <div class="add-group-container">
        <button class="add-group-btn" onclick="openAddGroupModal()">+ Add Group</button>
    </div>

    <!-- Add Group Modal -->
    <div id="add-group-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('add-group-modal')">&times;</span>
            <h3>Add Group</h3>
            <form id="add-group-form">
                <label for="group-name-add">Group Name:</label>
                <input type="text" id="group-name-add" required>

                <label for="group-desc-add">Group Description:</label>
                <textarea id="group-desc-add"></textarea>

                <label for="group-status-add">Group Status:</label>
                <select id="group-status-add">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>

                <button type="button" onclick="addGroup()">Add Group</button>
            </form>
        </div>
    </div>

    <script src="scripts/group-management.js"></script>
</body>
</html>