<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  $username = $_POST["username"];
  $password = $_POST["password"];

  $servername = "localhost";
  $username_db = "alvinleow";
  $password_db = "alvinleow020816";
  $dbname = "project_user_DB";

  $conn = new mysqli($servername, $username_db, $password_db, $dbname);

  if ($conn->connect_error) 
  {
    die("Connection failed: " . $conn->connect_error);
  }

  $stmt = $conn->prepare("SELECT * FROM User WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) 
  {
    $row = $result->fetch_assoc();
    $storedPassword = $row["passwords"];
    $userlevel = $row["userlevel"];

    if (password_verify($password, $storedPassword)) {
      setcookie("username", $username, time() + 31536000, "/");
      
      echo '<script>alert("Login success!");';
      echo 'localStorage.setItem("isLoggedIn", "true");'; // Set the login status in localStorage
      echo 'localStorage.setItem("username", "'.$username.'");'; // Set the username in localStorage

      if ($userlevel === 2) {
        echo 'window.location.href = "../Admin/HomePageAdmin.html";'; // Redirect to admin homepage
      } else {
        echo 'window.location.href = "../User/HomePage.html";'; // Redirect to user homepage
      }

      echo '</script>';
      exit();
    } 
    else 
    {
      echo '<script>alert("Incorrect password. Please try again.");';
      echo 'window.location.href = "../User/loginpage.html";';
      echo '</script>';
      exit();
    }
  }
}
?>
