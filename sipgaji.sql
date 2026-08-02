-- MySQL dump 10.13  Distrib 9.7.0, for Win64 (x86_64)
--
-- Host: localhost    Database: sipgaji
-- ------------------------------------------------------
-- Server version	9.7.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '9e044c4b-4507-11f1-a5ce-d493904c1439:1-8891';

--
-- Table structure for table `jabatan`
--

DROP TABLE IF EXISTS `jabatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jabatan` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama_jabatan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `gaji_pokok` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tunj_jabatan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tunj_makan_per_hari` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tunj_transport_per_hari` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jabatan`
--

LOCK TABLES `jabatan` WRITE;
/*!40000 ALTER TABLE `jabatan` DISABLE KEYS */;
INSERT INTO `jabatan` VALUES (1,'Manager IT',8500000.00,2500000.00,40000.00,30000.00,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(2,'Senior Software Engineer',7000000.00,1800000.00,35000.00,25000.00,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(3,'HRD & Legal Staff',5500000.00,1200000.00,30000.00,20000.00,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(4,'Financial Analyst',6000000.00,1500000.00,30000.00,20000.00,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(5,'Marketing & Sales Executive',4800000.00,1000000.00,25000.00,20000.00,'2026-08-02 12:09:23','2026-08-02 12:09:23');
/*!40000 ALTER TABLE `jabatan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `karyawan`
--

DROP TABLE IF EXISTS `karyawan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `karyawan` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `nip` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'L',
  `tempat_lahir` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_general_ci,
  `no_telp` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'default.png',
  `jabatan_id` int unsigned NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `status_nikah` enum('Belum Menikah','Menikah') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Belum Menikah',
  `jumlah_anak` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nip` (`nip`),
  KEY `karyawan_user_id_foreign` (`user_id`),
  KEY `karyawan_jabatan_id_foreign` (`jabatan_id`),
  CONSTRAINT `karyawan_jabatan_id_foreign` FOREIGN KEY (`jabatan_id`) REFERENCES `jabatan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `karyawan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `karyawan`
--

