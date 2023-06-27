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

// Retrieve the event name from the URL parameter
$eventName = $_GET['eventName'];

// Create a query to retrieve the event's table data
$sqlSelectEventTable = "SELECT * FROM `$eventName`";

$result = $conn->query($sqlSelectEventTable);

if ($result && $result->num_rows > 0) {
    // Display the event's table
    echo "<table>";
    echo "<tr><th>Number</th><th>Participant's Name</th><th>Attendance</th><th>Action</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["numbers"] . "</td>";
        echo "<td>" . $row["participantsName"] . "</td>";
        echo "<td>" . $row["attendance"] . "</td>";
        echo "<td>";
        
        if ($row["attendance"] == 0) {
            echo "<button onclick=\"updateAttendance('$eventName', " . $row["numbers"] . ", 1)\">Add Attendance</button>";
        } else {
            echo "<button onclick=\"updateAttendance('$eventName', " . $row["numbers"] . ", 0)\">Delete Attendance</button>";
        }
        
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No data found for the event.";
}

// Close the database connection
$conn->close();

?>

<script>
function updateAttendance(eventName, numbers, attendance) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            // Reload the page to update the attendance
            location.reload();
        }
    };
    xhttp.open("GET", "http://localhost/utm/WP_MINI_PROJECT/php/updateAttendance.php?eventName=" + eventName + "&numbers=" + numbers + "&attendance=" + attendance, true);
    xhttp.send();
}
</script>
