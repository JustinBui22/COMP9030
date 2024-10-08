<?php
// Database connection details
include "../../common/inc/dbconn.inc.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $query = $_GET['query'];
    $type = $_GET['type'];

    // Based on the type
    if ($type === 'exercise') {
        $stmt = $conn->prepare("SELECT * FROM exercises WHERE ex_name LIKE ?");
    } elseif ($type === 'food') {
        $stmt = $conn->prepare("SELECT * FROM food_info WHERE food_name LIKE ?");
    }

    // Partial matching
    $searchTerm = '%' . $query . '%';
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

    // Results as JSON
    echo json_encode($items);
    exit;
}
