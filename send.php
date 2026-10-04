<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

// Set header so the browser handles this as JSON
header('Content-Type: application/json');

// Check if vendor/autoload.php exists (Composer install)
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
} 
// Fallback: Check if PHPMailer was downloaded manually without Composer
elseif (file_exists(__DIR__ . '/PHPMailer/src/PHPMailer.php')) {
    require __DIR__ . '/PHPMailer/src/Exception.php';
    require __DIR__ . '/PHPMailer/src/PHPMailer.php';
    require __DIR__ . '/PHPMailer/src/SMTP.php';
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'PHPMailer files not found! Please install via Composer or download PHPMailer.'
    ]);
    exit;
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email   = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));

    // Validate the form data
    if (empty($name) || !$email || empty($message)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Please fill in all fields with a valid email!'
        ]);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // --- Server Settings ---
        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->Port       = 587;
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Username   = 'mail_3@pbodavaodelsur.com';
        $mail->Password   = 'It10@2026';
        
        // --- Sender & Recipients ---
        $mail->setFrom('mail_3@pbodavaodelsur.com', 'Portfolio Contact Form');
        $mail->addAddress('a.albano.65100.dc@umindanao.edu.ph', 'Asshlene Nicole');
        $mail->addReplyTo($email, $name); // Clicking 'Reply' in your inbox goes directly to the sender!
        
        // --- Message Content ---
        $mail->isHTML(true);
        $mail->Subject = "New Portfolio Message from $name ✨";
        
        // Styled Pink HTML Email Body
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; padding: 24px; background-color: #fff0f3; border-radius: 16px; color: #2d3748;'>
                <h2 style='color: #ff7597; margin-top: 0;'>New Contact Form Submission 💖</h2>
                <hr style='border: none; border-top: 1px solid #ffccd5; margin-bottom: 20px;'>
                <p style='margin-bottom: 8px;'><strong>From:</strong> {$name}</p>
                <p style='margin-bottom: 16px;'><strong>Email:</strong> <a href='mailto:{$email}' style='color: #ff7597;'>{$email}</a></p>
                <p style='margin-bottom: 8px;'><strong>Message:</strong></p>
                <div style='background: #ffffff; padding: 18px; border-radius: 12px; border: 1px solid #ffccd5; line-height: 1.6;'>
                    " . nl2br($message) . "
                </div>
            </div>
        ";

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
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}