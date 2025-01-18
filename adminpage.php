<?php
// Start the session
session_start();

// Check if the user is not logged in, redirect to the l page
if (!isset($_SESSION['admin_username'])) {
    header("Location:index.php");
    exit();
}

// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}



// Create tables and add judges functionality
// Create tables and add judges functionality
if (isset($_POST['create_judges_table'])) {
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

    // Check if judges table exists
    $checkTableExists = "SHOW TABLES LIKE 'judges'";
    $result = $conn->query($checkTableExists);

    if ($result->num_rows > 0) {

        echo '<div id="alert" class="alert alert-danger alert-dismissible fade show" role="alert">
                Judges table already exists!
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
        // Create judges table
        $sql = "CREATE TABLE judges (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) UNIQUE NOT NULL,
            firstname VARCHAR(255) NOT NULL,
            lastname VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL
        )";

        if ($conn->query($sql) === TRUE) {

    echo '<div id="alert" class="alert alert-success alert-dismissible fade show" role="alert">
              Judges table created successfully!
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
            echo "Error creating judges table: " . $conn->error;
        }
    }

    // Close the database connection
    $conn->close();

}

// Logout functionality
// Logout functionality
if (isset($_POST['logoutConfirmed'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}



// ADD SLIDER
if (isset($_POST['upload_image'])) {
    // Define the target directory and file path
    $targetDir = "img/";
    $targetFile = $targetDir . "cultural5.gif"; // The new file name

    // Check if the upload was successful
    if (isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] == UPLOAD_ERR_OK) {
        $fileType = mime_content_type($_FILES['imageFile']['tmp_name']);

        // Validate that the file is an image
        if (in_array($fileType, ['image/gif', 'image/jpeg', 'image/png'])) {
            // Move the uploaded file to the target directory and rename it
            if (move_uploaded_file($_FILES['imageFile']['tmp_name'], $targetFile)) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Image uploaded successfully and renamed to cultural5.gif.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
            } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Error uploading the image. Please try again.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
            }
        } else {
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Invalid file type. Please upload a valid image file (GIF, JPEG, or PNG).
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        }
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                No file was uploaded or there was an upload error. Please try again.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
              </div>';
    }
}




// ADD cert app
if (isset($_POST['upload_certapp'])) {
    // Define the target directory and file path
    $targetDir = "img/";
    $targetFile = $targetDir . "appearance.png"; // The new file name

    // Check if the upload was successful
    if (isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] == UPLOAD_ERR_OK) {
        $fileType = mime_content_type($_FILES['imageFile']['tmp_name']);

        // Validate that the file is an image
        if (in_array($fileType, ['image/gif', 'image/jpeg', 'image/png'])) {
            // Move the uploaded file to the target directory and rename it
            if (move_uploaded_file($_FILES['imageFile']['tmp_name'], $targetFile)) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                          Image uploaded successfully for CERTIFICATE OF APPEARANCE.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
            } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Error uploading the image. Please try again.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
            }
        } else {
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Invalid file type. Please upload a valid image file (GIF, JPEG, or PNG).
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        }
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                No file was uploaded or there was an upload error. Please try again.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
              </div>';
    }
}




// ADD cert recognition
if (isset($_POST['upload_certrecog'])) {
    // Define the target directory and file path
    $targetDir = "img/";
    $targetFile = $targetDir . "recognition.png"; // The new file name

    // Check if the upload was successful
    if (isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] == UPLOAD_ERR_OK) {
        $fileType = mime_content_type($_FILES['imageFile']['tmp_name']);

        // Validate that the file is an image
        if (in_array($fileType, ['image/gif', 'image/jpeg', 'image/png'])) {
            // Move the uploaded file to the target directory and rename it
            if (move_uploaded_file($_FILES['imageFile']['tmp_name'], $targetFile)) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Image uploaded successfully for CERTIFICATE OF RECOGNITION.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
            } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Error uploading the image. Please try again.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
            }
        } else {
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Invalid file type. Please upload a valid image file (GIF, JPEG, or PNG).
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        }
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                No file was uploaded or there was an upload error. Please try again.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
              </div>';
    }
}



