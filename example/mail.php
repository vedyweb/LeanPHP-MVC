<?php

use LeanPHP\Core\EmailService;

// EmailService sınıfını başlat
$emailService = new EmailService();

// Alıcı e-posta adresi
$recipientEmail = "user@example.com";

// E-posta konusu
$emailSubject = "Password Reset";

// E-posta içeriği
$emailBodyContent = '
<h1>Password Reset Request</h1>
<p>You have requested to reset your password.</p>
<p>Please click <a href="https://example.com/reset-password?token=xyz">here</a> to reset your password.</p>
';

try {
    // E-postayı gönder
    $result = $emailService->sendEmail($recipientEmail, $emailSubject, $emailBodyContent);
    echo $result;  // Başarı mesajını yazdır
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();  // Hata mesajını yazdır
}

/* TOPLU MAİL

// Alıcı listesi
$recipients = ['user1@example.com', 'user2@example.com', 'user3@example.com'];

// E-posta konusu ve içeriği
$subject = "Monthly Newsletter";
$bodyContent = "<h1>Our Latest News</h1><p>Check out our latest updates...</p>";

// Her bir alıcıya e-posta gönder
foreach ($recipients as $recipient) {
    try {
        $emailService->sendEmail($recipient, $subject, $bodyContent);
        echo "Mail sent to: $recipient<br>";
    } catch (Exception $e) {
        echo 'Error sending to ' . $recipient . ': ' . $e->getMessage() . "<br>";
    }
}

*/