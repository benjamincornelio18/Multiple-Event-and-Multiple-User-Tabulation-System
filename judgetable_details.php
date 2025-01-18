<?php
// table_details.php
// Establish a database connection

session_start();

// Check if the user is not logged in, redirect to the l page
if (!isset($_SESSION['judge_username'])) {
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

      echo '<h2>Welcome, Judge:'.$_SESSION['judge_firstname']." ".$_SESSION['judge_lastname'].'</h2>';
      echo "<a href='judgespage.php' class='btn btn-primary'>Go Back</a>";
    //  echo "<button class='btn btn-success' data-toggle='modal' data-target='#addDataModal'>Add Data</button>";

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

          //echo "<button class='btn btn-primary' data-toggle='modal' data-target='#addJudgeModal'>Add Judge and Contestants</button>";

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
                              <form action='add_judge.php' method='post'>
                                  <div class='form-group'>
                                      <label for='judgeUsername'>Judge Username:</label>
                                      <input type='text' class='form-control' name='judgeUsername' id='judgeUsername' required>
                                  </div>
                                  <div class='form-group'>
                                      <label for='numberOfParticipants'>Number of Participants:</label>
                                      <input type='number' class='form-control' name='numberOfParticipants' id='numberOfParticipants' min='0' required>
                                  </div>
                                  <button type='submit' class='btn btn-success' name='addJudge'>Add Judge</button>
                              </form>
                          </div>
                      </div>
                  </div>
                </div>";

        echo "<h2>Category: $tableName</h2>";
        echo "<div class='table-responsive'>"; // Add responsive class
        echo "<table class='table table-bordered'>
                <thead>
                    <tr>";
        // Dynamically generate table headers based on columns
        $tableDetailsResult->data_seek(0); // Reset result set pointers
        echo "<th>MANAGE</th>";
                  while ($row = $tableDetailsResult->fetch_assoc())
                  {
                    $columnName = $row['Field'];
                    $dataType = $row['Type'];
                    $constraints = $row['Extra'];
                    $columnNames[] = $columnName; // Store column names in the array

                          if ($row['Field'] === 'judgeUsername') {
                              continue;
                          }

                          echo "<th>{$row['Field']}</th>";
                          }
        echo "</tr></thead><tbody>";

        // Define the columns to exclude
        $excludeColumns = ['judgeUsername'];
        // Fetch and display data for each row
        //$fetchTableDataQuery = "SELECT * FROM `$tableName`";
        $judgeUserName=$_SESSION['judge_username'];
        $fetchTableDataQuery = "SELECT " . implode(',', array_diff($columnNames, $excludeColumns)) . " FROM `$tableName` WHERE judgeUsername='$judgeUserName'";
        $tableDataResult = $conn->query($fetchTableDataQuery);

        while ($rowData = $tableDataResult->fetch_assoc()) {
            echo "<tr>";
          echo  " <td>


             <button type='button' class='btn btn-success' data-toggle='modal' data-target='#rateContestants{$rowData['criterion_id']}'>
              RATE
           </button>

           <!-- Edit Judge Modal -->
           <div class='modal' id='rateContestants{$rowData['criterion_id']}' tabindex='-1' role='dialog' aria-labelledby='editJudgeModalLabel' aria-hidden='true'>
               <div class='modal-dialog' role='document'>
                   <div class='modal-content'>
                       <div class='modal-header'>
                           <h5 class='modal-title' id='editJudgeModalLabel'>CONTESTANT NO.{$rowData['contestantNumber']}</h5>
                           <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                               <span aria-hidden='true'>&times;</span>
                           </button>
                       </div>
                       <div class='modal-body'>
                           <!-- Form for Edit -->
                           <form action='rate_contestant.php' method='post'>

                               <div class='form-group'>
                                   <!-- <label for='editUsername'>Judge:   $judgeUserName</label>  -->
                                   <input type='text' hidden class='form-control' id='edit_username' name='edit_username' value='$judgeUserName' required>
                               </div>
                               <input type='text' hidden name='id' id='id' value='{$rowData['criterion_id']}'>

                               <div class='form-group'>
                                   <!-- <label for='editUsername'>Contestant No: {$rowData['contestantNumber']}</label> -->
                                   <input type='text' hidden class='form-control' id='edit_contestantNumber' name='edit_contestantNumber' value='{$rowData['contestantNumber']}' required>
                               </div>

                               <input type='text' hidden class='form-control' name='tableName' id='tableName' value='$tableName' required>
                                   <!-- Include input fields for each column --> ";

                                   ?>
                                   <?php
       // Fetch the existing data from the database (modify this query based on your database structure)
       $existingDataQuery = "SELECT * FROM `$tableName` WHERE criterion_id = {$rowData['criterion_id']}";
       $existingDataResult = $conn->query($existingDataQuery);

       if ($existingDataResult && $existingDataRow = $existingDataResult->fetch_assoc()) {
           // Loop through the columns and set default values for corresponding input fields
           $tableDetailsResult->data_seek(0); // Reset result set pointer
           while ($row = $tableDetailsResult->fetch_assoc()) {
               $columnName = $row['Field'];

               // Skip certain columns as needed (you can adjust these conditions based on your requirements)
               if ($columnName === 'judgeUsername' || $columnName === 'contestantNumber' || $columnName === 'criterion_percentage' || $columnName === 'ranking' || $columnName === 'criterion_id') {
                   continue;
               }
               // Set the default value for the input field based on the existing data from the database
               $defaultValue = $existingDataRow[$columnName];

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
                                    <h5> <label for='$columnName'>$columnName:</label> </h5>";
                                    // Use preg_match to extract numbers in the column name
                                    if (preg_match('/\d+/', $columnName, $matches)) {
                                        $numberOnly = $matches[0];
                                        // Determine the range based on the extracted number
                                          $minValue = 0;  // Set your desired minimum value
                                          $maxValue = $numberOnly;
                                    }
                                    echo "<input type='$inputType' class='form-control' name='$columnName' id='$columnName' min='$minValue' max='$maxValue' value='$defaultValue'  required>
                                  </div>";
                                }
              // $inputType = (strpos($row['Type'], 'int') !== false) ? 'number' : 'text';

            //   echo "<div class='form-group'>
                  //     <label for='$columnName'>$columnName:</label>
                //       <input type='$inputType' class='form-control' name='$columnName' id='$columnName' value='$defaultValue' required>
                  //   </div>";
           }
       }
       ?>
<?php
       echo" <button type='submit' class='btn btn-warning'>Rate</button>
    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
</form>
                           </form>
                       </div>
                   </div>
               </div>
            </td>";
            // this is for teh table shows
            foreach ($rowData as $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }

        echo "</tbody></table>";
        echo "</div>"; // Close the table-responsive div
        echo "</div>"; // Close the table-responsive div


    } else {
        echo "<p>Error fetching details for table '$tableName': " . $conn->error . "</p>";
    }
} else {
    echo "<p>No table specified.</p>";
}

// Close the database connection
$conn->close();
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI/tZ1Zq75J8ANbYIk0J368aR5xC1bUh8kATeYeM=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
