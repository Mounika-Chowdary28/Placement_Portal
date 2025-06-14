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
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `is_read`, `created_at`) VALUES
(1, 1, 'Resume Feedback', 'You have received feedback on your resume from the placement officer.', 0, '2025-04-18 06:26:20'),
(2, 1, 'New Job Posted', 'A new job matching your profile has been posted by InnovateX.', 0, '2025-04-18 06:26:20'),
(3, 2, 'Technical Assessment', 'You have been invited to take a technical assessment for CloudSys Technologies.', 1, '2025-04-18 06:26:20'),
(4, 2, 'Application Status Update', 'Your application for WebTech Solutions has been reviewed.', 1, '2025-04-18 06:26:20'),
(5, 2, 'Interview Reminder', 'Your first round interview with TechCorp is tomorrow at 10:00 AM.', 1, '2025-04-18 06:26:20'),
(6, 2, 'Offer Letter', 'Congratulations! You have received an offer letter from TechCorp.', 1, '2025-04-18 06:26:20'),
(7, 2, 'Application Rejected', 'We regret to inform you that your application for GlobalTech has been rejected.', 1, '2025-04-18 06:26:20'),
(8, 3, 'Resume Feedback', 'You have received feedback on your resume from the placement officer.', 1, '2025-04-18 06:26:20'),
(9, 3, 'Technical Assessment', 'You have been invited to take a technical assessment for CloudSys Technologies.', 1, '2025-04-18 06:26:20'),
(10, 4, 'Application Incomplete', 'Please complete your application for DataSystems Inc.', 1, '2025-04-18 06:26:20'),
(11, 4, 'Technical Assessment', 'You have been invited to take a technical assessment for CloudSys Technologies.', 1, '2025-04-18 06:26:20'),
(12, 4, 'Resume Feedback', 'You have received feedback on your resume from the placement officer.', 1, '2025-04-18 06:26:20'),
(13, 5, 'Application Rejected', 'We regret to inform you that your application for GlobalTech has been rejected.', 1, '2025-04-18 06:26:20'),
(14, 5, 'Application Incomplete', 'Please complete your application for DataSystems Inc.', 1, '2025-04-18 06:26:20'),
(15, 5, 'New Job Posted', 'A new job matching your profile has been posted by InnovateX.', 1, '2025-04-18 06:26:20'),
(16, 5, 'Technical Assessment', 'You have been invited to take a technical assessment for CloudSys Technologies.', 1, '2025-04-18 06:26:20'),
(17, 6, 'Application Incomplete', 'Please complete your application for DataSystems Inc.', 0, '2025-04-18 06:26:20'),
(18, 6, 'Event Reminder', 'Don\'t forget to attend the Resume Building Workshop tomorrow.', 0, '2025-04-18 06:26:20'),
(19, 6, 'Application Status Update', 'Your application for WebTech Solutions has been reviewed.', 1, '2025-04-18 06:26:20'),
(20, 7, 'Application Incomplete', 'Please complete your application for DataSystems Inc.', 0, '2025-04-18 06:26:20'),
(21, 7, 'Profile Completion', 'Your profile is 85% complete. Add more details to improve visibility to recruiters.', 0, '2025-04-18 06:26:20'),
(22, 8, 'New Job Posted', 'A new job matching your profile has been posted by InnovateX.', 1, '2025-04-18 06:26:20'),
(23, 8, 'Offer Letter', 'Congratulations! You have received an offer letter from TechCorp.', 0, '2025-04-18 06:26:20'),
(24, 8, 'Resume Feedback', 'You have received feedback on your resume from the placement officer.', 1, '2025-04-18 06:26:20'),
(25, 9, 'Resume Feedback', 'You have received feedback on your resume from the placement officer.', 0, '2025-04-18 06:26:20'),
(26, 9, 'Technical Assessment', 'You have been invited to take a technical assessment for CloudSys Technologies.', 1, '2025-04-18 06:26:20'),
(27, 10, 'Application Status Update', 'Your application for WebTech Solutions has been reviewed.', 1, '2025-04-18 06:26:20'),
(28, 10, 'Profile Completion', 'Your profile is 85% complete. Add more details to improve visibility to recruiters.', 0, '2025-04-18 06:26:20'),
(29, 10, 'Event Reminder', 'Don\'t forget to attend the Resume Building Workshop tomorrow.', 1, '2025-04-18 06:26:20'),
(30, 10, 'Technical Assessment', 'You have been invited to take a technical assessment for CloudSys Technologies.', 0, '2025-04-18 06:26:20'),
(31, 11, 'New Job Posted', 'A new job matching your profile has been posted by InnovateX.', 1, '2025-04-18 06:26:20'),
(32, 11, 'Application Status Update', 'Your application for WebTech Solutions has been reviewed.', 0, '2025-04-18 06:26:20'),
(33, 11, 'Application Incomplete', 'Please complete your application for DataSystems Inc.', 1, '2025-04-18 06:26:20'),
(34, 12, 'Interview Reminder', 'Your first round interview with TechCorp is tomorrow at 10:00 AM.', 1, '2025-04-18 06:26:20'),
(35, 12, 'Application Incomplete', 'Please complete your application for DataSystems Inc.', 0, '2025-04-18 06:26:20'),
(36, 13, 'Profile Completion', 'Your profile is 85% complete. Add more details to improve visibility to recruiters.', 1, '2025-04-18 06:26:20'),
(37, 13, 'Technical Assessment', 'You have been invited to take a technical assessment for CloudSys Technologies.', 0, '2025-04-18 06:26:20'),
(38, 14, 'New Job Posted', 'A new job matching your profile has been posted by InnovateX.', 1, '2025-04-18 06:26:20'),
(39, 14, 'Technical Assessment', 'You have been invited to take a technical assessment for CloudSys Technologies.', 1, '2025-04-18 06:26:20'),
(40, 15, 'Resume Feedback', 'You have received feedback on your resume from the placement officer.', 0, '2025-04-18 06:26:20'),
(41, 15, 'Application Incomplete', 'Please complete your application for DataSystems Inc.', 0, '2025-04-18 06:26:20'),
(42, 16, 'Resume Feedback', 'You have received feedback on your resume from the placement officer.', 1, '2025-04-18 06:26:20'),
(43, 16, 'Technical Assessment', 'You have been invited to take a technical assessment for CloudSys Technologies.', 1, '2025-04-18 06:26:20'),
(44, 16, 'Application Rejected', 'We regret to inform you that your application for GlobalTech has been rejected.', 0, '2025-04-18 06:26:20'),
(45, 16, 'New Job Posted', 'A new job matching your profile has been posted by InnovateX.', 1, '2025-04-18 06:26:20'),
(46, 17, 'Technical Assessment', 'You have been invited to take a technical assessment for CloudSys Technologies.', 1, '2025-04-18 06:26:20'),
(47, 17, 'New Job Posted', 'A new job matching your profile has been posted by InnovateX.', 0, '2025-04-18 06:26:20'),
(48, 17, 'Event Reminder', 'Don\'t forget to attend the Resume Building Workshop tomorrow.', 0, '2025-04-18 06:26:20'),
(49, 17, 'Offer Letter', 'Congratulations! You have received an offer letter from TechCorp.', 0, '2025-04-18 06:26:20'),
(50, 18, 'Technical Assessment', 'You have been invited to take a technical assessment for CloudSys Technologies.', 0, '2025-04-18 06:26:20'),
(51, 18, 'Application Status Update', 'Your application for WebTech Solutions has been reviewed.', 0, '2025-04-18 06:26:20'),
(52, 18, 'Event Reminder', 'Don\'t forget to attend the Resume Building Workshop tomorrow.', 1, '2025-04-18 06:26:20'),
(53, 18, 'Profile Completion', 'Your profile is 85% complete. Add more details to improve visibility to recruiters.', 0, '2025-04-18 06:26:20'),
(54, 19, 'Technical Assessment', 'You have been invited to take a technical assessment for CloudSys Technologies.', 1, '2025-04-18 06:26:20'),
(55, 19, 'Offer Letter', 'Congratulations! You have received an offer letter from TechCorp.', 1, '2025-04-18 06:26:20'),
(56, 19, 'Application Incomplete', 'Please complete your application for DataSystems Inc.', 1, '2025-04-18 06:26:20'),
(57, 20, 'Application Rejected', 'We regret to inform you that your application for GlobalTech has been rejected.', 1, '2025-04-18 06:26:20'),
(58, 20, 'Application Status Update', 'Your application for WebTech Solutions has been reviewed.', 1, '2025-04-18 06:26:20'),
(59, 20, 'Technical Assessment', 'You have been invited to take a technical assessment for CloudSys Technologies.', 1, '2025-04-18 06:26:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