//Add judge
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
                        $judge_email = $_POST["judge_email"];


                        // Check if the username already exists
                        $checkUsernameQuery = "SELECT * FROM judges WHERE username = '$judgeUsername' OR email = '$judge_email'";
                        $result = $conn->query($checkUsernameQuery);

            if ($result->num_rows > 0) {
              echo '<div id="alert" class="alert alert-danger alert-dismissible fade show" role="alert">
                      Error adding judge: Username already or Email exists.
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

            }

             else {
                // Insert data into judges table
                $insertJudgeQuery = "INSERT INTO judges (username, firstname, lastname, password,email) VALUES ('$judgeUsername', '$judgeFirstname', '$judgeLastname', '$judgePassword','$judge_email')";

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
    <h2>Welcome, <?php echo 'User:'.$_SESSION['admin_username']." ".$_SESSION['admin_firstname']." ".$_SESSION['admin_lastname']; ?>!</h2>
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




    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createJudgesTableModal">
        Create Judges Table
    </button>

    <!-- Confirmation Modal for Creating Judges Table -->
    <div class="modal" id="createJudgesTableModal" role="dialog" aria-labelledby="createJudgesTableModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmLogoutModalLabel">Confirm Create Table for Judjes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Create Judges Table?
                </div>
                <div class="modal-footer">
                    <form id="logoutForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <button type="submit" name="create_judges_table" class="btn btn-danger">Create</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addJudgeModal">
        Add Judge
    </button>


    <!-- Add Judge Modal -->
    <div class="modal" id="addJudgeModal" tabindex="-1" role="dialog" aria-labelledby="addJudgeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addJudgeModalLabel">Add Judge</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add Judge Form -->
                    <form id="addJudgeForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="judge_username">Username:</label>
                            <input type="text" class="form-control" id="judge_username" name="judge_username" required>
                        </div>
                        <div class="form-group">
                            <label for="judge_firstname">First Name:</label>
                            <input type="text" class="form-control" id="judge_firstname" name="judge_firstname" required>
                        </div>
                        <div class="form-group">
                            <label for="judge_lastname">Last Name:</label>
                            <input type="text" class="form-control" id="judge_lastname" name="judge_lastname" required>
                        </div>
                        <div class="form-group">
                            <label for="judge_password">Password:</label>
                            <input type="password" class="form-control" id="judge_password" name="judge_password" required>
                        </div>
                        <div class="form-group">
                             <label for="judge_email">Email:</label>
                             <input type="email" class="form-control" id="judge_email" name="judge_email" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}">
                             <small id="emailHelp" class="form-text text-muted">Enter a valid email address.</small>
                       </div>
                        <button type="submit" name="add_judge" class="btn btn-primary">Add Judge</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Competition Button -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCompetitionModal">
    Add Competition
</button>

<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadImageModal">
  Upload Slider
</button>

<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadCertApp">
  Upload CertApp
</button>

<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadCertRecog">
  Upload CertRecog
</button>

<a href='digitalcert.php' class='btn btn-primary'>Design Certificate</a>

<!-- Upload Image Modal -->
<div class="modal" id="uploadImageModal" tabindex="-1" role="dialog" aria-labelledby="uploadImageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadImageModalLabel">Upload Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Upload Image Form -->
                <form id="uploadImageForm" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="form-group">
                        <label for="imageFile">Choose an image:</label>
                        <input type="file" class="form-control-file" id="imageFile" name="imageFile" accept="image/*" required>
                    </div>
                    <button type="submit" name="upload_image" class="btn btn-primary">Upload Image</button>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Upload Cert Appearance-->
<div class="modal" id="uploadCertApp" tabindex="-1" role="dialog" aria-labelledby="uploadImageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadImageModalLabel">Upload CERTIFICATE OF APPERANCE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Upload Image Form -->
                <form id="uploadImageForm" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="form-group">
                        <label for="imageFile">Choose an image:</label>
                        <input type="file" class="form-control-file" id="imageFile" name="imageFile" accept="image/*" required>
                    </div>
                    <button type="submit" name="upload_certapp" class="btn btn-primary">Upload Image</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Upload Cert Recognition-->
