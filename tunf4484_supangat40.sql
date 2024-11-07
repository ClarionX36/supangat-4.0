-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 07, 2024 at 11:34 AM
-- Server version: 10.6.19-MariaDB-cll-lve
-- PHP Version: 8.3.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tunf4484_supangat40`
--

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `id` int(11) NOT NULL,
  `namaJabatan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_pendanaan`
--

CREATE TABLE `jenis_pendanaan` (
  `id` int(11) NOT NULL,
  `namaPendanaan` varchar(100) NOT NULL,
  `deskripsiSingkat` text DEFAULT NULL,
  `nominal` decimal(15,0) DEFAULT NULL,
  `codeName` varchar(50) NOT NULL,
  `expiry` date DEFAULT NULL,
  `tanggalRilis` date DEFAULT NULL,
  `tipePendanaan` enum('Umum','Khusus') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenjang`
--

CREATE TABLE `jenjang` (
  `id` int(11) NOT NULL,
  `namaJenjang` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `klasifikasi`
--

CREATE TABLE `klasifikasi` (
  `id` int(11) NOT NULL,
  `namaKlasifikasi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pendanaan_khusus`
--

CREATE TABLE `pendanaan_khusus` (
  `id` int(11) NOT NULL,
  `id_pendanaan` int(11) DEFAULT NULL,
  `id_jenjang` int(11) DEFAULT NULL,
  `id_klasifikasi` int(11) DEFAULT NULL,
  `id_jabatan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_pendanaan_khusus`
--

CREATE TABLE `riwayat_pendanaan_khusus` (
  `id` int(11) NOT NULL,
  `riwayat_penghapusan_id` int(11) DEFAULT NULL,
  `jenjang_id` int(11) DEFAULT NULL,
  `klasifikasi_id` int(11) DEFAULT NULL,
  `jabatan_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_penghapusan`
--

CREATE TABLE `riwayat_penghapusan` (
  `id` int(11) NOT NULL,
  `jenis_pendanaan_id` int(11) DEFAULT NULL,
  `nama_pendanaan` varchar(255) DEFAULT NULL,
  `deskripsi_singkat` text DEFAULT NULL,
  `nominal` decimal(15,2) DEFAULT NULL,
  `code_name` varchar(100) DEFAULT NULL,
  `tanggal_rilis` date DEFAULT NULL,
  `expiry` date DEFAULT NULL,
  `tipe_pendanaan` enum('umum','khusus') DEFAULT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `privilege` enum('supangat','superAdmin','pusat','provinsi','kab','kec','ksbDesa','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jenis_pendanaan`
--
ALTER TABLE `jenis_pendanaan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codeName` (`codeName`);

--
-- Indexes for table `jenjang`
--
ALTER TABLE `jenjang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `klasifikasi`
--
ALTER TABLE `klasifikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pendanaan_khusus`
--
ALTER TABLE `pendanaan_khusus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_pendanaan` (`id_pendanaan`,`id_jenjang`,`id_klasifikasi`,`id_jabatan`),
  ADD KEY `id_jenjang` (`id_jenjang`),
  ADD KEY `id_klasifikasi` (`id_klasifikasi`),
  ADD KEY `id_jabatan` (`id_jabatan`);

--
-- Indexes for table `riwayat_pendanaan_khusus`
--
ALTER TABLE `riwayat_pendanaan_khusus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `riwayat_penghapusan_id` (`riwayat_penghapusan_id`);

--
-- Indexes for table `riwayat_penghapusan`
--
ALTER TABLE `riwayat_penghapusan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jenis_pendanaan_id` (`jenis_pendanaan_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_pendanaan`
--
ALTER TABLE `jenis_pendanaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenjang`
--
ALTER TABLE `jenjang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `klasifikasi`
--
ALTER TABLE `klasifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pendanaan_khusus`
--
ALTER TABLE `pendanaan_khusus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `riwayat_pendanaan_khusus`
--
ALTER TABLE `riwayat_pendanaan_khusus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `riwayat_penghapusan`
--
ALTER TABLE `riwayat_penghapusan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pendanaan_khusus`
--
ALTER TABLE `pendanaan_khusus`
  ADD CONSTRAINT `pendanaan_khusus_ibfk_1` FOREIGN KEY (`id_pendanaan`) REFERENCES `jenis_pendanaan` (`id`),
  ADD CONSTRAINT `pendanaan_khusus_ibfk_2` FOREIGN KEY (`id_jenjang`) REFERENCES `jenjang` (`id`),
  ADD CONSTRAINT `pendanaan_khusus_ibfk_3` FOREIGN KEY (`id_klasifikasi`) REFERENCES `klasifikasi` (`id`),
  ADD CONSTRAINT `pendanaan_khusus_ibfk_4` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id`);

--
-- Constraints for table `riwayat_pendanaan_khusus`
--
ALTER TABLE `riwayat_pendanaan_khusus`
  ADD CONSTRAINT `riwayat_pendanaan_khusus_ibfk_1` FOREIGN KEY (`riwayat_penghapusan_id`) REFERENCES `riwayat_penghapusan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `riwayat_penghapusan`
--
ALTER TABLE `riwayat_penghapusan`
  ADD CONSTRAINT `riwayat_penghapusan_ibfk_1` FOREIGN KEY (`jenis_pendanaan_id`) REFERENCES `jenis_pendanaan` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
