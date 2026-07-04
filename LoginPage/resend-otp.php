<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/koneksi.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['pending_email'])) {
    header('Location: unauth.php?error=emailnotfound&from=otp');
    exit();
}

$email = $_SESSION['pending_email'];

// Cari data user yang masih pending
$stmt = $pdo->prepare("
    SELECT username
    FROM temp_registrations
    WHERE email = :email
    LIMIT 1
");

$stmt->execute([
    ':email' => $email
]);

$data = $stmt->fetch();

if (!$data) {
    header('Location: unauth.php?error=emailnotfound&from=otp');
    exit();
}

$username = $data['username'];

$otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

// Update OTP 
$update = $pdo->prepare("
    UPDATE temp_registrations
    SET otp = :otp
    WHERE email = :email
");

$update->execute([
    ':otp'   => $otp,
    ':email' => $email
]);

$mail = new PHPMailer(true);

try {

    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'putuprawira425@gmail.com';
    $mail->Password   = 'iaba oktl ilel ckxi';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom('putuprawira425@gmail.com', 'Sistem Pendaftaran');
    $mail->addAddress($email, $username);

    $mail->isHTML(true);
    $mail->Subject = 'Kode OTP Baru';

    $mail->Body = "
        <h2>Halo, {$username}</h2>

        <p>Berikut adalah kode OTP terbaru kamu:</p>

        <h1 style='letter-spacing:4px;'>{$otp}</h1>

        <p>Masukkan kode ini pada halaman verifikasi.</p>
    ";

    $mail->send();

    header('Location: success.php?success=resend&from=otp');
    exit();

} catch (Exception $e) {
    echo "Gagal mengirim email: " . $mail->ErrorInfo;
}