<?php
session_start();
require_once __DIR__ . '/../LoginPage/koneksi.php';

if (!isset($_SESSION['reset_verified'])) {
    header('Location: /../Permission/unauth.php');
    exit();
}

$password = trim($_POST['password'] ?? '');

if (empty($password)) {
    header('Location: /../Permission/unauth.php');
    exit();
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$update = $pdo->prepare("
    UPDATE users
    SET password = :password
    WHERE email = :email
");

$update->execute([
    ':password' => $hashedPassword,
    ':email'    => $_SESSION['reset_verified']
]);

unset($_SESSION['reset_verified']);
unset($_SESSION['reset_flow']);
unset ($_SESSION['reset_email']);
unset($_SESSION['reset_pass']);
header('Location: /../Permission/success.php?success=resetpass');
exit();