-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 04, 2025 at 12:25 PM
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
-- Database: `restorandb`
--

-- --------------------------------------------------------

--
-- Table structure for table `akaun`
--

CREATE TABLE `akaun` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` char(60) NOT NULL,
  `no_phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `akaun`
--

INSERT INTO `akaun` (`id`, `nama`, `password`, `no_phone`) VALUES
(0, 'Guest', '', ''),
(1, 'test', '$2y$10$Iwq4fqiwI3v0n3vt4ETOFeRHkPycoFHHGcAVKF3xPFpKZw/l4b3LS', '0123456789');

-- --------------------------------------------------------

--
-- Table structure for table `belian`
--

CREATE TABLE `belian` (
  `id` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `id_makanan` int(11) NOT NULL,
  `kuantiti` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `belian`
--

INSERT INTO `belian` (`id`, `id_pesanan`, `id_makanan`, `kuantiti`) VALUES
(1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `label` varchar(1) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `label`, `nama`) VALUES
(1, 'A', 'Taco'),
(2, 'B', 'Wonton'),
(3, 'C', 'Mee'),
(4, 'D', 'Makanan Ringan'),
(5, 'E', 'Minuman');

-- --------------------------------------------------------

--
-- Table structure for table `makanan`
--

CREATE TABLE `makanan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `detail` text NOT NULL,
  `harga` int(11) NOT NULL,
  `gambar` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `makanan`
--

INSERT INTO `makanan` (`id`, `nama`, `id_kategori`, `detail`, `harga`, `gambar`) VALUES
(1, 'Chicken Taco', 1, '', 10, '/Order-System-Website/src/assets/user-regular.svg'),
(2, 'Spicy Chicken Taco', 1, '', 0, ''),
(3, 'Taco Salad', 1, '', 0, ''),
(4, 'Tacos de barbacoa', 1, '', 0, ''),
(5, 'Wonton', 2, '', 0, ''),
(6, 'Spicy Wonton', 2, '', 0, ''),
(7, 'Potstickers', 2, '', 0, ''),
(8, 'Wonton Mee', 3, '', 0, ''),
(9, 'Cheesy Taco Pasta', 3, '', 0, ''),
(10, 'Mexican fries', 4, '', 0, ''),
(11, 'Tortilla Chips', 4, '', 0, ''),
(12, 'Popiah', 4, '', 0, ''),
(13, 'Bò Bía', 4, '', 0, ''),
(14, 'Lemon Tea', 5, '', 0, ''),
(15, 'Coke', 5, '', 0, ''),
(16, '100-plus', 5, '', 0, ''),
(17, 'Sprite', 5, '', 0, ''),
(18, 'Pepsi', 5, '', 0, ''),
(19, 'Chrysanthemum tea', 5, '', 0, ''),
(20, 'Hibiscus tea', 5, '', 0, ''),
(21, 'Water', 5, '', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `meja`
--

CREATE TABLE `meja` (
  `no_meja` int(11) NOT NULL,
  `info` text NOT NULL,
  `tersedia` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meja`
--

INSERT INTO `meja` (`no_meja`, `info`, `tersedia`) VALUES
(1, 'blabla', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `akaun_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `no_meja` int(11) NOT NULL,
  `tarikh` date NOT NULL,
  `cara` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id`, `akaun_id`, `status_id`, `no_meja`, `tarikh`, `cara`) VALUES
(1, 1, 1, 1, '2025-02-04', '');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `status`) VALUES
(1, 'open'),
(2, 'preparing'),
(3, 'completed'),
(4, 'cancelled');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akaun`
--
ALTER TABLE `akaun`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `belian`
--
ALTER TABLE `belian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_makanan` (`id_makanan`),
  ADD KEY `id_pesanan` (`id_pesanan`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `makanan`
--
ALTER TABLE `makanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`id_kategori`);

--
-- Indexes for table `meja`
--
ALTER TABLE `meja`
  ADD PRIMARY KEY (`no_meja`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `akaun_id` (`akaun_id`),
  ADD KEY `status` (`status_id`),
  ADD KEY `no_meja` (`no_meja`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akaun`
--
ALTER TABLE `akaun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `belian`
--
ALTER TABLE `belian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `makanan`
--
ALTER TABLE `makanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `meja`
--
ALTER TABLE `meja`
  MODIFY `no_meja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `belian`
--
ALTER TABLE `belian`
  ADD CONSTRAINT `pesanan_fk_1` FOREIGN KEY (`id_makanan`) REFERENCES `makanan` (`id`),
  ADD CONSTRAINT `pesanan_fk_2` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id`);

--
-- Constraints for table `makanan`
--
ALTER TABLE `makanan`
  ADD CONSTRAINT `makanan_fk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
