<?php
session_start();

if (isset($_COOKIE['username'])) {
  $username = $_COOKIE['username'];
} else {
  echo "Cookie not found.";
  exit();
}

$servername = "localhost";
$username_db = "alvinleow";
$password_db = "alvinleow020816";
$dbname = "project_user_DB";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT * FROM User WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
  $row = $result->fetch_assoc();
  $fullname = $row["fullname"];
  $identityNum = $row["identityNum"];
  $email = $row["email"];
  $phone = $row["phone"];
  $address = $row["addresses"];
} else {
  echo "User not found.";
  exit();
}

$stmt->close();

// Fetch event list from the database
$sqlSelectEventList = "SELECT eventId, eventName FROM `event list`";
$eventListResult = $conn->query($sqlSelectEventList);

// Function to check attendance for the given event and user
function checkAttendance($conn, $eventName, $username)
{
  $stmt = $conn->prepare("SELECT attendance FROM `$eventName` WHERE participantsName = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $attendanceResult = $stmt->get_result();
  $stmt->close();

  if ($attendanceResult->num_rows === 1) {
    $attendanceRow = $attendanceResult->fetch_assoc();
    $attendance = $attendanceRow["attendance"];
    return ($attendance == 1) ? "Attend" : "Not Attend";
  }

  return "Not Participate";
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>User Profile</title>
  <link rel="stylesheet" type="text/css" href="../css/Header.css">
  <link rel="stylesheet" type="text/css" href="../css/table.css">
  <link rel='stylesheet' type='text/css' href="../css/Body-Container-2.css">
  <link rel='stylesheet' type='text/css' href="../css/userProfile.css">
  <link rel='stylesheet' type='text/css' href='../css/AboutUs.css'>
</head>

<body>
  <div class="body-container">
    <div class='text'>
      <p class='title'>User Profile</p>
      <a class='back' href='../User/HomePage.html'> < Back to Home Page</a>
    </div>
    <div class="profile-data">
      <label>Username:</label>
      <span><?php echo $username; ?></span>
    </div>
    <div class="profile-data">
      <label>Full Name:</label>
      <span><?php echo $fullname; ?></span>
    </div>
    <div class="profile-data">
      <label>Identity Number:</label>
      <span><?php echo $identityNum; ?></span>
    </div>
    <div class="profile-data">
      <label>Email:</label>
      <span><?php echo $email; ?></span>
    </div>
    <div class="profile-data">
      <label>Phone:</label>
      <span><?php echo $phone; ?></span>
    </div>
    <div class="profile-data">
      <label>Address:</label>
      <span><?php echo $address; ?></span>
    </div>

    <div class="update-button">
      <button class="update-information" onclick="location.href='update_profile.php'">Update Information</button>
    </div>

    <div class="activities-list ">
      <h3>Activities with Attendance:</h3>
      <?php if ($eventListResult->num_rows > 0) : ?>
        <table class="activity-table">
          <tr>
            <th class="eventId">Event ID</th>
            <th class="eventName">Event Name</th>
            <th class="attendance">Attendance</th>
          </tr>
          <?php while ($eventRow = $eventListResult->fetch_assoc()) : ?>
            <tr>
              <td><?php echo $eventRow["eventId"]; ?></td>
              <td><?php echo $eventRow["eventName"]; ?></td>
              <td><?php echo checkAttendance($conn, $eventRow["eventName"], $fullname); ?></td>
            </tr>
          <?php endwhile; ?>
        </table>
      <?php else : ?>
        <p>No activities with attendance found.</p>
      <?php endif; ?>
    </div>
    
  </div>
</body>

</html>
