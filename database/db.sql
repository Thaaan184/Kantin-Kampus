-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2024 at 11:21 AM
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
(33, 'Kopi', 'minuman', 'Enak kayak kopi rasanya', 100000, 3, 'Kopi.jpg', 'seller');

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
(24, 'aku adalah yin', '33', 'enak coy');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `menu_id`, `quantity`, `total_price`, `payment_proof`, `status`, `created_at`) VALUES
(3, 36, 33, 1, 100000, 'qris.png', 'waiting', '2024-06-11 09:18:42');

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
  `password` varchar(255) NOT NULL,
  `qris_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `balance`, `name`, `username`, `email`, `password`, `qris_image`) VALUES
(28, 'admin', 9537999, 'admin', 'admin', 'admin@admin.com', '$2y$10$/IMhrTs99bLR//TxbcwyWe1BZoiPIcj2/1flnqr1Qm/0PQT9TcJD2', NULL),
(29, 'user', 57777, 'M Fathan A', 'patan', 'thaaan184@gmail.com', '$2y$10$rResex2MEhvUDqKZPm8yseHmKlzzV3q4bzz7vctBaetcMxaHAyFSe', NULL),
(30, 'seller', 0, 'Dwikhi Deandra Purnianto', 'wikihow', 'gogogo@gmail.com', '$2y$10$adaRK/inpGjgBnNRM3K0CuWQuo/sPdb.u0B/mqmExenBkTdjwCB1G', NULL),
(31, 'user', 5000, 'Sabil Aja', 'sabil', 'SABILSABOL@gmail.com', '$2y$10$Xymb.bymfQ3O05c1Np/J/.uu2Ijw7DLTAakvaF4og9Tc0s7fhxgU2', NULL),
(32, 'user', 25000, 'rayen', 'rayenbejir', 'rayen@gmail.com', '$2y$10$JJKkx1cJpMrRzw.lcibfRefP5GV0/nHP9y42PURWJeiiveoos4tOS', NULL),
(34, 'seller', 0, 'seller', 'seller', 'seller@seller.com', '$2y$10$P0VzoJFkMZJdDwxFJH5WG.b9l2GmtHBg86zk5rk3LPlO2bCDkMYfu', 'qris.png'),
(36, 'user', 0, 'test1', 'test1', 'test@gmail.com', '$2y$10$LW8f6HNYmRHPU1k2NMp2fe4aDcL.NIPjikAzufRfmFvYoSlBnnYWa', NULL);

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
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `menu_id` (`menu_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
