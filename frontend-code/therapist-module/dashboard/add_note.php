<?php
require_once "../../common/inc/dbconn.inc.php";

$data = json_decode(file_get_contents('php://input'), true);
$patientId = $data['patientId'];
$noteContent = $data['noteContent'];

// Insert the note into the notes table
$sql = "INSERT INTO notes (patient_id, note) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $patientId, $noteContent);

if ($stmt->execute()) {
    echo "Note added successfully.";
} else {
    echo "Error adding note.";
}

$conn->close();
?>