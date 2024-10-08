<?php
require_once "../../common/inc/dbconn.inc.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    // Add new patient
    if ($action === 'addPatient') {
        $name = $_POST['name'] ?? '';
        $status = $_POST['status'] ?? '';
        $groupId = $_POST['group_id'] ?? null;
    
        // Check if all required fields are present
        if ($name && $status && $groupId !== null) {
            $sql = "INSERT INTO patients (name, status, patient_group) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
    
            if ($stmt) {
                // Binding parameters and executing statement
                $stmt->bind_param("ssi", $name, $status, $groupId);
                if ($stmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Patient added successfully.']);
                } else {
                    // Output SQL error for debugging purposes
                    echo json_encode(['status' => 'error', 'message' => 'Error executing query: ' . $stmt->error]);
                }
                $stmt->close();
            } else {
                // Output error related to the prepare statement
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
            }
        } else {
            // Missing data validation
            echo json_encode(['status' => 'error', 'message' => 'Invalid input: name, status, and group are required.']);
        }
    }
    // Edit existing patient
    elseif ($action === 'editPatient') {
        $patientId = $_POST['patient_id'] ?? null;
        $name = $_POST['name'] ?? '';
        $status = $_POST['status'] ?? '';
        $groupId = $_POST['group_id'] ?? null;

        if ($patientId && $name && $status && $groupId) {  // Validate inputs
            $sql = "UPDATE patients SET name = ?, status = ?, patient_group = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("ssii", $name, $status, $groupId, $patientId);
                if ($stmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Patient updated successfully.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error updating patient.']);
                }
                $stmt->close();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($_GET['action'] === 'getPatient') {
        $patientId = $_GET['patient_id'] ?? null;

        if ($patientId) {  // Validate input
            // Fetch patient details
            $sql = "SELECT id, name, status, patient_group FROM patients WHERE id = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("i", $patientId);
                $stmt->execute();
                $result = $stmt->get_result();
                $patient = $result->fetch_assoc();
                
                echo json_encode($patient);
                $stmt->close();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid patient ID.']);
        }
    }
}

$conn->close();
?>