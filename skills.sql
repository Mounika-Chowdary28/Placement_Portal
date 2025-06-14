-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2025 at 03:33 PM
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
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `skill_type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `user_id`, `name`, `skill_type`, `created_at`) VALUES
(1, 1, 'JavaScript', 'Programming Languages', '2025-04-18 06:29:01'),
(2, 1, 'React', 'Frameworks', '2025-04-18 06:29:01'),
(3, 1, 'Node.js', 'Frameworks', '2025-04-18 06:29:01'),
(4, 1, 'MongoDB', 'Databases', '2025-04-18 06:29:01'),
(5, 1, 'Express', 'Frameworks', '2025-04-18 06:29:01'),
(6, 1, 'RESTful APIs', 'Tools', '2025-04-18 06:29:01'),
(7, 1, 'HTML', 'Programming Languages', '2025-04-18 06:29:01'),
(8, 1, 'CSS', 'Programming Languages', '2025-04-18 06:29:01'),
(9, 2, 'Flutter', 'Frameworks', '2025-04-18 06:29:01'),
(10, 2, 'React Native', 'Frameworks', '2025-04-18 06:29:01'),
(11, 2, 'Swift', 'Programming Languages', '2025-04-18 06:29:01'),
(12, 2, 'Kotlin', 'Programming Languages', '2025-04-18 06:29:01'),
(13, 2, 'Firebase', 'Databases', '2025-04-18 06:29:01'),
(14, 2, 'Git', 'Tools', '2025-04-18 06:29:01'),
(15, 2, 'JavaScript', 'Programming Languages', '2025-04-18 06:29:01'),
(16, 3, 'Python', 'Programming Languages', '2025-04-18 06:29:01'),
(17, 3, 'TensorFlow', 'Frameworks', '2025-04-18 06:29:01'),
(18, 3, 'PyTorch', 'Frameworks', '2025-04-18 06:29:01'),
(19, 3, 'Data Analysis', 'Tools', '2025-04-18 06:29:01'),
(20, 3, 'ML Algorithms', 'Tools', '2025-04-18 06:29:01'),
(21, 3, 'SQL', 'Databases', '2025-04-18 06:29:01'),
(22, 3, 'Statistics', 'Tools', '2025-04-18 06:29:01'),
(23, 3, 'R', 'Programming Languages', '2025-04-18 06:29:01'),
(24, 3, 'Data Visualization', 'Tools', '2025-04-18 06:29:01'),
(25, 4, 'Docker', 'Tools', '2025-04-18 06:29:01'),
(26, 4, 'Kubernetes', 'Tools', '2025-04-18 06:29:01'),
(27, 4, 'Jenkins', 'Tools', '2025-04-18 06:29:01'),
(28, 4, 'AWS', 'Tools', '2025-04-18 06:29:01'),
(29, 4, 'Linux', 'Tools', '2025-04-18 06:29:01'),
(30, 4, 'Git', 'Tools', '2025-04-18 06:29:01'),
(31, 4, 'CI/CD', 'Tools', '2025-04-18 06:29:01'),
(32, 5, 'JavaScript', 'Programming Languages', '2025-04-18 06:29:01'),
(33, 5, 'React', 'Frameworks', '2025-04-18 06:29:01'),
(34, 5, 'Redux', 'Frameworks', '2025-04-18 06:29:01'),
(35, 5, 'HTML', 'Programming Languages', '2025-04-18 06:29:01'),
(36, 5, 'CSS', 'Programming Languages', '2025-04-18 06:29:01'),
(37, 5, 'Responsive Design', 'Tools', '2025-04-18 06:29:01'),
(38, 5, 'TypeScript', 'Programming Languages', '2025-04-18 06:29:01'),
(39, 6, 'Java', 'Programming Languages', '2025-04-18 06:29:01'),
(40, 6, 'Spring Boot', 'Frameworks', '2025-04-18 06:29:01'),
(41, 6, 'Microservices', 'Tools', '2025-04-18 06:29:01'),
(42, 6, 'MySQL', 'Databases', '2025-04-18 06:29:01'),
(43, 6, 'API Design', 'Tools', '2025-04-18 06:29:01'),
(44, 6, 'SQL', 'Databases', '2025-04-18 06:29:01'),
(45, 7, 'Network Security', 'Tools', '2025-04-18 06:29:01'),
(46, 7, 'Penetration Testing', 'Tools', '2025-04-18 06:29:01'),
(47, 7, 'SIEM', 'Tools', '2025-04-18 06:29:01'),
(48, 7, 'Ethical Hacking', 'Tools', '2025-04-18 06:29:01'),
(49, 7, 'Firewalls', 'Tools', '2025-04-18 06:29:01'),
(50, 7, 'VPN', 'Tools', '2025-04-18 06:29:01'),
(51, 7, 'IDS/IPS', 'Tools', '2025-04-18 06:29:01'),
(52, 8, 'UI Design', 'Tools', '2025-04-18 06:29:01'),
(53, 8, 'UX Research', 'Tools', '2025-04-18 06:29:01'),
(54, 8, 'Figma', 'Tools', '2025-04-18 06:29:01'),
(55, 8, 'Adobe XD', 'Tools', '2025-04-18 06:29:01'),
(56, 8, 'Prototyping', 'Tools', '2025-04-18 06:29:01'),
(57, 8, 'CSS', 'Programming Languages', '2025-04-18 06:29:01'),
(58, 8, 'HTML', 'Programming Languages', '2025-04-18 06:29:01'),
(59, 9, 'AWS', 'Tools', '2025-04-18 06:29:01'),
(60, 9, 'Azure', 'Tools', '2025-04-18 06:29:01'),
(61, 9, 'Docker', 'Tools', '2025-04-18 06:29:01'),
(62, 9, 'Kubernetes', 'Tools', '2025-04-18 06:29:01'),
(63, 9, 'Terraform', 'Tools', '2025-04-18 06:29:01'),
(64, 9, 'Cloud Integration', 'Tools', '2025-04-18 06:29:01'),
(65, 10, 'Blockchain', 'Tools', '2025-04-18 06:29:01'),
(66, 10, 'Solidity', 'Programming Languages', '2025-04-18 06:29:01'),
(67, 10, 'Ethereum', 'Tools', '2025-04-18 06:29:01'),
(68, 10, 'Smart Contracts', 'Tools', '2025-04-18 06:29:01'),
(69, 10, 'JavaScript', 'Programming Languages', '2025-04-18 06:29:01'),
(70, 11, 'JavaScript', 'Programming Languages', '2025-04-18 06:29:01'),
(71, 11, 'React Native', 'Frameworks', '2025-04-18 06:29:01'),
(72, 11, 'Redux', 'Frameworks', '2025-04-18 06:29:01'),
(73, 11, 'TypeScript', 'Programming Languages', '2025-04-18 06:29:01'),
(74, 11, 'Firebase', 'Databases', '2025-04-18 06:29:01'),
(75, 11, 'Git', 'Tools', '2025-04-18 06:29:01'),
(76, 12, 'Python', 'Programming Languages', '2025-04-18 06:29:01'),
(77, 12, 'Deep Learning', 'Tools', '2025-04-18 06:29:01'),
(78, 12, 'Neural Networks', 'Tools', '2025-04-18 06:29:01'),
(79, 12, 'Research', 'Tools', '2025-04-18 06:29:01'),
(80, 12, 'NLP', 'Tools', '2025-04-18 06:29:01'),
(81, 13, 'Unity', 'Tools', '2025-04-18 06:29:01'),
(82, 13, 'C#', 'Programming Languages', '2025-04-18 06:29:01'),
(83, 13, 'Game Design', 'Tools', '2025-04-18 06:29:01'),
(84, 13, '3D Graphics', 'Tools', '2025-04-18 06:29:01'),
(85, 13, 'Physics', 'Tools', '2025-04-18 06:29:01'),
(86, 14, 'Unity3D', 'Tools', '2025-04-18 06:29:01'),
(87, 14, 'C#', 'Programming Languages', '2025-04-18 06:29:01'),
(88, 14, '3D Modeling', 'Tools', '2025-04-18 06:29:01'),
(89, 14, 'AR Frameworks', 'Tools', '2025-04-18 06:29:01'),
(90, 14, 'VR Development', 'Tools', '2025-04-18 06:29:01'),
(91, 15, 'IoT', 'Tools', '2025-04-18 06:29:01'),
(92, 15, 'Embedded Systems', 'Tools', '2025-04-18 06:29:01'),
(93, 15, 'C++', 'Programming Languages', '2025-04-18 06:29:01'),
(94, 15, 'MQTT', 'Tools', '2025-04-18 06:29:01'),
(95, 15, 'Cloud Integration', 'Tools', '2025-04-18 06:29:01'),
(96, 15, 'Embedded C', 'Programming Languages', '2025-04-18 06:29:01'),
(97, 15, 'Microcontrollers', 'Tools', '2025-04-18 06:29:01'),
(98, 15, 'RTOS', 'Tools', '2025-04-18 06:29:01'),
(99, 15, 'Electronics', 'Tools', '2025-04-18 06:29:01'),
(100, 16, 'Python', 'Programming Languages', '2025-04-18 06:29:01'),
(101, 16, 'SQL', 'Databases', '2025-04-18 06:29:01'),
(102, 16, 'Hadoop', 'Tools', '2025-04-18 06:29:01'),
(103, 16, 'Spark', 'Tools', '2025-04-18 06:29:01'),
(104, 16, 'ETL', 'Tools', '2025-04-18 06:29:01'),
(105, 17, 'JavaScript', 'Programming Languages', '2025-04-18 06:29:01'),
(106, 17, 'React', 'Frameworks', '2025-04-18 06:29:01'),
(107, 17, 'Java', 'Programming Languages', '2025-04-18 06:29:01'),
(108, 17, 'Spring Boot', 'Frameworks', '2025-04-18 06:29:01'),
(109, 17, 'MySQL', 'Databases', '2025-04-18 06:29:01'),
(110, 18, 'Docker', 'Tools', '2025-04-18 06:29:01'),
(111, 18, 'AWS', 'Tools', '2025-04-18 06:29:01'),
(112, 18, 'Terraform', 'Tools', '2025-04-18 06:29:01'),
(113, 18, 'Linux', 'Tools', '2025-04-18 06:29:01'),
(114, 19, 'React', 'Frameworks', '2025-04-18 06:29:01'),
(115, 19, 'React Native', 'Frameworks', '2025-04-18 06:29:01'),
(116, 19, 'JavaScript', 'Programming Languages', '2025-04-18 06:29:01'),
(117, 19, 'HTML', 'Programming Languages', '2025-04-18 06:29:01'),
(118, 19, 'CSS', 'Programming Languages', '2025-04-18 06:29:01'),
(119, 20, 'Python', 'Programming Languages', '2025-04-18 06:29:01'),
(120, 20, 'JavaScript', 'Programming Languages', '2025-04-18 06:29:01'),
(121, 20, 'React', 'Frameworks', '2025-04-18 06:29:01'),
(122, 20, 'Docker', 'Tools', '2025-04-18 06:29:01'),
(123, 20, 'Git', 'Tools', '2025-04-18 06:29:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `skills`
--
ALTER TABLE `skills`
  ADD CONSTRAINT `skills_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
