<?php
// Start the session
session_start();

// Check if the user is not logged in, redirect to the l page
if (!isset($_SESSION['judge_username'])) {
    header("Location:index.php");
    exit();
}

// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}



// Logout functionality
// Logout functionality
if (isset($_POST['logoutConfirmed'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}



// Add judge functionality

if (isset($_POST['add_judge'])) {
    // Database connection details
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

  else  {
            // Check if judges table exists
            $checkTableExists = "SHOW TABLES LIKE 'judges'";
            $result = $conn->query($checkTableExists);

              if ($result->num_rows == 0) {

                  echo '<div id="alert" class="alert alert-danger alert-dismissible fade show" role="alert">
                          Judges table is not created yet, Please create the judges table!
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
                              }, 2000);
                          }, 3000);
                        </script>';
                        }

          else {

                        // Get data from the form
                        $judgeUsername = $_POST['judge_username'];
                        $judgeFirstname = $_POST['judge_firstname'];
                        $judgeLastname = $_POST['judge_lastname'];
                        $judgePassword = $_POST['judge_password'];

                        // Check if the username already exists
                        $checkUsernameQuery = "SELECT * FROM judges WHERE username = '$judgeUsername'";
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
                          }, 1000);
                      }, 2000);
                    </script>';

            } else {
                // Insert data into judges table
                $insertJudgeQuery = "INSERT INTO judges (username, firstname, lastname, password) VALUES ('$judgeUsername', '$judgeFirstname', '$judgeLastname', '$judgePassword')";

                if ($conn->query($insertJudgeQuery) === TRUE) {

                    echo '<div id="alert" class="alert alert-success alert-dismissible fade show" role="alert">
                              Judge added successfully!
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

              }
          }
      }
    // Close the database connection
    $conn->close();
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI/tZ1Zq75J8ANbYIk0J368aR5xC1bUh8kATeYeM=" crossorigin="anonymous"></script>

</head>
<body>
  <!-- Slider -->

  <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
      <div class="carousel-inner">
          <div class="carousel-item active">
              <img src="img/cultural5.gif" class="d-block w-100" alt="...">
          </div>


      </div>
  </div>
<!-- ... (previous body content) ... -->
<div class="container mt-5">
    <h2>Welcome JUDGE, <?php //echo 'User:'.$_SESSION['judge_username']; ?>!<?php echo $_SESSION['judge_firstname']." ".$_SESSION['judge_lastname']; ?></h2>

    <!-- Logout Button -->
    <!-- Logout Button -->
  <button type="button" id="logoutButton" class="btn btn-danger" data-toggle="modal" data-target="#confirmLogoutModal">
      Logout
  </button>


  <!-- Logout Confirmation Modal -->
  <div class="modal" id="confirmLogoutModal" tabindex="-1" role="dialog" aria-labelledby="confirmLogoutModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="confirmLogoutModalLabel">Confirm Logout</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  Are you sure you want to logout?
              </div>
              <div class="modal-footer">
                  <form id="logoutForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                      <button type="submit" name="logoutConfirmed" class="btn btn-danger">Logout</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  </form>
              </div>
          </div>
      </div>
  </div>




    <?php

                // Database connection details for the table to view in the
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

                // Query to fetch judges from the database
                $fetchJudgesQuery = "SELECT * FROM judges";

                // Check if the judges table exists before fetching
                $checkTableExists = "SHOW TABLES LIKE 'judges'";
                $tableResult = $conn->query($checkTableExists);

    if ($tableResult->num_rows > 0) {
                    $judgesResult = $conn->query($fetchJudgesQuery);

                    }

   else {
   // Display a message when the 'judges' table does not exist
   echo "<tr><td colspan='5'>Judges table does not exist</td></tr>";
}
                ?>
            </tbody>
        </table>

