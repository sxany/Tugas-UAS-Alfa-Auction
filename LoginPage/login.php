<?php

session_start();

// 1. EDIT DI SINI: Cek session di awal agar redirect sesuai role jika sudah login
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] === 'admin') {
        header('Location: ../DashboardAdmin/dashboardAdmin.php');
    } else {
        header('Location: ../DashboardUser/dashboardUser.php');
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: src/login.html');
    exit;
}

require_once __DIR__ . '/koneksi.php';

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');


if (empty($username) || empty($password)) {
    header('Location: src/login.html?error=empty');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
$stmt->execute([':username' => $username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    header('Location: ../src/login.html?error=invalid');
    exit;
}

$_SESSION['user'] = [
    'id'       => $user['id'],
    'username' => $user['username'],
    'email'    => $user['email'],
    'role'     => $user['role'],
];

// 2. EDIT DI SINI: Logika percabangan setelah berhasil login
if ($user['role'] === 'admin') {
    // Jika rolenya admin, lempar ke dashboardAdmin.php
    header('Location: ../DashboardAdmin/dashboardAdmin.php');
} else {
    // Jika rolenya user, lempar ke dashboardUser.php seperti bawaan sebelumnya
    header('Location: ../DashboardUser/dashboardUser.php');
}
exit;