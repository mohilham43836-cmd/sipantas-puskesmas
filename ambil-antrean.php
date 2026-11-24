<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'warga') {
    echo "<script>alert('Silakan login terlebih dahulu sebagai warga!'); window.location.href = 'login.html';</script>";
    exit;
}

require_once 'api/koneksi.php';

$klaster_id = $_GET['klaster_id'] ?? null;
if (!$klaster_id) {
    header("Location: dashboard-pengguna.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM klaster WHERE id = :id");
$stmt->execute([':id' => $klaster_id]);
$klaster = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$klaster) {
    header("Location: dashboard-pengguna.php");
    exit;
}

// Data Struktur & Jadwal UPTD Puskesmas Sei Taiwan
$data_klaster_detail = [
    1 => [
        'pj' => 'Yurianto, SKM',
        'anggota' => [
            ['jabatan' => 'Manajemen Arsip & Kepegawaian', 'nama' => 'Sri Wulan Handayani, SKM'],
            ['jabatan' => 'Manajemen Keuangan', 'nama' => 'Jumriani, A.Md.Keb'],
            ['jabatan' => 'Manajemen Mutu & Keselamatan Pasien', 'nama' => 'Muhammad Syahrudin, S.Far, Apt'],
            ['jabatan' => 'Manajemen Jejaring', 'nama' => 'Tandipau, AMK'],
            ['jabatan' => 'Manajemen Sumber Daya & Puskesmas', 'nama' => 'Yusrianto, SKM'],
            ['jabatan' => 'Manajemen Data & Informasi', 'nama' => 'Muhammad Syahrudin, S.Si, Apt'],
            ['jabatan' => 'Manajemen Sapras & Perbekalan Kes.', 'nama' => 'Jumiati, A.Md.Farm']
        ],
        'jadwal' => [
            ['hari' => 'Senin - Kamis', 'jam' => '08.00 - 14.00 WITA', 'layanan' => 'Administrasi, Tata Usaha & Pengaduan'],
            ['hari' => 'Jumat', 'jam' => '08.00 - 11.00 WITA', 'layanan' => 'Administrasi & Surat Menyurat']
        ]
    ],
    2 => [
        'pj' => 'dr. Chandra Wulan HP',
        'anggota' => [
            ['jabatan' => 'Ibu Hamil, Bersalin & Nifas', 'nama' => 'Resky Amelia, S.ST'],
            ['jabatan' => 'Bayi, Balita & Pra Sekolah', 'nama' => 'Rini Wijaya, A.Md.Keb'],
            ['jabatan' => 'Usia Sekolah & Remaja', 'nama' => 'drg. Faslyna']
        ],
        'jadwal' => [
            ['hari' => 'Senin & Rabu', 'jam' => '08.00 - 13.30 WITA', 'layanan' => 'Pemeriksaan Ibu Hamil (ANC) & Nifas'],
            ['hari' => 'Selasa & Kamis', 'jam' => '08.00 - 13.30 WITA', 'layanan' => 'Imunisasi Bayi, Balita & Pemeriksaan Anak'],
            ['hari' => 'Jumat', 'jam' => '08.00 - 11.00 WITA', 'layanan' => 'Konseling Kesehatan Remaja & Anak']
        ]
    ],
    3 => [
        'pj' => 'dr. Anisa Aditya',
        'anggota' => [
            ['jabatan' => 'Pelayanan Usia Dewasa', 'nama' => 'Tandipau, A.Md.Kep'],
            ['jabatan' => 'Pelayanan Lansia', 'nama' => 'Ima Halimah, A.Md.Kep'],
            ['jabatan' => 'KB dan Calon Pengantin (Catin)', 'nama' => 'Evitaria, A.Md.Keb']
        ],
        'jadwal' => [
            ['hari' => 'Senin - Kamis', 'jam' => '08.00 - 14.00 WITA', 'layanan' => 'Pemeriksaan Umum Dewasa & Skrining PTM'],
            ['hari' => 'Rabu & Jumat', 'jam' => '08.00 - 11.00 WITA', 'layanan' => 'Posyandu Lansia, Pelayanan KB & Catin']
        ]
    ],
    4 => [
        'pj' => 'Tandipau, AMK',
        'anggota' => [
            ['jabatan' => 'Surveilans Epidemiologi', 'nama' => 'Tandipau, AMK'],
            ['jabatan' => 'Kesehatan Lingkungan (Kesling)', 'nama' => 'Dahlia, SKM']
        ],
        'jadwal' => [
            ['hari' => 'Senin - Kamis', 'jam' => '08.00 - 14.00 WITA', 'layanan' => 'Pelacakan Penyakit Menular & TB/Kusta'],
            ['hari' => 'Selasa & Kamis', 'jam' => '09.00 - 13.00 WITA', 'layanan' => 'Konsultasi Sanitasi & Kesehatan Lingkungan']
        ]
    ],
    5 => [
        'pj' => 'Muhammad Syahrudin, S.Si, Apt',
        'anggota' => [
            ['jabatan' => 'Pelayanan Gawat Darurat (UGD)', 'nama' => 'Erwin, A.Md.Kep'],
            ['jabatan' => 'Pelayanan Laboratorium', 'nama' => 'Mutia Diniasti, S.Tr.Kes'],
            ['jabatan' => 'Pelayanan Kefarmasian / Apotek', 'nama' => 'Muhammad Syahrudin, S.Si, Apt'],
            ['jabatan' => 'Pelayanan Kesehatan Gigi & Mulut', 'nama' => 'drg. Faslyna']
        ],
        'jadwal' => [
            ['hari' => 'Setiap Hari (24 Jam)', 'jam' => '00.00 - 24.00 WITA', 'layanan' => 'Unit Gawat Darurat (UGD) & Persalinan'],
            ['hari' => 'Senin - Kamis', 'jam' => '08.00 - 14.00 WITA', 'layanan' => 'Poli Gigi, Cek Laboratorium & Pengambilan Obat'],
            ['hari' => 'Jumat', 'jam' => '08.00 - 11.00 WITA', 'layanan' => 'Poli Gigi, Cek Laboratorium & Pengambilan Obat']
        ]
    ]
];

