-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2025 at 02:09 PM
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
-- Table structure for table `belian`
--

CREATE TABLE `belian` (
  `id` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `kuantiti` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `belian`
--

INSERT INTO `belian` (`id`, `id_pesanan`, `id_produk`, `kuantiti`) VALUES
(18, 24, 1, 2),
(19, 25, 1, 1),
(20, 26, 2, 1),
(23, 29, 1, 5),
(24, 29, 4, 16);

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
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_phone` varchar(15) NOT NULL,
  `tahap` int(11) NOT NULL DEFAULT 1,
  `searchable` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `password`, `no_phone`, `tahap`, `searchable`) VALUES
(0, 'Guest', '', '', 1, 0),
(1, 'test', '$2y$10$Iwq4fqiwI3v0n3vt4ETOFeRHkPycoFHHGcAVKF3xPFpKZw/l4b3LS', '0123456789', 1, 1),
(21, 'admin', '$2y$10$1o7IcyhanhddKtxPoGW1..0zORzucPlyCQtY1y1/X1HdYZcGDaQlC', '0116664444', 2, 1),
(22, 'Henery', '$2y$10$.Voyd0P.Zg5HkbwouZYkb.4H5RdDg0qgUIClYkMJwjzgXu3.eN0tW', '0128889898', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `id_status` int(11) NOT NULL,
  `no_meja` int(11) NOT NULL,
  `tarikh` date NOT NULL,
  `cara` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id`, `id_pelanggan`, `id_status`, `no_meja`, `tarikh`, `cara`) VALUES
(24, 0, 1, 1, '2025-04-10', 'dine-in'),
(25, 0, 1, 1, '2025-04-10', 'dine-in'),
(26, 1, 1, 1, '2025-04-16', 'dine-in'),
(29, 0, 1, 1, '2025-04-26', 'dine-in');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `detail` text NOT NULL,
  `harga` float NOT NULL,
  `gambar` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `nama`, `id_kategori`, `detail`, `harga`, `gambar`) VALUES
(1, 'Chicken Taco', 1, 'taco yum', 10, '/Order-System-Website/src/assets/produk/1.png'),
(2, 'Spicy Chicken Taco', 1, '', 11, '/Order-System-Website/src/assets/produk/2.png'),
(3, 'Taco Salad', 1, '', 15, '/Order-System-Website/src/assets/produk/3.png'),
(4, 'Tacos de barbacoa', 1, '', 15, '/Order-System-Website/src/assets/produk/4.png'),
(5, 'Wonton', 2, '', 8, '/Order-System-Website/src/assets/produk/5.png'),
(6, 'Spicy Wonton', 2, '', 9, '/Order-System-Website/src/assets/produk/6.png'),
(7, 'Potstickers', 2, '', 10, '/Order-System-Website/src/assets/produk/7.png'),
(8, 'Wonton Mee', 3, '', 10, '/Order-System-Website/src/assets/produk/8.png'),
(9, 'Cheesy Taco Pasta', 3, '', 14, '/Order-System-Website/src/assets/produk/9.png'),
(10, 'Mexican fries', 4, '', 3, '/Order-System-Website/src/assets/produk/10.png'),
(11, 'Tortilla Chips', 4, '', 3.5, '/Order-System-Website/src/assets/produk/11.png'),
(12, 'Popiah', 4, '', 3, '/Order-System-Website/src/assets/produk/12.png'),
(13, 'Bò Bía', 4, '', 5, '/Order-System-Website/src/assets/produk/13.png'),
(14, 'Lemon Tea', 5, '', 4, ''),
(15, 'Coke', 5, '', 2, ''),
(16, '100-plus', 5, '', 2, ''),
(17, 'Sprite', 5, '', 2, ''),
(18, 'Pepsi', 5, '', 2, ''),
(19, 'Chrysanthemum tea', 5, '', 3, ''),
(20, 'Hibiscus tea', 5, '', 4, ''),
(21, 'Water', 5, '', 0.5, '');

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

-- --------------------------------------------------------

--
-- Table structure for table `tahap`
--

CREATE TABLE `tahap` (
  `id` int(11) NOT NULL,
  `tahap` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tahap`
--

INSERT INTO `tahap` (`id`, `tahap`) VALUES
(1, 'user'),
(2, 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `belian`
--
ALTER TABLE `belian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_makanan` (`id_produk`),
  ADD KEY `id_pesanan` (`id_pesanan`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meja`
--
ALTER TABLE `meja`
  ADD PRIMARY KEY (`no_meja`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pelanggan_fk1` (`tahap`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `akaun_id` (`id_pelanggan`),
  ADD KEY `status` (`id_status`),
  ADD KEY `no_meja` (`no_meja`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`id_kategori`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tahap`
--
ALTER TABLE `tahap`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `belian`
--
ALTER TABLE `belian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `meja`
--
ALTER TABLE `meja`
  MODIFY `no_meja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tahap`
--
ALTER TABLE `tahap`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `belian`
--
ALTER TABLE `belian`
  ADD CONSTRAINT `belian_fk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`),
  ADD CONSTRAINT `belian_fk_2` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD CONSTRAINT `pelanggan_fk1` FOREIGN KEY (`tahap`) REFERENCES `tahap` (`id`);

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_fk1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pesanan_fk2` FOREIGN KEY (`id_status`) REFERENCES `status` (`id`),
  ADD CONSTRAINT `pesanan_fk3` FOREIGN KEY (`no_meja`) REFERENCES `meja` (`no_meja`);

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_fk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
