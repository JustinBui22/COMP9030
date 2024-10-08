<?php
require_once "../../common/inc/dbconn.inc.php";

// Get group filter from the URL, if any
$groupFilter = isset($_GET['group']) ? $_GET['group'] : 'all';

// SQL query to fetch patients and join with groups table
$sql = "SELECT p.id, p.name, p.status, g.name AS group_name
        FROM patients p
        LEFT JOIN groups g ON p.patient_group = g.id";

// Apply group filter if selected
if ($groupFilter !== 'all') {
    $sql .= " WHERE p.patient_group = ?";
}

$stmt = $conn->prepare($sql);

if ($groupFilter !== 'all') {
    $stmt->bind_param("i", $groupFilter); // Bind the group filter
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/patient-list.css">
    <title>Patient List</title>
</head>
<body>

    <!-- Return Button -->
    <button id="return-button" onclick="goBack()" style="display: none;">Return to Group Management</button>

    <h2>Patient List</h2>

    <!-- Filter and Sort Options -->
    <div class="controls">
        <label for="group-filter">Group:</label>
        <select id="group-filter" onchange="filterPatients()">
            <option value="all">All Groups</option>
            <?php
            // Fetch groups for filtering
            $groups = mysqli_query($conn, "SELECT id, name FROM groups");
            while ($group = mysqli_fetch_assoc($groups)) {
                echo "<option value='" . $group['id'] . "'>" . $group['name'] . "</option>";
            }
            ?>
        </select>

        <label for="sort-options">Sort By:</label>
        <select id="sort-options" onchange="sortPatients()">
            <option value="name">Name</option>
            <option value="status">Status</option>
            <option value="group">Group</option>
        </select>

        <button onclick="openAddPatientModal()">Add Patient</button>
        <button onclick="exportPatients()">Export List</button>
    </div>

    <!-- Patient Table -->
    <table id="patient-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Group</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="patient-tbody">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo $row['group_name']; ?></td>
                    <td>
                        <button onclick="openEditPatientModal('<?php echo $row['id']; ?>')">Edit</button>
                        <button onclick="viewPatient('<?php echo $row['id']; ?>')">View</button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Add/Edit Patient Modal -->
    <div id="patient-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('patient-modal')">&times;</span>
            <h3 id="patient-modal-title">Add/Edit Patient</h3>
            <form id="patient-form">
                <input type="hidden" id="patient-id" value="">
                <label for="patient-name">Name:</label>
                <input type="text" id="patient-name" value="">
                <label for="patient-status">Status:</label>
                <select id="patient-status">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
                <label for="patient-group">Group:</label>
                <select id="patient-group">
                    <?php
                    // Populate group dropdown in modal
                    $groups = mysqli_query($conn, "SELECT id, name FROM groups");
                    while ($group = mysqli_fetch_assoc($groups)) {
                        echo "<option value='" . $group['id'] . "'>" . $group['name'] . "</option>";
                    }
                    ?>
                </select>
                <button type="button" onclick="savePatient()">Save</button>
            </form>
        </div>
    </div>

    <script src="scripts/patient-list.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>