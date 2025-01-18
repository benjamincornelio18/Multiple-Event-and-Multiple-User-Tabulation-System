<?php
// add_data.php

session_start();

// Check if the user is not logged in, redirect to the l page
if (!isset($_SESSION['admin_username'])) {
    header("Location:index.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Create a new table in the database based on the competition name
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cultural";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the 'table' parameter is set in the URL
// Check if the 'table' parameter is set in the URL
if (isset($_POST['table'])) {
    $tableName = $_POST['tableName'];

    // Fetch details for the selected table
    $fetchTableDetailsQuery = "DESCRIBE `$tableName`";
    $tableDetailsResult = $conn->query($fetchTableDetailsQuery);

    if ($tableDetailsResult !== FALSE) {
        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Reset result set pointer to the beginning
            $tableDetailsResult->data_seek(0);

            // Initialize an array to store values for duplicate checking
            $uniqueValues = array();

            // Loop through the columns and process the form data
            while ($row = $tableDetailsResult->fetch_assoc()) {
                $columnName = $row['Field'];

                // Skip the primary key column
                if ($row['Key'] === 'PRI') {
                    continue;
                }

                // Check if the column is the username
                if ($columnName === 'username') {
                    // Check if the username already exists in the database
                    $usernameValue = $conn->real_escape_string($_POST[$columnName]);

                    // Check if the username already exists
                    $checkUsernameQuery = "SELECT * FROM $tableName WHERE username = '$usernameValue'";
                    $result = $conn->query($checkUsernameQuery);

                    if ($result->num_rows > 0) {
                        echo '<div id="alert" class="alert alert-danger alert-dismissible fade show" role="alert">
                                Error adding judge: Username already exists.
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

                        // You might want to exit here or handle the error as needed
                        exit();
                    }

                    else {
                        // Continue with the rest of your code
                        $uniqueValues[$columnName] = $usernameValue;
                    }
                } else {
                    // Process other columns
                    // ...

                    $uniqueValues[$columnName] = $conn->real_escape_string($_POST[$columnName]);

                }
            }

            // Process the form data and insert into the table
            $insertQuery = "INSERT INTO `$tableName` (";
            $values = '';

            foreach ($uniqueValues as $column => $value) {
                $insertQuery .= "`$column`, ";
                $values .= "'$value', ";
            }

            // Remove the trailing commas
            $insertQuery = rtrim($insertQuery, ', ') . ") VALUES (" . rtrim($values, ', ') . ")";

            if ($conn->query($insertQuery) === TRUE) {
              echo '<div id="alert" class="alert alert-success alert-dismissible fade show" role="alert">
                      New record added successfully.
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
                echo "Error adding data: " . $conn->error;
            }
        }
    }
}
}
// Close the database connection
$conn->close();
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI/tZ1Zq75J8ANbYIk0J368aR5xC1bUh8kATeYeM=" crossorigin="anonymous"></script>
