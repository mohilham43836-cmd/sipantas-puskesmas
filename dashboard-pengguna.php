<?php
session_start();
// Pastikan hanya warga yang bisa mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'warga') {
    echo "<script>alert('Silakan login terlebih dahulu sebagai warga!'); window.location.href = 'login.html';</script>";
    exit;
}

// Hubungkan ke database menggunakan file koneksi PDO
require_once 'api/koneksi.php';

// Ambil data klaster langsung dari database MySQL
try {
    $stmt = $pdo->query("SELECT * FROM klaster");
    $klasters = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $klasters = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Warga - SIPANTAS Puskesmas Sei Taiwan</title>
    <link rel="stylesheet" href="css/dashboard-pengguna.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- NAVBAR -->
    <header class="navbar">
        <div class="navbar-brand">
            <img src="gambar/logo-puskesmas.png" alt="Logo Puskesmas">
            <div>
                <h2>SIPANTAS</h2>
                <p>UPTD Puskesmas Sei Taiwan</p>
            </div>
        </div>
        <div>
            <span style="margin-right: 15px; font-size: 14px;"><i class="fa-solid fa-user-circle"></i> Halo, <strong><?php echo htmlspecialchars($_SESSION['nama'] ?? $_SESSION['username']); ?></strong></span>
            <a href="api/logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </header>

    <!-- KONTEN UTAMA -->
    <main class="container">
        
        <!-- Banner Sambutan -->
        <div class="welcome-banner">
            <h1>Sistem Informasi & Layanan Antrean Klaster Puskesmas</h1>
            <p>Pilih klaster pelayanan kesehatan di bawah ini sesuai kebutuhan pemeriksaan medis Anda, pantau antrean, dan ambil nomor antrean secara online.</p>
        </div>

        <!-- DAFTAR KLASTER DARI DATABASE -->
        <h2 style="font-size: 18px; color: #333; margin-bottom: 15px;"><i class="fa-solid fa-hospital-user" style="color: #008037; margin-right: 8px;"></i> Daftar Klaster Pelayanan Kesehatan</h2>
        
        <div class="klaster-grid">
            <?php if (!empty($klasters)): ?>
                <?php foreach ($klasters as $k): ?>
                    <div class="klaster-card">
                        <div>
                            <div class="klaster-header">
                                <h3><?php echo htmlspecialchars($k['nama_klaster']); ?></h3>
                                <span class="badge-buka">Buka</span>
                            </div>
                            <p class="klaster-desc"><?php echo htmlspecialchars($k['deskripsi']); ?></p>
                        </div>
                        
                        <div>
                            <div class="queue-box">
                                <span>Status Antrean Hari Ini</span>
                                <h2><?php echo 'K-' . sprintf('%03d', $k['id']); ?></h2>
                            </div>
                            <!-- Tombol Ambil Antrean -->
                            <a href="ambil-antrean.php?klaster_id=<?php echo $k['id']; ?>" class="btn-daftar">
                                <i class="fa-solid fa-ticket"></i> Ambil Nomor Antrean
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #777;">Belum ada data klaster yang tersedia di database.</p>
            <?php endif; ?>
        </div>

        <!-- Informasi Pelayanan -->
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <h3 style="font-size: 16px; color: #333; margin-bottom: 10px;"><i class="fa-solid fa-circle-info" style="color: #008037; margin-right: 8px;"></i> Informasi Penting</h3>
            <ul style="margin: 0; padding-left: 20px; color: #555; font-size: 14px; line-height: 1.8;">
                <li>Pastikan Anda memilih klaster pelayanan yang sesuai dengan jenis keluhan kesehatan Anda.</li>
                <li>Nomor antrean yang telah diambil akan tercatat secara otomatis di sistem database UPTD Puskesmas Sei Taiwan.</li>
                <li>Jam Pelayanan: Senin - Kamis (08.00 - 14.00 WITA) | Jumat (08.00 - 11.00 WITA).</li>
            </ul>
        </div>

    </main>

    <!-- FOOTER -->
    <footer>
        &copy; 2026 SIPANTAS - UPTD Puskesmas Sei Taiwan. All Rights Reserved.
    </footer>

</body>
</html>