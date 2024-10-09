<?php

define("DB_HOST", "database-9030.cho2aisawwwl.ap-southeast-2.rds.amazonaws.com");
define("DB_NAME", "COMP9030");
define("DB_USER", "admin");
define("DB_PASS", "Test1234!");

$conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, 3306); // Port 3306 is used for MariaDB/MySQL

if (!$conn) {
    // Error handling for the connection
    echo "Error: Unable to connect to database.<br>";
    echo "Debugging errno: " . mysqli_connect_errno() . "<br>";
    echo "Debugging error: " . mysqli_connect_error() . "<br>";
    exit;
}
?>