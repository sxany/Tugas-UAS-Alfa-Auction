<?php
session_start();
require_once __DIR__ . '/../LoginPage/koneksi.php';

if (!isset($_SESSION['reset_pass'])) 
    { header('Location: /../Permission/unauth.php?');
    exit();
}

$email = $_SESSION['reset_email'];
$otp   = trim($_POST['otp'] ?? '');

if (empty($otp)) {
    header('Location: /../Permission/unauth.php');
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
    header('Location: /../Permission/unauth.php?error=otpresetinvalid&from=reset');
    exit();
}

$delete = $pdo->prepare("
    DELETE FROM password_resets
    WHERE id = :id
");

$delete->execute([
    ':id' => $data['id']
]);

$_SESSION['reset_verified'] = $email;
header('Location: reset-pass-page.php');
exit();