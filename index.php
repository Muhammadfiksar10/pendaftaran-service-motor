<?php
include 'config.php';

$message = '';
if (isset($_POST['submit_booking'])) {
    $kode_booking   = 'G22-' . rand(1000, 9999);
    
    // Data yang sensitif/penting di-ENKRIPSI sebelum masuk database
    $nama_pelanggan = encryptData(mysqli_real_escape_string($conn, $_POST['nama_pelanggan']));
    $no_hp          = encryptData(mysqli_real_escape_string($conn, $_POST['no_hp']));
    $keluhan_raw    = mysqli_real_escape_string($conn, $_POST['keluhan']);
    $jam_service    = mysqli_real_escape_string($conn, $_POST['jam_service']);
    $metode_bayar   = mysqli_real_escape_string($conn, $_POST['metode_pembayaran'] ?? 'Tunai');

    $tipe_motor     = mysqli_real_escape_string($conn, $_POST['tipe_motor']);
    $plat_nomor     = mysqli_real_escape_string($conn, $_POST['plat_nomor']);
    $paket_service  = mysqli_real_escape_string($conn, $_POST['paket_service']);
    $tanggal_service= $_POST['tanggal_service'];

    $keluhan_full = encryptData($keluhan_raw . " (Jam: " . $jam_service . " | Metode: " . $metode_bayar . ")");

    $query = "INSERT INTO booking (kode_booking, nama_pelanggan, no_hp, tipe_motor, plat_nomor, paket_service, tanggal_service, keluhan) 
              VALUES ('$kode_booking', '$nama_pelanggan', '$no_hp', '$tipe_motor', '$plat_nomor', '$paket_service', '$tanggal_service', '$keluhan_full')";

    if (mysqli_query($conn, $query)) {
        $message = "Pendaftaran berhasil! Kode Booking Anda: <strong class='text-red-500 font-mono text-xl'>$kode_booking</strong>. Data diamankan dengan enkripsi.";
    } else {
        $message = "Gagal melakukan pendaftaran: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GARAGE 22 - Garage & Service</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #0b1120; color: #f1f5f9; }
    </style>
</head>
<body class="font-sans antialiased selection:bg-red-500 selection:text-white">

    <!-- Navbar -->
    <header class="sticky top-0 z-50 bg-[#0b1120]/90 backdrop-blur-md border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center text-white font-black text-base shadow-lg shadow-red-600/40">
                    G22
                </div>
                <div>
                    <span class="text-xl font-black tracking-wider text-white block leading-none">GARAGE <span class="text-red-500">22</span></span>
                    <span class="text-[10px] text-gray-400 tracking-widest uppercase font-semibold">GARAGE & SERVICE</span>
                </div>
            </div>

            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-300">
                <a href="#beranda" class="hover:text-red-500 transition">Beranda</a>
                <a href="#layanan" class="hover:text-red-500 transition">Layanan & Biaya</a>
                <a href="#booking" class="hover:text-red-500 transition">Form Booking</a>
            </nav>

            <a href="login_admin.php" class="flex items-center gap-2 bg-gray-800/80 hover:bg-gray-700 text-gray-200 border border-gray-700 text-xs font-semibold px-4 py-2.5 rounded-xl transition">
                <i class="fa-solid fa-gear text-red-500"></i> Mode Admin
            </a>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="beranda" class="max-w-7xl mx-auto px-6 py-12 md:py-20 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        <div class="lg:col-span-7 space-y-6">
            <span class="inline-block bg-red-950/60 border border-red-800/50 text-red-400 text-xs font-semibold px-4 py-1.5 rounded-full">
                Booking Online Tanpa Antre
            </span>
            
            <h1 class="text-4xl md:text-6xl font-black leading-tight tracking-tight">
                Service Motor <br>
                Professional <span class="text-red-500">Cepat &</span> <br>
                <span class="text-orange-500">Terpercaya</span>
            </h1>

            <p class="text-gray-400 text-base md:text-lg max-w-xl leading-relaxed">
                Rawat performa motormu bersama mekanik berpengalaman di GARAGE 22. Pesan jadwal service online sekarang tanpa perlu mengantre lama di bengkel.
            </p>

            <div class="flex flex-wrap gap-4 pt-2">
                <a href="#booking" class="bg-red-600 hover:bg-red-700 text-white font-bold px-7 py-3.5 rounded-xl shadow-lg shadow-red-600/30 flex items-center gap-2 transition">
                    Daftar Service Sekarang <i class="fa-solid fa-arrow-right text-sm"></i>
                </a>
                <a href="#layanan" class="bg-gray-800/80 hover:bg-gray-700 text-gray-300 font-semibold px-7 py-3.5 rounded-xl border border-gray-700 transition">
                    Lihat Estimasi Biaya
                </a>
            </div>

            <div class="grid grid-cols-3 gap-6 pt-8 border-t border-gray-800 max-w-lg">
                <div>
                    <div class="text-2xl font-black text-white">100%</div>
                    <div class="text-xs text-gray-400 font-medium mt-0.5">Sparepart Original</div>
                </div>
                <div>
                    <div class="text-2xl font-black text-white">15+</div>
                    <div class="text-xs text-gray-400 font-medium mt-0.5">Mekanik Ahli</div>
                </div>
                <div>
                    <div class="text-2xl font-black text-white">4.9 <span class="text-yellow-400 text-lg">★</span></div>
                    <div class="text-xs text-gray-400 font-medium mt-0.5">Rating Pelanggan</div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-5 relative">
            <div class="p-2 bg-gradient-to-b from-gray-700 to-gray-900 rounded-3xl border border-gray-700 shadow-2xl">
                <div class="relative rounded-2xl overflow-hidden group">
                    <img src="https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&q=80&w=800" alt="Motorcycle Service" class="w-full h-[380px] object-cover group-hover:scale-105 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-transparent to-transparent opacity-90"></div>
                    
                    <div class="absolute bottom-4 left-4 right-4 bg-gray-900/90 backdrop-blur-md border border-gray-700 p-4 rounded-xl flex items-center gap-4">
                        <div class="w-10 h-10 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-lg flex items-center justify-center text-lg flex-shrink-0">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white">Garansi Service 14 Hari</h4>
                            <p class="text-xs text-gray-400 mt-0.5">Jaminan perbaikan ulang gratis jika ada kendala.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="layanan" class="max-w-7xl mx-auto px-6 py-16">
        <div class="text-center mb-12 space-y-2">
            <span class="text-red-500 text-xs font-bold uppercase tracking-widest">PAKET & BIAYA SERVICE</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-white">Pilih Layanan Sesuai Kebutuhan Motor</h2>
            <p class="text-gray-400 text-sm max-w-2xl mx-auto">Transparansi harga tanpa biaya tersembunyi. Pekerjaan dilakukan secara presisi oleh mekanik berpengalaman.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-red-950/60 text-red-500 rounded-xl flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-oil-can"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Service Ringan</h3>
                    <p class="text-xs text-gray-400 mb-6 leading-relaxed">Pemeriksaan rutin untuk menjaga performa harian motor tetap prima.</p>
                    <div class="text-2xl font-black text-red-500 mb-6">Rp 45.000 <span class="text-xs font-normal text-gray-400">/kedatangan</span></div>
                    <ul class="space-y-3 text-xs text-gray-300 mb-8">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Pembersihan Filter Udara</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Cek & Setel Busi</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Cek Sistem Rem & Rantai</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Setel Gas & Karbu/Injeksi</li>
                    </ul>
                </div>
                <a href="#booking" class="block text-center bg-gray-800 hover:bg-gray-700 text-gray-200 text-xs font-bold py-3 rounded-xl transition">Pilih Paket</a>
            </div>

            <div class="bg-gray-900 border-2 border-red-600 rounded-2xl p-6 flex flex-col justify-between relative shadow-xl">
                <span class="absolute -top-3 right-6 bg-red-600 text-white text-[10px] font-extrabold px-3 py-1 rounded-full uppercase">POPULER</span>
                <div>
                    <div class="w-12 h-12 bg-red-950/60 text-red-500 rounded-xl flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-gears"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Ganti Oli + Tune Up</h3>
                    <p class="text-xs text-gray-400 mb-6 leading-relaxed">Paket kombinasi oli mesin baru dan Tune-up injeksi/karburator.</p>
                    <div class="text-2xl font-black text-red-500 mb-6">Rp 95.000 <span class="text-xs font-normal text-gray-400">+ Oli</span></div>
                    <ul class="space-y-3 text-xs text-gray-300 mb-8">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Semua Fitur Service Ringan</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Ganti Oli Mesin & Gardan</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Cleaning Throttle Body</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Reset ECU Injeksi</li>
                    </ul>
                </div>
                <a href="#booking" class="block text-center bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-3 rounded-xl transition">Pilih Paket</a>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-blue-950/60 text-blue-500 rounded-xl flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-circle-notch"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Service CVT / Rantai</h3>
                    <p class="text-xs text-gray-400 mb-6 leading-relaxed">Pembersihan dan pelumasan area transmisi matic atau rantai motor.</p>
                    <div class="text-2xl font-black text-red-500 mb-6">Rp 65.000 <span class="text-xs font-normal text-gray-400">/kedatangan</span></div>
                    <ul class="space-y-3 text-xs text-gray-300 mb-8">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Bongkar & Cleaning Mangkok CVT</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Cek Roller, Belt & Kampas Ganda</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Pemberian Grease Hi-Temp</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Pembersihan Debu Karet</li>
                    </ul>
                </div>
                <a href="#booking" class="block text-center bg-gray-800 hover:bg-gray-700 text-gray-200 text-xs font-bold py-3 rounded-xl transition">Pilih Paket</a>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-purple-950/60 text-purple-500 rounded-xl flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-screwdriver-wrench"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Service Besar / Overhaul</h3>
                    <p class="text-xs text-gray-400 mb-6 leading-relaxed">Perbaikan mendalam untuk kendala mesin berat, ngebul, atau mati total.</p>
                    <div class="text-2xl font-black text-red-500 mb-6">Rp 250.000+ <span class="text-xs font-normal text-gray-400">/estimasi</span></div>
                    <ul class="space-y-3 text-xs text-gray-300 mb-8">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Bongkar Head & Blok Mesin</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Skir Klep & Pembersihan Kerak</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Ganti Piston / Seher</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Kalibrasi Ulang Mesin</li>
                    </ul>
                </div>
                <a href="#booking" class="block text-center bg-gray-800 hover:bg-gray-700 text-gray-200 text-xs font-bold py-3 rounded-xl transition">Pilih Paket</a>
            </div>
        </div>
    </section>

    <!-- Booking Form Section -->
    <section id="booking" class="max-w-4xl mx-auto px-6 py-16">
        <div class="bg-gray-900 border border-gray-800 p-8 md:p-12 rounded-3xl shadow-2xl relative">
            <div class="mb-8">
                <span class="text-red-500 text-xs font-bold uppercase tracking-wider block mb-1">FORM PENDAFTARAN</span>
                <h2 class="text-2xl md:text-3xl font-extrabold text-white">Isi Data Booking Service</h2>
                <p class="text-xs text-gray-400 mt-1">Lengkapi form di bawah ini untuk mengamankan antrean service Anda.</p>
            </div>

            <?php if ($message): ?>
                <div class="mb-8 p-4 bg-gray-800 border-l-4 border-red-500 text-gray-200 text-sm rounded-r-xl shadow">
                    <?= $message; ?>
                </div>
            <?php endif; ?>

            <form action="#booking" method="POST" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-2">Nama Pemilik <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-4 top-3.5 text-gray-500 text-xs"></i>
                            <input type="text" name="nama_pelanggan" required placeholder="Contoh: Budi Santoso" class="w-full bg-[#0b1120] border border-gray-800 rounded-xl py-3 pl-11 pr-4 text-xs text-white focus:outline-none focus:border-red-500 transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-2">Nomor HP / WhatsApp <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-phone absolute left-4 top-3.5 text-gray-500 text-xs"></i>
                            <input type="text" name="no_hp" required placeholder="Contoh: 081234567890" class="w-full bg-[#0b1120] border border-gray-800 rounded-xl py-3 pl-11 pr-4 text-xs text-white focus:outline-none focus:border-red-500 transition">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-2">Merk & Type Motor <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-motorcycle absolute left-4 top-3.5 text-gray-500 text-xs"></i>
                            <input type="text" name="tipe_motor" required placeholder="Contoh: Honda Vario 150" class="w-full bg-[#0b1120] border border-gray-800 rounded-xl py-3 pl-11 pr-4 text-xs text-white focus:outline-none focus:border-red-500 transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-2">Plat Nomor Motor <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-id-card absolute left-4 top-3.5 text-gray-500 text-xs"></i>
                            <input type="text" name="plat_nomor" required placeholder="CONTOH: F 1234 ABC" class="w-full bg-[#0b1120] border border-gray-800 rounded-xl py-3 pl-11 pr-4 text-xs text-white focus:outline-none focus:border-red-500 transition">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-2">Tanggal Kedatangan <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_service" required class="w-full bg-[#0b1120] border border-gray-800 rounded-xl py-3 px-4 text-xs text-white focus:outline-none focus:border-red-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-2">Jam Kedatangan <span class="text-red-500">*</span></label>
                        <select name="jam_service" required class="w-full bg-[#0b1120] border border-gray-800 rounded-xl py-3 px-4 text-xs text-white focus:outline-none focus:border-red-500 transition">
                            <option value="">-- Pilih Jam Service --</option>
                            <option value="08:00 WIB">08:00 WIB</option>
                            <option value="10:00 WIB">10:00 WIB</option>
                            <option value="13:00 WIB">13:00 WIB</option>
                            <option value="15:00 WIB">15:00 WIB</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-2">Jenis Service / Paket <span class="text-red-500">*</span></label>
                    <select name="paket_service" required class="w-full bg-[#0b1120] border border-gray-800 rounded-xl py-3 px-4 text-xs text-white focus:outline-none focus:border-red-500 transition">
                        <option value="">-- Pilih Paket Service --</option>
                        <option value="Service Ringan">Service Ringan (Rp 45.000)</option>
                        <option value="Ganti Oli + Tune Up">Ganti Oli + Tune Up (Rp 95.000)</option>
                        <option value="Service CVT / Rantai">Service CVT / Rantai (Rp 65.000)</option>
                        <option value="Service Besar / Overhaul">Service Besar / Overhaul (Rp 250.000+)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-2">Detail Keluhan / Catatan Tambahan</label>
                    <textarea name="keluhan" rows="3" placeholder="Contoh: Rem belakang agak blong..." class="w-full bg-[#0b1120] border border-gray-800 rounded-xl p-4 text-xs text-white focus:outline-none focus:border-red-500 transition"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-2">Metode Pembayaran Rencana <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-3 gap-4">
                        <label class="flex items-center justify-center gap-2 bg-[#0b1120] border border-red-500/50 p-3 rounded-xl cursor-pointer text-xs font-medium text-white transition">
                            <input type="radio" name="metode_pembayaran" value="Tunai" checked class="accent-red-600">
                            <i class="fa-solid fa-money-bill-wave text-emerald-400"></i> Tunai (Cash)
                        </label>
                        <label class="flex items-center justify-center gap-2 bg-[#0b1120] border border-gray-800 p-3 rounded-xl cursor-pointer text-xs font-medium text-gray-300 transition">
                            <input type="radio" name="metode_pembayaran" value="QRIS" class="accent-red-600">
                            <i class="fa-solid fa-qrcode text-blue-400"></i> QRIS
                        </label>
                        <label class="flex items-center justify-center gap-2 bg-[#0b1120] border border-gray-800 p-3 rounded-xl cursor-pointer text-xs font-medium text-gray-300 transition">
                            <input type="radio" name="metode_pembayaran" value="Transfer" class="accent-red-600">
                            <i class="fa-solid fa-building-columns text-yellow-400"></i> Transfer
                        </label>
                    </div>
                </div>

                <button type="submit" name="submit_booking" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-red-600/30 flex items-center justify-center gap-2 text-sm transition">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Pendaftaran Booking
                </button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-gray-800 bg-[#0b1120] py-8 text-center text-xs text-gray-500">
        <p>&copy; 2026 GARAGE 22 Garage & Service. All rights reserved.</p>
    </footer>

</body>
</html>