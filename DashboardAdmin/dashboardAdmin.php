<?php
require_once '../Permission/authAdmin.php';

require_once __DIR__ . '/../LoginPage/koneksi.php';

try {
    $query = "
        SELECT 
            bl.id_barang, 
            bl.nama_barang, 
            bl.harga_barang AS harga_awal,
            bl.status_lelang,
            bl.gambar,
            IFNULL(MAX(b.harga_tawar), bl.harga_barang) AS harga_tertinggi,
            COUNT(b.id_bid) AS total_bidder
        FROM barang_lelang bl
        LEFT JOIN bid b ON bl.id_barang = b.barang_id
        GROUP BY bl.id_barang
        ORDER BY bl.id_barang DESC
    ";
    $stmt = $pdo->query($query);
    $semua_barang = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Eror mengambil data dashboard admin: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alfa Auction - Admin Ultra Dark Navy Panel</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
   <script>
    function setDurasi(jam, el) {
        const sekarang = new Date();
        sekarang.setHours(sekarang.getHours() + jam);

        // Gunakan waktu lokal bukan UTC
        const tahun  = sekarang.getFullYear();
        const bulan  = String(sekarang.getMonth() + 1).padStart(2, '0');
        const hari   = String(sekarang.getDate()).padStart(2, '0');
        const hour   = String(sekarang.getHours()).padStart(2, '0');
        const menit  = String(sekarang.getMinutes()).padStart(2, '0');
        const detik  = String(sekarang.getSeconds()).padStart(2, '0');

        const waktu = `${tahun}-${bulan}-${hari} ${hour}:${menit}:${detik}`;

        document.getElementById('inputWaktu').value = waktu;
        document.getElementById('previewWaktu').innerText = `⏳ Berakhir: ${waktu}`;

        // Highlight tombol terpilih (Versi Gelap)
        document.querySelectorAll('.btn-durasi').forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-500');
            btn.classList.add('bg-slate-900', 'text-slate-300', 'border-slate-800');
        });
        el.classList.remove('bg-slate-900', 'text-slate-300', 'border-slate-800');
        el.classList.add('bg-blue-600', 'text-white', 'border-blue-500');
    }
</script>

