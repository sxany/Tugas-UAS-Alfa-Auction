<?php
session_start();
if (!isset($_SESSION['reset_pass'])) {
    header('Location: ../Permission/unauth.php');
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - Alfa Auction</title>
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
            background-color: #020617; /* Slate 950 */
            font-family: sans-serif;
        }

        .otp-card {
            background-color: #0f172a; /* Slate 900 */
            padding: 40px 36px;
            border-radius: 12px;
            border: 1px solid #1e293b; /* Slate 800 */
            width: 340px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
        }

        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #f8fafc; /* Slate 50 */
            text-align: center;
            margin-bottom: 6px;
        }

        .subtitle {
            font-size: 13px;
            color: #64748b; /* Slate 500 */
            text-align: center;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .input-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #94a3b8; /* Slate 400 */
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        input {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #1e293b; /* Slate 800 */
            border-radius: 8px;
            font-size: 14px;
            color: #ffffff;
            background: #020617; /* Slate 950 */
            text-align: center; /* Membuat input token/OTP berada di tengah agar lebih rapi */
            letter-spacing: 0.1em;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus {
            outline: none;
            border-color: #2563eb; /* Blue 600 */
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }

        input::placeholder {
            color: #475569; /* Slate 600 */
            letter-spacing: normal;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #2563eb; /* Blue 600 Aksen Utama */
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 6px;
            transition: background-color 0.2s, transform 0.1s;
        }

        button:hover {
            background-color: #1d4ed8; /* Blue 700 */
        }

        button:active {
            transform: scale(0.98);
        }

        p {
            margin-top: 24px;
            text-align: center;
            font-size: 13px;
            color: #64748b; /* Slate 500 */
        }

        a {
            color: #3b82f6; /* Blue 500 */
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        a:hover {
            color: #60a5fa; /* Blue 400 */
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="otp-card">
    <h1>Verifikasi OTP</h1>
    <p class="subtitle">
        Masukkan kode verifikasi OTP yang telah dikirim ke email Anda.
    </p>
    
    <form action="verify-otp-reset-pass.php" method="POST">
        <div class="input-group">
            <label for="otp">Kode Verifikasi</label>
            <input
                type="text"
                id="otp"
                name="otp"
                placeholder="Masukkan OTP"
                required>
        </div>
        <button type="submit">Verifikasi Akun</button>
    </form>
</div>
</body>
</html>