-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2024 at 01:21 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kantin`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `deskripsi_produk` varchar(255) NOT NULL,
  `harga_produk` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `seller_username` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `nama_produk`, `kategori`, `deskripsi_produk`, `harga_produk`, `stok`, `gambar`, `seller_username`) VALUES
(23, 'Telur Gulung', 'makanan_ringan', 'Sempol Nampol ', 1500, 1000, 'Telur-gulung.jpg', ''),
(24, 'Bakwan', 'makanan_ringan', '', 1000, 40, 'Bakwan.jpg', ''),
(25, 'Es Jeruk', 'minuman', '', 6000, 30, 'es-jeruk.jpg', ''),
(26, 'Bakso', 'makanan_berat', '', 15000, 9, 'Bakso.jpg', ''),
(27, 'Risol', 'makanan_ringan', '', 3000, 200, 'Risol.jpg', ''),
(28, 'Nasi Kuning', 'makanan_berat', '', 10000, 11, 'Nasi-Kuning.jpg', ''),
(29, 'Kopi', 'minuman', '', 10000, 24, 'Kopi.jpg', ''),
(32, 'cakwe', 'makanan_ringan', '', 3000, 20, 'cakwe.jpg', 'seller');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `menu` varchar(255) NOT NULL,
  `ulasan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`id`, `nama`, `menu`, `ulasan`) VALUES
(14, 'M Fathan A', 'Nasi Kuning', 'HMM ENAK'),
(15, 'Sabil Aja', 'Telur Gulung', 'MINYAK SEMUA tapi enak'),
(16, 'Sabil Aja', 'Nasi Kuning', 'ok si'),
(17, 'rayen', 'Kopi', 'keren'),
(18, 'rayen', 'Bakso', 'keren\r\n'),
(19, 'rayen', 'Kopi', 'keren'),
(20, 'rayen', 'Kopi', 'wow'),
(21, 'admin', 'Bakso', 'enaaak coy'),
(22, 'admin', 'Bakso', 'bulat');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role` varchar(70) NOT NULL,
  `balance` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `balance`, `name`, `username`, `email`, `password`) VALUES
(28, 'admin', 9594999, 'admin', 'admin', 'admin@admin.com', '$2y$10$/IMhrTs99bLR//TxbcwyWe1BZoiPIcj2/1flnqr1Qm/0PQT9TcJD2'),
(29, 'user', 57777, 'M Fathan A', 'patan', 'thaaan184@gmail.com', '$2y$10$rResex2MEhvUDqKZPm8yseHmKlzzV3q4bzz7vctBaetcMxaHAyFSe'),
(30, 'user', 0, 'Dwikhi Deandra Purnianto', 'wikihow', 'hkejaze@gmail.com', '$2y$10$adaRK/inpGjgBnNRM3K0CuWQuo/sPdb.u0B/mqmExenBkTdjwCB1G'),
(31, 'user', 5000, 'Sabil Aja', 'sabil', 'SABILSABOL@gmail.com', '$2y$10$Xymb.bymfQ3O05c1Np/J/.uu2Ijw7DLTAakvaF4og9Tc0s7fhxgU2'),
(32, 'user', 25000, 'rayen', 'rayenbejir', 'rayen@gmail.com', '$2y$10$JJKkx1cJpMrRzw.lcibfRefP5GV0/nHP9y42PURWJeiiveoos4tOS'),
(33, 'user', 0, 'fathan', 'test1', 'fathan@gmail.com', '$2y$10$kfSUdRd4YV.Luz7UnRQCfe0.HCXUWprrAP5mcbom36h3Hm6tgrdAm'),
(34, 'seller', 0, 'seller', 'seller', 'seller@seller.com', '$2y$10$P0VzoJFkMZJdDwxFJH5WG.b9l2GmtHBg86zk5rk3LPlO2bCDkMYfu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
