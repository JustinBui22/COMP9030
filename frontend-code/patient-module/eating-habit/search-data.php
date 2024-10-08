<?php
// Database connection details
include "../../common/inc/dbconn.inc.php";

// Handle search request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $query = $_GET['query'];
    $type = $_GET['type']; // New parameter to specify the type of search

    // Prepare SQL statement based on the type
    if ($type === 'exercise') {
        $stmt = $conn->prepare("SELECT * FROM exercises WHERE ex_name LIKE ?");
    } elseif ($type === 'food') {
        $stmt = $conn->prepare("SELECT * FROM food_info WHERE food_name LIKE ?");
    }

    // Use wildcard for partial matching
    $searchTerm = '%' . $query . '%';
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch results
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

    // Return results as JSON
    echo json_encode($items);
    exit;
}
