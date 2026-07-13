<?php
session_start();

require_once __DIR__ . '/../LoginPage/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_barang = trim($_POST['nama_barang']);
    $deskripsi_barang = trim($_POST['deskripsi_barang']);
    $harga_barang = floatval($_POST['harga_barang']);
    $waktu = trim($_POST['waktu']);

    if (empty($nama_barang) || empty($deskripsi_barang) || $harga_barang <= 0 || empty($waktu) ) {
        header('Location: dashboardAdmin.php?error=invalid_input');
        exit;
    }

    $nama_gambar_db = null; 

    if (isset($_FILES['foto_barang']) && $_FILES['foto_barang']['error'] === 0) {
        $nama_file_asli = $_FILES['foto_barang']['name'];
        $tmp_name = $_FILES['foto_barang']['tmp_name'];
        
        $ekstensi = strtolower(pathinfo($nama_file_asli, PATHINFO_EXTENSION));
        
        $ekstensi_valid = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ekstensi, $ekstensi_valid)) {
            $nama_gambar_db = time() . '_' . uniqid() . '.' . $ekstensi;
            
            $folder_tujuan = '../DashboardUser/img/' . $nama_gambar_db;
            
            if (!move_uploaded_file($tmp_name, $folder_tujuan)) {
                die("Gagal memindahkan file foto dari galeri ke folder server project!");
            }
        } else {
            die("Format file salah! Sistem hanya menerima ekstensi JPG, JPEG, PNG, atau WEBP.");
        }
    }

    try {
        $query = "
            INSERT INTO barang_lelang (nama_barang, deskripsi_barang, harga_barang, gambar, status_lelang, waktu) 
            VALUES (:nama, :deskripsi, :harga, :gambar, 'aktif', :waktu)
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':nama' => $nama_barang,
            ':deskripsi' => $deskripsi_barang,
            ':harga' => $harga_barang,
            ':gambar' => $nama_gambar_db,
            ':waktu' => $waktu
        ]);

        header('Location: dashboardAdmin.php?status=insert_success');
        exit;
    } catch (PDOException $e) {
        die("Gagal menyimpan komoditas lelang baru: " . $e->getMessage());
    }
} else {
    header('Location: dashboardAdmin.php');
    exit;
}