</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col font-sans">

    <nav class="bg-slate-950 border-b border-slate-900 text-white p-4 shadow-lg flex justify-between items-center z-10">
        <div class="flex items-center gap-2">
            <h1 class="text-xl font-bold tracking-wide flex items-center gap-1">⚡ Alfa Auction</h1>
            <span class="bg-slate-900 text-blue-400 border border-slate-800 text-[10px] uppercase tracking-wider px-2 py-0.5 rounded-md font-bold">Admin Panel</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="font-medium text-slate-400 text-sm">Operator: <strong class="text-white"><?php echo htmlspecialchars($_SESSION['user']['username']); ?></strong></span>
            <a href="../LoginPage/src/logout.php" class="bg-rose-600 hover:bg-rose-700 text-white text-xs px-3 py-1.5 rounded-xl transition duration-200 shadow-sm">Logout</a>
        </div>
    </nav>

    <main class="flex-1 p-6 bg-slate-950 grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-1">
            <div class="bg-slate-900 text-slate-100 rounded-2xl p-6 shadow-xl sticky top-6 border border-slate-800">
                <h3 class="text-base font-bold text-slate-100 mb-4 tracking-wide flex items-center gap-2 border-b border-slate-800 pb-3">
                    <span class="h-2 w-2 rounded-full bg-blue-500 animate-pulse"></span> ➕ Tambah Komoditas Lelang
                </h3>

                <!-- NOTIFIKASI BANNER (DISESUAIKAN GELAP) -->
                <?php if (isset($_GET['status'])): ?>
                    <?php if ($_GET['status'] === 'insert_success'): ?>
                        <div class="bg-emerald-950/50 border border-emerald-800 text-emerald-400 text-xs px-4 py-3 rounded-xl mb-4 font-medium">
                            🎉 <strong>Sukses!</strong> Barang lelang baru berhasil di-publish!
                        </div>
                    <?php elseif ($_GET['status'] === 'update_success'): ?>
                        <div class="bg-blue-950/50 border border-blue-900 text-blue-400 text-xs px-4 py-3 rounded-xl mb-4 font-medium">
                            🔄 <strong>Sukses!</strong> Status lelang berhasil diperbarui!
                        </div>
                    <?php elseif ($_GET['status'] === 'delete_success'): ?>
                        <div class="bg-amber-950/50 border border-amber-900 text-amber-400 text-xs px-4 py-3 rounded-xl mb-4 font-medium">
                            🗑️ <strong>Sukses!</strong> Barang lelang berhasil dihapus!
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                
                <form action="proses_tambah_barang.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Nama Barang / Komoditas</label>
                        <input type="text" name="nama_barang" placeholder="Contoh: Asus ROG Phone 6 Pro" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-slate-600" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Deskripsi & Spesifikasi</label>
                        <textarea name="deskripsi_barang" rows="3" placeholder="Jelaskan kondisi barang, kelengkapan, dll..." class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-slate-600" required></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Harga Buka Awal (Rp)</label>
                        <input type="number" name="harga_barang" placeholder="Minimal Rp 1.000" min="1000" step="1000" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-slate-600" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">🖼️ Foto Komoditas (Import Galeri)</label>
                        <input type="file" name="foto_barang" accept="image/*" class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border file:border-slate-800 file:text-xs file:font-semibold file:bg-slate-950 file:text-slate-300 hover:file:bg-slate-800 cursor-pointer" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-2">⏳ Durasi Lelang</label>
                        <div class="flex gap-2 flex-wrap mb-2">
                            <button type="button" onclick="setDurasi(1, this)"  class="btn-durasi px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-950 border border-slate-800 text-slate-300 hover:bg-slate-800 transition">1 Jam</button>
                            <button type="button" onclick="setDurasi(3, this)"  class="btn-durasi px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-950 border border-slate-800 text-slate-300 hover:bg-slate-800 transition">3 Jam</button>
                            <button type="button" onclick="setDurasi(6, this)"  class="btn-durasi px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-950 border border-slate-800 text-slate-300 hover:bg-slate-800 transition">6 Jam</button>
                            <button type="button" onclick="setDurasi(12, this)" class="btn-durasi px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-950 border border-slate-800 text-slate-300 hover:bg-slate-800 transition">12 Jam</button>
                            <button type="button" onclick="setDurasi(24, this)" class="btn-durasi px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-950 border border-slate-800 text-slate-300 hover:bg-slate-800 transition">24 Jam</button>
                        </div>
                        <p id="previewWaktu" class="text-[10px] text-slate-500 mb-1">Belum dipilih</p>
                        <input type="hidden" name="waktu" id="inputWaktu">
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-4 py-2.5 rounded-xl transition shadow-md mt-2">
                        Publish ke Dashboard User
                    </button>
                </form>
            </div>
        </div>

        <!-- KOLOM KANAN: MONITORING TABLE (TEMA GELAP) -->
        <div class="lg:col-span-2 flex flex-col">
            <div class="bg-slate-900 text-slate-100 rounded-2xl border border-slate-800 overflow-hidden shadow-xl flex-1">
                <div class="p-5 border-b border-slate-800 bg-slate-900/50 flex justify-between items-center">
                    <h2 class="text-base font-bold text-slate-100 tracking-wide flex items-center gap-2">
                        📊 Live Monitoring Data Lelang
                    </h2>
                    <span class="text-xs text-slate-400 font-medium">Total: <strong class="text-blue-400"><?php echo count($semua_barang); ?> Barang</strong></span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-950 text-slate-300 text-xs uppercase font-bold tracking-wider border-b border-slate-800">
                                <th class="py-3 px-4">Preview</th>
                                <th class="py-3 px-4">Nama Barang</th>
                                <th class="py-3 px-4">Harga Awal</th>
                                <th class="py-3 px-4">Bid Tertinggi</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-800">
                            <?php if (empty($semua_barang)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-12 text-slate-600 bg-slate-950/50">Belum ada komoditas lelang yang dibuat.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($semua_barang as $barang): ?>
                                    <tr class="hover:bg-slate-950/30 transition duration-150">
                                        <!-- KOLOM PRATINJAU FOTO DI ADMIN -->
                                        <td class="py-3 px-4">
                                            <?php if (!empty($barang['gambar']) && file_exists('../DashboardUser/img/' . $barang['gambar'])): ?>
                                                <img src="../DashboardUser/img/<?php echo $barang['gambar']; ?>" class="w-12 h-12 object-cover rounded-xl border border-slate-800 shadow-sm">
                                            <?php else: ?>
                                                <div class="w-12 h-12 bg-slate-950 rounded-xl flex items-center justify-center text-xs border border-dashed border-slate-800">📦</div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 px-4 font-semibold text-slate-100">
                                            <?php echo htmlspecialchars($barang['nama_barang']); ?>
                                            <span class="block text-[10px] text-slate-500 font-normal">💬 <?php echo $barang['total_bidder']; ?> Bid masuk</span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-400 text-xs">Rp <?php echo number_format($barang['harga_awal'], 0, ',', '.'); ?></td>
                                        <td class="py-3 px-4 font-bold text-blue-400 text-xs">Rp <?php echo number_format($barang['harga_tertinggi'], 0, ',', '.'); ?></td>
                                        <td class="py-3 px-4 flex gap-1 justify-center items-center h-20">
                                            <a href="proses_aksi_admin.php?aksi=toggle_status&id=<?php echo $barang['id_barang']; ?>&status_sekarang=<?php echo $barang['status_lelang']; ?>" class="bg-slate-950 hover:bg-slate-800 border border-slate-800 text-slate-300 text-[11px] font-medium px-2 py-1 rounded-lg transition">🔄 Status</a>
                                            <a href="proses_aksi_admin.php?aksi=hapus&id=<?php echo $barang['id_barang']; ?>" class="bg-rose-950/50 hover:bg-rose-900 border border-rose-900 text-rose-400 text-[11px] font-medium px-2 py-1 rounded-lg transition" onclick="return confirm('Hapus barang ini beserta datanya?');">🗑️ Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

</body>
</html>