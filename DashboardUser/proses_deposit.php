<?php
session_start();


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header('Location: ../LoginPage/src/login.php');
    exit;
}

require_once __DIR__ . '/../LoginPage/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id_user = $_SESSION['user']['id_user']; 
    $nominal = isset($_POST['nominal']) ? floatval($_POST['nominal']) : 0;

    if ($nominal < 10000) {
        header('Location: dashboardUser.php?tab=deposit&status=nominal_low');
        exit;
    }

    try {
        $query = "UPDATE users SET saldo = saldo + :nominal WHERE id_user = :id_user";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':nominal' => $nominal,
            ':id_user' => $id_user
        ]);

        header("Location: dashboardUser.php?tab=deposit&status=success&amount=" . $nominal);
        exit;

    } catch (PDOException $e) {
        echo "<pre style='color: white; background: red; padding: 20px; font-family: monospace;'>";
        echo "=== ERROR DATABASE ===" . PHP_EOL;
        echo "Pesan Error: " . $e->getMessage() . PHP_EOL;
        echo "ID User saat ini: " . var_export($id_user, true) . PHP_EOL;
        echo "Nominal input: " . var_export($nominal, true) . PHP_EOL;
        echo "</pre>";
        die();
    }
} else {
    header('Location: dashboardUser.php');
    exit;
}