-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2025 at 04:47 PM
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
-- Database: `sradb`
--

-- --------------------------------------------------------

--
-- Table structure for table `traders`
--

CREATE TABLE `traders` (
  `traderID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `traderName` varchar(255) NOT NULL,
  `contactNumber` varchar(20) NOT NULL,
  `telephoneNumber` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `region` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `traders`
--

INSERT INTO `traders` (`traderID`, `userID`, `traderName`, `contactNumber`, `telephoneNumber`, `address`, `region`) VALUES
(1, 7, 'example', '(+63)2376472263', '123487654', 'anicayhbjsaeadwasdawd', 'NCR'),
(2, 8, 'example', '(+63)2376472263', '125346576', '53241313524635', 'Region VI'),
(3, 13, 'Sophia Sugars', '(+63)9987238761', '108654764', '82A Masambong Malac', 'NCR');

-- --------------------------------------------------------

--
-- Table structure for table `trader_reports`
--

CREATE TABLE `trader_reports` (
  `reportID` int(11) NOT NULL,
  `traderID` int(11) NOT NULL,
  `crop_year` varchar(9) NOT NULL,
  `category` enum('Sugar','Molasses','Muscovado','Fructose') DEFAULT NULL,
  `prev_crop_stock_balance` varchar(50) DEFAULT NULL,
  `month` enum('September','October','November','December','January','February','March','April','May','June','July','August','TOTAL') DEFAULT NULL,
  `importation` varchar(50) DEFAULT NULL,
  `local_mills` varchar(50) DEFAULT NULL,
  `local_traders` varchar(50) DEFAULT NULL,
  `auction_boc` varchar(50) DEFAULT NULL,
  `other_sources` text DEFAULT NULL,
  `own_use` varchar(50) DEFAULT NULL,
  `sale_domestic` varchar(50) DEFAULT NULL,
  `export_us` varchar(50) DEFAULT NULL,
  `export_world` varchar(50) DEFAULT NULL,
  `clients_info` text DEFAULT NULL,
  `stock_balance` varchar(50) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `certified` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(128) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `reset_token_hash` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `name`, `email`, `password`, `is_admin`, `reset_token_hash`, `reset_token_expires_at`) VALUES
(1, 'Admin', 'mockadmin@test.com', '$2y$10$uKx6lwB9QoAADMDfbQVI/e4c5HMUQ2h9SpcP0h8KY8z7qkRX5SQVa', 1, NULL, NULL),
(2, 'Admin', 'sadfas@1123', '$2y$10$vtVwh1KirZJU66vvJSdmwe6tC5kmh7hrES8AHNX5RIbr0mUbli2oy', 1, NULL, NULL),
(3, 'Admin', 'usermock@test.com', '$2y$10$b/6TSwb7ymJn5LtjtlBPd.sy3PyvEDR2fCBqx/.J/DHhiFkWmfpMi', 0, NULL, NULL),
(4, 'Admin', 'asd@asss', '$2y$10$4MCNG/Pvbn6ZjYaUe1Y3m.pIzYITFTzQ06jqJrJCMswyt3Zp1MAzm', 0, NULL, NULL),
(5, 'Admin', 'nini@admin.com', '$2y$10$25u.1oWtlCUc8xb6CdccyeSNjqpPNBYopGRL7KVFFu2G5uAl0ydHW', 1, NULL, NULL),
(6, 'Admin', '1234@gasd', '$2y$10$1cEtshTiNnfdMzlaWrucYuvWC6eH7RkIVvJVks5.ks5k15QgH5lWq', 1, NULL, NULL),
(7, 'example', 'asd@asd', '$2y$10$JBQ4REGN5I8Cmm3eCm.phufPhoAwhtUZ9XfxL0TWPHKprXDpb0mOy', 0, NULL, NULL),
(8, 'example', 'asd@asds', '$2y$10$Ukcq03BDeNbB5ylPeaLGdOnnb2FBu/GT25SXAP249TlhQ2WF10kfe', 0, NULL, NULL),
(9, 'jerald', 'jerald@gmail.com', '$2y$10$rZXb9Dkhrth4SRNQ7Du7vuZcIi59UTHO7gVfy9ZitmDV39h.tQ9qK', 1, NULL, NULL),
(10, 'Jerald', 'jerald@gmail.c', '$2y$10$Li/ClG9NpvVwkNyiqltlC.PBqG2t7KzxKEJV1mMiMQ/4JACOgUZhK', 1, NULL, NULL),
(11, 'Jerald', 'jerald@gmail.m', '$2y$10$a7RIGI8rcdpTOcTbBtE1GOCR68EK8aENUFjgQBfqsb5LEOm41gU2u', 1, NULL, NULL),
(12, 'Jerald', 'lopera@gmail.c', '$2y$10$zQGfRFHVMb9O4PgtprE0H.UdG5MK3XEwNI3am9biR4csB6rqKoI1C', 1, NULL, NULL),
(13, 'Sophia Sugars', 'sopiyanotnot@gmail.com', '$2y$10$LElmMlkQ0HLCEgnRgApF/.ujpipVuEfxsIapu6JSMmfsEJ93oEprC', 0, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `traders`
--
ALTER TABLE `traders`
  ADD PRIMARY KEY (`traderID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `trader_reports`
--
ALTER TABLE `trader_reports`
  ADD PRIMARY KEY (`reportID`),
  ADD KEY `traderID` (`traderID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `reset_token_hash` (`reset_token_hash`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `traders`
--
ALTER TABLE `traders`
  MODIFY `traderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `trader_reports`
--
ALTER TABLE `trader_reports`
  MODIFY `reportID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `traders`
--
ALTER TABLE `traders`
  ADD CONSTRAINT `traders_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `trader_reports`
--
ALTER TABLE `trader_reports`
  ADD CONSTRAINT `trader_reports_ibfk_1` FOREIGN KEY (`traderID`) REFERENCES `traders` (`traderID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
