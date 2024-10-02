<?php
$servername = "localhost";  // Usually "localhost" if running on local machine or server
$username = "root";         // Replace with your database username
$password = "admin";             // Replace with your database password
$dbname = "event_management";  // Replace with the name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

