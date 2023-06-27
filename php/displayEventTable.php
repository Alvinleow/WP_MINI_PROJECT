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
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta http-equiv='X-UA-Compatible' content='IE=edge'>";
    echo "<meta name='viewport' content='width=device-width initial-scale=1.0'>";
    echo "<title>WP Mini Project</title>";
    echo "<link rel='stylesheet' type='text/css' href='../css/Header.css'>";
    echo "<link rel='stylesheet' type='text/css' href='../css/Body-Container-2.css'>";
    echo "<link rel='stylesheet' type='text/css' href='../css/AboutUs.css'>";
    echo "<link rel='stylesheet' type='text/css' href='../css/table.css'>";
    echo "<style>
            .add {
                background-color: green;
                width: 128px;
                height: 37px;
            }

            .delete {
                background-color: red;
            }

            .add, .delete {
                color: white;
                font-weight: bold;
            }
          </style>";
    echo "</head>";

    echo "<body>";
    echo "<div class='body-container'>";
    echo "<div class='text'>";
    echo "<p class='title'>". $eventName ."</p>";
    echo "<a class='back' href='http://localhost/utm/WP_MINI_PROJECT/php/attendance.php'> < Back to Event List</a>";
    echo "</div>";
    echo "<table>";
    echo "<tr><th>Number</th><th>Participant's Name</th><th>Attendance</th><th>Action</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["numbers"] . "</td>";
        echo "<td>" . $row["participantsName"] . "</td>";
        echo "<td>" . $row["attendance"] . "</td>";
        echo "<td>";
        
        if ($row["attendance"] == 0) {
            echo "<button class='add' onclick=\"updateAttendance('$eventName', " . $row["numbers"] . ", 1)\">Add Attendance</button>";
        } else {
            echo "<button class='delete' onclick=\"updateAttendance('$eventName', " . $row["numbers"] . ", 0)\">Delete Attendance</button>";
        }
        
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
    echo "</body>";
} else {
    echo "<script>alert('No data found for the event.');</script>";
    echo "<script>window.location.href = 'http://localhost/utm/WP_MINI_PROJECT/php/attendance.php';</script>";
    exit;
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
