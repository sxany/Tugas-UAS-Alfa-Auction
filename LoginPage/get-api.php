<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/koneksi.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) || empty($email) || empty($password)) {
    die('Semua field wajib diisi');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('Email tidak valid');
}

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
$otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
$otpExpires = date('Y-m-d H:i:s', strtotime('+5 minutes'));

try {
    $stmt = $pdo->prepare("
        INSERT INTO temp_registrations
        (username, email, password, role, otp, otp_expires)
        VALUES (:username, :email, :password, :role, :otp, :otp_expires)
    ");

    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password' => $hashedPassword,
        ':role' => 'user',
        ':otp' => $otp,
        ':otp_expires' => $otpExpires
    ]);
} catch (PDOException $e) {
    die($e->getMessage());
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'putuprawira425@gmail.com';
    $mail->Password = 'iaba oktl ilel ckxi';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('putuprawira425@gmail.com', 'Sistem Pendaftaran');
    $mail->addAddress($email, $username);

    $mail->isHTML(true);
    $mail->Subject = "Kode OTP Verifikasi";

    $mail->Body = "
        <h2>Halo {$username}</h2>
        <p>Kode OTP kamu:</p>
        <h1>{$otp}</h1>
        <p>Berlaku 5 menit.</p>
    ";

    $mail->send();
    $_SESSION['pending_email'] = $email;
header('Location: src/verify-otp.html');
exit;
} catch (Exception $e) {
    echo $mail->ErrorInfo;
}
?>