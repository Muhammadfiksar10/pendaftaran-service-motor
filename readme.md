# 🛠️ GARAGE 22 - Sistem Informasi Booking Service & Kelola Bengkel

GARAGE 22 adalah aplikasi web berbasis **PHP Native** dan **MySQL** yang dilengkapi dengan sistem keamanan kriptografi **AES-256-CBC** untuk melindungi data sensitif pelanggan, serta autentikasi **Private Key** untuk akses dashboard admin.

---

## 🚀 Fitur Utama
1. **Public Landing Page & Booking Online**: Pelanggan dapat mendaftar antrean service motor secara online tanpa harus mengantre lama di bengkel.
2. **Kriptografi Enkripsi Data (AES-256-CBC)**: Data sensitif pelanggan (seperti Nama Pemilik, No. WhatsApp, dan Keluhan) secara otomatis **dienkripsi** sebelum disimpan ke dalam database MySQL.
3. **Secure Admin Login (Private Key)**: Halaman login admin menggunakan kunci privat rahasia (*Private Key*) alih-alih password biasa.
4. **Admin Dashboard (Real-time Decryption)**: Data yang terenkripsi di database akan **didekripsi** secara otomatis dan real-time saat ditampilkan di tabel dashboard admin agar mudah dikelola.
5. **Kelola Status Antrean & Input Manual**: Admin dapat mengubah status pengerjaan (Menunggu, Diproses/Dalam Pengerjaan, Selesai, Dibatalkan) serta menambahkan antrean manual untuk pelanggan *walk-in*.

---

## 💻 Tech Stack
* **Frontend**: HTML5, Tailwind CSS (via CDN), FontAwesome Icons.
* **Backend**: PHP Native (Procedural & OpenSSL functions).
* **Database**: MySQL / MariaDB (via Laragon).
* **Server Lokal**: Apache (Laragon).

---

## 📂 Struktur Direktori Project
```text
motocare/
├── config.php          # Konfigurasi database, kunci enkripsi, & fungsi enkripsi/dekripsi
├── index.php           # Landing page utama & form pendaftaran booking online
├── login_admin.php     # Halaman login khusus admin menggunakan Private Key
├── admin.php           # Dashboard pengelolaan bengkel & dekripsi data antrean
└── update_status.php   # Skrip pemroses perubahan status antrean