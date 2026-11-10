<?php
session_start();
include 'config.php';

// Cek apakah admin sudah login pakai Private Key
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header("Location: login_admin.php");
    exit();
}

// Proses Tambah Antrean Manual oleh Admin
if (isset($_POST['tambah_manual'])) {
    $kode_booking   = 'G22-' . rand(1000, 9999);
    $nama_pelanggan = encryptData(mysqli_real_escape_string($conn, $_POST['nama_pelanggan']));
    $no_hp          = encryptData(mysqli_real_escape_string($conn, $_POST['no_hp']));
    $tipe_motor     = mysqli_real_escape_string($conn, $_POST['tipe_motor']);
    $plat_nomor     = mysqli_real_escape_string($conn, $_POST['plat_nomor']);
    $paket_service  = mysqli_real_escape_string($conn, $_POST['paket_service']);
    $tanggal_service= $_POST['tanggal_service'];
    $jam_service    = mysqli_real_escape_string($conn, $_POST['jam_service']);
    $metode_bayar   = mysqli_real_escape_string($conn, $_POST['metode_pembayaran']);
    $keluhan_raw    = mysqli_real_escape_string($conn, $_POST['keluhan']);

    $keluhan_full = encryptData(($keluhan_raw ? $keluhan_raw : "Walk-in") . " | " . $metode_bayar . " (Jam: " . $jam_service . ")");

    $query_insert = "INSERT INTO booking (kode_booking, nama_pelanggan, no_hp, tipe_motor, plat_nomor, paket_service, tanggal_service, keluhan, status) 
                     VALUES ('$kode_booking', '$nama_pelanggan', '$no_hp', '$tipe_motor', '$plat_nomor', '$paket_service', '$tanggal_service', '$keluhan_full', 'Diproses')";
    mysqli_query($conn, $query_insert);
    header("Location: admin.php");
    exit();
}

// Logout Admin
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login_admin.php");
    exit();
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'Semua';
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$where_clause = "WHERE 1=1";
if ($filter != 'Semua') {
    if ($filter == 'Diproses') {
        $where_clause .= " AND (status = 'Diproses' OR status = 'Dalam Pengerjaan')";
    } else {
        $where_clause .= " AND status = '$filter'";
    }
}
if (!empty($search)) {
    $where_clause .= " AND (kode_booking LIKE '%$search%' OR plat_nomor LIKE '%$search%')";
}

// Statistik
$total_antrean = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM booking"))['cnt'];
$jml_menunggu  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM booking WHERE status='Menunggu'"))['cnt'];
$jml_diproses  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM booking WHERE status='Diproses' OR status='Dalam Pengerjaan'"))['cnt'];
$jml_selesai   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM booking WHERE status='Selesai'"))['cnt'];

