<?php
// Memulai session (opsional jika dibutuhkan)
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - SIPANTAS</title>
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

            <h1>Pemulihan Sandi</h1>
            <p>Masukkan email atau username terdaftar untuk mereset password Anda.</p>

            <!-- Form mengarah langsung ke file proses di dalam folder api/ -->
            <form action="api/proses-lupa-password.php" method="POST">

                <label for="recovery">Username atau Email</label>
                <input
                    type="text"
                    id="recovery"
                    name="recovery"
                    placeholder="Masukkan username atau email terdaftar"
                    required
                >

                <button type="submit" class="btn-login" style="margin-top: 15px;">
                    Kirim Instruksi Reset
                </button>

            </form>

            <a href="login.html" class="back-link" style="margin-top: 20px; display: block; text-align: center;">
                Kembali ke Halaman Login
            </a>

        </div>

    </div>

</body>
</html>