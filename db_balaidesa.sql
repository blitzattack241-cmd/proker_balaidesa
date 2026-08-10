-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2026 at 03:24 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_balaidesa`
--

-- --------------------------------------------------------

--
-- Table structure for table `nomor_surat_global`
--

CREATE TABLE `nomor_surat_global` (
  `id` int(11) NOT NULL,
  `nomor` int(11) NOT NULL,
  `tahun` year(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_device_terpercaya`
--

CREATE TABLE `tb_device_terpercaya` (
  `id` int(11) NOT NULL,
  `device_token` varchar(64) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `last_used` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_nomor_surat_global`
--

CREATE TABLE `tb_nomor_surat_global` (
  `id` int(11) NOT NULL,
  `tahun` varchar(4) NOT NULL,
  `nomor` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_nomor_surat_global`
--

INSERT INTO `tb_nomor_surat_global` (`id`, `tahun`, `nomor`) VALUES
(1, '2026', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tb_pejabat`
--

CREATE TABLE `tb_pejabat` (
  `id_pejabat` int(11) NOT NULL,
  `nama_pejabat` varchar(150) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `alamat_pejabat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_pejabat`
--

INSERT INTO `tb_pejabat` (`id_pejabat`, `nama_pejabat`, `jabatan`, `alamat_pejabat`) VALUES
(1, 'Pujiono, S.Pd', 'Sekretaris Desa', 'Desa Berugenjang'),
(2, 'KISWO, S.E', 'Kepala Desa', 'Desa Berugenjang');

-- --------------------------------------------------------

--
-- Table structure for table `tb_penduduk`
--

CREATE TABLE `tb_penduduk` (
  `id` int(11) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `no_kk` varchar(20) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` varchar(20) DEFAULT NULL,
  `tempat_tgl_lahir` varchar(100) DEFAULT NULL,
  `umur` int(11) DEFAULT NULL,
  `agama` varchar(30) DEFAULT NULL,
  `pekerjaan` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `rt` varchar(5) DEFAULT NULL,
  `rw` varchar(5) DEFAULT NULL,
  `kepala_kk` varchar(100) DEFAULT NULL,
  `status_keluarga` varchar(50) DEFAULT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `status_pernikahan` varchar(50) DEFAULT NULL,
  `kewarganegaraan` varchar(50) DEFAULT NULL,
  `suku` varchar(50) DEFAULT NULL,
  `pendidikan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_penduduk`
--

INSERT INTO `tb_penduduk` (`id`, `nik`, `no_kk`, `nama`, `jenis_kelamin`, `tempat_tgl_lahir`, `umur`, `agama`, `pekerjaan`, `alamat`, `rt`, `rw`, `kepala_kk`, `status_keluarga`, `tempat_lahir`, `tgl_lahir`, `status_pernikahan`, `kewarganegaraan`, `suku`, `pendidikan`) VALUES
(1, '3319041001670002', '3319041012090032', 'ACHWAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'ACHWAN', 'Kepala Keluarga', 'KUDUS', '1967-10-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(2, '3319045608690004', '3319041012090032', 'SRI\'AH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'ACHWAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(3, '3319042601850002', '3319041007190004', 'AHMAD SODIQ', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '1', 'AHMAD SHODIQ', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(4, '3319044101920005', '3319041007190004', 'NGATENI SARAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '1', '1', 'AHMAD SHODIQ', 'Istri', 'KUDUS', '1992-01-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(5, '3319041604100001', '3319041007190004', 'MUHAMMAD SYAHRUDIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'AHMAD SHODIQ', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(6, '3319042706770002', '3319042604070009', 'AHMADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '1', '1', 'AHMADI', 'Kepala Keluarga', 'DEMAK', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(7, '3319044605830002', '3319042604070009', 'ISMIYATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '1', '1', 'AHMADI', 'Istri', 'KUDUS', '1983-06-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(8, '33190422111040001', '3319042604070009', 'AHMAD MAGFURIR ROHMAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'AHMADI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(9, '3319042304180001', '3319042604070009', 'RAFIF NAUFAL HAFIZH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'AHMADI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(10, '3319040905800001', '3319042002190003', 'ALI FAKIH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'ALI FAKIH', 'Kepala Keluarga', 'KUDUS', '1980-09-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(11, '3318016008960001', '3319042002190003', 'ENDANG LESTARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '1', '1', 'ALI FAKIH', 'Istri', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(12, '3319042410190001', '3319042002190003', 'MUHAMMAD SATYA DIRGANTARA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'ALI FAKIH', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(13, '3319040309250001', '3319042002190003', 'MUHAMMAD ARSYA KAUTSARRAZKY', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '1', '1', 'ALI FAKIH', 'Anak Kandung', 'KUDUS', '2025-03-09', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(14, '3319041210900002', '3319040605140003', 'ARIFIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '1', '1', 'ARIFIN', 'Kepala Keluarga', 'KUDUS', '1990-12-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(15, '3319044403920001', '3319040605140003', 'DWI PURWATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '1', '1', 'ARIFIN', 'Istri', 'KUDUS', '1992-04-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(16, '3319042304140001', '3319040605140003', 'NAUFAL TRISTAN AZZAMY', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'ARIFIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(17, '3319041801850005', '3319041006090053', 'BAKOH WAHONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '1', 'BAKOH WAHONO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(18, '3319044412890002', '3319041006090053', 'ROIKATUL AZIZAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '1', '1', 'BAKOH WAHONO', 'Istri', 'KUDUS', '1989-04-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(19, '3319045705120001', '3319041006090053', 'SUSY PURBANDARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'BAKOH WAHONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(20, '3318012709820001', '3319040702130006', 'BASUKI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'BASUKI', 'Kepala Keluarga', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(21, '3319045311880001', '3319040702130006', 'NGAPSI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'BASUKI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(22, '3319045301130004', '3319040702130006', 'LAURA VALERY ANGELIA NOVA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'BASUKI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(23, '3319044407180004', '3319040702130006', 'ANA ATTOFU NISA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'BASUKI', 'Anak Kandung', 'KUDUS', '2018-04-07', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(24, '3319030202980001', '3319042610200004', 'BAYU PRASETIYO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '1', 'BAYU PRASETIYO', 'Kepala Keluarga', 'KUDUS', '1998-02-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(25, '3319044608970001', '3319042610200004', 'WIDOSARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '1', 'BAYU PRASETIYO', 'Istri', 'KUDUS', '1997-06-08', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(26, '3319041005200003', '3319042610200004', 'ALGHAISAN RAMADHAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'BAYU PRASETIYO', 'Anak Kandung', 'KUDUS', '2020-10-05', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(27, '3319041308860006', '3319040812110008', 'BUSYIRI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'BUSYIRI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(28, '3319046101930001', '3319040812110008', 'KAMIATUN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '1', '1', 'BUSYIRI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(29, '3319046010140001', '3319040812110008', 'IRIANA RODHOTUL FITIAN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'BUSYIRI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(30, '3319046305880003', '3319041101100007', 'DEWI WULANSARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '1', 'DEWI WULANSARI', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(31, '3319044305090004', '3319041101100007', 'LILA ARISKA PUTRI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '1', '1', 'DEWI WULANSARI', 'Anak Kandung', 'KUDUS', '2009-03-05', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum tamat sd/sederajat'),
(32, '3319040106160001', '3319041101100007', 'ARVINO SYAFIR FAEZA ANDRES', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'DEWI WULANSARI', 'Anak Kandung', 'KUDUS', '2016-01-06', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(33, '3319040405790002', '3319042809070012', 'GUFRON', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'GUFRON', 'Kepala Keluarga', 'GROBOGAN', '1979-04-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(34, '3319046612660001', '3319042809070012', 'SABIR', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '1', 'GUFRON', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(35, '3319041602060002', '3319042809070012', 'FAIZAL HARIST', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'GUFRON', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(36, '3319047003160003', '3319042809070012', 'NAILA FIRDATUZ ZAHWA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'GUFRON', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum masuk TK/Kelompok Bermain'),
(37, '3319041505590002', '3319042707053511', 'JAMIRAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'JAMIRAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(38, '3319045004660001', '3319042707053511', 'WASIR', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'JAMIRAN', 'Istri', 'KUDUS', '1966-10-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(39, '3319042402830006', '3319041805090012', 'KAMTO BAJANG', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'KAMTO BAJANG', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum masuk TK/Kelompok Bermain'),
(40, '3319045711880001', '3319041805090012', 'RINA NOVITA SARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'KAMTO BAJANG', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(41, '3319046802100002', '3319041805090012', 'ANDIN ZAHROTUS SHITA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'KAMTO BAJANG', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(42, '3319044606180003', '3319041805090012', 'AYRA SHIRLY ALNAIRA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '1', 'KAMTO BAJANG', 'Anak Kandung', 'KUDUS', '2018-08-08', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(43, '3319040105860001', '3319042906090006', 'KARJONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '1', 'KARJONO', 'Kepala Keluarga', 'KUDUS', '1986-01-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(44, '3319044602790003', '3319042906090006', 'KUSTINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '1', 'KARJONO', 'Istri', 'PURBALINGGA', '1979-06-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(45, '3319042609090001', '3319042906090006', 'MUHAMMAD FURQAAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'KARJONO', 'Anak Kandung', 'KDUUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(46, '3319041012750005', '3319040912090034', 'KARNO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'KARNO', 'Kepala Keluarga', 'KUDUS', '1975-10-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(47, '3319045104830002', '3319040912090034', 'KURIAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'KARNO', 'Istri', 'KUDUS', '1983-11-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(48, '3319040406000001', '3319040912090034', 'MUKHAMAD KHALIMI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '1', 'KARNO', 'Anak Kandung', 'KUDUS', '2000-04-06', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(49, '3319041202130001', '3319040912090034', 'MUQSITH ASHIMUL KHULUQ', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'KARNO', 'Anak Kandung', 'JAWA', '2013-12-02', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(50, '3319044711600001', '3319040912090034', 'SUKESI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'KARNO', 'Kakak', 'KUDUS', '1960-07-11', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(51, '3319045112630001', '3319040610110003', 'KARSITI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'KARSITI', 'Kepala Keluarga', 'KUDUS', '1963-11-12', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tiadak/'),
(52, '3319040210400001', '3319041712090086', 'KARSO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '1', '1', 'KARSO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Strata III'),
(53, '3319045205490001', '3319041712090086', 'MAREMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '', '', 'KARSO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(54, '3319041011400001', '3319042707054613', 'KARSONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar', NULL, '1', '1', 'KARSONO', 'Kepala Keluarga', 'KUDUS', '1940-10-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SD/sederajat'),
(55, '3319045112430002', '3319042707054613', 'SRIYATUN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'KARSONO', 'Istri', 'KUDUS', '1943-11-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(56, '3319043112610087', '3319042604070007', 'KASMIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '1', 'KASMIN', 'Kepala Keluarga', 'GROBOGAN', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(57, '3319048901780001', '3319042604070007', 'NGATUNAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '1', 'KASMIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(58, '3319040304040001', '3319042604070007', 'ACHMAD AGUS SULAEMAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'KASMIN', 'Anak Kandung', 'KUDUS', '2004-03-04', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(59, '3319046012500001', '3319042001170004', 'KASNI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'KASNI', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(60, '3319043001990001', '3319042505230004', 'KHAMIL MUHAMAD UWAIDAH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '1', 'KHAMIL MUHAMAD UWAIDAH', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(61, '3319025112010002', '3319042505230004', 'ANISA RONDHIYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '1', 'ANISA RONDHIYAH', 'Istri', 'KUDUS', '2001-11-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(62, '3319045808230001', '3319042505230004', 'KANSA QIROATUL AINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '1', '1', 'KANSA QIROATUL AINI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/Belum Sekolah'),
(63, '3319046607600001', '3319042707052522', 'MO\'AH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'MO\'AH', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum tamat sd/sederajat'),
(64, '3319040907810001', '3319042703120008', 'KUSNADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '1', 'KUSNADI', 'Kepala Keluarga', 'KUDUS', '1981-09-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(65, '3519086109920001', '3319042703120008', 'TRIANAWATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Karyawan Swasta', NULL, '1', '1', 'KUSNADI', 'Istri', 'Madiun', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(66, '3319044903120001', '3319042703120008', 'CHELVIE PERMATA ANGGRAENI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'KUSNADI', 'Anak Kandung', 'KUDUS', '2012-09-03', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(67, '3319040706720004', '3319040912090035', 'KUSNO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'KUSNO', 'Kepala Keluarga', 'KUDUS', '1972-07-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(68, '3319044812730001', '3319040912090035', 'SUKINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'KUSNO', 'Istri', 'KUDUS', '1973-06-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(69, '3319044906010001', '3319040912090035', 'SOFI WITYANINGSIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '1', 'KUSNO', 'Anak Kandung', 'KUDUS', '2001-09-06', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(70, '3319041206590001', '3319042707053507', 'LEGI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'LEGI', 'Kepala Keluarga', 'KUDUS', '1953-12-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(71, '3319045211590001', '3319042707053507', 'KUSNI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'LEGI', 'Istri', 'KUDUS', '1959-12-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(72, '3319042910580001', '3319042707052520', 'LEGIMAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'LEGIMAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(73, '3319044612600001', '3319042707052520', 'PAIMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'LEGIMAN', 'Istri', 'KUDUS', '1960-06-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(74, '3319040205740001', '3319042707053504', 'MAHMUDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'MAHMUDI', 'Kepala Keluarga', 'PATI', '1974-02-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(75, '3319044502810002', '3319042707053504', 'KASMINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'MAHMUDI', 'Istri', 'KUDUS', '1981-05-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(76, '3319041505120002', '3319042707053504', 'AHMAD SYAMSUL HADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'MAHMUDI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(77, '3320031704890001', '3319042606130002', 'MARJUKI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '1', 'MARJUKI', 'Kepala Keluarga', 'JEPARA', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(78, '3319045207960001', '3319042606130002', 'MAMIK MELANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '1', '1', 'MARJUKI', 'Istri', 'KUDUS', '1998-12-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(79, '3319041019130001', '3319042606130002', 'RIZKI PRATAMA PUTRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'MARJUKI', 'Anak Kandung', 'KUDUS', '2013-01-09', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(80, '3319041508880004', '3319040304130003', 'MASLAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '1', 'MASLAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(81, '3319044101940002', '3319040304130003', 'SUPARTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '1', '1', 'MASLAN', 'Istri', 'KUDUS', '1994-01-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(82, '3319045903130001', '3319040304130003', 'AURELIA INDAH ROSELANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'MASLAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(83, '3319046009210006', '3319040304130003', 'ADIVA ZIA AL MAHYRA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'MASLAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(84, '3319042208730001', '3319042707054616', 'MOH RIFAI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'MOH RIFAI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(85, '3319045310760001', '3319042707054616', 'SULASTRI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'MOH RIFAI', 'Istri', 'JEMBER', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(86, '3319041405110001', '3319042707054616', 'HALIM ALAMSYAH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'MOH RIFAI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(87, '3319041808930001', '3319040207180012', 'MOHAMAD RIF\'AN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '1', '1', 'MOHAMAD RIF\'AN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'DIPLOMA IV/STRATA I'),
(88, '3319014305900008', '3319040207180012', 'ANITA DEWI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '1', '1', 'MOHAMAD RIF\'AN', 'Istri', 'KUDUS', '1990-03-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(89, '3319044208180003', '3319040207180012', 'ADEEVA KAMILA MYESHA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'MOHAMAD RIF\'AN', 'Anak Kandung', 'KUDUS', '2018-02-08', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(90, '3319045905210002', '3319040207180012', 'SHAFA AYUNDYA NASYWA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'MOHAMAD RIF\'AN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(91, '3319043110000002', '3319043005220012', 'MUHAMMAD FAJRIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '1', 'MUHAMMAD FAJRIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(92, '3319044905010001', '3319043005220012', 'VERRA VERONICA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '1', '1', 'MUHAMMAD FAJRIN', 'Istri', 'KUDUS', '2001-09-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(93, '3319040403230001', '3319043005220012', 'MUHAMMAD QEENAN GALANG PRATAMA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '1', '1', 'MUHAMMAD FAJRIN', 'Anak Kandung', 'KUDUS', '2023-04-03', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(94, '3319041207910005', '3319042309190008', 'MUHAMMAD JALI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'MUHAMMAD JALI', 'Kepala Keluarga', 'KUDUS', '1991-12-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(95, '3319044806940001', '3319042309190008', 'KHOLIFATUL FATEHAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'MUHAMMAD JALI', 'Istri', 'KUDUS', '1994-08-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum tamat sd/sederajat'),
(96, '3319046901130004', '3319042309190008', 'NADA GHAITSA ZEAN SAFIRA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'MUHAMMAD JALI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(97, '3319044303220002', '3319042309190008', 'AISHA SHIDQIA SHALIKHATUNNISA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '1', '1', 'MUHAMMAD JALI', 'Anak Kandung', 'KUDUS', '2022-03-03', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(98, '3319041606000007', '3319041807170001', 'MUKHAMAD MISBAKULMUNIR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '2', '1', 'MUKHAMAD MISBAKULMUNIR', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(99, '3319064710000007', '3319041807170001', 'CHIKAMIA AJI PAMULIA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '2', '1', 'MUKHAMAD MISBAKULMUNIR', 'Istri', 'KUDUS', '2000-07-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(100, '3319042511170005', '3319041807170001', 'MUHAMMAD NARENDRA ROBBANI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'MUKHAMAD MISBAKULMUNIR', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'TIDAK/BLM Sekolah'),
(101, '3319041703980003', '3319040205240005', 'MUKAMAT SUBAKIR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '1', 'MUKAMAT SUBAKIR', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(102, '3318017108980001', '3319040205240005', 'SUTIWATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '1', 'MUKAMAT SUBAKIR', 'Istri', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(103, '3319044708240001', '3319040205240005', 'AMIRA NAHLA AGUSTIN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '1', '1', 'MUKAMAT SUBAKIR', 'Anak Kandung', 'PATI', '2024-07-08', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/Belum Sekolah'),
(104, '3319043005830002', '3319040407110001', 'MUSLIKHAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Tukang Batu', NULL, '1', '1', 'MUSLIKHAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(105, '3319046010890002', '3319040407110001', 'WIDYA HASTUTIK', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '1', 'MUSLIKHAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(106, '3319045105130001', '3319040407110001', 'ANISA ROHMAWATI TALITA SAKHI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '1', 'MUSLIKHAN', 'Anak Kandung', 'KUDUS', '2013-11-05', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum tamat sd/sederajat'),
(107, '3319040408160002', '3319040407110001', 'AHMAD ALAIHI SALAM AMARO AMARO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'MUSLIKHAN', 'Anak Kandung', 'KUDUS', '2016-04-08', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(108, '3319043001690001', '3319042707053519', 'MUSRIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'MUSRIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(109, '3319046008700001', '3319042707053519', 'KASIBAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'MUSRIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(110, '3319041407680006', '3319041512090014', 'MUSTAMIK', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'MUSTAMIK', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(111, '3319046104770002', '3319041512090014', 'RASMINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'MUSTAMIK', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(112, '3319044567020001', '3319041512090014', 'DWI INDAH SARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '1', 'MUSTAMIK', 'Anak Kandung', 'KUDUS', '2002-05-07', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(113, '3319045009380001', '3319041012090042', 'NASIRAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '1', '1', 'NASIRAH', 'Kepala Keluarga', 'KUDUS', '1938-10-09', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(114, '3319041205590001', '3319042707054611', 'NGADENIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'NGADENIN', 'Kepala Keluarga', 'KUDUS', '1959-12-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(115, '3319045604620001', '3319042707054611', 'SITI SULASIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'NGADENIN', 'Istri', 'DEMAK', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(116, '3319048304010001', '3319042707054611', 'WINA SULISTIANA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '1', 'NGADENIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(117, '3319043112830034', '3319042809070010', 'NGADIRIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '1', 'NGADIRIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(118, '3319045205810004', '3319042809070010', 'ROWATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '1', 'NGADIRIN', 'Istri', 'TEGAL', '1981-12-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(119, '3319042108050001', '3319042809070010', 'AGUS MAULANA AL-BIKHORI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'NGADIRIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(120, '3319040212130001', '3319042809070010', 'MUHAMMAD DWI LINGGAR JATI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'NGADIRIN', 'Anak Kandung', 'KUDUS', '2013-02-12', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(121, '3319042202810002', '3319042505090008', 'NGATEMIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Tukang Batu', NULL, '1', '1', 'NGATEMIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(122, '3319046007860002', '3319042505090008', 'NURIPAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Burh harian Lepas', NULL, '1', '1', 'NGATEMIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(123, '3319044605070002', '3319042505090008', 'VIKA VATIHATUL JANNAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'NGATEMIN', 'Anak Kandung', 'KUDUS', '2007-06-05', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(124, '3319040605130002', '3319042505090008', 'ALWI ABDAHL SUTOMO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'NGATEMIN', 'Anak Kandung', 'KUDUS', '2013-06-05', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(125, '3319045202650001', '3319042707053518', 'NGATINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '1', '1', 'NGATINI', 'Kepala Keluarga', 'KUDUS', '1965-12-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(126, '3319042406900002', '3319042811180001', 'NGATIRIN SARAH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar', NULL, '1', '1', 'NGATIRIN SARAH', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(127, '3319046908020002', '3319042811180001', 'NAURUL', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '1', '1', 'NGATIRIN SARAH', 'Istri', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(128, '3319042802190002', '3319042811180001', 'NAUFAL REYHAN RAFFASYA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'NGATIRIN SARAH', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(129, '3319046909730001', '3319042707054608', 'NGATIYEM', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '1', '1', 'NGATIYEM', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(130, '3319041305990004', '3319042707054608', 'RUHUT ROMAN TAFRUDIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '1', 'NGATIYEM', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(131, '3319042503910001', '3319042411160004', 'NOR QOSIM', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '1', 'NOR QOSIM', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(132, '3319045307940002', '3319042411160004', 'RINI WARIYATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '1', '1', 'NOR QOSIM', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(133, '3319041202170001', '3319042411160004', 'DAFFA PRADIPTA ALLBY AYYAS', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'NOR QOSIM', 'Anak Kandung', 'KUDUS', '2017-12-02', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(134, '3319041202170002', '3319042411160004', 'DAFFI SAKHI AINIL IZZI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'NOR QOSIM', 'Anak Kandung', 'KUDUS', '2017-12-02', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(135, '3319041911940001', '33190441112090059', 'NORMAN ARDIANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'KARSIMAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(136, '3319045503000001', '3319042809220004', 'WAHYU UTAMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '1', '1', 'NORMAN ARDIANTO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(137, '3319042005230001', '3319042809220004', 'FATIH GHANI ADYAKSA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '1', '1', 'NORMAN ARDIANTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(138, '3315013009940001', '3319042708190009', 'NYOHADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '1', '1', 'NYOHADI', 'Kepala Keluarga', 'GROBOGAN', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(139, '3319044305900003', '3319042708190009', 'SULAMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '1', 'NYOHADI', 'Istri', 'KUDUS', '1990-03-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(140, '3319045005140001', '3319042708190009', 'SHINTA PUTRI ANGGREINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'NYOHADI', 'Anak Kandung', 'KUDUS', '2014-10-05', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(141, '3319043112600148', '3319042607054537', 'PANDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '1', 'PANDI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(142, '3319046604770001', '3319042607054537', 'SUNTARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'PANDI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(143, '3319041012790001', '3319042707053522', 'PAING', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '1', '1', 'PAING', 'Kepala Keluarga', 'KUDUS', '1979-10-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(144, '3319045903920001', '3319042707053522', 'NGATINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '1', '1', 'PAING', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(145, '3319043110030003', '3319042707053522', 'AGENG YUDA ARIA DEA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'PAING', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(146, '3319040808150002', '3319042707053522', 'RIYAN PANGESTU', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'PAING', 'Anak Kandung', 'KUDUS', '2015-08-08', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(147, '3319043101630001', '3319040912090026', 'PARKAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '1', '1', 'PARKAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(148, '3319046804650001', '3319040912090026', 'PASINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '1', 'PARKAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(149, '3318012602820003', '3319040601220004', 'SUCIPTO ADITYA SAPUTRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '1', 'SUCIPTO ADITYA SAPUTRA', 'Kepala Keluarga', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum tamat sd/sederajat'),
(150, '3319044203860001', '3319040601220004', 'PRIHATIN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '1', '1', 'SUCIPTO ADITYA SAPUTRA', 'Istri', 'KUDUS', '1986-02-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(151, '3319042812100001', '3319040601220004', 'PRAMUDYA PRIHATINIA PUTRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'SUCIPTO ADITYA SAPUTRA', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/Belum Sekolah'),
(152, '3319040902230001', '3319040601220004', 'MUHAMMAD ZIKO RAFFA SAPUTRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '', '', 'SUCIPTO ADITYA SAPUTRA', 'Anak Kandung', 'KUDUS', '2023-09-02', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(153, '3319040812780002', '3319042809070003', 'PUJIARTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '1', 'PUJIARTO', 'Kepala Keluarga', 'SEMARANG', '1978-08-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(154, '3319044604800006', '3319042809070003', 'NGATMINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'PUJIARTO', 'Istri', 'KUDUS', '1980-06-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(155, '3319042904070001', '3319042809070003', 'MUHAMAD ABDUL NGGOFUR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'PUJIARTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(156, '3319040502850002', '3319040102100044', 'PURNOMO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '1', 'PURNOMO', 'Kepala Keluarga', 'KUDUS', '1985-05-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(157, '3319046211890001', '3319040102100044', 'KUSRIMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '1', 'PURNOMO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(158, '3319040606100001', '3319040102100044', 'RIKO AJI SAPUTRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '1', 'PURNOMO', 'Anak Kandung', 'KUDUS', '2010-06-06', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(159, '3319044502230001', '3319040102100044', 'FAZZURA IZZA ALFATHUNISA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'PURNOMO', 'Anak Kandung', 'KUDUS', '2023-05-02', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(160, '3319041003660001', '3319042707053501', 'REBIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '1', '1', 'REBIN', 'Kepala Keluarga', 'KUDUS', '1966-10-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(161, '3319044910760001', '3319042707053501', 'SUKARTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '1', '1', 'REBIN', 'Istri', 'KUDUS', '1976-09-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(162, '3319044410070001', '3319042707053501', 'SARTIKAH RAMADHANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'REBIN', 'Anak Kandung', 'KUDUS', '2007-04-10', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(163, '3319040602780001', '3319042707053506', 'RIFAI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'RIFAI', 'Kepala Keluarga', 'KUDUS', '1976-06-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(164, '3319045901840001', '3319042707053506', 'SRI RAHAYU', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '1', '1', 'RIFAI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SD/sederajat'),
(165, '3319045705000001', '3319042707053506', 'DIAH AYU SRIHAPSARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'RIFAI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(166, '33190471110100001', '3319042707053506', 'AYU RIFA ILMIYATUN NAFISA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '1', '1', 'RIFAI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(167, '3319046903700001', '3319042707053614', 'SUMARSIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'SUMARSIH', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(168, '3319045811950003', '3319042707053614', 'NUR QOMSAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '1', 'SUMARSIH', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(169, '3319042101640002', '3319042707054601', 'ROSIDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '1', 'ROSIDI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(170, '3319045011740001', '3319042707054601', 'WARTIAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'ROSIDI', 'Istri', 'PATI', '1974-10-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(171, '3319040504020004', '3319040406240005', 'ROHMATULLIYAH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '1', 'ROHMATULLIYAH', 'Kepala Keluarga', 'KUDUS', '2002-05-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum tamat sd/sederajat'),
(172, '3319044411030003', '3319040406240005', 'SHINTYA SARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '1', 'ROHMATULLIYAH', 'Istri', '', '2003-04-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(173, '3319041012710001', '3319042707053510', 'RUKIBAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar', NULL, '1', '1', 'RUKIBAN', 'Kepala Keluarga', 'KUDUS', '1971-10-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(174, '3319044311820001', '3319042707053510', 'SUMINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '1', '1', 'RUKIBAN', 'Istri', 'KUDUS', '1982-03-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(175, '3319041103980001', '3319042707053510', 'NURUL HIDAYAT', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '1', 'RUKIBAN', 'Anak Kandung', 'KUDUS', '1998-11-03', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(176, '3319042703800001', '3319042707053520', 'KARSIDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '1', '1', 'KARSIDI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'DIPLOMA IV/STRATA I'),
(177, '3319047112450137', '3319042406090023', 'RUMINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'RUMINAH', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(178, '3319040504800005', '3319041712090085', 'RUSIBAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '1', '1', 'RUSIBAN', 'Kepala Keluarga', 'KUDUS', '1980-05-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(179, '3319044307880003', '3319041712090085', 'SARINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '1', '1', 'RUSIBAN', 'Istri', 'KUDUS', '1988-03-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(180, '3319044708090003', '3319041712090085', 'SHOIMATUL FARHATAIN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'RUSIBAN', 'Anak Kandung', 'KUDUS', '2009-07-09', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(181, '3319044101210001', '3319041712090085', 'SYAFIRA LAILATUN NAJWA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'RUSIBAN', 'Anak Kandung', 'KUDUS', '2021-01-01', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(182, '3319041102400001', '3319042707053505', 'SAKIRUN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'SAKIRUN', 'Kepala Keluarga', 'KUDUS', '1940-11-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(183, '3319046008450001', '3319042707053505', 'RUMINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'SAKIRUN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(184, '3319040802660002', '3319041012090037', 'SARMIJAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '1', '1', 'SARMIJAN', 'Kepala Keluarga', 'KUDUS', '1966-08-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat S-1/sederajat'),
(185, '3319045907680002', '3319041012090037', 'JASMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'SARMIJAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(186, '3319046902600001', '3319041104220006', 'WARTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'WARTI', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(187, '3319040208950001', '3319041210170001', 'SELAMET SUPRIYANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '1', '1', 'SELAMET SUPRIYANTO', 'Kepala Keluarga', 'KUDUS', '1995-02-08', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(188, '3319045407940001', '3319041210170001', 'YULI STYOWATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '1', '1', 'SELAMET SUPRIYANTO', 'Istri', 'DEMAK', '1994-04-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(189, '3319040803180003', '3319041210170001', 'MUHAMAD ZIA AKMAL', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'SELAMET SUPRIYANTO', 'Anak Kandung', 'KUDUS', '2018-08-03', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(190, '3319044202200001', '3319041210170001', 'FATIMATUZ ZAHRA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'SELAMET SUPRIYANTO', 'Anak Kandung', 'KUDUS', '2020-02-02', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(191, '3319044201860004', '3319040208120003', 'SHOLEKAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '1', 'SHOLEKAH', 'Kepala Keluarga', 'KUDUS', '1986-02-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(192, '3319042803060001', '3319040208120003', 'DANANG WIBISONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'SHOLEKAH', 'Anak Kandung', 'PATI', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(193, '3319046807140002', '3319040208120003', 'FITRI DWI ANJANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'SHOLEKAH', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(194, '3319042605750004', '3319040912090032', 'SISWANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'SISWANTO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(195, '3319046309750001', '3319040912090032', 'KASTINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'SISWANTO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(196, '3319046512050004', '3319040912090032', 'REVALINA SISWATI TEMAT', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'SISWANTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(197, '3319042306810001', '3319042604070006', 'SODIKIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '1', 'SODIKIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(198, '3319046407880001', '3319042604070006', 'NUR FITRIANA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'SODIKIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(199, '3319042605040001', '3319042604070006', 'RIDLO WAFA ABDILLAH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '1', 'SODIKIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum tamat sd/sederajat'),
(200, '3319045005120001', '3319042604070006', 'SYAFAATUS SHODIQOH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'SODIKIN', 'Anak Kandung', 'KUDUS', '2012-10-05', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(201, '3319041707600002', '3319042707054602', 'SUGIRI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'SUGIRI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(202, '3319048408500001', '3319042707054602', 'HARTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'SUGIRI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(203, '3315152503830004', '3319042905120007', 'SUJADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar', NULL, '1', '1', 'SUJADI', 'Kepala Keluarga', 'GROBOGAN', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SLTA/sederajat'),
(204, '3319046212850001', '3319042905120007', 'NGADIMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '1', '1', 'SUJADI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SD/sederajat');
INSERT INTO `tb_penduduk` (`id`, `nik`, `no_kk`, `nama`, `jenis_kelamin`, `tempat_tgl_lahir`, `umur`, `agama`, `pekerjaan`, `alamat`, `rt`, `rw`, `kepala_kk`, `status_keluarga`, `tempat_lahir`, `tgl_lahir`, `status_pernikahan`, `kewarganegaraan`, `suku`, `pendidikan`) VALUES
(205, '3319045605120003', '3319042905120007', 'LINDA ROSDIANA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'SUJADI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(206, '3319040406180003', '3319042905120007', 'MOHAMMAD SAPUTRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'SUJADI', 'Anak Kandung', 'KUDUS', '2018-04-06', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(207, '3319042003700001', '3319042707054605', 'SUJARI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'SUJARI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(208, '3319046602720001', '3319042707054605', 'YATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'SUJARI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(209, '3319040707940002', '3319042707054605', 'MUKHAMAD RIYAN FANDHOLLI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '1', 'SUJARI', 'Anak Kandung', 'KUDUS', '1994-07-07', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(210, '3319040709010002', '3319042707054605', 'ADI ANDRIYAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '1', 'SUJARI', 'Anak Kandung', 'KUDUS', '2001-07-09', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(211, '3319044704610008', '3319042707053508', 'SUJINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'SUJINAH', 'Kepala Keluarga', 'KUDUS', '1961-07-04', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(212, '3319040912590001', '3319042707054615', 'SUKARMAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'SUKARMAN', 'Kepala Keluarga', 'KUDUS', '1959-09-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(213, '3319044606580003', '3319042707054615', 'LASMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'SUKARMAN', 'Ibu', 'KUDUS', '1958-06-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(214, '3319040910620001', '3319042707054596', 'SUKIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'SUKIN', 'Kepala Keluarga', 'KUDUS', '1962-09-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(215, '3319044203040001', '3319042707054596', 'WAGIRAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'SUKIN', 'Istri', 'KUDUS', '1964-02-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(216, '3319042305600001', '3319042707053514', 'SULIKAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'SULIKAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(217, '3319046008030001', '3319042707053514', 'SARNI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'SULIKAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(218, '3319041212930001', '3319042707053514', 'SARI SISWANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '1', 'SULIKAN', 'Anak Kandung', 'KUDUS', '1993-12-12', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(219, '3319042003530001', '3319042707053524', 'SUMADI PAING', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'SUMADI PAING', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(220, '3319046508550001', '3319042707053524', 'JASMIRAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'SUMADI PAING', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(221, '3319041011700007', '3319040912090022', 'SUMARI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'SUMARI', 'Kepala Keluarga', 'KUDUS', '1970-10-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(222, '3319055510750002', '3319040912090022', 'SUMIATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '1', '1', 'SUMARI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(223, '3319040906960002', '3319040912090022', 'WISNU KAWIRIYAH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '1', 'SUMARI', 'Anak Kandung', 'KUDUS', '1996-09-06', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(224, '3319041911030001', '3319040912090022', 'BHAYU AJI NUGROHO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '1', 'SUMARI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(225, '3319045405790004', '3319042704100014', 'KUSMINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'KUSMINAH', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(226, '3319044108110001', '3319042704100014', 'ZASKIA PUTRI PURBANINGRUM', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '1', '1', 'KUSMINAH', 'Anak Kandung', 'KUDUS', '2011-01-06', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum tamat sd/sederajat'),
(227, '3319041212750004', '3319042707053512', 'SUPANGAT', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'SUPANGAT', 'Kepala Keluarga', 'KUDUS', '1975-12-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(228, '3319045607760002', '3319042707053512', 'SAWINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'SUPANGAT', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(229, '3319046103070001', '3319042707053512', 'INGGIL WIJAYANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '1', 'SUPANGAT', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum tamat sd/sederajat'),
(230, '3319042012880003', '3319041709160001', 'SUPRIYANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '1', 'SUPRIYANTO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(231, '3319046407990002', '3319041709160001', 'SITI ISTIKOMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '1', '1', 'SUPRIYANTO', 'Istri', 'SEMARANG', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(232, '3319040212190001', '3319041709160001', 'MOHAMAT HASSAN RIYANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'SUPRIYANTO', 'Anak Kandung', 'KUDUS', '2019-02-12', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(233, '3319042004700007', '3319040912090025', 'SURATMAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'SURATMAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(234, '3319045107750001', '3319040912090025', 'ISTIQOMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'SURATMAN', 'Istri', 'KUDUS', '1975-11-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(235, '3319045907070001', '3319040912090025', 'DIANA ZULFA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'SURATMAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(236, '3319046311080003', '3319040912090025', 'SEKAR ARUM', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'SURATMAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(237, '3319041708880001', '3319042707053616', 'SURIAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'SURIAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum masuk TK/Kelompok Bermain'),
(238, '3319046604890001', '3319042707053616', 'NGATINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'SURIAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(239, '3319042009920006', '3319043001230002', 'NGATEMAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '1', 'NGATEMAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(240, '3319044403010001', '3319042707053616', 'NURUL SYAFITRI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '1', 'SURIAN', 'Anak Kandung', 'KUDUS', '2001-04-03', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(241, '3319040210840001', '3319043103110001', 'SURONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '1', 'SURONO', 'Kepala Keluarga', 'KUDUS', '1984-02-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(242, '3319045303920001', '3319043103110001', 'SRI MARYATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '1', '1', 'SURONO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(243, '3319042403110001', '3319043103110001', 'FARIS SYAFIQ AL FADLI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'SURONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(244, '3319047004680001', '3319042001170010', 'SUTINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'SUTINAH', 'Kepala Keluarga', 'KUDUS', '1968-10-04', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(245, '3319045711010002', '3319042001170010', 'DEWI ATIK ZULFITA SARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '1', 'SUTINAH', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(246, '3321082503880001', '3319041909120021', 'SUTRIMAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '1', 'SUTRIMAN', 'Kepala Keluarga', 'DEMAK', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(247, '3319044505910001', '3319041909120021', 'SULASTRI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '1', '1', 'SUTRIMAN', 'Istri', 'KUDUS', '1991-05-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(248, '3319040709120005', '3319041909120021', 'MUHAMMAD ALVIN PRAYOGA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'SUTRIMAN', 'Anak Kandung', 'KUDUS', '2012-07-09', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(249, '3319045712200003', '3319041909120021', 'NAADHIRA DESVI AZZAHRA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'SUTRIMAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(250, '3319043011680001', '3319042707054594', 'SUWANDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'SUWANDI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(251, '3319046508700001', '3319042707054594', 'SAROPAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'SUWANDI', 'Istri', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(252, '3319042802770001', '3319042707054610', 'SUWARNO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'SUWARNO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(253, '3319045909800006', '3319042707054610', 'JAMINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'SUWARNO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(254, '3319046303040002', '3319042707054610', 'NOVITA KHARISMA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'SUWARNO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(255, '3319040205940001', '3319040112150004', 'TEGUH SUPRIYANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '1', '1', 'TEGUH SUPRIYANTO', 'Kepala Keluarga', 'KUDUS', '1994-02-05', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(256, '3319046204930002', '3319041007170009', 'TITIK KARLINA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '1', '1', 'TITIK KARLINA', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(257, '3319047001120001', '3319041007170009', 'CITRA ZUNA SHABILLA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'TITIK KARLINA', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(258, '3319044206470001', '3319040310190003', 'WAGIYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '1', 'WAGIYAH', 'Kepala Keluarga', 'KUDUS', '1947-02-06', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(259, '3321090902930006', '3319042307180006', 'WAHYU HADI ROHMAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '1', 'WAHYU HADI RAHMAN', 'Kepala Keluarga', 'DEMAK', '1993-09-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SLTA/sederajat'),
(260, '3319044902980002', '3319042307180006', 'TUTIK ALWIYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '1', '1', 'WAHYU HADI RAHMAN', 'Istri', 'KUDUS', '1996-09-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(261, '3319040207180002', '3319042307180006', 'AYUBI BILAL RAHMAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '1', 'WAHYU HADI RAHMAN', 'Anak Kandung', 'KUDUS', '2018-02-07', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(262, '3319042003660001', '3319042707053523', 'WARDONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'WARDONO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(263, '3319046104750002', '3319042707053523', 'RIYATUN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '1', 'WARDONO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(264, '3319046212990001', '3319042707053523', 'SITI WARIYANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '1', 'WARDONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(265, 'NO. NIK', 'NO. KK', 'ANGGOTA KELUARGA', 'JENIS KELAMIN', NULL, NULL, 'AGAMA', 'PEKERJAAN', NULL, 'RT', 'RW', 'KEPALA KK', 'SETATUS DALAM KELUARGA', 'TEMPAT LAHIR', NULL, 'Kawin', 'KEWARGANEGARAAN', 'ETNIS/SUKU', 'PENDIDIKAN'),
(266, '3319043007800001', '3319042809070007', 'ADI PRASETYO', 'LAKI-LAKI', NULL, NULL, 'Kristen', 'Wiraswasta', NULL, '2', '1', 'ADI PRASETYO', 'Kepala Keluarga', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(267, '3319046112810005', '3319042809070007', 'JAMIATUN', 'PEREMPUAN', NULL, NULL, 'Kristen', 'Perangkat Desa', NULL, '2', '1', 'ADI PRASETYO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTA/sederajat'),
(268, '3319041808110002', '3319042809070007', 'STEPHEN AGUNG PRASETYO', 'LAKI-LAKI', NULL, NULL, 'Kristen', 'Belum Bekerja', NULL, '2', '1', 'ADI PRASETYO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(269, '3319045512160001', '3319042809070007', 'ALONA SHERYN PRASETYO', 'PEREMPUAN', NULL, NULL, 'Kristen', 'Belum Bekerja', NULL, '2', '1', 'ADI PRASETYO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(270, '3319041212780002', '3319042707053608', 'AHMAD MASKAT', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'AHMAD MASKAT', 'Kepala Keluarga', 'KUDUS', '1978-12-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(271, '3319045212810008', '3319042707053608', 'JAMSIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'AHMAD MASKAT', 'Istri', 'KUDUS', '1981-12-12', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(272, '3319044301010001', '3319042707053608', 'RIMA ALFIYA PUSPITA DEWI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '1', 'AHMAD MASKAT', 'Anak Kandung', 'KUDUS', '2001-03-01', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Belum Tamat SD/Sederajat'),
(273, '3319044902080001', '3319042707053608', 'MAEMUNA ZAHRATUL JANNAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'AHMAD MASKAT', 'Anak Kandung', 'KUDUS', '2008-09-02', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(274, '3319045508100002', '3319042707053608', 'RAHMA REGINA MUTIARA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'AHMAD MASKAT', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(275, '3319046412590002', '3319040607200003', 'KUNTINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'KUNTINI', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(276, '3319041905960005', '3319041804170002', 'ALEX SAPUTRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'ALEX SAPUTRA', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(277, '3315155207980003', '3319041804170002', 'ANA ISTIFAIZAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '2', '1', 'ALEX SAPUTRA', 'Istri', 'GROBOGAN', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(278, '3319045107170002', '3319041804170002', 'ARSYILA JOVANNYA SAKHI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'ALEX SAPUTRA', 'Anak Kandung', 'KUDUS', '2017-11-07', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(279, '3319040507600002', '3319040812090051', 'ALI IMRON', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'ALI IMRON', 'Kepala Keluarga', 'KUDUS', '1960-05-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(280, '3319047004590001', '3319040812090051', 'MO\'AH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'ALI IMRON', 'Istri', 'KUDUS', '1959-01-05', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(281, '3319042701630001', '3319042707053595', 'ALI MASHUDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'ALI MASHUDI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(282, '3319046903670001', '3319042707053595', 'SUTRIMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'ALI MASHUDI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(283, '3319061204720003', '3319040210090007', 'ARI WIBOWO', 'LAKI-LAKI', NULL, NULL, 'Kristen', 'Karyawan Perusahaan Swasta', NULL, '2', '1', 'ARI WIBOWO', 'Kepala Keluarga', 'KUDUS', '1972-12-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'AKADEMI/DIPLOMA III/S. MUDA'),
(284, '3319046012820002', '3319040210090007', 'SUNTARI', 'PEREMPUAN', NULL, NULL, 'Kristen', 'Bidan', NULL, '2', '1', 'ARI WIBOWO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'AKADEMI/DIPLOMA III/S. MUDA'),
(285, '3319045003100001', '3319040210090007', 'CHARISTIANNE PUTRITARI WIBOWO', 'PEREMPUAN', NULL, NULL, 'Kristen', 'Belum Bekerja', NULL, '2', '1', 'ARI WIBOWO', 'Anak Kandung', 'KUDUS', '2010-10-03', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(286, '3319046704880003', '3319040108180002', 'WAHYUNI ISWORO', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '2', '1', 'WAHYUNI ISWORO', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'DIPLOMA IV/STRATA I'),
(287, '3319040708650001', '3319042707053540', 'BASIRAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'BASIRAN', 'Kepala Keluarga', 'KUDUS', '1965-07-08', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(288, '3319040904670002', '3319042707053540', 'KUSMINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'BASIRAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(289, '3319046302030001', '3319042707053540', 'AKIKI MIRANDA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '2', '1', 'BASIRAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(290, '3318121204720004', '3319041402220006', 'EKO BUDI PRASETYO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Seniman', NULL, '2', '1', 'EKO BUDI PRASETYO', 'Kepala Keluarga', 'PATI', '1972-12-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(291, '3319044907900002', '3319041402220006', 'SUTARMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'EKO BUDI PRASETYO', 'Istri', 'KUDUS', '1990-09-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(292, '3319041904880001', '3319041212140011', 'FARIDWAN WIDODO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'FARIDWAN WIDODO', 'Kepala Keluarga', 'KUDUS', '1988-04-19', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(293, '3319044410950001', '3319041212140011', 'FITRIYANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'FARIDWAN WIDODO', 'Istri', 'KUDUS', '1995-05-10', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(294, ' 3319041504150001', '3319041212140011', 'NABIL ARDHANI AZFAR', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'FARIDWAN WIDODO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(296, '3319046612860001', '3319042809070012', 'SABIR', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'GUFRON', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(298, '3319047003150003', '3319042809070012', 'NALA FIRDATI UZ ZAHWA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'GUFRON', 'Anak Kandung', 'KUDUS', '2015-10-03', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(299, '3319040510910006', '3319041110160005', 'HARMOKO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'HARMOKO', 'Kepala Keluarga', 'KUDUS', '1991-05-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(300, '3319036512890004', '3319041110160005', 'MIRA NITA SETIYAWATI', 'PEREMPUAN', NULL, NULL, 'Islam', '', NULL, '2', '1', 'HARMOKO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', ''),
(301, '3319040806170001', '3319041110160005', 'MARCELLO GERALD SETIOKO', 'LAKI-LAKI', NULL, NULL, 'Islam', '', NULL, '2', '1', 'HARMOKO', 'Anak Kandung', 'KUDUS', '2017-08-06', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', ''),
(302, '3319042411860003', '3319040701110002', 'ISWAN NOVIYANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'ISWAN NOVIYANTO', 'Kepala Keluarga', 'KENDAL', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(303, '3319045404850001', '3319040701110002', 'NGUMROH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Guru', NULL, '2', '1', 'ISWAN NOVIYANTO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'DIPLOMA I/II'),
(304, '3319042911110001', '3319040701110002', 'FABIANS ABDILLAH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'ISWAN NOVIYANTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(305, '3319042406170001', '3319040701110002', 'MANGGALA AIDILBRATA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'ISWAN NOVIYANTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(306, '3319042607500001', '3319042707053538', 'JAMAL', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'JAMAL', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(307, '3319045311570001', '3319042707053538', 'SELAMET', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'JAMAL', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(308, '3319040212880003', '3319040110190002', 'JOKO MALIS', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'JOKO MALIS', 'Kepala Keluarga', 'KUDUS', '1988-02-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(309, '3319084303900005', '3319040110190002', 'PRASTYAWATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '2', '1', 'JOKO MALIS', 'Istri', 'KUDUS', '1990-03-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(310, '3319081104180001', '3319040110190002', 'AL KHALIFI AHZA ARSENIO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'JOKO MALIS', 'Anak Kandung', 'KUDUS', '2018-11-04', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(311, '3319041902470001', '3319042707053599', 'KAMSIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'KAMSIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(312, '3319045609430001', '3319042707053599', 'DARMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'KAMSIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(313, '3319042711850001', '3319042707053599', 'NGATIRAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'KAMSIN', 'Anak Kandung', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(314, '3319041209710001', '3319042707053530', 'KASMIRAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'KASMIRAN', 'Kepala Keluarga', 'KUDUS', '1971-12-09', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(315, '3319046104740002', '3319042707053530', 'SUKARTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'KASMIRAN', 'Istri', 'KUDUS', '1974-11-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(316, '3319045609970003', '3319042707053530', 'IKA ATIK ZULFAIDAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '1', 'KASMIRAN', 'Anak Kandung', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(317, '3319042012650001', '3319042707053609', 'KASMIN SUPARJO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '2', '1', 'KASMIN SUPARJO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(318, '3319045002680001', '3319042707053609', 'NARTUN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '2', '1', 'KASMIN SUPARJO', 'Istri', 'KUDUS', '1968-10-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(319, '3319046703040002', '3319042707053609', 'ICHA MARSELINDA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '1', 'KASMIN SUPARJO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(320, '3319044710050003', '3319042707053609', 'ANGGUN OKTAVIA RAMANDHANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'KASMIN SUPARJO', 'Anak Kandung', 'KUDUS', '2005-07-10', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(321, '3319040905740002', '3319040301090038', 'KASTONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'KASTONO', 'Kepala Keluarga', 'KUDUS', '1974-09-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(322, '3319044211840001', '3319040301090038', 'SRI LESTARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'KASTONO', 'Istri', 'KUDUS', '1984-02-11', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(323, '3319040801080002', '3319040301090038', 'AZKA MUHAMMAD IQBAL', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'KASTONO', 'Anak Kandung', 'KUDUS', '2008-08-01', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(324, '3319044302130001', '3319040301090038', 'FIKI ABIDATUZZAHRO', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'KASTONO', 'Anak Kandung', 'KUDUS', '2013-03-02', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(325, '3319043112500001', '3319042707053604', 'KASTUR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'KASTUR', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tidak pernah sekolah'),
(326, '3319047112580129', '3319042707053604', 'RATMISIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'KASTUR', 'Istri', 'KUDUS', '1959-01-01', 'Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(327, '3319041005870001', '3319042707053604', 'SUPARDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'KASTUR', 'Anak Kandung', 'KUDUS', '1987-10-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(328, '3319042212740001', '3319040202160006', 'KISWO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Kepala Desa', NULL, '2', '1', 'KISWO', 'Kepala Keluarga', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'DIPLOMA IV/STRATA I'),
(330, '3319040311880003', '3319041709130004', 'KUSMONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'KUSMONO', 'Kepala Keluarga', 'KUDUS', '1988-03-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(331, '3319046702960001', '3319041709130004', 'ANIK SOFWATUL ALIYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '2', '1', 'KUSMONO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(332, '3319044910140001', '3319041709130004', 'CANTIKA AULIA IZZAT UNNISA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'KUSMONO', 'Anak Kandung', 'KUDUS', '2014-09-10', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(333, '3319042008690001', '3319042707053529', 'KUSAERI', 'LAKI-LAKI', NULL, NULL, 'Kristen', 'Petani/Perkebun', NULL, '2', '1', 'KUSAERI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(334, '3319044603770001', '3319042707053529', 'SRI NGAENI', 'PEREMPUAN', NULL, NULL, 'Kristen', 'Petani/Perkebun', NULL, '2', '1', 'KUSAERI', 'Istri', 'KUDUS', '1977-06-03', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(335, '3319044311960002', '3319042707053529', 'SOFA BUDIYANI', 'PEREMPUAN', NULL, NULL, 'Kristen', 'Pelajar/Mahasiswa', NULL, '2', '1', 'KUSAERI', 'Anak Kandung', 'KUDUS', '1996-03-11', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(336, '331904474010002', '3319042707053529', 'APRILIA KUSUMAWATI', 'PEREMPUAN', NULL, NULL, 'Kristen', 'Pelajar/Mahasiswa', NULL, '2', '1', 'KUSAERI', 'Anak Kandung', 'KUDUS', '2001-07-04', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Belum Tamat SD/Sederajat'),
(337, '3319041503690002', '3319040103100037', 'LEGIYANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '2', '1', 'LEGIYANTO', 'Kepala Keluarga', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(338, '3319045201710002', '3319040103100037', 'MARSINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '2', '1', 'LEGIYANTO', 'Istri', 'KUDUS', '1971-12-01', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(339, '3319046305090003', '3319040103100037', 'WIWIK MUTIA HANDAYANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'LEGIYANTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(340, '3315151101760001', '3319043009190008', 'MOCH AMIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'MOCH AMIN', 'Kepala Keluarga', 'GROBOGAN', '1976-11-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(341, '3319045101760002', '3319043009190008', 'JATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'MOCH AMIN', 'Istri', 'KUDUS', '1976-12-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(342, '3319041202610008', '3319040405110003', 'MOHAMAD ALIYONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'MOHAMMAD ALIYONO', 'Kepala Keluarga', 'KUDUS', '1961-12-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(343, '3319044511830001', '3319040405110003', 'SITI ASIYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Guru swasta', NULL, '2', '1', 'MOHAMMAD ALIYONO', 'Istri', 'KUDUS', '1983-05-11', 'Kawin', 'Warga Negara Indonesia', '', 'DIPLOMA I/II'),
(344, '3319045204120001', '3319040405110003', 'SALSABILA HANDAYANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'MOHAMMAD ALIYONO', 'Anak Kandung', 'KUDUS', '2012-12-04', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(345, '3319046512220001', '3319040405110003', 'SILVIANA AYUDIA SYIFA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'MOHAMMAD ALIYONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(349, '3319046111820005', '3319042001210004', 'MUNTINGAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'MUNTINGAH', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(350, '3319041911000001', '3319042001210004', 'ANGGA PRASTYO WIBOWO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '1', 'MUNTINGAH', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(351, '3319045506120001', '3319042001210004', 'JINGGA AURA PERMATA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'MUNTINGAH', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(352, '3319042004650002', '3319042707053603', 'MURYADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'MURYADI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(353, '3319047011680001', '3319042707053603', 'SARAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'MURYADI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(354, '3319041302900003', '3319040807150003', 'MUSYAFIQIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '2', '1', 'MUSYAFIQIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(355, '3319046306950002', '3319040807150003', 'JAMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '2', '1', 'MUSYAFIQIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(356, '3319041506150004', '3319040807150003', 'MUHAMMAD ARKA PRADIPTA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'MUSYAFIQIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tiidak/BLM Sekolah'),
(357, '3319042009860001', '3319042705200001', 'MUSTAIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'MUSTAIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(358, '3315156104020001', '3319042705200001', 'EVA LIANA NOVITA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '1', 'MUSTAIN', 'Istri', 'GROBOGAN', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(359, '3319040710200001', '3319042705200001', 'AL HABSYI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'MUSTAIN', 'Anak Kandung', 'KUDUS', '2020-07-10', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(360, '3319042901740001', '3319042707053600', 'NGADIMIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'NGADIMIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(361, '3319045012780005', '3319042707053600', 'GUNARTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'NGADIMIN', 'Istri', 'KUDUS', '1978-10-12', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(362, '3319042612980001', '3319042707053600', 'NOOR SALIM', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '1', 'NGADIMIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(363, '3319045309040001', '3319042707053600', 'ALFI MUKAROMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '1', 'NGADIMIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Belum Tamat SD/Sederajat'),
(364, '3319043112760006', '3319042707053533', 'NGATENO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'NGATENO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(365, '3319044504810003', '3319042707053533', 'SRIPIT', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'NGATENO', 'Istri', 'KUDUS', '1981-05-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(366, '3319045909010001', '3319042707053533', 'ERIKA VINA SEPTIANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '1', 'NGATENO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(367, '3319041106120001', '3319042707053533', 'ILHAM ANUGRAH WIDODO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'NGATENO', 'Anak Kandung', 'KUDUS', '2012-11-06', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(368, '3319042803800002', '3319042707053525', 'NUR BAKOH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'NUR BAKOH', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(369, '3319044305850001', '3319042707053525', 'MARIYATUN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'NUR BAKOH', 'Istri', 'KUDUS', '1985-03-05', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(370, '3319044301120002', '3319042707053525', 'HELMA PUSPITA SARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'NUR BAKOH', 'Anak Kandung', 'KUDUS', '2012-03-01', 'Belum Kawin', 'Warga Negara Indonesia', '', 'TIDAK/BLM Sekolah'),
(371, '3319042903820001', '3319041202100038', 'NUR CHOLIS', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'NUR CHOLIS', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(372, '3319041508790001', '3319042304090023', 'NUR SAID', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'NUR SAID', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(373, '3319044509830001', '3319042304090023', 'SITI MUTIAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'NUR SAID', 'Istri', 'BOYOLALI', '1983-05-09', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTA/sederajat'),
(374, '3319046204070001', '3319042304090023', 'NIA WAKIDATUL MAHFIROH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'NUR SAID', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(375, '3319045703730002', '3319045703730002', 'NURYATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'NURYATI', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(376, '3319046804990004', '3319045703730002', 'PUTRI MAWAR', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '1', 'NURYATI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(377, '3319045908450001', '3319041806120003', 'PARMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'PARMI', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(378, '3318022910930001', '3319041909180001', 'PIPIT UTOMO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'KEPOLISIAN RI (POLRI)', NULL, '2', '1', 'PIPIT UTOMO', 'Kepala Keluarga', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(379, '3319045906960004', '3319041909180001', 'ANA SEPTIANI MUTIA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '2', '1', 'PIPIT UTOMO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTA/sederajat'),
(380, '3319044402910001', '3319041909180001', 'VALENCIA AIZA UTOMO', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'PIPIT UTOMO', 'Anak Kandung', 'KUDUS', '2019-04-02', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(381, '3315140805890003', '3319041806140003', 'RIYADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'RIYADI', 'Kepala Keluarga', 'GROBOGAN', '1989-08-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(382, '3319044206880001', '3319041806140003', 'RANI SUSANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '1', 'RIYADI', 'Istri', 'KUDUS', '1988-02-06', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(383, '3319046305140001', '3319041806140003', 'ADELIA FARANISA AZMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'RIYADI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Sedang TK/Kelompok Bermain'),
(384, '3319041606930001', '3319040509180003', 'ROHMAD', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'ROHMAD', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(385, '3319044708820007', '3319040509180003', 'SRI KASWATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '1', 'ROHMAD', 'Istri', 'KUDUS', '1982-08-09', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(386, '3319092903060005', '3319040509180003', 'MUHAMMAD ADITIYA ALVIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '1', 'ROHMAD', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(387, '3319044403190007', '3319040509180003', 'DINDA SEVTYANINGRUM', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'ROHMAD', 'Anak Kandung', 'KUDUS', '2019-04-03', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(388, '3319040811740001', '3319042810090007', 'ROMANI', 'LAKI-LAKI', NULL, NULL, 'Kristen', 'Petani', NULL, '2', '1', 'ROMANI', 'Kepala Keluarga', 'GROBOGAN', '1974-08-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(389, '3319046304780005', '3319042810090007', 'SUTIAH', 'PEREMPUAN', NULL, NULL, 'Kristen', 'Petani', NULL, '2', '1', 'ROMANI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(390, '3319046502040003', '3319042810090007', 'ELISA PURWANINGSIH', 'PEREMPUAN', NULL, NULL, 'Kristen', 'Belum Bekerja', NULL, '2', '1', 'ROMANI', 'Anak Kandung', 'GROBOGAN', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(391, '33190445804100002', '3319042810090007', 'YOHANITA DWI CRISTIANI', 'PEREMPUAN', NULL, NULL, 'Kristen', 'Belum Bekerja', NULL, '2', '1', 'ROMANI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(392, '3319041012500001', '3319042707053535', 'RUSMIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'RUSMIN', 'Kepala Keluarga', 'PATI', '1950-10-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(393, '3319045005680001', '3319042707055782', 'HARTINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'KAMTO', 'Istri', 'KUDUS', '1968-10-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(394, '3319041810730004', '3319042707053597', 'SARIMIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'SARIMIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(395, '3319046606820001', '3319042707053597', 'ZULIATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'SARIMIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(396, '3319045910970001', '3319042707053597', 'IKA ZULIA SARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '1', 'SARIMIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'SLTP/Sederajat'),
(397, '3319041904050001', '3319042707053597', 'MUHAMMAD SYARIFF ZULIANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'SARIMIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(398, '3319041510580001', '3319042707053532', 'SARU SUNAR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'SARU SUNAR', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(399, '3319046009570001', '3319042707053532', 'SUMARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'SARU SUNAR', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(400, '3319040802680001', '3319042707053615', 'SENEN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'SENEN', 'Kepala Keluarga', 'KUDUS', '1968-08-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(401, '3319045012730001', '3319042707053615', 'TEMU', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'SENEN', 'Istri', 'KUDUS', '1973-10-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(402, '3319040108980001', '3319042707053615', 'MUHAMMAD CHAERUL ANAM', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'SENEN', 'Anak Kandung', 'KUDUS', '1996-01-08', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(403, '3319041011500001', '3319040412090018', 'SENEN SUYONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'SENEN SUYONO', 'Kepala Keluarga', 'KUDUS', '1950-10-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(404, '3319046907570001', '3319040412090018', 'SUMINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'SENEN SUYONO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(405, '3319042103900001', '3319040412090018', 'PUJIONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '1', 'SENEN SUYONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(406, '3319042802570001', '3319041112090041', 'SUDARIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'SUDARIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(407, '3319046006570001', '3319041112090041', 'BATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'SUDARIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(408, '3319040112900003', '3319040703150002', 'SUMADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '1', 'SUMADI', 'Kepala Keluarga', 'KUDUS', '1990-01-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(409, '3319044209950001', '3319040703150002', 'SITI AMINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '2', '1', 'SUMADI', 'Kepala Keluarga', 'KUDUS', '1995-03-09', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(410, '3319043103130002', '3319040703150002', 'RENO GIOVANO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'SUMADI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(411, '3319040910220001', '3319040703150002', 'MARCELLO ACOSTA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '', '', 'SUMADI', 'Anak Kandung', 'KUDUS', '2022-09-10', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(412, '3319041011400003', '3319042707053606', 'SUMADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'SUMADI', 'Kepala Keluarga', 'KUDUS', '1940-10-11', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(413, '3319052211820005', '3319042108140006', 'SUUDIYONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '1', 'SUUDIYONO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(414, '3319045305940001', '3319042108140006', 'PUJIANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '1', 'SUUDIYONO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(415, '3319046311140002', '3319042108140006', 'AGNES ANINDITA NASHA RAFANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'SUUDIYONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(416, '3319045004940001', '3319042402200001', 'SITI PRIHATIN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '2', '1', 'SITI PRIHATIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat');
INSERT INTO `tb_penduduk` (`id`, `nik`, `no_kk`, `nama`, `jenis_kelamin`, `tempat_tgl_lahir`, `umur`, `agama`, `pekerjaan`, `alamat`, `rt`, `rw`, `kepala_kk`, `status_keluarga`, `tempat_lahir`, `tgl_lahir`, `status_pernikahan`, `kewarganegaraan`, `suku`, `pendidikan`) VALUES
(417, '3319044302150002', '3319042402200001', 'FEBY CHINTYA RAHMA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'SITI PRIHATIN', 'Anak Kandung', 'KUDUS', '2015-03-02', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(418, '3319020106830008', '3319042711120002', 'SLAMET ROHADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'SLAMET ROHADI', 'Kepala Keluarga', 'KUDUS', '1983-01-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(419, '3319046803860003', '3319042711120002', 'DEWI HASTUTIK', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'SLAMET ROHADI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(420, '3319042301140002', '3319042711120002', 'MUHAMMAD ADYESTA AQILA PRATAMA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'SLAMET ROHADI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(421, '3319042603210001', '3319042711120002', 'FAIZIL ZAFLAN ALFARIZQI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'SLAMET ROHADI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(422, '3319045908870005', '3319040207180013', 'SRI MULYANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'SRI MULYANI', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(423, '3319041211150003', '3319040207180013', 'HABIBI ALFIAN IHSANI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'SRI MULYANI', 'Anak Kandung', 'KUDUS', '2015-12-11', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(424, '3319041204650001', '3319042707053605', 'SUKARIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Perangkat Desa', NULL, '2', '1', 'SUKARIN', 'Kepala Keluarga', 'KUDUS', '1965-12-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(425, '3319045208680002', '3319042707053605', 'SUTINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'SUKARIN', 'Istri', 'KUDUS', '1968-12-08', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(426, '3319041105900003', '3319042707053605', 'ENDRIYANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'SUKARIN', 'Anak Kandung', 'KUDUS', '1990-11-05', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'DIPLOMA IV/STRATA I'),
(427, '3319045909000001', '3319042707053605', 'ENDANG RATNASARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '1', 'SUKARIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(428, '3319045204640001', '3319042707053602', 'SUKIJAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '2', '1', 'SUKIJAH', 'Kepala Keluarga', 'KUDUS', '1964-12-04', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(429, '3319040605810001', '3319042804070004', 'SUMINTRI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'SUMINTRI', 'Kepala Keluarga', 'KUDUS', '1981-06-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(430, '3319047001840002', '3319042804070004', 'SUNDARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '2', '1', 'SUMINTRI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(431, '3319046511040001', '3319042804070004', 'DIAH ARUM KUSUMANINGTYAS', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '2', '1', 'SUMINTRI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Belum Tamat SD/Sederajat'),
(432, '3319040602140001', '3319042804070004', 'FEBRIAN HUTAMA PUTRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'SUMINTRI', 'Anak Kandung', 'KUDUS', '2014-06-02', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(433, '3319041012740001', '3319042804070003', 'SUNAR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'SUNAR', 'Kepala Keluarga', 'KUDUS', '1974-10-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(434, '3319045408780001', '3319042804070003', 'SITI KHOIROTUS NISFIYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'SUNAR', 'Istri', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(435, '3319041302050001', '3319042804070003', 'KAFI HUDAYA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'SUNAR', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(436, '3319041201110001', '3319042804070003', 'ROSIKH FAHMI MUHAMMAD', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'SUNAR', 'Anak Kandung', 'KUDUS', '2011-12-01', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(437, '3319040306710001', '3319042707053593', 'SUPANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'SUPANTO', 'Kepala Keluarga', 'KUDUS', '1971-03-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(438, '3319045011780001', '3319042707053593', 'NGATMI AL UMIYATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'SUPANTO', 'Istri', 'SEMARANG', '1987-11-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(439, '3319041404030003', '3319042707053593', 'REFI ADITYA MINTAHAR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '1', 'SUPANTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(440, '3319040111140001', '3319042707053593', 'NAUVAL KRIDA WIRATAMA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'SUPANTO', 'Anak Kandung', 'KUDUS', '2014-01-11', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(441, '3319042605780001', '3319042707053544', 'SUPARI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'SUPARI', 'Kepala Keluarga', 'SEMARANG', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(442, '3319045603820002', '3319042707053544', 'SITI RUSMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'SUPARI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(443, '3319041406030001', '3319042707053544', 'MOHAMAD ALIF FAHRUDIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '1', 'SUPARI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(444, '3319040706120001', '3319042707053544', 'AHMAD ARFIANSAH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'SUPARI', 'Anak Kandung', 'KUDUS', '2012-07-06', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'TIDAK/BLM Sekolah'),
(445, '3319041012640001', '3319042707053528', 'SUPARI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'SUPARI', 'Kepala Keluarga', 'KUDUS', '1964-10-12', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(446, '3319042106940001', '3319042707053528', 'TRI YONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'SUPARI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(447, '3319041708680001', '3319042707053616', 'SURIAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'SURIAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(448, '3319046604690001', '3319042707053616', 'NGATINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'SURIAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(451, '3319045112570001', '3319042707053598', 'SURIMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'SURIMI', 'Kepala Keluarga', 'KUDUS', '1957-11-12', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(452, '3319042311850005', '3319041302090001', 'SYAIFUL ANWAR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '1', 'SYAIFUL ANWAR', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(453, '3319046706820002', '3319041302090001', 'NGATMINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'SYAIFUL ANWAR', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTA/sederajat'),
(454, '3319047112070003', '3319041302090001', 'INDI LAMINATUL MUNA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '1', 'SYAIFUL ANWAR', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Belum Tamat SD/Sederajat'),
(455, '3319040308160001', '3319041302090001', 'AGA SAPUTRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'SYAIFUL ANWAR', 'Anak Kandung', 'KUDUS', '2016-03-08', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'TIDAK/BLM Sekolah'),
(456, '3319041911770001', '3319042707053537', 'TEMON', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'TEMON', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(457, '3319046408800001', '3319042707053537', 'UMIASIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Guru swasta', NULL, '2', '1', 'TEMON', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'STRATA II'),
(458, '3319041608630001', '3319042707053539', 'WARSIDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'WARSIDI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(459, '3319045210650003', '3319042707053539', 'LEGI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'WARSIDI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(460, '3319040806950001', '3319042707053539', 'ROHMAT HIDAYAT', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '1', 'WARSIDI', 'Anak Kandung', 'KUDUS', '1995-08-05', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(461, '3319040607900002', '3319042411110002', 'WAHYU YULI ANGGORO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '1', 'WAHYU YULI ANGGORO', 'Kepala Keluarga', 'KUDUS', '1990-06-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(462, '3321086303990003', '3319042411110002', 'RITNA IRFIANA SARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '2', '1', 'WAHYU YULI ANGGORO', 'Istri', 'DEMAK', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(463, '3319044312160001', '3319042411110002', 'DELISA ANGGUN LESTARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '1', 'WAHYU YULI ANGGORO', 'Anak Kandung', 'DEMAK', '2015-03-12', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(464, '3319045612880001', '3319042702190005', 'ZUN WAHYUNI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '1', 'ZUN WAHYUNI', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(466, '3319040505660007', '3319041112090073', 'ACHMAD JUREMI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'ACHMAD JUREMI', 'Kepala Keluarga', 'DEMAK', '1966-05-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(467, '33190451001730001', '3319041112090073', 'KUSMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'ACHMAD JUREMI', 'Istri', 'KUDUS', '1973-11-01', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(468, '3319043005090001', '3319041112090073', 'AHMAT PRIYANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'ACHMAD JUREMI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(469, '3319042409990006', '3319042204210002', 'ADE IRWANSAH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '1', 'ADE IRWANSAH', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(470, '3315155110020004', '3319042204210002', 'NANIK ROHMAYANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '1', 'ADE IRWANSAH', 'Istri', 'GROBOGAN', '2002-11-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(471, '3319047103210001', '3319042204210002', 'MAUREEN ANGELISTA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'ADE IRWANSAH', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(472, '3319041207910001', '3319042412130002', 'AGUS SRIYONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '1', 'AGUS SRIYONO', 'Kepala Keluarga', 'KUDUS', '1991-12-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(473, '3319076803920001', '3319042412130002', 'FARIDA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '3', '1', 'AGUS SRIYONO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTA/sederajat'),
(474, '3319042106140003', '3319042412130002', 'NAUFAL HILMIAT TAQIY', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '1', 'AGUS SRIYONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(475, '3319040201190002', '3319042412130002', 'REYHAN ZAID ALFARIZQI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '1', 'AGUS SRIYONO', 'Anak Kandung', 'KUDUS', '2019-02-01', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(476, '3319041204040001', '3319041912160002', 'AGUNG DICKY MAHENDRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'AGUNG DICKY MAHENDRA', 'Kepala Keluarga', 'KUDUS', '2004-12-04', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(477, '3318052803880010', '3319040711130001', 'AHMAD ISMAIL', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '1', 'AHMAD ISMAIL', 'Kepala Keluarga', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(478, '3319047004910001', '3319040711130001', 'SARAH WIDLAWATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '1', 'AHMAD ISMAIL', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(479, '3319046810140001', '3319040711130001', 'NAHLATUS SYAHADAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'AHMAD ISMAIL', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Sedang TK/Kelompok Bermain'),
(480, '3319041002220001', '3319040711130001', 'AHMAD ALBY ALFARIZKY', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '', '', 'AHMAD ISMAIL', 'Anak Kandung', 'KUDUS', '2022-10-02', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(481, '3319043003810001', '3319041112090087', 'ALI ROSO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'ALI ROSO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(482, '3319047103860001', '3319041112090087', 'SUTRIMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '3', '1', 'ALI ROSO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(483, '3319046506070001', '3319041112090087', 'NADYA EFFIE KUMALA DEWI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'ALI ROSO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Sedang SLTP/Sederajat'),
(484, '3319044801160004', '3319041112090087', 'ANINDITA ALYA ZAHRA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'ALI ROSO', 'Anak Kandung', 'KUDUS', '2016-08-01', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Sedang TK/Kelompok Bermain'),
(485, '3319045807180001', '3319041112090087', 'AULIA ZITRA KIRANA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'ALI ROSO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Belum masuk TK/Kelompok Bermain'),
(486, '3315122808950001', '3319042812210004', 'BAYU BAGUS PRABOWO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '3', '1', 'BAYU BAGUS PRABOWO', 'Kepala Keluarga', 'GROBOGAN', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(487, '3319046711980001', '3319042812210004', 'JUWITA SARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '3', '1', 'BAYU BAGUS PRABOWO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(488, '3319042703190003', '3319042812210004', 'AHMAD RADITYA PRATAMA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'BAYU BAGUS PRABOWO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(489, '3319040504690001', '3319042707053591', 'BROJONOTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'BROJONOTO', 'Kepala Keluarga', 'KUDUS', '1969-05-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(490, '3319045607760004', '3319042707053591', 'NGATMINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'BROJONOTO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(491, '3319042506040001', '3319042707053591', 'IRSYAD SYAUQY MUHAMMAD', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '1', 'BROJONOTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(492, '3319040611140001', '3319042707053591', 'ASYROF KHOIRUL AZAM', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'BROJONOTO', 'Anak Kandung', 'KUDUS', '2014-06-11', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(493, '3321022012850002', '3319042706120002', 'DEDY SANTOSA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '3', '1', 'DEDY SANTOSA', 'Kepala Keluarga', 'DEMAK', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTA/sederajat'),
(494, '3319045003920001', '3319042706120002', 'SULISTIANINGRUM', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '3', '1', 'DEDY SANTOSA', 'Istri', 'KUDUS', '1992-10-03', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTA/sederajat'),
(495, '3319041407120005', '3319042706120002', 'REZKY LANGIT RAMADHAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'DEDY SANTOSA', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(496, '3319040612160001', '3319042706120002', 'HAIDAR ARSYA KHADAFI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'DEDY SANTOSA', 'Anak Kandung', 'KUDUS', '2016-06-12', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(497, '3319040601580001', '3319042707053586', 'DJUHADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '3', '1', 'DJUHADI', 'Kepala Keluarga', 'KUDUS', '1958-06-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(498, '3319046804600003', '3319042707053586', 'RUMISIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '3', '1', 'DJUHADI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tidak pernah sekolah'),
(499, '3319041709660001', '3319042707053574', 'HARDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'HARDI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(500, '3319045801740003', '3319042707053574', 'KASMINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'HARDI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(501, '3319046906020001', '3319042707053574', 'RATNA SAGITA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'HARDI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Sedang SLTA/sederajat'),
(502, '3577031804840006', '3319042403170001', 'HERI KARTIKA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Tukang Batu', NULL, '3', '1', 'HERI KARTIKA', 'Kepala Keluarga', 'MADIUN', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(503, '3319045203780005', '3319042403170001', 'SUNARTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '3', '1', 'HERI KARTIKA', 'Istri', 'KUDUS', '1978-12-03', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(504, '3319045805170002', '3319042403170001', 'ARSYILA WILDA AZZAHRA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'HERI KARTIKA', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(505, '3319042807600001', '3319041112090078', 'JAMI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'JAMI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(506, '3319045309660001', '3319041112090078', 'RUMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'JAMI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(507, '3319046203970002', '3319041112090078', 'PURI RETNO MUTIA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'JAMI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'SLTA/Sederajat'),
(508, '3319046009610002', '3319042707053578', 'JAMIRAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'JAMIRAH', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(509, '3319042112860001', '3319042707053578', 'ABDUL GHOFUR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '1', 'JAMIRAH', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(510, '3319041007920004', '3319042707053578', 'AHMAD RIYONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '1', 'JAMIRAH', 'Anak Kandung', 'KUDUS', '1992-10-07', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(511, '3319046106470002', '3319041112090070', 'JAMIYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '3', '1', 'JAMIYAH', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', '', 'Tidak pernah sekolah'),
(512, '3319041012780004', '3319042707053575', 'JAMRUN ZULIANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '1', 'JAMRUN ZULIANTO', 'Kepala Keluarga', 'KUDUS', '1978-10-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(513, '3319045309800001', '3319042707053575', 'MUSTOFIAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '1', 'JAMRUN ZULIANTO', 'Istri', 'JEPARA', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(514, '3319045607020005', '3319042707053575', 'JAUWHANES JULIA PUTRI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'JAMRUN ZULIANTO', 'Anak Kandung', 'JEPARA', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Sedang SLTA/sederajat'),
(515, '3319040911130001', '3319042707053575', 'MOHAMAD JAMUS INDRA SAMUDRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'JAMRUN ZULIANTO', 'Anak Kandung', 'KUDUS', '2013-09-11', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(516, '3319045710750003', '3319043001990009', 'JASIRAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'JASIRAH', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(517, '3319046112040001', '3319043001990009', 'WAHYU SRI LESTARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'JASIRAH', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Sedang SLTA/sederajat'),
(518, '3319044803750001', '3319042707053612', 'JUMI\'AH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '1', 'JUMI\'AH', 'Kepala Keluarga', 'KUDUS', '1975-08-03', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(519, '3319041905950001', '3319042707053612', 'ROFIK ULIL ABSOR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '1', 'JUMI\'AH', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(520, '3319040203710001', '3319042707053568', 'KADAR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'KADAR', 'Kepala Keluarga', 'KUDUS', '1971-02-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(521, '3319045508800007', '3319042707053568', 'SUMARNI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'KADAR', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(522, '3319040107050002', '3319042707053568', 'TAUFIK NUR HIDAYAT', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'KADAR', 'Anak Kandung', 'KUDUS', '2005-01-07', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(523, '3319040311020002', '3319041112090017', 'KANIF UDIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '1', 'SUWARNO', 'Anak Kandung', 'KUDUS', '2002-03-11', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(524, '3319046806020001', '3319042110220003', 'PUTRI INDAH PERWIRA SARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '1', 'KANIF UDIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(525, '3319042906580001', '3319042707053562', 'KAOLAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'KAOLAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(526, '3319046404570001', '3319042707053562', 'SUKILAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'KAOLAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tidak pernah sekolah'),
(527, '3319040709750003', '3319041112090066', 'KASMIAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'KASMIAN', 'Kepala Keluarga', 'KUDUS', '1975-07-09', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(528, '3319045210780003', '3319041112090066', 'SUTI\'AH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'KASMIAN', 'Istri', 'KUDUS', '1978-12-10', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(529, '3319041111970001', '3319041112090066', 'MIFTAKUL KAFINDUN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '1', 'KASMIAN', 'Anak Kandung', 'KUDUS', '1997-11-11', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(530, '3319041010050003', '3319041112090066', 'AHMAT NOR FAIZ', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'KASMIAN', 'Anak Kandung', 'KUDUS', '2005-10-10', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(531, '3319041107790005', '3319041112090069', 'KHUNUT', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'KHUNUT', 'Kepala Keluarga', 'KUDUS', '1979-11-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(532, '3319044505840001', '3319041112090069', 'SOLECHAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'KHUNUT', 'Istri', 'KUDUS', '1984-05-05', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(533, '3319046111010001', '3319041112090069', 'KHOIRU ANIN NISA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'KHUNUT', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Sedang SD/sederajat'),
(534, '3319041910120001', '3319041112090069', 'PANJI SAMUDRO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'KHUNUT', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(535, '3319042712170001', '3319041112090069', 'MUHAMMAD AZRIL RAKHSAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'KHUNUT', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(536, '3319046507500001', '3319042707053579', 'KUNTIMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '1', 'KUNTIMAH', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(537, '3321070301850002', '3319042104110003', 'M NURSOLIKIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '1', 'M NURSOLIKIN', 'Kepala Keluarga', 'DEMAK', '1985-03-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(538, '3319046505890001', '3319042104110003', 'JUMIATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '1', 'M NURSOLIKIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(539, '3319040903110001', '3319042104110003', 'AHMAD NOR SHAFII', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'M NURSOLIKIN', 'Anak Kandung', 'KUDUS', '2011-09-03', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(540, '3319042407200001', '3319042104110003', 'M ADHA HAFIZH ALFAEYZA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'M NURSOLIKIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(541, '3319072211970005', '3319042109200007', 'M WISNU WICAKSONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '1', 'M. WISNU WICAKSONO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(542, '3319046206000001', '3319042109200007', 'EVA SAFIRA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '1', 'M. WISNU WICAKSONO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTA/sederajat'),
(543, '3319041709200001', '3319042109200007', 'MOHAMMAD DANISH EVANO PRATAMA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'M. WISNU WICAKSONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(544, '3319040507690004', '3319040507690004', 'MAD SHOLIHIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '1', 'MAD SHOLIHIN', 'Kepala Keluarga', 'GROBOGAN', '1969-05-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(545, '3319045506690005', '3319041112090084', 'MURMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '1', 'MOH SHOLIHIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(546, '3319042902520001', '3319042707053558', 'MASDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '3', '1', 'MASDI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(547, '3319045805550001', '3319042707053558', 'NGATONAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '3', '1', 'MASDI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(548, '3319042002640001', '3319042707053577', 'MASMIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'MASMIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(549, '3319047103670001', '3319042707053577', 'SULATUN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'MASMIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(550, '3319046309810001', '3319043012160001', 'MI\'AH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pedagang Keliling', NULL, '3', '1', 'MI\'AH', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(551, '3319042609010003', '3319043012160001', 'DAVID ARDHIASTA MARCELLINO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'MI\'AH', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(552, '3319046511090001', '3319043012160001', 'MEGA DWI AYYU MUSTIKA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'MI\'AH', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SD/sederajat'),
(553, '3319042204190003', '3319043012160001', 'GILANG WIRA ADITAMA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '1', 'MI\'AH', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(554, '3319040903790003', '3319041112090089', 'MOH ALI DARSONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '3', '1', 'MOH ALI DARSONO', 'Kepala Keluarga', 'DEMAK', '1979-09-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(555, '3319047107810001', '3319041112090089', 'KASIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'MOH ALI DARSONO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tidak pernah sekolah'),
(556, '3319042802000001', '3319041112090089', 'JOKO SASONGKO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '1', 'MOH ALI DARSONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(557, '3319043107100004', '3319041112090089', 'RENDY EGGA PRAWANA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'MOH ALI DARSONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(558, '3310940507690004', '3319041112090084', 'MOH SHOLIHIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '1', 'MOH SHOLIHIN', 'Kepala Keluarga', 'KUDUS', '1969-05-09', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(560, '3321091403940003', '3319041311180002', 'MOHAMAD AIDIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '3', '1', 'MOHAMAD AIDIN', 'Kepala Keluarga', 'DEMAK', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(561, '3319044101960001', '3319041311180002', 'SRIYANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '3', '1', 'MOHAMAD AIDIN', 'Istri', 'KUDUS', '1996-01-01', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTA/sederajat'),
(562, '3319042102190002', '3319041311180002', 'KEVIN PUTRA ANDREANSYAH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'MOHAMAD AIDIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(563, '3319041704690001', '3319042707053585', 'MOHAMAD SHODIQ', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'MOHAMAD SHODIQ', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(564, '3319044201780008', '3319040901230007', 'SITI RUKINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'SITI RUKINI', 'Kepala Keluarga', 'KUDUS', '1978-02-01', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(565, '3319046311070001', '3319040901230007', 'RIYANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'SITI RUKINI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Sedang SLTP/Sederajat'),
(566, '3315030303850001', '3319040912090024', 'MUHAMAD MUZAMIL', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '1', 'MUHAMMAD MUZAMIL', 'Kepala Keluarga', 'GROBOGAN', '1985-03-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(567, '3319044503840005', '3319040912090024', 'SUNAMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Guru swasta', NULL, '3', '1', 'MUHAMMAD MUZAMIL', 'Istri', 'KUDUS', '1984-06-03', 'Kawin', 'Warga Negara Indonesia', '', 'DIPLOMA IV/STRATA I'),
(568, '3319041305080001', '3319040912090024', 'MUHAMAT RIZAL PUTRA AZAMI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '1', 'MUHAMMAD MUZAMIL', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(569, '3319043005180002', '3319040912090024', 'ALIAZ ESHAAL KESAWA RAHMADHANI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '1', 'MUHAMMAD MUZAMIL', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(570, '3319041407950002', '3319042311210003', 'MUKHAMAD KAERUL UMAM', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '1', 'MUKHAMAD KAERUL UMAM', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(571, '3319046212970001', '3319042311210003', 'SUCI INDAH SARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '3', '1', 'MUKHAMAD KAERUL UMAM', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'DIPLOMA IV/STRATA I'),
(572, '3319042405700002', '3319041112090079', 'NGADENIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'NGADENIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(573, '3319045212750006', '3319041112090079', 'KASMONAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'NGADENIN', 'Istri', 'KUDUS', '1975-12-12', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(574, '3319045008000001', '3319041112090079', 'WINDI ERNA AGUSTIN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'NGADENIN', 'Anak Kandung', 'KUDUS', '2000-10-08', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Sedang S-1/sederajat'),
(575, '3319040508790001', '3319042107090015', 'NGARIJAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '1', 'NGARIJAN', 'Kepala Keluarga', 'KUDUS', '1979-05-08', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(576, '3319047112830032', '3319042107090015', 'SUMIATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '1', 'NGARIJAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(577, '3319044402090001', '3319042107090015', 'UMI FATIMATUZ ZAHRA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'NGARIJAN', 'Anak Kandung', 'KUDUS', '2009-04-02', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Sedang SD/sederajat'),
(578, '3319045905170002', '3319042107090015', 'NURUL LATIFATIL KAROMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '1', 'NGARIJAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Belum masuk TK/Kelompok Bermain'),
(580, '3318035005900002', '3319043001230002', 'ANISKA MEI SARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '', '', 'NGATEMAN', 'Istri', 'PATI', '1990-10-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(581, '3319041503220001', '3319043001230002', 'MUHAMMAD ZEVAN ALBY DAVIE FORTUNIO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '', '', 'NGATEMAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(582, '3319041406930002', '3319040302220001', 'NOOR EFENDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '1', 'NOOR EFENDI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(583, '3319045709930002', '3319040302220001', 'UMI SAIRIYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '3', '1', 'NOOR EFENDI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(584, '3319040601920001', '3320072107170002', 'NOR WAHID', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '3', '1', 'NOR WAHID', 'Kepala Keluarga', 'KUDUS', '1992-06-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(585, '332007460920004', '3320072107170002', 'LINDA KISMAWATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '3', '1', 'NOR WAHID', 'Istri', 'JEPARA', '1994-06-05', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(586, '3320073101160004', '3320072107170002', 'MUHAMMAD AZZAM NUR WAHID', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '1', 'NOR WAHID', 'Anak Kandung', 'JEPARA', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(587, '3319041510590001', '3319042707053570', 'NUR KADIG', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '1', 'NUR KADIG', 'Kepala Keluarga', 'DEMAK', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(588, '3319045002760001', '3319042707053570', 'SULASTRI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'NUR KADIG', 'Istri', 'KUDUS', '1976-10-02', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(589, '3319044504000001', '3319042707053570', 'NURIN INAYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'NUR KADIG', 'Anak Kandung', 'KUDUS', '2000-05-04', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(590, '3319040301070002', '3319042707053570', 'ALI MUHAMAD NUSRON', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '1', 'NUR KADIG', 'Anak Kandung', 'KUDUS', '2007-03-01', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(591, '3319040503820006', '3319042809070009', 'NURKONDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '1', 'NURKONDI', 'Kepala Keluarga', 'KUDUS', '1982-05-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(592, '3319045905860005', '3319042809070009', 'FITRI NENI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '3', '1', 'NURKONDI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(593, '3319042812060001', '3319042809070009', 'WAHYU AJI PRATAMA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '1', 'NURKONDI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(594, '3319046808170001', '3319042809070009', 'ASTI AULIA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '1', 'NURKONDI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Belum masuk TK/Kelompok Bermain'),
(595, '3319041106710001', '3319042707053556', 'PARINDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'PARINDI', 'Kepala Keluarga', 'KUDUS', '1971-11-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(596, '3319044708750001', '3319042707053556', 'KARTINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'PARINDI', 'Istri', 'KUDUS', '1975-07-08', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(597, '3319041001570001', '3319042707053564', 'RAJI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '1', 'RAJI', 'Kepala Keluarga', 'KUDUS', '1957-10-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(598, '3319044712580001', '3319042707053564', 'KUNYATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'RAJI', 'Istri', 'KUDUS', '1958-07-12', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(599, '3322155207750007', '3319041812130006', 'RALIN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '1', 'RALIN', 'Kepala Keluarga', 'KUDUS', '1975-12-07', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', '', 'Tamat SLTA/sederajat'),
(600, '3322156708990001', '3319041812130006', 'PUTRI ISTIQOMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'RALIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTA/sederajat'),
(601, '3322155512050002', '3319041812130006', 'DESTIANA SAFITRI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'RALIN', 'Anak Kandung', 'KABUPATEN SEMARANG', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Sedang SLTP/Sederajat'),
(604, '3321081008930001', '3319042810190004', 'ROHMAD NAHROWI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '1', 'ROHMAD NAHROWI', 'Kepala Keluarga', 'DEMAK', '1993-10-08', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(605, '3319046004950001', '3319042810190004', 'PUJI HASTUTIK', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '1', 'ROHMAD NAHROWI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTA/sederajat'),
(606, '3319041008020001', '3319042810190004', 'PUGOH SULISTIYO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '1', 'ROHMAD NAHROWI', 'Famili Lain', 'KUDUS', '2002-10-08', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(607, '3319043009950001', '3319042705190005', 'RUDI KRISTIANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '1', 'RUDI KRISTIANTO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(608, '3315155606010005', '3319042705190005', 'ZUNITA ANGGRAINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '1', 'RUDI KRISTIANTO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(609, '3319041910190001', '3319042705190005', 'BRIYAN ARYA PUTRA PRADANA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '1', 'RUDI KRISTIANTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(610, '3319041712620002', '3319042202100021', 'RUKAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'RUKAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(611, '3319045201650005', '3319042202100021', 'BASNI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'RUKAN', 'Istri', 'KUDUS', '1965-12-01', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(612, '3319040211830002', '3319042202100021', 'SUPALIL', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'RUKAN', 'Anak Kandung', 'KUDUS', '1983-02-11', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(613, '3319042412870006', '3319042202100021', 'JOKO KISTORO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'RUKAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(614, '3319040704950001', '3319042202100021', 'JADANG SAPUTRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'RUKAN', 'Anak Kandung', 'KUDUS', '1995-07-04', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(615, '3319045207630001', '3319042707053560', 'RUKAYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '3', '1', 'RUKAYAH', 'Kepala Keluarga', 'KUDUS', '1963-12-07', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', '', 'Tidak pernah sekolah'),
(616, '3319040911770003', '3319042710080010', 'SAMAT SUPRIYADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'SAMAT SUPRIYADI', 'Kepala Keluarga', 'KUDUS', '1977-09-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(617, '3319045106820001', '3319042710080010', 'SURATMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'SAMAT SUPRIYADI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(618, '3319044196990001', '3319042710080010', 'DEBI INDAH RETNOSARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '3', '1', 'SAMAT SUPRIYADI', 'Anak Kandung', 'KUDUS', '1999-01-06', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(619, '3319045909080001', '3319042710080010', 'ROMDHONA PUSPUTA DEWI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'SAMAT SUPRIYADI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SD/sederajat'),
(620, '3319040501800001', '3319040412090008', 'SANTOSO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Perawat', NULL, '3', '1', 'SANTOSO', 'Kepala Keluarga', 'KUDUS', '1980-05-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'AKADEMIK/DIPLOMA III/SARJANA MUDA'),
(621, '3319046102820003', '3319040412090008', 'SITI FATONAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pegawai Negeri Sipil', NULL, '3', '1', 'SANTOSO', 'Istri', 'DEMAK', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'DIPLOMA IV/STRATA I'),
(622, '3319044910070003', '3319040412090008', 'FARAH FADILA TSAQIF', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '1', 'SANTOSO', 'Anak Kandung', 'KUDUS', '2007-09-10', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(623, '3319040111130001', '3319040412090008', 'FARHAN NAUFAL AMIRUZZACKY', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '1', 'SANTOSO', 'Anak Kandung', 'KUDUS', '2013-01-11', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(624, '3319042504830003', '3319043005110005', 'SHOKIB', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '1', 'SHOKIB', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(625, '3319045002890005', '3319043005110005', 'SRI RAHAYU', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '3', '1', 'SHOKIB', 'Istri', 'KUDUS', '1989-10-02', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTA/sederajat'),
(626, '3319044205110001', '3319043005110005', 'ZAKKIYA ZA\'FARANI PUTRI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'SHOKIB', 'Anak Kandung', 'KUDUS', '2011-02-05', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Sedang SD/sederajat');
INSERT INTO `tb_penduduk` (`id`, `nik`, `no_kk`, `nama`, `jenis_kelamin`, `tempat_tgl_lahir`, `umur`, `agama`, `pekerjaan`, `alamat`, `rt`, `rw`, `kepala_kk`, `status_keluarga`, `tempat_lahir`, `tgl_lahir`, `status_pernikahan`, `kewarganegaraan`, `suku`, `pendidikan`) VALUES
(627, '3319046712180001', '3319043005110005', 'ADIBA SYAKILA ATMARINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '1', 'SHOKIB', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Belum masuk TK/Kelompok Bermain'),
(628, '3321096306960001', '3319040701210002', 'SITI MUSYAROFAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '1', 'SITI MUSYAROFAH', 'Kepala Keluarga', 'DEMAK', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(629, '3319044902140002', '3319040701210002', 'NOVITA ANGELINA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'SITI MUSYAROFAH', 'Anak Kandung', 'KUDUS', '2014-09-02', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(630, '3319046704170001', '3319040701210002', 'GLADIS PUTRI MANDALIFA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'SITI MUSYAROFAH', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(631, '3319044630840001', '3319040907200002', 'SIRAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'SIRAH', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', '', 'Tidak pernah sekolah'),
(632, '3319043112710001', '3319042707053571', 'SLAMET', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'SLAMET', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(633, '3319046307720002', '3319042707053571', 'RATIMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'SLAMET', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(634, '3319046604990001', '3319042707053571', 'NOVY SRI WAHYUNI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'SLAMET', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Sedang S-1/sederajat'),
(635, '3319046201080001', '3319042707053571', 'SOVIA NOOR JANAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'SLAMET', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(636, '3319042903530001', '3319042707053546', 'SUJADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '1', 'SUJADI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(637, '3319046411550001', '3319042707053546', 'TASRIPAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '1', 'SUJADI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(638, '3319040907830005', '3319042804070002', 'SUJADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '1', 'SUJADI', 'Kepala Keluarga', 'KUDUS', '1983-09-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(639, '3319045505850007', '3319042707053546', 'ZULIYANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '3', '1', 'SUJADI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(640, '3319040309040001', '3319042804070002', 'MUHAMMAD RIZQI ADITIYA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'SUJADI', 'Anak Kandung', 'KUDUS', '2004-03-09', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(641, '3319044211100001', '3319042707053546', 'NAILA JANNATUZ ZAHRA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'SUJADI', 'Anak Kandung', 'KUDUS', '2010-02-11', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Sedang SD/sederajat'),
(642, '3319042506870002', '3319041112090045', 'SUKARMAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '3', '1', 'SUKARMAN', 'Anak Kandung', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(643, '3319047001880001', '3319041112090045', 'ANIK WINDARTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '3', '1', 'SUKARMAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(644, '3319044504120002', '3319041112090045', 'RIZKA AMELYA AURISTY', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'SUKARMAN', 'Anak Kandung', 'KUDUS', '2012-05-04', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Sedang SD/sederajat'),
(645, '3319044808190003', '3319041112090045', 'ARSYILA SHOFIA AMARA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '1', 'SUKARMAN', 'Anak Kandung', 'KUDUS', '2019-08-08', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Belum masuk TK/Kelompok Bermain'),
(646, '3319041708910001', '3319042308160012', 'SUTIYONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'SUTIYONO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(647, '3319046411900004', '3319044111290044', 'SUSANA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'SULIKIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(648, '3319042307090002', '3319044111290044', 'PANDU SISWO PRANOTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'SULIKIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(649, '3319044110150002', '3319044111290044', 'ARUM DWI RAHMAWATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'SULIKIN', 'Anak Kandung', 'KUDUS', '2015-01-10', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Sedang TK/Kelompok Bermain'),
(650, '3319042902680002', '3319041112090077', 'SUMARLAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'SUMARLAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(651, '3319044712810001', '3319041112090077', 'ST BAJANG', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'SUMARLAN', 'Istri', 'KUDUS', '1981-07-12', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(652, '3319044606000001', '3319041112090077', 'LIYANA SARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'SUMARLAN', 'Anak Kandung', 'KUDUS', '2000-06-06', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTA/sederajat'),
(653, '3319040405110001', '3319041112090077', 'AHMAD REFAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'SUMARLAN', 'Anak Kandung', 'KUDUS', '2011-04-05', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(654, '3319042006400001', '3319042707053582', 'SUNARI SAWILAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '1', 'SUNARI SAWILAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(655, '3319042804900002', '3319042204130012', 'SURIAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '3', '1', 'SURIAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(656, '3319045708900001', '3319042204130012', 'NUR INAYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '3', '1', 'SURIAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTA/sederajat'),
(657, '3319041505680001', '3319042707053553', 'SURIYAT', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'SURIYAT', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(658, '3319044402730002', '3319042707053553', 'ZUMROH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'SURIYAT', 'Istri', 'KUDUS', '1973-04-02', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(659, '3319040411940001', '3319042707053553', 'AHMAD SOKHIP', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '1', 'SURIYAT', 'Anak Kandung', 'KUDUS', '1994-04-11', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(660, '3319040909990001', '3319042707053553', 'SOCHIBUL AKROM', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '1', 'SURIYAT', 'Anak Kandung', 'KUDUS', '1999-09-09', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(661, '3319042901080001', '3319042707053553', 'REZA SUBKHAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'SURIYAT', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(662, '3319041706850002', '3319040210190002', 'SURONTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '1', 'SURONTO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(663, '3319095205950001', '3319040210190002', 'NOOR HIDAYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '3', '1', 'SURONTO', 'Istri', 'KUDUS', '1995-12-05', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(664, '3319041709890004', '3319042206150003', 'SUROSO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'SUROSO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(665, '3319044203930005', '3319042206150003', 'MEI SYAKIYA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '3', '1', 'SUROSO', 'Istri', 'KUDUS', '1993-02-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(666, '3319042406150001', '3319042206150003', 'RADITIYA PRAYOGA SUROSO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'SUROSO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(667, '3319042607700001', '3319040906090006', 'SUTARO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'SUTARO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(668, '3319046510690002', '3319040906090006', 'SUTINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'SUTARO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(669, '3319040805980001', '3319040906090006', 'MUCHAMAD CHOIRUL ANWAR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'SUTARO', 'Anak Kandung', 'KUDUS', '1998-08-05', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(670, '3319043101790001', '3319042809070008', 'SUTIKNO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '1', 'SUTIKNO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(671, '3319044204870006', '3319042809070008', 'SUCI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '1', 'SUTIKNO', 'Istri', 'KUDUS', '1987-02-01', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTA/sederajat'),
(672, '3319042706070001', '3319042809070008', 'RIFQI HABIB IRFANA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '1', 'SUTIKNO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(673, '3319044309160002', '3319042809070008', 'SEPTY OLIVIA PRAMESTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '1', 'SUTIKNO', 'Anak Kandung', 'KUDUS', '2018-03-09', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(674, '3319042309190002', '3319042809070008', 'DEVANO ARKA TRIATMAJA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '1', 'SUTIKNO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(675, '3319041011690001', '3319042707053565', 'SUWANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'SUWANTO', 'Kepala Keluarga', 'KUDUS', '1969-10-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(676, '3319044203710001', '3319042707053565', 'ASROFIYATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'SUWANTO', 'Istri', 'KUDUS', '1971-02-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(677, '3319041204690006', '3319041112090017', 'SUWARNO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'SUWARNO', 'Kepala Keluarga', 'PATI', '1969-12-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(678, '3319044601770002', '3319041112090017', 'KUNTARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'SUWARNO', 'Istri', 'KUDUS', '1977-05-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(680, '3319046901150001', '3319040202160008', 'CAHAYA AYU PRABANDARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '1', 'SUWOKO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Sedang TK/Kelompok Bermain'),
(681, '3319115212990002', '3319040202160008', 'SRI UTAMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '1', 'SUWOKO', 'Istri', 'PATI', '1988-12-12', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat S-1/sederajat'),
(682, '3319042805670001', '3319042707055760', 'SUYOKO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pegawai Negeri Sipil', NULL, '3', '1', 'SUYOKO', 'Kepala Keluarga', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(683, '3319046203740001', '3319042707055760', 'ZULI ONASARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'BIDAN', NULL, '3', '1', 'SUYOKO', 'Istri', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'AKADEMIK/DIPLOMA III/SARJANA MUDA'),
(684, '3319040807960001', '3319042707055760', 'ANGGIKA ZULI PRASETYA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '1', 'SUYOKO', 'Anak Kandung', 'KUDUS', '1996-08-07', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(685, '3319041807130002', '3319042707055760', 'ABIDZAR ABDUL GHONI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '1', 'SUYOKO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(686, '3319042207640001', '3319042707053566', 'SUYOTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'SUYOTO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(687, '3319045608670001', '3319042707053566', 'MUSLIMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '1', 'SUYOTO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(688, '3319041008040001', '3319042707053566', 'SUSILO ADHI WIBOWO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'SUYOTO', 'Anak Kandung', 'KUDUS', '2004-10-08', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(689, '3315155207660001', '3319042110160017', 'TASMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '3', '1', 'TASMI', 'Kepala Keluarga', 'GROBOGAN', '1966-12-07', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(690, '3315150311980004', '3319042110160017', 'NUR KHOLIS', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '1', 'TASMI', 'Anak Kandung', 'GROBOGAN', '1998-03-11', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(691, '3315152512010004', '3319042110160017', 'NOOR HADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '1', 'TASMI', 'Anak Kandung', 'GROBOGAN', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(692, '3319046104430002', '3319042402100005', 'WAGINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '3', '1', 'WAGINAH', 'Kepala Keluarga', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tidak pernah sekolah'),
(693, '3319040602930001', '3319041608190002', 'YUSUF WIBISONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '1', 'YUSUF WIBISONO', 'Kepala Keluarga', 'KUDUS', '1993-06-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'DIPLOMA IV/STRATA I'),
(694, '3309115501959006', '3319041608190002', 'KURNIA PARAMITA PRAMESTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '3', '1', 'YUSUF WIBISONO', 'Istri', 'BOYOLALI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'DIPLOMA IV/STRATA I'),
(695, '3319042102200002', '3319041608190002', 'MUHAMMAD ILYAS ARROSYAD', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '3', '1', 'YUSUF WIBISONO', 'Anak Kandung', 'SURAKARTA', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(696, '3374032010940001', '3319040408200001', 'ZULFIKAR DIMAS SYAH PUTRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '1', 'ZUKFIKAR DIMAS SYAHPUTRA', 'Kepala Keluarga', 'SEMARANG', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(697, '3319046112940001', '3319040408200001', 'LULUK NOFIANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '3', '1', 'ZUKFIKAR DIMAS SYAHPUTRA', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTA/sederajat'),
(699, '3319040101710005', '331904160210003', 'ABDUL JALIL', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'ABDUL JALIL', 'Kepala Keluarga', 'KUDUS', '1971-01-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(700, '3319045205760002', '331904160210003', 'DARWATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'ABDUL JALIL', 'Istri', 'KUDUS', '1976-12-05', 'Kawin', 'Warga Negara Indonesia', '', 'SLTP/Sederajat'),
(701, '3319040910030002', '331904160210003', 'ANDIKA DWI CAHYA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '2', 'ABDUL JALIL', 'Anak Kandung', 'KUDUS', '2003-09-10', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(702, '3319040111120003', '331904160210003', 'MUHAMMAD THOYIBUL ARDANI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'ABDUL JALIL', 'Anak Kandung', 'KUDUS', '2012-01-11', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(703, '3319044507150006', '331904160210003', 'ANIDA HIMATU KARIMA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'ABDUL JALIL', 'Anak Kandung', 'KUDUS', '2015-05-07', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(704, '3319040404890005', '3319042609110004', 'ABDUL MUNDORI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '2', 'ABDUL MUNDORI', 'Kepala Keluarga', 'KUDUS', '1989-04-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(705, '3319046406880002', '3319042609110004', 'MARYATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '2', 'ABDUL MUNDORI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(706, '3319042207120001', '3319042609110004', 'MUHAMMAD ZACKY ALFARISI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'ABDUL MUNDORI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(707, '3319042506180003', '3319042609110004', 'GILANG AYYASY UMRAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'ABDUL MUNDORI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(708, '3319042004890004', '3319041606220002', 'ACHMAD ROMADLON', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Swasta', NULL, '1', '2', 'ACHMAD ROMADLON', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'DIPLOMA IV/STRATA I'),
(709, '3519135205940002', '3319041606220002', 'MAILINA NUR RAHAYU', 'PEREMPUAN', NULL, NULL, 'Islam', 'Karyawan Swasta', NULL, '', '', 'ACHMAD ROMADLON', 'Istri', 'MADIUN', '1994-12-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'DIPLOMA IV/STRATA I'),
(710, '3319040610770003', '3319041407110002', 'AHMAD ZAED ABDILLAH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'AHMAD ZAED ABDILLAH', 'Kepala Keluarga', 'KUDUS', '1977-06-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(711, '3321034104810004', '3319041407110002', 'RAFIATUN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'AHMAD ZAED ABDILLAH', 'Istri', 'DEMAK', '1981-01-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(712, '3319045104120002', '3319041407110002', 'KHUMAIRATUZZAHRAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'AHMAD ZAED ABDILLAH', 'Anak Kandung', 'KUDUS', '2012-11-04', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(713, '3319042910210001', '3319041407110002', 'RAZIQ MAHIR SASKARA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'AHMAD ZAED ABDILLAH', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(714, '3319041003820003', '3319042306090009', 'AJI SUNAJI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'AJI SUNAJI', 'Kepala Keluarga', 'DEMAK', '1982-10-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(715, '3319046009810005', '3319042306090009', 'SOFIATUN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Guru swasta', NULL, '1', '2', 'AJI SUNAJI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'DIPLOMA I/II'),
(716, '3319042109830002', '3319041602110002', 'ARIS SUYANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Swasta', NULL, '1', '2', 'ARIS SUYANTO', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(717, '3319046205880001', '3319041602110002', 'LILIYANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'ARIS SUYANTO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(718, '3319045705110001', '3319041602110002', 'KHEISYA ALFANI ARISYAPUTRI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'ARIS SUYANTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(719, '3319046008190002', '', 'ZHAFIRA IZZATULMUNA ARISYA PUTRI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'ARIS SUYANTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(720, '3319041301790003', '3319041601130003', 'BARIAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'BARIAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(721, '3319046706900001', '3319041601130003', 'YULIANA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '2', 'BARIAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(722, '3319048607080005', '3319041601130003', 'PUTRI MIFTAKHUL JANAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'BARIAN', 'Anak Tiri', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(723, '3319044609120001', '3319041601130003', 'AULIA FATMALASARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'BARIAN', 'Anak Kandung', 'KUDUS', '2012-06-09', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(724, '3319061111810004', '3319042202190006', 'BUSTANUL ARIFIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pegawai Negeri Sipil', NULL, '1', '2', 'BUSTANUL ARIFIN', 'Kepala Keluarga', 'KUDUS', '1981-11-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(725, '3319046101900001', '3319042202190006', 'SARAH HARIYANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pegawai Negeri Sipil', NULL, '1', '2', 'BUSTANUL ARIFIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'AKADEMI/DIPLOMA III/SARJAN MUDA'),
(726, '3319041704130002', '3319042202190006', 'FAYYADH LABIB AL MIJAHID', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'BUSTANUL ARIFIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(727, '3319044802200001', '3319042202190006', 'DIVIANTI LOVELIA ALESHA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'BUSTANUL ARIFIN', 'Anak Kandung', 'KUDUS', '2020-08-02', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(728, '3319042411920005', '3319041707190005', 'CRISTANTO PUJIANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '2', 'CRISTANTO PUJIANTO', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(729, '3319041011190001', '3319041707190005', 'MUHAMMAD EGGY AKBAR PRADANA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'CRISTANTO PUJIANTO', 'Anak Kandung', 'KUDUS', '2019-10-11', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(730, '3319041905210001', '3319041707190005', 'EGGA ARDILA MAHARDIAN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'CRISTANTO PUJIANTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(731, '3321072406900002', '3319040507180003', 'DIMAS DWI ARDYANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '2', 'DIMAS DWI ARDYANTO', 'Kepala Keluarga', 'DEMAK', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(732, '3319044410870001', '3319040507180003', 'YANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '1', '2', 'DIMAS DWI ARDYANTO', 'Istri', 'KUDUS', '1987-04-10', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(733, '3319042508180001', '3319040507180003', 'KENZIE ARYA SAPUTRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'DIMAS DWI ARDYANTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(734, '3319041009650003', '3319041112090055', 'DURIYAT', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '2', 'DURIYAT', 'Kepala Keluarga', 'GROBOGAN', '1965-10-09', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(735, '3319046511690001', '3319041112090055', 'SUMILAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '2', 'DURIYAT', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(736, '3319046612050001', '3319041112090055', 'DEVINA RAHMAWATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '1', '2', 'DURIYAT', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SLTP/Sederajat'),
(737, '3319046507620001', '3319041112090055', 'NGATIPAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '2', 'DURIYAT', 'Mertua', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(738, '3318010412910001', '3319041307150004', 'DWI CAHYONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'DWI CAHYONO', 'Kepala Keluarga', 'PATI', '1991-04-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(739, '3319046310940003', '3319041307150004', 'MUARIFAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '1', '2', 'DWI CAHYONO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(740, '3319046207150002', '3319041307150004', 'NAJWA ANINDA RESTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'DWI CAHYONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(741, '3319040802870002', '3319042309200003', 'EDY MURDIYONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Swasta', NULL, '1', '2', 'EDY MURDIYONO', 'Kepala Keluarga', 'KUDUS', '1987-08-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(742, '3321014101910003', '3319042309200003', 'JUMIRAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Karyawan Swasta', NULL, '1', '2', 'EDY MURDIYONO', 'Istri', 'DEMAK', '1991-01-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(743, '3321010310070004', '3319042309200003', 'NAENDRA MURDIYANTO ROMADHONA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '2', 'EDY MURDIYONO', 'Anak Kandung', 'DEMAK', '2007-03-10', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(744, '3321011403130003', '3319042309200003', 'PRABU BINARSO HIDAYAT', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'EDY MURDIYONO', 'Anak Kandung', 'DEMAK', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(745, '3319040408850003', '3319042809070011', 'EKO WAHYUDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'EKO WAHYUDI', 'Kepala Keluarga', 'KUDUS', '1985-04-08', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(746, '3319046810880001', '3319042809070011', 'SRI RAHAYU', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'EKO WAHYUDI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'SLTP/Sederajat'),
(747, '3319041805080002', '3319042809070011', 'HENDRA BAGUS ADI PRATAMA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '2', 'EKO WAHYUDI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum tanat SD/Sederajat'),
(748, '3319041402180001', '3319042809070011', 'ARI DWI CANDRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'EKO WAHYUDI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(749, '3319041508610002', '3319042707054627', 'HARTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Perangkat Desa', NULL, '1', '2', 'HARTO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(750, '3319046102660003', '3319042707054627', 'SUMIATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'HARTO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(751, '331904103000001', '3319042707054627', 'INDAH SARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '1', '2', 'HARTO', 'Anak Kandung', 'KUDUS', '2000-01-03', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(752, '3319042312950002', '3319041405190004', 'IMAM BAROKAH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '2', 'IMAM BAROKAH', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(753, '3315147007010001', '3319041405190004', 'HILDA PRIHATININGTIA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '1', '2', 'IMAM BAROKAH', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(754, '3319040601760001', '3319042707054660', 'JAMUJI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'JAMUJI', 'Kepala Keluarga', 'KUDUS', '1976-06-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(755, '3319046009800001', '3319042707054660', 'SHOFIATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'JAMUJI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(756, '3319042303030001', '3319042707054660', 'TRIA VERNANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'JAMUJI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(757, '3319042612120001', '3319042707054660', 'AHMAD KHABIB TRISNAWAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'JAMUJI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(758, '3319041710640001', '331904246090012', 'JARMIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'JARMIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(759, '3319045509630001', '331904246090012', 'SUWARNI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'JARMIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(760, '3319042401900003', '3319041201100026', 'JOKO PURNOMO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '2', 'JOKO PURNOMO', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(761, '3319040712090002', '3319041201100026', 'MUHAMMAD ERIC SUBASTIAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'JOKO PURNOMO', 'Anak Kandung', 'KUDUS', '2009-07-12', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(762, '3319042601900003', '3319041607130002', 'JUMADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '1', '2', 'JUMADI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(763, '3319046011920001', '3319041607130002', 'PURWATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '1', '2', 'JUMADI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(764, '3319040803140003', '3319041607130002', 'DANIS PERMANA PUTRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'JUMADI', 'Anak Kandung', 'KUDUS', '2014-08-03', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(765, '3319045205210001', '3319041607130002', 'ADEEVA AFSHIN MYESHA RAMADHANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'JUMADI', 'Anak Kandung', 'KUDUS', '2021-12-05', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(766, '3319042108610001', '3319042707054636', 'KARMAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'KARMAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(767, '3319044907670001', '3319042707054636', 'SUTARTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'KARMAN', 'Istri', 'KUDUS', '1967-09-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(768, '3319042008990001', '3319042707054636', 'SUHONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '2', 'KARMAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(769, '3319045701500001', '3319042707054637', 'KARSIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'KARSIH', 'Kepala Keluarga', 'KUDUS', '1950-07-10', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(770, '3319040312660006', '3319041112090059', 'KARSIMAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '2', 'KARSIMAN', 'Kepala Keluarga', 'KUDUS', '1966-03-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(771, '3319047003720001', '33190441112090059', 'NUR AZAIYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '1', '2', 'KARSIMAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(773, '3319041512010001', '33190441112090059', 'ZUVAN DWI BUDIHARSO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'KARSIMAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(774, '3319041005610004', '3319040411090004', 'KARSITO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'KARSITO', 'Kepala Keluarga', 'KUDUS', '1961-10-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(775, '3319046502660002', '3319040411090004', 'NGARNI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'KARSITO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(776, '3319040408670001', '3319041112090064', 'KASIBUN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'KASIBUN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(777, '3319045704770001', '3319041112090064', 'SUMIRAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'KASIBUN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(778, '3319047004030001', '3319041112090064', 'LAILATUL MARUFAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '1', '2', 'KASIBUN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(779, '3319044305050002', '3319041112090064', 'YUN MASRURAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '1', '2', 'KASIBUN', 'Anak Kandung', 'KUDUS', '2005-03-05', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(780, '3319040612120001', '3319041112090064', 'TAUFIQ HIDAYAT', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'KASIBUN', 'Anak Kandung', 'KUDUS', '2012-06-12', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(781, '3319043103810001', '3319041112090065', 'KASMIRAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '1', '2', 'KASMIRAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(782, '3319046302840003', '3319041112090065', 'SITI MUSYAROFAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '1', '2', 'KASMIRAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(783, '3319046607060003', '3319041112090065', 'NAILA AMELIA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'KASMIRAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(784, '3319041707180001', '3319041112090065', 'PUTRA ALIFIAN FIRDAUS', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'KASMIRAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(785, '3319040901410001', '3319042707054620', 'KAYATUN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'KAYATUN', 'Kepala Keluarga', 'KUDUS', '1941-09-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(786, '3319045702550001', '3319042707054620', 'KAMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'KAYATUN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(787, '3319041810560001', '3319042707054665', 'KOSIM', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'KOSIM', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(788, '3319046909630001', '3319042707054665', 'SUTAMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'KOSIM', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(789, '3319041709660002', '3319042707054647', 'KUNTORO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'KUNTORO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(790, '3319047112690147', '3319042707054647', 'TUMINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'KUNTORO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(791, '3319042504990003', '3319042707054647', 'ADITYA CAHYANING PAMUNGKAS', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '2', 'KUNTORO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(792, '3319040605020002', '3319042707054647', 'ADY PRASETYO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '2', 'KUNTORO', 'Anak Kandung', 'KUDUS', '2002-06-05', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(793, '3319040604040001', '3319042707054647', 'AFRIANA NAFRUDIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '2', 'KUNTORO', 'Anak Kandung', 'KUDUS', '2004-06-04', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(794, '3319040410660002', '3319041208080010', 'KUSMONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'KUSMONO', 'Kepala Keluarga', 'KUDUS', '1966-04-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(795, '3319045901680004', '3319041208080010', 'ASMIRAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'KUSMONO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(796, '3319041604980003', '3319041208080010', 'LUDFI SAID', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'KUSMONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(797, '3319042207790001', '3319042006090001', 'KUSWOYO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'KUSWOYO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(798, '3319045003830004', '3319042006090001', 'FIFI LUTFIANA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'KUSWOYO', 'Istri', 'BANTUL', '1983-10-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(799, '3319041208080001', '3319042006090001', 'KHOIRUL ANAM', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'KUSWOYO', 'Anak Kandung', 'KUDUS', '2008-12-08', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(800, '3319047011160002', '3319042006090001', 'HAFNA EMBUN ILMY', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '1', '2', 'KUSWOYO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum masuk TK/Kelompok Bermain'),
(801, '3319045002480002', '3319042707054628', 'MARMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'MARMI', 'Kepala Keluarga', 'KUDUS', '1948-10-02', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', '', 'Tidak pernah sekolah'),
(802, '3319041202410001', '3319042707054641', 'MARGONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '2', 'MARGONO', 'Kepala Keluarga', 'KUDUS', '1941-12-02', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(803, '3319045706470002', '3319040407170007', 'MASRIPAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'MASRIPAH', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(804, '3319041106830002', '3319042604070011', 'MAT SURYONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'MAT SURYONO', 'Kepala Keluarga', 'KUDUS', '1983-11-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(805, '3319046009830001', '3319042604070011', 'SITI MUNJAROCH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'MAT SURYONO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Sedang SLTP/Sederajat'),
(806, '3319043011050001', '3319042604070011', 'RIFKI ANDRIAN PRATAMA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'MAT SURYONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(807, '3319044511150001', '3319042604070011', 'RAISYA AQILA PUTRI SURYONO', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'MAT SURYONO', 'Anak Kandung', 'KUDUS', '2015-03-11', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Belum masuk TK/Kelompok Bermain'),
(808, '3319041111970005', '3319040107210009', 'MOH EVA GHOFINDA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '2', 'MOH EVA GHOFINDA', 'Kepala Keluarga', 'KUDUS', '1997-11-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/Sederajat'),
(809, '3319046006010001', '3319040107210009', 'ANITA KHOIRULLIYA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '2', 'MOH EVA GHOFINDA', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/Sederajat'),
(810, '3319041305210004', '3319040107210009', 'REYHAN FATHAN ALFARIZI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'MOH EVA GHOFINDA', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(811, '3319041006740001', '3319042707054657', 'MUHAMAD ABDUL AZIS', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'MUHAMAD ABDUL AZIS', 'Kepala Keluarga', 'KUDUS', '1974-10-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(812, '3319045212760001', '3319042707054657', 'RUMTARSIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'MUHAMAD ABDUL AZIS', 'Istri', 'KUDUS', '1976-12-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(813, '3319041712990001', '3319042707054657', 'AHMAD FADLUR ROHMAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '2', 'MUHAMAD ABDUL AZIS', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(814, '3319046008590001', '3319042102180002', 'MUNIRAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'MUNIRAH', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(815, '3319045111900002', '3319042111180002', 'MUNTIAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'MUNTIAH', 'Kepala Keluarga', 'KUDUS', '1990-11-11', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(816, '3319046611930002', '3319042111180002', 'ZAENAB', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'MUNTIAH', 'Adik', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(817, '3319042908960001', '3319042102180002', 'ALI MURTANDHO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '2', 'MUNTIAH', 'Adik', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(818, '3315154812680002', '3319042505160001', 'MUNZAENAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pedagang Keliling', NULL, '1', '2', 'MUNZAENAH', 'Kepala Keluarga', 'KUDUS', '1968-03-12', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(819, '3315150802900001', '3319042505160001', 'ACH KHOZIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'MUNZAENAH', 'Anak Kandung', 'GROBOGAN', '1990-08-02', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(820, '3319041804740003', '3319041112090052', 'MUSTAKIM', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'MUSTAKIM', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(821, '3319045307760002', '3319041112090052', 'SITI MARYATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'MUSTAKIM', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(822, '3319042907990002', '3319041112090052', 'KHAIRUL ANWAR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'MUSTAKIM', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(823, '3319046308070001', '3319041112090052', 'SITI AMIRUL HALIMAN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '1', '2', 'MUSTAKIM', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(824, '3319041610940001', '3319040710190008', 'NAJIB HABIBI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '2', 'NAJIB HABIBI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'DIPLOMA IV/STRATA I'),
(825, '3211136705950002', '3319040710190008', 'MARATUS SHOLIKHA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '1', '2', 'NAJIB HABIBI', 'Istri', 'INDRAMAYU', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(826, '3319041508200003', '3319040710190008', 'MUHAMMAD MAJID KAMIL HABIBI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'NAJIB HABIBI', 'Anak Kandung', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(827, '3319041701690001', '3319042707054632', 'NASIMAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'NASIMAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(828, '3319045405770001', '3319042707054632', 'SARMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'NASIMAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(829, '3319041402990001', '3319042707054632', 'ABDUL WAKHID ZUDDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar', NULL, '1', '2', 'NASIMAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SD/sederajat'),
(830, '3319045211120002', '3319042707054632', 'NURUN NAZILATIR RAHMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'NASIMAN', 'Anak Kandung', 'KUDUS', '2012-12-11', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(831, '3319040312500001', '3319042604070010', 'NASIRI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'NASIRI', 'Kepala Keluarga', 'KUDUS', '1960-03-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(832, '3319046003660004', '3319042604070010', 'SULASTRI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'NASIRI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(833, '3319045111000002', '3319042604070010', 'NOVIA ANGGRAINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '2', 'NASIRI', 'Anak Kandung', 'KUDUS', '2000-11-11', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(834, '3319041710570001', '3319042707054659', 'NGADENIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'NGADENIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat');
INSERT INTO `tb_penduduk` (`id`, `nik`, `no_kk`, `nama`, `jenis_kelamin`, `tempat_tgl_lahir`, `umur`, `agama`, `pekerjaan`, `alamat`, `rt`, `rw`, `kepala_kk`, `status_keluarga`, `tempat_lahir`, `tgl_lahir`, `status_pernikahan`, `kewarganegaraan`, `suku`, `pendidikan`) VALUES
(835, '3319045001630001', '3319042707054659', 'PASMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'NGADENIN', 'Istri', 'KUDUS', '1963-10-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(836, '3319041301810001', '3319042406090002', 'NGADINO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'NGADINO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(837, '3319045904870005', '3319042406090002', 'ROSMIATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'NGADINO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(838, '3319041606090001', '3319042406090002', 'ANDRE GUNAWAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'NGADINO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(839, '3319041501190001', '3319042406090002', 'AHMAD GIBRAN ADYATAMA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'NGADINO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(840, '3319041710520001', '3319041012090039', 'NGATIRAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'NGATIRAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(841, '3319044312540001', '3319041012090039', 'SAMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'NGATIRAN', 'Istri', 'KUDUS', '1954-03-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(842, '3319040410650001', '331904270754619', 'NOOR ALI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pegawai Negeri Sipil', NULL, '1', '2', 'NOOR ALI', 'Kepala Keluarga', 'KUDUS', '1965-04-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(843, '3319045712750002', '331904270754619', 'RUFIAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'NOOR ALI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(844, '3319040104960001', '331904270754619', 'LUQMAN HAKIM', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '2', 'NOOR ALI', 'Anak Kandung', 'KUDUS', '1996-01-04', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'DIPLOMA IV/STRATA I'),
(845, '3319041605850002', '3319041303150004', 'NURAFIF', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'NURAFIF', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(846, '3320024403900001', '3319041303150004', 'ARYANI DEWI INDRWATHY', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '1', '2', 'NURAFIF', 'Istri', 'JEPARA', '1990-04-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'DIPLOMA IV/STRATA I'),
(847, '3319041507150001', '3319041303150004', 'NUR ACHMAD ABQARI AR RAFIF', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'NURAFIF', 'Anak Kandung', 'JEPARA', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(848, '3319040409170003', '3319041303150004', 'NUR ACHMAD TSAQIB ARRAFIF', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'NURAFIF', 'Anak Kandung', 'JEPARA', '2017-04-09', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(849, '3319041007629992', '3319042206090003', 'PARLI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'PARLI', 'Kepala Keluarga', 'KUDUS', '1962-10-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(850, '3319046102670003', '3319042206090003', 'MUNIKAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'PARLI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(851, '3319044806010005', '3319042206090003', 'SITI AINUN MUFIDAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '2', 'PARLI', 'Anak Kandung', 'KUDUS', '2001-08-06', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Belum Tamat SD/Sederajat'),
(852, '3319042403720001', '331904111209005', 'QOMARI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '2', 'QOMARI', 'Kepala Keluarga', 'Lampung', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(853, '3319044301750001', '331904111209005', 'JAMI SARAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '2', 'QOMARI', 'Istri', 'KUDUS', '1975-03-01', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(854, '3319045205990003', '331904111209005', 'KUNTI KHASANUL MUNIROH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '2', 'QOMARI', 'Anak Kandung', 'KUDUS', '1999-12-05', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTA/sederajat'),
(855, '3319042401090003', '331904111209005', 'ABIMANYU ACHMAD FATICHI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '2', 'QOMARI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Belum Tamat SD/Sederajat'),
(856, '3319041807480001', '3319042707054648', 'RASMIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'RASMIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(857, '3319047006490001', '3319042707054648', 'SUKIRAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'RASMIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(858, '3319041008790004', '3319042309070014', 'ROMDON', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'ROMDON', 'Kepala Keluarga', 'KUDUS', '1979-10-08', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(859, '3319045706860005', '3319042309070014', 'MURYATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'ROMDON', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(860, '3319040110050002', '3319042309070014', 'HISYAM QUDSY SYARIFUDDIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '2', 'ROMDON', 'Anak Kandung', 'KUDUS', '2005-01-10', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(861, '3319045303120001', '3319042309070014', 'AHMAD TSANY FAIQ', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'ROMDON', 'Anak Kandung', 'KUDUS', '2012-03-03', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(862, '3319040203830001', '3319042210130007', 'RUNJIKAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'RUNJIKAN', 'Kepala Keluarga', 'KUDUS', '1983-02-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(863, '3321096212910001', '3319042210130007', 'SRI SUDARYANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'RUNJIKAN', 'Istri', 'DEMAK', '1991-12-12', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTA/sederajat'),
(864, '3319040202150001', '3319042210130007', 'FAKHRI ZHAFRAN KHOIRY', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'RUNJIKAN', 'Anak Kandung', 'KUDUS', '2016-02-02', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(865, '3319045007590002', '3319041812120002', 'RUSTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'RUSTI', 'Kepala Keluarga', 'KUDUS', '1959-10-07', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(866, '3319040509670001', '3319041112090050', 'SAMIRUN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'SAMIRUN', 'Kepala Keluarga', 'KUDUS', '1967-05-09', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(867, '3319045010760001', '3319041112090050', 'NAFIATUN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'SAMIRUN', 'Istri', 'KUDUS', '1976-10-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(868, '3319040509950001', '3319041112090050', 'AHMAD SYAFI\'I', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar', NULL, '1', '2', 'SAMIRUN', 'Anak Kandung', 'KUDUS', '1995-05-09', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(869, '3319045412000001', '3319041112090050', 'NURUL ITA ROHMANIYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '1', '2', 'SAMIRUN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(870, '3319044602780001', '3319042707054635', 'SAPUR', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'SAPUR', 'Kepala Keluarga', 'KUDUS', '1978-06-02', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(871, '3319044404020001', '3319042707054635', 'NANIK WIJAYANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '1', '2', 'SAPUR', 'Anak Kandung', 'KUDUS', '2002-04-04', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(872, '3319043001790003', '3319041112090058', 'SARJO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '2', 'SARJO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(873, '3319044402870004', '3319041112090058', 'RUMIATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'SARJO', 'Istri', 'KUDUS', '1987-04-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(874, '3319045007080001', '3319041112090058', 'FITRI BAYU AMELIA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'SARJO', 'Anak Kandung', 'KUDUS', '2008-10-07', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(875, '3319042809160002', '3319041112090058', 'FARID DAMAR SAKI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'SARJO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(876, '3319041205680001', '3319042707054662', 'SETU BIYANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'SETU BIYANTO', 'Kepala Keluarga', 'KUDUS', '1968-12-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(877, '3319045809730001', '3319042707054662', 'SUKARLIN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'SETU BIYANTO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(878, '3319046607040001', '3319042707054662', 'ZULIA DWI SANTI KASARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '2', 'SETU BIYANTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(879, '3319041408910002', '3319042308160010', 'SHODIQIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '2', 'SHODIQIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(880, '3319047112950002', '3319042308160010', 'NUR CHASANAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '1', '2', 'SHODIQIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(881, '3319046707180002', '3319042308160010', 'AZIYA QOTHRUNNADA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'SHODIQIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum masuk TK/Kelompok Bermain'),
(882, '3319045703680002', '3319042707054645', 'SIPON', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'SIPON', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(883, '3319041801730003', '3319041112090051', 'SUBIYANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'SUBIYANTO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(884, '3319045903790003', '3319041112090051', 'SARPIN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'SUBIYANTO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(885, '3319046006960003', '3319041112090051', 'HENI WIWIK RAHAYU', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '2', 'SUBIYANTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(886, '3319045109030001', '3319041112090051', 'ZUISKA PURNIASIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '2', 'SUBIYANTO', 'Anak Kandung', 'KUDUS', '2003-11-09', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(887, '3319040601920004', '3319040205170005', 'SUBKHAN YUSUF', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '1', '2', 'SUBKHAN YUSUF', 'Kepala Keluarga', 'KUDUS', '1992-06-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(888, '3302086505930004', '3319040205170005', 'PRICHATIN NINGSIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '1', '2', 'SUBKHAN YUSUF', 'Istri', 'BANYUMAS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(889, '3319041303190002', '3319040205170005', 'ABID ARKA YUSTIN PRATAMA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'SUBKHAN YUSUF', 'Anak Kandung', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(890, '3319040105830001', '3319040806090043', 'SUBUR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'SUBUR', 'Kepala Keluarga', 'KUDUS', '1983-01-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(891, '3319046806820003', '3319040806090043', 'HENY FITRIYANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'SUBUR', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(892, '3319045804100003', '3319040806090043', 'FAZA NUR RIZQIYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '1', '2', 'SUBUR', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SD/sederajat'),
(893, '3319044503140001', '3319040806090043', 'NURISMATUL IZZAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '1', '2', 'SUBUR', 'Anak Kandung', 'KUDUS', '2014-05-03', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang TK/Kelompok Bermain'),
(894, '3319041007740001', '3319042707054633', 'SUHARI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'SUHARI', 'Kepala Keluarga', 'KUDUS', '1974-10-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(895, '3319046101810006', '3319042707054633', 'SUMARNI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'SUHARI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(896, '3319042803040001', '3319042707054633', 'MUHAMAD KHOZINATUL ASROR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '2', 'SUHARI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(897, '3319040702130003', '3319042707054633', 'MUHAMMAD AUFAL MAROM', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'SUHARI', 'Anak Kandung', 'KUDUS', '2013-07-02', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(898, '3319041803770001', '3319042707054653', 'SUPAR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '2', 'SUPAR', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(899, '3319044402780005', '3319042707054653', 'SIPON NAZIROH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '1', '2', 'SUPAR', 'Istri', 'KUDUS', '1978-04-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(900, '3319041904010005', '3319042707054653', 'IRFAN AMIRRUDDIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '2', 'SUPAR', 'Anak Kandung', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(901, '3319045910070001', '3319042707054653', 'WAHYU CHUSNIATUL MUNA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '1', '2', 'SUPAR', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(902, '3319040111480001', '3319042707054653', 'SURAT', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '2', 'SUPAR', 'Orang Tua', 'KUDUS', '1948-01-11', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(903, '3319041012840002', '3319042411110001', 'SUPARIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'SUPARIN', 'Kepala Keluarga', 'KUDUS', '1984-10-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(904, '3319044408900001', '3319042411110001', 'MUSLIHATUN NIMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'SUPARIN', 'Istri', 'KUDUS', '1990-04-08', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(905, '3319043012120001', '3319042411110001', 'MUHAMMAD RIF\'AN MINAZ ZUHRI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'SUPARIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(906, '3319040611180001', '3319042411110001', 'ARFAN MIYAZ AWWAB IRAWAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'SUPARIN', 'Anak Kandung', 'KUDUS', '2018-06-11', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(907, '3319040311710003', '3319041112090060', 'SUPARMAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'SUPARMAN', 'Kepala Keluarga', 'KUDUS', '1971-03-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(908, '33219044809680001', '3319041112090060', 'DARMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '2', 'SUPARMAN', 'Istri', 'GROBOGAN', '1968-08-09', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(909, '3319040606970001', '3319041112090060', 'HERI LUKMAN ARIF', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'SUPARMAN', 'Anak Kandung', 'KUDUS', '1997-06-06', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(910, '3319046908040002', '3319041112090060', 'IKA FITRIA SARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '2', 'SUPARMAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(911, '3319041007850001', '3319040803100027', 'SUTOPO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'SUTOPO', 'Kepala Keluarga', 'KUDUS', '1985-02-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(912, '3319047005900001', '3319040803100027', 'RUBIYATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '1', '2', 'SUTOPO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(913, '331904530710001', '3319040803100027', 'NABILLA YULIANA PUTRI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '1', '2', 'SUTOPO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Sedang SD/sederajat'),
(914, '3319042610870002', '3319041109090006', 'SYAIFUL HUDA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '2', 'SYAIFUL HUDA', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(915, '3319044703390001', '3319041109090006', 'KHOIRIYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '1', '2', 'SYAIFUL HUDA', 'Istri', 'KUDUS', '1989-07-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(916, '3319043012090001', '3319041109090006', 'WALID NAZRIL ILHAM', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'SYAIFUL HUDA', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(917, '3319042205170003', '3319041109090006', 'SADAD AL WAFA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'SYAIFUL HUDA', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(918, '3319041601760003', '3319041112090054', 'TEMU', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '2', 'TEMU', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(919, '3319045002700001', '3319041112090054', 'ROFIATUN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '1', '2', 'TEMU', 'Istri', 'KUDUS', '1970-10-02', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(920, '3319042912030001', '3319041112090054', 'TEGUH EDI PRAYOGA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '2', 'TEMU', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(921, '3319045606140001', '3319041112090054', 'RIFA KAILA DWI CAHYANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'TEMU', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(922, '331904150262001', '3319042707054658', 'TUMIRAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '2', 'TUMIRAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(923, '3319046212640001', '3319042707054658', 'SABIR', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '1', '2', 'TUMIRAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(924, '3319040612740001', '3319042707054626', 'WASNO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'WASNO', 'Kepala Keluarga', 'KUDUS', '1974-06-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(925, '3319046402850001', '3319042707054626', 'NGATMINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '1', '2', 'WASNO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(926, '3319042106020001', '3319042707054626', 'ADE FIRNANDA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '1', '2', 'WASNO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(927, '3319045301170002', '3319042707054626', 'ASYIFA AYUDIA INARA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'WASNO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(928, '3319040802920001', '3319041001170003', 'ZAINUROQIB', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '1', '2', 'ZAINUROQIB', 'Kepala Keluarga', 'KUDUS', '1992-08-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'DIPLOMA IV/STRATA I'),
(929, '3319044208930004', '3319041001170003', 'SUMIAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '1', '2', 'ZAINUROQIB', 'Istri', 'KUDUS', '1993-02-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'DIPLOMA IV/STRATA I'),
(930, '3319043004170003', '3319041001170003', 'ASSYAUQIE WAFIY ARFADHIA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'ZAINUROQIB', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(931, '3319045012200001', '3319041001170003', 'SYAUQIEA LANITA KHANZA AL AZMINA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '1', '2', 'ZAINUROQIB', 'Anak Kandung', 'KUDUS', '2020-10-12', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(933, '3319042108750002', '3319041612090027', 'ALI MUSYAFAK', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'ALI MUSYAFAK', 'Kepala Keluarga', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(934, '3319045204730002', '3319041612090027', 'SITAH ARIANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'ALI MUSYAFAK', 'Istri', 'KUDUS', '1973-12-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(935, '3319045605080001', '3319041612090027', 'UFFIYATUL MAIYASAROH SYIFA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'ALI MUSYAFAK', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(936, '3319043005760002', '3319042401110001', 'AMIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'AMIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(937, '3319044804810005', '3319042401110001', 'SUWARNI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'AMIN', 'Istri', 'PATI', '1981-08-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(938, '3319040201010006', '3319042401110001', 'ERWIN HIDAYAD', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '2', 'AMIN', 'Anak Kandung', 'KUDUS', '2001-02-01', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD'),
(939, '3319044501140001', '3319042401110001', 'LOLITA FADHILAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'AMIN', 'Anak Kandung', 'KUDUS', '2014-05-01', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(940, '3319040312790001', '3319042707054666', 'BUSRI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'BUSRI', 'Kepala Keluarga', 'KUDUS', '1979-03-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(941, '3319045707810005', '3319042707054666', 'SULIKAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'BUSRI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(942, '3319046705020003', '3319042707054666', 'NURI INKA SETIANA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '2', 'BUSRI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD'),
(943, '3319043110070001', '3319042707054666', 'AGUS DWI SETIAWAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'BUSRI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(944, '3319046108180002', '3319042707054666', 'DIAN RIZKY PRAMESTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'BUSRI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(945, '3318012606810001', '3319040110140001', 'HAFIDHIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '2', 'HAFIDHIN', 'Kepala Keluarga', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(946, '3319046505910001', '3319040110140001', 'SUTIAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '2', '2', 'HAFIDHIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(947, '3319045410140001', '3319040110140001', 'GALIH ALFATIR MAULANA RASYA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'HAFIDHIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(948, '3319042703670001', '3319042797054693', 'HARSONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '2', 'HARSONO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(949, '3319040101690001', '3319042797054693', 'MARSINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '2', '2', 'HARSONO', 'Istri', 'KUDUS', '1969-01-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(950, '3319044309470001', '3319042707054687', 'LEGINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'LEGINAH', 'Kepala Keluarga', 'KUDUS', '1947-03-09', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(951, '3319040108690002', '3319041612090024', 'JARI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'JARI', 'Kepala Keluarga', 'KUDUS', '1969-01-08', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(952, '3319044204770006', '3319041612090024', 'HARNI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'JARI', 'Istri', 'KUDUS', '1977-02-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(953, '3319045112960004', '3319041612090024', 'RETNO INTAN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '2', 'JARI', 'Anak Kandung', 'KUDUS', '1996-11-12', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(954, '3319041710070004', '3319041612090024', 'BINTANG HARI PRATAMA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'JARI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(955, '3319041411750002', '3319042607053389', 'JUMANIANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '2', 'JUMANIANTO', 'Kepala Keluarga', 'DEMAK', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(956, '3319045212780001', '3319042607053389', 'ISTIMADAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '2', '2', 'JUMANIANTO', 'Istri', 'KUDUS', '1975-12-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(957, '3319042210000001', '3319042607053389', 'MUHAMMAD AGUNG KHUSNUL MANAB', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '2', 'JUMANIANTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(958, '3319046006070001', '3319042607053389', 'ANGGUN LU\'LU\'UL MAKNUN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '2', 'JUMANIANTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD'),
(959, '3319046011550001', '3319041606160004', 'KARTINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pedagang', NULL, '2', '2', 'KARTINI', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(960, '3319047112730076', '3319041311180004', 'KASINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'KASINI', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(961, '3319042405950001', '3319041311180004', 'RIKI HARIYANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '2', 'KASINI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(962, '3319044109010001', '3319041311180004', 'RENA DWI SITI ANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '2', 'KASINI', 'Anak Kandung', 'KUDUS', '2001-01-08', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(963, '3319046005510001', '3319042707054704', 'TURAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '2', 'TURAH', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(964, '3319042406400001', '3319042707054691', 'KASNAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'KASNAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(965, '3319047007430001', '3319042707054691', 'SUTINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'KASNAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(966, '3319040310860001', '3319043007100002', 'KUMRIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'KUMRIN', 'Kepala Keluarga', 'KUDUS', '1986-03-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(967, '3321086801920002', '3319043007100002', 'LAILATUL QODRIYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'KUMRIN', 'Istri', 'DEMAK', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(968, '3319042507130001', '3319043007100002', 'ARYA SHAFI NUGROHO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'KUMRIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(969, '3319044209210003', '3319043007100002', 'KAMILA LAILA MAISARA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'KUMRIN', 'Anak Kandung', 'KUDUS', '2021-02-09', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(970, '3319042912790001', '3319042707054702', 'KUSMANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '2', 'KUSMANTO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(971, '3319044301850003', '3319042707054702', 'MUZAROAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '2', '2', 'KUSMANTO', 'Istri', 'KUDUS', '1985-03-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(972, '3319047005020001', '3319042707054702', 'ELANG ERLANGGA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '2', 'KUSMANTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(973, '3319046705150001', '3319042707054702', 'SABRINA MIFTAKHUL HIDAYANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'KUSMANTO', 'Anak Kandung', 'KUDUS', '2015-07-05', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(974, '3319040201790001', '3319041005190002', 'MAFUD', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'MAFUD', 'Kepala Keluarga', 'KUDUS', '1979-02-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(975, '3319044109830001', '3319041005190002', 'SUCI ATIK', 'PEREMPUAN', NULL, NULL, 'Islam', 'Guru', NULL, '2', '2', 'MAFUD', 'Istri', 'KUDUS', '1983-02-08', 'Kawin', 'Warga Negara Indonesia', '', 'DIPLOMA IV/STRATA I'),
(976, '3319042202120004', '3319041005190002', 'MUHAMMAD NAZURA AZKA AZHAR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'MAFUD', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(977, '3319043107750001', '3319041712090026', 'MASHADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '2', 'MASHADI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(978, '3319046108850004', '3319041712090026', 'SRIYANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'MASHADI', 'Istri', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(979, '3319046904110002', '3319041712090026', 'ANGGUN APRILIA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '2', '2', 'MASHADI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SD/sederajat'),
(980, '3319091911980003', '3319042903220009', 'MUHAMMAD NOOR AZIS', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '2', 'MOHAMMAD NOOR AZIS', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(981, '3319045404010001', '3319042903220009', 'EVA NUR CAHYANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '2', '2', 'MOHAMMAD NOOR AZIS', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(982, '3319041203430001', '3319042707054676', 'NOR SOLEH TUKUL', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '2', 'NOR SOLEH TUKUL', 'Kepala Keluarga', 'KUDUS', '1943-12-03', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(983, '3319041209690002', '3319042707054692', 'NUR ROCHMAD', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'NUR ROCHMAD', 'Kepala Keluarga', 'KUDUS', '1969-12-09', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(984, '3319046911790001', '3319042707054692', 'SUNTARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'NUR ROCHMAD', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(985, '3319045001030001', '3319042707054692', 'FARIDA AYU SHEFIA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '2', '2', 'NUR ROCHMAD', 'Anak Kandung', 'KUDUS', '2003-10-01', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(986, '3319042207140002', '3319042707054692', 'MOHAMMAD HAFIZ RAMADHAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'NUR ROCHMAD', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(987, '3319041207970001', '3319041407210002', 'NURSALIM', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '2', 'NURSALIM', 'Kepala Keluarga', 'KUDUS', '1997-12-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(988, '3319065612010002', '3319041407210002', 'FITRI ANDRIYANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '2', 'NURSALIM', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(989, '3319045308210002', '3319041407210002', 'CLARISTA KIKY AGATHA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'NURSALIM', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(990, '3319045208940006', '3319040107210008', 'NUNUNG SETIANA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '2', '2', 'NUNUNG SETIANA', 'Kepala Keluarga', 'KUDUS', '1994-12-08', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(991, '3319045311150003', '3319040107210008', 'ALYA RIZKY FAIQA PUTRI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'NUNUNG SETIANA', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(992, '3319042901940001', '3319040102210001', 'OKI PUTRA SENA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '2', 'OKI PUTRA SENA', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(993, '3319045004940003', '3319040102210001', 'MUNANDIROH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '2', 'OKI PUTRA SENA', 'Istri', 'KUDUS', '1994-11-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(994, '3319041208210001', '3319040102210001', 'ELBARRAQ ABRAR PUTRASENA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'OKI PUTRA SENA', 'Anak Kandung', 'KUDUS', '2021-12-08', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(995, '3319041110690002', '3319042707054699', 'PAIMAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '2', 'PAIMAN', 'Kepala Keluarga', 'KUDUS', '1969-11-10', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(996, '3319042204010004', '3319042707054699', 'YUSRIL HENDRAWAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '2', 'PAIMAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(997, '3319042106830002', '3319041612090031', 'PURNOMO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'PURNOMO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(998, '3319045111860001', '3319041612090031', 'MUYASAROH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'PURNOMO', 'Istri', 'TUBAN', '1986-11-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(999, '3319042310040001', '3319041612090031', 'ADIAS RIZKI RAMADANU', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'PURNOMO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1000, '3319046609160001', '3319041612090031', 'AMANDA SHAFANIA MAFTHALIA IQLINA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'PURNOMO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1001, '3319041605660003', '3319042112090007', 'PURWITO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '2', 'PURWITO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1002, '3319044801690001', '3319042112090007', 'SUPRIYATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'PURWITO', 'Istri', 'KUDUS', '1969-08-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1003, '3319042606660001', '3319042707054689', 'REBO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'REBO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1004, '3319044310650001', '3319042707054689', 'SUPARMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'REBO', 'Istri', 'KUDUS', '1965-05-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1005, '3319042810880003', '3319042707054689', 'SUPRIYONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'REBO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1006, '3315151506830001', '3319040906110005', 'ROHMADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'ROHMADI', 'Kepala Keluarga', 'GROBOGAN', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1007, '3319044904850001', '3319040906110005', 'NUR CHATIMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '2', '2', 'ROHMADI', 'Istri', 'KUDUS', '1985-09-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1008, '3319041308110002', '', 'ACHMAD KEVIN RIZKY SAPUTRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'ROHMADI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1009, '3319045802170001', '', 'AULIA IZZATUNNISA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'ROHMADI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1010, '3319041711820006', '3319041902090002', 'RONI WIJAYA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '2', '2', 'RONI WIJAYA', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1011, '3319044302890007', '3319041902090002', 'YULIYEM', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '2', '2', 'RONI WIJAYA', 'Istri', 'KUDUS', '1989-03-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1012, '3319046903080001', '3319041902090002', 'PUTRI KARMILA WIJAYA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '2', '2', 'RONI WIJAYA', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SLTP/Sederajat'),
(1013, '3319044610140002', '3319041902090002', 'NAJWA AHZA ZULFA WIJAYA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '2', '2', 'RONI WIJAYA', 'Anak Kandung', 'KUDUS', '2014-06-10', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang TK/Kelompok Bermain'),
(1014, '3319041111850002', '3319041910090009', 'ROSID', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '2', '2', 'ROSID', 'Kepala Keluarga', 'KUDUS', '1985-11-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1015, '3315156910850001', '3319041910090009', 'NANIK PURWANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'ROSID', 'Istri', 'GROBOGAN', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1016, '3319040403210001', '3319041910090009', 'TIRTA WIJAYA KUSUMA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'ROSID', 'Anak Kandung', 'KUDUS', '2021-04-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1017, '3319042908660001', '3319041512090043', 'RUBAI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'RUBAI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1018, '3319046707730001', '3319041512090043', 'NUR ASIYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'RUBAI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(1019, '3319044304990002', '', 'NUR FATIMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '2', '2', 'RUBAI', 'Anak Kandung', 'KUDUS', '1999-03-04', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1020, '3319020710920001', '3319040806220003', 'RUDY IRAWAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '2', 'RUDY IRAWAN', 'Kepala Keluarga', 'KUDUS', '1992-07-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1021, '3319046908990002', '3319040806220003', 'RENI KHUSNUL HIDAYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '2', '2', 'RUDY IRAWAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1022, '3319040906700002', '3319042707054673', 'RUKAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'RUKAN', 'Kepala Keluarga', 'KUDUS', '1970-09-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1023, '3319046712730001', '3319042707054673', 'ERNI NINGSIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'RUKAN', 'Istri', 'LAMPUNG', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1024, '3319044902990002', '3319042707054673', 'SRI SUKMA NINGSIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '2', 'RUKAN', 'Anak Kandung', 'KUDUS', '1999-09-02', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1025, '3319044801050001', '3319042707054673', 'KARTIKA SARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'RUKAN', 'Anak Kandung', 'KUDUS', '2005-08-01', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1026, '3319043112830026', '3319040601140002', 'RUMANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'RUMANTO', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1027, '3319047077660001', '3319042604070004', 'SAHLI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'SAHLI', 'Kepala Keluarga', 'KUDUS', '1966-07-07', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1028, '3319041810950002', '3319042604070004', 'MUHAMMAD SYARONI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'SAHLI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1029, '3319042804690001', '3319042707054675', 'SAJIM', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'SAJIM', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1030, '3319046009720002', '3319042707054675', 'SUTARMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'SAJIM', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1031, '3319042305030002', '3319042707054675', 'FAUZAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '2', 'SAJIM', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD'),
(1032, '3319042004710001', '3319041612090022', 'SALAMUN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'SALAMUN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1033, '3319046912770001', '3319041612090022', 'NGATMINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'SALAMUN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1034, '3319045005990001', '3319041612090022', 'ELYAWATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '2', 'SALAMUN', 'Anak Kandung', 'KUDUS', '1999-10-05', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1035, '3319040908070002', '3319041612090022', 'MOHAMAD KRISTIYANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'SALAMUN', 'Anak Kandung', 'KUDUS', '2007-09-08', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1036, '3319042103670006', '3319042205090005', 'SAMIRUN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'SAMIRUN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1037, '3319045001720001', '3319042205090005', 'SUMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'SAMIRUN', 'Istri', 'KUDUS', '1972-10-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1038, '3319043112810040', '3319040812090011', 'SANTOSO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'SANTOSO', 'Kepala Keluarga', 'GROBOGAN', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1039, '3319045212820003', '3319040812090011', 'LEGI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'SANTOSO', 'Istri', 'KUDUS', '1982-12-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1040, '3319044609090002', '3319040812090011', 'ERIKA EFITA RUSANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '2', '2', 'SANTOSO', 'Anak Kandung', 'KUDUS', '2009-06-09', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1041, '3319046804210003', '3319040812090011', 'ICHA APRILIA RAMADHANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'SANTOSO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah');
INSERT INTO `tb_penduduk` (`id`, `nik`, `no_kk`, `nama`, `jenis_kelamin`, `tempat_tgl_lahir`, `umur`, `agama`, `pekerjaan`, `alamat`, `rt`, `rw`, `kepala_kk`, `status_keluarga`, `tempat_lahir`, `tgl_lahir`, `status_pernikahan`, `kewarganegaraan`, `suku`, `pendidikan`) VALUES
(1042, '3319040306870001', '3319042910130008', 'SARKAM', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'SARKAM', 'Kepala Keluarga', 'KUDUS', '1987-03-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1043, '3319055305900002', '3319042910130008', 'JUMIATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'SARKAM', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1044, '3319046811130003', '3319042910130008', 'ZAHRA ALMAIRA RAHMANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'SARKAM', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1045, '3319044311750001', '3319042707054684', 'SELAMET', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'SELAMET', 'Kepala Keluarga', 'KUDUS', '1975-03-11', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1046, '3319045206000001', '3319042707055784', 'ANALIA AGUSTIN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '4', '2', 'BAMBANG UTOMO', 'Anak Kandung', 'KUDUS', '2000-12-08', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1047, '3319042708510001', '3319042707054682', 'SENEN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'SENEN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1048, '3319044602550001', '', 'SAMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'SENEN', 'Istri', 'KUDUS', '1955-07-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1049, '3319042411800001', '', 'SULI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'SENEN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1050, '3319042303920001', '3319040110180008', 'SIROJUL MUNIR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '2', 'SIROJUL MUNIR', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1051, '3319046209950001', '3319040110180008', 'RIKA DWI SEPTIANA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '2', '2', 'SIROJUL MUNIR', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1052, '3319046607190001', '3319040110180008', 'ADEA PUTRI RINJANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'SIROJUL MUNIR', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1053, '3319047009800001', '3319041011170003', 'SLAMET', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'SLAMET', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(1054, '3319042905800001', '3319042707054670', 'SUDARMONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'SUDARMONO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1055, '3319046708840003', '3319042707054670', 'SRI MURYATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'SUDARMONO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1056, '3319041911020001', '3319042707054670', 'RIAN FIRMANSYAH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '2', 'SUDARMONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1057, '3319047003150002', '3319042707054670', 'SANI AIDA ZAFIRA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'SUDARMONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1058, '3319041609610001', '3319042707054672', 'SUDIR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'SUDIR', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1059, '3319046710640001', '3319042707054672', 'KARSITI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'SUDIR', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1060, '3319046503050003', '3319042707054672', 'ROSIDATUL ULFIA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'SUDIR', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1061, '3319042208800003', '3319040104100046', 'SUGENG RIYADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '2', '2', 'SUGENG RIYADI', 'Kepala Keluarga', 'NGAWI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1062, '3319044502860001', '3319040104100046', 'LINA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '2', '2', 'SUGENG RIYADI', 'Istri', 'KUDUS', '1986-05-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1063, '3319045206100001', '3319040104100046', 'DHEA SHEILA MEYLANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '2', 'SUGENG RIYADI', 'Anak Kandung', 'KUDUS', '2010-12-06', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD'),
(1064, '3319047006200002', '3319040104100046', 'DIVANYA PUTRI HADIBAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'SUGENG RIYADI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1065, '3319042306680001', '3319042707055759', 'SUGIARTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '2', 'SUGIARTO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1066, '3319045705810002', '3319042707055759', 'SITI ASIROH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '2', 'SUGIARTO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1067, '3319041301140001', '3319042707055759', 'AHMAD DIMAS SAHFARUDIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'SUGIARTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1068, '3319041502920001', '3319040709170004', 'SUGIHARTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '2', 'SUGIHARTO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1069, '3319044709960001', '3319040709170004', 'SITI SHOLIKAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '2', 'SUGIHARTO', 'Istri', 'KUDUS', '1996-07-09', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1070, '3319044803180002', '3319040709170004', 'ARSYILA ROMEESA FARZANA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'SUGIHARTO', 'Anak Kandung', 'KUDUS', '2018-08-03', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum masuk TK/Kelompok Bermain'),
(1071, '3319043112770066', '3319041612090030', 'SUKARNO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '2', 'SUKARNO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1072, '3319044112840003', '3319041612090030', 'TUNI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '2', 'SUKARNO', 'Istri', 'KUDUS', '1984-01-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1073, '3319044707030001', '3319041612090030', 'ERDINA ARIYANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '2', 'SUKARNO', 'Anak Kandung', 'KUDUS', '2003-07-07', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD'),
(1074, '3319041601130002', '3319041612090030', 'DEWANGGA ARJUNA PUTRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'SUKARNO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1075, '3319041205720007', '3319041612090028', 'SURAHMAT', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'SURAHMAT', 'Kepala Keluarga', 'KUDUS', '1972-12-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1076, '3319047101800001', '3319041612090028', 'ISMIYATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'SURAHMAT', 'Istri', 'MAGELANG', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1077, '3319041101040001', '3319041612090028', 'SONDANG PRATAMA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'SURAHMAT', 'Anak Kandung', 'KUDUS', '2004-11-01', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1078, '3319046803150001', '3319041612090028', 'AISYAH KIRANA ANJANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'SURAHMAT', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1079, '3319045306650002', '3319040307170001', 'SURIKAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'SURIKAH', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1080, '3319041006770001', '3319042707054656', 'SUTARO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'SUTARO', 'Kepala Keluarga', 'KUDUS', '1977-10-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1081, '3319045508790001', '3319042707054656', 'ROFI\'AH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'SUTARO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1082, '3319040104040001', '3319042707054656', 'EKA ALVIN MAULANA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '2', 'SUTARO', 'Anak Kandung', 'KUDUS', '2004-01-04', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD'),
(1083, '3319045411120001', '3319042707054656', 'DESY MUFLIKHATUS SAADAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'SUTARO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1084, '3319041705770004', '3319042809070016', 'SUTIONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'SUTIONO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1085, '3319044608840003', '3319042809070016', 'SULIN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'SUTIONO', 'Istri', 'KUDUS', '1984-05-08', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1086, '3319041812050001', '3319042809070016', 'IRGI ANDIKA PRADANA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'SUTIONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1087, '3319045812150001', '3319042809070016', 'IDA AQILA ATARINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'SUTIONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1089, '3321074501910002', '3319042308160012', 'SUGIYARTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '2', '2', 'SUTIYONO', 'Istri', 'DEMAK', '1991-05-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1090, '3319045003180001', '3319042308160012', 'NAVICA SETIYONINGSIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'SUTIYONO', 'Anak Kandung', 'KUDUS', '2018-10-03', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1091, '3319040703820006', '3319041612090026', 'SUTRIMAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'SUTRIMAN', 'Kepala Keluarga', 'KUDUS', '1982-07-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1092, '3319046801890001', '3319041612090026', 'SUTRIAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'SUTRIMAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1093, '3319044904080005', '3319041612090026', 'INDRA MANTRIA KHABIBAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'SUTRIMAN', 'Anak Kandung', 'KUDUS', '2008-09-04', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1094, '3319042606200002', '3319041612090026', 'ANANDA FADIL FIRMANSYAH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'SUTRIMAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1095, '3319040802660004', '3319041512090042', 'SUTRIMO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'SUTRIMO', 'Kepala Keluarga', 'KUDUS', '1966-08-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1096, '3319047010720003', '3319041512090042', 'SUWATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'SUTRIMO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1097, '3319042301920004', '3319041512090042', 'IKWAN ARIFIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '', '', 'SUTRIMO', 'Anak Kandung', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1098, '3319045707060004', '3319041512090042', 'NOVITA MULYANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '', '', 'SUTRIMO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1099, '3319042303750001', '3319041612090029', 'SUWAR ANWAR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'SUWAR ANWAR', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1100, '3319046008780001', '3319041612090029', 'NENGNORALIA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'SUWAR ANWAR', 'Istri', 'GARUT', NULL, 'Kawin', 'Warga Negara Indonesia', 'Sunda', 'Tamat SLTA/sederajat'),
(1101, '3319041109030002', '3319041612090029', 'KHOLIQIR ROJIKI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar', NULL, '2', '2', 'SUWAR ANWAR', 'Anak Kandung', 'KUDUS', '2003-11-09', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1102, '3319044607130001', '3319041612090029', 'HAVISAH AULIA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'SUWAR ANWAR', 'Anak Kandung', 'KUDUS', '2013-06-07', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1103, '3319042301850001', '3319042010100002', 'TUKUL SUROSO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '2', '2', 'TUKUL SUROSO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1104, '3319046607890001', '3319042010100002', 'MUTIARA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '2', '2', 'TUKUL SUROSO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1105, '3319046201110001', '3319042010100002', 'AISYA PUTRI CHIENTYA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'TUKUL SUROSO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1106, '3202041210900008', '3319042012180001', 'WANDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '2', '2', 'WANDI', 'Kepala Keluarga', 'SUKABUMI', '1990-01-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1107, '3319045207690001', '3319042012180001', 'NARTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pedagang barang kelontong', NULL, '2', '2', 'WANDI', 'Istri', 'KUDUS', '1969-12-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1108, '3319041607010001', '3319042012180001', 'AJI BAYU ANGGORO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '2', 'WANDI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD'),
(1109, '1703121010850007', '3319041702100034', 'WARTONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'WARTONO', 'Kepala Keluarga', 'FAJAR BARU', '1985-10-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1110, '3319046504820007', '3319041702100034', 'MUSYAROH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'WARTONO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1111, '3319043107090003', '3319041702100034', 'BAGUS PUTRA PRATAMA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'WARTONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1112, '3319041704190003', '3319041702100034', 'ARAFI DUWI PUTRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'WARTONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1113, '3319041007790002', '3319042707054686', 'WILUJENG', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'WILUJENG', 'Kepala Keluarga', 'KUDUS', '1979-10-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1114, '3319045806800001', '3319042707054686', 'PUJI ASTUTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Guru swasta', NULL, '2', '2', 'WILUJENG', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'DIPLOMA I/II'),
(1115, '3319046610000001', '3319042707054686', 'FADILA AYU KUMALASASIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '2', '2', 'WILUJENG', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD'),
(1116, '3319040804130002', '3319042707054686', 'PANJI ANUNG HANINDITO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'WILUJENG', 'Anak Kandung', 'KUDUS', '2013-08-04', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1117, '3319041605850004', '3319042607110006', 'YANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'YANTO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1118, '3319044609900002', '3319042607110006', 'LUBNATUL KHOIROH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '2', '2', 'YANTO', 'Istri', 'KUDUS', '1990-08-09', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1119, '3319045203120001', '3319042607110006', 'IRMA CITRA HANDAYANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'YANTO', 'Anak Kandung', 'KUDUS', '2012-12-03', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1120, '3319044902190002', '3319042607110006', 'JIHAN MUTIARA ALHUSNA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'YANTO', 'Anak Kandung', 'KUDUS', '2019-09-02', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1121, '3319041407480001', '3319042707054700', 'YATMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '2', '2', 'YATMI', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(1122, '3319040601870001', '3319042707054700', 'BUDI SANTOSO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '2', '2', 'YATMI', 'Anak Kandung', 'KUDUS', '1987-06-01', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1123, '3319040708840004', '3319041709130006', 'ZAENURI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'ZAENURI', 'Kepala Keluarga', 'KUDUS', '1984-07-08', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1124, '3319046209900006', '3319041709130006', 'KUMIDAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '2', '2', 'ZAENURI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1125, '3319044904160002', '', 'ZENATA RIVALINA PUTRI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '', '', 'ZAENURI', 'Anak Kandung', 'KUDUS', '2016-09-04', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1127, '3319045809980001', '3319042801210002', 'AGIL ARUM CITRA PRABANDARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'AGIL ARUM CITRA PRABANDARI', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1128, '3319045405160001', '3319042801210002', 'VEGA HAURA NASHIFA AYUDIA ARYANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'AGIL ARUM CITRA PRABANDARI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1129, '3319032008870007', '3319041711150002', 'AGUS ARIFIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '2', 'AGUS ARIFIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1130, '3204264109900001', '3319041711150002', 'ETIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '3', '2', 'AGUS ARIFIN', 'Istri', 'CILACAP', '1990-01-09', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1131, '3319041502160002', '3319041711150002', 'MUHAMMAD FAREL ADITYA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'AGUS ARIFIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1132, '3319040610210001', '3319041711150002', 'MUHAMMAD RAFFASYA ALFARIQ', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'AGUS ARIFIN', 'Anak Kandung', 'KUDUS', '2021-06-10', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1133, '3319043009830001', '3319041506090012', 'AHMAT KUSAERI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'AHMAT KUSAERI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1134, '3319044702900006', '3319041506090012', 'DAMAYANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'AHMAT KUSAERI', 'Istri', 'DEMAK', '1990-07-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1135, '3319044404090001', '3319041506090012', 'AVRILYA SYALUM LIBYASANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'AHMAT KUSAERI', 'Anak Kandung', 'KUDUS', '2009-04-04', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1136, '3319040708190003', '3319041506090012', 'MUHAMMAD NAUFA EZAR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'AHMAT KUSAERI', 'Anak Kandung', 'KUDUS', '2019-07-08', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1137, '3319041210600001', '3319042707055750', 'ALI ZUBAIDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Guru', NULL, '3', '2', 'ALI ZUBAIDI', 'Kepala Keluarga', 'KUDUS', '1960-12-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'DIPLOMA IV/STRATA I'),
(1138, '3319044110690002', '3319042707055750', 'KASMIYATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pedagang barang kelontong', NULL, '3', '2', 'ALI ZUBAIDI', 'Istri', 'KUDUS', '1969-01-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1139, '3319040803930001', '3319042707055750', 'FUAD KHAIRUN PASA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '2', 'ALI ZUBAIDI', 'Anak Kandung', 'KUDUS', '1993-08-03', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1140, '3319040411950001', '3319041908190002', 'ALI AKUL QOMSIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '2', 'ALI AKUL QOMSIN', 'Kepala Keluarga', 'KUDUS', '1995-04-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1141, '3319074204940001', '3319041908190002', 'MUTHIA FITRIYANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '3', '2', 'ALI AKUL QOMSIN', 'Istri', 'KUDUS', '1994-02-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1142, '3319046305190002', '3319041908190002', 'ALIYA YUNA KALILA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'ALI AKUL QOMSIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1143, '3321081107860001', '3319041911100023', 'ANJAR KUSMANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'ANJAR KUSMANTO', 'Kepala Keluarga', 'DEMAK', '1987-11-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1144, '3319045208930003', '3319041911100023', 'HARYANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'ANJAR KUSMANTO', 'Istri', 'KUDUS', '1993-12-08', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1145, '3319042610100002', '3319041911100023', 'ADHITYA PRABOWO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'ANJAR KUSMANTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1146, '3319042601140003', '3319041911100023', 'AJI BAYU PAMUNGKAS', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'ANJAR KUSMANTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1147, '3319042709830001', '3319041901110004', 'ARKANI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '3', '2', 'ARKANI', 'Kepala Keluarga', 'GROBOGAN', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1148, '3319045010890003', '3319041901110004', 'RUSMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '3', '2', 'ARKANI', 'Istri', 'KUDUS', '1989-10-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1149, '3319041503110001', '3319041901110004', 'ARVINO ZIDANI PUTRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'ARKANI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1150, '3319046610200001', '3319041901110004', 'ARRAYA WILONA MAULIDINA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'ARKANI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1151, '3319040201720001', '3319042604070005', 'BAMBANG WAHYU', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '2', 'BAMBANG WAHYU', 'Kepala Keluarga', 'KUDUS', '1972-02-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1152, '3319045002850008', '3319042604070005', 'PATIMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'BAMBANG WAHYU', 'Istri', 'DEMAK', '1985-10-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1153, '3319045905050001', '3319042604070005', 'NURULIA FARISKA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'BAMBANG WAHYU', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1154, '3319040309180002', '3319042604070005', 'MUHAMAD LUKMAN KHAFI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'BAMBANG WAHYU', 'Anak Kandung', 'KUDUS', '2018-03-09', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1155, '3319042802500001', '3319042707055713', 'DANURI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'DANURI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1156, '3319045702590001', '3319042707055713', 'YATIN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'DANURI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1157, '3319042602850003', '3319042312110001', 'EKO SAPUTRO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'EKO SAPUTRO', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1158, '3319041704900005', '3319042312090003', 'FITRIYANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'FITRIYANTO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1159, '3319044101880001', '3319042312090003', 'MASTIAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '3', '2', 'FITRIYANTO', 'Istri', 'KUDUS', '1988-01-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1160, '3319040603090001', '3319042312090003', 'DEWA RAKA YUDHISTIRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'FITRIYANTO', 'Anak Kandung', 'KUDUS', '2009-06-03', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1161, '3319040511190003', '3319042312090003', 'DANEVAN MAULUD\'FI AOZORA ZIO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'FITRIYANTO', 'Anak Kandung', 'KUDUS', '2019-05-11', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1162, '3319040708540001', '3319042707055731', 'GIRAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '2', 'GIRAN', 'Kepala Keluarga', 'GROBOGAN', '1954-07-08', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1163, '3319045212630007', '3319042707055731', 'TANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '2', 'GIRAN', 'Istri', 'BLORA', '1963-12-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1164, '3319042007870001', '3319042405190004', 'HAMAM NASIRUDIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'HAMAM NASIRUDIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'DIPLOMA IV/STRATA I'),
(1165, '3319044102990001', '3319042405190004', 'NGAINUN NAFISAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'HAMAM NASIRUDIN', 'Istri', 'KUDUS', '1999-01-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1166, '3319041011670001', '3319042707055725', 'HARTOYO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'HARTOYO', 'Kepala Keluarga', 'GROBOGAN', '1967-10-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1167, '3319045801770001', '3319042707055725', 'LASTRI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'HARTOYO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1168, '3319040903790002', '3319042809070025', 'IBNU ABAS', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'IBNU ABAS', 'Kepala Keluarga', 'KUDUS', '1979-09-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1169, '3319046109860003', '3319042809070025', 'DEWI WULANDARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'IBNU ABAS', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1170, '3319046410080002', '3319042809070025', 'NADIN CANDRAWINATA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '2', 'IBNU ABAS', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SLTP/Sederajat'),
(1171, '3319042311170001', '3319042809070025', 'DIRGA PRAMUDYA WARDANA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'IBNU ABAS', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1172, '3319041405950003', '3319042801190002', 'JONI PRANOTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '2', 'JONI PRANOTO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1173, '3319045505990001', '3319042801190002', 'WINDA NOVITA SARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '2', 'JONI PRANOTO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1174, '3319043103200001', '3319042801190002', 'MARCELLO ALFIAN PRANATA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'JONI PRANOTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1175, '3319010502930001', '3319040604160007', 'KHUMAIDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'KHUMAIDI', 'Kepala Keluarga', 'KUDUS', '1993-05-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1176, '3319045310960001', '3319040604160007', 'MUSYAROFAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '3', '2', 'KHUMAIDI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1177, '3319045007160001', '3319040604160007', 'ADHFILA SONYA RISKY', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'KHUMAIDI', 'Anak Kandung', 'KUDUS', '2016-10-07', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1178, '3319040105800002', '3319042202160004', 'KISTORO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'KISTORO', 'Kepala Keluarga', 'KUDUS', '1980-01-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1179, '3319045704840001', '3319042202160004', 'SUMIATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'KISTORO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1180, '3319045511030001', '3319042202160004', 'FAIRA ANI SHOIMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '2', 'KISTORO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(1181, '3319040202160002', '3319042202160004', 'WIDURA ATTUF MUAZZAM', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'KISTORO', 'Anak Kandung', 'KUDUS', '2016-02-02', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1182, '3319045209790005', '3319042506090004', 'KUMYANAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'KUMYANAH', 'Kepala Keluarga', 'KUDUS', '1978-12-09', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1183, '3319047106000006', '3319042506090004', 'PINKA CORNELIA TAMEVIA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '2', 'KUMYANAH', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'SLTP/Sederajat'),
(1184, '3319041508590001', '3319042707055728', 'KUSNAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'KUSNAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1185, '3319040712660001', '3319042707055734', 'MASDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '2', 'MASDI', 'Kepala Keluarga', 'KUDUS', '1966-07-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1186, '3319044204720002', '3319042707055734', 'RUSIYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'MASDI', 'Istri', 'KUDUS', '1972-02-04', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(1187, '3319046202970001', '3319042707055734', 'MIFTAHUL JANAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '2', 'MASDI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'SLTP/Sederajat'),
(1188, '3319041002580003', '3319042809070024', 'MASRUH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'MASRUH', 'Kepala Keluarga', 'GROBOGAN', '1958-10-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1189, '3319044710630004', '3319042809070024', 'YATMI RUMISIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'MASRUH', 'Istri', 'KUDUS', '1963-07-10', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(1190, '3319041609770001', '3319042707055730', 'MOH SOLECHAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'MOH SOLECHAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1191, '3319046501810001', '3319042707055730', 'ZULIATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'MOH SOLECHAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1192, '3319046209990001', '3319042707055730', 'DIAH ERNAWATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '2', 'MOH SOLECHAN', 'Anak Kandung', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1193, '3319047007100002', '3319042707055730', 'KALYCA CINDY AULYA NAFAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'MOH SOLECHAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1194, '3321081104960001', '3319041110210002', 'MOHAMAD YUSRON', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'MOHAMAD YUSRON', 'Kepala Keluarga', 'DEMAK', '1996-11-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1195, '3319044411990004', '3319041110210002', 'NUFITA SARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'MOHAMAD YUSRON', 'Istri', 'KUDUS', '1999-04-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1196, '3319044509820002', '3319041602170003', 'MUNTAMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '3', '2', 'MUNTAMAH', 'Kepala Keluarga', 'KUDUS', '1982-05-09', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(1197, '3319040209050002', '3319041602170003', 'FERY ARDIANSYAH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '2', 'MUNTAMAH', 'Anak Kandung', 'KUDUS', '2005-02-09', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(1198, '3319042208160003', '3319041602170003', 'AHMAD ZIAD', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'MUNTAMAH', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1199, '3319040309690003', '3319042707055743', 'NASIMIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'NASIMIN', 'Kepala Keluarga', 'KUDUS', '1969-03-09', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1200, '3319044708770003', '3319042707055743', 'DRIKAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'NASIMIN', 'Istri', 'KUDUS', '1977-07-08', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(1201, '3319042612950001', '3319042707055743', 'ABDUL BASID', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '2', 'NASIMIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1202, '3319042408860001', '3319040809120013', 'NGATEMAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '2', 'NGATEMAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1203, '3319046101910001', '3319040809120013', 'NURUL KOMARIYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '3', '2', 'NGATEMAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1204, '3319044905130001', '3319040809120013', 'NAIRA ASYILA AZWA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'NGATEMAN', 'Anak Kandung', 'KUDUS', '2013-09-05', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1205, '3319041007690008', '3319041412090016', 'NGATEMAN YADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '2', 'NGATEMAN YADI', 'Kepala Keluarga', 'KUDUS', '1969-10-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1206, '3319046001850001', '3319041412090016', 'KUSMINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '2', 'NGATEMAN YADI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1207, '3319048808020001', '3319041412090016', 'PUTRI INDAH PERWIRA SARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '2', 'NGATEMAN YADI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(1208, '3319046406100003', '3319041412090016', 'RISKA PUTRI RAHMADHANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'NGATEMAN YADI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1209, '3315151212910004', '3319041105150005', 'NUR SALIM', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '2', 'NOR SALIM', 'Kepala Keluarga', 'GROBOGAN', '1991-12-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1210, '3319044701970004', '3319041105150005', 'SITI NAHDLATUL ULAMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '2', 'NOR SALIM', 'Istri', 'KUDUS', '1997-07-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1211, '3319041611150003', '3319041105150005', 'NOFA ALDIANO PRATAMA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'NOR SALIM', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1212, '3319042311220001', '3319041105150005', 'JANU PRADIPTA GUMILAR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'NOR SALIM', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1213, '3319041206910005', '3319041206170005', 'NUR AZIS', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'NUR AZIS', 'Kepala Keluarga', 'KUDUS', '1991-12-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1214, '3319044907930001', '3319041206170005', 'BUDI SURYANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '3', '2', 'NUR AZIS', 'Istri', 'KUDUS', '1993-09-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1215, '3319046505170003', '3319041206170005', 'BILQIS CAHAYA LATHISA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'NUR AZIS', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1216, '3319041803210002', '3319041206170005', 'SULTAN ADAM HERMANSYAH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'NUR AZIS', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1217, '3319041203690001', '3319042707055737', 'NUR PALAL', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '2', 'NUR PAPAL', 'Kepala Keluarga', 'KUDUS', '1969-12-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1218, '3319044507740001', '3319042707055737', 'NGATIYEM', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '', '', 'NUR PAPAL', 'Istri', 'KUDUS', '1974-06-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1219, '3319041704700003', '3319042707055712', 'NURKAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '2', 'NURKAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1220, '3319046903810001', '3319042707055712', 'DARYATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'NURKAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1221, '3319041301990001', '3319042707055712', 'HARTOKO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '2', 'NURKAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1222, '3319040308060001', '3319042707055712', 'ARIF ANDIKA PUTRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'NURKAN', 'Anak Kandung', 'KUDUS', '2006-03-08', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1223, '3318185702870001', '3319041310110002', 'PUJI LESTARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'PUJI LESTARI', 'Kepala Keluarga', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1224, '3315163112730066', '3319041606150001', 'REBO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'REBO', 'Kepala Keluarga', 'GROBOGAN', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1225, '3315165169800001', '3319041606150001', 'JUMI\'AH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'REBO', 'Istri', 'KUDUS', '1980-11-09', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(1226, '3315160207030005', '3319041606150001', 'EDI ANGGORO PUTRO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '2', 'REBO', 'Anak Kandung', 'KUDUS', '2003-02-07', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1227, '3319045607160001', '3319041606150001', 'ALYA SAFITRI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'REBO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(1228, '3319042802730001', '3319040806090032', 'RIFAI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '3', '2', 'RIFAI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1229, '3319044607830007', '3319040806090032', 'SARAH JUMIIYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '3', '2', 'RIFAI', 'Istri', 'KUDUS', '1983-06-07', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(1230, '3319044511070002', '3319040806090032', 'ZALWA NOVIA WARDANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '2', 'RIFAI', 'Anak Kandung', 'KUDUS', '2007-05-11', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Belum Tamat SD/Sederajat'),
(1231, '3319044401990002', '3319040806090032', 'ANINDITA KERSHA ZAHRA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'RIFAI', 'Anak Kandung', 'KDUUS', '2019-04-01', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(1232, '3319045122630007', '3319042707055731', 'RANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '2', 'GIRAN', 'Istri', 'BLORA', '1963-12-12', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(1233, '3319042509740001', '3319044277055720', 'RUSNANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'RUSNANTO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1234, '3319044502800003', '3319044277055720', 'TUMISIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'RUSNANTO', 'Istri', 'KUDUS', '1980-05-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1235, '3319040410970001', '3319044277055720', 'RINANGKU SAPUTRO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '3', '2', 'RUSNANTO', 'Anak Kandung', 'KUDUS', '1997-04-10', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1236, '3319046110050004', '3319044277055720', 'BUNGA RATIH TEJONINGRUM', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '2', 'RUSNANTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1237, '3319042403790001', '3319042707055740', 'RUSWANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '2', 'RUSWANTO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1238, '3319046105820001', '3319042707055740', 'MARKAMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '3', '2', 'RUSWANTO', 'Istri', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(1239, '3319042110010001', '3319042707055740', 'ANDYKA MAHRUS SALAM', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '2', 'RUSWANTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1240, '3319046903140001', '3319042707055740', 'ANDINI ASILA PRAMUDIA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'RUSWANTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(1241, '3319045705730003', '3319040702230002', 'NGATMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'NGATMI', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1242, '3319040108940001', '3319040702230002', 'ZAENAL ARIFIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'NGATMI', 'Anak Kandung', 'KUDUS', '1994-01-08', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1243, '3319041106960001', '3319040702230002', 'SUPRIYONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '2', 'NGATMI', 'Anak Kandung', 'KUDUS', '1996-11-06', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1244, '3319041101500001', '3319042707055722', 'SARIMIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'SARIMIN', 'Kepala Keluarga', 'KUDUS', '1950-11-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1245, '3319045304680003', '3319042707055722', 'TURIKAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'SARIMIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1246, '3319042105690001', '3319042105690001', 'SARNO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'SARNO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1247, '3319047004600001', '3319042105690001', 'JEMINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'SARNO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat');
INSERT INTO `tb_penduduk` (`id`, `nik`, `no_kk`, `nama`, `jenis_kelamin`, `tempat_tgl_lahir`, `umur`, `agama`, `pekerjaan`, `alamat`, `rt`, `rw`, `kepala_kk`, `status_keluarga`, `tempat_lahir`, `tgl_lahir`, `status_pernikahan`, `kewarganegaraan`, `suku`, `pendidikan`) VALUES
(1248, '3319041203690002', '3319042707055748', 'SARU', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '3', '2', 'SARU', 'Kepala Keluarga', 'KUDUS', '1969-12-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1249, '3319046906750001', '3319042707055748', 'WARSINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '3', '2', 'SARU', 'Istri', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1250, '3319041605500001', '3319042707055719', 'SARU', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'SARU', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1251, '3319046406590001', '3319042707055719', 'YATMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'SARU', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1252, '3319043112750005', '3319042607058462', 'SHOLEH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '2', 'SHOLEH', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1253, '3319046708760003', '3319042607058462', 'SITI RUKAYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pembantu Rumah Tangga', NULL, '3', '2', 'SHOLEH', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1254, '3319046802980003', '3319042607058462', 'LIA FITRIANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '2', 'SHOLEH', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(1255, '3319041201580001', '3319042707055716', 'SIRUN SUKISNO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '2', 'SIRUN SUKISNO', 'Kepala Keluarga', 'KUDUS', '1958-12-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1256, '3319044508650003', '3319042707055716', 'SUKAINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '2', 'SIRUN SUKISNO', 'Istri', 'KUDUS', '1965-05-08', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1257, '3319042606870002', '3319042807200001', 'SISWO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '2', 'SISWO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1258, '3319036110910001', '3319042807200001', 'TRIYANA RAHAYU', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '2', 'SISWO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1260, '3319041410680001', '3319042707055738', 'SLAMET', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '2', 'SLAMET', 'Kepala Keluarga', 'GROBOGAN', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1261, '3319046405960001', '3319042707055738', 'RAHAYU PUJI LESTARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '3', '2', 'SLAMET', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'DIPLOMA IV/STRATA I'),
(1262, '3319044105030003', '3319042707055738', 'FIKA KUSUMA WARDANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '3', '2', 'SLAMET', 'Anak Kandung', 'KUDUS', '2003-01-05', 'Belum Kawin', 'Warga Negara Indonesia', '', 'SLTP/Sederajat'),
(1263, '3319040508590002', '3319042707055714', 'SUDAR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'SUDAR', 'Kepala Keluarga', 'KUDUS', '1959-05-08', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1264, '3319045010670001', '3319042707055714', 'RUKIYATUN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'SUDAR', 'Istri', 'PATI', '1967-10-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1265, '3319046801350001', '3319042707055714', 'SUNARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'SUDAR', 'Orang Tua', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1266, '3319044512580001', '3319042110160016', 'SUHARTINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'SUHARTINI', 'Kepala Keluarga', 'KUDUS', '1958-05-12', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1267, '3319042108920001', '3319042110160016', 'DEDIT DWI PURWANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '3', '2', 'SUHARTINI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1268, '3319030510850002', '3319041303120002', 'SUHARTONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'SUHARTONO', 'Kepala Keluarga', 'KUDUS', '1985-05-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1269, '3319034311850004', '3319041303120002', 'MUAFAROH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'SUHARTONO', 'Istri', 'KUDUS', '1985-03-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1270, '3319042011120001', '3319041303120002', 'ILYAS FA HILAL SUHARTONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'SUHARTONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1271, '3319041209170002', '3319041303120002', 'DHAFIN NAZRIL SUHARTONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'SUHARTONO', 'Anak Kandung', 'SEMARANG', '2017-12-09', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1272, '3319041908660003', '3319042809070019', 'SUJONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '2', 'SUJONO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1273, '3319045401710002', '3319042809070019', 'KUSTINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '3', '2', 'SUJONO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1274, '3319042101710005', '3319041412090020', 'SUKAMTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'SUKAMTO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1275, '3319045004810010', '3319041412090020', 'SRI SUNARTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'SUKAMTO', 'Istri', 'PATI', '1981-10-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1276, '3319042302060002', '3319041412090020', 'RIZKI AINUL YAQIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '2', 'SUKAMTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1277, '3319045708450002', '3319041412090020', 'SURIPAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'SUKAMTO', 'Ibu', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(1278, '3319044408710002', '3319040210190001', 'SUKARTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '3', '2', 'SUKARTI', 'Kepala Keluarga', 'KUDUS', '1971-04-08', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1279, '3319040101900007', '3319040210190001', 'RENDI SUSANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '3', '2', 'SUKARTI', 'Anak Kandung', 'KUDUS', '1990-01-01', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1280, '3319046708990005', '3319040210190001', 'SUCI NDIAN PERTIWI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '2', 'SUKARTI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1281, '3319040801100001', '3319040210190001', 'MUHAMMAD HASAN SYATHIR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'SUKARTI', 'Anak Kandung', 'KUDUS', '2010-08-01', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1282, '3319041307730001', '3319042707055721', 'SULKAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'SULKAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1283, '3319044112790001', '3319042707055721', 'NANING ISTANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '3', '2', 'SULKAN', 'Istri', 'GROBOGAN', '1979-01-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1284, '3319041208980001', '3319042707055721', 'AHMAD ERI SETIAWAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar', NULL, '3', '2', 'SULKAN', 'Anak Kandung', 'KUDUS', '1998-12-08', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1285, '3319045704050004', '3319042707055721', 'ADINDA DWI INDAH PUSPITA SARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'SULKAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1286, '3319041712740001', '3319042707055724', 'SUNARTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pegawai Negeri Sipil', NULL, '3', '2', 'SUNARTO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'DIPLOMA IV/STRATA I'),
(1287, '3319045404820002', '3319042707055724', 'MUNAWAROH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'SUNARTO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1288, '3319041712980001', '3319042707055724', 'SUHARMAKDUM NUGROHO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '2', 'SUNARTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1289, '3319041801060001', '3319042707055724', 'DENDRA ADI SUPROBO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '2', 'SUNARTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(1290, '3319040610160002', '3319042707055724', 'AGUNG TRI CAHYO WIDODO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'SUNARTO', 'Anak Kandung', 'KUDUS', '2016-06-10', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1291, '3316010610900001', '3319043006160001', 'SUPODO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '2', 'SUPODO', 'Kepala Keluarga', 'BLORA', '1990-06-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1292, '3319044107950013', '3319043006160001', 'KUSWATUN KASANAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '3', '2', 'SUPODO', 'Istri', 'KUDUS', '1995-01-07', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(1293, '3319045907160001', '3319043006160001', 'PRISHA INDRI ESTININGTYAS', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'SUPODO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(1294, '3319041204640007', '3319041112090040', 'SUROTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'SUROTO', 'Kepala Keluarga', 'KUDUS', '1964-12-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1295, '3319046406750001', '3319041112090040', 'WELAS', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'SUROTO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1296, '3319040301090003', '3319041112090040', 'MUKAMAT KOSIKUL KURBI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '2', 'SUROTO', 'Anak Kandung', 'KUDUS', '2009-03-01', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(1297, '3319040201850004', '3319042811110003', 'SUTRIYO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '2', 'SUTRIYO', 'Kepala Keluarga', 'KUDUS', '1985-02-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1298, '3319044610930002', '3319042811110003', 'SITI KHOIRIYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '3', '2', 'SUTRIYO', 'Istri', 'KUDUS', '1993-08-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1299, '3319041709150001', '3319042811110003', 'ABRAHAM ALEXI PRATAMA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'SUTRIYO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1300, '3319045709190002', '3319042811110003', 'AZALEA KHALIQA DZAHIM', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'SUTRIYO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1301, '3319043112690082', '3319040501110036', 'SUTOPO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '3', '2', 'SUTOPO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1302, '3319041505700009', '3319041508220005', 'WAHYUDIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '2', 'WAHYUDIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1303, '3319047112760073', '3319041508220005', 'KASMINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '2', 'WAHYUDIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1304, '3319041406170001', '3319041508220005', 'DENI TRIATMOJO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'WAHYUDIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1305, '3319041202760003', '3319040405110004', 'WALUYO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'WALUYO', 'Kepala Keluarga', 'KUDUS', '1976-12-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1306, '3319045703770003', '3319040405110004', 'MARGIATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '3', '2', 'WALUYO', 'Istri', 'TEMANGGUNG', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1307, '3319047007100003', '3319040405110004', 'YULIWATI PUTRININGSIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'WALUYO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1308, '3319044507170002', '3319040405110004', 'NATASYA YULIANA SAFITRI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'WALUYO', 'Anak Kandung', 'KUDUS', '2017-05-07', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1309, '3319042904590001', '3319042809020021', 'YONO SANJOYO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '3', '2', 'YONO SANJOYO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1310, '3319044111650002', '3319042809020021', 'MASRIPAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '3', '2', 'YONO SANJOYO', 'Istri', 'KUDUS', '1965-01-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1311, '3321071504910001', '3319042903170002', 'ZAENURI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '3', '2', 'ZAENURI', 'Kepala Keluarga', 'DEMAK', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1312, '3319045811960001', '3319042903170002', 'LILIS RUWANA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'ZAENURI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'SLTA/Sederajat'),
(1313, '3319045707170002', '3319042903170002', 'MAURA SALSABILA EKAZULI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '3', '2', 'ZAENURI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tidak/BLM Sekolah'),
(1314, '3319040708620001', '3319042707055736', 'ZUHDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '3', '2', 'ZUHDI', 'Kepala Keluarga', 'DEMAK', '1962-07-08', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1315, '3319044309750002', '3319042707055736', 'SRI RAHAYU', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '3', '2', 'ZUHDI', 'Istri', 'KUDUS', '1975-03-09', 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(1316, '3319045201970001', '3319042707055736', 'LAELATUL ROHMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '2', 'ZUHDI', 'Anak Kandung', 'KUDUS', '1997-12-01', 'Belum Kawin', 'Warga Negara Indonesia', '', 'SLTP/Sederajat'),
(1317, '3319044503010001', '3319042707055736', 'ANISAUL MUKAROMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '3', '2', 'ZUHDI', 'Anak Kandung', 'KUDUS', '2001-05-03', 'Belum Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(1319, '3318010708870003', '3319041610150001', 'ABDUL ROHIM', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '4', '2', 'ABDUL ROHIM', 'Kepala Keluarga', 'PATI', '1987-07-08', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1320, '3319046208940003', '3319041610150001', 'MASRIPAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '4', '2', 'ABDUL ROHIM', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1321, '3319041309150002', '3319041610150001', 'RIZKY ADITYA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '4', '2', 'ABDUL ROHIM', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1322, '3319041008980001', '3319042707055806', 'ANDI MOHAMAD SHOFA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '4', '2', 'KASIBAH', 'Anak Kandung', 'KUDUS', '1998-10-08', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1323, '3321084806000003', '3319043005220010', 'RINI SULISTIAWATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '', '', 'ANDI MOHAMAD SHOFA', 'Istri', 'DEMAK', '2000-08-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1324, '3319040110220002', '3319043005220010', 'MUHAMMAD ABIYAN SHAKA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '', '', 'ANDI MOHAMAD SHOFA', 'Anak Kandung', 'KUDUS', '2022-01-10', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1325, '3318013009740002', '3319040507180005', 'AHMAD NUKIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '4', '2', 'AHMAD NUKIN', 'Kepala Keluarga', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1326, '3319045901880001', '3319040507180005', 'SUTRIYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '4', '2', 'AHMAD NUKIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SD/sederajat'),
(1327, '3318014712150001', '3319040507180005', 'NITA SELVIANA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '4', '2', 'AHMAD NUKIN', 'Anak Kandung', 'PATI', '2015-07-12', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang TK/Kelompok Bermain'),
(1328, '3320082208880004', '3319040610110002', 'AHMAD ZUBAIDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Tukang Kayu', NULL, '4', '2', 'AHMAD ZUBAIDI', 'Kepala Keluarga', 'JEPARA', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1329, '3319045709900001', '3319040610110002', 'NUR KORIAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '4', '2', 'AHMAD ZUBAIDI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', '', 'Tamat SLTP/sederajat'),
(1330, '3319046202110001', '3319040610110002', 'SHELY NINDIANA SAFARA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '4', '2', 'AHMAD ZUBAIDI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SD/sederajat'),
(1331, '3319041804850001', '3319042801160008', 'AHMAT JAWAIB', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '4', '2', 'AHMAT JAWAIB', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1332, '3319046607960007', '3319042801160008', 'ZULIA ANGGRIANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '4', '2', 'AHMAT JAWAIB', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1333, '3319047006160002', '3319042801160008', 'DENIA NABIHAH RAMADHANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '4', '2', 'AHMAT JAWAIB', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum masuk TK/Kelompok Bermain'),
(1334, '3318011109930004', '3319041302180008', 'ALI MASTUKIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '4', '2', 'ALI MASTUKIN', 'Kepala Keluarga', 'PATI', '1993-11-09', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1335, '3319044101920003', '3319041302180008', 'SUTRIMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '4', '2', 'ALI MASTUKIN', 'Istri', 'KUDUS', '1992-01-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1336, '3319045311170005', '3319041302180008', 'MELY SUKMA AYU WIJAYANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '4', '2', 'ALI MASTUKIN', 'Anak Kandung', 'KUUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum masuk TK/Kelompok Bermain'),
(1337, '3319044406530001', '3319042707055776', 'SUTIMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'SUTIMAH', 'Kepala Keluarga', 'KUDUS', '1953-04-06', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(1338, '3319042809780001', '3319042707055784', 'BAMBANG UTOMO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'BAMBANG UTOMO', 'Kepala Keluarga', 'DEMAK', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1339, '3319045503720005', '3319042707055784', 'KUSNI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'BAMBANG UTOMO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1341, '3319041606110001', '3319042707055784', 'JUNAYA UTAMA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '4', '2', 'BAMBANG UTOMO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1342, '3319041208570001', '3319042707055796', 'DASNO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '4', '2', 'DASNO', 'Kepala Keluarga', 'KUDUS', '1957-12-08', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1343, '3319042006800005', '3319041712090044', 'HARNO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'HARNO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1344, '3319044410770003', '3319041712090044', 'SUNIK', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'HARNO', 'Istri', 'KUDUS', '1977-04-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1345, '3319046205010001', '3319041712090044', 'ANGGITA SOFIYA RANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '4', '2', 'HARNO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1346, '3319044910110002', '3319041712090044', 'DWI OKTAVIANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '4', '2', 'HARNO', 'Anak Kandung', 'KUDUS', '2011-09-10', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SD/sederajat'),
(1347, '3319041106900008', '3319041308180005', 'IMAM IKHWAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'IMAM IKHWAN', 'Kepala Keluarga', 'KUDUS', '1990-11-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1348, '3319034812930005', '3319041308180005', 'KIKI NOVIYANI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '4', '2', 'IMAM IKHWAN', 'Istri', 'KUDUS', '1993-08-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1349, '3319043006130001', '3319041308180005', 'ALVARO DAMAR ICHWAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '4', '2', 'IMAM IKHWAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1350, '3319040210180003', '3319041308180005', 'RAFFASYA RIZKY IKHWAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '4', '2', 'IMAM IKHWAN', 'Anak Kandung', 'KUDUS', '2018-02-10', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1351, '3319041306910001', '3319042201200004', 'JIWO LELONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '4', '2', 'JIWO LELONO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Diploma IV/STRATA I'),
(1352, '3319034710950003', '3319042201200004', 'NORFIANA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '4', '2', 'JIWO LELONO', 'Istri', 'KUDUS', '1995-07-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1353, '3319041908200002', '3319042201200004', 'RAMA KARKASA RAJENDRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '4', '2', 'JIWO LELONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1354, '3319040803840001', '3319042707055772', 'JOKO SUSILO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '4', '2', 'JOKO SUSILO', 'Kepala Keluarga', 'KUDUS', '1984-08-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1355, '3319044105830002', '3319042707055772', 'SUKARLIN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pegawai Negeri Sipil', NULL, '4', '2', 'JOKO SUSILO', 'Istri', 'KUDUS', '1983-01-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat S-1/sederajat'),
(1356, '3319040601110001', '3319042707055772', 'DAVA DIMAS ADI SUSILO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '4', '2', 'JOKO SUSILO', 'Anak Kandung', 'KUDUS', '2011-06-01', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1357, '3319040703860006', '3319042707055785', 'JOKO UMBARAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '4', '2', 'JOKO UMBARAN', 'Kepala Keluarga', 'KUDUS', '1986-07-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1358, '3319045202890004', '3319042707055785', 'HARDIANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '4', '2', 'JOKO UMBARAN', 'Istri', 'KUDUS', '1989-12-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1359, '3319045708090002', '3319042707055785', 'ADINDA ZAKIYATUL FIKRIYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '4', '2', 'JOKO UMBARAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SD/sederajat'),
(1360, '3319042904160001', '3319042707055785', 'DANORWENDA PUTRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Tidak/Belum Sekolah', NULL, '4', '2', 'JOKO UMBARAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak tamat SD/sederajat'),
(1361, '3319015904630001', '3319042901150006', 'JUMISIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '4', '2', 'JUMISIH', 'Kepala Keluarga', 'PATI', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak tamat SD/sederajat'),
(1362, '3319040705580001', '3319041906090038', 'KAMSIDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '4', '2', 'KAMSIDI', 'Kepala Keluarga', 'KUDUS', '1958-07-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1363, '3319045707680005', '3319041906090038', 'KUSTINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'KAMSIDI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1364, '3319040701640001', '3319042707055782', 'KAMTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '4', '2', 'KAMTO', 'Kepala Keluarga', 'KUDUS', '1964-07-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1366, '3319040612990001', '3319042707055782', 'DIKI NUGROHO HARYANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '4', '2', 'KAMTO', 'Anak Kandung', 'KUDUS', '1999-06-12', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(1367, '3319040506750007', '3319041612090042', 'KARONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '4', '2', 'KARSONO', 'Kepala Keluarga', 'KUDUS', '1975-05-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1368, '3319044207760001', '3319041612090042', 'DALIPAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'KARSONO', 'Istri', 'GROBOGAN', '1976-02-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1369, '3319041407120004', '3319041612090042', 'DWI MUHAMMAD HERLAMBANG', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '4', '2', 'KARSONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(1370, '3319044208750001', '3319042707055806', 'KASIBAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'KASIBAH', 'Kepala Keluarga', 'KUDUS', '1976-02-08', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1372, '3319042109620003', '3319042809070018', 'KUSNAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '4', '2', 'KUSNAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1373, '3319046703630002', '3319042809070018', 'WAGIYEM', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'KUSNAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak tamat SD/sederajat'),
(1374, '3319044207770001', '3319041612090047', 'KUSTIRAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'KUSTIRAH', 'Kepala Keluarga', 'KUDUS', '1977-02-07', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1375, '3319042410580002', '3319042707055805', 'LOSO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '4', '2', 'LOSO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1376, '3319046202570001', '3319042707055805', 'SANIPAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '4', '2', 'LOSO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(1377, '3319042003760002', '3319042707055767', 'M ABDUL MUKHLIS', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '4', '2', 'M ABDUL MUKHLIS', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1378, '3319044911720001', '3319042707055767', 'RUKANAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '4', '2', 'M ABDUL MUKHLIS', 'Istri', 'DEMAK', '1972-09-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1379, '3319044705990001', '3319042707055767', 'FITA ADIA ROHMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'M ABDUL MUKHLIS', 'Anak Kandung', 'KUDUS', '1999-07-05', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1380, '3319045301130001', '3319042707055767', 'NIKITA AULIA DWI KARTIKA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '4', '2', 'M ABDUL MUKHLIS', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SD/sederajat'),
(1381, '3319040607710001', '3319042707055774', 'MASIRAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '4', '2', 'MASIRAN', 'Kepala Keluarga', 'DEMAK', '1971-06-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1382, '3319045804800001', '3319042707055774', 'AMBAR YATIN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '4', '2', 'MASIRAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1383, '3319042702930003', '3319041301170001', 'MUHAIMIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '4', '2', 'MUHAIMIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1384, '3319045601930001', '3319041301170001', 'KHUSNUL KHOTIMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '4', '2', 'MUHAIMIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1385, '3319046107170001', '3319041301170001', 'GENDHIS ARETHA TAQQIYA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '4', '2', 'MUHAIMIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang TK/Kelompok Bermain'),
(1386, '3319042404750001', '3319042707055795', 'MUHAMAD GUNTUR', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '4', '2', 'MUHAMAD GUNTUR', 'Kepala Keluarga', 'GROBOGAN', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1387, '3319045905780001', '3319042707055795', 'SULIKAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '4', '2', 'MUHAMAD GUNTUR', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1388, '3319040504010002', '3319042707055795', 'BAGAS NUGROHO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '4', '2', 'MUHAMAD GUNTUR', 'Anak Kandung', 'KUDUS', '2001-05-04', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1389, '3319044111600001', '3319042707055795', 'AFIFAH DWI LESTARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '4', '2', 'MUHAMAD GUNTUR', 'Anak Kandung', 'KUDUS', '2016-01-11', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum masuk TK/Kelompok Bermain'),
(1390, '3321090802900005', '3319040501180010', 'MOHAMAD KHAMIK', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'MUHAMAD KHAMIK', 'Kepala Keluarga', 'DEMAK', '1990-08-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1391, '3319046603000001', '3319040501180010', 'MINA ASMITA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'MUHAMAD KHAMIK', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1392, '3319042405180001', '3319040501180010', 'AHMAD MARDHANI ARKA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '4', '2', 'MUHAMAD KHAMIK', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1393, '3319042107910001', '3319042608140006', 'MUHAMMAD ABDUL MUFID', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'MUHAMMAD ABDUL MUFID', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1394, '3319044506940002', '3319042608140006', 'DIAH PUJI ASTUTIK', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '4', '2', 'MUHAMMAD ABDUL MUFID', 'Istri', 'KUDUS', '1994-06-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1395, '3319047008140003', '3319042608140006', 'FRISKA AULIA MUFIDA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '4', '2', 'MUHAMMAD ABDUL MUFID', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang TK/Kelompok Bermain'),
(1396, '3319011509960002', '3319041703200002', 'MUHAMMAD ARIFIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '4', '2', 'MUHAMMAD ARIFIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1397, '3319046304970004', '3319041703200002', 'AMI ATUN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '4', '2', 'MUHAMMAD ARIFIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1398, '3319041105200003', '3319041703200002', 'MUHAMMAD ANDIKA SYAHPUTRA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '4', '2', 'MUHAMMAD ARIFIN', 'Anak Kandung', 'KUDUS', '2020-11-05', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1399, '3319041707770005', '3319041610080020', 'MUSMULYADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '4', '2', 'MUSMULYADI', 'Kepala Keluarga', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1400, '3319045109810001', '3319041610080020', 'KUNJANAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '4', '2', 'MUSMULYADI', 'Istri', 'KUDUS', '1981-11-09', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1401, '3319044411100001', '3319041610080020', 'SARAH AFIDATUS SALMA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '4', '2', 'MUSMULYADI', 'Anak Kandung', 'KUDUS', '2010-04-11', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SD/sederajat'),
(1402, '3319044702760002', '3319042909140005', 'MUSTIAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'MUSTIAH', 'Kepala Keluarga', 'KUDUS', '1978-07-02', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1403, '3319041603030002', '3319042909140005', 'YUDI HERMANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '4', '2', 'MUSTIAH', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum Tamat SD/Sederajat'),
(1404, '3319041109120002', '3319042909140005', 'YAYAN SEPTIAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '4', '2', 'MUSTIAH', 'Anak Kandung', 'KUDUS', '2012-11-09', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1405, '3319040408650001', '3319042707055768', 'NGADIRUN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Perangkat Desa', NULL, '4', '2', 'NGADIRUN', 'Kepala Keluarga', 'KUDUS', '1965-04-08', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1406, '3319045408700001', '3319042707055768', 'SUMINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '4', '2', 'NGADIRUN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1407, '3319042710940001', '3319042707055768', 'JOKO LELONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '4', '2', 'NGADIRUN', 'Anak Kandung', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1408, '3319042012760001', '3319042707055780', 'NGATNO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '4', '2', 'NGATNO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1409, '3319044401810004', '3319042707055780', 'SRI MARYANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'NGATNO', 'Istri', 'DEMAK', '1981-04-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1410, '3319044712010005', '3319042707055780', 'OKY RIZKIANA RAMADHINA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '4', '2', 'NGATNO', 'Anak Kandung', 'KUDUS', '2001-07-12', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1411, '3319045512130001', '3319042707055780', 'AZZA KAYLA SAFIRA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '4', '2', 'NGATNO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SD/sederajat'),
(1412, '3319042605800004', '3319042707055762', 'NUR AFANDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '4', '2', 'NUR AFANDI', 'Kepala Keluarga', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1413, '3319044602780003', '3319042707055762', 'NGATMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '4', '2', 'NUR AFANDI', 'Istri', 'KUDUS', '1978-06-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1414, '3319042807010001', '3319042707055762', 'SUFAAT', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '4', '2', 'NUR AFANDI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1415, '3319042402210001', '3319042707055762', 'AZKA ADHENTA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '4', '2', 'NUR AFANDI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1416, '3321091403920003', '3319040605190001', 'NURUSSUBAH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Tukang Batu', NULL, '4', '2', 'NURUSSUBAH', 'Kepala Keluarga', 'DEMAK', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1417, '3319045405940006', '3319040605190001', 'ISTIQOMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '4', '2', 'NURUSSUBAH', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1418, '3319040811140004', '3319040605190001', 'AHSAN MUBAROK MUQTAFA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '4', '2', 'NURUSSUBAH', 'Anak Kandung', 'KUDUS', '2014-08-11', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1419, '3319045208650003', '3319042407180005', 'PAIMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'PAIMAH', 'Kepala Keluarga', 'KUDUS', '1965-12-08', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1420, '3319045909660001', '3319042707055803', 'PAIRAH PONI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'PAIRAH PONI', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(1421, '3315171903880006', '3319040501180009', 'PURWADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '4', '2', 'PURWADI', 'Kepala Keluarga', 'GROBOGAN', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1422, '3319045412000003', '3319040501180009', 'SRI WARIYANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Mengurus Rumah Tangga', NULL, '4', '2', 'PURWADI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1423, '3319046505180001', '3319040501180009', 'MARIA MAHMUDA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '4', '2', 'PURWADI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1424, '3319045307200001', '3319040501180009', 'SITI SHOFIYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '4', '2', 'PURWADI', 'Anak Kandung', 'GROBOGAN', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1425, '3318102007890001', '3319042010150009', 'RADEN BENI DEWA BRATA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '4', '2', 'RADEN BENI DEWA BRATA', 'Kepala Keluarga', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Diploma IV/STRATA I'),
(1426, '3319045704900001', '3319042010150009', 'SIYAMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '4', '2', 'RADEN BENI DEWA BRATA', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Diploma IV/STRATA I'),
(1427, '3319045805160003', '3319042010150009', 'RR KEMUNING SENJANG AGNYBRATA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '4', '2', 'RADEN BENI DEWA BRATA', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum masuk TK/Kelompok Bermain'),
(1428, '3319040201200002', '3319042010150009', 'R ARGHANI GIANDRA AGNYBRATA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '4', '2', 'RADEN BENI DEWA BRATA', 'Anak Kandung', 'KUDUS', '2020-02-01', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1429, ' 331904200961001', '3319042707055804', 'RASID', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'RASID', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(1430, '3319044102670001', '3319042707055804', 'RUMISIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'RASID', 'Istri', 'KUDUS', '1967-01-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(1431, '3319040304850004', '3319042707055804', 'ROHMAD SARAH', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '4', '2', 'RASID', 'Anak Kandung', 'KUDUS', '1985-03-04', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1432, '3319045410010003', '3319042707055804', 'PUJI LESTARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '4', '2', 'RASID', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1433, '3318010107870138', '3319041608190003', 'RISWANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '4', '2', 'RISWANTO', 'Kepala Keluarga', 'PATI', '1987-01-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1434, '3319044911950001', '3319041608190003', 'DARWADI QOSIDAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '4', '2', 'RISWANTO', 'Istri', 'KUDUS', '1995-09-11', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1435, '3319045710110001', '3319041608190003', 'SHERIL CHIKA YUANITA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '4', '2', 'RISWANTO', 'Anak Tiri', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SD/sederajat'),
(1436, '3319044901200003', '3319041608190003', 'SAHRAZA ARISHA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '4', '2', 'RISWANTO', 'Anak Kandung', 'KUDUS', '2020-09-01', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum masuk TK/Kelompok Bermain'),
(1437, '3319040306930001', '3319042110190003', 'SARAH MIYANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'SARAH MIYANTO', 'Kepala Keluarga', 'KUDUS', '1993-03-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1438, '3318014412870002', '3319042110190003', 'SITI ZULAIHATUN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '4', '2', 'SARAH MIYANTO', 'Istri', 'PATI', '1987-04-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1439, '3319041010900002', '3319040905120044', 'SARAH ROHMAT', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'SARAH ROHMAT', 'Kepala Keluarga', 'KUDUS', '1990-10-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1440, '3318015007990007', '3319040905120044', 'SRI WAHYUNI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '4', '2', 'SARAH ROHMAT', 'Istri', 'PATI', '1999-10-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1441, '331904470110001', '3319040905120044', 'LENI WIDIASARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '4', '2', 'SARAH ROHMAT', 'Anak Kandung', 'KUDUS', '2013-07-01', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SD/sederajat'),
(1442, '3319046808180002', '3319040905120044', 'ALYA NAURA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '4', '2', 'SARAH ROHMAT', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum masuk TK/Kelompok Bermain'),
(1443, '3319041510610002', '3319041712090037', 'SIKIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '4', '2', 'SIKIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1444, '3319044212710003', '3319041712090037', 'KUSTINI KARMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'SIKIN', 'Istri', 'KUDUS', '1971-02-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1445, '3319040110930001', '3319041712090037', 'EKO MIJIANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '4', '2', 'SIKIN', 'Anak Kandung', 'KUDUS', '1993-01-10', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1446, '3319047103960002', '3319041712090037', 'WIWIK HARIYANTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'SIKIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1447, '3315182411900002', '3319042910190001', 'SULISTYO TRI WIDODO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Karyawan Perusahaan Swasta', NULL, '4', '2', 'SILISTYO TRI WIDODO', 'Kepala Keluarga', 'GROBOGAN', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1448, '3319044706990001', '3319042910190001', 'EKA WIDYASTUTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Ibu Rumah Tangga', NULL, '4', '2', 'SILISTYO TRI WIDODO', 'Istri', 'KUDUS', '1999-07-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1449, '3319042603200001', '3319042910190001', 'MUHAMMAD ADIL MAKARIM', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '4', '2', 'SILISTYO TRI WIDODO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1450, '3319040104970002', '3319040601210004', 'SOLIKIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '4', '2', 'SOLIKIN', 'Kepala Keluarga', 'KUDUS', '1997-01-04', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1451, '3319041204660003', '3319041712090047', 'SISWANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '4', '2', 'SISWANTO', 'Kepala Keluarga', 'KUDUS', '1966-12-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1452, '3319044408710004', '3319041712090047', 'MARYAM', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '4', '2', 'SISWANTO', 'Istri', 'DEMAK', '1971-04-08', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1453, '3319042008930005', '3319041712090047', 'MASRUDI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'SISWANTO', 'Anak Kandung', 'DEMAK', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1454, '3319042211740002', '3319041612090045', 'SUCIPTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'SUCIPTO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat');
INSERT INTO `tb_penduduk` (`id`, `nik`, `no_kk`, `nama`, `jenis_kelamin`, `tempat_tgl_lahir`, `umur`, `agama`, `pekerjaan`, `alamat`, `rt`, `rw`, `kepala_kk`, `status_keluarga`, `tempat_lahir`, `tgl_lahir`, `status_pernikahan`, `kewarganegaraan`, `suku`, `pendidikan`) VALUES
(1455, '3319045206760005', '3319041612090045', 'NAGTMINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'SUCIPTO', 'Istri', 'KUDUS', '1976-12-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1456, '3319043108730001', '3319042707055781', 'SUDIMAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani/Perkebun', NULL, '4', '2', 'SUDIMAN', 'Kepala Keluarga', 'PATI', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1457, '3319044806710002', '3319042707055781', 'SITI ASRIMAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '4', '2', 'SUDIMAN', 'Istri', 'KUDUS', '1971-08-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1458, '3319044907050006', '3319042707055781', 'DWI KUMALASARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '4', '2', 'SUDIMAN', 'Anak Kandung', 'KUDUS', '2005-09-07', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SLTP/Sederajat'),
(1459, '3319041604680001', '3319042707055765', 'SUHADI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'SUHADI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1460, '3319045507680003', '3319042707055765', 'HARNI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'SUHADI', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1461, '3319046410970001', '3319042707055765', 'SULISTIYAWATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '4', '2', 'SUHADI', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat S-1/sederajat'),
(1462, '3319042103630002', '3319042707055764', 'SUKARI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '4', '2', 'SUKARI', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1463, '3319045101650001', '3319042707055764', 'SURIKAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '4', '2', 'SUKARI', 'Istri', 'KUDUS', '1965-11-01', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1464, '3319041607530006', '3319041612090041', 'SUKARMIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '4', '2', 'SUKARMIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1465, '3319046908560001', '3319041612090041', 'WARSINI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '4', '2', 'SUKARMIN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(1466, '3319045009370001', '3319042308130001', 'SUKMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'SUKMI', 'Kepala Keluarga', 'KUDUS', '1937-10-09', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(1467, '3319041710590001', '3319042707055794', 'SULIKAN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Perangkat Desa', NULL, '4', '2', 'SULIKAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1468, '3319045902620001', '3319042707055794', 'KASINAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'SULIKAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1469, '3319047010010001', '3319042707055794', 'TRI WAHYUNINGSIH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '4', '2', 'SULIKAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat S-1/sederajat'),
(1470, '3319045703470001', '3319041612090035', 'SUMIJAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'SUMIJAH', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(1471, '3319042403560001', '3319042707055777', 'SUMIRAN', 'LAKI-LAKI', NULL, NULL, 'Kristen', 'Petani', NULL, '4', '2', 'SUMIRAN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(1472, '3319045608590001', '3319042707055777', 'NGATIPAH', 'PEREMPUAN', NULL, NULL, 'Kristen', 'Petani', NULL, '4', '2', 'SUMIRAN', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(1473, '3319042706850003', '3319042707055777', 'KUSMONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'SUMIRAN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1474, '3319043112690097', '3319041712090038', 'SUNOTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '4', '2', 'SUNOTO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1475, '3319046606700001', '3319041712090038', 'SULASMI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'SUNOTO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1476, '3319040607650001', '3319042707055775', 'SUPANGAT', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '4', '2', 'SUPANGAT', 'Kepala Keluarga', 'KUDUS', '1965-06-07', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1477, '3319044710690001', '3319042707055775', 'SUKITRI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'SUPANGAT', 'Istri', 'KUDUS', '1969-07-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1478, '3319040403790001', '3319041712090040', 'SUPANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'SUPANTO', 'Kepala Keluarga', 'KUDUS', '1979-04-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1479, '3319047008780001', '3319041712090040', 'RETNOWATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'SUPANTO', 'Istri', 'KEBUMEN', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1480, '3319043103030006', '3319041712090040', 'ALFIAN WIBISONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '4', '2', 'SUPANTO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1481, '3319044806170005', '3319041712090040', 'FAIZA AURELIA KINANDHITA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '4', '2', 'SUPANTO', 'Anak Kandung', 'KUDUS', '2017-08-06', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum masuk TK/Kelompok Bermain'),
(1482, '3319041802510001', '3319042707055801', 'SUPARMIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'SUPARMIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1483, '3319044106530002', '3319042707055801', 'KAMISAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'SUPARMIN', 'Istri', 'KUDUS', '1953-01-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(1484, '3319042102870001', '3319043110120004', 'SUPRIYONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '4', '2', 'SUPRIYONO', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1485, '3319046507900002', '3319043110120004', 'ZAZUK SAROH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Wiraswasta', NULL, '4', '2', 'SUPRIYONO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1486, '3319041508130002', '3319043110120004', 'MUHAMMAD FAREL NOR RIZQI', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '4', '2', 'SUPRIYONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1487, '3319044804430001', '3319041712090048', 'SUTARTI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '4', '2', 'SUTARTI', 'Kepala Keluarga', 'KUDUS', '1943-08-04', 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(1488, '3319041210820009', '3319040104100024', 'SUYANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '4', '2', 'SUYANTO', 'Kepala Keluarga', 'BLORA', '1982-12-10', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1489, '3319045002750005', '3319040104100024', 'WARSITI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'SUYANTO', 'Istri', 'KUDUS', '1975-10-02', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1490, '3319044604200003', '3319040104100024', 'ALENA JIHAN PUTRIYANI YASHFA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Belum Bekerja', NULL, '4', '2', 'SUYANTO', 'Anak Kandung', 'GROBOGAN', '2020-06-04', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Belum masuk TK/Kelompok Bermain'),
(1491, '3319040912970003', '3319041111220001', 'TRI RAHARJO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'TRI RAHARJO', 'Kepala Keluarga', 'KUDUS', '1997-09-12', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1492, '3321065006020002', '3319041111220001', 'LINA SETIYAWATI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'TRI RAHARJO', 'Istri', 'DEMAK', '2002-10-06', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1493, '3319041004770004', '3319042707055797', 'WAHONO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Petani/Perkebun', NULL, '4', '2', 'WAHONO', 'Kepala Keluarga', 'KUDUS', '1977-10-04', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTA/Sederajat'),
(1494, '3319044405800009', '3319042707055797', 'PREHATIN', 'PEREMPUAN', NULL, NULL, 'Islam', 'Petani', NULL, '4', '2', 'WAHONO', 'Istri', 'KUDUS', '1980-04-05', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1495, '3319045103010001', '3319042707055797', 'CITTA PRIHARTIKA WAHYUL JANAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar/Mahasiswa', NULL, '4', '2', 'WAHONO', 'Anak Kandung', 'KUDUS', '2001-11-03', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTA/sederajat'),
(1496, '3319042109120003', '3319042707055797', 'WILDAN KAUSYAR PRAYUDA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '4', '2', 'WAHONO', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1497, '3319045606320001', '3319042707055788', 'WARNI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Tani', NULL, '4', '2', 'WARNI', 'Kepala Keluarga', 'KUDUS', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'Tidak pernah sekolah'),
(1498, '3319041408750002', '3319042809070017', 'YATIN', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'YATIN', 'Kepala Keluarga', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SD/sederajat'),
(1499, '3319045203790003', '3319042809070017', 'MURTIAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'YATIN', 'Istri', 'PATI', '1979-12-03', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1500, '3319046504030002', '3319042809070017', 'NUZULUL HIDAYAH', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '4', '2', 'YATIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SLTA/sederajat'),
(1501, '3319046202130001', '3319042809070017', 'FEBY ZAKIYATUL HUSNA', 'PEREMPUAN', NULL, NULL, 'Islam', 'Pelajar', NULL, '4', '2', 'YATIN', 'Anak Kandung', 'KUDUS', NULL, 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Sedang SD/sederajat'),
(1502, '3319040809920003', '3319040506120012', 'ZULIYANTO', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'ZULIYANTO', 'Kepala Keluarga', 'KUDUS', '1992-08-09', 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat'),
(1503, '3319046907920005', '3319040506120012', 'TRI WULANDARI', 'PEREMPUAN', NULL, NULL, 'Islam', 'Buruh Harian Lepas', NULL, '4', '2', 'ZULIYANTO', 'Istri', 'KUDUS', NULL, 'Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tamat SLTP/sederajat'),
(1504, '3319040811130001', '3319040506120012', 'ARYA PUTRA PRATAMA', 'LAKI-LAKI', NULL, NULL, 'Islam', 'Belum/Tidak Bekerja', NULL, '4', '2', 'ZULIYANTO', 'Anak Kandung', 'KUDUS', '2013-06-11', 'Belum Kawin', 'Warga Negara Indonesia', 'Jawa', 'Tidak/BLM Sekolah'),
(1505, '3319046603920004', '3319040804200001', 'SITI ZUARIYAH', 'PEREMPUAN', NULL, NULL, 'Islam', '', NULL, '4', '2', 'SITI ZUARIYAH', 'Kepala Keluarga', 'DEMAK', NULL, 'Cerai / Janda / Duda', 'Warga Negara Indonesia', 'Jawa', 'SLTP/Sederajat');

-- --------------------------------------------------------

--
-- Table structure for table `tb_sktm_bumil`
--

CREATE TABLE `tb_sktm_bumil` (
  `id_sktm` int(11) NOT NULL,
  `nomor_surat` varchar(100) NOT NULL,
  `nama_warga` varchar(150) NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') NOT NULL DEFAULT 'Perempuan',
  `pekerjaan` varchar(100) NOT NULL,
  `agama` varchar(50) NOT NULL,
  `kewarganegaraan` varchar(50) NOT NULL DEFAULT 'Indonesia',
  `alamat_tinggal` text NOT NULL,
  `rt` varchar(10) NOT NULL,
  `rw` varchar(10) NOT NULL,
  `no_kk` varchar(20) NOT NULL,
  `no_ktp` varchar(20) NOT NULL,
  `keperluan` text NOT NULL,
  `berlaku_mulai` date NOT NULL,
  `berlaku_selesai` varchar(100) NOT NULL DEFAULT 'Selesai',
  `keterangan_lain` text NOT NULL,
  `tanggal_surat` date NOT NULL,
  `id_pejabat` int(11) NOT NULL,
  `nama_camat` varchar(150) DEFAULT '.....................',
  `foto_depan` varchar(255) DEFAULT NULL,
  `foto_ruang_tamu` varchar(255) DEFAULT NULL,
  `foto_kamar` varchar(255) DEFAULT NULL,
  `foto_dapur` varchar(255) DEFAULT NULL,
  `foto_toilet` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_sktm_kip`
--

CREATE TABLE `tb_sktm_kip` (
  `id_sktm` int(11) NOT NULL,
  `nomor_surat` varchar(100) NOT NULL,
  `nama_warga` varchar(150) NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `agama` varchar(50) NOT NULL DEFAULT 'Islam',
  `kewarganegaraan` varchar(50) NOT NULL DEFAULT 'Indonesia',
  `status_perkawinan` varchar(50) NOT NULL DEFAULT 'Belum Kawin',
  `pekerjaan` varchar(100) NOT NULL DEFAULT 'Pelajar/Mahasiswa',
  `alamat_tinggal` text NOT NULL,
  `no_ktp` varchar(16) NOT NULL,
  `no_kk` varchar(16) NOT NULL,
  `keperluan` text NOT NULL,
  `tanggal_surat` date NOT NULL,
  `id_pejabat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_sktm_kis`
--

CREATE TABLE `tb_sktm_kis` (
  `id_sktm` int(11) NOT NULL,
  `nomor_surat` varchar(100) NOT NULL,
  `nama_warga` varchar(150) NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') NOT NULL,
  `pekerjaan` varchar(100) NOT NULL,
  `agama` varchar(50) NOT NULL,
  `kewarganegaraan` varchar(50) DEFAULT 'Indonesia',
  `alamat_tinggal` text NOT NULL,
  `no_kk` varchar(16) NOT NULL,
  `no_ktp` varchar(16) NOT NULL,
  `keperluan` text NOT NULL,
  `anggota_keluarga` text NOT NULL,
  `berlaku_mulai` date NOT NULL,
  `tanggal_surat` date NOT NULL,
  `id_pejabat` int(11) NOT NULL,
  `foto_depan` varchar(255) DEFAULT NULL,
  `foto_ruang_tamu` varchar(255) DEFAULT NULL,
  `foto_kamar_tidur` varchar(255) DEFAULT NULL,
  `foto_dapur` varchar(255) DEFAULT NULL,
  `foto_kamar_mandi` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_sktm_rawat`
--

CREATE TABLE `tb_sktm_rawat` (
  `id_sktm` int(11) NOT NULL,
  `nomor_surat` varchar(100) NOT NULL,
  `nama_pemohon` varchar(150) NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') NOT NULL,
  `pekerjaan` varchar(100) NOT NULL,
  `agama` varchar(50) NOT NULL,
  `kewarganegaraan` varchar(50) DEFAULT 'Indonesia',
  `alamat_tinggal` text NOT NULL,
  `no_kk` varchar(20) NOT NULL,
  `no_ktp` varchar(20) NOT NULL,
  `rumah_sakit_tujuan` varchar(150) NOT NULL,
  `berlaku_mulai` date NOT NULL,
  `tanggal_surat` date NOT NULL,
  `id_pejabat` int(11) NOT NULL,
  `foto_depan` varchar(255) DEFAULT NULL,
  `foto_ruang_tamu` varchar(255) DEFAULT NULL,
  `foto_kamar` varchar(255) DEFAULT NULL,
  `foto_dapur` varchar(255) DEFAULT NULL,
  `foto_toilet` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_sktm_rawat_pasien`
--

CREATE TABLE `tb_sktm_rawat_pasien` (
  `id_pasien_detail` int(11) NOT NULL,
  `id_sktm` int(11) NOT NULL,
  `nama_pasien` varchar(150) NOT NULL,
  `nik_pasien` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_sktm_stunting`
--

CREATE TABLE `tb_sktm_stunting` (
  `id_sktm` int(11) NOT NULL,
  `nomor_surat` varchar(50) NOT NULL,
  `nama_warga` varchar(150) NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') NOT NULL,
  `pekerjaan` varchar(100) NOT NULL,
  `agama` varchar(50) NOT NULL DEFAULT 'Islam',
  `kewarganegaraan` varchar(50) NOT NULL DEFAULT 'Indonesia',
  `alamat_tinggal` text NOT NULL,
  `no_kk` varchar(16) NOT NULL,
  `no_ktp` varchar(16) NOT NULL,
  `keperluan` text NOT NULL,
  `nama_anak` varchar(150) NOT NULL,
  `berlaku_mulai` date NOT NULL,
  `tanggal_surat` date NOT NULL,
  `id_pejabat` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_surat_domisili`
--

CREATE TABLE `tb_surat_domisili` (
  `id_domisili` int(11) NOT NULL,
  `nomor_surat` varchar(100) NOT NULL,
  `id_pejabat` int(11) NOT NULL,
  `nama_warga` varchar(150) NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') NOT NULL,
  `agama` varchar(30) NOT NULL,
  `nik` char(16) NOT NULL,
  `alamat_jalan` varchar(255) NOT NULL,
  `rt` char(3) NOT NULL,
  `rw` char(3) NOT NULL,
  `desa` varchar(100) DEFAULT 'Berugenjang',
  `kecamatan` varchar(100) DEFAULT 'Undaan',
  `kabupaten` varchar(100) DEFAULT 'Kudus',
  `keperluan` text NOT NULL,
  `berlaku_mulai` date NOT NULL,
  `keterangan_lain` text DEFAULT NULL,
  `tanggal_surat` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_surat_dukcapil`
--

CREATE TABLE `tb_surat_dukcapil` (
  `id_surat` int(11) NOT NULL,
  `nomor_surat` varchar(100) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `jenis_dikirim` text NOT NULL,
  `banyaknya` varchar(50) NOT NULL,
  `keterangan` text NOT NULL,
  `created_by` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_surat_garapan`
--

CREATE TABLE `tb_surat_garapan` (
  `id_garapan` int(11) NOT NULL,
  `nomor_surat` varchar(100) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `nama_penggarap` varchar(150) NOT NULL,
  `bin_binti_penggarap` varchar(150) DEFAULT NULL,
  `nama_pasangan` varchar(150) DEFAULT NULL,
  `bin_binti_pasangan` varchar(150) DEFAULT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `agama` varchar(50) NOT NULL DEFAULT 'Islam',
  `pekerjaan` varchar(100) NOT NULL,
  `alamat_tinggal` text NOT NULL,
  `keperluan` text NOT NULL,
  `nama_kades` varchar(150) NOT NULL DEFAULT 'KISWO, S.E',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_surat_garapan_detail`
--

CREATE TABLE `tb_surat_garapan_detail` (
  `id_detail` int(11) NOT NULL,
  `id_garapan` int(11) NOT NULL,
  `sawah_atas_nama` varchar(150) NOT NULL,
  `terletak_di_desa` varchar(100) NOT NULL DEFAULT 'Berugenjang',
  `blok` varchar(100) NOT NULL,
  `persil` varchar(50) NOT NULL,
  `luas_m2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_surat_kelahiran`
--

CREATE TABLE `tb_surat_kelahiran` (
  `id_surat` int(11) NOT NULL,
  `nomor_surat` varchar(100) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `nama_kepala_keluarga` varchar(150) NOT NULL,
  `nomor_kk` varchar(16) NOT NULL,
  `nama_bayi` varchar(150) NOT NULL,
  `jenis_kelamin_bayi` enum('Laki-laki','Perempuan') NOT NULL,
  `tempat_dilahirkan` enum('RS/RB','Puskesmas','Polindes','Rumah','Lainnya') NOT NULL,
  `tempat_kelahiran_kab` varchar(100) NOT NULL,
  `hari_lahir_bayi` varchar(20) NOT NULL,
  `tanggal_lahir_bayi` date NOT NULL,
  `pukul_lahir_bayi` time NOT NULL,
  `jenis_kelahiran` enum('Tunggal','Kembar 2','Kembar 3','Kembar 4','Lainnya') DEFAULT 'Tunggal',
  `kelahiran_ke` int(11) NOT NULL,
  `penolong_kelahiran` enum('Dokter','Bidan/Perawat','Dukun','Lainnya') NOT NULL,
  `berat_bayi_gram` int(11) NOT NULL,
  `panjang_bayi_cm` int(11) NOT NULL,
  `nik_ibu` varchar(16) NOT NULL,
  `nama_ibu` varchar(150) NOT NULL,
  `tanggal_lahir_ibu` date NOT NULL,
  `umur_ibu` int(11) NOT NULL,
  `pekerjaan_ibu` varchar(100) DEFAULT NULL,
  `alamat_ibu` text NOT NULL,
  `desa_ibu` varchar(100) DEFAULT 'BERUGENJANG',
  `kecamatan_ibu` varchar(100) DEFAULT 'UNDAAN',
  `kabupaten_ibu` varchar(100) DEFAULT 'KUDUS',
  `provinsi_ibu` varchar(100) DEFAULT 'JAWA TENGAH',
  `kewarganegaraan_ibu` enum('WNI','WNA') DEFAULT 'WNI',
  `kebangsaan_ibu` varchar(50) DEFAULT 'INDONESIA',
  `tgl_pencatatan_perkawinan` date DEFAULT NULL,
  `nik_ayah` varchar(16) NOT NULL,
  `nama_ayah` varchar(150) NOT NULL,
  `tanggal_lahir_ayah` date NOT NULL,
  `umur_ayah` int(11) NOT NULL,
  `pekerjaan_ayah` varchar(100) DEFAULT NULL,
  `alamat_ayah` text NOT NULL,
  `desa_ayah` varchar(100) DEFAULT 'BERUGENJANG',
  `kecamatan_ayah` varchar(100) DEFAULT 'UNDAAN',
  `kabupaten_ayah` varchar(100) DEFAULT 'KUDUS',
  `provinsi_ayah` varchar(100) DEFAULT 'JAWA TENGAH',
  `kewarganegaraan_ayah` enum('WNI','WNA') DEFAULT 'WNI',
  `kebangsaan_ayah` varchar(50) DEFAULT 'INDONESIA',
  `nik_pelapor` varchar(16) NOT NULL,
  `nama_pelapor` varchar(150) NOT NULL,
  `umur_pelapor` int(11) NOT NULL,
  `jenis_kelamin_pelapor` enum('Laki-laki','Perempuan') NOT NULL,
  `pekerjaan_pelapor` varchar(100) DEFAULT NULL,
  `alamat_pelapor` text NOT NULL,
  `desa_pelapor` varchar(100) DEFAULT 'BERUGENJANG',
  `kecamatan_pelapor` varchar(100) DEFAULT 'UNDAAN',
  `kabupaten_pelapor` varchar(100) DEFAULT 'KUDUS',
  `provinsi_pelapor` varchar(100) DEFAULT 'JAWA TENGAH',
  `nik_saksi1` varchar(16) NOT NULL,
  `nama_saksi1` varchar(150) NOT NULL,
  `umur_saksi1` int(11) NOT NULL,
  `pekerjaan_saksi1` varchar(100) DEFAULT NULL,
  `alamat_saksi1` text NOT NULL,
  `desa_saksi1` varchar(100) DEFAULT 'BERUGENJANG',
  `kecamatan_saksi1` varchar(100) DEFAULT 'UNDAAN',
  `kabupaten_saksi1` varchar(100) DEFAULT 'KUDUS',
  `provinsi_saksi1` varchar(100) DEFAULT 'JAWA TENGAH',
  `nik_saksi2` varchar(16) NOT NULL,
  `nama_saksi2` varchar(150) NOT NULL,
  `umur_saksi2` int(11) NOT NULL,
  `pekerjaan_saksi2` varchar(100) DEFAULT NULL,
  `alamat_saksi2` text NOT NULL,
  `desa_saksi2` varchar(100) DEFAULT 'BERUGENJANG',
  `kecamatan_saksi2` varchar(100) DEFAULT 'UNDAAN',
  `kabupaten_saksi2` varchar(100) DEFAULT 'KUDUS',
  `provinsi_saksi2` varchar(100) DEFAULT 'JAWA TENGAH',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_surat_kematian`
--

CREATE TABLE `tb_surat_kematian` (
  `id_surat` int(11) NOT NULL,
  `nomor_surat` varchar(50) NOT NULL,
  `kode_surat` varchar(50) DEFAULT 'F-2.29',
  `nama_kepala_keluarga` varchar(100) DEFAULT NULL,
  `no_kk` varchar(20) DEFAULT NULL,
  `nik_jenazah` char(16) NOT NULL,
  `nama_jenazah` varchar(100) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `tempat_lahir_jenazah` varchar(100) NOT NULL,
  `tanggal_lahir_jenazah` date NOT NULL,
  `umur` varchar(10) DEFAULT NULL,
  `agama_jenazah` varchar(20) NOT NULL,
  `pekerjaan_jenazah` varchar(50) DEFAULT NULL,
  `alamat_jenazah` text NOT NULL,
  `desa_jenazah` varchar(50) DEFAULT NULL,
  `kecamatan_jenazah` varchar(50) DEFAULT NULL,
  `kabupaten_jenazah` varchar(50) DEFAULT NULL,
  `provinsi_jenazah` varchar(50) DEFAULT 'Jawa Tengah',
  `anak_ke` varchar(5) DEFAULT NULL,
  `hari_kematian` varchar(20) DEFAULT NULL,
  `status_perkawinan_jenazah` varchar(30) DEFAULT NULL,
  `tanggal_kematian` date NOT NULL,
  `jam_kematian` time DEFAULT NULL,
  `sebab_kematian` varchar(100) NOT NULL,
  `tempat_kematian` varchar(100) NOT NULL,
  `penolong_kematian` varchar(100) DEFAULT NULL,
  `nik_ayah` char(16) DEFAULT NULL,
  `nama_ayah` varchar(100) DEFAULT NULL,
  `tanggal_lahir_ayah` date DEFAULT NULL,
  `umur_ayah` varchar(10) DEFAULT NULL,
  `pekerjaan_ayah` varchar(50) DEFAULT NULL,
  `alamat_ayah` varchar(150) DEFAULT NULL,
  `desa_ayah` varchar(50) DEFAULT NULL,
  `kecamatan_ayah` varchar(50) DEFAULT NULL,
  `kabupaten_ayah` varchar(50) DEFAULT NULL,
  `provinsi_ayah` varchar(50) DEFAULT 'Jawa Tengah',
  `nik_ibu` char(16) DEFAULT NULL,
  `nama_ibu` varchar(100) DEFAULT NULL,
  `tanggal_lahir_ibu` date DEFAULT NULL,
  `umur_ibu` varchar(10) DEFAULT NULL,
  `pekerjaan_ibu` varchar(50) DEFAULT NULL,
  `alamat_ibu` varchar(150) DEFAULT NULL,
  `desa_ibu` varchar(50) DEFAULT NULL,
  `kecamatan_ibu` varchar(50) DEFAULT NULL,
  `kabupaten_ibu` varchar(50) DEFAULT NULL,
  `provinsi_ibu` varchar(50) DEFAULT 'Jawa Tengah',
  `nik_pelapor` char(16) NOT NULL,
  `nama_pelapor` varchar(100) NOT NULL,
  `hubungan_pelapor` varchar(50) NOT NULL,
  `tanggal_lahir_pelapor` date DEFAULT NULL,
  `umur_pelapor` varchar(10) DEFAULT NULL,
  `pekerjaan_pelapor` varchar(50) DEFAULT NULL,
  `alamat_pelapor` varchar(150) DEFAULT NULL,
  `desa_pelapor` varchar(50) DEFAULT NULL,
  `kecamatan_pelapor` varchar(50) DEFAULT NULL,
  `kabupaten_pelapor` varchar(50) DEFAULT NULL,
  `provinsi_pelapor` varchar(50) DEFAULT 'Jawa Tengah',
  `nik_saksi1` char(16) NOT NULL,
  `nama_saksi1` varchar(100) NOT NULL,
  `umur_saksi1` varchar(10) DEFAULT NULL,
  `pekerjaan_saksi1` varchar(50) DEFAULT NULL,
  `alamat_saksi1` varchar(150) DEFAULT NULL,
  `desa_saksi1` varchar(50) DEFAULT NULL,
  `kecamatan_saksi1` varchar(50) DEFAULT NULL,
  `kabupaten_saksi1` varchar(50) DEFAULT NULL,
  `provinsi_saksi1` varchar(50) DEFAULT 'Jawa Tengah',
  `nik_saksi2` char(16) NOT NULL,
  `nama_saksi2` varchar(100) NOT NULL,
  `umur_saksi2` varchar(10) DEFAULT NULL,
  `pekerjaan_saksi2` varchar(50) DEFAULT NULL,
  `alamat_saksi2` varchar(150) DEFAULT NULL,
  `desa_saksi2` varchar(50) DEFAULT NULL,
  `kecamatan_saksi2` varchar(50) DEFAULT NULL,
  `kabupaten_saksi2` varchar(50) DEFAULT NULL,
  `provinsi_saksi2` varchar(50) DEFAULT 'Jawa Tengah',
  `tanggal_surat` date NOT NULL,
  `nama_penandatangan` varchar(100) DEFAULT 'KISWO, S.E',
  `jabatan_penandatangan` varchar(100) DEFAULT 'Kepala Desa Berugenjang',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_surat_pengantar`
--

CREATE TABLE `tb_surat_pengantar` (
  `id_surat` int(11) NOT NULL,
  `nomor_surat` varchar(50) NOT NULL COMMENT 'Nomor surat di dalam stempel bulat',
  `kode_surat` varchar(20) DEFAULT '31.07.16' COMMENT 'Kode klasifikasi surat di pojok kiri atas',
  `nama_penduduk` varchar(150) NOT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `kewenangnegaraan` varchar(50) DEFAULT 'Indonesia',
  `agama` varchar(30) NOT NULL,
  `status_perkawinan` varchar(50) NOT NULL,
  `pekerjaan` varchar(100) NOT NULL,
  `alamat_tinggal` text NOT NULL COMMENT 'Detail RT, RW, Desa, Kecamatan, Kabupaten',
  `nik` varchar(16) NOT NULL,
  `nomor_kk` varchar(16) NOT NULL,
  `keperluan` text NOT NULL COMMENT 'Tujuan pembuatan surat',
  `berlaku_mulai` date NOT NULL,
  `berlaku_sampai` varchar(50) DEFAULT 'Selesai',
  `keterangan_lain` text DEFAULT NULL COMMENT 'Catatan tambahan penguat di poin 12',
  `nama_penandatanganan` varchar(150) DEFAULT NULL,
  `jabatan_penandatanganan` varchar(150) DEFAULT NULL,
  `tanggal_surat` date NOT NULL COMMENT 'Tanggal surat dikeluarkan',
  `nama_pemohon` varchar(150) NOT NULL,
  `jabatan_penandatangan` varchar(100) DEFAULT 'Kepala Desa Berugenjang',
  `nama_penandatangan` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_surat_pengantar`
--

INSERT INTO `tb_surat_pengantar` (`id_surat`, `nomor_surat`, `kode_surat`, `nama_penduduk`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `kewenangnegaraan`, `agama`, `status_perkawinan`, `pekerjaan`, `alamat_tinggal`, `nik`, `nomor_kk`, `keperluan`, `berlaku_mulai`, `berlaku_sampai`, `keterangan_lain`, `nama_penandatanganan`, `jabatan_penandatanganan`, `tanggal_surat`, `nama_pemohon`, `jabatan_penandatangan`, `nama_penandatangan`, `created_at`) VALUES
(22, '400.10.2.2/01/31.07.16/2026', '31.07.16/2026', 'SUSILO ADHI WIBOWO', 'Laki-Laki', 'KUDUS', '2004-10-08', 'Indonesia', 'Islam', 'Kawin', 'Belum/Tidak Bekerja', 'RT 003 / RW 001', '3319041008040001', '3319042707053566', 'Mayoran', '2026-08-06', 'Selesai', 'Menerangkan Bahwa Orang tersebut diatas, benar-benar penduduk Desa dan bertingkah laku baik.', 'KISWO, S.E', 'Kepala Desa', '2026-08-06', 'SUSILO ADHI WIBOWO', 'Kepala Desa Berugenjang', '', '2026-08-06 02:37:57');

-- --------------------------------------------------------

--
-- Table structure for table `tb_surat_undangan`
--

CREATE TABLE `tb_surat_undangan` (
  `id_undangan` int(11) NOT NULL,
  `nomor_surat` varchar(50) NOT NULL,
  `sifat` varchar(20) DEFAULT 'Penting',
  `lampiran` varchar(50) DEFAULT '-',
  `perihal` varchar(100) DEFAULT 'UNDANGAN',
  `tempat_surat` varchar(50) DEFAULT 'Berugenjang',
  `tanggal_surat` date NOT NULL,
  `hari_acara` varchar(20) NOT NULL,
  `tanggal_acara` date NOT NULL,
  `jam_acara` varchar(50) NOT NULL,
  `tempat_acara` varchar(255) NOT NULL,
  `acara` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `id_pejabat` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_surat_waris`
--

CREATE TABLE `tb_surat_waris` (
  `id_waris` int(11) NOT NULL,
  `nomor_surat` varchar(100) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `nama_almarhum` varchar(150) NOT NULL,
  `bin_binti` varchar(100) DEFAULT NULL,
  `tanggal_meninggal` date NOT NULL,
  `tempat_meninggal` text NOT NULL,
  `alamat_terakhir` text NOT NULL,
  `nama_pasangan` varchar(150) DEFAULT NULL,
  `status_pasangan` enum('Hidup','Alm') DEFAULT 'Hidup',
  `keperluan` text DEFAULT NULL,
  `id_pejabat` int(11) NOT NULL,
  `nama_camat` varchar(150) DEFAULT 'Camat Undaan',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_undangan_tujuan`
--

CREATE TABLE `tb_undangan_tujuan` (
  `id_tujuan` int(11) NOT NULL,
  `id_undangan` int(11) NOT NULL,
  `nama_tujuan` varchar(150) NOT NULL,
  `jabatan_tujuan` varchar(100) DEFAULT NULL,
  `nama_jabatan_tujuan` varchar(100) DEFAULT NULL,
  `alamat_tujuan` varchar(150) DEFAULT 'Tempat'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `user_id` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` char(50) DEFAULT NULL,
  `profile_picture` varchar(50) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `telepon` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`user_id`, `nama`, `email`, `password`, `role`, `profile_picture`, `alamat`, `telepon`) VALUES
(1, 'Rinangku', 'admin@gmail.com', 'admin123', 'Admin', 'avatar_1_1784622894.jpeg', 'Desa Berugenjang Kec. Undaan, Kab. Kudus', '082258422803'),
(2, 'User', 'user@gmail.com', 'user123', 'User', 'avatar_2_1783874732.jpg', 'Kudus', '08198765432');

-- --------------------------------------------------------

--
-- Table structure for table `tb_verifikasi_dokumen`
--

CREATE TABLE `tb_verifikasi_dokumen` (
  `id` int(11) NOT NULL,
  `jenis_surat` varchar(50) NOT NULL,
  `id_surat` int(11) NOT NULL,
  `nomor_surat` varchar(100) DEFAULT NULL,
  `token` varchar(64) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_verifikasi_dokumen`
--

INSERT INTO `tb_verifikasi_dokumen` (`id`, `jenis_surat`, `id_surat`, `nomor_surat`, `token`, `created_at`) VALUES
(1, 'surat_keterangan_pengantar', 3, '400.10.2.2/01', 'c65daab15e01cc9efb19869ba840ec0b', '2026-07-25 12:48:25'),
(2, 'surat_garapan', 7, '581/ 001 /31.07.16/2026', '0412e9671f7efd55b2a32afde191561f', '2026-07-25 14:37:53'),
(3, 'surat_garapan', 8, '581/ 002 /31.07.16/2026', '0eafef72b3c21886143eb78f74ccbc4c', '2026-07-25 15:14:58'),
(4, 'sktm_stunting', 4, '474 / 001 / 31.07.16 / 2026', '15f45f88449fcc0e3510605bfc6c061c', '2026-07-25 15:16:09'),
(5, 'surat_ahli_waris', 3, '001 /31.07.16/2026', 'a8ce646446431f46e6d47e8f771f76e2', '2026-07-25 22:24:10'),
(6, 'surat_undangan', 6, '005/ 001 /31.07.16/2026', '5e68acdecaa73e8d8064c367b16ae540', '2026-07-25 22:24:26'),
(7, 'surat_kelahiran', 4, '474.3/01/31.07.16/   /VII/2026', '471c4f27e55645f9a2be665d2dc88cd2', '2026-07-25 22:24:43'),
(8, 'surat_kematian', 4, '145/31.071.6/474.3/1/F-2.29/2026', '9e2b2df15ff2942c2f924f05f47db13b', '2026-07-25 22:24:54'),
(9, 'sktm_bumil', 5, '090 / 31. 07.16 / 2026', '639e6fdce382e02dc2cf9d6cd5b5e914', '2026-07-25 22:25:14'),
(10, 'sktm_kis', 1, '31.07.16 / 2026', '8fd36dcd2463fa6175bfb45f83c17609', '2026-07-25 22:35:00'),
(11, 'surat_domisili', 9, '474/1/31.07.16/2026', '1f7f59364657f4aaafaeddcfc84bab90', '2026-07-28 14:43:38'),
(12, 'surat_pengantar_dukcapil', 6, '090 / 31. 07.16 / 2026', '19ababb4444c35ff380f39fd40cdf86c', '2026-07-28 14:46:56'),
(13, 'sktm_rawat', 2, '474 / 05 / 31.07.16 / 2026', '57963bfead84a7f7056d1653128c0cc4', '2026-07-28 14:51:45'),
(14, 'sktm_kip', 4, '474 / 001 / 31.07.16 / 2026', '41f3774b0e3d6849f425a859087ad16f', '2026-07-28 14:56:16'),
(15, 'surat_keterangan_pengantar', 5, '335', '58fb137ed72d3d26388400e4487a8f20', '2026-07-29 10:23:23'),
(16, 'surat_keterangan_pengantar', 6, '400.10.2.2/01/31.07.16/2026', '50ce089a5292cf6f58179f44af45f798', '2026-07-29 10:45:27'),
(17, 'surat_keterangan_pengantar', 10, '400.10.2.2/02/31.07.16/2026', '25ded31a65c67f0918b07eb3155c8cae', '2026-07-31 05:58:44'),
(18, 'surat_ahli_waris', 4, '400.10.2.2/03/31.07.16/2026', '5a5c66d7cffb2aeeb00b7d4634a818f2', '2026-07-31 21:20:53'),
(19, 'sktm_rawat', 4, '400.10.2.2/08/31.07.16/2026', '01a259cc9b20e7dbcdf381924ebf3ccc', '2026-07-31 22:45:34'),
(20, 'sktm_bumil', 6, '400.10.2.2/01/31.07.16/2026', 'a9862bd09747792313e54a4c7f3aae76', '2026-07-31 22:47:00'),
(21, 'surat_kelahiran', 5, '400.10.2.2/10/31.07.16/2026', 'bed61e6d3f78fef9a4fd4f339f41ee8b', '2026-08-01 12:17:34'),
(22, 'surat_kematian', 8, '400.10.2.2/11/31.07.16/2026', '345d1083336286106bf042a7da1c5677', '2026-08-01 12:17:52'),
(23, 'surat_kematian', 9, '400.10.2.2/13/31.07.16/2026', '0b9736119d089e2778e7fc86d912ce8a', '2026-08-02 14:45:11'),
(24, 'sktm_kis', 4, '400.10.2.2/03/31.07.16/2026', '0c5bc958cb17a1152e2df0321425d3a4', '2026-08-03 20:39:51'),
(25, 'surat_garapan', 11, '400.10.2.2/14/31.07.16/2026', '4d7ae9a2c3a9965011073f21da195059', '2026-08-04 08:22:32'),
(26, 'surat_ahli_waris', 5, '400.10.2.2/15/31.07.16/2026', '778e8b8e3a9b8ec72b601be103cb53ca', '2026-08-04 08:23:04'),
(27, 'surat_garapan', 9, '400.10.2.2/09/31.07.16/2026', '01cfbe9f23aa1e5feedefa1c96fbc0d0', '2026-08-04 08:23:19'),
(28, 'surat_undangan', 10, '400.10.2.2/13/31.07.16/2026', '31b624a2cf82640a648cb40a3ba73bbf', '2026-08-04 08:23:32'),
(29, 'surat_kelahiran', 6, '400.10.2.2/16/31.07.16/2026', 'c5ad042e2279d59733eac5f016747ad0', '2026-08-04 08:23:48'),
(30, 'surat_kematian', 10, '400.10.2.2/17/31.07.16/2026', '77350302100fad55f19377fb405266e7', '2026-08-04 08:24:01'),
(31, 'surat_keterangan_pengantar', 11, '400.10.2.2/12/31.07.16/2026', '68823177d480cd2323c8b405e017ff28', '2026-08-04 08:24:09'),
(32, 'surat_domisili', 11, '400.10.2.2/18/31.07.16/2026', 'a4c584ddeb31a34828c83d0e94aca96c', '2026-08-04 08:24:18'),
(33, 'surat_pengantar_dukcapil', 11, '400.10.2.2/19/31.07.16/2026', '619016de6563b7a363bd503bdb23f338', '2026-08-04 08:24:25'),
(34, 'sktm_bumil', 9, '400.10.2.2/20/31.07.16/2026', 'cd27971b9e14a0a6ba1c49453b4d4b97', '2026-08-04 08:24:42'),
(35, 'sktm_rawat', 5, '400.10.2.2/21/31.07.16/2026', '0d0267f586de9abf59d479f9f3ba80a5', '2026-08-04 08:24:51'),
(36, 'sktm_kis', 5, '400.10.2.2/22/31.07.16/2026', 'a709fa67e53e10949e144317f13bcfb6', '2026-08-04 08:24:59'),
(37, 'sktm_kip', 6, '400.10.2.2/23/31.07.16/2026', 'ef0404feff5e1901bde962fb3e45afc5', '2026-08-04 08:25:06'),
(38, 'sktm_stunting', 7, '400.10.2.2/24/31.07.16/2026', 'd2cf552d03ee9fbfce89cabf180c910c', '2026-08-04 08:25:15'),
(39, 'surat_keterangan_pengantar', 18, '400.10.2.2/24/31.07.16/2026', '5c07d67cb99c77bd0adb4751e1610dd7', '2026-08-04 17:13:45'),
(40, 'surat_undangan', 11, '400.10.2.2/24/31.07.16/2026', '7e4a2f72e8232b00963d00e6bf2ef905', '2026-08-04 17:21:24'),
(41, 'surat_keterangan_pengantar', 20, '400.10.2.2/25/31.07.16/2026', '7cd2c312ddb2da212cf5373d9d170fcf', '2026-08-04 17:39:15'),
(42, 'surat_keterangan_pengantar', 22, '400.10.2.2/01/31.07.16/2026', '109115b74624fdf93a5755fdd11fb8fc', '2026-08-06 10:32:27');

-- --------------------------------------------------------

--
-- Table structure for table `tb_waris_detail_anak`
--

CREATE TABLE `tb_waris_detail_anak` (
  `id_detail_anak` int(11) NOT NULL,
  `id_waris` int(11) NOT NULL,
  `nama_anak` varchar(150) NOT NULL,
  `pekerjaan` varchar(100) DEFAULT NULL,
  `alamat_tinggal` text DEFAULT NULL,
  `status_hidup` enum('Hidup','Meninggal') DEFAULT 'Hidup'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_waris_detail_saksi`
--

CREATE TABLE `tb_waris_detail_saksi` (
  `id_detail_saksi` int(11) NOT NULL,
  `id_waris` int(11) NOT NULL,
  `nama_saksi` varchar(150) NOT NULL,
  `pekerjaan` varchar(100) DEFAULT NULL,
  `alamat_saksi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `nomor_surat_global`
--
ALTER TABLE `nomor_surat_global`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_device_terpercaya`
--
ALTER TABLE `tb_device_terpercaya`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `device_token` (`device_token`);

--
-- Indexes for table `tb_nomor_surat_global`
--
ALTER TABLE `tb_nomor_surat_global`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_tahun` (`tahun`);

--
-- Indexes for table `tb_pejabat`
--
ALTER TABLE `tb_pejabat`
  ADD PRIMARY KEY (`id_pejabat`);

--
-- Indexes for table `tb_penduduk`
--
ALTER TABLE `tb_penduduk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- Indexes for table `tb_sktm_bumil`
--
ALTER TABLE `tb_sktm_bumil`
  ADD PRIMARY KEY (`id_sktm`),
  ADD KEY `id_pejabat` (`id_pejabat`);

--
-- Indexes for table `tb_sktm_kip`
--
ALTER TABLE `tb_sktm_kip`
  ADD PRIMARY KEY (`id_sktm`),
  ADD KEY `fk_sktm_kip_pejabat` (`id_pejabat`);

--
-- Indexes for table `tb_sktm_kis`
--
ALTER TABLE `tb_sktm_kis`
  ADD PRIMARY KEY (`id_sktm`);

--
-- Indexes for table `tb_sktm_rawat`
--
ALTER TABLE `tb_sktm_rawat`
  ADD PRIMARY KEY (`id_sktm`),
  ADD KEY `id_pejabat` (`id_pejabat`);

--
-- Indexes for table `tb_sktm_rawat_pasien`
--
ALTER TABLE `tb_sktm_rawat_pasien`
  ADD PRIMARY KEY (`id_pasien_detail`),
  ADD KEY `id_sktm` (`id_sktm`);

--
-- Indexes for table `tb_sktm_stunting`
--
ALTER TABLE `tb_sktm_stunting`
  ADD PRIMARY KEY (`id_sktm`),
  ADD KEY `fk_sktm_stunting_pejabat` (`id_pejabat`);

--
-- Indexes for table `tb_surat_domisili`
--
ALTER TABLE `tb_surat_domisili`
  ADD PRIMARY KEY (`id_domisili`),
  ADD KEY `id_pejabat` (`id_pejabat`);

--
-- Indexes for table `tb_surat_dukcapil`
--
ALTER TABLE `tb_surat_dukcapil`
  ADD PRIMARY KEY (`id_surat`);

--
-- Indexes for table `tb_surat_garapan`
--
ALTER TABLE `tb_surat_garapan`
  ADD PRIMARY KEY (`id_garapan`);

--
-- Indexes for table `tb_surat_garapan_detail`
--
ALTER TABLE `tb_surat_garapan_detail`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_garapan` (`id_garapan`);

--
-- Indexes for table `tb_surat_kelahiran`
--
ALTER TABLE `tb_surat_kelahiran`
  ADD PRIMARY KEY (`id_surat`);

--
-- Indexes for table `tb_surat_kematian`
--
ALTER TABLE `tb_surat_kematian`
  ADD PRIMARY KEY (`id_surat`);

--
-- Indexes for table `tb_surat_pengantar`
--
ALTER TABLE `tb_surat_pengantar`
  ADD PRIMARY KEY (`id_surat`);

--
-- Indexes for table `tb_surat_undangan`
--
ALTER TABLE `tb_surat_undangan`
  ADD PRIMARY KEY (`id_undangan`);

--
-- Indexes for table `tb_surat_waris`
--
ALTER TABLE `tb_surat_waris`
  ADD PRIMARY KEY (`id_waris`);

--
-- Indexes for table `tb_undangan_tujuan`
--
ALTER TABLE `tb_undangan_tujuan`
  ADD PRIMARY KEY (`id_tujuan`),
  ADD KEY `fk_undangan_tujuan` (`id_undangan`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tb_verifikasi_dokumen`
--
ALTER TABLE `tb_verifikasi_dokumen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_dokumen` (`jenis_surat`,`id_surat`);

--
-- Indexes for table `tb_waris_detail_anak`
--
ALTER TABLE `tb_waris_detail_anak`
  ADD PRIMARY KEY (`id_detail_anak`),
  ADD KEY `id_waris` (`id_waris`);

--
-- Indexes for table `tb_waris_detail_saksi`
--
ALTER TABLE `tb_waris_detail_saksi`
  ADD PRIMARY KEY (`id_detail_saksi`),
  ADD KEY `id_waris` (`id_waris`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `nomor_surat_global`
--
ALTER TABLE `nomor_surat_global`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_device_terpercaya`
--
ALTER TABLE `tb_device_terpercaya`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_nomor_surat_global`
--
ALTER TABLE `tb_nomor_surat_global`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_pejabat`
--
ALTER TABLE `tb_pejabat`
  MODIFY `id_pejabat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_penduduk`
--
ALTER TABLE `tb_penduduk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1506;

--
-- AUTO_INCREMENT for table `tb_sktm_bumil`
--
ALTER TABLE `tb_sktm_bumil`
  MODIFY `id_sktm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tb_sktm_kip`
--
ALTER TABLE `tb_sktm_kip`
  MODIFY `id_sktm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tb_sktm_kis`
--
ALTER TABLE `tb_sktm_kis`
  MODIFY `id_sktm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_sktm_rawat`
--
ALTER TABLE `tb_sktm_rawat`
  MODIFY `id_sktm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_sktm_rawat_pasien`
--
ALTER TABLE `tb_sktm_rawat_pasien`
  MODIFY `id_pasien_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tb_sktm_stunting`
--
ALTER TABLE `tb_sktm_stunting`
  MODIFY `id_sktm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tb_surat_domisili`
--
ALTER TABLE `tb_surat_domisili`
  MODIFY `id_domisili` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tb_surat_dukcapil`
--
ALTER TABLE `tb_surat_dukcapil`
  MODIFY `id_surat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tb_surat_garapan`
--
ALTER TABLE `tb_surat_garapan`
  MODIFY `id_garapan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tb_surat_garapan_detail`
--
ALTER TABLE `tb_surat_garapan_detail`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tb_surat_kelahiran`
--
ALTER TABLE `tb_surat_kelahiran`
  MODIFY `id_surat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tb_surat_kematian`
--
ALTER TABLE `tb_surat_kematian`
  MODIFY `id_surat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tb_surat_pengantar`
--
ALTER TABLE `tb_surat_pengantar`
  MODIFY `id_surat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tb_surat_undangan`
--
ALTER TABLE `tb_surat_undangan`
  MODIFY `id_undangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tb_surat_waris`
--
ALTER TABLE `tb_surat_waris`
  MODIFY `id_waris` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_undangan_tujuan`
--
ALTER TABLE `tb_undangan_tujuan`
  MODIFY `id_tujuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_verifikasi_dokumen`
--
ALTER TABLE `tb_verifikasi_dokumen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `tb_waris_detail_anak`
--
ALTER TABLE `tb_waris_detail_anak`
  MODIFY `id_detail_anak` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `tb_waris_detail_saksi`
--
ALTER TABLE `tb_waris_detail_saksi`
  MODIFY `id_detail_saksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_sktm_bumil`
--
ALTER TABLE `tb_sktm_bumil`
  ADD CONSTRAINT `fk_sktm_pejabat` FOREIGN KEY (`id_pejabat`) REFERENCES `tb_pejabat` (`id_pejabat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_sktm_kip`
--
ALTER TABLE `tb_sktm_kip`
  ADD CONSTRAINT `fk_sktm_kip_pejabat` FOREIGN KEY (`id_pejabat`) REFERENCES `tb_pejabat` (`id_pejabat`) ON UPDATE CASCADE;

--
-- Constraints for table `tb_sktm_rawat`
--
ALTER TABLE `tb_sktm_rawat`
  ADD CONSTRAINT `fk_sktm_rawat_pejabat` FOREIGN KEY (`id_pejabat`) REFERENCES `tb_pejabat` (`id_pejabat`) ON UPDATE CASCADE;

--
-- Constraints for table `tb_sktm_rawat_pasien`
--
ALTER TABLE `tb_sktm_rawat_pasien`
  ADD CONSTRAINT `fk_pasien_sktm` FOREIGN KEY (`id_sktm`) REFERENCES `tb_sktm_rawat` (`id_sktm`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_sktm_stunting`
--
ALTER TABLE `tb_sktm_stunting`
  ADD CONSTRAINT `fk_sktm_stunting_pejabat` FOREIGN KEY (`id_pejabat`) REFERENCES `tb_pejabat` (`id_pejabat`) ON UPDATE CASCADE;

--
-- Constraints for table `tb_surat_domisili`
--
ALTER TABLE `tb_surat_domisili`
  ADD CONSTRAINT `tb_surat_domisili_ibfk_1` FOREIGN KEY (`id_pejabat`) REFERENCES `tb_pejabat` (`id_pejabat`) ON UPDATE CASCADE;

--
-- Constraints for table `tb_surat_garapan_detail`
--
ALTER TABLE `tb_surat_garapan_detail`
  ADD CONSTRAINT `tb_surat_garapan_detail_ibfk_1` FOREIGN KEY (`id_garapan`) REFERENCES `tb_surat_garapan` (`id_garapan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_undangan_tujuan`
--
ALTER TABLE `tb_undangan_tujuan`
  ADD CONSTRAINT `fk_undangan_tujuan` FOREIGN KEY (`id_undangan`) REFERENCES `tb_surat_undangan` (`id_undangan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_waris_detail_anak`
--
ALTER TABLE `tb_waris_detail_anak`
  ADD CONSTRAINT `tb_waris_detail_anak_ibfk_1` FOREIGN KEY (`id_waris`) REFERENCES `tb_surat_waris` (`id_waris`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_waris_detail_saksi`
--
ALTER TABLE `tb_waris_detail_saksi`
  ADD CONSTRAINT `tb_waris_detail_saksi_ibfk_1` FOREIGN KEY (`id_waris`) REFERENCES `tb_surat_waris` (`id_waris`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
