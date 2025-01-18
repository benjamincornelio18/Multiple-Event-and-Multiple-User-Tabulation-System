<?php
// Assuming you have a MySQL database named 'cultural2023'
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cultural2023";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get username and password from the POST request
$username = $_POST['username'];
$password = $_POST['password'];

// Perform validation against the 'admin2023' table
$sql = "SELECT * FROM admin2023 WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Authentication successful
    echo "Login successful";
} else {
    // Authentication failed
    echo "Login failed";
}

$conn->close();
?>
