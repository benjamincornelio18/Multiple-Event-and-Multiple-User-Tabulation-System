<?php
//SEND CERT OF RECOG
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;
use Dompdf\Options;

// ... (Other dependencies and setup)

// Get data from the form
$selectedEmails = $_POST['selected_emails'];
$tablename = $_POST['tableName'];
//$firstname = $_POST['firstName']; // Assuming you have a field named "tableName" in your form
//echo $firstname;
// Generate PDF content

$tablename= strtoupper($tablename);
$pdfContent1 = generatePDFContent1($tablename,$selectedEmails); // Pass the tablename to the function

//$pdfContent2 = generatePDFContent2($tablename,$selectedEmails); // Pass the tablename to the function

// Check if the PDF content was successfully generated
if (empty($pdfContent1)) {
    echo 'Error: Failed to generate PDF content.';
    exit();
}



$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->Port       = 587; // Updated port for TLS
    $mail->SMTPAuth   = true;
    $mail->Username   = 'benjamincornelio@isufst.edu.ph';
    $mail->Password   = 'wxhm enuk vrcj sjqd';
    $mail->SMTPSecure = 'tls'; // Updated to 'tls'

    // ... (Recipient, attachment, and content settings)

    // Loop through selected email addresses and send the email
    foreach ($selectedEmails as $recipientEmail) {
        // ... (Other email configuration)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "cultural";

        $conn = new mysqli($servername, $username, $password, $dbname);

        $fetchEmailsQuery = "SELECT email, firstname, lastname FROM judges WHERE email='$recipientEmail'";
        $result = $conn->query($fetchEmailsQuery);
        if ($result->num_rows > 0) {

          while ($row = $result->fetch_assoc()) {

              $firstname = $row['firstname'];
              $lastname= $row['lastname'];


                    $mail->setFrom('benjamincornelio@isufst.edu.ph', 'ISUFST-DINGLE CAMPUS');
                    $mail->addAddress($recipientEmail);

                    $mail->isHTML(true);
                    $mail->Subject = 'ISUFST DINGLE CAMPUS:Thank You for Your Judging Expertise';


                    $mail->Body    = '<p>Dear ' .$firstname.':</p>';
                    $mail->Body    .= '';
                    //$mail->Body    .= 'We wanted to extend our heartfelt thanks for your role as a judge in our Culture and the arts festival 2024</p>';
                    $mail->Body    .= '<p>We would like to extend our heartfelt thanks for your role as a judge in the '.$tablename.'</p>';
                    $mail->Body    .= '<p>Your expertise and thoughtful assessments were crucial to the success of the event. </p>';

                    $mail->Body    .= '<p>Your commitment to excellence did not go unnoticed, and we are truly grateful for your valuable contribution. </p>';
                    $mail->Body    .= '<p>We look forward to the possibility of working with you again in the future.</p>';

                    $mail->Body    .= '<p>Attached in this email is your digital copy of your <b>Certificate of Recognition.</b></p>';

                      $mail->Body    .= '';
                      $mail->Body    .= '';
                      $mail->Body    .= '';

                    $mail->Body    .= '<p>Best regards,</p>';
                    $mail->Body    .= '<p>From our University President, <b> DR. NORDY D. SIASON JR. CESO VI </b>';
                    $mail->Body    .= '<p>ISUFST-DINGLE CAMPUS COMMUNITY</p>';
                      $mail->SMTPOptions = ['ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true]];
                    // Attach the dynamically generated PDF content
                    $mail->addStringAttachment($pdfContent1, "certofrecognition.pdf");



                    //$mail->addStringAttachment($pdfContent2, "certappearance.pdf");

                    // Send the email
                    $mail->send();

                    // Clear the recipient and attachment for the next iteration
                    $mail->clearAddresses();
                    $mail->clearAttachments();
                    }

              }



    echo 'Emails sent successfully';
    $conn->close();

  }
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

// Function to generate PDF content using Dompdf
function generatePDFContent1($tablename,$selectedEmails) {
    try {

      date_default_timezone_set('Asia/Kolkata'); // Set your timezone
      $specificDate  = date("Y-m-d");
      $formattedDate = date("jS F Y", strtotime($specificDate));
      echo "Formatted date is: $formattedDate";
              // Create an instance of the Dompdf class
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf($options);

        /// THIS IS FO RTHECONTENT START
        foreach ($selectedEmails as $recipientEmail) {
            // ... (Other email configuration)
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "cultural";

            $conn = new mysqli($servername, $username, $password, $dbname);

            $fetchEmailsQuery = "SELECT email, firstname, lastname FROM judges WHERE email='$recipientEmail'";
            $result = $conn->query($fetchEmailsQuery);
            if ($result->num_rows > 0) {

              while ($row = $result->fetch_assoc()) {

                  $firstname = $row['firstname'];
                  $lastname = $row['lastname'];

        $bgImageStyle = 'url(\'data:image/jpeg;base64,' . base64_encode(file_get_contents('img/recognition.png')) . '\')';
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
                 img.cert{ width:100%;height:100%; }

                 body {
                   background-image: ' . $bgImageStyle . ';
                   background-size: cover;
                   background-position: center;
                   background-repeat: no-repeat;
                   margin: 0;
                   padding: 0;
               }
            </style>
        </head>
        <body>';
      //  <p style="line-height: 1; font-family:century; text-align: center; font-size: 65px; margin-bottom:28px; margin-top:27px;margin-left:-30px;">' . $firstname .' '. $lastname . ' </p>
        //<p style="line-height: 1; font-family:century; text-align: left; font-size: 18px; margin-left: 120px;">' . $tablename . ' </p>';

            }
          }
        }



        /// THIS IS FO RTHECONTENT End



        $dompdf->loadHtml($html);

        // Set paper size to legal with portrait orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
      //  $dompdf->stream('certofappreciation.pdf', array('Attachment' => 0));


        // Output PDF as a string
        return $dompdf->output();
    } catch (Exception $e) {
        return ''; // Return an empty string if PDF generation fails
    }

}


?>
