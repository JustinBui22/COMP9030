<?php
require_once "../../common/inc/dbconn.inc.php";

if (isset($_GET['patient_id']) && isset($_GET['date'])) {
    $patientID = $_GET['patient_id'];
    $date = $_GET['date'];

    // SQL query to fetch existing data for the given patient and date
    $sql = "SELECT weekly_activities AS running, activities_for_week AS cycling, goals AS others, daily_affirmations AS affirmation 
            FROM patient_detail 
            WHERE patient_id = ? AND date = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $patientID, $date);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    header('Content-Type: application/json');
    echo json_encode($data);
}

$conn->close();
?>