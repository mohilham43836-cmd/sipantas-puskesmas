<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak! Khusus Admin.'); window.location.href = '../login.html';</script>";
    exit;
}

$aksi = $_POST['aksi'] ?? $_GET['aksi'] ?? '';

try {
    // 1. TAMBAH AKUN BARU OLEH ADMIN (Admin / Petugas / Warga)
    if ($aksi === 'tambah_user') {
        $nama     = trim($_POST['nama']);
        $username = trim($_POST['username']);
        $email    = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role     = $_POST['role'];

        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO users (nama, username, email, password, role) VALUES (:nama, :username, :email, :password, :role)");
        $stmt->execute([
            ':nama'     => $nama,
            ':username' => $username,
            ':email'    => $email,
            ':password' => $password,
            ':role'     => $role
        ]);
        $new_id = $pdo->lastInsertId();

        // Jika role warga, otomatis buatkan baris di tabel warga
        if ($role === 'warga') {
            $stmtWarga = $pdo->prepare("INSERT INTO warga (user_id, nik, telepon, alamat) VALUES (:uid, '-', '-', '-')");
            $stmtWarga->execute([':uid' => $new_id]);
        }

        $pdo->commit();
        echo "<script>alert('Akun baru berhasil ditambahkan sebagai " . strtoupper($role) . "!'); window.location.href = '../dashboard-admin.php';</script>";
        exit;
    }

    // 2. UBAH ROLE AKUN (Admin / Petugas / Warga)
    if ($aksi === 'ubah_role') {
        $target_id = $_POST['user_id'];
        $role_baru = $_POST['role'];

        $stmt = $pdo->prepare("UPDATE users SET role = :role WHERE id = :id");
        $stmt->execute([':role' => $role_baru, ':id' => $target_id]);

        // Pastikan jika diubah ke warga dan belum ada di tabel warga, otomatis ditambahkan
        if ($role_baru === 'warga') {
            $cek = $pdo->prepare("SELECT id FROM warga WHERE user_id = :uid");
            $cek->execute([':uid' => $target_id]);
            if ($cek->rowCount() === 0) {
                $ins = $pdo->prepare("INSERT INTO warga (user_id, nik, telepon, alamat) VALUES (:uid, '-', '-', '-')");
                $ins->execute([':uid' => $target_id]);
            }
        }

        echo "<script>alert('Role pengguna berhasil diperbarui!'); window.location.href = '../dashboard-admin.php';</script>";
        exit;
    }

    // 3. HAPUS AKUN PENGGUNA
    if ($aksi === 'hapus_user') {
        $target_id = $_GET['id'];
        if ($target_id == $_SESSION['user_id']) {
            echo "<script>alert('Anda tidak dapat menghapus akun Anda sendiri yang sedang login!'); window.location.href = '../dashboard-admin.php';</script>";
            exit;
        }
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute([':id' => $target_id]);
        echo "<script>alert('Akun pengguna berhasil dihapus!'); window.location.href = '../dashboard-admin.php';</script>";
        exit;
    }

    // 4. GANTI NAMA PERAWAT / DOKTER PADA KLASTER
    if ($aksi === 'ganti_petugas_klaster') {
        $tim_id       = $_POST['tim_id'];
        $nama_petugas = trim($_POST['nama_petugas']);
        $jabatan      = trim($_POST['jabatan']);

        $stmt = $pdo->prepare("UPDATE tim_klaster SET nama_petugas = :nama, jabatan = :jabatan WHERE id = :id");
        $stmt->execute([
            ':nama'    => $nama_petugas,
            ':jabatan' => $jabatan,
            ':id'      => $tim_id
        ]);

        echo "<script>alert('Data perawat/dokter klaster berhasil diperbarui!'); window.location.href = '../dashboard-admin.php';</script>";
        exit;
    }

    // 5. TAMBAH PERAWAT / ANGGOTA BARU KE KLASTER
    if ($aksi === 'tambah_petugas_klaster') {
        $klaster_id   = $_POST['klaster_id'];
        $peran        = $_POST['peran'];
        $jabatan      = trim($_POST['jabatan']);
        $nama_petugas = trim($_POST['nama_petugas']);

        $stmt = $pdo->prepare("INSERT INTO tim_klaster (klaster_id, peran, jabatan, nama_petugas) VALUES (:kid, :peran, :jabatan, :nama)");
        $stmt->execute([
            ':kid'     => $klaster_id,
            ':peran'   => $peran,
            ':jabatan' => $jabatan,
            ':nama'    => $nama_petugas
        ]);

        echo "<script>alert('Anggota medis klaster berhasil ditambahkan!'); window.location.href = '../dashboard-admin.php';</script>";
        exit;
    }

    // 6. HAPUS ANGGOTA MEDIS DARI KLASTER
    if ($aksi === 'hapus_petugas_klaster') {
        $tim_id = $_GET['id'];
        $stmt = $pdo->prepare("DELETE FROM tim_klaster WHERE id = :id");
        $stmt->execute([':id' => $tim_id]);
        echo "<script>alert('Anggota medis klaster berhasil dihapus!'); window.location.href = '../dashboard-admin.php';</script>";
        exit;
    }

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "<script>alert('Terjadi kesalahan: " . addslashes($e->getMessage()) . "'); window.location.href = '../dashboard-admin.php';</script>";
    exit;
}
?>