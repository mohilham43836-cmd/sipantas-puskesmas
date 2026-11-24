<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses khusus Admin!'); window.location.href = '../login.html';</script>";
    exit;
}

require_once 'api/koneksi.php';

$page = $_GET['page'] ?? 'dashboard';

// Statistik Ringkas
$total_pasien = $pdo->query("SELECT COUNT(*) FROM users WHERE role='warga'")->fetchColumn();
$antrean_hari_ini = $pdo->query("SELECT COUNT(*) FROM antrean WHERE tanggal = CURDATE()")->fetchColumn();
$total_layanan = $pdo->query("SELECT COUNT(*) FROM layanan")->fetchColumn();
$total_pengguna = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SIPANTAS</title>
    <link rel="stylesheet" href="css/dashboard-admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <!-- Sidebar Samping Kiri -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <img src="gambar/logo-puskesmas.png" alt="Logo">
            <div>
                <h2>SIPANTAS</h2>
                <p>Puskesmas Sei Taiwan</p>
            </div>
        </div>
        <div class="sidebar-menu">
            <div class="menu-category">Menu Utama</div>
            <a href="dashboard-admin.php?page=dashboard" class="<?php echo $page=='dashboard'?'active':''; ?>"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="dashboard-admin.php?page=pasien" class="<?php echo $page=='pasien'?'active':''; ?>"><i class="fa-solid fa-hospital-user"></i> Data Pasien</a>
            <a href="dashboard-admin.php?page=antrean" class="<?php echo $page=='antrean'?'active':''; ?>"><i class="fa-solid fa-ticket"></i> Manajemen Antrean</a>
            <a href="dashboard-admin.php?page=layanan" class="<?php echo $page=='layanan'?'active':''; ?>"><i class="fa-solid fa-notes-medical"></i> Data Layanan</a>

            <div class="menu-category">Manajemen</div>
            <a href="dashboard-admin.php?page=pengguna" class="<?php echo $page=='pengguna'?'active':''; ?>"><i class="fa-solid fa-users-gear"></i> Data Pengguna</a>
            <a href="dashboard-admin.php?page=laporan" class="<?php echo $page=='laporan'?'active':''; ?>"><i class="fa-solid fa-chart-pie"></i> Laporan</a>
            <a href="dashboard-admin.php?page=pengaturan" class="<?php echo $page=='pengaturan'?'active':''; ?>"><i class="fa-solid fa-gear"></i> Pengaturan</a>
        </div>
        <div class="sidebar-footer">
            <a href="api/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
        </div>
    </aside>

    <!-- Konten Utama Kanan -->
    <div class="main-content">
        <header class="top-header">
            <div class="header-title">
                <h2>Dashboard</h2>
                <p>Selamat datang di panel admin SIPANTAS</p>
            </div>
            <div class="header-user">
                <span><i class="fa-regular fa-calendar"></i> <?php echo date('d F Y'); ?></span>
                <div class="user-profile">
                    <i class="fa-solid fa-user-shield" style="color: #008037;"></i>
                    <span><strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                </div>
            </div>
        </header>

        <div class="container-body">
            <!-- 4 Kotak Statistik Atas -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <span>Total Pasien</span>
                        <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                    </div>
                    <h3><?php echo $total_pasien; ?></h3>
                    <small>Terdaftar</small>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <span>Antrean Hari Ini</span>
                        <div class="stat-icon"><i class="fa-solid fa-ticket"></i></div>
                    </div>
                    <h3><?php echo $antrean_hari_ini; ?></h3>
                    <small>Pasien masuk</small>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <span>Total Layanan</span>
                        <div class="stat-icon"><i class="fa-solid fa-stethoscope"></i></div>
                    </div>
                    <h3><?php echo $total_layanan; ?></h3>
                    <small>Kelompok layanan</small>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <span>Total Pengguna</span>
                        <div class="stat-icon"><i class="fa-solid fa-user-gear"></i></div>
                    </div>
                    <h3><?php echo $total_pengguna; ?></h3>
                    <small>Akun sistem</small>
                </div>
            </div>

            <?php if ($page === 'dashboard'): ?>
                <!-- TAMPILAN UTAMA DASHBOARD -->
                <div class="welcome-banner">
                    <h1>Selamat Datang, admin!</h1>
                    <p>Berikut adalah ringkasan aktivitas pelayanan Puskesmas Sei Taiwan</p>
                </div>

                <div class="card-section">
                    <div class="card-header-flex">
                        <h3>Antrean Terbaru</h3>
                        <a href="dashboard-admin.php?page=antrean" class="btn-link">Lihat Semua</a>
                    </div>
                    <div class="table-wrap">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pasien</th>
                                    <th>Nomor Antrean</th>
                                    <th>Layanan</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $antrean_terbaru = $pdo->query("
                                    SELECT a.*, u.nama AS nama_warga, k.nama_klaster 
                                    FROM antrean a 
                                    JOIN users u ON a.user_id = u.id 
                                    JOIN layanan l ON a.layanan_id = l.id 
                                    JOIN klaster k ON l.klaster_id = k.id 
                                    ORDER BY a.id DESC LIMIT 5
                                ")->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($antrean_terbaru as $i => $ant): 
                                ?>
                                    <tr>
                                        <td><?php echo $i + 1; ?></td>
                                        <td><?php echo htmlspecialchars($ant['nama_warga']); ?></td>
                                        <td><strong>K<?php echo $ant['layanan_id'].'-'.sprintf('%03d', $ant['nomor_antrean']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($ant['nama_klaster']); ?></td>
                                        <td><?php echo $ant['tanggal']; ?></td>
                                        <td><span class="badge badge-<?php echo $ant['status']; ?>"><?php echo $ant['status']; ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif ($page === 'pasien'): ?>
                <!-- DATA PASIEN -->
                <div class="card-section">
                    <h3 style="margin-bottom: 15px;"><i class="fa-solid fa-hospital-user"></i> Data Pasien Terdaftar</h3>
                    <div class="table-wrap">
                        <table class="data-table">
                            <thead>
                                <tr><th>No</th><th>Nama Lengkap</th><th>NIK</th><th>No Telepon</th><th>Email</th><th>Alamat</th></tr>
                            </thead>
                            <tbody>
                                <?php 
                                $data_pasien = $pdo->query("SELECT u.*, w.nik, w.telepon, w.alamat FROM users u LEFT JOIN warga w ON u.id=w.user_id WHERE u.role='warga'")->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($data_pasien as $i => $p): 
                                ?>
                                    <tr>
                                        <td><?php echo $i+1; ?></td>
                                        <td><strong><?php echo htmlspecialchars($p['nama']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($p['nik'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($p['telepon'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($p['email']); ?></td>
                                        <td><?php echo htmlspecialchars($p['alamat'] ?? '-'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif ($page === 'antrean'): ?>
                <!-- MANAJEMEN ANTREAN -->
                <div class="card-section">
                    <h3 style="margin-bottom: 15px;"><i class="fa-solid fa-ticket"></i> Manajemen Semua Antrean Pasien</h3>
                    <div class="table-wrap">
                        <table class="data-table">
                            <thead>
                                <tr><th>ID</th><th>Nama Pasien</th><th>No Antrean</th><th>Klaster</th><th>Tanggal</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                <?php 
                                $all_antrean = $pdo->query("SELECT a.*, u.nama AS nama_warga, k.nama_klaster FROM antrean a JOIN users u ON a.user_id=u.id JOIN layanan l ON a.layanan_id=l.id JOIN klaster k ON l.klaster_id=k.id ORDER BY a.id DESC")->fetchAll(PDO::FETCH_ASSOC);
                                foreach($all_antrean as $aa): 
                                ?>
                                    <tr>
                                        <td><?php echo $aa['id']; ?></td>
                                        <td><strong><?php echo htmlspecialchars($aa['nama_warga']); ?></strong></td>
                                        <td>K<?php echo $aa['layanan_id'].'-'.sprintf('%03d', $aa['nomor_antrean']); ?></td>
                                        <td><?php echo htmlspecialchars($aa['nama_klaster']); ?></td>
                                        <td><?php echo $aa['tanggal']; ?></td>
                                        <td><span class="badge badge-<?php echo $aa['status']; ?>"><?php echo $aa['status']; ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif ($page === 'layanan'): ?>
                <!-- DATA LAYANAN -->
                <div class="card-section">
                    <h3 style="margin-bottom: 15px;"><i class="fa-solid fa-notes-medical"></i> Data Layanan & Klaster Puskesmas</h3>
                    <div class="table-wrap">
                        <table class="data-table">
                            <thead>
                                <tr><th>ID</th><th>Nama Klaster</th><th>Nama Layanan</th><th>Deskripsi</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                <?php 
                                $daftar_layanan = $pdo->query("SELECT l.*, k.nama_klaster FROM layanan l JOIN klaster k ON l.klaster_id=k.id")->fetchAll(PDO::FETCH_ASSOC);
                                foreach($daftar_layanan as $dl): 
                                ?>
                                    <tr>
                                        <td><?php echo $dl['id']; ?></td>
                                        <td><strong><?php echo htmlspecialchars($dl['nama_klaster']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($dl['nama_layanan']); ?></td>
                                        <td><?php echo htmlspecialchars($dl['deskripsi']); ?></td>
                                        <td><span class="badge badge-selesai"><?php echo $dl['status']; ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif ($page === 'pengguna'): ?>
                <!-- DATA PENGGUNA (MANAJEMEN ROLE AKUN) -->
                <div class="card-section">
                    <h3 style="margin-bottom: 15px;"><i class="fa-solid fa-users-gear"></i> Manajemen Akun & Hak Akses (Admin / Petugas / Warga)</h3>
                    <form action="api/proses-admin.php" method="POST" class="form-grid" style="display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:10px; margin-bottom:15px;">
                        <input type="hidden" name="aksi" value="tambah_user">
                        <input type="text" name="nama" class="form-input" placeholder="Nama Lengkap" required>
                        <input type="text" name="username" class="form-input" placeholder="Username" required>
                        <input type="email" name="email" class="form-input" placeholder="Email" required>
                        <input type="password" name="password" class="form-input" placeholder="Password" required>
                        <select name="role" class="form-input" required>
                            <option value="warga">Warga</option>
                            <option value="petugas">Petugas</option>
                            <option value="admin">Admin</option>
                        </select>
                        <button type="submit" class="btn-primary">Tambah Akun</button>
                    </form>
                    <div class="table-wrap">
                        <table class="data-table">
                            <thead>
                                <tr><th>No</th><th>Nama</th><th>Username</th><th>Email</th><th>Role</th><th>Ubah Role</th><th>Aksi</th></tr>
                            </thead>
                            <tbody>
                                <?php 
                                $all_users = $pdo->query("SELECT id, nama, username, email, role FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($all_users as $i => $u): 
                                ?>
                                    <tr>
                                        <td><?php echo $i+1; ?></td>
                                        <td><strong><?php echo htmlspecialchars($u['nama']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($u['username']); ?></td>
                                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                                        <td><span class="badge badge-<?php echo $u['role']; ?>"><?php echo $u['role']; ?></span></td>
                                        <td>
                                            <form action="api/proses-admin.php" method="POST" class="inline-form">
                                                <input type="hidden" name="aksi" value="ubah_role">
                                                <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                                <select name="role" class="form-select-sm">
                                                    <option value="warga" <?php echo $u['role']=='warga'?'selected':''; ?>>Warga</option>
                                                    <option value="petugas" <?php echo $u['role']=='petugas'?'selected':''; ?>>Petugas</option>
                                                    <option value="admin" <?php echo $u['role']=='admin'?'selected':''; ?>>Admin</option>
                                                </select>
                                                <button type="submit" class="btn-primary" style="padding:4px 8px; font-size:11px;">Simpan</button>
                                            </form>
                                        </td>
                                        <td><a href="api/proses-admin.php?aksi=hapus_user&id=<?php echo $u['id']; ?>" class="btn-danger" onclick="return confirm('Hapus akun?');">Hapus</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif ($page === 'laporan'): ?>
                <!-- LAPORAN GRAFIK KLASTER -->
                <div class="card-section">
                    <h3 style="margin-bottom: 15px;"><i class="fa-solid fa-chart-pie"></i> Laporan Grafik Jumlah Pasien per Klaster</h3>
                    <div class="chart-grid">
                        <div class="chart-box"><canvas id="barChart"></canvas></div>
                        <div class="chart-box"><canvas id="doughnutChart"></canvas></div>
                    </div>
                </div>
                <?php 
                $laporan_klaster = $pdo->query("SELECT k.nama_klaster, COUNT(a.id) AS total FROM klaster k LEFT JOIN layanan l ON l.klaster_id = k.id LEFT JOIN antrean a ON a.layanan_id = l.id GROUP BY k.id")->fetchAll(PDO::FETCH_ASSOC);
                $lbls = []; $vals = [];
                foreach($laporan_klaster as $lk) { $lbls[] = $lk['nama_klaster']; $vals[] = (int)$lk['total']; }
                ?>
                <script>
                    const lbls = <?php echo json_encode($lbls); ?>;
                    const vals = <?php echo json_encode($vals); ?>;
                    const clrs = ['#008037', '#2ecc71', '#3498db', '#f1c40f', '#e67e22'];
                    new Chart(document.getElementById('barChart'), { type: 'bar', data: { labels: lbls, datasets: [{ label: 'Jumlah Pasien', data: vals, backgroundColor: clrs }] }, options: { responsive: true, maintainAspectRatio: false } });
                    new Chart(document.getElementById('doughnutChart'), { type: 'doughnut', data: { labels: lbls, datasets: [{ data: vals, backgroundColor: clrs }] }, options: { responsive: true, maintainAspectRatio: false } });
                </script>

            <?php elseif ($page === 'pengaturan'): ?>
                <!-- PENGATURAN (PERGANTIAN PERAWAT / DOKTER KLASTER) -->
                <div class="card-section">
                    <h3 style="margin-bottom: 15px;"><i class="fa-solid fa-gear"></i> Pengaturan Pergantian Perawat & Dokter per Klaster</h3>
                    <form action="api/proses-admin.php" method="POST" style="display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:10px; margin-bottom:15px;">
                        <input type="hidden" name="aksi" value="tambah_petugas_klaster">
                        <select name="klaster_id" class="form-input" required>
                            <?php 
                            $klasters = $pdo->query("SELECT id, nama_klaster FROM klaster")->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($klasters as $k): ?>
                                <option value="<?php echo $k['id']; ?>"><?php echo htmlspecialchars($k['nama_klaster']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="peran" class="form-input" required>
                            <option value="anggota">Pelaksana / Perawat</option>
                            <option value="pj">Penanggung Jawab</option>
                        </select>
                        <input type="text" name="jabatan" class="form-input" placeholder="Bidang Layanan" required>
                        <input type="text" name="nama_petugas" class="form-input" placeholder="Nama Perawat / Dokter" required>
                        <button type="submit" class="btn-primary">Tambah</button>
                    </form>
                    <div class="table-wrap">
                        <table class="data-table">
                            <thead>
                                <tr><th>Klaster</th><th>Posisi</th><th>Bidang & Nama Perawat/Dokter (Ganti Langsung)</th><th>Aksi</th></tr>
                            </thead>
                            <tbody>
                                <?php 
                                $tim_klaster = $pdo->query("SELECT t.*, k.nama_klaster FROM tim_klaster t JOIN klaster k ON t.klaster_id = k.id ORDER BY t.klaster_id ASC")->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($tim_klaster as $tim): 
                                ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($tim['nama_klaster']); ?></strong></td>
                                        <td><?php echo $tim['peran']=='pj'?'PJ':'Pelaksana'; ?></td>
                                        <td>
                                            <form action="api/proses-admin.php" method="POST" class="inline-form">
                                                <input type="hidden" name="aksi" value="ganti_petugas_klaster">
                                                <input type="hidden" name="tim_id" value="<?php echo $tim['id']; ?>">
                                                <input type="text" name="jabatan" value="<?php echo htmlspecialchars($tim['jabatan']); ?>" class="form-input" required>
                                                <input type="text" name="nama_petugas" value="<?php echo htmlspecialchars($tim['nama_petugas']); ?>" class="form-input" required>
                                                <button type="submit" class="btn-primary" style="padding:5px 10px; font-size:11px;">Ganti</button>
                                            </form>
                                        </td>
                                        <td><a href="api/proses-admin.php?aksi=hapus_petugas_klaster&id=<?php echo $tim['id']; ?>" class="btn-danger" onclick="return confirm('Hapus petugas?');">Hapus</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>