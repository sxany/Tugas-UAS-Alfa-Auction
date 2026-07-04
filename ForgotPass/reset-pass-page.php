<?php
session_start();

if (!isset($_SESSION['reset_verified'])) {
    header('Location: /../Permission/unauth.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

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

        .login-card {
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

        input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            color: #111111;
            background: #ffffff;
        }

        input:focus {
            outline: none;
            border-color: #aaaaaa;
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
    </style>
</head>
<body>
<div class="login-card">
    <h1>Reset Password</h1>
    <p class="subtitle">
        Masukkan password baru untuk akun Anda.
    </p>
    <form action="reset-pass.php" method="POST">
        <div class="input-group">
            <input
                type="password"
                name="password"
                placeholder="Password Baru"
                required
            >
        </div>
        <button type="submit">
            Simpan Password
        </button>
    </form>
</div>
</body>
</html>