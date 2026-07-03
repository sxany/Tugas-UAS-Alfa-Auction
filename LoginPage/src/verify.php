<?php
session_start();
if (!isset($_SESSION['pending_email'])) {
    header('Location: unauth.php?error=retry&from=regis');
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f5f5f5;
            font-family: sans-serif;
        }

        .otp-card {
            background-color: #ffffff;
            padding: 40px 36px;
            border-radius: 12px;
            border: 1px solid #e8e8e8;
            width: 320px;
        }

        h1 {
            font-size: 22px;
            font-weight: 500;
            color: #111111;
            text-align: center;
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 14px;
            color: #999999;
            text-align: center;
            margin-bottom: 28px;
            line-height: 1.5;
        }

        .input-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #555555;
            margin-bottom: 6px;
        }

        input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 11px;
            background-color: #111111;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            margin-top: 8px;
        }

        button:hover {
            background-color: #333333;
        }

        a {
            color: #111111;
            text-decoration: none;
            font-weight: 600;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="otp-card">
        <h1>Verifikasi OTP</h1>
        <p class="subtitle">Masukkan kode OTP yang telah dikirim ke email kamu</p>
        <form action="../verify-otp.php" method="POST">
            <div class="input-group">
                <label for="otp">OTP:</label>
                <input type="text" id="otp" name="otp" placeholder="Masukkan OTP" required>
            </div>
            <button type="submit">Verifikasi</button>
        </form>
    </div>
</body>
</html>