<h5>TABLE EVENTS AND CATEGORY!</h5>
        <!-- TABLE for RECORDS-->
        <table class="table table-bordered">
        <thead>
            <tr>
                <th>Table Name</th>
                <th>View Details</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch all tables in the cultural database
            $fetchTablesQuery = "SHOW TABLES FROM cultural";

            $tablesResult = $conn->query($fetchTablesQuery);
            //echo $tablesResult;

            if ($tablesResult !== FALSE) {
                while ($table = $tablesResult->fetch_row()) {
                    $tableName1 = $table[0];
                    //echo $tableName1;

                    if ($tableName1 !== 'admin' && $tableName1 !== 'judges') {
                        // GET THE USERNAME OF THE JUDGE to query in each table

                        $jUserName = $_SESSION['judge_username'];
                        // Use prepared statements to prevent SQL injection
                        $stmt = $conn->prepare("SELECT * FROM `$tableName1` WHERE judgeUsername = ?");
                        $stmt->bind_param("s", $jUserName);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            // Remove spaces and combine words
                            $tableName = str_replace(' ', '', $tableName1);

                            echo "<tr>
                                    <td>{$tableName}</td>
                                    <td>
                                        <a href='judgetable_details.php?table={$tableName1}' class='btn btn-primary'>View Details</a>
                                    </td>
                                </tr>";
                        }
                    }
                }
            } else {
                // Display a message when no records are found
                echo "<tr><td colspan='2'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>

















    </div>
</div>

<!-- THis is for teh criteria  -->



<script>
    // Initialize criteria count and total percentage
    var criteriaCount = 0;
    var totalPercentage = 0;

    // Function to add a new criterion field
    function addCriterion() {
        if (criteriaCount >= 10 || totalPercentage >= 100) {
            return; // Limit criteria count and total percentage
        }

        // Create new criterion elements
  var criteriaContainer = document.getElementById('criteriaContainer');

  var newCriterionDiv = document.createElement('div');
  newCriterionDiv.className = 'form-row';

  // Create and set attributes for the Criterion Name input
  var criterionNameDiv = document.createElement('div');
  criterionNameDiv.className = 'col';
  var criterionNameInput = document.createElement('input');
  criterionNameInput.type = 'text';
  criterionNameInput.className = 'form-control';
  criterionNameInput.id = 'criterionName' + (criteriaCount + 1);  // Set the id attribute

  // this is for the name of the Criteria THAT WILL BEPASS TO THE NEXT FILE
  criterionNameInput.name = 'criteria' + (criteriaCount + 1) + '_name';


  criterionNameInput.placeholder = 'Criterion Name';
  criterionNameDiv.appendChild(criterionNameInput);

  // Create and set attributes for the Criterion Percentage input
  var criterionPercentageDiv = document.createElement('div');
  criterionPercentageDiv.className = 'col';
  var criterionPercentageInput = document.createElement('input');
  criterionPercentageInput.type = 'number';
  criterionPercentageInput.className = 'form-control';
  criterionPercentageInput.id = 'criterionPercentage' + (criteriaCount + 1);  // Set the id attribute

    // this is for the name of the PERCENTAGE THAT WILL BEPASS TO THE NEXT FILE
  criterionPercentageInput.name = 'criteria' + (criteriaCount + 1) + '_percentage';
  criterionPercentageInput.placeholder = 'Percentage';
  criterionPercentageInput.addEventListener('input', updateTotalPercentage);
  criterionPercentageDiv.appendChild(criterionPercentageInput);

        // Create remove button
        var removeButtonDiv = document.createElement('div');
        removeButtonDiv.className = 'col';
        var removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'btn btn-danger';
        removeButton.innerHTML = 'X';
        removeButton.addEventListener('click', function() {
            removeCriterion(newCriterionDiv);
        });
        removeButtonDiv.appendChild(removeButton);

        // Add the new criterion elements to the container


        newCriterionDiv.appendChild(criterionNameDiv);
        newCriterionDiv.appendChild(criterionPercentageDiv);
        newCriterionDiv.appendChild(removeButtonDiv);
        criteriaContainer.appendChild(newCriterionDiv);

        // Update counts
        criteriaCount++;

        // Update total percentage
        updateTotalPercentage();
    }

    // Function to remove a criterion field
    function removeCriterion(criterionDiv) {
        criteriaCount--;
        criterionDiv.remove();

        // Update total percentage
        updateTotalPercentage();
    }

    // Function to update the total percentage
    function updateTotalPercentage() {
        totalPercentage = 0;

        // Loop through all criterion percentage inputs
        var percentageInputs = document.querySelectorAll('[name^="criteria"][name$="_percentage"]');
        percentageInputs.forEach(function (input) {
            totalPercentage += parseInt(input.value) || 0;
        });

        // Update the total percentage textbox
        document.getElementById('totalPercentage').value = totalPercentage;

        // Enable/disable button based on total percentage
        document.getElementById('addCriteriaBtn').disabled = totalPercentage >= 100;
    }

    // Event listener for adding criteria
    document.getElementById('addCriteriaBtn').addEventListener('click', addCriterion);
</script>
<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeNabjIx6a0G+ma5FBpL" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
