-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2024 at 03:15 AM
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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
