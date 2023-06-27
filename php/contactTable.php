<?php
// Create a new connection to the database
$servername = "localhost";
$username_db = "alvinleow";
$password_db = "alvinleow020816";
$dbname = "project_user_DB";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the form data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$message = $_POST['message'];

// Create the "contact" table if it doesn't exist
$sqlCreateTable = "CREATE TABLE IF NOT EXISTS contact (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    message TEXT NOT NULL
)";

if ($conn->query($sqlCreateTable) === TRUE) {
    // Table created successfully or already exists

    // Insert the form data into the "contact" table
    $sqlInsertData = "INSERT INTO contact (name, email, phone, message) 
                      VALUES ('$name', '$email', '$phone', '$message')";

    if ($conn->query($sqlInsertData) === TRUE) {
        echo "<script>alert('Submit Successfully.');</script>";
        echo "<script>window.location.href = 'http://localhost/utm/WP_MINI_PROJECT/User/Contact.html';</script>";
        exit; // Terminate the script after redirection
    } else {
        echo "Error inserting data: " . $conn->error;
    }
} else {
    echo "Error creating table: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
