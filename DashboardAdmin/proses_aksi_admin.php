<?php
session_start();

require_once __DIR__ . '/../LoginPage/koneksi.php';

if (!isset($_GET['aksi']) || !isset($_GET['id'])) {
    header('Location: dashboardAdmin.php');
    exit;
}

$aksi = $_GET['aksi'];
$id_barang = intval($_GET['id']);

if ($aksi === 'toggle_status' && isset($_GET['status_sekarang'])) {
    $status_baru = ($_GET['status_sekarang'] === 'aktif') ? 'selesai' : 'aktif';
    
    try {
        $query = "UPDATE barang_lelang SET status_lelang = :status_baru WHERE id_barang = :id_barang";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':status_baru' => $status_baru,
            ':id_barang' => $id_barang
        ]);
        
        header('Location: dashboardAdmin.php?status=update_success');
        exit;
    } catch (PDOException $e) {
        die("Gagal memperbarui status lelang: " . $e->getMessage());
    }
}

if ($aksi === 'hapus') {
    $id = $_GET['id'];
    
    $stmt = $pdo->prepare("SELECT gambar FROM barang_lelang WHERE id_barang = :id");
    $stmt->execute([':id' => $id]);
    $barang = $stmt->fetch();
    
    if ($barang && !empty($barang['gambar'])) {
        $path_foto = '../DashboardUser/img/' . $barang['gambar'];
        if (file_exists($path_foto)) {
            unlink($path_foto);
        }
    }
    
    $stmt = $pdo->prepare("DELETE FROM barang_lelang WHERE id_barang = :id");
    $stmt->execute([':id' => $id]);
    
    header('Location: dashboardAdmin.php?status=delete_success');
    exit;
}

header('Location: dashboardAdmin.php');
exit;