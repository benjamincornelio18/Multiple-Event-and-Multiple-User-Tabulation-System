<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addJudge'])) {
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

    // Get form data
    $judgeUsername = $_POST['judgeUsername'];
    $numberOfParticipants = $_POST['numberOfParticipants'];
    $tableName = $_POST['tableName'];
    // Check if the judge exists in the database
       $checkJudgeSql = "SELECT * FROM judges WHERE username = '$judgeUsername'";
       $checkJudgeResult = $conn->query($checkJudgeSql);

       if ($checkJudgeResult->num_rows > 0) {
           // Judge exists, proceed to add participants
           for ($i = 1; $i <= $numberOfParticipants; $i++) {
               // Sanitize table name
               $tableName = mysqli_real_escape_string($conn, $tableName);

               // Insert participant into the database
               $insertParticipantSql = "INSERT INTO `$tableName` (judgeUsername, contestantNumber) VALUES ('$judgeUsername', $i)";
               $conn->query($insertParticipantSql);
           }
           echo '<div id="alert" class="alert alert-success alert-dismissible fade show" role="alert">
                  Participants added successfully for judge'; echo '
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
                 window.location.href ="judgetable_details.php?table=' . $tableName . '";
             }, 500);
         }, 500);
     </script>';
           echo "";
       }

       echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">';

       echo '<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI/tZ1Zq75J8ANbYIk0J368aR5xC1bUh8kATeYeM=" crossorigin="anonymous"></script>';

       // Close the database connection
       $conn->close();
   }
   ?>
