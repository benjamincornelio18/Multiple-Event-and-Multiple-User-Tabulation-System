<?php
// Start the session
session_start();

// Check if the admin session exists
if (isset($_SESSION['admin_username'])) {
    // Redirect to adminpage.php
    header("Location: adminpage.php");
    exit();
}

// ... (your existing code for login and other content)
?>


<?php
// Check if the login form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cultural";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

     // Check connection to MySQL server
    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        echo "<div class='container mt-5'>";
        echo "<h2 class='text-danger'>Database Connection Error</h2>";
        echo "<p>The specified database '$dbname' does not exist. Please create it and try again.</p>";
        echo "<button class='btn btn-primary' onclick='history.back()'>Go Back</button>"; // Bootstrap button
        echo "</div>";
        exit();
    }

    // Check if the database exists
    $dbname = "cultural";
    $db_check_query = "SHOW DATABASES LIKE '$dbname'";
    $result = $conn->query($db_check_query);

    if ($result->num_rows == 0) {
        echo "<div class='container mt-5'>";
        echo "<h2 class='text-warning'>Database Not Found</h2>";
        echo "<p>The specified database '$dbname' does not exist. Please create it and try again.</p>";
        echo "<button class='btn btn-primary' onclick='history.back()'>Go Back</button>"; // Bootstrap button
        echo "</div>";
        exit();
    }

    // If the database exists, connect to it
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection again
    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        echo "<div class='container mt-5'>";
        echo "<h2 class='text-danger'>Database Connection Error</h2>";
        echo "<button class='btn btn-primary' onclick='history.back()'>Go Back</button>"; // Bootstrap button
        echo "</div>";
        exit();
    }

    // Get username and password from the login form
    $enteredUsername = $_POST['username'];
    $enteredPassword = $_POST['password'];
    // Check if the admin table exists
    $table_check_query = "SHOW TABLES LIKE 'admin'";
    $table_check_result = $conn->query($table_check_query);

    if ($table_check_result->num_rows == 0) { // if zero ang database  no record in the admin table

        echo '<div id="alert" class="alert alert-danger alert-dismissible fade show" role="alert">
                Admin table is not created yet, Please create the admin table!
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
      //.  header("Location: index.php");
        //  exit();
    }


  else {

        //  echo $enteredUsername;
        //  echo $enteredPassword;


          // SQL query to check the credentials
          $sql = "SELECT * FROM admin WHERE username = '$enteredUsername' AND password = '$enteredPassword'";
          $result = $conn->query($sql);
          // Debug information for admin
          //echo "Admin Query: $sql<br>";
          //echo "Admin Result: ";
        //  var_dump($result);

          if($result->num_rows > 0) {
              session_start();
              // Fetch the user data
              $userData = $result->fetch_assoc();
              $_SESSION['admin_username'] = $enteredUsername;
              $_SESSION['admin_firstname'] = $userData['firstname'];
              $_SESSION['admin_lastname'] = $userData['lastname'];


              // Valid credentials, redirect to adminpage.php
              header("Location: adminpage.php");
              exit();
          }


          else
          {
            // Check if the admin table exists
            $table_check_query = "SHOW TABLES LIKE 'judges'";
            $table_check_result = $conn->query($table_check_query);


            if ($table_check_result->num_rows == 0) {
              //  echo "<h2 class='text-warning'>Database Table Not Found</h2>";
                //echo "<p>The specified judges table does not exist. Please create it and try again.</p>";
              //  echo "<button class='btn btn-primary' onclick='history.back()'>Go Back</button>"; // Bootstrap button
                //echo "<button class='btn btn-primary' onclick='history.back()'>Go Back</button>"; // Bootstrap button
                //exit();
                //header("Location: index.php");

                echo '<div id="alert" class="alert alert-danger alert-dismissible fade show" role="alert">
                        Judges table is not created yet, Please create the admin table!
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
              $sql = "SELECT * FROM judges WHERE username = '$enteredUsername' AND password = '$enteredPassword'";
              $result = $conn->query($sql);
              // Debug information for admin
              // echo "Admin Query: $sql<br>";
            //   echo "Admin Result: ";
               //var_dump($result);

                    if($result->num_rows>0)
                    {
                      session_start();
                      // Fetch the user data
                      $userData = $result->fetch_assoc();
                      $_SESSION['judge_username'] = $enteredUsername;
                      $_SESSION['judge_firstname'] = $userData['firstname'];
                      $_SESSION['judge_lastname'] = $userData['lastname'];
                      // Valid credentials, redirect to adminpage.php
                      header("Location: judgespage.php");
                      exit();
                    }
                    else{

                      echo '<div id="alert" class="alert alert-danger alert-dismissible fade show" role="alert">
                              Wrong username or Password!
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <title>ISUFST DINGLE CAMPUS-Culture and the arts festival 2024</title>


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Custom styles -->
    <style>
        /* Add your custom styles here */
    </style>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI/tZ1Zq75J8ANbYIk0J368aR5xC1bUh8kATeYeM=" crossorigin="anonymous"></script>
</head>
<body>

<!-- Navigation bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">


            <li class="nav-item" style="align:left;">
                <button class="btn btn-primary" data-toggle="modal" data-target="#loginModal">Login</button>
            </li>
        </ul>
    </div>
</nav>

<!-- Slider -->

<div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="img/cultural5.gif" class="d-block w-100" alt="...">
        </div>


    </div>
</div>


<!-- Login Modal -->
<div class="modal" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Enable continuous sliding
    $('.carousel').carousel({
        interval: 10000, // Set the interval between slides in milliseconds
        wrap: true, // Enable continuous sliding
        keyboard: false, // Disable keyboard navigation
        pause: 'hover' // Pause on hover
    });
</script>
<!-- Add your custom scripts here -->

</body>
</html>
