<?php

// Include the necessary libraries
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;
use Dompdf\Options;
use TCPDF;

function generatePDFContent2($tablename, $selectedEmails) {
    try {
        date_default_timezone_set('Asia/Kolkata'); // Set your timezone
        $specificDate = date("Y-m-d");
        $formattedDate = date("jS F Y", strtotime($specificDate));

        // Create an instance of the Dompdf class
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf($options);

        // Content to be included in the PDF (HTML)
        $html = ''; // Add your HTML content here

        // Replace the following with actual content generation, based on your needs
        foreach ($selectedEmails as $recipientEmail) {
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

                    // Add dynamic content to the HTML
                    $html .= '
                    <html>
                    <head>
                        <style>
                            body { font-family: Arial, sans-serif; }
                            header, footer { padding: 0.5px; text-align: center; background-color: #f2f2f2; }
                            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                            th, td { border: 1px solid #dddddd; padding: 8px; text-align:center; }
                            th { background-color: #f2f2f2; }
                            img.isufst { width: 120px; float: left; margin-left: 2px; margin-top: -8px; }
                            img.caring { width: 100px; float: right; margin-right: 2px; margin-top: -10px; }
                            img.cert { width: 100%; height: 100%; }

                            body {
                                background-image: url(\'data:image/jpeg;base64,' . base64_encode(file_get_contents('img/appearance.png')) . '\');
                                background-size: cover;
                                background-position: center;
                                background-repeat: no-repeat;
                                margin: 0;
                                padding: 0;
                            }
                        </style>
                    </head>
                    <body>
                    <p style="line-height: 1; font-family:century; text-align: center; font-size: 65px; margin-bottom:25px; margin-top: 278px; margin-left: -30px;">' . $firstname .' '. $lastname . '</p>
                    </body>
                    </html>';
                }
            }
            $conn->close();
        }

        // Load HTML content into Dompdf
        $dompdf->loadHtml($html);

        // Set paper size and orientation (A4, landscape)
        $dompdf->setPaper('A4', 'landscape');

        // Render the PDF
        $dompdf->render();

        // Output PDF to string
        $pdfContent = $dompdf->output();

        // Now apply the password protection using TCPDF
        $tcpdf = new TCPDF();
        $tcpdf->SetCreator('TCPDF');
        $tcpdf->SetAuthor('Your Organization');
        $tcpdf->SetTitle('Certificate of Appearance');
        $tcpdf->SetSubject('Subject of the PDF');

        // Add a page to TCPDF
        $tcpdf->AddPage();

        // Import the generated PDF content into TCPDF
        $tcpdf->setSourceFile(StreamReader::createFromString($pdfContent));
        $tplIdx = $tcpdf->importPage(1);
        $tcpdf->useTemplate($tplIdx);

        // Set password protection
        $password = 'your_secure_password'; // Set your desired password here
        $tcpdf->SetProtection(['print'], $password);

        // Output the final PDF with password protection
        $protectedPdf = $tcpdf->Output('protected_certappearance.pdf', 'S');

        return $protectedPdf;
    } catch (Exception $e) {
        return ''; // Return an empty string if PDF generation fails
    }
}

// Sending email with the protected PDF
$mail = new PHPMailer(true);
try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@example.com';
    $mail->Password = 'your_email_password';
    $mail->SMTPSecure = 'tls';

    // Get data from the form
    $selectedEmails = $_POST['selected_emails'];

    // Loop through selected email addresses and send the email
    foreach ($selectedEmails as $recipientEmail) {
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

                // Prepare email
                $mail->setFrom('your_email@example.com', 'Your Name');
                $mail->addAddress($recipientEmail);
                $mail->isHTML(true);
                $mail->Subject = 'Your Certificate of Appearance';
                $mail->Body = "<p>Dear $firstname $lastname,</p><p>Your certificate is attached.</p>";

                // Attach the protected PDF
                $pdfContent = generatePDFContent2('Your Table Name', $selectedEmails);
                $mail->addStringAttachment($pdfContent, 'certappearance.pdf');

                // Send the email
                $mail->send();

                // Clear the recipient and attachment for the next iteration
                $mail->clearAddresses();
                $mail->clearAttachments();
            }
        }

        $conn->close();
    }

    echo 'Emails sent successfully!';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
