<?php

ini_set('display_errors', 1); // Ensure that errors are shown
ini_set('display_startup_errors', 1); // Show errors that occur during PHP startup
error_reporting(E_ALL); // Report all types of errors

require_once "../../common/inc/dbconn.inc.php";

// Get patient_id from the URL
$patientId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($patientId) {
    // Fetch patient details
    $sql = "SELECT p.name, p.gender, p.age, p.height, p.weight, g.name as group_name 
            FROM patients p 
            LEFT JOIN groups g ON p.patient_group = g.id 
            WHERE p.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $patientId);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();

    // Fetch therapist notes for the patient
    $notesSql = "SELECT note FROM notes WHERE patient_id = ?";
    $notesStmt = $conn->prepare($notesSql);
    $notesStmt->bind_param("i", $patientId);
    $notesStmt->execute();
    $notesResult = $notesStmt->get_result();
    $notes = $notesResult->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $notesStmt->close();
    $conn->close();
} else {
    // Handle case where no valid patient ID is provided
    echo "No valid patient ID provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Details</title>
    <link rel="stylesheet" href="styles/patient-details.css">
</head>
<body>
    <!-- Back Button -->
    <button id="backButton" class="button" onclick="goBack()">Return</button>

    <!-- Patient Info Section -->
    <section id="patient-info">
        <div class="column avatar-name">
            <img src="../../common/assets/images/patient_icon.png" alt="Patient Avatar" class="avatar">
            <h2><?php echo htmlspecialchars($patient['name'] ?? 'Unknown'); ?></h2>
        </div>
        <div class="column patient-details">
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($patient['gender'] ?? 'N/A'); ?></p>
            <p><strong>Age:</strong> <?php echo htmlspecialchars($patient['age'] ?? 'N/A'); ?></p>
            <p><strong>Height:</strong> <?php echo htmlspecialchars($patient['height'] ?? 'N/A'); ?> cm</p>
            <p><strong>Weight:</strong> <?php echo htmlspecialchars($patient['weight'] ?? 'N/A'); ?> kg</p>
            <p><strong>Group:</strong> <?php echo htmlspecialchars($patient['group_name'] ?? 'N/A'); ?></p>
        </div>
        <div class="column action-buttons">
            <button onclick="editPatient()">Edit</button>
            <button onclick="changeGroup()" style="display: none;">Change Group</button>
            <button onclick="setGoal()" style="display: none;">Set Goal</button>
            <button onclick="openAddNoteModal()">Add Note</button>
        </div>
    </section>

    <!-- Patient Records Section -->
    <section id="patient-records">
        <h3 class="section-title">Detailed Record</h3>
        <hr>
        <div class="tabs">
            <button class="tablink" onclick="openTab(event, 'emotional-records')" id="defaultOpen">Emotional Records</button>
            <button class="tablink" onclick="openTab(event, 'sleep-records')">Sleep Records</button>
            <button class="tablink" onclick="openTab(event, 'exercise-records')">Exercise Records</button>
        </div>

        <div id="emotional-records" class="tabcontent">
            <h3>Emotional Records</h3>
            <p>Record 1: Feeling anxious on 15th Aug.</p>
            <p>Record 2: Feeling relaxed on 17th Aug.</p>
        </div>

        <div id="sleep-records" class="tabcontent">
            <h3>Sleep Records</h3>
            <p>Record 1: 6 hours of sleep on 14th Aug.</p>
            <p>Record 2: 8 hours of sleep on 16th Aug.</p>
        </div>

        <div id="exercise-records" class="tabcontent">
            <h3>Exercise Records</h3>
            <p>Record 1: 30 mins running on 12th Aug.</p>
            <p>Record 2: 1-hour yoga on 15th Aug.</p>
        </div>
    </section>

    <!-- Therapist Notes Section -->
    <section id="therapist-notes">
        <h3 class="section-title">Therapist Notes</h3>
        <hr>
        <?php if (!empty($notes)): ?>
            <?php foreach ($notes as $note): ?>
            <div class="note-card sticky-note">
                <h4>Therapist Note</h4>
                <p class="note-content"><?php echo htmlspecialchars($note['note']); ?></p>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No notes available for this patient.</p>
        <?php endif; ?>
    </section>

    <!-- Add Note Modal -->
    <div id="addNoteModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddNoteModal()">&times;</span>
            <h2>Add Note</h2>
            <textarea id="noteContent" placeholder="Write your note here..."></textarea>
            <button onclick="saveNote()">Save Note</button>
        </div>
    </div>

    <script src="scripts/patient-details.js"></script>
</body>
</html>