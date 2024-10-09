<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust the path if you're not using Composer

function sendVerificationEmail($userEmail, $verificationToken) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host       = 'smtp.example.com';               // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                             // Enable SMTP authentication
        $mail->Username   = 'your_email@example.com';         // SMTP username
        $mail->Password   = 'your_email_password';            // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = 587;                              // TCP port to connect to

        // Recipients
        $mail->setFrom('your_email@example.com', 'Your Name');
        $mail->addAddress($userEmail);                        // Add a recipient

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = 'You requested a password reset. Click the link below to reset your password:<br><br>'
                        . '<a href="https://yourwebsite.com/reset-password.php?token=' . $verificationToken . '">Reset Password</a>';
        $mail->AltBody = 'You requested a password reset. Copy and paste the following URL into your browser to reset your password: https://yourwebsite.com/reset-password.php?token=' . $verificationToken;

        $mail->send();
        echo 'Verification email has been sent';
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}

// Usage
$userEmail = 'user@example.com'; // The user's email address
$verificationToken = bin2hex(random_bytes(16)); // Generate a random token for verification

sendVerificationEmail($userEmail, $verificationToken);
