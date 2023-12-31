<?php
$servername = "localhost";
$username_db = "alvinleow";
$password_db = "alvinleow020816";
$dbname = "project_user_DB";

// Create connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE TABLE IF NOT EXISTS User (
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

if ($conn->query($sql) === TRUE) 
{

    // Table created successfully or already exists

            // Check if initial data exists
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
                if ($stmt1-> execute()) {
                    // // Insertion successful
                    // echo "Initial data inserted successfully.";
                } else {
                    echo "Error inserting data: " . $stmt1->error;
                }

                $stmt1->close();
            } else {
            }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userlevel = 1;
        $username = $_POST['username'];
        $password = $_POST['password'];
        $fullname = $_POST['Name'];
        $identity = $_POST['Identity'];
        $email = $_POST['Email'];
        $phone = $_POST['Phone'];
        $address = $_POST['Address'];

        $checkQuery = "SELECT * FROM User WHERE username = '$username'";
        $checkResult = $conn->query($checkQuery);
        
        if ($checkResult->num_rows > 0) 
        {
            echo '<script>
                    alert("Username already exists. Please choose a different username.");
                    window.history.back();
                  </script>';
            exit();
        }

        else 
        {  
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO User (userlevel, username, passwords, fullname, identityNum, email, phone, addresses) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssssss", $userlevel, $username, $hashedPassword, $fullname, $identity, $email, $phone, $address);


            // Execute statement
            if ($stmt->execute()) 
            {
                echo
                '<!DOCTYPE html>
                <html>
                <head>
                    <title>Registration Successful</title>
                    <style>
                        body 
                        {
                            font-family: Arial, sans-serif;
                            background-color: #f2f2f2;
                            margin: 0;
                            padding: 0;
                        }
                        
                        .container 
                        {
                            width: 400px;
                            margin: 100px auto;
                            background-color: #fff;
                            padding: 20px;
                            border-radius: 5px;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        }
                        
                        .success-message 
                        {
                            text-align: center;
                            font-size: 20px;
                            color: #4caf50;
                            margin-bottom: 20px;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <p class="success-message">Registration successful, redirecting to login page...</p>
                        <script>
                            setTimeout(function() 
                            {
                                window.location.href = "../User/loginpage.html";
                            }, 5000);
                        </script>
                    </div>
                </body>
                </html>';
                exit();
            } 
            
            else 
            {
                echo "Error registering user: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}

$conn->close();
?>
