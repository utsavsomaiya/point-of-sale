-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 07, 2022 at 07:57 PM
-- Server version: 8.0.28-0ubuntu0.20.04.3
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `point-of-sale`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `name` varchar(120) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`) VALUES
(1, 'Utsav', '123'),
(2, 'Jinal', '123');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int NOT NULL,
  `name` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(57, 'Accesories');

-- --------------------------------------------------------

--
-- Table structure for table `discount`
--

CREATE TABLE `discount` (
  `id` int NOT NULL,
  `name` varchar(120) NOT NULL,
  `type` int NOT NULL,
  `digit` int NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `discount`
--

INSERT INTO `discount` (`id`, `name`, `type`, `digit`, `status`) VALUES
(17, 'abc', 2, 15, 2),
(33, 'xyz', 2, 134, 2),
(34, 'pqr', 1, 12, 1),
(37, 'ww', 2, 1, 1),
(38, 'qw', 2, 12222, 2),
(39, 'hh', 1, 11, 2);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int NOT NULL,
  `name` varchar(120) NOT NULL,
  `price` varchar(120) NOT NULL,
  `category_id` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tax` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `stock` int NOT NULL,
  `image` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `price`, `category_id`, `tax`, `stock`, `image`) VALUES
(75, 'Headphone', '255', '57', '10', 3, '6.jpg'),
(76, 'Shoes', '26', '57', '20', 12, '4.jpg'),
(77, 'Watch', '35', '57', '15', 20, '2.jpg'),
(78, 'Nick Shoes', '60', '57', '20', 34, '3.jpg'),
(81, 'Neckband', '89', '57', '10', 12, '1.jpg'),
(84, 'Mouse', '16', '57', '15', 11, '1.jpg'),
(85, 'Jim-Jam Treat ', '10', '57', '5', 5, '5.jpg'),
(86, 'Mouse.', '5', '57', '20', 0, '2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int NOT NULL,
  `subtotal` int NOT NULL,
  `total_tax` double NOT NULL,
  `discount_id` int DEFAULT NULL,
  `discount` double NOT NULL,
  `total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `subtotal`, `total_tax`, `discount_id`, `discount`, `total`) VALUES
(114, 154, 9, NULL, 100, 63),
(115, 154, 9, NULL, 100, 63),
(116, 154, 9, NULL, 100, 63),
(117, 154, 9, NULL, 100, 63),
(118, 154, 9, NULL, 100, 63),
(119, 154, 9.05, NULL, 100, 63),
(120, 154, 9.05, NULL, 100, 63.047),
(121, 260, 22.46, NULL, 100, 182.4615),
(122, 530, 44.2, 17, 79.5, 494.7),
(123, 530, 44.72, 19, 74.2, 500.52),
(124, 2100, 207, 0, 0, 2307),
(125, 255, 24, 17, 15, 264),
(126, 540, 51.04, 17, 15, 576.0415),
(127, 530, 38.85, 33, 134, 434.853),
(128, 265, 12.85, 33, 134, 143.853),
(129, 765, 65.03, 17, 114.75, 715.275),
(130, 765, 65.03, 17, 114.75, 715.275),
(131, 255, 21.68, 17, 38.25, 238.425),
(132, 255, 21.68, 17, 38.25, 238.425),
(133, 510, -17.34, 33, 683.4, -190.74),
(134, 255, 21.68, 17, 38.25, 238.425),
(135, 255, 24, 17, 15, 264),
(136, 255, 22.7, 39, 28.05, 249.645);

-- --------------------------------------------------------

--
-- Table structure for table `sales_item`
--

CREATE TABLE `sales_item` (
  `id` int NOT NULL,
  `sales_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_price` int NOT NULL,
  `product_quantity` int NOT NULL,
  `product_total_price` int NOT NULL,
  `product_discount_id` int DEFAULT NULL,
  `product_discount` double NOT NULL,
  `product_tax_percentage` int NOT NULL,
  `product_taxable_price` double NOT NULL,
  `product_tax_amount` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales_item`
--

INSERT INTO `sales_item` (`id`, `sales_id`, `product_id`, `product_price`, `product_quantity`, `product_total_price`, `product_discount_id`, `product_discount`, `product_tax_percentage`, `product_taxable_price`, `product_tax_amount`) VALUES
(227, 114, 75, 25, 2, 50, NULL, 32, 10, 18, 2),
(228, 114, 76, 26, 4, 104, NULL, 68, 20, 36, 7),
(229, 115, 75, 25, 2, 50, NULL, 32.467532467532, 10, 17.532467532468, 1.7532467532468),
(230, 115, 76, 26, 4, 104, NULL, 67.532467532468, 20, 36.467532467532, 7.2935064935065),
(231, 116, 75, 25, 2, 50, NULL, 32.47, 10, 17.53, 1.753),
(232, 116, 76, 26, 4, 104, NULL, 67.53, 20, 36.47, 7.294),
(233, 117, 75, 25, 2, 50, NULL, 32.47, 10, 17.53, 1.753),
(234, 117, 76, 26, 4, 104, NULL, 67.53, 20, 36.47, 7.294),
(235, 118, 75, 25, 2, 50, NULL, 32.47, 10, 17.53, 1.753),
(236, 118, 76, 26, 4, 104, NULL, 67.53, 20, 36.47, 7.294),
(237, 119, 75, 25, 2, 50, NULL, 32.47, 10, 17.53, 1.753),
(238, 119, 76, 26, 4, 104, NULL, 67.53, 20, 36.47, 7.294),
(239, 120, 75, 25, 2, 50, NULL, 32.47, 10, 17.53, 1.753),
(240, 120, 76, 26, 4, 104, NULL, 67.53, 20, 36.47, 7.294),
(241, 121, 75, 25, 2, 50, NULL, 19.23, 10, 30.77, 3.077),
(242, 121, 77, 35, 6, 210, NULL, 80.77, 15, 129.23, 19.3845),
(243, 122, 75, 255, 2, 510, 17, 76.5, 10, 433.5, 43.35),
(244, 122, 85, 10, 2, 20, 17, 3, 5, 17, 0.85),
(245, 123, 75, 255, 2, 510, 19, 71.4, 10, 438.6, 43.86),
(246, 123, 85, 10, 2, 20, 19, 2.8, 5, 17.2, 0.86),
(247, 124, 75, 255, 8, 2040, 0, 0, 10, 2040, 204),
(248, 124, 85, 10, 6, 60, 0, 0, 5, 60, 3),
(249, 125, 75, 255, 1, 255, 17, 15, 10, 240, 24),
(250, 126, 75, 255, 2, 510, 17, 14.17, 10, 495.83, 49.583),
(251, 126, 85, 10, 3, 30, 17, 0.83, 5, 29.17, 1.4585),
(252, 127, 75, 255, 2, 510, 33, 128.94, 10, 381.06, 38.106),
(253, 127, 85, 10, 2, 20, 33, 5.06, 5, 14.94, 0.747),
(254, 128, 75, 255, 1, 255, 33, 128.94, 10, 126.06, 12.606),
(255, 129, 75, 255, 3, 765, 17, 114.75, 10, 650.25, 65.025),
(256, 130, 75, 255, 3, 765, 17, 114.75, 10, 650.25, 65.025),
(257, 131, 75, 255, 1, 255, 17, 38.25, 10, 216.75, 21.675),
(258, 132, 75, 255, 1, 255, 17, 38.25, 10, 216.75, 21.675),
(259, 133, 75, 255, 2, 510, 33, 683.4, 10, -173.4, -17.34),
(260, 134, 75, 255, 1, 255, 17, 38.25, 10, 216.75, 21.675),
(261, 135, 75, 255, 1, 255, 17, 15, 10, 240, 24),
(262, 136, 75, 255, 1, 255, 39, 28.05, 10, 226.95, 22.695);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_item`
--
ALTER TABLE `sales_item`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `discount`
--
ALTER TABLE `discount`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `sales_item`
--
ALTER TABLE `sales_item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=263;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
