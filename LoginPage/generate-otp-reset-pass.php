    <?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/koneksi.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = trim($_POST['email'] ?? '');

if (empty($email)) {
    header('Location: unauth.php');
    exit();
}

// Cek apakah email terdaftar
$stmt = $pdo->prepare("
    SELECT username
    FROM users
    WHERE email = :email
    LIMIT 1
");

$stmt->execute([
    ':email' => $email
]);

$user = $stmt->fetch();

if (!$user) {
    header('Location: unauth.php');
    exit();
}

$username = $user['username'];

$_SESSION['reset_email'] = $email;
$_SESSION['reset_flow'] = true;

// Generate OTP
$otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

// Hapus OTP lama jika ada
$delete = $pdo->prepare("
    DELETE FROM password_resets
    WHERE email = :email
");

$delete->execute([
    ':email' => $email
]);

// Simpan OTP baru
$insert = $pdo->prepare("
    INSERT INTO password_resets (email, otp)
    VALUES (:email, :otp)
");

$insert->execute([
    ':email' => $email,
    ':otp'   => $otp
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

    $mail->setFrom('putuprawira425@gmail.com', 'Sistem Reset Password');
    $mail->addAddress($email, $username);

    $mail->isHTML(true);
    $mail->Subject = 'Kode OTP Reset Password';

    $mail->Body = "
        <h2>Halo, {$username}</h2>

        <p>Berikut adalah kode OTP untuk reset password akun Anda:</p>

        <h1 style='letter-spacing:4px;'>{$otp}</h1>

        <p>Masukkan kode OTP tersebut pada halaman verifikasi.</p>

        <p>Apabila Anda tidak meminta reset password, abaikan email ini.</p>
    ";

    $mail->send();
    $_SESSION['reset_pass'] = 'resetpass';
    header('Location: verify-otp-reset-pass-page.php');
    exit();

} catch (Exception $e) {
    echo "Gagal mengirim email: " . $mail->ErrorInfo;
}
?>