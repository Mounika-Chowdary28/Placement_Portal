-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2025 at 03:37 PM
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
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `technologies` text DEFAULT NULL,
  `project_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `user_id`, `title`, `description`, `technologies`, `project_url`, `created_at`) VALUES
(1, 1, 'Movie Recommendation System', 'Built a recommendation system using machine learning algorithms', 'Python, Scikit-learn, Pandas, Flask', 'github.com/user/movie-recommender', '2025-04-18 06:26:20'),
(2, 1, 'Smart Attendance System', 'Created an automated attendance system using facial recognition technology', 'Python, OpenCV, TensorFlow, Flask', 'github.com/user/attendance-system', '2025-04-18 06:26:20'),
(3, 2, 'Fitness Tracking App', 'Developed a mobile app for tracking workouts and nutrition', 'React Native, Firebase, Redux', 'github.com/user/fitness-tracker', '2025-04-18 06:26:20'),
(4, 2, 'Social Media Dashboard', 'Built a dashboard to track and analyze social media metrics', 'React, D3.js, Node.js, MongoDB', 'github.com/user/social-dashboard', '2025-04-18 06:26:20'),
(5, 2, 'Movie Recommendation System', 'Built a recommendation system using machine learning algorithms', 'Python, Scikit-learn, Pandas, Flask', 'github.com/user/movie-recommender', '2025-04-18 06:26:20'),
(6, 3, 'Inventory Management System', 'Created a system for tracking inventory, sales, and purchases', 'Java, Spring Boot, MySQL, Thymeleaf', 'github.com/user/inventory-system', '2025-04-18 06:26:20'),
(7, 3, 'Social Media Dashboard', 'Built a dashboard to track and analyze social media metrics', 'React, D3.js, Node.js, MongoDB', 'github.com/user/social-dashboard', '2025-04-18 06:26:20'),
(8, 3, 'E-Commerce Website', 'Developed a full-stack e-commerce platform with product listings, shopping cart, and payment integration', 'React, Node.js, MongoDB, Stripe', 'github.com/user/ecommerce-project', '2025-04-18 06:26:20'),
(9, 4, 'E-Commerce Website', 'Developed a full-stack e-commerce platform with product listings, shopping cart, and payment integration', 'React, Node.js, MongoDB, Stripe', 'github.com/user/ecommerce-project', '2025-04-18 06:26:20'),
(10, 4, 'Social Media Dashboard', 'Built a dashboard to track and analyze social media metrics', 'React, D3.js, Node.js, MongoDB', 'github.com/user/social-dashboard', '2025-04-18 06:26:20'),
(11, 4, 'Task Management App', 'Built a task management application with user authentication and real-time updates', 'Angular, Firebase, TypeScript', 'github.com/user/task-manager', '2025-04-18 06:26:20'),
(12, 5, 'Movie Recommendation System', 'Built a recommendation system using machine learning algorithms', 'Python, Scikit-learn, Pandas, Flask', 'github.com/user/movie-recommender', '2025-04-18 06:26:20'),
(13, 6, 'Fitness Tracking App', 'Developed a mobile app for tracking workouts and nutrition', 'React Native, Firebase, Redux', 'github.com/user/fitness-tracker', '2025-04-18 06:26:20'),
(14, 6, 'Task Management App', 'Built a task management application with user authentication and real-time updates', 'Angular, Firebase, TypeScript', 'github.com/user/task-manager', '2025-04-18 06:26:20'),
(15, 6, 'Social Media Dashboard', 'Built a dashboard to track and analyze social media metrics', 'React, D3.js, Node.js, MongoDB', 'github.com/user/social-dashboard', '2025-04-18 06:26:20'),
(16, 7, 'Chat Application', 'Developed a real-time chat application with private and group messaging', 'Socket.io, Express.js, MongoDB, React', 'github.com/user/chat-app', '2025-04-18 06:26:20'),
(17, 7, 'Social Media Dashboard', 'Built a dashboard to track and analyze social media metrics', 'React, D3.js, Node.js, MongoDB', 'github.com/user/social-dashboard', '2025-04-18 06:26:20'),
(18, 8, 'Weather Forecast App', 'Developed a weather forecast application using public APIs', 'JavaScript, HTML, CSS, OpenWeatherMap API', 'github.com/user/weather-app', '2025-04-18 06:26:20'),
(19, 8, 'Fitness Tracking App', 'Developed a mobile app for tracking workouts and nutrition', 'React Native, Firebase, Redux', 'github.com/user/fitness-tracker', '2025-04-18 06:26:20'),
(20, 9, 'Fitness Tracking App', 'Developed a mobile app for tracking workouts and nutrition', 'React Native, Firebase, Redux', 'github.com/user/fitness-tracker', '2025-04-18 06:26:20'),
(21, 9, 'Inventory Management System', 'Created a system for tracking inventory, sales, and purchases', 'Java, Spring Boot, MySQL, Thymeleaf', 'github.com/user/inventory-system', '2025-04-18 06:26:20'),
(22, 10, 'Inventory Management System', 'Created a system for tracking inventory, sales, and purchases', 'Java, Spring Boot, MySQL, Thymeleaf', 'github.com/user/inventory-system', '2025-04-18 06:26:20'),
(23, 10, 'Weather Forecast App', 'Developed a weather forecast application using public APIs', 'JavaScript, HTML, CSS, OpenWeatherMap API', 'github.com/user/weather-app', '2025-04-18 06:26:20'),
(24, 11, 'Task Management App', 'Built a task management application with user authentication and real-time updates', 'Angular, Firebase, TypeScript', 'github.com/user/task-manager', '2025-04-18 06:26:20'),
(25, 11, 'Fitness Tracking App', 'Developed a mobile app for tracking workouts and nutrition', 'React Native, Firebase, Redux', 'github.com/user/fitness-tracker', '2025-04-18 06:26:20'),
(26, 11, 'Inventory Management System', 'Created a system for tracking inventory, sales, and purchases', 'Java, Spring Boot, MySQL, Thymeleaf', 'github.com/user/inventory-system', '2025-04-18 06:26:20'),
(27, 12, 'Smart Attendance System', 'Created an automated attendance system using facial recognition technology', 'Python, OpenCV, TensorFlow, Flask', 'github.com/user/attendance-system', '2025-04-18 06:26:20'),
(28, 12, 'Inventory Management System', 'Created a system for tracking inventory, sales, and purchases', 'Java, Spring Boot, MySQL, Thymeleaf', 'github.com/user/inventory-system', '2025-04-18 06:26:20'),
(29, 13, 'Task Management App', 'Built a task management application with user authentication and real-time updates', 'Angular, Firebase, TypeScript', 'github.com/user/task-manager', '2025-04-18 06:26:20'),
(30, 14, 'Weather Forecast App', 'Developed a weather forecast application using public APIs', 'JavaScript, HTML, CSS, OpenWeatherMap API', 'github.com/user/weather-app', '2025-04-18 06:26:20'),
(31, 15, 'Social Media Dashboard', 'Built a dashboard to track and analyze social media metrics', 'React, D3.js, Node.js, MongoDB', 'github.com/user/social-dashboard', '2025-04-18 06:26:20'),
(32, 15, 'Movie Recommendation System', 'Built a recommendation system using machine learning algorithms', 'Python, Scikit-learn, Pandas, Flask', 'github.com/user/movie-recommender', '2025-04-18 06:26:20'),
(33, 16, 'Fitness Tracking App', 'Developed a mobile app for tracking workouts and nutrition', 'React Native, Firebase, Redux', 'github.com/user/fitness-tracker', '2025-04-18 06:26:20'),
(34, 17, 'Task Management App', 'Built a task management application with user authentication and real-time updates', 'Angular, Firebase, TypeScript', 'github.com/user/task-manager', '2025-04-18 06:26:20'),
(35, 17, 'Weather Forecast App', 'Developed a weather forecast application using public APIs', 'JavaScript, HTML, CSS, OpenWeatherMap API', 'github.com/user/weather-app', '2025-04-18 06:26:20'),
(36, 17, 'Chat Application', 'Developed a real-time chat application with private and group messaging', 'Socket.io, Express.js, MongoDB, React', 'github.com/user/chat-app', '2025-04-18 06:26:20'),
(37, 18, 'Chat Application', 'Developed a real-time chat application with private and group messaging', 'Socket.io, Express.js, MongoDB, React', 'github.com/user/chat-app', '2025-04-18 06:26:20'),
(38, 19, 'E-Commerce Website', 'Developed a full-stack e-commerce platform with product listings, shopping cart, and payment integration', 'React, Node.js, MongoDB, Stripe', 'github.com/user/ecommerce-project', '2025-04-18 06:26:20'),
(39, 20, 'Chat Application', 'Developed a real-time chat application with private and group messaging', 'Socket.io, Express.js, MongoDB, React', 'github.com/user/chat-app', '2025-04-18 06:26:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
