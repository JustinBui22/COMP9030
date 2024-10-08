<?php
require_once "../../common/inc/dbconn.inc.php";

if (isset($_GET['patient_id'])) {
    $patientID = $_GET['patient_id'];

    // SQL query to sum activity hours over the past week for running, cycling, and others
    $sql = "SELECT 
                SUM(CASE WHEN weekly_activities LIKE '%Running%' THEN activities_for_week ELSE 0 END) AS running,
                SUM(CASE WHEN weekly_activities LIKE '%Cycling%' THEN activities_for_week ELSE 0 END) AS cycling,
                SUM(CASE WHEN weekly_activities LIKE '%Others%' THEN activities_for_week ELSE 0 END) AS others
            FROM patient_detail
            WHERE patient_id = ? 
            AND date >= (NOW() - INTERVAL 1 WEEK)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $patientID);
    $stmt->execute();
    $result = $stmt->get_result();
    $totals = $result->fetch_assoc();

    // If no totals exist, default to zero
    $totals = array_map(function($val) { return $val ?? 0; }, $totals);

    header('Content-Type: application/json');
    echo json_encode($totals);
}

$conn->close();
?>