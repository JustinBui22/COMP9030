<?php

// Database connection details
$host = 'database-9030.cho2aisawwwl.ap-southeast-2.rds.amazonaws.com';
$dbname = 'COMP9030';
$username = 'admin';
$password = 'Test1234!';

// Create a connection to the MariaDB database
$conn = new mysqli($host, $username, $password, $dbname);

// Check if connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
