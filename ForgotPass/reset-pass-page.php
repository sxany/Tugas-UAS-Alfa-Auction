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
    <title>Reset Password - Alfa Auction</title>

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

        .login-card {
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
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus {
            outline: none;
            border-color: #2563eb; /* Blue 600 */
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }

        input::placeholder {
            color: #475569; /* Slate 600 */
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #2563eb; /* Blue 600 */
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
            <label for="password">Password Baru</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Buat password baru"
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