$query = "SELECT * FROM booking $where_clause ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kelola Bengkel - GARAGE 22</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { background-color: #0b1120; color: #f1f5f9; }</style>
</head>
<body class="font-sans antialiased">

    <!-- Header Navigation -->
    <header class="bg-[#0b1120]/90 backdrop-blur-md border-b border-gray-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center text-white font-black text-base shadow-lg shadow-red-600/40">
                    G22
                </div>
                <div>
                    <span class="text-xl font-black tracking-wider text-white block leading-none">GARAGE <span class="text-red-500">22</span></span>
                    <span class="text-[10px] text-gray-400 tracking-widest uppercase font-semibold">ADMIN DASHBOARD</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="index.php" class="bg-gray-800 hover:bg-gray-700 text-gray-200 border border-gray-700 text-xs font-semibold px-4 py-2.5 rounded-xl transition">
                    Website Utama
                </a>
                <a href="admin.php?logout=true" class="bg-red-950/60 hover:bg-red-900 text-red-400 border border-red-800 text-xs font-semibold px-4 py-2.5 rounded-xl transition">
                    Logout <i class="fa-solid fa-right-from-bracket ml-1"></i>
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-6 py-10 space-y-8">
        
        <!-- Header Title & Action -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Dashboard Kelola Bengkel</h1>
                <p class="text-xs text-gray-400 mt-1">Pantau status pendaftaran dan pengerjaan service motor harian.</p>
            </div>
            <button onclick="toggleModal(true)" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-5 py-3.5 rounded-xl shadow-lg shadow-red-600/30 flex items-center gap-2 transition">
                <i class="fa-solid fa-plus"></i> Tambah Antrean Manual
            </button>
        </div>

        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl flex justify-between items-center">
                <div>
                    <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider block mb-1">TOTAL ANTREAN</span>
                    <h3 class="text-3xl font-black text-white"><?= $total_antrean; ?></h3>
                </div>
                <div class="w-12 h-12 bg-blue-950/50 text-blue-400 border border-blue-900/40 rounded-xl flex items-center justify-center text-lg">
                    <i class="fa-solid fa-list-check"></i>
                </div>
            </div>
            <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl flex justify-between items-center">
                <div>
                    <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider block mb-1">MENUNGGU</span>
                    <h3 class="text-3xl font-black text-yellow-400"><?= $jml_menunggu; ?></h3>
                </div>
                <div class="w-12 h-12 bg-yellow-950/50 text-yellow-400 border border-yellow-900/40 rounded-xl flex items-center justify-center text-lg">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>
            <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl flex justify-between items-center">
                <div>
                    <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider block mb-1">DALAM PENGERJAAN</span>
                    <h3 class="text-3xl font-black text-orange-400"><?= $jml_diproses; ?></h3>
                </div>
                <div class="w-12 h-12 bg-gray-800 text-gray-400 border border-gray-700 rounded-xl flex items-center justify-center text-lg">
                    <i class="fa-solid fa-gears"></i>
                </div>
            </div>
            <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl flex justify-between items-center">
                <div>
                    <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider block mb-1">SELESAI HARI INI</span>
                    <h3 class="text-3xl font-black text-emerald-400"><?= $jml_selesai; ?></h3>
                </div>
                <div class="w-12 h-12 bg-emerald-950/50 text-emerald-400 border border-emerald-900/40 rounded-xl flex items-center justify-center text-lg">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-gray-900 border border-gray-800 p-4 rounded-2xl flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                <span class="text-xs font-bold text-gray-400 uppercase mr-2">FILTER:</span>
                <?php 
                    $filters = ['Semua', 'Menunggu', 'Diproses', 'Selesai'];
                    foreach($filters as $f) {
                        $active = ($filter == $f) ? 'bg-red-600 text-white font-bold shadow-lg shadow-red-600/30' : 'bg-gray-800 text-gray-300 hover:bg-gray-700';
                        echo '<a href="admin.php?filter=' . $f . '&search=' . $search . '" class="text-xs px-4 py-2 rounded-xl transition ' . $active . '">' . $f . '</a>';
                    }
                ?>
            </div>

            <form action="admin.php" method="GET" class="w-full md:w-72 relative">
                <input type="hidden" name="filter" value="<?= $filter; ?>">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3 text-gray-500 text-xs"></i>
                <input type="text" name="search" value="<?= htmlspecialchars($search); ?>" placeholder="Cari Kode / Plat..." class="w-full bg-[#0b1120] border border-gray-800 rounded-xl py-2.5 pl-10 pr-4 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-red-500 transition">
            </form>
        </div>

        <!-- Tabel Antrean -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-950/50 border-b border-gray-800 text-gray-400 text-[11px] uppercase tracking-wider">
                            <th class="p-4 font-bold">KODE</th>
                            <th class="p-4 font-bold">PELANGGAN</th>
                            <th class="p-4 font-bold">MOTOR & PLAT</th>
                            <th class="p-4 font-bold">LAYANAN</th>
                            <th class="p-4 font-bold">JADWAL</th>
                            <th class="p-4 font-bold">STATUS</th>
                            <th class="p-4 font-bold text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/60 text-xs">
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): 
                                $nama_jelas   = decryptData($row['nama_pelanggan']);
                                $no_hp_jelas  = decryptData($row['no_hp']);
                                $keluhan_jelas= decryptData($row['keluhan']);
                            ?>
                                <tr class="hover:bg-gray-800/30 transition">
                                    <td class="p-4 font-mono font-bold text-red-500"><?= $row['kode_booking']; ?></td>
                                    <td class="p-4">
                                        <div class="font-bold text-white"><?= htmlspecialchars($nama_jelas ? $nama_jelas : $row['nama_pelanggan']); ?></div>
                                        <div class="text-[11px] text-gray-400 mt-0.5"><i class="fa-brands fa-whatsapp text-emerald-500 mr-1"></i> <?= htmlspecialchars($no_hp_jelas ? $no_hp_jelas : $row['no_hp']); ?></div>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-semibold text-gray-200"><?= htmlspecialchars($row['tipe_motor']); ?></div>
                                        <span class="inline-block bg-[#0b1120] border border-gray-800 px-2 py-0.5 rounded text-[10px] text-gray-300 font-mono mt-1"><?= htmlspecialchars($row['plat_nomor']); ?></span>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-medium text-gray-200"><?= htmlspecialchars($row['paket_service']); ?></div>
                                        <div class="text-[10px] text-gray-500 mt-0.5"><?= htmlspecialchars($keluhan_jelas); ?></div>
                                    </td>
                                    <td class="p-4 text-gray-300">
                                        <div><i class="fa-regular fa-calendar text-gray-500 mr-1"></i> <?= date('Y-m-d', strtotime($row['tanggal_service'])); ?></div>
                                        <div class="text-[10px] text-gray-500 mt-0.5"><i class="fa-regular fa-clock text-gray-500 mr-1"></i> Sesuai Antrean</div>
                                    </td>
                                    <td class="p-4">
                                        <?php
                                            $status = $row['status'];
                                            $stClass = [
                                                'Menunggu' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/30',
                                                'Diproses' => 'bg-orange-500/10 text-orange-400 border-orange-500/30',
                                                'Selesai'  => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                                                'Dibatalkan' => 'bg-red-500/10 text-red-400 border-red-500/30'
                                            ];
                                        ?>
                                        <span class="px-3 py-1 rounded-full text-[11px] font-semibold border <?= $stClass[$status] ?? 'bg-gray-800 text-gray-300 border-gray-700'; ?>">
                                            <?= $status; ?>
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <form action="update_status.php" method="POST" class="inline-block">
                                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                            <select name="status" onchange="this.form.submit()" class="bg-[#0b1120] border border-gray-700 text-xs rounded-xl px-3 py-2 text-white font-medium focus:outline-none focus:border-red-500 cursor-pointer transition">
                                                <option value="Menunggu" <?= $status == 'Menunggu' ? 'selected' : ''; ?>>Menunggu</option>
                                                <option value="Diproses" <?= $status == 'Diproses' ? 'selected' : ''; ?>>Diproses</option>
                                                <option value="Selesai" <?= $status == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                                                <option value="Dibatalkan" <?= $status == 'Dibatalkan' ? 'selected' : ''; ?>>Dibatalkan</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="p-8 text-center text-gray-500">Tidak ada data antrean ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Modal Tambah Antrean Manual -->
    <div id="modalManual" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-gray-900 border border-gray-800 w-full max-w-xl rounded-3xl p-8 relative shadow-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-xl font-bold text-white">Tambah Antrean Manual</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Input data pelanggan walk-in (langsung datang ke bengkel).</p>
                </div>
                <button onclick="toggleModal(false)" class="w-8 h-8 bg-gray-800 hover:bg-gray-700 rounded-full flex items-center justify-center text-gray-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="admin.php" method="POST" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Nama Pemilik *</label>
                        <input type="text" name="nama_pelanggan" required placeholder="Nama Pelanggan" class="w-full bg-[#0b1120] border border-gray-800 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">No. WhatsApp *</label>
                        <input type="text" name="no_hp" required placeholder="0812xxxx" class="w-full bg-[#0b1120] border border-gray-800 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-red-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Merk & Tipe Motor *</label>
                        <input type="text" name="tipe_motor" required placeholder="Honda Vario 150" class="w-full bg-[#0b1120] border border-gray-800 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Plat Nomor *</label>
                        <input type="text" name="plat_nomor" required placeholder="F 1234 ABC" class="w-full bg-[#0b1120] border border-gray-800 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-red-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Paket Service *</label>
                        <select name="paket_service" required class="w-full bg-[#0b1120] border border-gray-800 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-red-500">
                            <option value="Service Ringan">Service Ringan (Rp 45.000)</option>
                            <option value="Ganti Oli + Tune Up">Ganti Oli + Tune Up (Rp 95.000)</option>
                            <option value="Service CVT / Rantai">Service CVT / Rantai (Rp 65.000)</option>
                            <option value="Service Besar / Overhaul">Service Besar / Overhaul (Rp 250.000+)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Tanggal *</label>
                        <input type="date" name="tanggal_service" value="<?= date('Y-m-d'); ?>" required class="w-full bg-[#0b1120] border border-gray-800 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-red-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Jam Kedatangan</label>
                        <select name="jam_service" class="w-full bg-[#0b1120] border border-gray-800 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-red-500">
                            <option value="08:00 WIB">08:00 WIB</option>
                            <option value="10:00 WIB">10:00 WIB</option>
                            <option value="13:00 WIB">13:00 WIB</option>
                            <option value="15:00 WIB">15:00 WIB</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="w-full bg-[#0b1120] border border-gray-800 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-red-500">
                            <option value="Tunai">Tunai (Cash)</option>
                            <option value="QRIS">QRIS</option>
                            <option value="Transfer">Transfer</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Keluhan Motor</label>
                    <textarea name="keluhan" rows="2" placeholder="Catatan kendala motor..." class="w-full bg-[#0b1120] border border-gray-800 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-red-500"></textarea>
                </div>

                <button type="submit" name="tambah_manual" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-red-600/30 transition text-xs">
                    Simpan & Enkripsi ke Database
                </button>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(show) {
            const modal = document.getElementById('modalManual');
            if(show) {
                modal.classList.remove('hidden');
            } else {
                modal.classList.add('hidden');
            }
        }
    </script>

</body>
</html>