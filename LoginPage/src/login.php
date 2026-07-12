<?php
session_start();

function redirectByRole($role) {
    if ($role === 'admin') {
        header('Location: /../DashboardAdmin/dashboardAdmin.php');
    } else {
        header('Location: /../DashboardUser/dashboardUser.php');
    }
    exit;
}

if (isset($_SESSION['user'])) {
    redirectByRole($_SESSION['user']['role']);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.html');
    exit;
}

require_once __DIR__ . '/../koneksi.php';

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');


if (empty($username) || empty($password)) {
    header('Location: /../Permission/unauth.php?error=empty');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
$stmt->execute([':username' => $username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    header('Location: /../Permission/unauth.php?error=invalid');
    exit;
}

$_SESSION['user'] = [
    'id_user'  => $user['id_user'],
    'username' => $user['username'],
    'email'    => $user['email'],
    'role'     => $user['role'],
];

redirectByRole($_SESSION['user']['role']);
?>