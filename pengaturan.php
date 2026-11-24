<?php
session_start();

// Proteksi halaman: pastikan sudah login dan rolenya admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.html");
    exit;
}

$namaAdmin = htmlspecialchars(
    $_SESSION["nama"] ?? $_SESSION["username"] ?? "Administrator",
    ENT_QUOTES,
    "UTF-8"
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - SIPANTAS</title>
    <!-- Menghubungkan CSS dashboard agar tampilannya seragam -->
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- Sidebar (Menu Navigasi Samping) -->
    <div class="sidebar">
        <div class="logo-container">
            <img src="gambar/logo-puskesmas.png" alt="Logo Puskesmas">
            <h2>SIPANTAS</h2>
            <p class="sub-title">Puskesmas Sei Taiwan</p>
        </div>

        <div class="menu-label">Menu Utama</div>
        <ul>
            <li><a href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
            <li><a href="pasien.php"><i class="fa-solid fa-hospital-user"></i> Data Pasien</a></li>
            <li><a href="antrean.php"><i class="fa-solid fa-list-ol"></i> Manajemen Antrean</a></li>
            <li><a href="layanan.php"><i class="fa-solid fa-stethoscope"></i> Data Layanan</a></li>
        </ul>

        <div class="menu-label">Manajemen</div>
        <ul>
            <li><a href="pengguna.php"><i class="fa-solid fa-users"></i> Data Pengguna</a></li>
            <li><a href="laporan.php"><i class="fa-solid fa-chart-line"></i> Laporan</a></li>
            <li><a href="pengaturan.php" style="background-color: rgba(255,255,255,0.2);"><i class="fa-solid fa-gear"></i> Pengaturan</a></li>
        </ul>

        <a href="api/logout.php" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        <header>
            <h1>Pengaturan Sistem</h1>
            <div class="user-profile">
                <i class="fa-solid fa-user"></i> Halo, <strong><?php echo $namaAdmin; ?></strong>
            </div>
        </header>

        <div class="container">
            <div class="card">
                <h2>Pengaturan Akun & Website</h2>
                <p>Kelola konfigurasi sistem Puskesmas Sei Taiwan dan ubah preferensi akun admin di sini.</p>
                <hr style="margin: 20px 0; border: 0; border-top: 1px solid #ddd;">
                <p><em>Fitur pengaturan lanjutan (seperti ganti password dan profil web) akan segera ditambahkan di sini.</em></p>
            </div>
        </div>
    </div>

</body>
</html>