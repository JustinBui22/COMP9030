<?php
require_once "../../common/inc/dbconn.inc.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action === 'editGroup') {
        $groupId = $_POST['group_id'];
        $groupName = $_POST['group_name'];
        $groupDesc = $_POST['group_desc'];

        $sql = "UPDATE groups SET name = ?, description = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $groupName, $groupDesc, $groupId);
        $stmt->execute();
        echo json_encode(['status' => 'success', 'message' => 'Group updated successfully.']);
    } elseif ($action === 'addMember') {
        $groupId = $_POST['group_id'];
        $memberId = $_POST['member_id'];

        $sql = "INSERT INTO group_members (group_id, patient_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $groupId, $memberId);
        $stmt->execute();

        // Update group member count
        $sqlUpdate = "UPDATE groups SET member_count = member_count + 1 WHERE id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("i", $groupId);
        $stmtUpdate->execute();

        echo json_encode(['status' => 'success', 'message' => 'Member added successfully.']);
    } elseif ($action === 'scheduleEvent') {
        $groupId = $_POST['group_id'];
        $title = $_POST['title'];
        $scheduleDate = $_POST['schedule_date'];
        $description = $_POST['description'];

        $sql = "INSERT INTO schedules (group_id, title, schedule_date, description) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $groupId, $title, $scheduleDate, $description);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'Schedule added successfully.']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['group_id'])) {
    // Fetch group details
    $groupId = $_GET['group_id'];

    $sql = "SELECT name, description FROM groups WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $groupId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $group = $result->fetch_assoc();
        echo json_encode($group);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Group not found']);
    }
}

mysqli_close($conn);
?>
