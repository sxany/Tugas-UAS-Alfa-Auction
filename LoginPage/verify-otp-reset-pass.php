<?php
session_start();
require_once __DIR__ . '/koneksi.php';

// Pastikan berasal dari proses forgot password
if (!isset($_SESSION['reset_pass'])) 
    { header('Location: unauth.php?');
    exit();
}

$email = $_SESSION['reset_email'];
$otp   = trim($_POST['otp'] ?? '');

if (empty($otp)) {
    header('Location: unauth.php');
    exit();
}

$stmt = $pdo->prepare("
    SELECT *
    FROM password_resets
    WHERE email = :email
      AND otp = :otp
    LIMIT 1
");

$stmt->execute([
    ':email' => $email,
    ':otp'   => $otp
]);

$data = $stmt->fetch();

if (!$data) {
    header('Location: unauth.php?error=otpresetinvalid&from=reset');
    exit();
}

$delete = $pdo->prepare("
    DELETE FROM password_resets
    WHERE id = :id
");

$delete->execute([
    ':id' => $data['id']
]);

unset($_SESSION['reset_pass']);
$_SESSION['reset_verified'] = $email;
header('Location: reset-pass-page.php');
exit();