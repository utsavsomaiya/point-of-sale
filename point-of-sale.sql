-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 30, 2022 at 05:27 PM
-- Server version: 8.0.28-0ubuntu0.20.04.3
-- PHP Version: 7.4.29

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
(2, 'Jinal', '123'),
(3, 'Avani', '123');

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
(57, 'Accesories'),
(61, 'Device'),
(62, 'Avani');

-- --------------------------------------------------------

--
-- Table structure for table `discount`
--

CREATE TABLE `discount` (
  `id` int NOT NULL,
  `name` varchar(120) NOT NULL,
  `type` int DEFAULT NULL,
  `status` int NOT NULL,
  `category` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `discount`
--

INSERT INTO `discount` (`id`, `name`, `type`, `status`, `category`) VALUES
(76, 'New Year Offer', NULL, 2, 2),
(110, 'Kirana Store Offer', 1, 2, 1),
(127, 'Grand Sale', NULL, 2, 2),
(130, 'Somaiya Saheb', 2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `discount_tier`
--

CREATE TABLE `discount_tier` (
  `tier_id` int NOT NULL,
  `discount_id` int NOT NULL,
  `minimum_spend_amount` int NOT NULL,
  `discount_digit` int DEFAULT NULL,
  `discount_product` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `discount_tier`
--

INSERT INTO `discount_tier` (`tier_id`, `discount_id`, `minimum_spend_amount`, `discount_digit`, `discount_product`) VALUES
(64, 76, 12, NULL, 'Nick Shoes'),
(177, 110, 234, 2, NULL),
(185, 110, 122, 12, NULL),
(223, 110, 155, 99, NULL),
(224, 127, 1400, NULL, 'Nick Shoes'),
(225, 127, 150000, NULL, 'Headphone'),
(226, 127, 1666666, NULL, 'Watch'),
(229, 130, 12, 1200, NULL),
(230, 130, 13, 1300, NULL),
(231, 130, 14, 1400, NULL);

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
(75, 'Headphone', '255', '57', '10', 7, '6.jpg'),
(77, 'Watch', '35', '57', '15', 42, '2.jpg'),
(78, 'Nick Shoes', '60', '57', '20', 109, '3.jpg'),
(81, 'Neckband', '89', '57', '10', 43, '1.jpg'),
(84, 'Mouse', '12', '57', '15', 37, '1.jpg'),
(85, 'Jim-Jam Treat ', '10', '61', '5', 184, '5.jpg'),
(144, 'Utsav', '11', '57', '10', 40, 'black&text=Utsav Somaiya.gif'),
(146, 'iphone x', '5400', '61', '10', 7, '6.jpeg'),
(147, 'iphone 11', '9000', '61', '15', 88, '5.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int NOT NULL,
  `subtotal` int NOT NULL,
  `total_tax` double NOT NULL,
  `total_discount` double NOT NULL,
  `discount_id` int DEFAULT NULL,
  `discount_tier_id` int DEFAULT NULL,
  `discount_category` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `subtotal`, `total_tax`, `total_discount`, `discount_id`, `discount_tier_id`, `discount_category`, `total`) VALUES
(248, 38624, 5111.778, 772.48, 110, 177, 'price', 42963.298),
(249, 29370, 3843, 60, 76, 64, 'gift', 33153),
(257, 10, 0.5, 0, NULL, NULL, NULL, 10.5),
(258, 11, 1.1, 0, NULL, NULL, NULL, 12.1),
(259, 14850, 1942.3, 60, 76, 64, 'gift', 16732.3),
(260, 20593, 2463.279, 411.86, 110, 177, 'price', 22644.419),
(261, 34611, 43.6245, 34264.89, 110, 223, 'price', 389.7345);

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
  `product_discount_tier_id` int DEFAULT NULL,
  `product_discount` double DEFAULT NULL,
  `product_tax_percentage` int NOT NULL,
  `product_taxable_price` double NOT NULL,
  `product_tax_amount` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales_item`
--

INSERT INTO `sales_item` (`id`, `sales_id`, `product_id`, `product_price`, `product_quantity`, `product_total_price`, `product_discount_id`, `product_discount_tier_id`, `product_discount`, `product_tax_percentage`, `product_taxable_price`, `product_tax_amount`) VALUES
(352, 248, 75, 255, 2, 510, 110, 177, 10.2, 10, 499.8, 49.98),
(353, 248, 147, 9000, 3, 27000, 110, 177, 540, 15, 26460, 3969),
(354, 248, 146, 5400, 2, 10800, 110, 177, 216, 10, 10584, 1058.4),
(355, 248, 81, 89, 2, 178, 110, 177, 3.56, 10, 174.44, 17.444),
(356, 248, 84, 12, 2, 24, 110, 177, 0.48, 15, 23.52, 3.528),
(357, 248, 85, 10, 2, 20, 110, 177, 0.4, 5, 19.6, 0.98),
(358, 248, 144, 11, 2, 22, 110, 177, 0.44, 10, 21.56, 2.156),
(359, 248, 77, 35, 2, 70, 110, 177, 1.4, 15, 68.6, 10.29),
(360, 249, 75, 255, 2, 510, 76, 64, 0, 10, 510, 51),
(361, 249, 147, 9000, 2, 18000, 76, 64, 0, 15, 18000, 2700),
(362, 249, 146, 5400, 2, 10800, 76, 64, 0, 10, 10800, 1080),
(363, 249, 78, 60, 1, 60, 76, 64, 0, 20, 60, 12),
(364, 249, 78, 60, 1, 60, 76, 64, 60, 20, 0, 0),
(366, 257, 85, 10, 1, 10, 0, NULL, 0, 5, 10, 0.5),
(367, 258, 144, 11, 1, 11, 0, NULL, 0, 10, 11, 1.1),
(368, 259, 84, 12, 3, 36, 76, 64, 0, 15, 36, 5.4),
(369, 259, 146, 5400, 1, 5400, 76, 64, 0, 10, 5400, 540),
(370, 259, 147, 9000, 1, 9000, 76, 64, 0, 15, 9000, 1350),
(371, 259, 75, 255, 1, 255, 76, 64, 0, 10, 255, 25.5),
(372, 259, 85, 10, 1, 10, 76, 64, 0, 5, 10, 0.5),
(373, 259, 78, 60, 1, 60, 76, 64, 0, 20, 60, 12),
(374, 259, 81, 89, 1, 89, 76, 64, 0, 10, 89, 8.9),
(375, 259, 78, 60, 1, 60, 76, 64, 60, 20, 0, 0),
(376, 260, 146, 5400, 2, 10800, 110, 177, 216, 10, 10584, 1058.4),
(377, 260, 81, 89, 2, 178, 110, 177, 3.56, 10, 174.44, 17.444),
(378, 260, 77, 35, 1, 35, 110, 177, 0.7, 15, 34.3, 5.145),
(379, 260, 84, 12, 5, 60, 110, 177, 1.2, 15, 58.8, 8.82),
(380, 260, 147, 9000, 1, 9000, 110, 177, 180, 15, 8820, 1323),
(381, 260, 75, 255, 2, 510, 110, 177, 10.2, 10, 499.8, 49.98),
(382, 260, 85, 10, 1, 10, 110, 177, 0.2, 5, 9.8, 0.49),
(383, 261, 146, 5400, 3, 16200, 110, 223, 16038, 10, 162, 16.2),
(384, 261, 147, 9000, 2, 18000, 110, 223, 17820, 15, 180, 27),
(385, 261, 81, 89, 1, 89, 110, 223, 88.11, 10, 0.89, 0.089),
(386, 261, 85, 10, 2, 20, 110, 223, 19.8, 5, 0.2, 0.01),
(387, 261, 75, 255, 1, 255, 110, 223, 252.45, 10, 2.55, 0.255),
(388, 261, 84, 12, 1, 12, 110, 223, 11.88, 15, 0.12, 0.018),
(389, 261, 77, 35, 1, 35, 110, 223, 34.65, 15, 0.35, 0.0525);

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
-- Indexes for table `discount_tier`
--
ALTER TABLE `discount_tier`
  ADD PRIMARY KEY (`tier_id`);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `discount`
--
ALTER TABLE `discount`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `discount_tier`
--
ALTER TABLE `discount_tier`
  MODIFY `tier_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=235;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=262;

--
-- AUTO_INCREMENT for table `sales_item`
--
ALTER TABLE `sales_item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=390;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;