-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2025 at 03:28 PM
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
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'present',
  `excuse_reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `event_id`, `status`, `excuse_reason`, `created_at`) VALUES
(1, 1, 1, 'present', NULL, '2025-04-18 06:26:20'),
(2, 2, 1, 'present', NULL, '2025-04-18 06:26:20'),
(3, 4, 1, 'present', NULL, '2025-04-18 06:26:20'),
(4, 10, 1, 'present', NULL, '2025-04-18 06:26:20'),
(5, 13, 1, 'present', NULL, '2025-04-18 06:26:20'),
(6, 15, 1, 'absent', 'Personal reasons', '2025-04-18 06:26:20'),
(7, 16, 1, 'present', NULL, '2025-04-18 06:26:20'),
(8, 18, 1, 'present', NULL, '2025-04-18 06:26:20'),
(9, 19, 1, 'present', NULL, '2025-04-18 06:26:20'),
(10, 20, 1, 'absent', 'Personal reasons', '2025-04-18 06:26:20'),
(11, 1, 2, 'present', NULL, '2025-04-18 06:26:20'),
(12, 2, 2, 'absent', 'Personal reasons', '2025-04-18 06:26:20'),
(13, 3, 2, 'present', NULL, '2025-04-18 06:26:20'),
(14, 4, 2, 'present', NULL, '2025-04-18 06:26:20'),
(15, 5, 2, 'present', NULL, '2025-04-18 06:26:20'),
(16, 8, 2, 'present', NULL, '2025-04-18 06:26:20'),
(17, 11, 2, 'present', NULL, '2025-04-18 06:26:20'),
(18, 13, 2, 'present', NULL, '2025-04-18 06:26:20'),
(19, 14, 2, 'present', NULL, '2025-04-18 06:26:20'),
(20, 15, 2, 'present', NULL, '2025-04-18 06:26:20'),
(21, 16, 2, 'present', NULL, '2025-04-18 06:26:20'),
(22, 17, 2, 'present', NULL, '2025-04-18 06:26:20'),
(23, 18, 2, 'present', NULL, '2025-04-18 06:26:20'),
(24, 20, 2, 'present', NULL, '2025-04-18 06:26:20'),
(25, 3, 3, 'absent', 'Personal reasons', '2025-04-18 06:26:20'),
(26, 4, 3, 'present', NULL, '2025-04-18 06:26:20'),
(27, 5, 3, 'present', NULL, '2025-04-18 06:26:20'),
(28, 7, 3, 'present', NULL, '2025-04-18 06:26:20'),
(29, 9, 3, 'present', NULL, '2025-04-18 06:26:20'),
(30, 11, 3, 'absent', 'Personal reasons', '2025-04-18 06:26:20'),
(31, 13, 3, 'absent', 'Personal reasons', '2025-04-18 06:26:20'),
(32, 14, 3, 'present', NULL, '2025-04-18 06:26:20'),
(33, 16, 3, 'present', NULL, '2025-04-18 06:26:20'),
(34, 17, 3, 'present', NULL, '2025-04-18 06:26:20'),
(35, 18, 3, 'absent', 'Personal reasons', '2025-04-18 06:26:20'),
(36, 19, 3, 'absent', 'Personal reasons', '2025-04-18 06:26:20'),
(37, 20, 3, 'present', NULL, '2025-04-18 06:26:20'),
(38, 1, 4, 'present', NULL, '2025-04-18 06:26:20'),
(39, 2, 4, 'absent', 'Personal reasons', '2025-04-18 06:26:20'),
(40, 3, 4, 'present', NULL, '2025-04-18 06:26:20'),
(41, 6, 4, 'present', NULL, '2025-04-18 06:26:20'),
(42, 9, 4, 'absent', 'Personal reasons', '2025-04-18 06:26:20'),
(43, 10, 4, 'absent', 'Personal reasons', '2025-04-18 06:26:20'),
(44, 11, 4, 'present', NULL, '2025-04-18 06:26:20'),
(45, 12, 4, 'absent', 'Personal reasons', '2025-04-18 06:26:20'),
(46, 14, 4, 'present', NULL, '2025-04-18 06:26:20'),
(47, 15, 4, 'present', NULL, '2025-04-18 06:26:20'),
(48, 18, 4, 'present', NULL, '2025-04-18 06:26:20'),
(49, 19, 4, 'present', NULL, '2025-04-18 06:26:20'),
(50, 20, 4, 'present', NULL, '2025-04-18 06:26:20'),
(51, 1, 5, 'present', NULL, '2025-04-18 06:26:20'),
(52, 3, 5, 'present', NULL, '2025-04-18 06:26:20'),
(53, 4, 5, 'absent', 'Personal reasons', '2025-04-18 06:26:20'),
(54, 9, 5, 'absent', 'Personal reasons', '2025-04-18 06:26:20'),
(55, 10, 5, 'present', NULL, '2025-04-18 06:26:20'),
(56, 12, 5, 'present', NULL, '2025-04-18 06:26:20'),
(57, 13, 5, 'present', NULL, '2025-04-18 06:26:20'),
(58, 17, 5, 'present', NULL, '2025-04-18 06:26:20'),
(59, 18, 5, 'absent', 'Personal reasons', '2025-04-18 06:26:20'),
(60, 19, 5, 'absent', 'Personal reasons', '2025-04-18 06:26:20'),
(61, 20, 5, 'present', NULL, '2025-04-18 06:26:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `event_id` (`event_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
