<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '/vendor/autoload.php';

// Set header so the browser handles this as JSON
header('Content-Type: application/json');

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars($_POST['message'] ?? '');

    // Validate the form data
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Please fill in all fields with a valid email!'
        ]);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // tls
        $mail->Username = 'mail_3@pbodavaodelsur.com';
        $mail->Password = 'It10@2026';
        
        // setFrom must match the authenticated Username on Hostinger
        $mail->setFrom('mail_3@pbodavaodelsur.com', 'Portfolio Contact Form'); 
        $mail->addAddress('a.albano.65100.dc@umindanao.edu.ph'); // recipient email
        
        $mail->isHTML(true);
        $mail->Subject = 'Portfolio Contact Message';
        $mail->Body = '<p><strong>Name:</strong> ' . $name . '</p>' .
                      '<p><strong>Email:</strong> ' . $email . '</p>' .
                      '<p><strong>Message:</strong><br>' . nl2br($message) . '</p>';
        $mail->AltBody = "Name: $name\nEmail: $email\nMessage:\n$message";

        if ($mail->send()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Your message was sent successfully! I will get back to you soon 💖'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Email could not be sent at this time.'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mailer Error: ' . $mail->ErrorInfo
        ]);
    }
}