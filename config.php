<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_motocare";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}

// Kunci Rahasia untuk Enkripsi & Dekripsi
define('ENCRYPTION_KEY', 'Garage22SecretKey2026SecureKey!!');
define('AES_METHOD', 'aes-256-cbc');

// Fungsi Enkripsi Data
function encryptData($data) {
    if (empty($data)) return "";
    $key = hash('sha256', ENCRYPTION_KEY, true);
    $iv_length = openssl_cipher_iv_length(AES_METHOD);
    $iv = openssl_random_pseudo_bytes($iv_length);
    $encrypted = openssl_encrypt($data, AES_METHOD, $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

// Fungsi Dekripsi Data yang Aman dari Warning Data Lama
function decryptData($data) {
    if (empty($data)) return "";
    
    $decoded = base64_decode($data, true);
    if ($decoded === false) {
        // Jika data bukan base64 (artinya teks biasa / data lama), langsung kembalikan
        return $data; 
    }

    $iv_length = openssl_cipher_iv_length(AES_METHOD);
    if (strlen($decoded) < $iv_length) {
        return $data; // Jika panjang data tidak sesuai format enkripsi, kembalikan apa adanya
    }

    $iv = substr($decoded, 0, $iv_length);
    $encrypted = substr($decoded, $iv_length);
    $key = hash('sha256', ENCRYPTION_KEY, true);
    
    $decrypted = openssl_decrypt($encrypted, AES_METHOD, $key, 0, $iv);
    
    // Jika dekripsi gagal (misal data lama), kembalikan data aslinya
    return ($decrypted !== false) ? $decrypted : $data;
}
?>