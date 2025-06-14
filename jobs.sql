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
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `job_type` varchar(50) DEFAULT NULL,
  `salary_range` varchar(50) DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `min_cgpa` decimal(3,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `company_id`, `title`, `description`, `location`, `job_type`, `salary_range`, `deadline`, `skills`, `requirements`, `created_at`, `min_cgpa`) VALUES
(1, 1, 'Software Development Engineer', NULL, 'Bangalore, India', 'Full-time', '₹12-18 LPA', '2023-05-20', 'Java, Spring Boot, React, AWS', 'Bachelor\'s degree in Computer Science or related field, 3+ years of experience in software development', '2025-04-18 06:26:17', 7.50),
(2, 2, 'Data Scientist', NULL, 'Hyderabad, India', 'Full-time', '₹14-20 LPA', '2023-05-25', 'Python, Machine Learning, SQL, TensorFlow', 'Master\'s degree in Data Science, Statistics or related field', '2025-04-18 06:26:17', 8.00),
(3, 3, 'Frontend Developer', NULL, 'Chennai, India', 'Full-time', '₹8-12 LPA', '2023-05-22', 'JavaScript, React, HTML/CSS, TypeScript', 'Experience with modern JavaScript frameworks', '2025-04-18 06:26:17', 7.00),
(4, 4, 'DevOps Engineer', NULL, 'Remote', 'Full-time', '₹15-22 LPA', '2023-05-30', 'Docker, Kubernetes, CI/CD, AWS', 'Experience with containerization and cloud platforms', '2025-04-18 06:26:17', 8.50),
(5, 5, 'Full Stack Developer', NULL, 'Pune, India', 'Full-time', '₹10-15 LPA', '2023-06-05', 'JavaScript, Node.js, React, MongoDB', 'Experience with full stack development', '2025-04-18 06:26:17', 7.50),
(6, 6, 'Product Manager', NULL, 'Bangalore, India', 'Full-time', '₹18-25 LPA', '2023-06-10', 'Product Management, Agile, UX/UI, Analytics', 'Experience in product management and development', '2025-04-18 06:26:17', 8.00),
(7, 7, 'UI/UX Designer', NULL, 'Mumbai, India', 'Full-time', '₹8-14 LPA', '2023-06-15', 'UI Design, UX Design, Figma, Adobe XD', 'Experience in designing user interfaces and experiences', '2025-04-18 06:26:17', 7.20),
(8, 8, 'Backend Developer', NULL, 'Delhi, India', 'Full-time', '₹10-16 LPA', '2023-06-20', 'Java, Python, Node.js, SQL', 'Experience in backend development and databases', '2025-04-18 06:26:17', 7.80),
(9, 9, 'Machine Learning Engineer', NULL, 'Hyderabad, India', 'Full-time', '₹16-22 LPA', '2023-06-25', 'Python, Machine Learning, Deep Learning, TensorFlow', 'Experience in machine learning and deep learning', '2025-04-18 06:26:17', 8.20),
(10, 10, 'Software Engineer Intern', NULL, 'Bangalore, India', 'Internship', '₹25-35K per month', '2023-07-01', 'Java, Python, JavaScript, SQL', 'Currently pursuing a degree in Computer Science or related field', '2025-04-18 06:26:17', 8.50),
(11, 1, 'Mobile App Developer', 'Join our team to build innovative mobile applications for iOS and Android platforms', 'Bangalore, India', 'Full-time', '₹10-16 LPA', '2023-12-30', 'Swift, Kotlin, Flutter, Firebase, Git', 'Bachelor\'s degree in Computer Science, 2+ years of mobile app development experience', '2025-04-18 06:28:48', 7.50),
(12, 2, 'Data Engineer', 'Design and build data pipelines and data infrastructure', 'Mumbai, India', 'Full-time', '₹14-18 LPA', '2023-12-28', 'Python, SQL, Hadoop, Spark, ETL', 'Experience with big data technologies and cloud platforms', '2025-04-18 06:28:48', 8.00),
(13, 3, 'Cloud Solutions Architect', 'Design and implement cloud-based solutions', 'Hyderabad, India', 'Full-time', '₹18-25 LPA', '2023-12-20', 'AWS, Azure, Docker, Kubernetes, Terraform', 'Cloud certification and 4+ years of experience', '2025-04-18 06:28:48', 7.00),
(14, 4, 'Cybersecurity Analyst', 'Monitor and analyze security threats and implement security measures', 'Delhi, India', 'Full-time', '₹12-18 LPA', '2023-12-15', 'Network Security, Penetration Testing, SIEM, Ethical Hacking', 'Security certification and experience in threat detection', '2025-04-18 06:28:48', 8.50),
(15, 5, 'React Native Developer', 'Develop cross-platform mobile apps using React Native', 'Chennai, India', 'Contract', '₹8-12 LPA', '2023-12-25', 'JavaScript, React Native, Redux, TypeScript', 'Experience building and deploying mobile apps', '2025-04-18 06:28:48', 7.50),
(16, 6, 'Quantum Computing Researcher', 'Research and develop quantum computing algorithms', 'Bangalore, India', 'Full-time', '₹20-30 LPA', '2024-01-10', 'Quantum Computing, Physics, Mathematics, Python', 'PhD in Physics, Computer Science or related field', '2025-04-18 06:28:48', 8.00),
(17, 7, 'Machine Learning Engineer', 'Develop and deploy machine learning models', 'Pune, India', 'Full-time', '₹15-22 LPA', '2023-12-18', 'Python, TensorFlow, PyTorch, Data Analysis, ML Algorithms', 'Strong background in machine learning and statistical analysis', '2025-04-18 06:28:48', 7.20),
(18, 8, 'UX/UI Designer', 'Create user-centered designs for web and mobile applications', 'Remote', 'Full-time', '₹10-15 LPA', '2023-12-22', 'UI Design, UX Research, Figma, Adobe XD, Prototyping', 'Portfolio demonstrating UX/UI skills and experience', '2025-04-18 06:28:48', 7.80),
(19, 9, 'Blockchain Developer', 'Develop and implement blockchain solutions', 'Hyderabad, India', 'Full-time', '₹16-24 LPA', '2024-01-05', 'Blockchain, Solidity, Ethereum, Smart Contracts, JavaScript', 'Experience with blockchain platforms and protocols', '2025-04-18 06:28:48', 8.20),
(20, 10, 'IoT Solutions Developer', 'Design and develop IoT solutions for smart devices', 'Bangalore, India', 'Full-time', '₹12-18 LPA', '2023-12-28', 'IoT, Embedded Systems, C++, MQTT, Cloud Integration', 'Experience with IoT platforms and embedded systems', '2025-04-18 06:28:48', 8.50),
(21, 1, 'Backend Developer', 'Develop server-side logic and integrate with front-end elements', 'Delhi, India', 'Full-time', '₹12-16 LPA', '2023-12-20', 'Java, Spring Boot, Microservices, MySQL, API Design', 'Strong understanding of backend technologies and databases', '2025-04-18 06:28:48', 7.50),
(22, 2, 'AI Research Scientist', 'Lead research in artificial intelligence and develop new algorithms', 'Mumbai, India', 'Full-time', '₹18-25 LPA', '2024-01-15', 'Deep Learning, Neural Networks, Python, Research, NLP', 'PhD or Master\'s degree in AI, Machine Learning or related field', '2025-04-18 06:28:48', 8.00),
(23, 3, 'DevOps Engineer', 'Implement and maintain CI/CD pipelines and infrastructure', 'Pune, India', 'Full-time', '₹14-20 LPA', '2023-12-15', 'Jenkins, Docker, Kubernetes, Git, Linux, AWS', 'Experience with DevOps practices and tools', '2025-04-18 06:28:48', 7.00),
(24, 4, 'Network Security Engineer', 'Design and implement network security solutions', 'Hyderabad, India', 'Full-time', '₹15-22 LPA', '2023-12-30', 'Network Security, Firewalls, VPN, IDS/IPS, SIEM', 'Security certification and network infrastructure experience', '2025-04-18 06:28:48', 8.50),
(25, 5, 'Frontend Developer', 'Build responsive and interactive user interfaces', 'Chennai, India', 'Full-time', '₹10-15 LPA', '2023-12-25', 'JavaScript, React, Redux, HTML, CSS, Responsive Design', 'Portfolio showing frontend development skills', '2025-04-18 06:28:48', 7.50),
(26, 6, 'Data Scientist', 'Apply data science techniques to solve business problems', 'Bangalore, India', 'Full-time', '₹16-22 LPA', '2024-01-10', 'Python, R, SQL, Statistics, Machine Learning, Data Visualization', 'Experience with data analysis and statistical modeling', '2025-04-18 06:28:48', 8.00),
(27, 7, 'AR/VR Developer', 'Create augmented and virtual reality experiences', 'Mumbai, India', 'Full-time', '₹14-20 LPA', '2023-12-20', 'Unity3D, C#, 3D Modeling, AR Frameworks, VR Development', 'Experience developing AR/VR applications', '2025-04-18 06:28:48', 7.20),
(28, 8, 'Full Stack Developer', 'Work on both frontend and backend aspects of web applications', 'Remote', 'Full-time', '₹12-18 LPA', '2023-12-28', 'JavaScript, React, Node.js, Express, MongoDB, RESTful APIs', 'Experience with full stack development and modern frameworks', '2025-04-18 06:28:48', 7.80),
(29, 9, 'Game Developer', 'Develop engaging and interactive games', 'Pune, India', 'Full-time', '₹12-18 LPA', '2024-01-05', 'Unity, C#, Game Design, 3D Graphics, Physics', 'Portfolio of games and experience with game development', '2025-04-18 06:28:48', 8.20),
(30, 10, 'Embedded Systems Engineer', 'Design and develop firmware for embedded devices', 'Bangalore, India', 'Full-time', '₹14-20 LPA', '2023-12-22', 'Embedded C, Microcontrollers, RTOS, Electronics, IoT', 'Experience with embedded systems development', '2025-04-18 06:28:48', 8.50),
(31, 1, 'Software Development Engineer', NULL, 'Bangalore, India', 'Full-time', '₹12-18 LPA', '2023-05-20', 'Java, Spring Boot, React, AWS', 'Bachelor\'s degree in Computer Science or related field, 3+ years of experience in software development', '2025-04-21 18:17:54', 7.50),
(32, 2, 'Data Scientist', NULL, 'Hyderabad, India', 'Full-time', '₹14-20 LPA', '2023-05-25', 'Python, Machine Learning, SQL, TensorFlow', 'Master\'s degree in Data Science, Statistics or related field', '2025-04-21 18:17:54', 8.00),
(33, 3, 'Frontend Developer', NULL, 'Chennai, India', 'Full-time', '₹8-12 LPA', '2023-05-22', 'JavaScript, React, HTML/CSS, TypeScript', 'Experience with modern JavaScript frameworks', '2025-04-21 18:17:54', 7.00),
(34, 4, 'DevOps Engineer', NULL, 'Remote', 'Full-time', '₹15-22 LPA', '2023-05-30', 'Docker, Kubernetes, CI/CD, AWS', 'Experience with containerization and cloud platforms', '2025-04-21 18:17:54', 8.50),
(35, 5, 'Full Stack Developer', NULL, 'Pune, India', 'Full-time', '₹10-15 LPA', '2023-06-05', 'JavaScript, Node.js, React, MongoDB', 'Experience with full stack development', '2025-04-21 18:17:54', 7.50),
(36, 6, 'Product Manager', NULL, 'Bangalore, India', 'Full-time', '₹18-25 LPA', '2023-06-10', 'Product Management, Agile, UX/UI, Analytics', 'Experience in product management and development', '2025-04-21 18:17:54', 8.00),
(37, 7, 'UI/UX Designer', NULL, 'Mumbai, India', 'Full-time', '₹8-14 LPA', '2023-06-15', 'UI Design, UX Design, Figma, Adobe XD', 'Experience in designing user interfaces and experiences', '2025-04-21 18:17:54', 7.00),
(38, 8, 'Backend Developer', NULL, 'Delhi, India', 'Full-time', '₹10-16 LPA', '2023-06-20', 'Java, Python, Node.js, SQL', 'Experience in backend development and databases', '2025-04-21 18:17:54', 7.50),
(39, 9, 'Machine Learning Engineer', NULL, 'Hyderabad, India', 'Full-time', '₹16-22 LPA', '2023-06-25', 'Python, Machine Learning, Deep Learning, TensorFlow', 'Experience in machine learning and deep learning', '2025-04-21 18:17:54', 8.50),
(40, 10, 'Software Engineer Intern', NULL, 'Bangalore, India', 'Internship', '₹25-35K per month', '2023-07-01', 'Java, Python, JavaScript, SQL', 'Currently pursuing a degree in Computer Science or related field', '2025-04-21 18:17:54', 7.00),
(41, 1, 'Software Development Engineer', NULL, 'Bangalore, India', 'Full-time', '₹12-18 LPA', '2023-05-20', 'Java, Spring Boot, React, AWS', 'Bachelor\'s degree in Computer Science or related field, 3+ years of experience in software development', '2025-04-21 18:18:43', 7.50),
(42, 2, 'Data Scientist', NULL, 'Hyderabad, India', 'Full-time', '₹14-20 LPA', '2023-05-25', 'Python, Machine Learning, SQL, TensorFlow', 'Master\'s degree in Data Science, Statistics or related field', '2025-04-21 18:18:43', 8.00),
(43, 3, 'Frontend Developer', NULL, 'Chennai, India', 'Full-time', '₹8-12 LPA', '2023-05-22', 'JavaScript, React, HTML/CSS, TypeScript', 'Experience with modern JavaScript frameworks', '2025-04-21 18:18:43', 7.00),
(44, 4, 'DevOps Engineer', NULL, 'Remote', 'Full-time', '₹15-22 LPA', '2023-05-30', 'Docker, Kubernetes, CI/CD, AWS', 'Experience with containerization and cloud platforms', '2025-04-21 18:18:43', 8.50),
(45, 5, 'Full Stack Developer', NULL, 'Pune, India', 'Full-time', '₹10-15 LPA', '2023-06-05', 'JavaScript, Node.js, React, MongoDB', 'Experience with full stack development', '2025-04-21 18:18:43', 7.50),
(46, 6, 'Product Manager', NULL, 'Bangalore, India', 'Full-time', '₹18-25 LPA', '2023-06-10', 'Product Management, Agile, UX/UI, Analytics', 'Experience in product management and development', '2025-04-21 18:18:43', 8.00),
(47, 7, 'UI/UX Designer', NULL, 'Mumbai, India', 'Full-time', '₹8-14 LPA', '2023-06-15', 'UI Design, UX Design, Figma, Adobe XD', 'Experience in designing user interfaces and experiences', '2025-04-21 18:18:43', 7.00),
(48, 8, 'Backend Developer', NULL, 'Delhi, India', 'Full-time', '₹10-16 LPA', '2023-06-20', 'Java, Python, Node.js, SQL', 'Experience in backend development and databases', '2025-04-21 18:18:43', 7.50),
(49, 9, 'Machine Learning Engineer', NULL, 'Hyderabad, India', 'Full-time', '₹16-22 LPA', '2023-06-25', 'Python, Machine Learning, Deep Learning, TensorFlow', 'Experience in machine learning and deep learning', '2025-04-21 18:18:43', 8.50),
(50, 10, 'Software Engineer Intern', NULL, 'Bangalore, India', 'Internship', '₹25-35K per month', '2023-07-01', 'Java, Python, JavaScript, SQL', 'Currently pursuing a degree in Computer Science or related field', '2025-04-21 18:18:43', 7.00),
(51, 1, 'Software Development Engineer', NULL, 'Bangalore, India', 'Full-time', '₹12-18 LPA', '2023-05-20', 'Java, Spring Boot, React, AWS', 'Bachelor\'s degree in Computer Science or related field, 3+ years of experience in software development', '2025-04-21 18:18:45', 7.50),
(52, 2, 'Data Scientist', NULL, 'Hyderabad, India', 'Full-time', '₹14-20 LPA', '2023-05-25', 'Python, Machine Learning, SQL, TensorFlow', 'Master\'s degree in Data Science, Statistics or related field', '2025-04-21 18:18:45', 8.00),
(53, 3, 'Frontend Developer', NULL, 'Chennai, India', 'Full-time', '₹8-12 LPA', '2023-05-22', 'JavaScript, React, HTML/CSS, TypeScript', 'Experience with modern JavaScript frameworks', '2025-04-21 18:18:45', 7.00),
(54, 4, 'DevOps Engineer', NULL, 'Remote', 'Full-time', '₹15-22 LPA', '2023-05-30', 'Docker, Kubernetes, CI/CD, AWS', 'Experience with containerization and cloud platforms', '2025-04-21 18:18:45', 8.50),
(55, 5, 'Full Stack Developer', NULL, 'Pune, India', 'Full-time', '₹10-15 LPA', '2023-06-05', 'JavaScript, Node.js, React, MongoDB', 'Experience with full stack development', '2025-04-21 18:18:45', 7.50),
(56, 6, 'Product Manager', NULL, 'Bangalore, India', 'Full-time', '₹18-25 LPA', '2023-06-10', 'Product Management, Agile, UX/UI, Analytics', 'Experience in product management and development', '2025-04-21 18:18:45', 8.00),
(57, 7, 'UI/UX Designer', NULL, 'Mumbai, India', 'Full-time', '₹8-14 LPA', '2023-06-15', 'UI Design, UX Design, Figma, Adobe XD', 'Experience in designing user interfaces and experiences', '2025-04-21 18:18:45', 7.00),
(58, 8, 'Backend Developer', NULL, 'Delhi, India', 'Full-time', '₹10-16 LPA', '2023-06-20', 'Java, Python, Node.js, SQL', 'Experience in backend development and databases', '2025-04-21 18:18:45', 7.50),
(59, 9, 'Machine Learning Engineer', NULL, 'Hyderabad, India', 'Full-time', '₹16-22 LPA', '2023-06-25', 'Python, Machine Learning, Deep Learning, TensorFlow', 'Experience in machine learning and deep learning', '2025-04-21 18:18:45', 8.50),
(60, 10, 'Software Engineer Intern', NULL, 'Bangalore, India', 'Internship', '₹25-35K per month', '2023-07-01', 'Java, Python, JavaScript, SQL', 'Currently pursuing a degree in Computer Science or related field', '2025-04-21 18:18:45', 7.00),
(61, 1, 'Software Development Engineer', NULL, 'Bangalore, India', 'Full-time', '₹12-18 LPA', '2023-05-20', 'Java, Spring Boot, React, AWS', 'Bachelor\'s degree in Computer Science or related field, 3+ years of experience in software development', '2025-04-21 18:18:49', 7.50),
(62, 2, 'Data Scientist', NULL, 'Hyderabad, India', 'Full-time', '₹14-20 LPA', '2023-05-25', 'Python, Machine Learning, SQL, TensorFlow', 'Master\'s degree in Data Science, Statistics or related field', '2025-04-21 18:18:49', 8.00),
(63, 3, 'Frontend Developer', NULL, 'Chennai, India', 'Full-time', '₹8-12 LPA', '2023-05-22', 'JavaScript, React, HTML/CSS, TypeScript', 'Experience with modern JavaScript frameworks', '2025-04-21 18:18:49', 7.00),
(64, 4, 'DevOps Engineer', NULL, 'Remote', 'Full-time', '₹15-22 LPA', '2023-05-30', 'Docker, Kubernetes, CI/CD, AWS', 'Experience with containerization and cloud platforms', '2025-04-21 18:18:49', 8.50),
(65, 5, 'Full Stack Developer', NULL, 'Pune, India', 'Full-time', '₹10-15 LPA', '2023-06-05', 'JavaScript, Node.js, React, MongoDB', 'Experience with full stack development', '2025-04-21 18:18:49', 7.50),
(66, 6, 'Product Manager', NULL, 'Bangalore, India', 'Full-time', '₹18-25 LPA', '2023-06-10', 'Product Management, Agile, UX/UI, Analytics', 'Experience in product management and development', '2025-04-21 18:18:49', 8.00),
(67, 7, 'UI/UX Designer', NULL, 'Mumbai, India', 'Full-time', '₹8-14 LPA', '2023-06-15', 'UI Design, UX Design, Figma, Adobe XD', 'Experience in designing user interfaces and experiences', '2025-04-21 18:18:49', 7.00),
(68, 8, 'Backend Developer', NULL, 'Delhi, India', 'Full-time', '₹10-16 LPA', '2023-06-20', 'Java, Python, Node.js, SQL', 'Experience in backend development and databases', '2025-04-21 18:18:49', 7.50),
(69, 9, 'Machine Learning Engineer', NULL, 'Hyderabad, India', 'Full-time', '₹16-22 LPA', '2023-06-25', 'Python, Machine Learning, Deep Learning, TensorFlow', 'Experience in machine learning and deep learning', '2025-04-21 18:18:49', 8.50),
(70, 10, 'Software Engineer Intern', NULL, 'Bangalore, India', 'Internship', '₹25-35K per month', '2023-07-01', 'Java, Python, JavaScript, SQL', 'Currently pursuing a degree in Computer Science or related field', '2025-04-21 18:18:49', 7.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
