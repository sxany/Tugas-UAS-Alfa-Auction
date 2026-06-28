<?php
session_start();

// 1. KONTROL AKSES - Wajib Admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../LoginPage/login.php');
    exit;
}

require_once __DIR__ . '/../LoginPage/koneksi.php';

// Validasi parameter URL mendasar
if (!isset($_GET['aksi']) || !isset($_GET['id'])) {
    header('Location: dashboardAdmin.php');
    exit;
}

$aksi = $_GET['aksi'];
$id_barang = intval($_GET['id']);

// --- AKSI 1: UPDATE STATUS LELANG (TOGGLE) ---
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

// Contoh logika hapus di proses_aksi_admin.php
if ($aksi === 'hapus') {
    $id = $_GET['id'];
    
    // 1. Ambil nama file gambar dulu dari DB sebelum dihapus
    $stmt = $pdo->prepare("SELECT gambar FROM barang_lelang WHERE id_barang = :id");
    $stmt->execute([':id' => $id]);
    $barang = $stmt->fetch();
    
    if ($barang && !empty($barang['gambar'])) {
        $path_foto = '../DashboardUser/img/' . $barang['gambar'];
        if (file_exists($path_foto)) {
            unlink($path_foto); // <-- INI DIA! Menghapus file dari galeri server
        }
    }
    
    // 2. Baru jalankan query DELETE data di DB
    $stmt = $pdo->prepare("DELETE FROM barang_lelang WHERE id_barang = :id");
    $stmt->execute([':id' => $id]);
    
    header('Location: dashboardAdmin.php?status=delete_success');
    exit;
}

// Jika ada parameter aneh yang masuk, kembalikan ke dashboard
header('Location: dashboardAdmin.php');
exit;