<?php
session_start();

// 1. KEAMANAN & AKSES KONTROL (RBAC)
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../LoginPage/login.php');
    exit;
}

// Hubungkan ke database
require_once __DIR__ . '/../LoginPage/koneksi.php';

try {
    // 2. QUERY TAMPILKAN BARANG YANG SUDAH DIBUAT OLEH ADMIN
    $query = "
        SELECT 
            bl.id_barang, 
            bl.nama_barang, 
            bl.harga_barang AS harga_awal,
            bl.status_lelang,
            IFNULL(MAX(b.harga_tawar), bl.harga_barang) AS harga_berjalan,
            COUNT(b.id_bid) AS total_penawaran
        FROM barang_lelang bl
        LEFT JOIN bid b ON bl.id_barang = b.barang_id
        GROUP BY bl.id_barang
        ORDER BY bl.id_barang DESC
    ";
    $stmt = $pdo->query($query);
    $daftar_barang = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Eror mengambil data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alfa Auction - Dashboard Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- NAVBAR -->
    <nav class="bg-slate-800 text-white p-4 shadow-md flex justify-between items-center">
        <h1 class="text-xl font-bold tracking-wide">🛡️ Alfa Auction Admin Panel</h1>
        <div class="flex items-center gap-4">
            <span class="font-medium text-slate-300">Login sebagai: <strong class="text-white"><?php echo htmlspecialchars($_SESSION['user']['username']); ?></strong></span>
            <a href="../LoginPage/logout.php" class="bg-red-500 hover:bg-red-600 text-sm px-3 py-1.5 rounded transition">Logout</a>
        </div>
    </nav>

    <!-- KONTEN UTAMA -->
    <main class="max-w-6xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- FORM TAMBAH BARANG -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 lg:col-span-1 h-fit">
            <h2 class="text-xl font-bold mb-4 text-gray-700 border-b border-gray-100 pb-2">Tambah Event Lelang</h2>
            
            <form action="proses_tambah_barang.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Nama Barang</label>
                    <input type="text" name="nama_barang" placeholder="Contoh: PC Gaming Asus TUF" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-slate-500 focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Deskripsi Barang</label>
                    <textarea name="deskripsi_barang" rows="3" placeholder="Detail spesifikasi barang..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-slate-500 focus:outline-none" required></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Harga Awal (Rp)</label>
                    <input type="number" name="harga_barang" placeholder="1000000" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-slate-500 focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Kategori Barang</label>
                    <select name="kategori" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-slate-500 focus:outline-none">
                        <option value="Elektronik">Elektronik & Gadget</option>
                        <option value="Gaming">Gaming Gear</option>
                        <option value="Fashion">Fashion & Apparel</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Foto Barang</label>
                    <input type="file" name="foto_barang" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200" required>
                </div>

                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-medium py-2 rounded-lg transition duration-200 text-sm shadow">
                    🚀 Publikasikan Lelang
                </button>
            </form>
        </div>

        <!-- TABEL MONITORING -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 lg:col-span-2">
            <h2 class="text-xl font-bold mb-4 text-gray-700 border-b border-gray-100 pb-2">Monitoring Live Lelang</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 text-sm uppercase font-semibold border-b border-gray-200">
                            <th class="py-3 px-4">Nama Barang</th>
                            <th class="py-3 px-4">Harga Awal</th>
                            <th class="py-3 px-4">Bid Tertinggi</th>
                            <th class="py-3 px-4">Info</th>
                            <th class="py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        <?php if (empty($daftar_barang)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-400">Belum ada event lelang aktif yang kamu buat.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($daftar_barang as $item): ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 px-4 font-medium text-gray-900"><?php echo htmlspecialchars($item['nama_barang']); ?></td>
                                    <td class="py-3 px-4 text-gray-600">Rp <?php echo number_format($item['harga_awal'], 0, ',', '.'); ?></td>
                                    <td class="py-3 px-4 font-bold text-blue-600">Rp <?php echo number_format($item['harga_berjalan'], 0, ',', '.'); ?></td>
                                    <td class="py-3 px-4 text-xs text-gray-500">🔥 <?php echo $item['total_penawaran']; ?> Bid masuk</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                            <?php echo strtoupper($item['status_lelang']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

</body>
</html>