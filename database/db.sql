-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2024 at 08:44 AM
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
(33, 'Kopi', 'minuman', 'Enak kayak kopi rasanya', 100000, 0, 'Kopi.jpg', 'seller'),
(34, 'Bakso', 'makanan_berat', 'Bola Daging Dengan Kuah Rempah Khas', 15000, 1, 'Bakso.jpg', 'seller'),
(36, 'cakwe', 'makanan_ringan', '', 1200, 19, 'cakwe.jpg', 'seller'),
(37, 'Bakwan', 'makanan_ringan', '', 5000, 220, 'Bakwan.jpg', 'seller'),
(38, 'Es Jeruk', 'minuman', '', 5000, 100, 'es-jeruk.jpg', 'wikihow');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `report_text` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `transaction_id`, `report_text`, `status`, `created_at`) VALUES
(2, 36, 26, 'penjual aneh banget ', 'pending', '2024-06-13 00:51:25');

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
(94, 36, 36, 1, 1200, 'buktiqris.png', 'finished', '2024-06-14 06:36:29'),
(95, 36, 37, 2, 10000, 'buktiqris.png', 'finished', '2024-06-14 06:37:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role` varchar(70) NOT NULL,
  `name` varchar(40) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `qris_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `name`, `username`, `email`, `password`, `qris_image`) VALUES
(28, 'admin', 'admin', 'admin', 'admin@admin.com', '$2y$10$/IMhrTs99bLR//TxbcwyWe1BZoiPIcj2/1flnqr1Qm/0PQT9TcJD2', NULL),
(29, 'user', 'M Fathan A', 'patan', 'thaaan184@gmail.com', '$2y$10$rResex2MEhvUDqKZPm8yseHmKlzzV3q4bzz7vctBaetcMxaHAyFSe', NULL),
(30, 'seller', 'Dwikhi Deandra Purnianto', 'wikihow', 'gogogo@gmail.com', '$2y$10$adaRK/inpGjgBnNRM3K0CuWQuo/sPdb.u0B/mqmExenBkTdjwCB1G', NULL),
(31, 'user', 'Sabil Aja', 'sabil', 'SABILSABOL@gmail.com', '$2y$10$Xymb.bymfQ3O05c1Np/J/.uu2Ijw7DLTAakvaF4og9Tc0s7fhxgU2', NULL),
(32, 'user', 'rayen', 'rayenbejir', 'rayen@gmail.com', '$2y$10$JJKkx1cJpMrRzw.lcibfRefP5GV0/nHP9y42PURWJeiiveoos4tOS', NULL),
(34, 'seller', 'seller', 'seller', 'seller@seller.com', '$2y$10$P0VzoJFkMZJdDwxFJH5WG.b9l2GmtHBg86zk5rk3LPlO2bCDkMYfu', 'Seller1.png'),
(36, 'user', 'test1', 'test1', 'test@gmail.com', '$2y$10$LW8f6HNYmRHPU1k2NMp2fe4aDcL.NIPjikAzufRfmFvYoSlBnnYWa', NULL),
(37, 'user', 'test2', 'test2', 'test2@gmail.com', '$2y$10$IBi02fhOpXVmHHkeQQ87euO1i4vpSkSEr7iOIDq.h6aOpVndVqQcG', NULL),
(38, 'user', 'nabil', 'nabil', 'nabil@gmail.com', '$2y$10$WgR3eYchM.b1RWEVQnK6depEod4b1cozHp5yaNW/0UUserQLfpqIu', NULL),
(39, 'user', 'edwin', 'edwin', 'edwin@gmail.com', '$2y$10$xdveT8e5hG64zWjf25yprOW981k/VBkdCoXrincFn8zZ8qVUR/RgK', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

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
