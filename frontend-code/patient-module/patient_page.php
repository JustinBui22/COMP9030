<?php
// patient.php:                                                                                                                                                                                         <?php

// Database configuration
$servername = "database-9030.cho2aisawwwl.ap-southeast-2.rds.amazonaws.com";
$username = "admin";
$password = "Test1234!";
$dbname = "COMP9030";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form
    $patientName = $_POST["patientName"];
    $patientID = $_POST["patientID"];
    $exerciseDate = $_POST["exerciseDate"];
    $runningHours = $_POST["running"];
    $cyclingHours = $_POST["cycling"];
    $otherHours = $_POST["others"];
    $affirmation = $_POST["affirmation"];

    $patientName = $conn->real_escape_string($patientName);
    $patientID = $conn->real_escape_string($patientID);
    $exerciseDate = $conn->real_escape_string($exerciseDate);
    $runningHours = $conn->real_escape_string($runningHours);
    $cyclingHours = $conn->real_escape_string($cyclingHours);
    $otherHours = $conn->real_escape_string($otherHours);
    $affirmation = $conn->real_escape_string($affirmation);
 //Check if patient exists, if not, add them.
 $checkPatient = "SELECT patient_id FROM patients WHERE patient_name = '$patientName' AND patient_id = '$patientID'";
 $result = $conn->query($checkPatient);

 if ($result->num_rows == 0) {
     $sql = "INSERT INTO patients (patient_name, patient_id) VALUES ('$patientName', '$patientID')";
     if (!$conn->query($sql)) {
         die("Error adding patient: " . $conn->error);
     }
     $patient_id = $conn->insert_id; //Get the ID of the newly inserted patient
 } else {
     $row = $result->fetch_assoc();
     $patient_id = $row["patient_id"];
 }

 //Insert exercise data
 $sqlExercise = "INSERT INTO exercises (patient_id, exercise_date, activity, hours) VALUES
                                 ('$patient_id', '$exerciseDate', 'Running', '$runningHours'),
                                 ('$patient_id', '$exerciseDate', 'Cycling', '$cyclingHours'),
                                 ('$patient_id', '$exerciseDate', 'Others', '$otherHours')";
 if (!$conn->multi_query($sqlExercise)) {
     die("Error inserting exercise data: " . $conn->error);
 }
  //Insert affirmation data
  $sqlAffirmation = "INSERT INTO affirmations (patient_id, affirmation_date, affirmation) VALUES ('$patient_id', '$exerciseDate', '$affirmation')";
  if (!$conn->query($sqlAffirmation)) {
      die("Error inserting affirmation: " . $conn->error);
  }


  echo "Data submitted successfully!";

} else {
  // Handle non-POST requests (e.g., display a form)
  echo "Error: Invalid request method.";
}

$conn->close();

?>