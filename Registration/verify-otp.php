<?php
session_start();
require_once __DIR__ . '/../LoginPage/koneksi.php';

$email = $_SESSION['pending_email'] ?? null;
$otp   = trim($_POST['otp'] ?? '');

if (!$email) {
    header('Location: /../Permission/unauth.php?error=emailnotfound&from=otp');
    die();
}

if (empty($otp)) {
    header('Location: /../Permission/unauth.php?error=otpempty&from=otp');
    die();
}

$stmt = $pdo->prepare("
    SELECT * FROM temp_registrations
    WHERE email = :email
    AND otp = :otp
    LIMIT 1
");

$stmt->execute([
    ':email' => $email,
    ':otp'   => $otp
]);

$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    header('Location: /../Permission/unauth.php?error=otpinvalid&from=otp');
    die();
}
try {

    $insert = $pdo->prepare("
        INSERT INTO users (username, email, password, role)
        VALUES (:username, :email, :password, :role)
    ");

    $insert->execute([
        ':username' => $data['username'],
        ':email'    => $data['email'],
        ':password' => $data['password'],
        ':role'     => $data['role']
    ]);

    $userId = $pdo->lastInsertId();

    $delete = $pdo->prepare("
        DELETE FROM temp_registrations
        WHERE id = :id
    ");

    $delete->execute([
        ':id' => $data['id']
    ]);

    
    unset($_SESSION['pending_email']);
    
    $_SESSION['user'] = [
        'id'       => $userId,
        'username' => $data['username'],
        'email'    => $data['email'],
        'role'     => $data['role']
    ];
header("Location: /../Permission/success.php?success=verified");
exit();
    
} catch (PDOException $e) {
    die($e->getMessage());
}
?>