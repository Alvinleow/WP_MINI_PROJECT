<?php
$servername = "localhost";
$username_db = "alvinleow";
$password_db = "alvinleow020816";
$dbname = "project_user_DB";

// Retrieve the event name, numbers, and attendance from the AJAX request
$eventName = $_GET['eventName'];
$numbers = $_GET['numbers'];
$attendance = $_GET['attendance'];

// Create connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update the attendance value in the database
$sqlUpdateAttendance = "UPDATE `$eventName` SET attendance = $attendance WHERE numbers = $numbers";

if ($conn->query($sqlUpdateAttendance) === TRUE) {
    echo "Attendance updated successfully!";
} else {
    echo "Error updating attendance: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
