<?php
$error = $_GET['error'] ?? 'unauth';
$from  = $_GET['from'] ?? '';

if ($from === 'otp') {
    $target = 'src/verify.php';
} 
else if ($from === 'regis') {
    $target = 'src/index.html';
}
else {
    $target = 'src/login.html';
}
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
else if (error === "pending") {
    Swal.fire({
        icon: "warning",
        title: "Verifikasi Diperlukan",
        text: "Silakan verifikasi email Anda terlebih dahulu"
    }).then(() => {
        window.location.href = targetUrl;
    });
}
else if (error === "alreadyregistered") {
    Swal.fire({
        icon: "error",
        title: "Registrasi Gagal",
        text: "Username atau Email sudah terdaftar"
    }).then(() => {
        window.location.href = targetUrl;
    });
}
else if (error === "usernametaken") {
    Swal.fire({
        icon: "error",
        title: "Registrasi Gagal",
        text: "Username sudah digunakan"
    }).then(() => {
        window.location.href = targetUrl;
    });
}
else if (error === "passwordwrong") {
    Swal.fire({
        icon: "error",
        title: "Verifikasi Gagal",
        text: "Password salah"
    }).then(() => {
        window.location.href = targetUrl;
    });
}
else if (error === "emailtaken") {
    Swal.fire({
        icon: "error",
        title: "Registrasi Gagal",
        text: "Email sudah digunakan"
    }).then(() => {
        window.location.href = targetUrl;
    });
}
else if (error === "retry") {
    Swal.fire({
        icon: "warning",
        title: "Unauthorized",
        text: "Silakan lakukan registrasi ulang"
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
        title: "Verifikasi Gagal",
        text: "OTP salah"
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