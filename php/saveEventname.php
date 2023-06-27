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

// Create the "Event List" table if it doesn't exist
$sqlCreateTable = "CREATE TABLE IF NOT EXISTS `Event List` (
    eventId INT AUTO_INCREMENT PRIMARY KEY,
    eventName VARCHAR(255)
)";

if ($conn->query($sqlCreateTable) === FALSE) {
    echo "Error creating table: " . $conn->error;
    $conn->close();
    exit;
}

// Retrieve the event name from the prompt input
$eventName = $_POST['eventName'];

// Prepare the SQL query to insert the event name into the "Event List" table
$sqlInsertEvent = "INSERT INTO `Event List` (eventName) VALUES ('$eventName')";

// Execute the query to insert the event name into the "Event List" table
if ($conn->query($sqlInsertEvent) === TRUE) {
    echo "Event name saved successfully!";
} else {
    echo "Error: " . $sqlInsertEvent . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>
