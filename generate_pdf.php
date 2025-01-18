<?php


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




$sql = "SELECT * FROM admin WHERE username = 'tabulator'";
$result = $conn->query($sql);
// Debug information for admin
// echo "Admin Query: $sql<br>";
//   echo "Admin Result: ";
 //var_dump($result);

      if($result->num_rows>0)
      {

        // Fetch the user data
        $userData = $result->fetch_assoc();

        $name = $userData['firstname'];
        $lastname = $userData['lastname'];
      }
      else{
        $name="NO TABULATOR NAME";
        echo "No name for Tabulator";
      }


// Check if the 'table' parameter is set in the URL
if (isset($_GET['table'])) {
    $tableName = $_GET['table'];

    // Fetch details for the selected table
    $fetchTableDetailsQuery = "DESCRIBE `$tableName`";
    $tableDetailsResult = $conn->query($fetchTableDetailsQuery);

    if ($tableDetailsResult !== FALSE) {
}
}
// Include autoloader
require 'vendor/autoload.php';

// Use the dompdf namespace
use Dompdf\Dompdf;
use Dompdf\Options;

// Create an instance of the dompdf class
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);
$capitalizedTableName = strtoupper($tableName);
// Load HTML content with header, footer, and table
$html = '
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            header, footer { padding: 0.5px; text-align: center; background-color: #f2f2f2; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #dddddd; padding: 8px; text-align:center; }
            th { background-color: #f2f2f2; }
             img.isufst{ width: 120px; float: left; margin-left: 2px; margin-top:-8px;}
             img.caring{ width: 100px; float: right; margin-right:2px; margin-top:-10px; }
             img.mis{ width: 70px; float: left; margin-right:10px; margin-top:-10px; }
        </style>
    </head>
    <body>
<img class="isufst" src="data:image/jpeg;base64,' . base64_encode(file_get_contents('img/isufst.png')) . '" alt="HELLO WORLD">
<img class="caring" src="data:image/jpeg;base64,' . base64_encode(file_get_contents('img/caring.png')) . '" alt="HELLO WORLD">
        <header style="line-height: 0.2; margin-bottom: 10px;">

            <h4 >Iloilo State University of Fisheries Science and Technology</h4>
            <h4 >Dinge Campus</h4>

        </header>

          <p class="text-center" style="font-family:verdana; font-size:18px; margin-bottom:-20px;">SUMMARY RESULTS: ' . $capitalizedTableName . ' Category </p>
            <div class="table-responsive" style="line-height: 0.5; margin-bottom: 5px;">
                <table class="table table-bordered">
                    ';

// Fetch distinct judgeUsernames
$distinctJudgesQuery = "SELECT DISTINCT judgeUsername FROM `$tableName`";
$distinctJudgesResult = $conn->query($distinctJudgesQuery);

$html .= '<th><p class="text-center" style="font-size:10px;">Contestant</p>';
 $html .= '<p class="text-center" style="font-size:10px;">Number</p></th>';

  $judge=1;
while ($judgeRow = $distinctJudgesResult->fetch_assoc()) {
    $html .= '<th><p class="text-center" style="font-size:14px;">JUDGE '.$judge.':</p> <p class="text-center" style="font-size:14px;"> RANKING<br></p>';



    $html .= '<p class="text-center" style="font-size:10px;">Username:</p> <p class="text-center" style="font-size:10px;">' . $judgeRow['judgeUsername'].'</p></th>';
    $judge++;
}

$html .= '<th><p style="font-size:10px">Combined</p> <p style="font-size:10px">Rank</p></th></tr></thead>
            <tbody>';

                        // Fetch distinct contestantNumbers
                        $distinctContestantsQuery = "SELECT DISTINCT contestantNumber FROM `$tableName`";
                        $distinctContestantsResult = $conn->query($distinctContestantsQuery);

                        // Array to store total rankings for each contestant
                        $totalRankingsArray = [];



                        // Display data for each contestantNumber and judgeUsername combination
                        while ($contestantRow = $distinctContestantsResult->fetch_assoc()) {
                          $html .= '<tr>
                                      <td>' . $contestantRow['contestantNumber'] . '</td>';

                              $distinctJudgesResult->data_seek(0);
                            while ($judgeRow = $distinctJudgesResult->fetch_assoc()) {
                                $fetchRankingQuery = "SELECT ranking, criterion_percentage FROM `$tableName` WHERE contestantNumber = {$contestantRow['contestantNumber']} AND judgeUsername = '{$judgeRow['judgeUsername']}'";
                                $rankingResult = $conn->query($fetchRankingQuery);

                                $rankingData = $rankingResult->fetch_assoc();
                                $rankingValue = $rankingData ? $rankingData['ranking'] : '';
                                $percentageValue = $rankingData ? $rankingData['criterion_percentage'] : ''; // Corrected: Get percentage value from $rankingData

                                $html .='<td>'.$rankingValue.' ';
                                $html .=' ('.$percentageValue.'%)</td>';

                                // Add ranking value to the totalRankings array
                                $totalRankingsArray[$contestantRow['contestantNumber']][] = $rankingValue;
                            }

                            // Calculate and display total ranking for each contestant
                            $totalRankingQuery = "SELECT SUM(ranking) AS totalRanking FROM `$tableName` WHERE contestantNumber = {$contestantRow['contestantNumber']}";
                            $totalRankingResult = $conn->query($totalRankingQuery);
                            $totalRankingData = $totalRankingResult->fetch_assoc();
                            $totalRankingValue = $totalRankingData ? $totalRankingData['totalRanking'] : ''; // Get total ranking value or empty if not available

                          //  $html .='<td>' . {$totalRankingValue}.'</td>';
                            $html .= '<td>' . $totalRankingValue . '</td>';
                            //echo $totalRankingValue;

                            // Assign rank based on the sorted order of total rankings
                            //echo $contestantRow['contestantNumber'];

                            //$specificKey = 'some_key';

                            //echo "<td>$myArray[$specificKey]</td>";
                          $html .='</tr>';

                        }
            $html .='</tbody>
        </table></div></div>';



              // Table 2 with col-4
            $html .='<div class="col-4" >';
            $html .='<p class="text-center" style="font-family:verdana; font-size:20px; margin-bottom:-20px;">WINNERS</p>';

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
                                      $html .='<div class="table-responsive"  style="line-height: 0.5; margin-bottom: 5px;">';
                                      $html .='<table class="table table-bordered"  style="line-height: 0.5; margin-bottom: 5px;">
                                              <thead>
                                              <tr>
                                              <td><h4> Contestant No. </h4></td>
                                              <td><h4>  FINAL RANKING </h4></td>
                                              </tr>';
                                      foreach ($totalRankingValues as $value) {
                                    $html .='<tr>';
                                          $rank = array_search($value, $ordered_values) + 1;
                                          $html .='<td>'. $ContestantNum .'<br/> </td>'; // CONTESTANT NUM
                                          $html .='<td> Rank combined score:'. $value.' : <b> Rank '.$rank.'</b>  <br/> </td>';

                                          $html .='</tr>';
                                          $ContestantNum++;
                                      }

                                    //$html .='<a href="generate_pdf.php?table={$tableName}" class="btn btn-primary">EXPORT RESULT</a>';

                $html .='</tbody></table>';
                $html .='</div>';
              $html .='</div>';



      $html .=' <footer>';

        $html .='<div class="col-8" >
          <h3 style="line-height: 0.2; margin-bottom: 1px;"> </h3>
          <div class="table-responsive" style="line-height: 0.5; margin-bottom: 1px;">
              <table class="table table-bordered">
                  ';

                      $combinedQuery = "
                      SELECT DISTINCT t1.judgeUsername, t2.firstname, t2.lastname,t2.username
                      FROM `$tableName` AS t1
                      JOIN `judges` AS t2 ON t1.judgeUsername = t2.username
                ";
                $judge=1;

                $combinedResult = $conn->query($combinedQuery);

                if ($combinedResult) {
                    // Loop through the combined result set and display data
                    while ($row = $combinedResult->fetch_assoc()) {
                        // Access columns from both tables using the alias directly
                        //$html .= '<td>' . $row['judgeUsername'] . '</td>'; // Use t1.judgeUsername if needed

                        $html .= '<th style="padding: 0.5px;"><p class="text-center" style="font-family:verdana; font-size:12px;">' . $row['firstname'] .' '. $row['lastname']. '</p>';
                        $html .= '<p class="text-center" style="font-family:verdana; font-size:9px;">JUDGE '.$judge.' Name and Signature<br></p></th>';
                      ///  $html .= '<td>' . $row['lastname'] . '</td>';
                        // Add other columns as needed
                          $judge++;
                    }
                } else {
                    // Handle query error
                    echo "Error: " . $conn->error;
                }




                //$html .= '<h6 style="text-align:center;padding: 1px;">JUDGE '.$judge.' Name and Signature<br></h6></th>';
        $html .=' </footer>


      <h5 style="text-align:left;">Verified and Tabulated by:</h5>


      <h5 style="text-align:left;margin-bottom:-25px">'.$name.' '.$lastname .'</h5>
      <h6 style="text-align:left;">Chair Tabulator</h6>
        <img class="mis" src="data:image/jpeg;base64,' . base64_encode(file_get_contents('img/mis.png')) . '" alt="HELLO WORLD">
         <br>
        <br>
        <h6 style="text-align:left;">TABULATION SYSTEM PREPARED BY MIS OFFICE</h6>
    </body>
    </html>';

$dompdf->loadHtml($html);

// Set paper size to A4 with landscape orientation
$dompdf->setPaper('legal', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream($tableName . '.pdf', array('Attachment' => 0));



// Render the HTML as PDF
$dompdf->render();

// Get the generated PDF content as a string
$output = $dompdf->output();

// Define the file path where you want to save the PDF on the server
$pdfFilePath = 'pdf/' . $tableName . '.pdf';

// Save the PDF content to the file
file_put_contents($pdfFilePath, $output);

// Output success message or perform any other actions
echo 'PDF saved successfully to: ' . $pdfFilePath;
?>
