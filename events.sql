-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2025 at 03:38 PM
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
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(100) NOT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `event_type` varchar(50) NOT NULL,
  `event_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_name`, `company_name`, `event_type`, `event_date`, `description`, `location`, `created_at`) VALUES
(1, 'TechCorp Recruitment Drive', 'TechCorp', 'Recruitment', '2023-05-15', 'Campus recruitment drive for software development roles', 'Main Auditorium', '2025-04-18 06:26:17'),
(2, 'DataSystems Technical Workshop', 'DataSystems Inc', 'Workshop', '2023-05-20', 'Workshop on data analytics and machine learning', 'Lab 204', '2025-04-18 06:26:17'),
(3, 'Resume Building Workshop', 'Placement Cell', 'Workshop', '2023-05-25', 'Learn how to build an effective resume', 'Seminar Hall', '2025-04-18 06:26:17'),
(4, 'Mock Interview Session', 'Placement Cell', 'Training', '2023-05-30', 'Practice interviews with industry professionals', 'Conference Room', '2025-04-18 06:26:17'),
(5, 'WebTech Info Session', 'WebTech Solutions', 'Info Session', '2023-06-05', 'Information session about company and roles', 'Lecture Hall 101', '2025-04-18 06:26:17'),
(6, 'TechCorp Recruitment Drive', 'TechCorp', 'Recruitment', '2023-05-15', 'Campus recruitment drive for software development roles', 'Main Auditorium', '2025-04-21 18:17:54'),
(7, 'DataSystems Technical Workshop', 'DataSystems Inc', 'Workshop', '2023-05-20', 'Workshop on data analytics and machine learning', 'Lab 204', '2025-04-21 18:17:54'),
(8, 'Resume Building Workshop', 'Placement Cell', 'Workshop', '2023-05-25', 'Learn how to build an effective resume', 'Seminar Hall', '2025-04-21 18:17:54'),
(9, 'Mock Interview Session', 'Placement Cell', 'Training', '2023-05-30', 'Practice interviews with industry professionals', 'Conference Room', '2025-04-21 18:17:54'),
(10, 'WebTech Info Session', 'WebTech Solutions', 'Info Session', '2023-06-05', 'Information session about company and roles', 'Lecture Hall 101', '2025-04-21 18:17:54'),
(11, 'TechCorp Recruitment Drive', 'TechCorp', 'Recruitment', '2023-05-15', 'Campus recruitment drive for software development roles', 'Main Auditorium', '2025-04-21 18:18:43'),
(12, 'DataSystems Technical Workshop', 'DataSystems Inc', 'Workshop', '2023-05-20', 'Workshop on data analytics and machine learning', 'Lab 204', '2025-04-21 18:18:43'),
(13, 'Resume Building Workshop', 'Placement Cell', 'Workshop', '2023-05-25', 'Learn how to build an effective resume', 'Seminar Hall', '2025-04-21 18:18:43'),
(14, 'Mock Interview Session', 'Placement Cell', 'Training', '2023-05-30', 'Practice interviews with industry professionals', 'Conference Room', '2025-04-21 18:18:43'),
(15, 'WebTech Info Session', 'WebTech Solutions', 'Info Session', '2023-06-05', 'Information session about company and roles', 'Lecture Hall 101', '2025-04-21 18:18:43'),
(16, 'TechCorp Recruitment Drive', 'TechCorp', 'Recruitment', '2023-05-15', 'Campus recruitment drive for software development roles', 'Main Auditorium', '2025-04-21 18:18:45'),
(17, 'DataSystems Technical Workshop', 'DataSystems Inc', 'Workshop', '2023-05-20', 'Workshop on data analytics and machine learning', 'Lab 204', '2025-04-21 18:18:45'),
(18, 'Resume Building Workshop', 'Placement Cell', 'Workshop', '2023-05-25', 'Learn how to build an effective resume', 'Seminar Hall', '2025-04-21 18:18:45'),
(19, 'Mock Interview Session', 'Placement Cell', 'Training', '2023-05-30', 'Practice interviews with industry professionals', 'Conference Room', '2025-04-21 18:18:45'),
(20, 'WebTech Info Session', 'WebTech Solutions', 'Info Session', '2023-06-05', 'Information session about company and roles', 'Lecture Hall 101', '2025-04-21 18:18:45'),
(21, 'TechCorp Recruitment Drive', 'TechCorp', 'Recruitment', '2023-05-15', 'Campus recruitment drive for software development roles', 'Main Auditorium', '2025-04-21 18:18:49'),
(22, 'DataSystems Technical Workshop', 'DataSystems Inc', 'Workshop', '2023-05-20', 'Workshop on data analytics and machine learning', 'Lab 204', '2025-04-21 18:18:49'),
(23, 'Resume Building Workshop', 'Placement Cell', 'Workshop', '2023-05-25', 'Learn how to build an effective resume', 'Seminar Hall', '2025-04-21 18:18:49'),
(24, 'Mock Interview Session', 'Placement Cell', 'Training', '2023-05-30', 'Practice interviews with industry professionals', 'Conference Room', '2025-04-21 18:18:49'),
(25, 'WebTech Info Session', 'WebTech Solutions', 'Info Session', '2023-06-05', 'Information session about company and roles', 'Lecture Hall 101', '2025-04-21 18:18:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
