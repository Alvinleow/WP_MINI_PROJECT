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
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
      margin: 0;
      padding: 0;
    }

    .container {
      width: 600px;
      margin: 100px auto;
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
    }

    .profile-data {
      margin-bottom: 15px;
    }

    .profile-data label {
      font-weight: bold;
    }

    .profile-data span {
      margin-left: 10px;
    }

    .activity-table {
      margin-top: 20px;
      width: 100%;
      border-collapse: collapse;
    }

    .activity-table th,
    .activity-table td {
      padding: 8px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>User Profile</h2>
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
    <div class="activities-list">
      <h3>Activities with Attendance:</h3>
      <?php if ($eventListResult->num_rows > 0) : ?>
        <table class="activity-table">
          <tr>
            <th>Event ID</th>
            <th>Event Name</th>
            <th>Attendance</th>
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