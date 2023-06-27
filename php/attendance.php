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
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta http-equiv='X-UA-Compatible' content='IE=edge'>";
    echo "<meta name='viewport' content='width=device-width initial-scale=1.0'>";
    echo "<title>WP Mini Project</title>";
    echo "<link rel='stylesheet' type='text/css' href='../css/Header.css'>";
    echo "<link rel='stylesheet' type='text/css' href='../css/Body-Container-2.css'>";
    echo "<link rel='stylesheet' type='text/css' href='../css/AboutUs.css'>";
    echo "<link rel='stylesheet' type='text/css' href='../css/table.css'>";
    echo "</head>";
    // Display the Event List table
    echo "<div class='body-container'>";
    echo "<div class='text'>";
    echo "<p class='title'>Event List</p>";
    echo "<a class='back' href='../Admin/HomePageAdmin.html'> < Back to Home Page</a>";
    echo "</div>";
    echo "<table>";
    echo "<tr><th>Event ID</th><th>Event Name</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["eventId"] . "</td>";
        echo "<td><a href='http://localhost/utm/WP_MINI_PROJECT/php/displayEventTable.php?eventName=" . urlencode($row["eventName"]) . "'>" . $row["eventName"] . "</a></td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
} else {
    echo "No events found.";
}

// Close the database connection
$conn->close();
?>
