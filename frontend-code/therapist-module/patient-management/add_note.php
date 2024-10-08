<?php
require_once "../../common/inc/dbconn.inc.php";

// Decode the incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Check if both patientId and noteContent are provided
if (isset($data['patientId']) && isset($data['noteContent'])) {
    $patientId = $data['patientId'];
    $noteContent = $data['noteContent'];

    // Insert the note into the 'notes' table instead of 'therapist_notes'
    $sql = "INSERT INTO notes (patient_id, note) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $patientId, $noteContent);

    // Check if the query executed successfully
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add note.']);
    }

    // Close the prepared statement
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
}

// Close the database connection
$conn->close();
?>