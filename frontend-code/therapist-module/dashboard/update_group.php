<?php
require_once "../../common/inc/dbconn.inc.php";

header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);
$patientId = $input['patientId'];
$groupName = $input['groupName'];

if (!$patientId || !$groupName) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit;
}

// Get group ID by group name
$sqlGroup = "SELECT id FROM groups WHERE name = ?";
$stmtGroup = $conn->prepare($sqlGroup);
$stmtGroup->bind_param('s', $groupName);
$stmtGroup->execute();
$resultGroup = $stmtGroup->get_result();
if ($resultGroup->num_rows > 0) {
    $group = $resultGroup->fetch_assoc();
    $groupId = $group['id'];

    // Update patient group
    $sqlUpdate = "UPDATE patients SET patient_group = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param('ii', $groupId, $patientId);
    if ($stmtUpdate->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update patient group']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Group not found']);
}

$stmtGroup->close();
$conn->close();