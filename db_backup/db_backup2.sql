SELECT * FROM users;
select * FROM klaster;
select * from layanan;
select * from antrean;
select * from petugas;
select * from warga;
select * from riwayat_antrean;









UPDATE users SET role = 'warga', nama = 'Moh Ilham' WHERE username = 'Mohilham234#';
CREATE TABLE IF NOT EXISTS tim_klaster (
    id INT AUTO_INCREMENT PRIMARY KEY,
    klaster_id INT NOT NULL,
    peran ENUM('pj', 'anggota') NOT NULL,
    jabatan VARCHAR(150) NOT NULL,
    nama_petugas VARCHAR(150) NOT NULL,
    FOREIGN KEY (klaster_id) REFERENCES klaster(id) ON DELETE CASCADE
);
SELECT * FROM tim_klaster;
UPDATE users SET role = 'admin' WHERE id = 1;
-- Isi data awal sesuai SK UPTD Puskesmas Sei Taiwan
INSERT INTO tim_klaster (klaster_id, peran, jabatan, nama_petugas) VALUES
(1, 'pj', 'Penanggung Jawab Klaster', 'Yurianto, SKM'),
(1, 'anggota', 'Manajemen Arsip & Kepegawaian', 'Sri Wulan Handayani, SKM'),
(1, 'anggota', 'Manajemen Keuangan', 'Jumriani, A.Md.Keb'),
(2, 'pj', 'Penanggung Jawab Klaster', 'dr. Chandra Wulan HP'),
(2, 'anggota', 'Ibu Hamil, Bersalin & Nifas', 'Resky Amelia, S.ST'),
(2, 'anggota', 'Bayi, Balita & Pra Sekolah', 'Rini Wijaya, A.Md.Keb'),
(2, 'anggota', 'Usia Sekolah & Remaja', 'drg. Faslyna'),
(3, 'pj', 'Penanggung Jawab Klaster', 'dr. Anisa Aditya'),
(3, 'anggota', 'Pelayanan Usia Dewasa', 'Tandipau, A.Md.Kep'),
(3, 'anggota', 'Pelayanan Lansia', 'Ima Halimah, A.Md.Kep'),
(3, 'anggota', 'KB dan Calon Pengantin (Catin)', 'Evitaria, A.Md.Keb'),
(4, 'pj', 'Penanggung Jawab Klaster', 'Tandipau, AMK'),
(4, 'anggota', 'Surveilans Epidemiologi', 'Tandipau, AMK'),
(4, 'anggota', 'Kesehatan Lingkungan (Kesling)', 'Dahlia, SKM'),
(5, 'pj', 'Penanggung Jawab Klaster', 'Muhammad Syahrudin, S.Si, Apt'),
(5, 'anggota', 'Pelayanan Gawat Darurat (UGD)', 'Erwin, A.Md.Kep'),
(5, 'anggota', 'Pelayanan Laboratorium', 'Mutia Diniasti, S.Tr.Kes'),
(5, 'anggota', 'Pelayanan Kefarmasian / Apotek', 'Muhammad Syahrudin, S.Si, Apt'),
(5, 'anggota', 'Pelayanan Kesehatan Gigi & Mulut', 'drg. Faslyna
DELETE FROM users WHERE username = 'admin';

INSERT INTO users (nama, username, email, password, role) 
VALUES ('Administrator', 'admin', 'admin@sipantas.local', '123456', 'admin');

-- 1. Masukkan data klaster dasar
INSERT INTO klaster (id, nama_klaster, deskripsi) VALUES 
(1, 'Klaster 1: Manajemen Puskesmas', 'Pelayanan manajemen Puskesmas.'),
(2, 'Klaster 2: Kesehatan Ibu dan Anak', 'Pelayanan kesehatan ibu dan anak.'),
(3, 'Klaster 3: Kesehatan Dewasa dan Lansia', 'Pelayanan kesehatan dewasa dan lansia.'),
(4, 'Klaster 4: Penanggulangan Penyakit', 'Pelayanan penanggulangan penyakit.');

INSERT INTO layanan (id, klaster_id, nama_layanan, deskripsi, status) VALUES 
(1, 1, 'Pendaftaran / Admisi', 'Loket pendaftaran umum pasien', 'aktif'),
(2, 2, 'Pemeriksaan KIA & Imunisasi', 'Layanan ibu hamil dan anak', 'aktif'),
(3, 3, 'Poli Umum & Lansia', 'Pemeriksaan kesehatan dewasa dan lanjut usia', 'aktif'),
(4, 4, 'Poli P2P (Penyakit Menular)', 'Layanan penanggulangan penyakit', 'aktif');

ALTER TABLE users ADD COLUMN reset_token VARCHAR(255) NULL, ADD COLUMN reset_expiry DATETIME NULL;

ALTER TABLE users 
ADD COLUMN reset_token VARCHAR(255) NULL AFTER password,
ADD COLUMN reset_expiry DATETIME NULL AFTER reset_token;