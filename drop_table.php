<?php
// drop_table.php

// Establish a database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cultural";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the 'table' parameter is set in the POST data
if (isset($_POST['table'])) {
    $tableName = $_POST['table'];

    // Drop the specified table
    $dropTableQuery = "DROP TABLE IF EXISTS `$tableName`";
    $dropTableResult = $conn->query($dropTableQuery);

    if ($dropTableResult !== FALSE) {


        echo '<div id="alert" class="alert alert-success alert-dismissible fade show" role="alert">
                Table '."$tableName".' dropped successfully.!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>';
        // Add a script to fade out the alert after 2 seconds
        echo '<script>
              setTimeout(function(){
                  var alert = document.getElementById("alert");
                  alert.style.transition = "opacity 1s";
                  alert.style.opacity = "0";
                  setTimeout(function(){
                      alert.style.display = "none";
                      // Redirect to adminpage.php
                     window.location.href = "adminpage.php";
                  }, 1000);
              }, 2000);
            </script>';
    } else {
        echo "<p>Error dropping table '$tableName': " . $conn->error . "</p>";
    }
} else {
    echo "<p>No table specified for dropping.</p>";
}

// Close the database connection
$conn->close();
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI/tZ1Zq75J8ANbYIk0J368aR5xC1bUh8kATeYeM=" crossorigin="anonymous"></script>
