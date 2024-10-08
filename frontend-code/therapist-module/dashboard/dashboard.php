<?php
require_once "../../common/inc/dbconn.inc.php";

// Fetch all groups from the database
$sqlGroups = "SELECT id, name FROM groups";
$resultGroups = $conn->query($sqlGroups);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Therapist Dashboard</title>
    <link rel="stylesheet" href="styles/dashboard.css"> <!-- Link to external CSS -->
</head>
<body>

<h2>Therapist Dashboard</h2>

<!-- Group Filter Tabs (Droppable areas) -->
<div class="tab-menu">
    <button class="tab" onclick="filterByGroup('ALL')">ALL</button>
    <?php while ($group = $resultGroups->fetch_assoc()) { ?>
        <button class="tab" ondragover="allowDrop(event)" ondrop="dropOnTab(event, '<?php echo $group['name']; ?>')" onclick="filterByGroup('<?php echo $group['name']; ?>')"><?php echo $group['name']; ?></button>
    <?php } ?>
</div>

<!-- Card Layout for Patients -->
<div class="card-container">
<?php

// Get the selected group from the URL (if any)
$groupName = isset($_GET['group']) ? $_GET['group'] : 'ALL';

// Fetch patients and their corresponding group names for this therapist
if ($groupName === 'ALL') {
    $sql = "SELECT p.*, g.name AS group_name
            FROM patients p
            LEFT JOIN groups g ON p.patient_group = g.id
            WHERE p.therapist_id = 1;";
} else {
    $sql = "SELECT p.*, g.name AS group_name
            FROM patients p
            LEFT JOIN groups g ON p.patient_group = g.id
            WHERE p.therapist_id = 1 AND g.name = ?";
}

$stmt = $conn->prepare($sql);

if ($groupName !== 'ALL') {
    $stmt->bind_param("s", $groupName);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='card' id='card" . $row['id'] . "' draggable='true' ondragstart='drag(event)'>";
        echo "<img src='" . $row['avatar_url'] . "' alt='Patient Avatar'>";
        echo "<h3>" . $row['name'] . "</h3>";
        echo "<p>Status: " . $row['status'] . "</p>";
        echo "<p>Group: " . ($row['group_name'] ? $row['group_name'] : 'No Group Assigned') . "</p>";
        echo "<button onclick='viewDetails(" . $row['id'] . ")'>View Details</button>";
        echo "<button onclick='openAddNoteModal(" . $row['id'] . ")'>Add Note</button>";
        echo "</div>";
    }
} else {
    echo "<p>No patients found.</p>";
}

$stmt->close();
$conn->close();
?>
</div>

<!-- Add Note Modal -->
<div id="addNoteModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddNoteModal()">&times;</span>
        <h2>Add Note</h2>
        <textarea id="noteContent" placeholder="Write your note here..."></textarea>
        <button id="saveNoteBtn" onclick="saveNote()">Save Note</button>
    </div>
</div>

<script src="scripts/dashboard.js"></script> <!-- Link to external JS -->
</body>
</html>