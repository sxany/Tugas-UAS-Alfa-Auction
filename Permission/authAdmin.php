<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../Permission/unauth.php');
    exit;
}

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: ../Permission/unauth.php');
    exit;
}
?>