-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2025 at 11:13 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bspsupply_mngt`
--

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `item_num` int(255) NOT NULL,
  `Particular` varchar(255) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Unit` varchar(255) NOT NULL,
  `Date` date NOT NULL DEFAULT current_timestamp(),
  `Div_request` varchar(255) NOT NULL,
  `Total_Item_Requested` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`item_num`, `Particular`, `Quantity`, `Unit`, `Date`, `Div_request`, `Total_Item_Requested`) VALUES
(1, 'Bond Paper, Legal Size', 60, 'Rims', '2025-05-08', 'Finance Division', 10),
(2, 'Bond Paper, Legal Size', 60, 'Rims', '2025-05-08', 'Central Record Office', 5),
(3, 'Bond Paper, Legal Size', 60, 'Rims', '2025-05-08', 'Planning and ICT Unit', 8);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`item_num`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
