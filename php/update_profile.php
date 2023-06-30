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

// Initialize variables with empty values
$phone = $address = $email = '';

// Fetch user information from the database
$stmt = $conn->prepare("SELECT phone, addresses, email FROM User WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
  $row = $result->fetch_assoc();
  $phone = $row["phone"];
  $address = $row["addresses"];
  $email = $row["email"];
}

$stmt->close();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Retrieve the updated information from the form
  $phone = $_POST["phone"];
  $address = $_POST["address"];
  $email = $_POST["email"];

  // Prepare and execute the update statement
  $stmt = $conn->prepare("UPDATE User SET phone = ?, addresses = ?, email = ? WHERE username = ?");
  $stmt->bind_param("ssss", $phone, $address, $email, $username);
  $stmt->execute();

  // Check if the update was successful
  if ($stmt->affected_rows > 0) {
    echo "<script>alert('Information updated successfully.');</script>";
    echo "<script>window.location.href = 'profile.php';</script>";
    exit();
  } else {
    echo "<script>alert('Failed to update information.');</script>";
  }

  $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Update Profile</title>
  <link rel="stylesheet" type="text/css" href="../css/Header.css">
  <link rel="stylesheet" type="text/css" href="../css/table.css">
  <link rel='stylesheet' type='text/css' href="../css/Body-Container-2.css">
  <link rel='stylesheet' type='text/css' href="../css/userProfile.css">
  <link rel='stylesheet' type='text/css' href='../css/AboutUs.css'>
</head>

<body>
<div class="body-container form">
  <div class="update-form">
    <h3 class="form-title">Update Information</h3>
    <form action="update_profile.php" method="POST">
      <div class="form-field">
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>">
      </div>
      <div class="form-field">
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?php echo $address; ?>">
      </div>
      <div class="form-field">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>">
      </div>
      <div class="form-field centered">
        <button class="update-information" type="submit">Update</button>
      </div>
    </form>
  </div>
</div>
</body>

</html>
