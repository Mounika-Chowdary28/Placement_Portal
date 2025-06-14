-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2025 at 03:30 PM
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
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `reg_number` varchar(20) NOT NULL COMMENT 'Student registration number (e.g. AP23110011340)',
  `password` varchar(255) NOT NULL COMMENT 'Hashed password',
  `full_name` varchar(100) NOT NULL COMMENT 'Student full name',
  `email` varchar(100) NOT NULL COMMENT 'Institutional email',
  `personal_email` varchar(100) DEFAULT NULL COMMENT 'Personal email address',
  `phone` varchar(20) DEFAULT NULL COMMENT 'Contact phone number',
  `dob` date DEFAULT NULL COMMENT 'Date of birth',
  `department` varchar(100) DEFAULT NULL COMMENT 'Department',
  `degree` varchar(50) DEFAULT NULL COMMENT 'Degree program (e.g. B.Tech)',
  `year` int(1) DEFAULT NULL COMMENT 'Current year of study',
  `cgpa` decimal(3,2) DEFAULT NULL COMMENT 'Cumulative GPA',
  `backlogs` int(2) DEFAULT 0 COMMENT 'Number of backlogs',
  `profile_image` varchar(255) DEFAULT 'default.jpg' COMMENT 'Profile photo filename',
  `linkedin` varchar(255) DEFAULT NULL COMMENT 'LinkedIn profile URL',
  `github` varchar(255) DEFAULT NULL COMMENT 'GitHub profile URL',
  `address` text DEFAULT NULL COMMENT 'Residential address',
  `city` varchar(50) DEFAULT NULL COMMENT 'City of residence',
  `state` varchar(50) DEFAULT NULL COMMENT 'State',
  `country` varchar(50) DEFAULT 'India' COMMENT 'Country',
  `postal_code` varchar(20) DEFAULT NULL COMMENT 'Postal',
  `bio` text DEFAULT NULL COMMENT 'Student bio/about me',
  `resume_url` varchar(255) DEFAULT NULL COMMENT 'Path to uploaded resume file',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(11) DEFAULT NULL COMMENT 'Foreign key to users table',
  `graduation_year` int(4) DEFAULT NULL COMMENT 'Expected graduation year',
  `enrollment_number` varchar(50) DEFAULT NULL COMMENT 'University enrollment number'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `reg_number`, `password`, `full_name`, `email`, `personal_email`, `phone`, `dob`, `department`, `degree`, `year`, `cgpa`, `backlogs`, `profile_image`, `linkedin`, `github`, `address`, `city`, `state`, `country`, `postal_code`, `bio`, `resume_url`, `created_at`, `updated_at`, `user_id`, `graduation_year`, `enrollment_number`) VALUES
