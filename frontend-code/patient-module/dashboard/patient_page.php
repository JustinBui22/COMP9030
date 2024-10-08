<?php

require_once "../../common/inc/dbconn.inc.php";
// Handle GET requests for daily data and weekly totals
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($_GET['action'] === 'getDailyData') {
        $patientID = $_GET['patient_id'];
        $date = $_GET['date'];

        // Fetch the data for the specific date
        $sql = "SELECT weekly_activities AS runningHours, activities_for_week AS cyclingHours, goals AS otherHours, daily_affirmations AS affirmation 
                FROM patient_detail 
                WHERE patient_id = ? AND date = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $patientID, $date);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        // If no data is found, return empty values
        if (!$data) {
            $data = [
                'runningHours' => 0,
                'cyclingHours' => 0,
                'otherHours' => 0,
                'affirmation' => ''
            ];
        }
        echo json_encode($data);
        exit;
    }

    if ($_GET['action'] === 'getWeeklyTotals') {
        $patientID = $_GET['patient_id'];
        $date = $_GET['date'];

        // Calculate the start and end of the week for the selected date
        $weekStart = date('Y-m-d', strtotime('monday this week', strtotime($date)));
        $weekEnd = date('Y-m-d', strtotime('sunday this week', strtotime($date)));

        // Fetch weekly totals for running, cycling, and others
        $sql = "SELECT 
                    SUM(CASE WHEN weekly_activities IS NOT NULL THEN weekly_activities ELSE 0 END) AS totalRunning,
                    SUM(CASE WHEN activities_for_week IS NOT NULL THEN activities_for_week ELSE 0 END) AS totalCycling,
                    SUM(CASE WHEN goals IS NOT NULL THEN goals ELSE 0 END) AS totalOthers
                FROM patient_detail
                WHERE patient_id = ? AND date BETWEEN ? AND ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $patientID, $weekStart, $weekEnd);
        $stmt->execute();
        $result = $stmt->get_result();
        $totals = $result->fetch_assoc();

        echo json_encode($totals);
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form
    $patientID = $_POST["patientID"];
    $exerciseDate = $_POST["exerciseDate"];
    $runningHours = $_POST["runningHours"];
    $cyclingHours = $_POST["cyclingHours"];
    $otherHours = $_POST["otherHours"];
    $affirmation = $_POST["affirmation"];

    // Sanitize inputs
    $patientID = $conn->real_escape_string($patientID);
    $exerciseDate = $conn->real_escape_string($exerciseDate);
    $runningHours = $conn->real_escape_string($runningHours);
    $cyclingHours = $conn->real_escape_string($cyclingHours);
    $otherHours = $conn->real_escape_string($otherHours);
    $affirmation = $conn->real_escape_string($affirmation);

    // Insert or update data into patient_detail table
    $sqlDetail = "INSERT INTO patient_detail (patient_id, date, weekly_activities, activities_for_week, goals, daily_affirmations)
                  VALUES ('$patientID', '$exerciseDate', '$runningHours', '$cyclingHours', '$otherHours', '$affirmation')
                  ON DUPLICATE KEY UPDATE
                  weekly_activities='$runningHours', activities_for_week='$cyclingHours', goals='$otherHours', daily_affirmations='$affirmation'";

    if (!$conn->query($sqlDetail)) {
        echo "Error inserting patient details: " . $conn->error;
        exit;
    }

    // Insert affirmation data into affirmations table
    $sqlAffirmation = "INSERT INTO affirmations (patient_id, affirmation_date, affirmation)
                       VALUES ('$patientID', '$exerciseDate', '$affirmation')";
    if (!$conn->query($sqlAffirmation)) {
        echo "Error inserting affirmation: " . $conn->error;
        exit;
    }

    // Return a success message to be displayed in an alert
    echo "Data submitted successfully!";
}

$conn->close();
?>