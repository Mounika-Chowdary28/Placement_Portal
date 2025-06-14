-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2025 at 03:41 PM
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
-- Table structure for table `certifications`
--

CREATE TABLE `certifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `issuer` varchar(100) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `credential_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certifications`
--

INSERT INTO `certifications` (`id`, `user_id`, `name`, `issuer`, `issue_date`, `expiry_date`, `credential_url`, `created_at`) VALUES
(1, 1, 'MongoDB Certified Developer', 'MongoDB', '2022-09-15', '2025-09-15', 'mongodb.com/certification/12345', '2025-04-18 06:26:20'),
(2, 2, 'Certified Ethical Hacker', 'EC-Council', '2022-10-05', '2025-10-05', 'eccouncil.org/certification/12345', '2025-04-18 06:26:20'),
(3, 3, 'Google Cloud Associate Engineer', 'Google Cloud', '2022-02-20', '2025-02-20', 'google.com/certification/12345', '2025-04-18 06:26:20'),
(4, 3, 'Microsoft Certified: Azure Developer', 'Microsoft', '2022-03-10', '2025-03-10', 'microsoft.com/certification/12345', '2025-04-18 06:26:20'),
(5, 3, 'MongoDB Certified Developer', 'MongoDB', '2022-09-15', '2025-09-15', 'mongodb.com/certification/12345', '2025-04-18 06:26:20'),
(6, 4, 'Cisco Certified Network Associate', 'Cisco', '2022-08-30', '2025-08-30', 'cisco.com/certification/12345', '2025-04-18 06:26:20'),
(7, 5, 'MongoDB Certified Developer', 'MongoDB', '2022-09-15', '2025-09-15', 'mongodb.com/certification/12345', '2025-04-18 06:26:20'),
(8, 5, 'Google Cloud Associate Engineer', 'Google Cloud', '2022-02-20', '2025-02-20', 'google.com/certification/12345', '2025-04-18 06:26:20'),
(9, 5, 'Oracle Certified Professional: Java SE', 'Oracle', '2022-04-05', '2025-04-05', 'oracle.com/certification/12345', '2025-04-18 06:26:20'),
(10, 6, 'AWS Certified Solutions Architect', 'Amazon Web Services', '2022-01-15', '2025-01-15', 'aws.com/certification/12345', '2025-04-18 06:26:20'),
(11, 6, 'Certified Ethical Hacker', 'EC-Council', '2022-10-05', '2025-10-05', 'eccouncil.org/certification/12345', '2025-04-18 06:26:20'),
(12, 6, 'Microsoft Certified: Azure Developer', 'Microsoft', '2022-03-10', '2025-03-10', 'microsoft.com/certification/12345', '2025-04-18 06:26:20'),
(13, 7, 'AWS Certified Solutions Architect', 'Amazon Web Services', '2022-01-15', '2025-01-15', 'aws.com/certification/12345', '2025-04-18 06:26:20'),
(14, 7, 'Certified Scrum Master', 'Scrum Alliance', '2022-06-18', '2024-06-18', 'scrumalliance.org/certification/12345', '2025-04-18 06:26:20'),
(15, 7, 'MongoDB Certified Developer', 'MongoDB', '2022-09-15', '2025-09-15', 'mongodb.com/certification/12345', '2025-04-18 06:26:20'),
(16, 8, 'Certified Kubernetes Administrator', 'Cloud Native Computing Foundation', '2022-05-12', '2025-05-12', 'cncf.io/certification/12345', '2025-04-18 06:26:20'),
(17, 8, 'MongoDB Certified Developer', 'MongoDB', '2022-09-15', '2025-09-15', 'mongodb.com/certification/12345', '2025-04-18 06:26:20'),
(18, 9, 'CompTIA Security+', 'CompTIA', '2022-07-22', '2025-07-22', 'comptia.org/certification/12345', '2025-04-18 06:26:20'),
(19, 9, 'Certified Kubernetes Administrator', 'Cloud Native Computing Foundation', '2022-05-12', '2025-05-12', 'cncf.io/certification/12345', '2025-04-18 06:26:20'),
(20, 10, 'Certified Kubernetes Administrator', 'Cloud Native Computing Foundation', '2022-05-12', '2025-05-12', 'cncf.io/certification/12345', '2025-04-18 06:26:20'),
(21, 10, 'MongoDB Certified Developer', 'MongoDB', '2022-09-15', '2025-09-15', 'mongodb.com/certification/12345', '2025-04-18 06:26:20'),
(22, 11, 'Certified Scrum Master', 'Scrum Alliance', '2022-06-18', '2024-06-18', 'scrumalliance.org/certification/12345', '2025-04-18 06:26:20'),
(23, 12, 'Certified Kubernetes Administrator', 'Cloud Native Computing Foundation', '2022-05-12', '2025-05-12', 'cncf.io/certification/12345', '2025-04-18 06:26:20'),
(24, 13, 'Cisco Certified Network Associate', 'Cisco', '2022-08-30', '2025-08-30', 'cisco.com/certification/12345', '2025-04-18 06:26:20'),
(25, 13, 'CompTIA Security+', 'CompTIA', '2022-07-22', '2025-07-22', 'comptia.org/certification/12345', '2025-04-18 06:26:20'),
(26, 13, 'Oracle Certified Professional: Java SE', 'Oracle', '2022-04-05', '2025-04-05', 'oracle.com/certification/12345', '2025-04-18 06:26:20'),
(27, 14, 'Certified Ethical Hacker', 'EC-Council', '2022-10-05', '2025-10-05', 'eccouncil.org/certification/12345', '2025-04-18 06:26:20'),
(28, 15, 'AWS Certified Solutions Architect', 'Amazon Web Services', '2022-01-15', '2025-01-15', 'aws.com/certification/12345', '2025-04-18 06:26:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `certifications`
--
ALTER TABLE `certifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `certifications`
--
ALTER TABLE `certifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certifications`
--
ALTER TABLE `certifications`
  ADD CONSTRAINT `certifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