<div class="modal" id="uploadCertRecog" tabindex="-1" role="dialog" aria-labelledby="uploadImageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadImageModalLabel">Upload CERTIFICATE OF RECOGNITION</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Upload Image Form -->
                <form id="uploadImageForm" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="form-group">
                        <label for="imageFile">Choose an image:</label>
                        <input type="file" class="form-control-file" id="imageFile" name="imageFile" accept="image/*" required>
                    </div>
                    <button type="submit" name="upload_certrecog" class="btn btn-primary">Upload Image</button>
                </form>
            </div>
        </div>
    </div>
</div>




<!-- Add Competition Modal -->
<div class="modal" id="addCompetitionModal" tabindex="-1" role="dialog" aria-labelledby="addCompetitionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCompetitionModalLabel">Add Competition</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addCompetitionForm" method="post" action="process_add_competition.php">
                    <div class="form-group">
                        <label for="competitionName">Competition Name</label>
                        <input type="text" class="form-control" id="competitionName" name="competition_name" required>
                    </div>
                    <div id="criteriaContainer">
                        <!-- Dynamic criteria fields will be added here -->
                    </div>
                    <div class="form-group">
                        <label for="totalPercentage">Total Percentage</label>
                        <input type="text" class="form-control" name="totalPercentage" id="totalPercentage" readonly>
                    </div>
                    <button type="button" class="btn btn-primary" id="addCriteriaBtn">Add Criterion</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
    <!-- ... (other Bootstrap-styled content) ... -->




<h5>JUDGES TABLE!</h5>
      <!-- TABLEFOR THEJUDGES-->
    <div class="table-responsive mt-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Manage</th>
                    <!-- Add more columns as needed -->
                </tr>
            </thead>
            <tbody>
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

                // Check if the query execution was successful
        if ($judgesResult === FALSE) {
                   // Display an error message if the query fails
                   echo "Error fetching judges: " . $conn->error;
               }
          else {
                if ($judgesResult->num_rows > 0)
                {
                // Display judges from the result set
                while ($row = $judgesResult->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['username']}</td>
                            <td>{$row['firstname']}</td>
                            <td>{$row['lastname']}</td>
                            <td>
                            <button type='button' class='btn btn-danger' data-toggle='modal' data-target='#confirmDeleteModal{$row['id']}'>
                                 Delete
                             </button>
                             <button type='button' class='btn btn-warning' data-toggle='modal' data-target='#editJudgeModal{$row['id']}' data-judgeid='{$row['id']}' data-judgeusername='{$row['username']}' data-judgefirstname='{$row['firstname']}' data-judgelastname='{$row['lastname']}'data-judgeemail='{$row['email']}'>
                              Edit
                           </button>
                            </td>
                 <!-- Delete Confirmation Modal -->
                 <div class='modal' id='confirmDeleteModal{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='confirmDeleteModalLabel' aria-hidden='true'>
                     <div class='modal-dialog' role='document'>
                         <div class='modal-content'>
                             <div class='modal-header'>
                                 <h5 class='modal-title' id='confirmDeleteModalLabel'>Confirm Delete Judge</h5>
                                 <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                     <span aria-hidden='true'>&times;</span>
                                 </button>
                             </div>
                             <div class='modal-body'>
                                 Are you sure you want to delete Judge ID: {$row['id']}?
                             </div>
                             <div class='modal-footer'>
                                 <!-- Form for Delete Confirmation -->
                                 <form action='delete_judge.php' method='post'>
                                     <input type='hidden' name='id' value='{$row['id']}'>
                                     <button type='submit' class='btn btn-danger'>Delete</button>
                                     <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
                                 </form>
                             </div>
                         </div>
                     </div>
                 </div>

                 <!-- Edit Judge Modal -->
                 <div class='modal' id='editJudgeModal{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='editJudgeModalLabel' aria-hidden='true'>
                     <div class='modal-dialog' role='document'>
                         <div class='modal-content'>
                             <div class='modal-header'>
                                 <h5 class='modal-title' id='editJudgeModalLabel'>Edit Judge</h5>
                                 <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                     <span aria-hidden='true'>&times;</span>
                                 </button>
                             </div>
                             <div class='modal-body'>
                                 <!-- Form for Edit -->
                                 <form action='edit_judge.php' method='post'>
                                     <input type='hidden' name='id' value='{$row['id']}'>

                                     <div class='form-group'>
                                         <label for='editUsername'>Username:</label>
                                         <input type='text' class='form-control' id='editUsername' name='edit_username' value='{$row['username']}' required>
                                     </div>

                                     <div class='form-group'>
                                         <label for='editFirstName'>First Name:</label>
                                         <input type='text' class='form-control' id='editFirstName' name='edit_firstname' value='{$row['firstname']}' required>
                                     </div>

                                     <div class='form-group'>
                                         <label for='editLastName'>Last Name:</label>
                                         <input type='text' class='form-control' id='editLastName' name='edit_lastname' value='{$row['lastname']}' required>
                                     </div>

                                     <div class='form-group'>
                                         <label for='editLastName'>Password:</label>
                                         <input type='text' class='form-control' id='editPassword' name='edit_password' value='{$row['password']}' required>
                                     </div>
                                     <div class='form-group'>
                                         <label for='editLastName'>Email:</label>
                                         <input type='text' class='form-control' id='editEmail' name='edit_email' value='{$row['email']}' required>
                                     </div>

                                     <button type='submit' class='btn btn-warning'>Update</button>
                                     <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
                                 </form>
                             </div>
                         </div>
                     </div>
                 </div>
             </tr>";
                          }

}
                else {
    // Display a message when no records are found
    echo "<tr><td colspan='5'>No records found</td></tr>";
  }

     }

   }

   else {
   // Display a message when the 'judges' table does not exist
   echo "<tr><td colspan='5'>Judges table does not exist</td></tr>";
}
                ?>
            </tbody>
        </table>