$detail = $data_klaster_detail[$klaster_id] ?? $data_klaster_detail[1];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail <?php echo htmlspecialchars($klaster['nama_klaster']); ?> - SIPANTAS</title>
    <link rel="stylesheet" href="css/ambil-antrean.css">
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
        <a href="dashboard-pengguna.php" class="btn-kembali">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </header>

    <main class="container">
        <div class="klaster-banner">
            <div>
                <h1><?php echo htmlspecialchars($klaster['nama_klaster']); ?></h1>
                <p><?php echo htmlspecialchars($klaster['deskripsi']); ?></p>
            </div>
            <a href="api/proses-antrean.php?klaster_id=<?php echo $klaster['id']; ?>" class="btn-konfirmasi">
                <i class="fa-solid fa-ticket"></i> Konfirmasi & Ambil Nomor Antrean
            </a>
        </div>

        <section class="section-card">
            <h2 class="section-title">
                <i class="fa-solid fa-sitemap"></i> Bagan Struktur & Tim Pelaksana Klaster
            </h2>
            <p class="section-subtitle">Susunan Penanggung Jawab dan Tenaga Medis UPTD Puskesmas Sei Taiwan</p>

            <div class="org-chart">
                <div class="org-pj">
                    <span>Penanggung Jawab Klaster</span>
                    <h4><i class="fa-solid fa-user-doctor"></i> <?php echo htmlspecialchars($detail['pj']); ?></h4>
                </div>

                <div class="org-line-vertical"></div>

                <div class="org-members">
                    <?php foreach ($detail['anggota'] as $anggota): ?>
                        <div class="member-card">
                            <small><?php echo htmlspecialchars($anggota['jabatan']); ?></small>
                            <strong><i class="fa-solid fa-user-nurse"></i> <?php echo htmlspecialchars($anggota['nama']); ?></strong>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="section-card">
            <h2 class="section-title">
                <i class="fa-solid fa-calendar-days"></i> Jadwal Pelayanan Dokter & Perawat
            </h2>
            <p class="section-subtitle">Informasi jam operasional dan jenis kegiatan pemeriksaan pada klaster ini</p>

            <table class="schedule-table">
                <thead>
                    <tr>
                        <th>Hari Pelayanan</th>
                        <th>Jam Operasional</th>
                        <th>Jenis Kegiatan / Layanan Medis</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detail['jadwal'] as $j): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($j['hari']); ?></strong></td>
                            <td><span class="badge-jam"><?php echo htmlspecialchars($j['jam']); ?></span></td>
                            <td><?php echo htmlspecialchars($j['layanan']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="action-footer">
                <a href="api/proses-antrean.php?klaster_id=<?php echo $klaster['id']; ?>" class="btn-konfirmasi">
                    <i class="fa-solid fa-check-circle"></i> Lanjut Ambil Nomor Antrean Sekarang
                </a>
            </div>
        </section>
    </main>

    <footer>
        &copy; 2026 SIPANTAS - UPTD Puskesmas Sei Taiwan. All Rights Reserved.
    </footer>

</body>
</html>