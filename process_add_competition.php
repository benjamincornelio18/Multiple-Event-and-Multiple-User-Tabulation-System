<?php
// process_add_competition.php


session_start();

// Check if the user is not logged in, redirect to the l page
if (!isset($_SESSION['admin_username'])) {
    header("Location:index.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve competition name
    $competitionName = $_POST["competition_name"];

    $totalPercentage = $_POST["totalPercentage"];

    // Retrieve criteria
    $criteria = [];

    for ($i = 1; isset($_POST["criteria{$i}_name"]); $i++) {

      // name of the criteria
        $criterionName = $_POST["criteria{$i}_name"];
      //  echo $criterionName;
      // number to set
        $criterionPercentage = $_POST["criteria{$i}_percentage"];
      //  echo $criterionPercentage;
        // Do something with the criterion data, e.g., store in an array
        $criteria[] = [
            'name' => $criterionName,
            'percentage' => $criterionPercentage,
        ];
    }

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

    // Check if the competition name already exists
    // Check if the competition table already exists
      $tableExistsQuery = "SHOW TABLES LIKE '{$competitionName}'";
      $tableExistsResult = $conn->query($tableExistsQuery);

    if ($tableExistsResult->num_rows > 0) {
        // Competition name already exists, display an error message


      echo '<div id="alert" class="alert alert-danger alert-dismissible fade show" role="alert">
              Competition with the name ' . $competitionName . ' already exists. Please choose a different name.
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
                }, 500);
            }, 500);
          </script>';
    }

    else {
                                    // Create a table with the competition name
                                    // Create a table with the competition name
                                $sql = "CREATE TABLE IF NOT EXISTS `{$competitionName}` (
                                    `criterion_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,";
                                // Add individual criterion columns based on user input
                                $sql .= "`judgeUsername` VARCHAR(255), ";
                                $sql .= "`contestantNumber` INT NULL, ";


                                for ($i = 1; isset($_POST["criteria{$i}_name"]); $i++) {
                                  //  $tableName = str_replace(' ', '', $tableName1);
                                  //combine if there is spaces
                                    $criterionPercentage = $_POST["criteria{$i}_percentage"];
                                    $criterionName = str_replace(' ', '', $_POST["criteria{$i}_name"]);
                                    // Combine criterionName and criterionPercentage
                                    $combinedName = "{$criterionName}_{$criterionPercentage}";

                                    //$sql .= "`{$criterionName}` INT NULL, ";
                                    // Adjust the range (1 to 20) as needed
                                           $sql .= "`{$combinedName }` INT CHECK (`{$combinedName }` >= 1 AND `{$combinedName}` <= '$criterionPercentage'), ";

                                        }

                                        // Add the common criterion_percentage column

                                        $sql .= "`criterion_percentage` INT DEFAULT 0 NULL,";
                                        $sql .= "`ranking` INT DEFAULT 0 NULL
                                        )";

                                       //$tableName = $competitionName; // replace with your actual table name
                                      // $query = "SELECT * FROM $competitionName";
                                    //   $result = $conn->query($query);

                                      // $insertJudgeQuery = "INSERT INTO $competitionName (?) VALUES ('20', '20', '20', '20)";
                                      //if ($result->num_rows > 0) {

                                // Execute the SQL query
                                if ($conn->query($sql) === TRUE) {

                                    echo '<div id="alert" class="alert alert-success alert-dismissible fade show" role="alert">
                                            Competition added successfully!
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
                                              }, 500);
                                          }, 500);
                                        </script>';
                                } else {

                                    echo '<div id="alert" class="alert alert-danger alert-dismissible fade show" role="alert">
                                            Error creating table:!
                                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>'. $conn->error;

                                    echo "Query: " . $sql;
                                }
                            }


          }
?>


<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI/tZ1Zq75J8ANbYIk0J368aR5xC1bUh8kATeYeM=" crossorigin="anonymous"></script>
