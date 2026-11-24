<?php
// File: reset-password.php
require_once 'api/koneksi.php';

$token = $_GET['token'] ?? '';
$error = '';

if (empty($token)) {
    echo "<script>alert('Token verifikasi tidak valid!'); window.location.href = 'login.html';</script>";
    exit;
}

// Cek apakah token ada di database (pengecekan waktu kedaluwarsa dilewati agar langsung bisa digunakan)
$stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<script>alert('Token verifikasi tidak ditemukan di database!'); window.location.href = 'login.html';</script>";
    exit;
}

// Proses saat form disubmit untuk menyimpan password baru
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $passwordBaru = $_POST['password'] ?? '';
    $konfirmasi   = $_POST['konfirmasi_password'] ?? '';

    if (empty($passwordBaru) || strlen($passwordBaru) < 6) {
        $error = "Password baru minimal harus 6 karakter!";
    } elseif ($passwordBaru !== $konfirmasi) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        // Enkripsi password baru dengan aman
        $hashed = password_hash($passwordBaru, PASSWORD_DEFAULT);
        
        // Update password ke database dan kosongkan token agar tidak bisa dipakai ulang
        $update = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE id = ?");
        $update->execute([$hashed, $user['id']]);

        echo "<script>alert('Password berhasil diubah! Silakan login dengan password baru.'); window.location.href = 'login.html';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Password Baru - SIPANTAS</title>
    <link rel="stylesheet" href="css/login.css">
    <style>
        body {
            background: url('gambar/puskesmas.png') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>
<body>

    <div class="login-container">

        <div class="login-logo">
            <img src="gambar/logo-puskesmas.png" alt="Logo Puskesmas">
            <h2>SIPANTAS</h2>
            <p>Puskesmas Sei Taiwan</p>
        </div>

        <div class="login-form">

            <h1>Reset Password</h1>
            <p>Halo <strong><?php echo htmlspecialchars($user['nama'] ?? $user['username']); ?></strong>, masukkan sandi baru akun Anda.</p>

            <?php if (!empty($error)): ?>
                <div style="background: #fde8e8; color: #d9534f; padding: 10px 15px; border-radius: 6px; font-size: 13px; margin-bottom: 15px; border: 1px solid #f5c6cb;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <label for="password">Password Baru</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Minimal 6 karakter"
                    required
                >

                <label for="konfirmasi_password" style="margin-top: 10px; display: block;">Konfirmasi Password Baru</label>
                <input
                    type="password"
                    id="konfirmasi_password"
                    name="konfirmasi_password"
                    placeholder="Ulangi password baru"
                    required
                >

                <button type="submit" class="btn-login" style="margin-top: 15px;">
                    Simpan Password Baru
                </button>

            </form>

            <a href="login.html" class="back-link" style="margin-top: 20px; display: block; text-align: center;">
                Kembali ke Halaman Login
            </a>

        </div>

    </div>

</body>
</html>