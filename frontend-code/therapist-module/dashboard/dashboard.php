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
        <button class="tab" ondragover="allowDrop(event)" ondrop="dropOnTab(event, 'ALL')" draggable="false">ALL</button>
        <button class="tab" ondragover="allowDrop(event)" ondrop="dropOnTab(event, 'Group 1')" draggable="false">Group 1</button>
        <button class="tab" ondragover="allowDrop(event)" ondrop="dropOnTab(event, 'Group 2')" draggable="false">Group 2</button>
        <button class="tab" ondragover="allowDrop(event)" ondrop="dropOnTab(event, 'Group 3')" draggable="false">Group 3</button>
    </div>

    <!-- Card Layout for Patients -->
    <div class="card-container">
    <?php

        // Include the database connection
        require_once "../../common/inc/dbconn.inc.php";

        // Fetch patients for this therapist
        $sql = "SELECT * FROM patients WHERE therapist_id = 1;";

        if ($result = mysqli_query($conn, $sql)) {
            // Fetch and display results
            // Display patient cards
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='card' id='card" . $row['id'] . "' draggable='true' ondragstart='drag(event)'>";
                    echo "<img src='" . $row['avatar_url'] . "' alt='Patient Avatar'>";
                    echo "<h3>" . $row['name'] . "</h3>";
                    echo "<p>Status: " . $row['status'] . "</p>";
                    echo "<p>Group: " . $row['patient_group'] . "</p>";
                    echo "<button onclick='viewDetails(" . $row['id'] . ")'>View Details</button>";
                    echo "<button onclick='openAddNoteModal(" . $row['id'] . ")'>Add Note</button>";
                    echo "</div>";
                }
            } else {
                echo "<p>No patients found.</p>";
            }

            mysqli_free_result($result);
        } else {
            echo "<p>Connected db error.</p>";
        }

        ?>
    </div>


    <!-- Add Note Modal -->
    <div id="addNoteModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddNoteModal()">&times;</span>
            <h2>Add Note</h2>
            <textarea id="noteContent" placeholder="Write your note here..."></textarea>
            <button onclick="saveNote()">Save Note</button>
        </div>
    </div>
    <script src="scripts/dashboard.js"></script> <!-- Link to external JS -->
</body>
</html>