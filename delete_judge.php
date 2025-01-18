<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cultural";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $judgeIdToDelete = $_POST['id'];

    $deleteJudgeQuery = "DELETE FROM judges WHERE id = $judgeIdToDelete";

    if ($conn->query($deleteJudgeQuery) === TRUE) {
      echo '<div id="alert" class="alert alert-success alert-dismissible fade show" role="alert">
                Judges deleted successfully!
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
        echo '<div class="alert alert-danger" role="alert">
                Error deleting judge: ' . $conn->error . '
              </div>';
    }

    $conn->close();
}
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI/tZ1Zq75J8ANbYIk0J368aR5xC1bUh8kATeYeM=" crossorigin="anonymous"></script>
