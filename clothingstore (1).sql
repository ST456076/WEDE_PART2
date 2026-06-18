-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 03, 2026 at 11:05 PM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clothingstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

DROP TABLE IF EXISTS `tbladmin`;
CREATE TABLE IF NOT EXISTS `tbladmin` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `admin_full_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`admin_id`, `admin_full_name`, `email`) VALUES
(1, 'Main Admin', 'admin@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `tblclothes`
--

DROP TABLE IF EXISTS `tblclothes`;
CREATE TABLE IF NOT EXISTS `tblclothes` (
  `clothes_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`clothes_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tblclothes`
--

INSERT INTO `tblclothes` (`clothes_id`, `name`, `price`) VALUES
(1, 'T-Shirt', 199.99),
(2, 'Jeans', 499.99);

-- --------------------------------------------------------

--
-- Table structure for table `tblorder`
--

DROP TABLE IF EXISTS `tblorder`;
CREATE TABLE IF NOT EXISTS `tblorder` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `clothes_id` int DEFAULT NULL,
  `order_date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `clothes_id` (`clothes_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tblorder`
--

INSERT INTO `tblorder` (`order_id`, `user_id`, `clothes_id`, `order_date`) VALUES
(1, 1, 1, '2026-05-04 00:37:20'),
(2, 2, 2, '2026-05-04 00:37:20');

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

DROP TABLE IF EXISTS `tbluser`;
CREATE TABLE IF NOT EXISTS `tbluser` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`user_id`, `full_name`, `email`, `password`, `created_at`) VALUES
(1, 'John Doe', 'john.doe@example.com', '$2y$10$91Cmpd9wU6ogxSessrcv5eyRMohFBy.D5sEaiaDc6uiZixyzKczXi', '2026-05-04 00:37:20'),
(2, 'Jane Smith', 'jane.smith@example.com', '$2y$10$OmDmwRZHy17ykxebMNBMzOW91Y02VOgNV7rThcF2QUoWZ0mGPg2i2', '2026-05-04 00:37:20'),
(3, 'Mike Johnson', 'mike.j@example.com', '$2y$10$gMDsHOL32mMl1EB9oVJjsu2/GUDgDR.jMnjpIuaXPGnSxOqYIWgFa', '2026-05-04 00:37:20'),
(4, 'Sarah Williams', 'sarah.w@example.com', '$2y$10$19tPl6r96uWyW2Flyh1MHOx5unwRnHzHDQkQDVAeH6zqs1fYjNHjq', '2026-05-04 00:37:20'),
(5, 'David Brown', 'david.b@example.com', '$2y$10$VksFC9kxouUgPLAUVTwnm.R5cGJ..AAcy4mkGnj3oHsJntJc.GdhS', '2026-05-04 00:37:20'),
(6, 'Ntokozo Mashiane', 'abc@gmail.com', '$2y$10$.03oxIkvE3amsJoGNWNMk.OcvsMlR14asgWg0kWAijPkoHfTqGmS6', '2026-05-04 00:55:02');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
