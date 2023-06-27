<?php

if (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];
} else {
    echo "Cookie not found.";
}

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

// Retrieve the user's full name from the user database
$sqlRetrieveFullName = "SELECT fullname FROM User WHERE username = '$username'";

$result = $conn->query($sqlRetrieveFullName);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fullName = $row['fullname'];

    // Check if the participant's full name already exists in the event table
    $sqlCheckParticipant = "SELECT COUNT(*) AS count FROM `$eventName` WHERE participantsName = '$fullName'";

    $checkResult = $conn->query($sqlCheckParticipant);

    if ($checkResult && $checkResult->num_rows > 0) {
        $checkRow = $checkResult->fetch_assoc();
        $participantCount = $checkRow['count'];

        if ($participantCount > 0) {
            echo "You are already participating in this event!";
        } else {
            // Insert the participant's full name into the event table
            $sqlInsertParticipant = "INSERT INTO `$eventName` (participantsName) VALUES ('$fullName')";

            if ($conn->query($sqlInsertParticipant) === TRUE) {
                echo "Participant added successfully!";
            } else {
                echo "Error: " . $sqlInsertParticipant . "<br>" . $conn->error;
            }
        }
    } else {
        echo "Error: Failed to check participant.";
    }
} else {
    echo "Error: User not found!";
}

// Close the database connection
$conn->close();
?>
