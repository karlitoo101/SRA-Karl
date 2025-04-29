-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2025 at 06:10 PM
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
-- Database: `sradb2`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `sender` varchar(64) NOT NULL,
  `receiver` varchar(64) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('Sent','Read','Unread','') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `sender`, `receiver`, `message`, `created_at`, `status`) VALUES
(0, 'SRA', 'Karlito Mangapot', 'Hello Karlito, we want to inform you that there are some mistakes about your report kindly review you report and change according to the feedback given.', '2025-04-25 16:50:23', 'Read'),
(0, 'SRA', 'Sophia Sugars', 'SRA Hello Karlito, we want to inform you that there are some mistakes about your report kindly review you report and change according to the feedback given.', '2025-04-25 16:50:46', 'Read'),
(0, 'Karlito Mangapot', 'SRA', 'Ok', '2025-04-25 16:51:05', 'Read'),
(0, 'Sophia Sugars', 'SRA', 'Ok po!', '2025-04-25 16:51:08', 'Read'),
(0, 'SRA', 'Sophia Sugars', 'Good morning, Sophia! Your report was already accepted you can now submit a notary copy to our office.', '2025-04-26 01:36:32', 'Read'),
(0, 'Sophia Sugars', 'SRA', 'ok', '2025-04-26 03:21:37', 'Read');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
