<?php
    $servername = "localhost";
    $username_db = "alvinleow";
    $password_db = "alvinleow020816";
    $dbname = "project_user_DB";

    $conn = new mysqli($servername, $username_db, $password_db);
    
    if(!$conn)
    {
        die('Could no connect: '.mysqli_connect_error());
    }

    echo "Connected successful.";
    
    if(mysqli_query($conn, "CREATE DATABASE project_user_DB"))
    {
        echo "Database created";
    }

    else 
    {
        echo "Error creating database: " .mysqli_connect_error();
    }

    mysqli_close($conn);
?>