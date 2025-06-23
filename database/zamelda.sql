-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 22, 2025 at 03:06 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `basisdata`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id_absensi` int NOT NULL,
  `id_jadwal` int DEFAULT NULL,
  `id_mahasiswa` int DEFAULT NULL,
  `tanggal` date NOT NULL,
  `status_kehadiran` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id_absensi`, `id_jadwal`, `id_mahasiswa`, `tanggal`, `status_kehadiran`) VALUES
(1, 1, 1, '2025-03-01', 'Hadir'),
(2, 2, 2, '2025-03-02', 'Izin'),
(3, 3, 3, '2025-03-03', 'Sakit'),
(4, 4, 4, '2025-03-04', 'Hadir'),
(5, 5, 5, '2025-03-05', 'Alpha');


INSERT INTO `absensi` (`id_absensi`, `id_jadwal`, `id_mahasiswa`, `tanggal`, `status_kehadiran`) VALUES
(6, 1, 1, '2025-03-06', 'Hadir'),

-- --------------------------------------------------------

--
-- Table structure for table `bimbingan_akademik`
--

CREATE TABLE `bimbingan_akademik` (
  `id_bimbingan` int NOT NULL,
  `id_mahasiswa` int DEFAULT NULL,
  `id_dosen` int DEFAULT NULL,
  `tanggal` date NOT NULL,
  `catatan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bimbingan_akademik`
--

INSERT INTO `bimbingan_akademik` (`id_bimbingan`, `id_mahasiswa`, `id_dosen`, `tanggal`, `catatan`) VALUES
(1, 1, 1, '2025-01-10', 'Rencana ambil 24 SKS'),
(2, 2, 2, '2025-01-12', 'Bingung memilih mata kuliah pilihan'),
(3, 3, 3, '2025-01-13', 'Diskusi topik skripsi'),
(4, 4, 4, '2025-01-15', 'Perlu bimbingan akademik lanjutan'),
(5, 5, 5, '2025-01-17', 'Konsultasi studi lanjut');

-- --------------------------------------------------------

--
-- Table structure for table `dosen`
--

CREATE TABLE `dosen` (
  `id_dosen` int NOT NULL,
  `NIDN` varchar(20) NOT NULL,
  `nama_dosen` varchar(50) NOT NULL,
  `jenis_kelamin` varchar(10) NOT NULL,
  `alamat` text,
  `email` varchar(20) DEFAULT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `id_fakultas` int DEFAULT NULL,
  `jabatan` varchar(20) DEFAULT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dosen`
--

INSERT INTO `dosen` (`id_dosen`, `NIDN`, `nama_dosen`, `jenis_kelamin`, `alamat`, `email`, `no_telp`, `id_fakultas`, `jabatan`, `status`) VALUES
(1, '12345678', 'Dr. Andi', 'Laki-laki', 'Jl. Merdeka', 'andi@ub.ac.id', '081234567890', 1, 'Kaprodi', 'Aktif'),
(2, '87654321', 'Siti Aminah S.Kom', 'Perempuan', 'Jl. Veteran', 'sari@ub.ac.id', '081234567891', 2, 'Lektor Kepala', 'Aktif'),
(3, '11223344', ' Farida S.H ', 'Laki-laki', 'Jl. Soekarno', 'bambang@ub.ac.id', '081234567892', 3, 'Lektor Kepala', 'Aktif'),
(4, '44332211', 'Dr. Rina', 'Perempuan', 'Jl. Hatta', 'rina@ub.ac.id', '081234567893', 4, 'Dosen', 'Aktif'),
(5, '99887766', 'Dr. Yuli', 'Perempuan', 'Jl. Ijen', 'yuli@ub.ac.id', '081234567894', 5, 'Dosen', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `dosen_wali`
--

CREATE TABLE `dosen_wali` (
  `id_wali` int NOT NULL,
  `id_dosen` int DEFAULT NULL,
  `id_mahasiswa` int DEFAULT NULL,
  `periode` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dosen_wali`
--

INSERT INTO `dosen_wali` (`id_wali`, `id_dosen`, `id_mahasiswa`, `periode`) VALUES
(1, 1, 1, '2024/2025'),
(2, 2, 2, '2024/2025'),
(3, 3, 3, '2024/2025'),
(4, 4, 4, '2024/2025'),
(5, 5, 5, '2024/2025');

-- --------------------------------------------------------

--
-- Table structure for table `fakultas`
--

CREATE TABLE `fakultas` (
  `id_fakultas` int NOT NULL,
  `nama_fakultas` varchar(100) NOT NULL,
  `nama_dekan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `fakultas`
--

INSERT INTO `fakultas` (`id_fakultas`, `nama_fakultas`, `nama_dekan`) VALUES
(1, 'Fakultas Ilmu Komputer', ''),
(2, 'Fakultas Ekonomi dan Bisnis', ''),
(3, 'Fakultas Hukum', ''),
(4, 'Fakultas Teknik', ''),
(5, 'Fakultas Kedokteran', '');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_kuliah`
--

CREATE TABLE `jadwal_kuliah` (
  `id_jadwal` int NOT NULL,
  `id_matkul` int DEFAULT NULL,
  `id_dosen` int DEFAULT NULL,
  `hari` varchar(10) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `id_ruangan` int DEFAULT NULL,
  `tahun_akademik` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jadwal_kuliah`
--

INSERT INTO `jadwal_kuliah` (`id_jadwal`, `id_matkul`, `id_dosen`, `hari`, `jam_mulai`, `jam_selesai`, `id_ruangan`, `tahun_akademik`) VALUES
(1, 1, 1, 'Senin', '08:00:00', '10:00:00', 1, 2024),
(2, 2, 2, 'Selasa', '10:00:00', '12:00:00', 2, 2024),
(3, 3, 3, 'Rabu', '13:00:00', '15:00:00', 3, 2024),
(4, 4, 4, 'Kamis', '09:00:00', '11:00:00', 4, 2024),
(5, 5, 5, 'Jumat', '14:00:00', '16:00:00', 5, 2024);

-- --------------------------------------------------------

--
-- Table structure for table `khs`
--

CREATE TABLE `khs` (
  `id_khs` int NOT NULL,
  `id_mahasiswa` int DEFAULT NULL,
  `semester` varchar(20) NOT NULL,
  `tahun_akademik` varchar(20) NOT NULL,
  `IPS` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `khs`
--

INSERT INTO `khs` (`id_khs`, `id_mahasiswa`, `semester`, `tahun_akademik`, `IPS`) VALUES
(1, 1, 'Ganjil', '2024/2025', '4'),
(2, 2, 'Ganjil', '2024/2025', '4'),
(3, 3, 'Ganjil', '2024/2025', '4'),
(4, 4, 'Genap', '2024/2025', '4'),
(5, 5, 'Genap', '2024/2025', '4');

-- --------------------------------------------------------

--
-- Table structure for table `krs`
--

CREATE TABLE `krs` (
  `id_krs` int NOT NULL,
  `id_mahasiswa` int DEFAULT NULL,
  `id_matkul` int DEFAULT NULL,
  `semester` varchar(20) NOT NULL,
  `tahun_akademik` varchar(20) NOT NULL,
  `status_pengambilan` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `krs`
--

INSERT INTO `krs` (`id_krs`, `id_mahasiswa`, `id_matkul`, `semester`, `tahun_akademik`, `status_pengambilan`) VALUES
(1, 1, 1, 'Ganjil', '2024/2025', 'Aktif'),
(2, 2, 2, 'Ganjil', '2024/2025', 'Aktif'),
(3, 3, 3, 'Ganjil', '2024/2025', 'Aktif'),
(4, 4, 4, 'Genap', '2024/2025', 'Aktif'),
(5, 5, 5, 'Genap', '2024/2025', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id_mahasiswa` int NOT NULL,
  `nim` varchar(20) NOT NULL,
  `nama_mahasiswa` varchar(50) NOT NULL,
  `jenis_kelamin` varchar(10) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` text,
  `email` varchar(20) DEFAULT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `id_prodi` int DEFAULT NULL,
  `angkatan` int NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `nim`, `nama_mahasiswa`, `jenis_kelamin`, `tanggal_lahir`, `alamat`, `email`, `no_telp`, `id_prodi`, `angkatan`, `status`) VALUES
(1, '215150700111001', 'Budi Santoso', 'Laki-laki', '2003-05-10', 'Jl. Soekarno Hatta', 'budi@yahoo.co.id', '081234567891', 1, 2021, 'Aktif'),
(2, '215150700111002', 'Siti Aminah', 'Perempuan', '2003-06-12', 'Jl. Soekarno Hatta', 'siti@ub.ac.id', '081234567892', 2, 2021, 'Aktif'),
(3, '215150700111003', 'Dedi Mulyadi', 'Laki-laki', '2003-07-15', 'Jl. Letjen Sutoyo', 'dedi@ub.ac.id', '081234567893', 3, 2021, 'Aktif'),
(4, '215150700111004', 'Rani Wijaya', 'Perempuan', '2003-08-20', 'Jl. Semeru', 'rani@ub.ac.id', '081234567894', 4, 2021, 'Aktif'),
(5, '215150700111005', 'Rara Pangestu', 'Laki-laki', '2003-09-25', 'Jl. Veteran', 'rara@gmail.com', '081234567895', 5, 2021, 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `mata_kuliah`
--

CREATE TABLE `mata_kuliah` (
  `id_matkul` int NOT NULL,
  `kode_matkul` varchar(20) NOT NULL,
  `nama_matkul` varchar(50) NOT NULL,
  `sks` varchar(10) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `id_prodi` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mata_kuliah`
--

INSERT INTO `mata_kuliah` (`id_matkul`, `kode_matkul`, `nama_matkul`, `sks`, `semester`, `id_prodi`) VALUES
(1, 'IF101', 'Pemrograman Dasar', '3', 'Ganjil', 1),
(2, 'SI102', 'Sistem Basis Data', '3', 'Ganjil', 2),
(3, 'MN103', 'Manajemen Keuangan', '3', 'Ganjil', 3),
(4, 'TS104', 'Struktur Beton', '3', 'Genap', 4),
(5, 'KD105', 'Pemrograman Lanjut', '3', 'Genap', 5);

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

CREATE TABLE `nilai` (
  `id_nilai` int NOT NULL,
  `id_mahasiswa` int DEFAULT NULL,
  `id_matkul` int DEFAULT NULL, `SKS` int DEFAULT NULL,
  `semester` varchar(20) NOT NULL,
  `tahun_akademik` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`id_nilai`, `id_mahasiswa`, `id_matkul`, `SKS`, `semester`, `tahun_akademik`) VALUES
(1, 1, 1, 3, 'Ganjil', '2024/2025'),
(2, 2, 2, 3, 'Ganjil', '2024/2025'),
(3, 3, 3, 3, 'Ganjil', '2024/2025'),
(4, 4, 4, 3, 'Genap', '2024/2025'),
(5, 5, 5, 3, 'Genap', '2024/2025');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int NOT NULL,
  `id_mahasiswa` int DEFAULT NULL,
  `jenis_pembayaran` varchar(10) NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `tgl_bayar` date NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_mahasiswa`, `jenis_pembayaran`, `jumlah`, `tgl_bayar`, `status`) VALUES
(1, 1, 'UKT', '4000000.00', '2025-01-15', 'Lunas'),
(2, 2, 'UKT', '4000000.00', '2025-01-16', 'Lunas'),
(3, 3, 'UKT', '4000000.00', '2025-01-17', 'Lunas'),
(4, 4, 'UKT', '4000000.00', '2025-01-18', 'Lunas'),
(5, 5, 'UKT', '4000000.00', '2025-01-19', 'Lunas');

-- --------------------------------------------------------

--
-- Table structure for table `program_studi`
--

CREATE TABLE `program_studi` (
  `id_prodi` int NOT NULL,
  `nama_prodi` varchar(20) NOT NULL,
  `jenjang` varchar(20) NOT NULL,
  `id_fakultas` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `program_studi`
--

INSERT INTO `program_studi` (`id_prodi`, `nama_prodi`, `jenjang`, `id_fakultas`) VALUES
(1, 'Informatika', 'S1', 1),
(2, 'Sistem Informasi', 'S1', 1),
(3, 'Manajemen', 'S1', 2),
(4, 'Teknik Sipil', 'S1', 4),
(5, 'Kedokteran Umum', 'S1', 5);

-- --------------------------------------------------------

--
-- Table structure for table `registrasi`
--

CREATE TABLE `registrasi` (
  `id_registrasi` int NOT NULL,
  `id_mahasiswa` int DEFAULT NULL,
  `tahun_akademik` int NOT NULL,
  `tgl_registrasi` date NOT NULL,
  `status_registrasi` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `registrasi`
--

INSERT INTO `registrasi` (`id_registrasi`, `id_mahasiswa`, `tahun_akademik`, `tgl_registrasi`, `status_registrasi`) VALUES
(1, 1, 2024, '2025-01-08', 'Terdaftar'),
(2, 2, 2024, '2025-01-09', 'Terdaftar'),
(3, 3, 2024, '2025-01-10', 'Terdaftar'),
(4, 4, 2024, '2025-01-11', 'Terdaftar'),
(5, 5, 2024, '2025-01-12', 'Terdaftar');

-- --------------------------------------------------------

--
-- Table structure for table `ruangan`
--

CREATE TABLE `ruangan` (
  `id_ruangan` int NOT NULL,
  `nama_ruangan` varchar(20) NOT NULL,
  `gedung` varchar(20) DEFAULT NULL,
  `kapasitas` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ruangan`
--

INSERT INTO `ruangan` (`id_ruangan`, `nama_ruangan`, `gedung`, `kapasitas`) VALUES
(1, 'Ruang 101', 'Gedung A', 40),
(2, 'Ruang 102', 'Gedung A', 50),
(3, 'Ruang 201', 'Gedung B', 30),
(4, 'Ruang 301', 'Gedung C', 45),
(5, 'Lab Komputer', 'Gedung D', 35);

-- --------------------------------------------------------

--
-- Stand-in structure for viewINSERT INTO `jadwal_kuliah` (`id_jadwal`, `id_matkul`, `id_dosen`, `hari`, `jam_mulai`, `jam_selesai`, `id_ruangan`, `tahun_akademik`) VALUES
(8, 2, 1, 'Kamis', '08:00:00', '10:00:00', 4, 2021); INSERT INTO `jadwal_kuliah` (`id_jadwal`, `id_matkul`, `id_dosen`, `hari`, `jam_mulai`, `jam_selesai`, `id_ruangan`, `tahun_akademik`) VALUES
(8, 2, 1, 'Kamis', '08:00:00', '10:00:00', 4, 2021);`view_pembayaran_semester`
-- (See below for the actual view)
--
CREATE TABLE `view_pembayaran_semester` (
`id_pembayaran` int
,`id_mahasiswa` int
,`jenis_pembayaran` varchar(10)
,`jumlah` decimal(10,2)
,`tgl_bayar` date
,`status` varchar(20)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_pembayaran_terbaru`
-- (See below for the actual view)
--
CREATE TABLE `view_pembayaran_terbaru` (
`id_pembayaran` int
,`id_mahasiswa` int
,`jenis_pembayaran` varchar(10)
,`jumlah` decimal(10,2)
,`tgl_bayar` date
,`status` varchar(20)
);

-- --------------------------------------------------------

--
-- Table structure for table `view_total_pembayaran_mahasiswa`
--

CREATE TABLE `view_total_pembayaran_mahasiswa` (
  `id_mahasiswa` int DEFAULT NULL,
  `total_pembayaran` decimal(32,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `view_total_pembayaran_semester`
--

CREATE TABLE `view_total_pembayaran_semester` (
  `id_mahasiswa` int DEFAULT NULL,
  `total_pembayaran` decimal(32,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure for view `view_pembayaran_semester`
--
DROP TABLE IF EXISTS `view_pembayaran_semester`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_pembayaran_semester`  AS SELECT `pembayaran`.`id_pembayaran` AS `id_pembayaran`, `pembayaran`.`id_mahasiswa` AS `id_mahasiswa`, `pembayaran`.`jenis_pembayaran` AS `jenis_pembayaran`, `pembayaran`.`jumlah` AS `jumlah`, `pembayaran`.`tgl_bayar` AS `tgl_bayar`, `pembayaran`.`status` AS `status` FROM `pembayaran` WHERE (`pembayaran`.`tgl_bayar` between '2025-01-01' and '2025-06-30')  ;

-- --------------------------------------------------------

--
-- Structure for view `view_pembayaran_terbaru`
--
DROP TABLE IF EXISTS `view_pembayaran_terbaru`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_pembayaran_terbaru`  AS SELECT `pembayaran`.`id_pembayaran` AS `id_pembayaran`, `pembayaran`.`id_mahasiswa` AS `id_mahasiswa`, `pembayaran`.`jenis_pembayaran` AS `jenis_pembayaran`, `pembayaran`.`jumlah` AS `jumlah`, `pembayaran`.`tgl_bayar` AS `tgl_bayar`, `pembayaran`.`status` AS `status` FROM `pembayaran` ORDER BY `pembayaran`.`tgl_bayar` DESC LIMIT 0, 5555  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absensi`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`),
  ADD KEY `id_jadwal` (`id_jadwal`);

--
-- Indexes for table `bimbingan_akademik`
--
ALTER TABLE `bimbingan_akademik`
  ADD PRIMARY KEY (`id_bimbingan`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`),
  ADD KEY `id_dosen` (`id_dosen`);

--
-- Indexes for table `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id_dosen`),
  ADD UNIQUE KEY `NIDN` (`NIDN`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_fakultas` (`id_fakultas`);

--
-- Indexes for table `dosen_wali`
--
ALTER TABLE `dosen_wali`
  ADD PRIMARY KEY (`id_wali`),
  ADD KEY `fk_dw_dosen` (`id_dosen`),
  ADD KEY `fk_dw_mahasiswa` (`id_mahasiswa`);

--
-- Indexes for table `fakultas`
--
ALTER TABLE `fakultas`
  ADD PRIMARY KEY (`id_fakultas`);

--
-- Indexes for table `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_matkul` (`id_matkul`),
  ADD KEY `id_dosen` (`id_dosen`),
  ADD KEY `id_ruangan` (`id_ruangan`);

--
-- Indexes for table `khs`
--
ALTER TABLE `khs`
  ADD PRIMARY KEY (`id_khs`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`);

--
-- Indexes for table `krs`
--
ALTER TABLE `krs`
  ADD PRIMARY KEY (`id_krs`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`),
  ADD KEY `id_matkul` (`id_matkul`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_prodi` (`id_prodi`);

--
-- Indexes for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD PRIMARY KEY (`id_matkul`),
  ADD UNIQUE KEY `kode_matkul` (`kode_matkul`),
  ADD KEY `id_prodi` (`id_prodi`);

--
-- Indexes for table `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id_nilai`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`),
  ADD KEY `id_matkul` (`id_matkul`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`);

--
-- Indexes for table `program_studi`
--
ALTER TABLE `program_studi`
  ADD PRIMARY KEY (`id_prodi`),
  ADD KEY `id_fakultas` (`id_fakultas`);

--
-- Indexes for table `registrasi`
--
ALTER TABLE `registrasi`
  ADD PRIMARY KEY (`id_registrasi`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`);

--
-- Indexes for table `ruangan`
--
ALTER TABLE `ruangan`
  ADD PRIMARY KEY (`id_ruangan`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`),
  ADD CONSTRAINT `absensi_ibfk_2` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal_kuliah` (`id_jadwal`);

--
-- Constraints for table `bimbingan_akademik`
--
ALTER TABLE `bimbingan_akademik`
  ADD CONSTRAINT `bimbingan_akademik_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`),
  ADD CONSTRAINT `bimbingan_akademik_ibfk_2` FOREIGN KEY (`id_dosen`) REFERENCES `dosen` (`id_dosen`);

--
-- Constraints for table `dosen`
--
ALTER TABLE `dosen`
  ADD CONSTRAINT `dosen_ibfk_1` FOREIGN KEY (`id_fakultas`) REFERENCES `fakultas` (`id_fakultas`);

--
-- Constraints for table `dosen_wali`
--
ALTER TABLE `dosen_wali`
  ADD CONSTRAINT `dosen_wali_ibfk_1` FOREIGN KEY (`id_dosen`) REFERENCES `dosen` (`id_dosen`),
  ADD CONSTRAINT `dosen_wali_ibfk_2` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`),
  ADD CONSTRAINT `fk_dw_dosen` FOREIGN KEY (`id_dosen`) REFERENCES `dosen` (`id_dosen`),
  ADD CONSTRAINT `fk_dw_mahasiswa` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`);

--
-- Constraints for table `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  ADD CONSTRAINT `jadwal_kuliah_ibfk_1` FOREIGN KEY (`id_matkul`) REFERENCES `mata_kuliah` (`id_matkul`),
  ADD CONSTRAINT `jadwal_kuliah_ibfk_2` FOREIGN KEY (`id_dosen`) REFERENCES `dosen` (`id_dosen`),
  ADD CONSTRAINT `jadwal_kuliah_ibfk_3` FOREIGN KEY (`id_ruangan`) REFERENCES `ruangan` (`id_ruangan`);

--
-- Constraints for table `khs`
--
ALTER TABLE `khs`
  ADD CONSTRAINT `khs_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`);

--
-- Constraints for table `krs`
--
ALTER TABLE `krs`
  ADD CONSTRAINT `krs_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`),
  ADD CONSTRAINT `krs_ibfk_2` FOREIGN KEY (`id_matkul`) REFERENCES `mata_kuliah` (`id_matkul`);

--
-- Constraints for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`id_prodi`) REFERENCES `program_studi` (`id_prodi`);

--
-- Constraints for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD CONSTRAINT `mata_kuliah_ibfk_1` FOREIGN KEY (`id_prodi`) REFERENCES `program_studi` (`id_prodi`);

--
-- Constraints for table `nilai`
--
ALTER TABLE `nilai`
  ADD CONSTRAINT `nilai_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`),
  ADD CONSTRAINT `nilai_ibfk_2` FOREIGN KEY (`id_matkul`) REFERENCES `mata_kuliah` (`id_matkul`);

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`);

--
-- Constraints for table `program_studi`
--
ALTER TABLE `program_studi`
  ADD CONSTRAINT `program_studi_ibfk_1` FOREIGN KEY (`id_fakultas`) REFERENCES `fakultas` (`id_fakultas`);

--
-- Constraints for table `registrasi`
--
ALTER TABLE `registrasi`
  ADD CONSTRAINT `registrasi_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
