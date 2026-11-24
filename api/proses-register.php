<?php
// Karena proses-register.php ada di dalam folder api, panggil koneksi.php langsung
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama     = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Data tambahan untuk tabel warga (bisa dikosongkan dulu atau diisi default)
    $telepon  = trim($_POST['telepon'] ?? '-');
    $alamat   = trim($_POST['alamat'] ?? '-');
    $nik      = trim($_POST['nik'] ?? '-');
    $role     = 'warga';

    if (empty($username) || empty($email) || empty($password)) {
        echo "<script>alert('Semua kolom wajib diisi!'); window.history.back();</script>";
        exit;
    }

    // Password di-hash agar aman
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Mulai transaksi database agar jika salah satu gagal, dibatalkan semua
        $pdo->beginTransaction();

        // 1. Masukkan ke tabel users terlebih dahulu
        $sqlUser = "INSERT INTO users (nama, username, email, password, role) VALUES (:nama, :username, :email, :password, :role)";
        $stmtUser = $pdo->prepare($sqlUser);
        $stmtUser->execute([
            ':nama'     => $nama,
            ':username' => $username,
            ':email'    => $email,
            ':password' => $hashed_password,
            ':role'     => $role
        ]);

        // Ambil ID dari user yang baru saja dimasukkan
        $userIdBaru = $pdo->lastInsertId();

        // 2. Masukkan juga data profilnya secara otomatis ke tabel warga
        $sqlWarga = "INSERT INTO warga (user_id, nik, telepon, alamat) VALUES (:user_id, :nik, :telepon, :alamat)";
        $stmtWarga = $pdo->prepare($sqlWarga);
        $stmtWarga->execute([
            ':user_id' => $userIdBaru,
            ':nik'     => $nik,
            ':telepon' => $telepon,
            ':alamat'  => $alamat
        ]);

        // Commit transaksi jika semuanya sukses
        $pdo->commit();

        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href = '../login.html';</script>";
        exit;

    } catch (PDOException $e) {
        // Batalkan transaksi jika terjadi error
        $pdo->rollBack();

        if ($e->getCode() == 23000) {
            echo "<script>alert('Username atau Email sudah digunakan!'); window.history.back();</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan: " . $e->getMessage() . "'); window.history.back();</script>";
        }
    }
}
?>