-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: sipantas
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `antrean`
--

DROP TABLE IF EXISTS `antrean`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `antrean` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `layanan_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `nomor_antrean` int(11) NOT NULL,
  `status` enum('menunggu','dipanggil','dilayani','selesai','dibatalkan') DEFAULT 'menunggu',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `layanan_id` (`layanan_id`,`tanggal`,`nomor_antrean`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `antrean_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `antrean_ibfk_2` FOREIGN KEY (`layanan_id`) REFERENCES `layanan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `antrean`
--

LOCK TABLES `antrean` WRITE;
/*!40000 ALTER TABLE `antrean` DISABLE KEYS */;
INSERT INTO `antrean` VALUES (4,1,5,'2026-11-17',1,'menunggu','2026-11-17 13:20:25'),(5,1,1,'2026-11-17',1,'menunggu','2026-11-17 13:23:53');
/*!40000 ALTER TABLE `antrean` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `klaster`
--

DROP TABLE IF EXISTS `klaster`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `klaster` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_klaster` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `klaster`
--

LOCK TABLES `klaster` WRITE;
/*!40000 ALTER TABLE `klaster` DISABLE KEYS */;
INSERT INTO `klaster` VALUES (1,'Klaster 1: Manajemen Puskesmas','Pelayanan manajemen Puskesmas.'),(2,'Klaster 2: Kesehatan Ibu dan Anak','Pelayanan kesehatan ibu dan anak.'),(3,'Klaster 3: Kesehatan Dewasa dan Lansia','Pelayanan kesehatan dewasa dan lansia.'),(4,'Klaster 4: Penanggulangan Penyakit','Pelayanan penanggulangan penyakit.'),(5,'Lintas Klaster','Pelayanan lintas kelompok klaster.');
/*!40000 ALTER TABLE `klaster` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `layanan`
--

DROP TABLE IF EXISTS `layanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `layanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `klaster_id` int(11) NOT NULL,
  `nama_layanan` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  PRIMARY KEY (`id`),
  KEY `klaster_id` (`klaster_id`),
  CONSTRAINT `layanan_ibfk_1` FOREIGN KEY (`klaster_id`) REFERENCES `klaster` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `layanan`
--

LOCK TABLES `layanan` WRITE;
/*!40000 ALTER TABLE `layanan` DISABLE KEYS */;
INSERT INTO `layanan` VALUES (1,1,'Pelayanan Manajemen & Administrasi','Layanan administrasi umum, surat menyurat, dan pengaduan','aktif'),(2,2,'Pelayanan Kesehatan Ibu dan Anak','Pemeriksaan ibu hamil, nifas, imunisasi bayi, balita, dan remaja','aktif'),(3,3,'Pelayanan Kesehatan Dewasa dan Lansia','Pemeriksaan umum dewasa, skrining PTM, posyandu lansia, dan KB','aktif'),(4,4,'Pelayanan Penanggulangan Penyakit','Surveilans epidemiologi, pelacakan penyakit menular, dan kesling','aktif'),(5,5,'Pelayanan Lintas Klaster','Pelayanan UGD, laboratorium, kefarmasian/apotek, dan poli gigi','aktif');
/*!40000 ALTER TABLE `layanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `petugas`
--

DROP TABLE IF EXISTS `petugas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `petugas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nip` varchar(30) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `nip` (`nip`),
  CONSTRAINT `petugas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `petugas`
--

LOCK TABLES `petugas` WRITE;
/*!40000 ALTER TABLE `petugas` DISABLE KEYS */;
/*!40000 ALTER TABLE `petugas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `riwayat_antrean`
--

DROP TABLE IF EXISTS `riwayat_antrean`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `riwayat_antrean` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `antrean_id` int(11) NOT NULL,
  `petugas_id` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `waktu_pelayanan` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `antrean_id` (`antrean_id`),
  KEY `petugas_id` (`petugas_id`),
  CONSTRAINT `riwayat_antrean_ibfk_1` FOREIGN KEY (`antrean_id`) REFERENCES `antrean` (`id`),
  CONSTRAINT `riwayat_antrean_ibfk_2` FOREIGN KEY (`petugas_id`) REFERENCES `petugas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `riwayat_antrean`
--

LOCK TABLES `riwayat_antrean` WRITE;
/*!40000 ALTER TABLE `riwayat_antrean` DISABLE KEYS */;
/*!40000 ALTER TABLE `riwayat_antrean` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tim_klaster`
--

DROP TABLE IF EXISTS `tim_klaster`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tim_klaster` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `klaster_id` int(11) NOT NULL,
  `peran` enum('pj','anggota') NOT NULL,
  `jabatan` varchar(150) NOT NULL,
  `nama_petugas` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `klaster_id` (`klaster_id`),
  CONSTRAINT `tim_klaster_ibfk_1` FOREIGN KEY (`klaster_id`) REFERENCES `klaster` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tim_klaster`
--

LOCK TABLES `tim_klaster` WRITE;
/*!40000 ALTER TABLE `tim_klaster` DISABLE KEYS */;
INSERT INTO `tim_klaster` VALUES (1,1,'pj','Penanggung Jawab Klaster','Yurianto, SKM'),(2,1,'anggota','Manajemen Arsip & Kepegawaian','Sri Wulan Handayani, SKM'),(3,1,'anggota','Manajemen Keuangan','Jumriani, A.Md.Keb'),(4,2,'pj','Penanggung Jawab Klaster','dr. Chandra Wulan HP'),(5,2,'anggota','Ibu Hamil, Bersalin & Nifas','Resky Amelia, S.ST'),(6,2,'anggota','Bayi, Balita & Pra Sekolah','Rini Wijaya, A.Md.Keb'),(7,2,'anggota','Usia Sekolah & Remaja','drg. Faslyna'),(8,3,'pj','Penanggung Jawab Klaster','dr. Anisa Aditya'),(9,3,'anggota','Pelayanan Usia Dewasa','Tandipau, A.Md.Kep'),(10,3,'anggota','Pelayanan Lansia','Ima Halimah, A.Md.Kep'),(11,3,'anggota','KB dan Calon Pengantin (Catin)','Evitaria, A.Md.Keb'),(12,4,'pj','Penanggung Jawab Klaster','Tandipau, AMK'),(13,4,'anggota','Surveilans Epidemiologi','Tandipau, AMK'),(14,4,'anggota','Kesehatan Lingkungan (Kesling)','Dahlia, SKM'),(15,5,'pj','Penanggung Jawab Klaster','Muhammad Syahrudin, S.Si, Apt'),(16,5,'anggota','Pelayanan Gawat Darurat (UGD)','Erwin, A.Md.Kep'),(17,5,'anggota','Pelayanan Laboratorium','Mutia Diniasti, S.Tr.Kes'),(18,5,'anggota','Pelayanan Kefarmasian / Apotek','Muhammad Syahrudin, S.Si, Apt'),(19,5,'anggota','Pelayanan Kesehatan Gigi & Mulut','drg. Faslyna');
/*!40000 ALTER TABLE `tim_klaster` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','petugas','warga') DEFAULT 'warga',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Moh Ilham','Mohilham234#','mohilham43836@gmail.com','$2y$12$v3CDUtE7nAbO9eVuQo0NP.rVFQXxtV4SCLjured3TSklKtWPsraCW','warga','2026-11-10 14:11:14'),(2,'Administrator','admin','admin@sipantas.local','123456','admin','2026-11-17 15:35:11');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warga`
--

DROP TABLE IF EXISTS `warga`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `warga` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nik` char(16) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `alamat` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `nik` (`nik`),
  CONSTRAINT `warga_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warga`
--

LOCK TABLES `warga` WRITE;
/*!40000 ALTER TABLE `warga` DISABLE KEYS */;
INSERT INTO `warga` VALUES (1,1,'-','-','-');
/*!40000 ALTER TABLE `warga` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-18  2:22:27
