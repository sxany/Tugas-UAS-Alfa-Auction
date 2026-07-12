<?php
session_start(); 

if (!isset($_SESSION['user'])) {
    header('Location: ../LoginPage/src/login.php');
    exit;
}

require_once __DIR__ . '/../LoginPage/koneksi.php';

$id_user = $_SESSION['user']['id_user']; 

$tab_aktif = isset($_GET['tab']) ? $_GET['tab'] : 'daftar';

try {
    $stmt_saldo = $pdo->prepare("SELECT saldo FROM users WHERE id_user = :id_user");
    $stmt_saldo->execute([':id_user' => $id_user]);
    $user_data = $stmt_saldo->fetch();
    $saldo_sekarang = $user_data ? $user_data['saldo'] : 0;
} catch (PDOException $e) {
    $saldo_sekarang = 0;
}

try {
    if ($tab_aktif === 'daftar') {
        $query = "
            SELECT 
                bl.id_barang, 
                bl.nama_barang, 
                bl.deskripsi_barang, 
                bl.gambar,
                bl.harga_barang AS harga_awal,
                IFNULL(MAX(b.harga_tawar), bl.harga_barang) AS harga_berjalan,
                COUNT(b.id_bid) AS total_penawaran
            FROM barang_lelang bl
            LEFT JOIN bid b ON bl.id_barang = b.barang_id
            WHERE bl.status_lelang = 'aktif'
            GROUP BY bl.id_barang
        ";
        $stmt = $pdo->query($query);
        $barang_lelang = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($tab_aktif === 'posisi') {
        $query_posisi = "
            SELECT 
                bl.nama_barang,
                b_user.harga_tawar AS bid_kamu,
                (SELECT MAX(harga_tawar) FROM bid WHERE barang_id = bl.id_barang) AS harga_berjalan,
                bl.status_lelang
            FROM barang_lelang bl
            JOIN bid b_user ON bl.id_barang = b_user.barang_id
            WHERE b_user.user_id = :id_user
        ";
        $stmt_posisi = $pdo->prepare($query_posisi);
        $stmt_posisi->execute([':id_user' => $id_user]);
        $posisi_bid = $stmt_posisi->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    die("Query Gagal: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Dashboard User - Alfa Auction</title>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    </head>
    <body class="bg-slate-950 text-slate-100 min-h-screen font-sans">

    <div class="flex min-h-screen">
        
        <aside class="w-64 bg-slate-900 border-r border-slate-800 p-6 flex flex-col justify-between">
            <div>
<div class="flex items-center gap-3 mb-8 px-2">
    <img src="../gambar/logoauction.png" alt="Logo Alfa Auction" class="w-12 h-12 object-contain">
    <div>
        <h1 class="font-bold text-lg leading-tight text-slate-100">Alfa Auction</h1>
        <span class="text-xs text-slate-500 font-medium">Sistem Lelang Online</span>
    </div>
</div>
                <div class="bg-slate-950 border border-slate-800 rounded-xl p-4 mb-6 shadow-sm">
                    <p class="text-xs text-slate-400 font-medium mb-1"><i class="fa-solid fa-wallet mr-1.5"></i> Saldo Anda</p>
                    <p class="text-lg font-bold text-emerald-400">Rp <?php echo number_format($saldo_sekarang, 0, ',', '.'); ?></p>
                </div>

                <nav class="space-y-1.5">
                    <a href="dashboardUser.php?tab=daftar" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 <?php echo $tab_aktif === 'daftar' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40 font-semibold' : 'text-slate-400 hover:bg-slate-950 hover:text-white' ?>">
                        <i class="fa-solid fa-list-ul w-5 text-center"></i> Daftar Barang Lelang
                    </a>
                    <a href="dashboardUser.php?tab=posisi" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 <?php echo $tab_aktif === 'posisi' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40 font-semibold' : 'text-slate-400 hover:bg-slate-950 hover:text-white' ?>">
                        <i class="fa-solid fa-chart-line w-5 text-center"></i> Posisi Taruhan Anda
                    </a>
                    <a href="dashboardUser.php?tab=deposit" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 <?php echo $tab_aktif === 'deposit' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40 font-semibold' : 'text-slate-400 hover:bg-slate-950 hover:text-white' ?>">
                        <i class="fa-solid fa-money-bill-wave w-5 text-center"></i> Deposit Saldo
                    </a>
                </nav>
            </div>

            <div class="pt-6 border-t border-slate-800">
                <a href="../LoginPage/src/logout.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-rose-400 hover:bg-rose-950/30 transition-all duration-200">
                    <i class="fa-solid fa-right-from-bracket w-5 text-center"></i> Keluar Sistem
                </a>
            </div>
        </aside>

        <main class="flex-1 p-8 bg-slate-950">
            
            <?php if ($tab_aktif === 'daftar'): ?>
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-slate-100 mb-1">Daftar Barang Lelang Aktif</h2>
                    <p class="text-sm text-slate-400">Pantau barang pilihanmu dan tawar harga terbaik sekarang sebelum waktu habis.</p>
                </div>

                <?php if (isset($_GET['status'])): ?>
                    <?php if ($_GET['status'] === 'success'): ?>
                        <div class="mb-6 bg-emerald-950/50 border border-emerald-900 text-emerald-400 px-4 py-3 rounded-xl text-sm shadow-sm flex items-center gap-2 max-w-xl">
                            <i class="fa-solid fa-circle-check"></i> Penawaran (Bid) Anda berhasil diajukan!
                        </div>
                    <?php elseif ($_GET['status'] === 'bid_too_low'): ?>
                        <div class="mb-6 bg-rose-950/50 border border-rose-900 text-rose-400 px-4 py-3 rounded-xl text-sm shadow-sm flex items-center gap-2 max-w-xl">
                            <i class="fa-solid fa-circle-exclamation"></i> Gagal! Nominal bid harus lebih tinggi dari harga berjalan saat ini.
                        </div>
                    <?php elseif ($_GET['status'] === 'saldo_insufficient'): ?>
                        <div class="mb-6 bg-rose-950/50 border border-rose-900 text-rose-400 px-4 py-3 rounded-xl text-sm shadow-sm flex items-center gap-2 max-w-xl">
                            <i class="fa-solid fa-wallet"></i> Gagal mengajukan bid! Saldo dompet Anda tidak mencukupi untuk nominal penawaran ini. Silakan top up saldo terlebih dahulu.
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php if (empty($barang_lelang)): ?>
                        <div class="col-span-full bg-slate-900 border border-slate-800 p-8 rounded-2xl text-center text-slate-500">
                            <i class="fa-solid fa-box-open text-4xl mb-3 block text-slate-600"></i>
                            Belum ada produk barang lelang yang aktif saat ini.
                        </div>
                    <?php else: ?>
                        <?php foreach ($barang_lelang as $barang): ?>
                            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl hover:border-slate-700 transition-all duration-300 flex flex-col justify-between">
                                <div>
                                    <div class="h-48 w-full bg-slate-950 relative overflow-hidden flex items-center justify-center">
                                        <?php if (!empty($barang['gambar'])): ?>
                                            <img src="img/<?php echo htmlspecialchars($barang['gambar']); ?>" alt="<?php echo htmlspecialchars($barang['nama_barang']); ?>" class="object-cover w-full h-full transition-transform duration-500 hover:scale-105">
                                        <?php else: ?>
                                            <i class="fa-solid fa-image text-4xl text-slate-800"></i>
                                        <?php endif; ?>
                                        <span class="absolute top-3 right-3 bg-blue-600 text-white font-bold text-[11px] px-2.5 py-1 rounded-full uppercase tracking-wider shadow-md"><i class="fa-solid fa-fire mr-1"></i> AKTIF</span>
                                    </div>
                                    
                                    <div class="p-5">
                                        <h3 class="font-bold text-lg text-slate-100 mb-1.5 truncate"><?php echo htmlspecialchars($barang['nama_barang']); ?></h3>
                                        <p class="text-xs text-slate-400 leading-relaxed mb-4 line-clamp-2"><?php echo htmlspecialchars($barang['deskripsi_barang']); ?></p>
                                        
                                        <div class="grid grid-cols-2 gap-2 bg-slate-950 p-3 rounded-xl border border-slate-800">
                                            <div>
                                                <span class="text-[10px] text-slate-500 block uppercase font-medium">Harga Awal</span>
                                                <span class="font-semibold text-xs text-slate-300">Rp <?php echo number_format($barang['harga_awal'], 0, ',', '.'); ?></span>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-[10px] text-slate-500 block uppercase font-medium">Harga Berjalan</span>
                                                <span class="font-bold text-sm text-emerald-400">Rp <?php echo number_format($barang['harga_berjalan'], 0, ',', '.'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-5 pb-5 pt-2 border-t border-slate-800">
                                    <form action="proses_bid.php" method="POST" class="space-y-3">
                                        <input type="hidden" name="id_barang" value="<?php echo $barang['id_barang']; ?>">
                                        <div>
                                            <div class="relative">
                                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs font-semibold">Rp</span>
                                                <input type="number" name="harga_tawar" min="<?php echo $barang['harga_berjalan'] + 1; ?>" placeholder="Masukkan harga bid" class="w-full bg-slate-950 border border-slate-800 rounded-xl py-2 pl-9 pr-3 text-xs text-white font-medium focus:outline-none focus:border-blue-500 transition-all" required>
                                            </div>
                                        </div>
                                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2.5 px-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-1.5 cursor-pointer shadow-md shadow-slate-950/30">
                                            <i class="fa-solid fa-gavel"></i> Ajukan Penawaran
                                        </button>
                                    </form>
                                    <div class="text-center mt-2.5">
                                        <span class="text-[10px] text-slate-500"><i class="fa-solid fa-users mr-1"></i> Total partisipan: <strong><?php echo $barang['total_penawaran']; ?> Kali Bid</strong></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($tab_aktif === 'posisi'): ?>
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-slate-100 mb-1">Status Taruhan Anda</h2>
                    <p class="text-sm text-slate-400">Pantau posisi peringkat nilai penawaran yang sudah kamu pasang di barang lelang.</p>
                </div>

                <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-950 border-b border-slate-800 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                                    <th class="py-4 px-6">Nama Barang</th>
                                    <th class="py-4 px-6">Bid Terakhir Kamu</th>
                                    <th class="py-4 px-6">Harga Tertinggi Pasar</th>
                                    <th class="py-4 px-6">Peringkat Posisi</th>
                                    <th class="py-4 px-6">Status Lelang</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800 text-sm text-slate-300">
                                <?php if (empty($posisi_bid)): ?>
                                    <tr>
                                        <td colspan="5" class="py-8 px-6 text-center text-slate-500 bg-slate-950/20">
                                            <i class="fa-solid fa-receipt text-3xl mb-2 block text-slate-600"></i>
                                            Kamu belum pernah mengajukan penawaran harga pada produk mana pun.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($posisi_bid as $posisi): ?>
                                        <tr class="hover:bg-slate-950/40 transition-colors">
                                            <td class="py-4 px-6 font-semibold text-slate-100"><?php echo htmlspecialchars($posisi['nama_barang']); ?></td>
                                            <td class="py-4 px-6 text-slate-400 font-medium">Rp <?php echo number_format($posisi['bid_kamu'], 0, ',', '.'); ?></td>
                                            <td class="py-4 px-6 text-emerald-400 font-bold">Rp <?php echo number_format($posisi['harga_berjalan'], 0, ',', '.'); ?></td>
                                            <td class="py-4 px-6">
                                                <?php if ($posisi['bid_kamu'] >= $posisi['harga_berjalan']): ?>
                                                    <span class="bg-emerald-950/40 text-emerald-400 text-xs font-medium px-2.5 py-1 rounded-full border border-emerald-900/40 shadow-sm shadow-emerald-950/50">🏆 Tertinggi (Memimpin)</span>
                                                <?php else: ?>
                                                    <span class="bg-rose-950/40 text-rose-400 text-xs font-medium px-2.5 py-1 rounded-full border border-rose-900/40 shadow-sm shadow-rose-950/50">📉 Tersalip (Kalah)</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="py-4 px-6">
                                                <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-slate-950 text-blue-400 border border-slate-800">
                                                    <?php echo strtoupper($posisi['status_lelang']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($tab_aktif === 'deposit'): ?>
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-slate-100 mb-1">Deposit Saldo Akun</h2>
                    <p class="text-sm text-slate-400">Tambahkan saldo akun Anda untuk dapat melakukan penawaran lelang barang.</p>
                </div>

                <?php if (isset($_GET['status'])): ?>
                    <?php if ($_GET['status'] === 'success' && isset($_GET['amount'])): ?>
                        <div class="max-w-md mb-4 bg-emerald-950/50 border border-emerald-900 text-emerald-400 px-4 py-3 rounded-xl text-sm shadow-sm flex items-center gap-2">
                            <i class="fa-solid fa-circle-check"></i> Deposit sukses sebesar <strong>Rp <?php echo number_format($_GET['amount'], 0, ',', '.'); ?></strong>!
                        </div>
                    <?php elseif ($_GET['status'] === 'nominal_low'): ?>
                        <div class="max-w-md mb-4 bg-rose-950/50 border border-rose-900 text-rose-400 px-4 py-3 rounded-xl text-sm shadow-sm flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation"></i> Gagal! Minimal pengisian deposit adalah Rp 10.000.
                        </div>
                    <?php elseif ($_GET['status'] === 'error'): ?>
                        <div class="max-w-md mb-4 bg-rose-950/50 border border-rose-900 text-rose-400 px-4 py-3 rounded-xl text-sm shadow-sm flex items-center gap-2">
                            <i class="fa-solid fa-circle-xmark"></i> Terjadi kesalahan teknis saat memproses database.
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="max-w-md bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl">
                    <h3 class="text-lg font-semibold text-slate-200 mb-4">
                        <i class="fa-solid fa-money-check-dollar mr-2 text-blue-500"></i>Formulir Top Up
                    </h3>
                    
                    <form action="proses_deposit.php" method="POST" class="space-y-5">
                        <div>
                            <label for="nominal" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nominal Top Up (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-semibold text-sm">Rp</span>
                                <input type="number" id="nominal" name="nominal" min="10000" placeholder="Contoh: 50000" class="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 pl-12 pr-4 text-white font-medium focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-slate-800 transition-all duration-200" required>
                            </div>
                            <span class="text-[11px] text-slate-500 block mt-1.5">* Batas pengisian minimal deposit adalah Rp 10.000</span>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-blue-900/40 transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-wallet"></i> Konfirmasi Isi Saldo
                        </button>
                    </form>
                </div>
            <?php endif; ?>

        </main>
    </div>

</body>
</html>