LOCK TABLES `karyawan` WRITE;
/*!40000 ALTER TABLE `karyawan` DISABLE KEYS */;
INSERT INTO `karyawan` VALUES (1,2,'NIP2026001','Ahmad Rizki','L','Lhokseumawe','1992-05-14','Jl. Merdeka No. 12, Lhokseumawe','081269001111','default.png',1,'2022-01-10','Menikah',2,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(2,3,'NIP2026002','Budi Santoso','L','Banda Aceh','1994-08-20','Jl. T. Umar No. 45, Banda Aceh','081269002222','default.png',2,'2022-01-10','Menikah',1,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(3,4,'NIP2026003','Citra Dewi','P','Medan','1995-11-03','Jl. Gatot Subroto No. 88, Medan','081269003333','default.png',3,'2022-01-10','Belum Menikah',0,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(4,5,'NIP2026004','Dedi Kurniawan','L','Lhokseumawe','1993-01-15','Jl. Samudra No. 05, Lhokseumawe','081269004444','default.png',4,'2022-01-10','Menikah',2,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(5,6,'NIP2026005','Eka Putri','P','Langsa','1996-04-25','Jl. Ahmad Yani No. 10, Langsa','081269005555','default.png',5,'2022-01-10','Belum Menikah',0,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(6,7,'NIP2026006','Fajar Pratama','L','Bireuen','1991-09-12','Jl. Medan-Banda Aceh Km 2, Bireuen','081269006666','default.png',1,'2022-01-10','Menikah',3,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(7,8,'NIP2026007','Gita Gutawa','P','Takengon','1997-02-18','Jl. Yos Sudarso No. 3, Takengon','081269007777','default.png',2,'2022-01-10','Belum Menikah',0,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(8,9,'NIP2026008','Hendra Wijaya','L','Lhokseumawe','1990-12-05','Jl. Pase No. 19, Lhokseumawe','081269008888','default.png',3,'2022-01-10','Menikah',1,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(9,10,'NIP2026009','Indah Permata','P','Meulaboh','1995-07-30','Jl. Gajah Mada No. 7, Meulaboh','081269009999','default.png',4,'2022-01-10','Menikah',0,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(10,11,'NIP2026010','Joko Susilo','L','Sigli','1993-03-22','Jl. Iskandar Muda No. 14, Sigli','081269010000','default.png',5,'2022-01-10','Belum Menikah',0,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(11,12,'NIP2026011','Kiki Amalia','P','Sabang','1998-06-10','Jl. Perdagangan No. 2, Sabang','081269011111','default.png',2,'2022-01-10','Belum Menikah',0,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(12,13,'NIP2026012','Lukman Hakim','L','Lhokseumawe','1992-10-08','Jl. Darussalam No. 21, Lhokseumawe','081269012222','default.png',3,'2022-01-10','Menikah',2,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(13,14,'NIP2026013','Maya Sari','P','Banda Aceh','1996-01-29','Jl. Diponegoro No. 9, Banda Aceh','081269013333','default.png',4,'2022-01-10','Belum Menikah',0,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(14,15,'NIP2026014','Naufal Alamsyah','L','Medan','1994-05-17','Jl. Sizingamangaraja No. 30, Medan','081269014444','default.png',5,'2022-01-10','Menikah',1,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(15,16,'NIP2026015','Oki Setiana','P','Lhokseumawe','1997-09-04','Jl. Cipto Mangunkusumo No. 6, Lhokseumawe','081269015555','default.png',2,'2022-01-10','Belum Menikah',0,'2026-08-02 12:09:23','2026-08-02 12:09:23');
/*!40000 ALTER TABLE `karyawan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026-08-02-000001','App\\Database\\Migrations\\CreatePayrollTables','default','App',1785672563,1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penggajian`
--

DROP TABLE IF EXISTS `penggajian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `penggajian` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `kode_transaksi` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `karyawan_id` int unsigned NOT NULL,
  `bulan` int NOT NULL,
  `tahun` int NOT NULL,
  `gaji_pokok` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tunj_jabatan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tunj_kehadiran` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tunj_keluarga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `bonus_lembur` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_pendapatan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `pot_bpjs_ks` decimal(15,2) NOT NULL DEFAULT '0.00',
  `pot_bpjs_tk` decimal(15,2) NOT NULL DEFAULT '0.00',
  `pot_pph21` decimal(15,2) NOT NULL DEFAULT '0.00',
  `pot_absensi` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_potongan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `gaji_bersih` decimal(15,2) NOT NULL DEFAULT '0.00',
  `foto_bukti_transfer` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_dibayar` datetime DEFAULT NULL,
  `status_bayar` enum('Pending','Lunas') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_transaksi` (`kode_transaksi`),
  KEY `penggajian_karyawan_id_foreign` (`karyawan_id`),
  CONSTRAINT `penggajian_karyawan_id_foreign` FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penggajian`
--

LOCK TABLES `penggajian` WRITE;
/*!40000 ALTER TABLE `penggajian` DISABLE KEYS */;
INSERT INTO `penggajian` VALUES (1,'TRX-PAY-202607-001',1,7,2026,8500000.00,2500000.00,1540000.00,1700000.00,736994.22,14976994.22,85000.00,170000.00,425000.00,0.00,680000.00,14296994.22,NULL,'2026-08-02 12:09:23','Lunas','2026-08-02 12:09:23','2026-08-02 12:09:23'),(2,'TRX-PAY-202607-002',2,7,2026,7000000.00,1800000.00,1260000.00,1050000.00,485549.13,11595549.13,70000.00,140000.00,350000.00,0.00,560000.00,11035549.13,NULL,'2026-08-02 12:09:23','Lunas','2026-08-02 12:09:23','2026-08-02 12:09:23'),(3,'TRX-PAY-202607-003',3,7,2026,5500000.00,1200000.00,1000000.00,0.00,238439.31,7938439.31,55000.00,110000.00,275000.00,0.00,440000.00,7498439.31,NULL,'2026-08-02 12:09:23','Lunas','2026-08-02 12:09:23','2026-08-02 12:09:23'),(4,'TRX-PAY-202607-004',4,7,2026,6000000.00,1500000.00,1100000.00,1200000.00,624277.46,10424277.46,60000.00,120000.00,300000.00,0.00,480000.00,9944277.46,NULL,'2026-08-02 12:09:23','Lunas','2026-08-02 12:09:23','2026-08-02 12:09:23'),(5,'TRX-PAY-202607-005',5,7,2026,4800000.00,1000000.00,855000.00,0.00,0.00,6655000.00,48000.00,96000.00,240000.00,218181.82,602181.82,6052818.18,NULL,'2026-08-02 12:09:23','Lunas','2026-08-02 12:09:23','2026-08-02 12:09:23'),(6,'TRX-PAY-202607-006',6,7,2026,8500000.00,2500000.00,1540000.00,1700000.00,1105491.33,15345491.33,85000.00,170000.00,425000.00,0.00,680000.00,14665491.33,NULL,'2026-08-02 12:09:23','Lunas','2026-08-02 12:09:23','2026-08-02 12:09:23'),(7,'TRX-PAY-202607-007',7,7,2026,7000000.00,1800000.00,1260000.00,0.00,242774.57,10302774.57,70000.00,140000.00,350000.00,0.00,560000.00,9742774.57,NULL,'2026-08-02 12:09:23','Lunas','2026-08-02 12:09:23','2026-08-02 12:09:23'),(8,'TRX-PAY-202607-008',8,7,2026,5500000.00,1200000.00,1000000.00,825000.00,286127.17,8811127.17,55000.00,110000.00,275000.00,0.00,440000.00,8371127.17,NULL,'2026-08-02 12:09:23','Lunas','2026-08-02 12:09:23','2026-08-02 12:09:23'),(9,'TRX-PAY-202607-009',9,7,2026,6000000.00,1500000.00,1100000.00,600000.00,520231.21,9720231.21,60000.00,120000.00,300000.00,0.00,480000.00,9240231.21,NULL,'2026-08-02 12:09:23','Lunas','2026-08-02 12:09:23','2026-08-02 12:09:23'),(10,'TRX-PAY-202607-010',10,7,2026,4800000.00,1000000.00,810000.00,0.00,0.00,6610000.00,48000.00,96000.00,240000.00,436363.64,820363.64,5789636.36,NULL,'2026-08-02 12:09:23','Lunas','2026-08-02 12:09:23','2026-08-02 12:09:23'),(11,'TRX-PAY-202607-011',11,7,2026,7000000.00,1800000.00,1320000.00,0.00,485549.13,10605549.13,70000.00,140000.00,350000.00,0.00,560000.00,10045549.13,NULL,'2026-08-02 12:09:23','Lunas','2026-08-02 12:09:23','2026-08-02 12:09:23'),(12,'TRX-PAY-202607-012',12,7,2026,5500000.00,1200000.00,1050000.00,1100000.00,286127.17,9136127.17,55000.00,110000.00,275000.00,0.00,440000.00,8696127.17,NULL,'2026-08-02 12:09:23','Lunas','2026-08-02 12:09:23','2026-08-02 12:09:23'),(13,'TRX-PAY-202607-013',13,7,2026,6000000.00,1500000.00,1000000.00,0.00,104046.24,8604046.24,60000.00,120000.00,300000.00,272727.27,752727.27,7851318.97,NULL,'2026-08-02 12:09:23','Lunas','2026-08-02 12:09:23','2026-08-02 12:09:23'),(14,'TRX-PAY-202607-014',14,7,2026,4800000.00,1000000.00,990000.00,720000.00,416184.97,7926184.97,48000.00,96000.00,240000.00,0.00,384000.00,7542184.97,NULL,'2026-08-02 12:09:23','Lunas','2026-08-02 12:09:23','2026-08-02 12:09:23'),(15,'TRX-PAY-202607-015',15,7,2026,7000000.00,1800000.00,1260000.00,0.00,242774.57,10302774.57,70000.00,140000.00,350000.00,0.00,560000.00,9742774.57,NULL,'2026-08-02 12:09:23','Lunas','2026-08-02 12:09:23','2026-08-02 12:09:23');
/*!40000 ALTER TABLE `penggajian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presensi`
--

DROP TABLE IF EXISTS `presensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `presensi` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `karyawan_id` int unsigned NOT NULL,
  `bulan` int NOT NULL,
  `tahun` int NOT NULL,
  `jumlah_hadir` int NOT NULL DEFAULT '0',
  `jumlah_sakit` int NOT NULL DEFAULT '0',
  `jumlah_izin` int NOT NULL DEFAULT '0',
  `jumlah_alpa` int NOT NULL DEFAULT '0',
  `jumlah_lembur_jam` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `presensi_karyawan_id_foreign` (`karyawan_id`),
  CONSTRAINT `presensi_karyawan_id_foreign` FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presensi`
--

LOCK TABLES `presensi` WRITE;
/*!40000 ALTER TABLE `presensi` DISABLE KEYS */;
INSERT INTO `presensi` VALUES (1,1,7,2026,22,0,0,0,10,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(2,2,7,2026,21,1,0,0,8,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(3,3,7,2026,20,0,2,0,5,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(4,4,7,2026,22,0,0,0,12,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(5,5,7,2026,19,1,1,1,0,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(6,6,7,2026,22,0,0,0,15,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(7,7,7,2026,21,0,1,0,4,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(8,8,7,2026,20,1,1,0,6,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(9,9,7,2026,22,0,0,0,10,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(10,10,7,2026,18,2,0,2,0,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(11,11,7,2026,22,0,0,0,8,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(12,12,7,2026,21,0,1,0,6,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(13,13,7,2026,20,1,0,1,2,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(14,14,7,2026,22,0,0,0,10,'2026-08-02 12:09:23','2026-08-02 12:09:23'),(15,15,7,2026,21,1,0,0,4,'2026-08-02 12:09:23','2026-08-02 12:09:23');
/*!40000 ALTER TABLE `presensi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','karyawan') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'karyawan',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin@sipgaji.com','$2y$12$0FIwYIrQlGXDD/bAQS83Ou6gARaqLPXCl4BoZ5dOt8T2Q8AaKk4Tm','admin','2026-08-02 12:09:23','2026-08-02 12:09:23'),(2,'karyawan1','karyawan1@sipgaji.com','$2y$12$0FIwYIrQlGXDD/bAQS83Ou6gARaqLPXCl4BoZ5dOt8T2Q8AaKk4Tm','karyawan','2026-08-02 12:09:23','2026-08-02 12:09:23'),(3,'karyawan2','karyawan2@sipgaji.com','$2y$12$0FIwYIrQlGXDD/bAQS83Ou6gARaqLPXCl4BoZ5dOt8T2Q8AaKk4Tm','karyawan','2026-08-02 12:09:23','2026-08-02 12:09:23'),(4,'karyawan3','karyawan3@sipgaji.com','$2y$12$0FIwYIrQlGXDD/bAQS83Ou6gARaqLPXCl4BoZ5dOt8T2Q8AaKk4Tm','karyawan','2026-08-02 12:09:23','2026-08-02 12:09:23'),(5,'karyawan4','karyawan4@sipgaji.com','$2y$12$0FIwYIrQlGXDD/bAQS83Ou6gARaqLPXCl4BoZ5dOt8T2Q8AaKk4Tm','karyawan','2026-08-02 12:09:23','2026-08-02 12:09:23'),(6,'karyawan5','karyawan5@sipgaji.com','$2y$12$0FIwYIrQlGXDD/bAQS83Ou6gARaqLPXCl4BoZ5dOt8T2Q8AaKk4Tm','karyawan','2026-08-02 12:09:23','2026-08-02 12:09:23'),(7,'karyawan6','karyawan6@sipgaji.com','$2y$12$0FIwYIrQlGXDD/bAQS83Ou6gARaqLPXCl4BoZ5dOt8T2Q8AaKk4Tm','karyawan','2026-08-02 12:09:23','2026-08-02 12:09:23'),(8,'karyawan7','karyawan7@sipgaji.com','$2y$12$0FIwYIrQlGXDD/bAQS83Ou6gARaqLPXCl4BoZ5dOt8T2Q8AaKk4Tm','karyawan','2026-08-02 12:09:23','2026-08-02 12:09:23'),(9,'karyawan8','karyawan8@sipgaji.com','$2y$12$0FIwYIrQlGXDD/bAQS83Ou6gARaqLPXCl4BoZ5dOt8T2Q8AaKk4Tm','karyawan','2026-08-02 12:09:23','2026-08-02 12:09:23'),(10,'karyawan9','karyawan9@sipgaji.com','$2y$12$0FIwYIrQlGXDD/bAQS83Ou6gARaqLPXCl4BoZ5dOt8T2Q8AaKk4Tm','karyawan','2026-08-02 12:09:23','2026-08-02 12:09:23'),(11,'karyawan10','karyawan10@sipgaji.com','$2y$12$0FIwYIrQlGXDD/bAQS83Ou6gARaqLPXCl4BoZ5dOt8T2Q8AaKk4Tm','karyawan','2026-08-02 12:09:23','2026-08-02 12:09:23'),(12,'karyawan11','karyawan11@sipgaji.com','$2y$12$0FIwYIrQlGXDD/bAQS83Ou6gARaqLPXCl4BoZ5dOt8T2Q8AaKk4Tm','karyawan','2026-08-02 12:09:23','2026-08-02 12:09:23'),(13,'karyawan12','karyawan12@sipgaji.com','$2y$12$0FIwYIrQlGXDD/bAQS83Ou6gARaqLPXCl4BoZ5dOt8T2Q8AaKk4Tm','karyawan','2026-08-02 12:09:23','2026-08-02 12:09:23'),(14,'karyawan13','karyawan13@sipgaji.com','$2y$12$0FIwYIrQlGXDD/bAQS83Ou6gARaqLPXCl4BoZ5dOt8T2Q8AaKk4Tm','karyawan','2026-08-02 12:09:23','2026-08-02 12:09:23'),(15,'karyawan14','karyawan14@sipgaji.com','$2y$12$0FIwYIrQlGXDD/bAQS83Ou6gARaqLPXCl4BoZ5dOt8T2Q8AaKk4Tm','karyawan','2026-08-02 12:09:23','2026-08-02 12:09:23'),(16,'karyawan15','karyawan15@sipgaji.com','$2y$12$0FIwYIrQlGXDD/bAQS83Ou6gARaqLPXCl4BoZ5dOt8T2Q8AaKk4Tm','karyawan','2026-08-02 12:09:23','2026-08-02 12:09:23');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-02 19:09:30
