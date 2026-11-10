<?php
session_start();
include 'config.php';

$error = '';
if (isset($_POST['login_admin'])) {
    $input_key = trim($_POST['private_key']);
    
    // Private Key untuk login admin (Bisa diubah di Baris 10)
    $correct_key = "G22-ADMIN-KEY-2026";

    if ($input_key === $correct_key) {
        $_SESSION['admin_logged'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $error = "Private Key tidak valid! Akses ditolak.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - GARAGE 22</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            background-color: #0b1120; 
            color: #f1f5f9; 
        }
    </style>
</head>
<body class="font-sans antialiased min-h-screen flex items-center justify-center p-4">

    <!-- Background Glow Effect -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none flex items-center justify-center">
        <div class="w-[500px] h-[500px] bg-red-600/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Login Card -->
    <div class="bg-gray-900 border border-gray-800 p-8 md:p-10 rounded-3xl w-full max-w-md shadow-2xl relative z-10">
        
        <!-- Header & Logo -->
        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-red-600 rounded-2xl flex items-center justify-center text-white font-black text-xl mx-auto mb-4 shadow-lg shadow-red-600/40 tracking-tighter">
                G22
            </div>
            <h1 class="text-2xl font-extrabold text-white">Authentikasi Admin</h1>
            <p class="text-xs text-gray-400 mt-1.5">Masukkan Private Key enkripsi untuk mengakses dashboard kelola bengkel GARAGE 22.</p>
        </div>

        <!-- Error Message -->
        <?php if ($error): ?>
            <div class="mb-6 p-4 bg-red-950/60 border border-red-800 text-red-400 text-xs rounded-2xl text-center font-medium flex items-center justify-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-sm"></i>
                <?= $error; ?>
            </div>
        <?php endif; ?>

        <!-- Form Input Private Key -->
        <form action="" method="POST" class="space-y-6">
            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-2">Private Key Kriptografi <span class="text-red-500">*</span></label>
                <div class="relative">
                    <i class="fa-solid fa-key absolute left-4 top-4 text-gray-500 text-xs"></i>
                    <input type="password" name="private_key" required placeholder="Masukkan private key admin..." class="w-full bg-[#0b1120] border border-gray-800 rounded-xl py-3.5 pl-11 pr-4 text-xs text-white placeholder-gray-600 focus:outline-none focus:border-red-500 transition">
                </div>
                <div class="flex items-center justify-between mt-2.5 px-1">
                    <span class="text-[10px] text-gray-500">Kunci bawaan:</span>
                    <code class="text-[10px] font-mono text-red-400 bg-red-950/40 px-2 py-0.5 rounded border border-red-900/40">G22-ADMIN-KEY-2026</code>
                </div>
            </div>

            <button type="submit" name="login_admin" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-red-600/30 text-xs flex items-center justify-center gap-2 transition">
                <i class="fa-solid fa-shield-halved"></i> Masuk Ke Dashboard
            </button>
        </form>

        <!-- Link Kembali -->
        <div class="mt-8 text-center pt-6 border-t border-gray-800">
            <a href="index.php" class="text-xs text-gray-400 hover:text-white font-medium transition inline-flex items-center gap-2">
                <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Landing Page
            </a>
        </div>
    </div>

</body>
</html>