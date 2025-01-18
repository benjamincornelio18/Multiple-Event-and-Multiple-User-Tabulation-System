<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Endroid\QrCode\QrCode;

// Generate QR code
$url = 'https://drive.google.com/file/d/1uK0n-P3ObjPB0OCrt2xfcpqZ_4hD61ze/view?usp=sharing'; // Replace with your actual link
$qrCode = new QrCode($url);
$qrCode->setSize(300);

// Get QR code data URI
$qrCodeDataUri = $qrCode->writeDataUri();

// ... (PDF generation code and file path)

$mail = new PHPMailer(true);

try {
    // ... (Other email configuration)

    // Attach the PDF
    $mail->addAttachment($pdfFilePath);

    // Embed QR code data URI directly in email body
    $mail->addEmbeddedImage($qrCodeDataUri, 'qrcode', 'qrcode.png');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Subject of your email';
    $mail->Body    = '<p>Your message here.</p><img src="cid:qrcode" alt="QR Code">';

    $mail->send();

    echo 'Email sent successfully';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
