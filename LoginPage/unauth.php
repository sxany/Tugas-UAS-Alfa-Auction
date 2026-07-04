<!-- error & redirect -->
 <?php
$error = $_GET['error'] ?? '';
$from  = $_GET['from'] ?? '';

if ($from === 'otp') {
    $target = 'src/verify.php';
} 
else if ($from === 'regis') {
    $target = 'src/index.html';
}
else if ($from === 'reset') {
    $target = 'verify-otp-reset-pass-page.php';
}
else {
    $target = 'src/login.html';
}
?> 

<!-- error visual -->
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

// list of error
// login page: empty, invalid, emailnotfound
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

// register page: usernameregistered, emailregistered table users
else if (error === "usernameregistered") {
    Swal.fire({
        icon: "error",
        title: "Registrasi Gagal",
        text: "Username sudah digunakan"
    }).then(() => {
        window.location.href = targetUrl;
    });
}
else if (error === "emailregistered") {
    Swal.fire({
        icon: "error",
        title: "Registrasi Gagal",
        text: "Email sudah digunakan"
    }).then(() => {
        window.location.href = targetUrl;
    });
}

// register page: usernametaken, emailtaken table temp_registrations
else if (error === "usernametaken") {
    Swal.fire({
        icon: "error",
        title: "Registrasi Gagal",
        text: "Username sudah digunakan"
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

// otp page: otpempty, otpinvalid, retry
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

// reset password 
else if (error === "otpresetinvalid") {
    Swal.fire({
        icon: "error",
        title: "Verifikasi Gagal",
        text: "OTP salah"
    }).then(() => {
        window.location.href = targetUrl;
    });
}
//unauthorized log
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