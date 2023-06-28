<?php
$servername = "localhost";
$username_db = "alvinleow";
$password_db = "alvinleow020816";
$dbname = "project_user_DB";

$conn = new mysqli($servername, $username_db, $password_db);

if (!$conn) {
    die('<br>Could not connect: ' . mysqli_connect_error());
}

echo "<br>Connected successfully.";

if (mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $dbname")) {
    echo "<br>Database created";
} else {
    echo "<br>Error creating database: " . mysqli_error($conn);
}

mysqli_close($conn);

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("<br>Connection failed: " . $conn->connect_error);
}

$sqlCreateUserTable = "CREATE TABLE IF NOT EXISTS User (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    userlevel INTEGER(1) NOT NULL,
    username VARCHAR(15) NOT NULL,
    passwords VARCHAR(200) NOT NULL,
    fullname VARCHAR(40) NOT NULL,
    identityNum VARCHAR(12) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(11) NOT NULL,
    addresses TEXT NOT NULL
)";

if ($conn->query($sqlCreateUserTable) === TRUE) {
    echo "<br>User table created successfully.";
} else {
    echo "<br>Error creating User table: " . $conn->error;
}

$sqlCheckData = "SELECT * FROM User LIMIT 1";
$result = $conn->query($sqlCheckData);

if ($result->num_rows === 0) {
    // Insert initial data into User table
    $userlevel = 2;
    $username = 'admin1';
    $passwordAdmin = 'Admin123';
    $fullname = 'admin1';
    $identity = 'admin1';
    $email = 'admin1';
    $phone = 'admin1';
    $address = 'admin1';

    $hashedPasswordAdmin = password_hash($passwordAdmin, PASSWORD_DEFAULT);

    $stmt1 = $conn->prepare("INSERT INTO User (userlevel, username, passwords, fullname, identityNum, email, phone, addresses) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt1->bind_param("isssssss", $userlevel, $username, $hashedPasswordAdmin, $fullname, $identity, $email, $phone, $address);

    // Execute statement
    if ($stmt1->execute()) {
        // Insertion successful
        echo "<br>Initial data inserted successfully.";
    } else {
        echo "<br>Error inserting data: " . $stmt1->error;
    }

    $stmt1->close();
}

$sqlCreateContactTable = "CREATE TABLE IF NOT EXISTS contact (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(40) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(11) NOT NULL,
    message TEXT NOT NULL
)";

if ($conn->query($sqlCreateContactTable) === TRUE) {
    echo "<br>Contact table created successfully.";
} else {
    echo "<br>Error creating Contact table: " . $conn->error;
}

$sqlCreateEventListTable = "CREATE TABLE IF NOT EXISTS `Event List` (
    eventId INT AUTO_INCREMENT PRIMARY KEY,
    eventName VARCHAR(255)
)";

if ($conn->query($sqlCreateEventListTable) === TRUE) {
    echo "<br>Event List table created successfully.";
} else {
    echo "<br>Error creating Event List table: " . $conn->error;
}

$conn->close();
?>