<h5>EVENT OR CATEGORY!</h5>
        <!-- TABLE for RECORDS-->
      <div class="table-responsive mt-3">
          <table class="table table-bordered">
              <thead>
                  <tr>
                    <th>Event/Category</th>
                    <th>View Details</th>
                    <th>Drop Table</th> <!-- New column for Drop Table button -->
                      <!-- Add more columns as needed -->
                  </tr>
              </thead>
              <tbody>
                  <?php
                  // Fetch all tables in the cultural database
                  $fetchTablesQuery = "SHOW TABLES FROM cultural";
                  $tablesResult = $conn->query($fetchTablesQuery);

                  if ($tablesResult !== FALSE) {
                      while ($table = $tablesResult->fetch_row()) {
                        //the tableName 1 has spaces
                          $tableName1 = $table[0];

                          if ($tableName1 !== 'admin' AND $tableName1 !== 'judges'   ) {

                          // Remove spaces and combine words
                          $tableName = str_replace(' ', '', $tableName1);
                        //  echo "Original Table Name:$tableName<br>";


                      echo "<tr>
                            <td>{$tableName}</td>

                              <td>
                                <a href='table_details.php?table={$tableName1}' class='btn btn-primary'>View Details</a>
                              </td>

                              <td>
                                  <button type='button' class='btn btn-danger' data-toggle='modal' data-target='#confirmDropModal{$tableName}'>
                                  Drop Table</button>
                              </td>

                   <!-- Delete Confirmation Modal -->
                   <div class='modal' id='confirmDropModal{$tableName}' tabindex='-1' role='dialog' aria-labelledby=''confirmDropModalLabel' aria-hidden='true'>
                       <div class='modal-dialog' role='document'>
                           <div class='modal-content'>
                               <div class='modal-header'>
                                   <h5 class='modal-title' id='confirmDeleteModalLabel'>Confirm Delete Judge</h5>
                                   <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                       <span aria-hidden='true'>&times;</span>
                                   </button>
                               </div>
                               <div class='modal-body'>
                                   Are you sure you want to drop the table:{$tableName}?
                               </div>
                               <div class='modal-footer'>
                                   <!-- Form for Delete Confirmation -->
                                   <form action='drop_table.php' method='post'>

                                    <!-- Back to the original Name with spaces-->

                                       <input type='hidden' name='table' value='{$tableName1}'>
                                       <button type='submit' class='btn btn-danger'>Delete</button>
                                       <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
                                   </form>
                               </div>
                           </div>
                       </div>
                   </div>
               </tr>";
                            }


    }
}



else {
// Display a message when no records are found
echo "<tr><td colspan='5'>No records found</td></tr>";
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
