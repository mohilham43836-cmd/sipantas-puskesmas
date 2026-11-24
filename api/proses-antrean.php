<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'warga') {
    echo "<script>alert('Silakan login terlebih dahulu sebagai warga!'); window.location.href = '../login.html';</script>";
    exit;
}

$user_id    = $_SESSION['user_id'];
$klaster_id = $_GET['klaster_id'] ?? null;

if (!$klaster_id) {
    echo "<script>alert('Klaster tidak valid!'); window.location.href = '../dashboard-pengguna.php';</script>";
    exit;
}

try {
    // 1. Ambil info klaster
    $stmtKlaster = $pdo->prepare("SELECT * FROM klaster WHERE id = :id");
    $stmtKlaster->execute([':id' => $klaster_id]);
    $klaster = $stmtKlaster->fetch(PDO::FETCH_ASSOC);

    if (!$klaster) {
        echo "<script>alert('Data klaster tidak ditemukan!'); window.location.href = '../dashboard-pengguna.php';</script>";
        exit;
    }

    // 2. Ambil layanan_id yang terhubung dengan klaster ini dari tabel 'layanan'
    $stmtLayanan = $pdo->prepare("SELECT id FROM layanan WHERE klaster_id = :klaster_id LIMIT 1");
    $stmtLayanan->execute([':klaster_id' => $klaster_id]);
    $layanan = $stmtLayanan->fetch(PDO::FETCH_ASSOC);

    $layanan_id = $layanan ? $layanan['id'] : $klaster_id;

    // 3. Cek apakah warga sudah punya antrean menunggu hari ini pada layanan tersebut
    $hari_ini = date('Y-m-d');
    $stmtCek = $pdo->prepare("SELECT id FROM antrean WHERE user_id = :user_id AND layanan_id = :layanan_id AND tanggal = :tanggal AND status = 'menunggu'");
    $stmtCek->execute([
        ':user_id'    => $user_id,
        ':layanan_id' => $layanan_id,
        ':tanggal'    => $hari_ini
    ]);

    if ($stmtCek->rowCount() > 0) {
        echo "<script>alert('Anda sudah memiliki nomor antrean aktif untuk layanan ini hari ini!'); window.location.href = '../dashboard-pengguna.php';</script>";
        exit;
    }

    // 4. Hitung nomor urut antrean (tipe INT sesuai kolom nomor_antrean di database)
    $stmtCount = $pdo->prepare("SELECT COALESCE(MAX(nomor_antrean), 0) AS max_nomor FROM antrean WHERE layanan_id = :layanan_id AND tanggal = :tanggal");
    $stmtCount->execute([
        ':layanan_id' => $layanan_id,
        ':tanggal'    => $hari_ini
    ]);
    $nomor_urut = (int)$stmtCount->fetch(PDO::FETCH_ASSOC)['max_nomor'] + 1;

    // 5. Simpan ke tabel 'antrean' sesuai kolom asli database
    $sqlInsert = "INSERT INTO antrean (user_id, layanan_id, tanggal, nomor_antrean, status) 
                  VALUES (:user_id, :layanan_id, :tanggal, :nomor_antrean, 'menunggu')";
    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->execute([
        ':user_id'       => $user_id,
        ':layanan_id'    => $layanan_id,
        ':tanggal'       => $hari_ini,
        ':nomor_antrean' => $nomor_urut
    ]);

    // Format tampilan tiket untuk layar pengguna (misal: K2-001)
    $kode_tampil = 'K' . $klaster_id . '-' . sprintf('%03d', $nomor_urut);

    $_SESSION['bukti_antrean'] = [
        'nama_warga'    => $_SESSION['nama'] ?? $_SESSION['username'],
        'nama_klaster'  => $klaster['nama_klaster'],
        'nomor_antrean' => $kode_tampil,
        'tanggal'       => date('d-m-Y')
    ];

    header("Location: ../bukti-antrean.php");
    exit;

} catch (PDOException $e) {
    echo "<script>alert('Gagal menyimpan antrean: " . addslashes($e->getMessage()) . "'); window.location.href = '../dashboard-pengguna.php';</script>";
    exit;
}
?>