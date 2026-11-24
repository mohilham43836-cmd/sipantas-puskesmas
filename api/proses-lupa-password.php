<?php
// File: api/proses-lupa-password.php
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identifier = trim($_POST['recovery'] ?? '');

    if (empty($identifier)) {
        echo "<script>alert('Silakan masukkan username atau email terlebih dahulu!'); window.history.back();</script>";
        exit;
    }

    try {
        // Cek data user berdasarkan username atau email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$identifier, $identifier]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Buat token unik
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Simpan token ke database
            $update = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE id = ?");
            $update->execute([$token, $expiry, $user['id']]);

            // Buat tautan menuju halaman reset password
            $linkReset = "../reset-password.php?token=" . $token;

            // Tampilkan token dan tombol langsung di layar lokal
            echo "
            <!DOCTYPE html>
            <html lang='id'>
            <head>
                <meta charset='UTF-8'>
                <title>Token Reset Dibuat - SIPANTAS</title>
                <link rel='stylesheet' href='../css/login.css'>
                <style>
                    body { background: url('../gambar/puskesmas.png') no-repeat center center fixed; background-size: cover; display: flex; justify-content: center; align-items: center; height: 100vh; }
                    .box-token { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); width: 100%; max-width: 500px; text-align: center; border-top: 5px solid #008037; }
                    .token-box { background: #f8f9fa; border: 1px dashed #008037; padding: 10px; font-family: monospace; font-size: 12px; word-break: break-all; margin: 15px 0; border-radius: 6px; color: #333; }
                    .btn-lanjut { display: inline-block; background-color: #008037; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 10px; }
                </style>
            </head>
            <body>
                <div class='box-token'>
                    <h2 style='color: #008037; margin-bottom: 10px;'>Token Berhasil Dibuat!</h2>
                    <p style='font-size: 13px; color: #555; margin-bottom: 10px;'>Akun ditemukan: <b>" . htmlspecialchars($user['username']) . "</b></p>
                    <p style='font-size: 12px; color: #777;'>Berikut adalah token dan tautan reset sandi Anda:</p>
                    
                    <div class='token-box'>" . htmlspecialchars($token) . "</div>

                    <a href='" . $linkReset . "' class='btn-lanjut'>Klik untuk Masukkan Password Baru</a>
                </div>
            </body>
            </html>
            ";
            exit;

        } else {
            echo "<script>alert('Username atau Email tidak ditemukan di dalam sistem!'); window.history.back();</script>";
        }

    } catch (PDOException $e) {
        echo "<script>alert('Terjadi kesalahan database: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    }
}
?>