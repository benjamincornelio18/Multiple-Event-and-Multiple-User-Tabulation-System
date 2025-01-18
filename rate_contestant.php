<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cultural";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    //echo "HELLO WORLD";
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $judgeUsername = $_POST['edit_username'];
    $contestantNumber = $_POST['edit_contestantNumber'];
    $tableName = $_POST['tableName'];

    //echo $tableName;
    //echo $contestantNumber;

    $id = $_POST['id'];

    // Retrieve other dynamic column values
    $dynamicColumnValues = [];
    $fetchTableDetailsQuery = "DESCRIBE `$tableName`";
    $tableDetailsResult = $conn->query($fetchTableDetailsQuery);

    $tableDetailsResult->data_seek(0); // Reset result set pointer
    while ($row = $tableDetailsResult->fetch_assoc()) {
        $columnName = $row['Field'];

        // Skip the primary key, judgeUsername, and contestantNumber columns
        if ($row['Key'] === 'PRI' || $columnName === 'judgeUsername' || $columnName === 'contestantNumber' ) {
            continue;
        }

        // Determine the input type based on the data type of the column
        $inputType = 'text';
        if (strpos($row['Type'], 'int') !== false) {
            $inputType = 'number';
        }
        // Get the value from the form data using the null coalescing operator if
          $dynamicColumnValues[$columnName] = $_POST[$columnName] ?? null;

          // Check if the value is not null before further processing
          if ($dynamicColumnValues[$columnName] !== null) {
              // You can perform additional processing here if needed
              $dynamicColumnValues[$columnName] = $_POST[$columnName];
          }
        // Get the value from the form data

        //if($dynamicColumnValues[$columnName]=='')
    }
    $sum=0;
    // PERCENTAGE ARRAY
    //$myArrayPercentage = array();
    // Construct SQL query for updating the data
    $updateQuery = "UPDATE `$tableName` SET ";
    foreach ($dynamicColumnValues as $columnName => $value) {
        $intValue = (int)$value;
        $sum+=$intValue;
        //echo "the total is ".$sum;
        //$updateQuery .= "`$columnName` = '$value', ";
        //echo $columnName; echo $value;
      //  array_push($myArrayPercentage,$value);
        //$myArrayPercentage[] =$value;
          //$sum += $value;

          // Check if the column name is "criterion_percentage"
    if ($columnName === "criterion_percentage") {
        // Add a specific value to it (replace 'your_specific_value' with the desired value)
        $updateQuery .= "`$columnName` = '$sum', ";
    } else {
        // For other columns, set their values as usual
        $updateQuery .= "`$columnName` = '$value', ";
    }
    }

  //  foreach ($myArrayPercentage as $value) {
  //  $sum += $value;
  //  }


    $updateQuery = rtrim($updateQuery, ", "); // Remove the trailing comma
    $updateQuery .= " WHERE `judgeUsername` = '$judgeUsername' AND `contestantNumber` = '$contestantNumber'  AND `criterion_id` = '$id'";

    // Execute the update query


    if ($conn->query($updateQuery) === TRUE) {
      // Calculate ranking and update the ranking field for the specific judge
      $calculateRankingQuery = "
          UPDATE `$tableName`
          SET `ranking` = (
              SELECT COUNT(*) + 1
              FROM `$tableName` AS t
              WHERE t.`judgeUsername` = '$judgeUsername' AND t.`criterion_percentage` > `$tableName`.`criterion_percentage`
          )
          WHERE `judgeUsername` = '$judgeUsername'
      ";
     if ($conn->query($calculateRankingQuery) === TRUE) {
      //echo $tableName;
      echo '<div id="alert" class="alert alert-success alert-dismissible fade show" role="alert">
              RATING UPDATED.
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

echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">';

echo '<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI/tZ1Zq75J8ANbYIk0J368aR5xC1bUh8kATeYeM=" crossorigin="anonymous"></script>';

      // You might want to exit here or handle the error as needed
      exit();

 }


    }



    else {
        echo "Error updating contestant rating: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
