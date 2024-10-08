<?php

require_once "../../common/inc/dbconn.inc.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    // Add new patient
    if ($action === 'addPatient') {
        $name = $_POST['name'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $age = $_POST['age'] ?? null;
        $height = $_POST['height'] ?? null;
        $weight = $_POST['weight'] ?? null;
        $status = $_POST['status'] ?? '';
        $groupId = $_POST['group_id'] ?? null;

        if ($name && $status && $groupId !== null) {
            $sql = "INSERT INTO patients (name, gender, age, height, weight, status, patient_group) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ssiddsi", $name, $gender, $age, $height, $weight, $status, $groupId);
                if ($stmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Patient added successfully.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error executing query: ' . $stmt->error]);
                }
                $stmt->close();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input: all required fields are not provided.']);
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