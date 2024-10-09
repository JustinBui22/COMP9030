<?php
// Database connection details
include "../../common/inc/dbconn.inc.php";

$data = [];

// Based on the type
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

// Data as JSON
echo json_encode($data);
