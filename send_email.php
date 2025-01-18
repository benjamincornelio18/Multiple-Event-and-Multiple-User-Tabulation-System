



<?php


require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ... (PDF generation code and file path)

// Get data from the form
$selectedEmails = $_POST['selected_emails'];

// Check if the attachment file was uploaded successfully
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    $attachmentFilePath = $_FILES['attachment']['tmp_name']; // Get the temporary file path of the attachment
} else {
    // Handle the case when no attachment is provided or an error occurred during upload
    echo 'Error: No attachment or upload error.';
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
        // Recipients
        $mail->setFrom('benjamincornelio@isufst.edu.ph', 'ISUFST-DINGLE CAMPUS');
        $mail->addAddress($recipientEmail);


        $mail->isHTML(true);
        $mail->Subject = 'Thank You for Your Judging Expertise';
        $mail->Body    = '<p>Dear _________ I wanted to extend my heartfelt thanks for your role as a judge at __________. </p>';

        $mail->Body    .= '<p>Your expertise and thoughtful assessments were crucial to the event success. </p>';

        $mail->Body    .= '<p>Your commitment to excellence did not go unnoticed, and we are truly grateful for your valuable contribution. </p>';
        $mail->Body    .= '<p>We look forward to the possibility of working with you again in the future.</p>';

        $mail->Body    .= '<p>Best regards,</p>';

      //  $mail->Body    .= '<p>Benjamin L. Cornelio Jr.</p>';
      //  $mail->Body    .= '<p>MIS Chair</p>';
        $mail->Body    .= '<p>ISUFST-DINGLE CAMPUS</p>';

        //$mail->Body    .= '</p><img src="cid:qrcode" alt="QR Code">';

      //  $mail->SMTPDebug = 2; // Enable debugging output
        $mail->SMTPOptions = ['ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true]];
        //$mail->send();

        // Attach the file
        $mail->addAttachment($attachmentFilePath, 'attachment.pdf');

        // Send the email
        $mail->send();

        // Clear the recipient and attachment for the next iteration
        $mail->clearAddresses();
        $mail->clearAttachments();
    }

    echo 'Emails sent successfully';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
