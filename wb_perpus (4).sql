-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 20, 2025 at 09:53 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wb_perpus`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','operator') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'operator'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama`, `username`, `email`, `password`, `role`) VALUES
(3, 'Muhammad Refan Fadillah', 'fadref', 'refanfadillah2007@gmail.com', '$2y$10$IGuOmW0d8bW/sQBymROaZuM1uvw5N8BN.ISouX1.vc9OKzewfghmi', 'operator'),
(5, 'AdminTamvan', 'admin', 'admin123@gmail.com', '$2y$10$0XZSd41TFyjBCM4QjRNPtOmDzU.cipfJWrWf8UBXsb2VnZWtdmNJa', 'admin'),
(7, 'Fadillah', 'fadil', 'fadillah2007@gmail.com', '$2y$10$g3Lx2fCRfL4BfYFU7K6zUO3pN/EsBhw5uEn3l/wmEngx1z6JqgcP.', 'operator');

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` int NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pengarang` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `penerbit` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_terbit` date DEFAULT NULL,
  `foto` text COLLATE utf8mb4_general_ci,
  `barcode` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `judul`, `pengarang`, `penerbit`, `tahun_terbit`, `foto`, `barcode`) VALUES
(14, 'Sakamoto Days', 'Suzuki Yūto ', 'Gramedia', '2025-01-28', 'sakamotodays.jpg', '3541267748'),
(17, 'Solo Leveling', 'Chugong', 'Gramedia', '2025-02-01', 'sunjinwo.jpg', '67384930'),
(18, 'Naruto Uzumaki', 'masashi', 'Gramedia', '2025-02-05', 'naruto.jpg', '837293022'),
(19, 'Contoh', 'fan', 'Gramedia', '2025-02-05', 'Bandai-Gundam-Barbatos-lupa-tokoh-aksi-Mobile-Suit-Gundam-Iron-Blooded-persaudaraan-Gunpla-rakit-Model-mainan.webp', '3543829');

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

CREATE TABLE `jurusan` (
  `id_jurusan` int NOT NULL,
  `nama_jurusan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `singkatan` varchar(10) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jurusan`
--

INSERT INTO `jurusan` (`id_jurusan`, `nama_jurusan`, `singkatan`) VALUES
(6, 'Teknik Otomotif', 'TOI'),
(8, 'Rekayasa Perangkat Lunak', 'RPL');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int NOT NULL,
  `nama_kelas` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `tingkat` enum('X','XI','XII') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`, `tingkat`) VALUES
(6, '1', 'X'),
(7, '2', 'X'),
(9, '3', 'X'),
(10, '4', 'X'),
(11, '5', 'X'),
(12, '1', 'XI'),
(13, '2', 'XI'),
(14, '3', 'XI'),
(16, '1', 'XI'),
(22, '1', 'XII');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int NOT NULL,
  `id_buku` int DEFAULT NULL,
  `id_siswa` int DEFAULT NULL,
  `id_admin` int DEFAULT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `status` enum('Dipinjam','Dikembalikan','Melebihi waktu') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Dipinjam'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `id_buku`, `id_siswa`, `id_admin`, `tanggal_pinjam`, `tanggal_kembali`, `status`) VALUES
(18, 18, 12, 5, '2025-02-10', '2025-02-13', 'Dikembalikan'),
(23, 19, 51, 5, '2025-02-19', '2025-02-20', 'Dikembalikan'),
(24, 14, 53, 5, '2025-02-19', '2025-02-20', 'Dipinjam');

-- --------------------------------------------------------

--
-- Table structure for table `sekolah`
--

CREATE TABLE `sekolah` (
  `id_sekolah` int NOT NULL,
  `nama_sekolah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email_sekolah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `website_sekolah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat_sekolah` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `notelp_sekolah` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sekolah`
--

INSERT INTO `sekolah` (`id_sekolah`, `nama_sekolah`, `email_sekolah`, `website_sekolah`, `alamat_sekolah`, `notelp_sekolah`) VALUES
(1, 'NEPER ONE', 'smkn1234@gmail.com', 'www.smkn1cirebon.com', 'Cirebon, Jl. perjuangan ', '0895386490035');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nis` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `barcode` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `id_kelas` int DEFAULT NULL,
  `id_jurusan` int DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_general_ci NOT NULL,
  `telepon` varchar(22) COLLATE utf8mb4_general_ci NOT NULL,
  `foto` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nama`, `nis`, `barcode`, `id_kelas`, `id_jurusan`, `alamat`, `telepon`, `foto`) VALUES
(9, 'M. Refan Fadillah', '12228419', '12228419', 22, 8, 'Kuningan', '0895333450389', 'IMG-20240811-WA0031.jpg'),
(10, 'Chinatsu', '12228420', '12228420', 22, 8, 'Cirebon', '0895333450389', 'blue box.jpg'),
(11, 'Thony', '12228421', '12228421', 22, 8, 'Pekalongan', '0843437434', ''),
(12, 'Inomata', '12228422', '12228422', 22, 8, 'Cirebon', '089664848', 'ao no hako - manga.jfif'),
(13, 'Farhan', '12228423', '12228423', 22, 8, 'Cirebon', '08943433334', 'farhankebab.webp'),
(14, 'Rusdi', '12228424', '12228424', 22, 8, 'Cirebon', '0894747383', 'masrusdi.webp'),
(15, 'Fadillah', '12228431', '12228431', 12, 8, 'Kuningan', '089344343', 'Kartun Guru Laki Laki Tersenyum Memegang Buku, Kartun Guru, Kartun Guru Mengajar, Kartun Guru Laki Laki PNG Transparan Clipart dan File PSD untuk Unduh Gratis.jfif'),
(50, 'Aidan', '12228401', '12228401', 6, 8, 'Cirebon', '893636272', NULL),
(51, 'Aditya Prabowo Santoso', '12228402', '12228402', 6, 8, 'Cirebon', '893636272', NULL),
(52, 'Andi Supriyadi', '12228403', '12228403', 6, 8, 'Cirebon', '893636272', NULL),
(53, 'Bagus Wahyu Prakoso', '12228404', '12228404', 6, 8, 'Cirebon', '893636272', NULL),
(54, 'Budi Santosa', '12228405', '12228405', 6, 8, 'Cirebon', '893636272', NULL),
(55, 'Cahya Dewi Puspitasari', '12228406', '12228406', 6, 8, 'Cirebon', '893636272', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`),
  ADD UNIQUE KEY `barcode` (`barcode`);

--
-- Indexes for table `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id_jurusan`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `id_buku` (`id_buku`),
  ADD KEY `id_siswa` (`id_siswa`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indexes for table `sekolah`
--
ALTER TABLE `sekolah`
  ADD PRIMARY KEY (`id_sekolah`),
  ADD UNIQUE KEY `email_sekolah` (`email_sekolah`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD UNIQUE KEY `nis` (`nis`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_jurusan` (`id_jurusan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id_jurusan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `sekolah`
--
ALTER TABLE `sekolah`
  MODIFY `id_sekolah` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`),
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`),
  ADD CONSTRAINT `peminjaman_ibfk_3` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`);

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE SET NULL,
  ADD CONSTRAINT `siswa_ibfk_2` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id_jurusan`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
