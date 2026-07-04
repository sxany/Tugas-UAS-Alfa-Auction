<?php
$success = $_GET['success']??'';
$from  = $_GET['from'] ?? '';

if ($from === 'otp') {
    $target = 'src/verify.php';
} 
else {
    $target = 'src/login.html';
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Success</title>
    <style>
        body { margin: 0; background: #f0f2f5; }
    </style>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const success = "<?= htmlspecialchars($success) ?>";
const targetUrl = "<?= $target ?>";

// list of success
if (success === "resend") {
    Swal.fire({
        icon: "success",
        title: "OTP Terkirim",
        text: "Silakan cek email Anda untuk mendapatkan OTP baru"
    }).then(() => {
        window.location.href = targetUrl;
    });
}
else if (success === "verified") {
    Swal.fire({
        icon: "success",
        title: "Verifikasi Berhasil",
        text: "Akun Anda telah berhasil diverifikasi."
    }).then(() => {
        window.location.href = targetUrl;
    });
}
</script>
</body>
</html>