(1, 'AP23110011340', '$2y$10$psX.JgMzob8XC9KqzfVSbuYikC.CMIWfCru01Q9sG3y...', 'S Mounika Chowdary', 'mounika.s@srm.edu.in', 'smounikachowdary@gmail.com', '9876543210', '2006-07-28', 'Computer Science Engineering', 'B.Tech', 3, 8.75, 0, 'student1.jpg', 'linkedin.com/in/smounikachowdary', 'github.com/smounikachowdary', NULL, NULL, NULL, 'India', NULL, NULL, NULL, '2025-04-18 06:26:17', '2025-04-18 06:27:12', NULL, NULL, NULL),
(2, 'AP23110011341', '$2y$10$6Chlyv7t0Ocjhvwrdu.S4usw2Z9kuqhyjKnYfmYmDEe...', 'Rahul Sharma', 'rahul.s@srm.edu.in', 'rahulsharma@gmail.com', '9876543211', '1999-06-22', 'Computer Science Engineering', 'B.Tech', 3, 8.50, 0, 'student2.jpg', 'linkedin.com/in/rahulsharma', 'github.com/rahulsharma', NULL, NULL, NULL, 'India', NULL, NULL, NULL, '2025-04-18 06:26:18', '2025-04-18 06:26:18', NULL, NULL, NULL),
(3, 'AP23110011342', '$2y$10$Fw2DAbR0pJS9h.bxFBFm9.nfDu4e5luMn0dt2y5Q/3e...', 'Priya Patel', 'priya.p@srm.edu.in', 'priyapatel@gmail.com', '9876543212', '2000-07-10', 'Computer Science Engineering', 'B.Tech', 3, 9.20, 0, 'student3.jpg', 'linkedin.com/in/priyapatel', 'github.com/priyapatel', NULL, NULL, NULL, 'India', NULL, NULL, NULL, '2025-04-18 06:26:18', '2025-04-18 06:26:18', NULL, NULL, NULL),
(4, 'AP23110011343', '$2y$10$vrV5qQVsGoWTRTVUj1oJCOjPexjSIZfFVGX1JPxnHIk...', 'Amit Kumar', 'amit.k@srm.edu.in', 'amitkumar@gmail.com', '9876543213', '2001-03-05', 'Computer Science Engineering', 'B.Tech', 3, 7.90, 1, 'student4.jpg', 'linkedin.com/in/amitkumar', 'github.com/amitkumar', NULL, NULL, NULL, 'India', NULL, NULL, NULL, '2025-04-18 06:26:18', '2025-04-18 06:26:18', NULL, NULL, NULL),
(5, 'AP23110011344', '$2y$10$foOztje8ayyi.YBb2PPbPeEhKdHdOBxtHALdTwDZne8...', 'Sneha Reddy', 'sneha.r@srm.edu.in', 'snehareddy@gmail.com', '9876543214', '2000-11-18', 'Computer Science Engineering', 'B.Tech', 3, 8.80, 0, 'student5.jpg', 'linkedin.com/in/snehareddy', 'github.com/snehareddy', NULL, NULL, NULL, 'India', NULL, NULL, NULL, '2025-04-18 06:26:18', '2025-04-18 06:26:18', NULL, NULL, NULL),
(6, 'AP23110011345', '$2y$10$igq5p4R/w4epkGy4QWS3FuLaOxup28yuPnSaiTN.cMJ...', 'Vikram Singh', 'vikram.s@srm.edu.in', 'vikramsingh@gmail.com', '9876543215', '1999-09-30', 'Electronics Engineering', 'B.Tech', 3, 8.10, 0, 'student6.jpg', 'linkedin.com/in/vikramsingh', 'github.com/vikramsingh', NULL, NULL, NULL, 'India', NULL, NULL, NULL, '2025-04-18 06:26:18', '2025-04-18 06:26:18', NULL, NULL, NULL),
(7, 'AP23110011346', '$2y$10$rxHNtOh1J7XQ8x15q839W.fzuLeewcCywWcyPRE0qvO...', 'Neha Gupta', 'neha.g@srm.edu.in', 'nehagupta@gmail.com', '9876543216', '2000-04-12', 'Electronics Engineering', 'B.Tech', 3, 8.60, 0, 'student7.jpg', 'linkedin.com/in/nehagupta', 'github.com/nehagupta', NULL, NULL, NULL, 'India', NULL, NULL, NULL, '2025-04-18 06:26:18', '2025-04-18 06:26:18', NULL, NULL, NULL),
(8, 'AP23110011347', '$2y$10$GsFU1WSXsPj41TsE9N1cxuC44pxIaO5qMLBTUBf9qsg...', 'Arjun Nair', 'arjun.n@srm.edu.in', 'arjunnair@gmail.com', '9876543217', '2000-08-25', 'Mechanical Engineering', 'B.Tech', 3, 7.80, 1, 'student8.jpg', 'linkedin.com/in/arjunnair', 'github.com/arjunnair', NULL, NULL, NULL, 'India', NULL, NULL, NULL, '2025-04-18 06:26:18', '2025-04-18 06:26:18', NULL, NULL, NULL),
(9, 'AP23110011348', '$2y$10$WCDdkcQ1R/o0C.e/CnvcW.bnN0wRNRWOYF7opc8MHJR...', 'Divya Sharma', 'divya.s@srm.edu.in', 'divyasharma@gmail.com', '9876543218', '2001-02-14', 'Mechanical Engineering', 'B.Tech', 3, 8.30, 0, 'student9.jpg', 'linkedin.com/in/divyasharma', 'github.com/divyasharma', NULL, NULL, NULL, 'India', NULL, NULL, NULL, '2025-04-18 06:26:18', '2025-04-18 06:26:18', NULL, NULL, NULL),
(10, 'AP23110011349', '$2y$10$Kl5s6Ib08cw9H8xX2HEFWu47cKAgygzACT.brNCA7xj...', 'Karthik Menon', 'karthik.m@srm.edu.in', 'karthikmenon@gmail.com', '9876543219', '2000-12-08', 'Civil Engineering', 'B.Tech', 3, 7.70, 1, 'student10.jpg', 'linkedin.com/in/karthikmenon', 'github.com/karthikmenon', NULL, NULL, NULL, 'India', NULL, NULL, NULL, '2025-04-18 06:26:19', '2025-04-18 06:26:19', NULL, NULL, NULL),
(11, 'AP23110011350', '$2y$10$cRVPknxZu68S.ik8RYYNFOlFxsGtu6QG3puq0CEqNIV...', 'Ananya Desai', 'ananya.d@srm.edu.in', 'ananyaDesai@gmail.com', '9876543220', '1999-10-19', 'Civil Engineering', 'B.Tech', 3, 8.40, 0, 'student11.jpg', 'linkedin.com/in/ananyaDesai', 'github.com/ananyaDesai', NULL, NULL, NULL, 'India', NULL, NULL, NULL, '2025-04-18 06:26:19', '2025-04-18 06:26:19', NULL, NULL, NULL),
(12, 'AP23110011351', '$2y$10$z1n3RmBZGzNhUaiAkTfJ.ugFdeCAbiKu1fD16zYEviJ...', 'Rohan Joshi', 'rohan.j@srm.edu.in', 'rohanjoshi@gmail.com', '9876543221', '2000-01-27', 'Information Technology', 'B.Tech', 3, 9.10, 0, 'student12.jpg', 'linkedin.com/in/rohanjoshi', 'github.com/rohanjoshi', NULL, NULL, NULL, 'India', NULL, NULL, NULL, '2025-04-18 06:26:19', '2025-04-18 06:26:19', NULL, NULL, NULL),
(13, 'AP23110011352', '$2y$10$9x8sLJ9fiGmhPPMjQcQ/5eKPh/4thOpRnA0blhZedeR...', 'Meera Krishnan', 'meera.k@srm.edu.in', 'meerakrishnan@gmail.com', '9876543222', '2001-06-03', 'Information Technology', 'B.Tech', 3, 8.90, 0, 'student13.jpg', 'linkedin.com/in/meerakrishnan', 'github.com/meerakrishnan', NULL, NULL, NULL, 'India', NULL, NULL, NULL, '2025-04-18 06:26:19', '2025-04-18 06:26:19', NULL, NULL, NULL),
(14, 'AP23110011353', '$2y$10$TJNboTW0DOqkyVYovBv9LOernt5OcVGzGsXIjn70xgg...', 'Aditya Verma', 'aditya.v@srm.edu.in', 'adityaverma@gmail.com', '9876543223', '2000-07-16', 'Computer Science Engineering', 'B.Tech', 3, 8.20, 0, 'student14.jpg', 'linkedin.com/in/adityaverma', 'github.com/adityaverma', NULL, NULL, NULL, 'India', NULL, NULL, NULL, '2025-04-18 06:26:19', '2025-04-18 06:26:19', NULL, NULL, NULL),
(15, 'AP23110011354', '$2y$10$WEXGwO3tsBesnsXyFQ.Kd./RzaV06dW1Qc7qjqjXlJ6...', 'Kavya Rao', 'kavya.r@srm.edu.in', 'kavyarao@gmail.com', '9876543224', '1999-04-29', 'Computer Science Engineering', 'B.Tech', 3, 8.70, 0, 'student15.jpg', 'linkedin.com/in/kavyarao', 'github.com/kavyarao', NULL, NULL, NULL, 'India', NULL, NULL, NULL, '2025-04-18 06:26:19', '2025-04-18 06:26:19', NULL, NULL, NULL),
(16, 'AP23110011355', '$2y$10$m1yi03t7S.oVDAoNMCSXH.mNtNRwdMvqYnQbJFrBx9u...', 'Siddharth Patel', 'siddharth.p@srm.edu.in', 'siddharthpatel@gmail.com', '9876543225', '2000-11-11', 'Electronics Engineering', 'B.Tech', 3, 7.60, 2, 'student16.jpg', 'linkedin.com/in/siddharthpatel', 'github.com/siddharthpatel', NULL, NULL, NULL, 'India', NULL, NULL, NULL, '2025-04-18 06:26:19', '2025-04-18 06:26:19', NULL, NULL, NULL),
(17, 'AP23110011356', '$2y$10$9PyQMLMbG5IhjCZaLbJvDeF4sdkd0VCvgdwk6hoBFh3...', 'Riya Malhotra', 'riya.m@srm.edu.in', 'riyamalhotra@gmail.com', '9876543226', '2001-02-23', 'Electronics Engineering', 'B.Tech', 3, 8.00, 0, 'student17.jpg', 'linkedin.com/in/riyamalhotra', 'github.com/riyamalhotra', NULL, NULL, NULL, 'India', NULL, NULL, NULL, '2025-04-18 06:26:19', '2025-04-18 06:26:19', NULL, NULL, NULL),
(18, 'AP23110011357', '$2y$10$/MPCBO1sdGlyH4ybVlReYen62nAg.uEjw1jJI4aeDSB...', 'Varun Kapoor', 'varun.k@srm.edu.in', 'varunkapoor@gmail.com', '9876543227', '2000-08-07', 'Mechanical Engineering', 'B.Tech', 3, 7.50, 1, 'student18.jpg', 'linkedin.com/in/varunkapoor', 'github.com/varunkapoor', NULL, NULL, NULL, 'India', NULL, NULL, NULL, '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL, NULL, NULL),
(19, 'AP23110011358', '$2y$10$bdzyrz/KFDh6ixUuplsibeqeQGaR9zRBbS1Q/cCBctz...', 'Ishita Sharma', 'ishita.s@srm.edu.in', 'ishitasharma@gmail.com', '9876543228', '1999-05-14', 'Mechanical Engineering', 'B.Tech', 3, 8.85, 0, 'student19.jpg', 'linkedin.com/in/ishitasharma', 'github.com/ishitasharma', NULL, NULL, NULL, 'India', NULL, NULL, NULL, '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL, NULL, NULL),
(20, 'AP23110011359', '$2y$10$IzNSTzvNuGX1qwyvWzAnxOBoK701m4rjW30qInd2lQ3...', 'Nikhil Mehta', 'nikhil.m@srm.edu.in', 'nikhilmehta@gmail.com', '9876543229', '2000-09-02', 'Civil Engineering', 'B.Tech', 3, 7.95, 0, 'student20.jpg', 'linkedin.com/in/nikhilmehta', 'github.com/nikhilmehta', NULL, NULL, NULL, 'India', NULL, NULL, NULL, '2025-04-18 06:26:20', '2025-04-18 06:26:20', NULL, NULL, NULL),
(21, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'default.jpg', NULL, NULL, NULL, NULL, NULL, 'India', NULL, NULL, 'uploads/resumes/AP23110011343_resume_1745335680.docx', '2025-04-20 14:07:56', '2025-04-22 15:28:00', 4, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reg_number` (`reg_number`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
