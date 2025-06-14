-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2025 at 03:36 PM
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
-- Database: `placement_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `logo`, `description`, `website`, `created_at`) VALUES
(1, 'TechCorp', NULL, 'Tech company specializing in software development', NULL, '2025-04-18 06:26:17'),
(2, 'DataSystems Inc', NULL, 'Data analytics and systems integration company', NULL, '2025-04-18 06:26:17'),
(3, 'WebTech Solutions', NULL, 'Web development and design company', NULL, '2025-04-18 06:26:17'),
(4, 'CloudSys Technologies', NULL, 'Cloud infrastructure and DevOps services', NULL, '2025-04-18 06:26:17'),
(5, 'InnovateX', NULL, 'Innovation and technology consulting firm', NULL, '2025-04-18 06:26:17'),
(6, 'GlobalTech', NULL, 'Global technology solutions provider', NULL, '2025-04-18 06:26:17'),
(7, 'Tech Solutions Inc.', NULL, 'IT solutions and services company', NULL, '2025-04-18 06:26:17'),
(8, 'Digital Dynamics', NULL, 'Digital transformation and consulting services', NULL, '2025-04-18 06:26:17'),
(9, 'Cyber Systems', NULL, 'Cybersecurity and network solutions provider', NULL, '2025-04-18 06:26:17'),
(10, 'AI Innovations', NULL, 'Artificial intelligence and machine learning company', NULL, '2025-04-18 06:26:17'),
(11, 'TechNova', NULL, 'Innovative software development firm specializing in mobile applications', 'technova.com', '2025-04-18 06:28:48'),
(12, 'DataMind Analytics', NULL, 'Data science and machine learning solutions provider', 'datamind.io', '2025-04-18 06:28:48'),
(13, 'CloudScale Systems', NULL, 'Cloud infrastructure and scaling solutions', 'cloudscale.tech', '2025-04-18 06:28:48'),
(14, 'SecurityFirst', NULL, 'Cybersecurity solutions and consulting', 'securityfirst.com', '2025-04-18 06:28:48'),
(15, 'MobileGenius', NULL, 'Mobile app development company', 'mobilegenius.app', '2025-04-18 06:28:48'),
(16, 'QuantumComputing', NULL, 'Quantum computing research and development', 'quantumcomputing.tech', '2025-04-18 06:28:48'),
(17, 'AIFuture', NULL, 'Artificial intelligence and machine learning firm', 'aifuture.ai', '2025-04-18 06:28:48'),
(18, 'WebDev Masters', NULL, 'Web development and design agency', 'webdevmasters.com', '2025-04-18 06:28:48'),
(19, 'BlockchainSolutions', NULL, 'Blockchain technology development', 'blockchainsolutions.io', '2025-04-18 06:28:48'),
(20, 'IoTInnovate', NULL, 'Internet of Things solutions provider', 'iotinnovate.tech', '2025-04-18 06:28:48'),
(21, 'TechCorp', NULL, 'Tech company specializing in software development', NULL, '2025-04-21 18:17:54'),
(22, 'DataSystems Inc', NULL, 'Data analytics and systems integration company', NULL, '2025-04-21 18:17:54'),
(23, 'WebTech Solutions', NULL, 'Web development and design company', NULL, '2025-04-21 18:17:54'),
(24, 'CloudSys Technologies', NULL, 'Cloud infrastructure and DevOps services', NULL, '2025-04-21 18:17:54'),
(25, 'InnovateX', NULL, 'Innovation and technology consulting firm', NULL, '2025-04-21 18:17:54'),
(26, 'GlobalTech', NULL, 'Global technology solutions provider', NULL, '2025-04-21 18:17:54'),
(27, 'Tech Solutions Inc.', NULL, 'IT solutions and services company', NULL, '2025-04-21 18:17:54'),
(28, 'Digital Dynamics', NULL, 'Digital transformation and consulting services', NULL, '2025-04-21 18:17:54'),
(29, 'Cyber Systems', NULL, 'Cybersecurity and network solutions provider', NULL, '2025-04-21 18:17:54'),
(30, 'AI Innovations', NULL, 'Artificial intelligence and machine learning company', NULL, '2025-04-21 18:17:54'),
(31, 'TechCorp', NULL, 'Tech company specializing in software development', NULL, '2025-04-21 18:18:43'),
(32, 'DataSystems Inc', NULL, 'Data analytics and systems integration company', NULL, '2025-04-21 18:18:43'),
(33, 'WebTech Solutions', NULL, 'Web development and design company', NULL, '2025-04-21 18:18:43'),
(34, 'CloudSys Technologies', NULL, 'Cloud infrastructure and DevOps services', NULL, '2025-04-21 18:18:43'),
(35, 'InnovateX', NULL, 'Innovation and technology consulting firm', NULL, '2025-04-21 18:18:43'),
(36, 'GlobalTech', NULL, 'Global technology solutions provider', NULL, '2025-04-21 18:18:43'),
(37, 'Tech Solutions Inc.', NULL, 'IT solutions and services company', NULL, '2025-04-21 18:18:43'),
(38, 'Digital Dynamics', NULL, 'Digital transformation and consulting services', NULL, '2025-04-21 18:18:43'),
(39, 'Cyber Systems', NULL, 'Cybersecurity and network solutions provider', NULL, '2025-04-21 18:18:43'),
(40, 'AI Innovations', NULL, 'Artificial intelligence and machine learning company', NULL, '2025-04-21 18:18:43'),
(41, 'TechCorp', NULL, 'Tech company specializing in software development', NULL, '2025-04-21 18:18:45'),
(42, 'DataSystems Inc', NULL, 'Data analytics and systems integration company', NULL, '2025-04-21 18:18:45'),
(43, 'WebTech Solutions', NULL, 'Web development and design company', NULL, '2025-04-21 18:18:45'),
(44, 'CloudSys Technologies', NULL, 'Cloud infrastructure and DevOps services', NULL, '2025-04-21 18:18:45'),
(45, 'InnovateX', NULL, 'Innovation and technology consulting firm', NULL, '2025-04-21 18:18:45'),
(46, 'GlobalTech', NULL, 'Global technology solutions provider', NULL, '2025-04-21 18:18:45'),
(47, 'Tech Solutions Inc.', NULL, 'IT solutions and services company', NULL, '2025-04-21 18:18:45'),
(48, 'Digital Dynamics', NULL, 'Digital transformation and consulting services', NULL, '2025-04-21 18:18:45'),
(49, 'Cyber Systems', NULL, 'Cybersecurity and network solutions provider', NULL, '2025-04-21 18:18:45'),
(50, 'AI Innovations', NULL, 'Artificial intelligence and machine learning company', NULL, '2025-04-21 18:18:45'),
(51, 'TechCorp', NULL, 'Tech company specializing in software development', NULL, '2025-04-21 18:18:49'),
(52, 'DataSystems Inc', NULL, 'Data analytics and systems integration company', NULL, '2025-04-21 18:18:49'),
(53, 'WebTech Solutions', NULL, 'Web development and design company', NULL, '2025-04-21 18:18:49'),
(54, 'CloudSys Technologies', NULL, 'Cloud infrastructure and DevOps services', NULL, '2025-04-21 18:18:49'),
(55, 'InnovateX', NULL, 'Innovation and technology consulting firm', NULL, '2025-04-21 18:18:49'),
(56, 'GlobalTech', NULL, 'Global technology solutions provider', NULL, '2025-04-21 18:18:49'),
(57, 'Tech Solutions Inc.', NULL, 'IT solutions and services company', NULL, '2025-04-21 18:18:49'),
(58, 'Digital Dynamics', NULL, 'Digital transformation and consulting services', NULL, '2025-04-21 18:18:49'),
(59, 'Cyber Systems', NULL, 'Cybersecurity and network solutions provider', NULL, '2025-04-21 18:18:49'),
(60, 'AI Innovations', NULL, 'Artificial intelligence and machine learning company', NULL, '2025-04-21 18:18:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
