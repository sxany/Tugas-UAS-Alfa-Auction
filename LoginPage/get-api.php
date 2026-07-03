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
    header('Location: unauth.php?error=otpempty&from=otp');
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: unauth.php?error=emailinvalid&from=otp');
    exit();
}


$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
$otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

//cek users kalau ada data sama
$check = $pdo->prepare("
    SELECT *
    FROM users
    WHERE username = :username
       OR email = :email
    LIMIT 1
");

$check->execute([
    ':username' => $username,
    ':email'    => $email
]);

$tempUser = $check->fetch();

if ($tempUser) {
    header('Location: unauth.php?error=alreadyregistered&from=regis');
    exit;
}

//cek temp registrations
$check = $pdo->prepare("
    SELECT * FROM temp_registrations
    WHERE username = :username OR email = :email
    LIMIT 1
");

$check->execute([
    ':username' => $username,
    ':email'    => $email
]);

$data = $check->fetch(PDO::FETCH_ASSOC);

if ($data) {
    $sameUsername = ($data['username'] === $username);
    $sameEmail    = ($data['email'] === $email);
    $samePassword = password_verify($password, $data['password']);

    if ($sameUsername && $sameEmail && $samePassword) {
        $_SESSION['pending_email'] = $email;
        header('Location: src/verify.php');
        exit;
    }
    else if ($sameUsername) {
        header('Location: unauth.php?error=usernametaken&from=regis');
        exit;
    }
    else if ($samePassword) {
        header('Location: unauth.php?error=passwordwrong&from=regis');
        exit;
    }
    else if ($sameEmail) {
        header('Location: unauth.php?error=emailtaken&from=regis');
        exit;
    }
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO temp_registrations
        (username, email, password, role, otp)
        VALUES (:username, :email, :password, :role, :otp)
    ");

    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password' => $hashedPassword,
        ':role' => 'user',
        ':otp' => $otp
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
    ";

    $mail->send();
    $_SESSION['pending_email'] = $email;
    header('Location: src/verify.php');
    exit;
} catch (Exception $e) {
    echo $mail->ErrorInfo;
}
?>