-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2025 at 03:26 PM
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
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Applied',
  `resume` varchar(255) DEFAULT NULL,
  `cover_letter` text DEFAULT NULL,
  `applied_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `feedback` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `user_id`, `job_id`, `status`, `resume`, `cover_letter`, `applied_date`, `updated_at`, `feedback`) VALUES
(1, 1, 8, 'Rejected', 'resume_1_8.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(2, 1, 5, 'Rejected', 'resume_1_5.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(3, 1, 9, 'Offer Received', 'resume_1_9.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(4, 1, 1, 'Rejected', 'resume_1_1.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(5, 2, 4, 'Technical Assessment', 'resume_2_4.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(6, 2, 8, 'Application Under Review', 'resume_2_8.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(7, 3, 7, 'Final Interview', 'resume_3_7.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(8, 3, 9, 'Rejected', 'resume_3_9.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(9, 4, 7, 'Offer Received', 'resume_4_7.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(11, 4, 8, 'Technical Assessment', 'resume_4_8.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(14, 5, 7, 'Final Interview', 'resume_5_7.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(15, 5, 8, 'Applied', 'resume_5_8.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(16, 5, 3, 'Application Under Review', 'resume_5_3.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(17, 5, 1, 'Interview Scheduled', 'resume_5_1.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(18, 6, 9, 'Rejected', 'resume_6_9.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(19, 6, 5, 'Final Interview', 'resume_6_5.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(20, 7, 4, 'Final Interview', 'resume_7_4.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(21, 7, 3, 'Rejected', 'resume_7_3.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(22, 7, 5, 'Rejected', 'resume_7_5.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(23, 7, 8, 'Applied', 'resume_7_8.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(24, 8, 10, 'Rejected', 'resume_8_10.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(25, 8, 4, 'Final Interview', 'resume_8_4.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(26, 8, 1, 'Withdrawn', 'resume_8_1.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(27, 8, 7, 'Interview Scheduled', 'resume_8_7.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(28, 9, 4, 'Withdrawn', 'resume_9_4.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(29, 9, 1, 'Offer Received', 'resume_9_1.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(30, 9, 9, 'Applied', 'resume_9_9.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(31, 10, 3, 'Interview Scheduled', 'resume_10_3.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(32, 11, 10, 'Final Interview', 'resume_11_10.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(33, 11, 5, 'Withdrawn', 'resume_11_5.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(34, 12, 1, 'Withdrawn', 'resume_12_1.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(35, 12, 6, 'Offer Received', 'resume_12_6.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(36, 12, 7, 'Rejected', 'resume_12_7.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(37, 13, 3, 'Withdrawn', 'resume_13_3.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(38, 13, 8, 'Applied', 'resume_13_8.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(39, 13, 9, 'Applied', 'resume_13_9.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(40, 13, 5, 'Interview Scheduled', 'resume_13_5.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(41, 14, 1, 'Final Interview', 'resume_14_1.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(42, 14, 4, 'Final Interview', 'resume_14_4.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(43, 14, 10, 'Technical Assessment', 'resume_14_10.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(44, 16, 8, 'Interview Scheduled', 'resume_16_8.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(45, 16, 9, 'Application Under Review', 'resume_16_9.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(46, 17, 7, 'Rejected', 'resume_17_7.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(47, 17, 3, 'Technical Assessment', 'resume_17_3.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(48, 18, 6, 'Withdrawn', 'resume_18_6.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(49, 18, 10, 'Offer Received', 'resume_18_10.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(50, 18, 3, 'Application Under Review', 'resume_18_3.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(51, 20, 2, 'Applied', 'resume_20_2.pdf', 'I am writing to express my interest in the position. I believe my skills and experience make me a strong candidate.', '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL),
(52, 2, 1, 'Applied', NULL, NULL, '2025-04-18 06:57:44', '2025-04-18 06:57:44', NULL),
(53, 2, 3, 'Applied', NULL, NULL, '2025-04-18 07:36:22', '2025-04-18 07:36:22', NULL),
(54, 6, 21, 'Applied', NULL, NULL, '2025-04-22 02:35:58', '2025-04-22 02:35:58', NULL),
(57, 20, 1, 'Applied', NULL, NULL, '2025-04-23 03:40:40', '2025-04-23 03:40:40', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `job_id` (`job_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
