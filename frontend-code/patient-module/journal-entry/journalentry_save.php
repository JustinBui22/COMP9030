<?php
//echo "PHP is working";
// Database connection details
$servername = "localhost";
$username = "dbadmin";  // Default username for XAMPP
$password = "";  // Default password for XAMPP (empty)
$dbname = "COMP9030";  // The database you created

//Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from the POST request
$date = $_POST['date'];
$journal_entry = $_POST['journal_entry'];
$mood = $_POST['mood'];
$mood_notes = $_POST['mood_notes'];

// Prepare and bind the SQL query
$sql = "INSERT INTO journal_entries (entry_date, journal_text, mood, mood_notes) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $date, $journal_entry, $mood, $mood_notes);

// Execute the query and check for success
if ($stmt->execute()) {
    echo "Journal entry saved successfully!";
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
