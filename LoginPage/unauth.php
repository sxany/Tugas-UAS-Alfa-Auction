<?php
$error = $_GET['error'] ?? 'unauth';
$from  = $_GET['from'] ?? '';

$target = ($from === 'otp')
    ? 'src/verify-otp.html'
    : 'src/login.html';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Error</title>
    <style>
        body { margin: 0; background: #f0f2f5; }
    </style>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const error = "<?= htmlspecialchars($error) ?>";
const targetUrl = "<?= $target ?>";


if (error === "empty") {
    Swal.fire({
        icon: "warning",
        title: "Form Kosong",
        text: "Username dan password wajib diisi"
    }).then(() => {
        window.location.href = targetUrl;
    });
}
else if (error === "invalid") {
    Swal.fire({
        icon: "error",
        title: "Login Gagal",
        text: "Username atau password salah"
    }).then(() => {
        window.location.href = targetUrl;
    });
}
else if (error === "emailnotfound") {
    Swal.fire({
        icon: "error",
        title: "Login Gagal",
        text: "Email tidak ditemukan di session"
    }).then(() => {
        window.location.href = targetUrl;
    });
}
else if (error === "otpempty") {
    Swal.fire({
        icon: "warning",
        title: "Form Kosong",
        text: "OTP wajib diisi"
    }).then(() => {
        window.location.href = targetUrl;
    });
}
else if (error === "otpinvalid") {
    Swal.fire({
        icon: "error",
        title: "Login Gagal",
        text: "OTP salah"
    }).then(() => {
        window.location.href = targetUrl;
    });
}
else if (error === "otpexpired") {
    Swal.fire({
        icon: "error",
        title: "Login Gagal",
        text: "OTP expired"
    }).then(() => {
        window.location.href = targetUrl;
    });
}
else {
    Swal.fire({
        icon: "warning",
        title: "Unauthorized",
        text: "Silakan login terlebih dahulu"
    }).then(() => {
        window.location.href = targetUrl;
    });
}



</script>
</body>
</html>