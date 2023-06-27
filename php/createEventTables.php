<?php
$servername = "localhost";
$username_db = "alvinleow";
$password_db = "alvinleow020816";
$dbname = "project_user_DB";

// Create connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$eventName = $_POST['eventName'];

// Create a table with the event name as the table name
$sqlCreateEventTable = "CREATE TABLE IF NOT EXISTS `$eventName` (
    numbers INT AUTO_INCREMENT PRIMARY KEY,
    participantsName VARCHAR(100) NOT NULL,
    attendance TINYINT(1) NOT NULL DEFAULT 0
)";

// Execute the query to create the event table
if ($conn->query($sqlCreateEventTable) === TRUE) {
    echo "Table created successfully!";
} else {
    echo "Error creating table: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
