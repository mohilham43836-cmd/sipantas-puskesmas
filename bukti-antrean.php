<?php
session_start();
if (!isset($_SESSION['bukti_antrean'])) {
    header("Location: dashboard-pengguna.php");
    exit;
}
$bukti = $_SESSION['bukti_antrean'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Nomor Antrean - SIPANTAS</title>
    <link rel="stylesheet" href="css/bukti-antrean.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <header class="navbar">
        <div class="navbar-brand">
            <img src="gambar/logo-puskesmas.png" alt="Logo Puskesmas">
            <div>
                <h2>SIPANTAS</h2>
                <p>UPTD Puskesmas Sei Taiwan</p>
            </div>
        </div>
    </header>

    <main class="ticket-wrapper">
        <div class="ticket-card">
            <div class="icon-success">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h2 class="ticket-title">Nomor Antrean Berhasil Disimpan</h2>
            <p class="ticket-subtitle">Data antrean Anda telah tercatat di sistem Puskesmas Sei Taiwan</p>

            <div class="ticket-box">
                <span class="ticket-klaster"><?php echo htmlspecialchars($bukti['nama_klaster']); ?></span>
                <h1 class="ticket-number"><?php echo htmlspecialchars($bukti['nomor_antrean']); ?></h1>
                <p class="ticket-info">Pasien: <strong><?php echo htmlspecialchars($bukti['nama_warga']); ?></strong></p>
                <p class="ticket-date">Tanggal Kunjungan: <?php echo htmlspecialchars($bukti['tanggal']); ?></p>
            </div>

            <p class="ticket-note">
                Silakan tunjukkan atau cetak tiket nomor antrean ini kepada petugas loket pendaftaran saat tiba di Puskesmas.
            </p>

            <div class="ticket-actions">
                <button onclick="window.print()" class="btn-print">
                    <i class="fa-solid fa-print"></i> Cetak Tiket
                </button>
                <a href="dashboard-pengguna.php" class="btn-dashboard">
                    <i class="fa-solid fa-house"></i> Ke Dashboard
                </a>
            </div>
        </div>
    </main>

    <footer>
        &copy; 2026 SIPANTAS - UPTD Puskesmas Sei Taiwan. All Rights Reserved.
    </footer>

</body>
</html>
