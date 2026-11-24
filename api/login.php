<?php
// File: api/login.php

session_start();
require_once 'koneksi.php'; // Menghubungkan ke database MySQL

// Ambil data dari form login
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? ''; // Di sini pengguna bisa memasukkan password biasa ATAU token reset

try {
    // Cek langsung ke database MySQL (untuk semua role: admin, petugas, warga)
    $sql = "SELECT * FROM users WHERE username = :username OR email = :username LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Cek apakah password valid ATAU input cocok dengan token reset yang tersimpan
    $isPasswordValid = $user && ($password === $user['password'] || password_verify($password, $user['password']));
    $isTokenValid    = $user && (!empty($user['reset_token']) && hash_equals($user['reset_token'], $password));

    if ($user && ($isPasswordValid || $isTokenValid)) {
        
        // Set session login dari database
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['nama']     = $user['nama'] ?? $user['username'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

        // Jika login menggunakan token reset, hapus tokennya dan arahkan ke halaman buat password baru
        if ($isTokenValid) {
            $clear = $pdo->prepare("UPDATE users SET reset_token = NULL, reset_expiry = NULL WHERE id = ?");
            $clear->execute([$user['id']]);

            header("Location: ../reset-password.php?token=" . $password);
            exit;
        }

        // Arahkan berdasarkan role di database jika login normal
        if ($user['role'] === 'admin') {
            header("Location: ../dashboard-admin.php");
            exit;
        } elseif ($user['role'] === 'petugas') {
            header("Location: ../dashboard-petugas.php");
            exit;
        } else {
            header("Location: ../dashboard-pengguna.php");
            exit;
        }

    } else {
        // Jika data tidak ditemukan / password / token salah
        header("Location: ../login.html?error=1");
        exit;
    }

} catch (PDOException $e) {
    // Jika terjadi kendala database
    header("Location: ../login.html?error=1");
    exit;
}
?>