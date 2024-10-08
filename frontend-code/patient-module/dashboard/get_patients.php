<?php
require_once "../../common/inc/dbconn.inc.php";

// Fetch the first patient from the patients table 
// TODO 
$sql = "SELECT id, name FROM patients LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $patient = $result->fetch_assoc();
    $patientID = $patient['id'];
    $patientName = $patient['name'];
} else {
    $patientID = '';
    $patientName = 'No patients found';
}
?>