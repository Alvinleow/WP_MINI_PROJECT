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

// Retrieve the Event List data from the database
$sqlSelectEvents = "SELECT * FROM `Event List`";

$result = $conn->query($sqlSelectEvents);

if ($result && $result->num_rows > 0) {
    // Display the Event List table
    echo "<table>";
    echo "<tr><th>Event ID</th><th>Event Name</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["eventId"] . "</td>";
        echo "<td><a href='http://localhost/utm/WP_MINI_PROJECT/php/displayEventTable.php?eventName=" . urlencode($row["eventName"]) . "'>" . $row["eventName"] . "</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No events found.";
}

// Close the database connection
$conn->close();
?>
