<?php
// table_details.php
// Establish a database connection

session_start();

// Check if the user is not logged in, redirect to the l page
if (!isset($_SESSION['admin_username'])) {
    header("Location:index.php");
    exit();
}


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cultural";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the 'table' parameter is set in the URL
if (isset($_GET['table'])) {
    $tableName = $_GET['table'];

    // Fetch details for the selected table
    $fetchTableDetailsQuery = "DESCRIBE `$tableName`";
    $tableDetailsResult = $conn->query($fetchTableDetailsQuery);

    if ($tableDetailsResult !== FALSE) {
      echo '<div class="container mt-5">';

      echo '<h2>Welcome, User:'.$_SESSION['admin_username']." ".$_SESSION['admin_firstname']." ".$_SESSION['admin_lastname'].'</h2>';
      echo "<a href='adminpage.php' class='btn btn-primary'>Go Back</a>";
      //echo "<button class='btn btn-success' data-toggle='modal' data-target='#addDataModal'>Add Data</button>";

      // Modal for adding data
      echo "<div class='modal' id='addDataModal' tabindex='-1' role='dialog' aria-labelledby='addDataModalLabel' aria-hidden='true'>
            <div class='modal-dialog' role='document'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='addDataModalLabel'>Add Data to $tableName</h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    <div class='modal-body'>
                        <!-- Form for adding data -->
                        <form action='add_criteria.php' method='post'>

                        <!-- Form for adding data -->
                        <!--  <label for=' $tableName'>$tableName:</label> -->

                        <input type='text' hidden class='form-control' name='tableName' id='tableName' value='$tableName' required>
                            <!-- Include input fields for each column --> ";
                            $tableDetailsResult->data_seek(0); // Reset result set pointer
                            while ($row = $tableDetailsResult->fetch_assoc())
                            {
                                $columnName = $row['Field'];
                                $dataType = $row['Type'];
                                $constraints = $row['Extra'];
                                // Skip the primary key column
                                  if ($row['Key'] === 'PRI') {
                                      continue;
                                  }

                                  // skip the criterion percentage because it will calculate the total score
                                  if ($columnName === 'criterion_percentage') {
                                      continue;
                                  }

                                  if ($columnName === 'judgeUsername') {
                                    echo "<label for='$columnName'>$columnName:</label>";
                                      echo "<input type='text' class='form-control' name='$columnName' id='$columnName' required>";
                                      continue;
                                  }
                                    $inputType = 'text';
                                  if ($columnName === 'contestantNumber') {
                                    echo "<label for='$columnName'>$columnName:</label>";
                                      echo "<input type='$inputType' class='form-control' name='$columnName' id='$columnName' min='50' max='10' required>";

                                      continue;
                                  }
                                  // Determine the input type based on the data type of the column

                                if (strpos($dataType, 'int') !== false) {
                                        $inputType = 'number';
                                        // Extract numeric constraints (assuming constraints are in the format "CHECK (`columnName` >= min AND `columnName` <= max)")
                                        preg_match('/CHECK \(`' . $columnName . '` >= (\d+) AND `' . $columnName . '` <= (\d+)\)/', $constraints, $matches);

                                        if ($matches) {
                                                 $minValue = $matches[1];
                                                 $maxValue = $matches[2];
                                             }

                                    echo "<div class='form-group'>
                                            <label for='$columnName'>$columnName:</label>";
                                            // Use preg_match to extract numbers in the column name
                                            if (preg_match('/\d+/', $columnName, $matches)) {
                                                $numberOnly = $matches[0];
                                                // Determine the range based on the extracted number
                                                  $minValue = 0;  // Set your desired minimum value
                                                  $maxValue = $numberOnly;
                                            }
                                            echo "<input type='$inputType' class='form-control' name='$columnName' id='$columnName' min='$minValue' max='$maxValue' required>
                                          </div>";
                                        }
                            }
                            //this is the column of the average of the total score that it should limit from 50 to 100

                            echo "<label for='$columnName'>$columnName:</label>";
                              echo "<input type='$inputType' class='form-control' name='$columnName' id='$columnName' min='50' max='100' required>";
                          echo " <button type='submit' class='btn btn-success' name='table'>Add Data</button>
                        </form>
                    </div>
                </div>
            </div>
          </div>";



          // Query to fetch judge usernames
$sql = "SELECT username FROM judges";
$result = $conn->query($sql);

// Check if there are any rows in the result
if ($result->num_rows > 0) {
    echo "<button class='btn btn-primary' data-toggle='modal' data-target='#addJudgeModal'>Add Judge and Contestants</button>";


    echo "<a id='exportButton' href='generate_pdf.php?table={$tableName}' class='btn btn-primary'>EXPORT RESULT</a>";
      echo '
     <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#emailModal">
         Send CertRecog
     </button>';

     echo '
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#emailModal2">
  Send CertApp
  </button>';


    // Modal for adding judge
    echo "<div class='modal' id='addJudgeModal' tabindex='-1' role='dialog' aria-labelledby='addJudgeModalLabel' aria-hidden='true'>
            <div class='modal-dialog' role='document'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='addJudgeModalLabel'>Add Judge Information</h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    <div class='modal-body'>
                        <!-- Form for adding judge information -->
                        <form action='add_judgeparticipants.php' method='post'>
                            <div class='form-group'>
                                <label for='judgeUsername'>Judge Username:</label>
                                <select class='form-control' name='judgeUsername' id='judgeUsername' required>";

                              // Loop through the result and add options to the dropdown
                              while ($row = $result->fetch_assoc()) {
                                  $username = $row['username'];
                                  echo "<option value='$username'>$username</option>";
                              }

                              echo "</select>
                            </div>
                            <div class='form-group'>
                                <label for='numberOfParticipants'>Number of Participants:</label>
                                <input type='number' class='form-control' name='numberOfParticipants' id='numberOfParticipants' min='0' required>
                                <label for='numberOfParticipants'>Table name:$tableName</label>
                                <input type='text' class='form-control' name='tableName' id='tableName' value='$tableName' hidden required>
                            </div>
                            <button type='submit' class='btn btn-success' name='addJudge'>Add Judge and Participants</button>
                        </form>
                    </div>
                </div>
            </div>
          </div>";
} else {
    echo "No judges found in the database.";
}
        echo "<h2>Category: $tableName</h2>";
        echo "<div class='container'>";
        echo "<div class='row'>";
        // Table 1 with col-8
        echo "<div class='col'>";


//THIS IS FOR THERANKING



echo "<h5>SUMMARY RESULTS: $tableName </h5>";
echo "<div class='table-responsive'>";
echo "<table class='table table-bordered'>
        <thead>
            <tr>";

            // check if indi admin
    if ($tableName!='admin')
            {
              $distinctJudgesQuery = "SELECT DISTINCT judgeUsername FROM `$tableName`";
              $distinctJudgesResult = $conn->query($distinctJudgesQuery);

              echo "<th>Contestant Number</th>"; // First column header


              $judge=1;
              // Display distinct judgeUsernames in the first row
              while ($judgeRow = $distinctJudgesResult->fetch_assoc()) {

              echo "<th><h6>(JUDGE:$judge RANKING) <br> {$judgeRow['judgeUsername']} </h6></th>";
              $judge++;
              }

              echo "<th>Total Score</th>"; // Add a column for total ranking
              //echo "<th>Rank</th>"; // Add a column for rank
              echo "</tr>
                      </thead>
                      <tbody>";

              // Fetch distinct contestantNumbers
              $distinctContestantsQuery = "SELECT DISTINCT contestantNumber FROM `$tableName`";
              $distinctContestantsResult = $conn->query($distinctContestantsQuery);

              // Array to store total rankings for each contestant
              $totalRankingsArray = [];



              // Display data for each contestantNumber and judgeUsername combination
              while ($contestantRow = $distinctContestantsResult->fetch_assoc()) {
                  echo "<tr>
                          <td>{$contestantRow['contestantNumber']}</td>"; // First column with contestantNumber

                  // Fetch and display ranking data for each judgeUsername
                  $distinctJudgesResult->data_seek(0); // Reset result set pointer
                  while ($judgeRow = $distinctJudgesResult->fetch_assoc()) {
                      $fetchRankingQuery = "SELECT ranking FROM `$tableName` WHERE contestantNumber = {$contestantRow['contestantNumber']} AND judgeUsername = '{$judgeRow['judgeUsername']}'";
                      $rankingResult = $conn->query($fetchRankingQuery);

                      $rankingData = $rankingResult->fetch_assoc();
                      $rankingValue = $rankingData ? $rankingData['ranking'] : ''; // Get ranking value or empty if not available

                      echo "<td>{$rankingValue}</td>";

                      // Add ranking value to the totalRankings array
                      $totalRankingsArray[$contestantRow['contestantNumber']][] = $rankingValue;
                  }

                  // Calculate and display total ranking for each contestant
                  $totalRankingQuery = "SELECT SUM(ranking) AS totalRanking FROM `$tableName` WHERE contestantNumber = {$contestantRow['contestantNumber']}";
                  $totalRankingResult = $conn->query($totalRankingQuery);
                  $totalRankingData = $totalRankingResult->fetch_assoc();
                  $totalRankingValue = $totalRankingData ? $totalRankingData['totalRanking'] : ''; // Get total ranking value or empty if not available

                  echo "<td>{$totalRankingValue}</td>";
                  //echo $totalRankingValue;

                  // Assign rank based on the sorted order of total rankings
                  //echo $contestantRow['contestantNumber'];

                  //$specificKey = 'some_key';

                  //echo "<td>$myArray[$specificKey]</td>";
                  echo "</tr>";

              }

              // Loop through the array and print each value
            //  foreach ($myArray as $key => $value) {
            //      echo "<tr>";
            //      echo "<td>$value</td>";
            //      echo "<tr>";
            //  }
  // ... (your existing code for the first table)

  echo "</tbody></table>";
  echo "</div>";
  echo "</div>"; // Close col-8 container

        // Table 2 with col-4
        echo "<div class='col'>";
        echo "<h5>WINNNERS</h5>";

                              $totalRankingValues = [];
                                // Fetch distinct judgeUsernames
                                $distinctJudgesQuery = "SELECT DISTINCT judgeUsername FROM `$tableName`";
                                $distinctJudgesResult = $conn->query($distinctJudgesQuery);
                              //  echo "<th>Contestant Number</th>"; // First column header
                              //  echo "<th colspan='2'>Final Ranking</th>"; // Final ranking column header
                              //  echo "</tr>
                              //      </thead>
                              //      <tbody>";
                                // Fetch distinct contestantNumbers
                                $distinctContestantsQuery = "SELECT DISTINCT contestantNumber FROM `$tableName`";
                                $distinctContestantsResult = $conn->query($distinctContestantsQuery);
                                // Display data for each contestantNumber
                                while ($contestantRow = $distinctContestantsResult->fetch_assoc()) {

                                            //<td>{$contestantRow['contestantNumber']} // First column with contestantNumber
                                    // Fetch and display final ranking for each contestant
                                    $totalRankingQuery = "SELECT SUM(ranking) AS totalRanking FROM `$tableName` WHERE contestantNumber = {$contestantRow['contestantNumber']}";
                                    $totalRankingResult = $conn->query($totalRankingQuery);
                                    $totalRankingData = $totalRankingResult->fetch_assoc();
                                    $totalRankingValue = $totalRankingData ? $totalRankingData['totalRanking'] : ''; // Get total ranking value or empty if not available
                                  //  echo "<td>$totalRankingValue</td>";
                                     $totalRankingValues[] = $totalRankingValue;
                                     //echo "<td>
                                  //   <select name='rankingSelect'>";
                                    // echo "<option value='1ST'>1ST</option>";
                                    // echo "<option value='2ND'>2ND</option>";
                                  //   echo "<option value='3RD'>3RD</option>";
                                  //   echo "<option value='4TH'>4TH'</option>";
                                  //   echo "<option value='5TH'>5TH</option>";
                                  //   echo "</select>
                                  //    </td>
                                  //   ";

                                }

                                $ContestantNum=1;

                                $ordered_values = $totalRankingValues;
                                sort($ordered_values);
                                echo "<div class='table-responsive'>";
                                echo "<table class='table table-bordered'>
                                        <thead>
                                        <tr>
                                        <td> Contestant No. </td>
                                        <td> Final Ranking </td>
                                        </tr>";
                                foreach ($totalRankingValues as $value) {
                                echo "<tr>";
                                    $rank = array_search($value, $ordered_values) + 1;
                                    echo "<td> $ContestantNum <br/> </td>"; // CONTESTANT NUM
                                    echo "<td> Total Score: $value : <b> Rank $rank </b>  <br/> </td>";

                                    echo  "</tr>";
                                    $ContestantNum++;
                                }




                                            echo '<form method="post" action="send_email2.php" enctype="multipart/form-data">
                                               <div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel" aria-hidden="true">
                                                   <div class="modal-dialog" role="document">
                                                       <div class="modal-content">
                                                           <div class="modal-header">
                                                               <h5 class="modal-title" id="emailModalLabel">Select Email Addresses</h5>
                                                               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                   <span aria-hidden="true">&times;</span>
                                                               </button>
                                                           </div>
                                                           <div class="modal-body">';

                                                           // Fetch emails from the judges table
                                                          //$fetchEmailsQuery = "SELECT email, firstname, lastname FROM judges";

                                                          $fetchEmailsQuery ="SELECT DISTINCT firstname,lastname,username,email FROM `judges` INNER JOIN `$tableName` ON `judges`.username = `$tableName`.judgeUsername";


                                                          $result = $conn->query($fetchEmailsQuery);
                                                          if ($result->num_rows > 0) {
                                                            // Display checkboxes for each email
                                                          while ($row = $result->fetch_assoc()) {
                                                              $email = $row['email'];
                                                              $firstname = $row['firstname'];
                                                              $lastname= $row['lastname'];

                                                              echo '<div class="form-check">';
                                                              echo '<input class="form-check-input" type="checkbox" name="selected_emails[]" value="'. $email .' "  >';
                                                              echo '<label class="form-check-label">' . $email . '    ('. $firstname .' '. $lastname .')</label>';
                                                              echo "<input type='text' class='form-control' name='tableName' id='tableName' value='Input the complete Name of the Competition or Event'required>";

                                                              echo '</div>';
                                                          }
                                                        }
                                                                      else {
                                                            echo 'No emails found in the database.';
                                                        }

                                                          echo '</div>
                                                           <div class="modal-footer">
                                                               <input type="file" name="attachment" id="attachment" class="form-control-file">
                                                               <button type="submit" class="btn btn-primary">Send Email</button>
                                                               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                           </div>
                                                       </div>
                                                   </div>
                                               </div>
                                            </form>';

                                                    echo "</tbody></table>";
                                                    echo "</div>";
                                                  echo "</div>"; // Close col-4 container




                                                  echo '<form method="post" action="send_email3.php" enctype="multipart/form-data">
                                                     <div class="modal fade" id="emailModal2" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel" aria-hidden="true">
                                                         <div class="modal-dialog" role="document">
                                                             <div class="modal-content">
                                                                 <div class="modal-header">
                                                                     <h5 class="modal-title" id="emailModalLabel">Select Email Addresses</h5>
                                                                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                         <span aria-hidden="true">&times;</span>
                                                                     </button>
                                                                 </div>
                                                                 <div class="modal-body">';

                                                                 // Fetch emails from the judges table
                                                                //$fetchEmailsQuery = "SELECT email, firstname, lastname FROM judges";

                                                                $fetchEmailsQuery ="SELECT DISTINCT firstname,lastname,username,email FROM `judges` INNER JOIN `$tableName` ON `judges`.username = `$tableName`.judgeUsername";


                                                                $result = $conn->query($fetchEmailsQuery);
                                                                if ($result->num_rows > 0) {
                                                                  // Display checkboxes for each email
                                                                while ($row = $result->fetch_assoc()) {
                                                                    $email = $row['email'];
                                                                    $firstname = $row['firstname'];
                                                                    $lastname= $row['lastname'];

                                                                    echo '<div class="form-check">';
                                                                    echo '<input class="form-check-input" type="checkbox" name="selected_emails[]" value="'. $email .' " >';
                                                                    echo '<label class="form-check-label">' . $email . '    ('. $firstname .' '. $lastname .')</label>';
                                                                    echo "<input type='text' class='form-control' name='tableName' id='tableName' value='Input the complete Name of the Competition or Event'required>";

                                                                    echo '</div>';
                                                                }
                                                              }
                                                                            else {
                                                                  echo 'No emails found in the database.';
                                                              }

                                                                echo '</div>
                                                                 <div class="modal-footer">
                                                                     <input type="file" name="attachment" id="attachment" class="form-control-file">
                                                                     <button type="submit" class="btn btn-primary">Send Email</button>
                                                                     <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                 </div>
                                                             </div>
                                                         </div>
                                                     </div>
                                                  </form>';

                                                          echo "</tbody></table>";
                                                          echo "</div>";
                                                        echo "</div>"; // Close col-4 container


                                                        //INDIVIDUAL JUDGES RESULTS
                                                        echo "<div class='col'>";
                                                        echo "<div class='table-responsive'>"; // Add responsive class
                                                        echo "<table class='table table-bordered'>
                                                                <thead>
                                                                    <tr>";
                                                        // Dynamically generate table headers based on columns
                                                        $tableDetailsResult->data_seek(0); // Reset result set pointer
                                                        while ($row = $tableDetailsResult->fetch_assoc()) {
                                                            echo "<th>{$row['Field']}</th>";

                                                        }
                                                        echo "</tr>
                                                                </thead>
                                                                <tbody>";

                                                        // Fetch and display data for each row
                                                        $fetchTableDataQuery = "SELECT * FROM `$tableName`";
                                                        $tableDataResult = $conn->query($fetchTableDataQuery);

                                                        while ($rowData = $tableDataResult->fetch_assoc()) {
                                                            echo "<tr>";
                                                            foreach ($rowData as $value) {
                                                                echo "<td>$value</td>";
                                                            }
                                                            echo "</tr>";
                                                        }

                                                        echo "</tbody></table>";
                                                        echo "</div>"; // Close the table-responsive div
                                                        echo "</div>"; // Close the table-responsive div


                                                        }
                                                        elseif($tableName=='admin')
                                                        {
                                                       echo "<p>NO RECORD FOR ADMIN ACCOUNTS </p>";
                                                       }

                                                         else
                                                         {
                                                        echo "<p>Error fetching details for table '$tableName': " . $conn->error . "</p>";
                                                        }

                                                        }
                                                        else
                                                        {
                                                        echo "<p>No table specified.</p>";
                                                        }
                                                          echo "</div>";


                                            echo "</div>"; // Close row
                                            echo "</div>"; // Close container
            }

            else{

                  echo "<th><h6>THIS IS THE ADMIN PAGE NOTHING TO ADD </h6></th>";


            }

 // Close the table-responsive div // Close the table-responsive div FIR RANKING

// Close the database connection
$conn->close();
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI/tZ1Zq75J8ANbYIk0J368aR5xC1bUh8kATeYeM=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
