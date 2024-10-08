<?php
// Database connection details
include "../../common/inc/dbconn.inc.php";

// Initialize an array to hold the data
$data = [];

// Determine which data to fetch based on the type
if ($type === 'exercise') {
    $result = $conn->query("SELECT * FROM exercises");
    if ($result->num_rows > 0) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
    }
} elseif ($type === 'food') {
    $result = $conn->query("SELECT * FROM food_info");
    if ($result->num_rows > 0) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
    }
} else {
    echo json_encode(["error" => "Invalid type specified"]);
    exit;
}

// Return the data as JSON
echo json_encode($data);
