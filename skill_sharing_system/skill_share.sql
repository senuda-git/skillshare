-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2025 at 06:35 AM
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
-- Database: `skill_share`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `learner_id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected','expired') NOT NULL DEFAULT 'pending',
  `message` text NOT NULL,
  `proposed_time` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='This table stores all bookings. Basic workflow.';

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `learner_id`, `tutor_id`, `skill_id`, `status`, `message`, `proposed_time`, `created_at`) VALUES
(1, 4, 3, 1, 'rejected', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-06 14:04:37'),
(2, 4, 3, 1, '', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-06 19:05:45'),
(3, 5, 4, 2, '', 'Hi, I would like to learn about fgdfkghdkjgdhgkj hkjghdkjghdkjgh kdjghdkfjghdkjfg hdkfjgh dkfjghdkj ghdkfjg hdkj ghk', '2025-12-13 18:40:01', '2025-12-06 19:20:50'),
(4, 4, 3, 1, '', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-06 20:27:17'),
(5, 4, 3, 1, '', 'dggdfgdfg', '2025-12-13 18:40:01', '2025-12-06 20:28:23'),
(6, 3, 4, 2, 'rejected', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-08 06:30:38'),
(7, 3, 4, 3, 'approved', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-08 06:31:05'),
(8, 3, 4, 3, 'approved', 'Hi, I would like to learn about speaking plz', '2025-12-13 18:40:01', '2025-12-08 08:48:08'),
(9, 4, 3, 1, 'approved', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-08 08:50:04'),
(10, 6, 3, 1, 'approved', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-08 10:43:38'),
(11, 6, 4, 2, 'approved', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-08 10:43:46'),
(12, 6, 4, 3, 'rejected', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-08 10:43:54'),
(13, 3, 6, 4, 'expired', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-09 12:32:20'),
(14, 3, 6, 4, 'expired', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-10 23:48:23'),
(15, 9, 8, 1, 'approved', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-13 11:32:10'),
(16, 9, 7, 1, 'expired', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-13 11:32:16'),
(17, 10, 9, 1, 'expired', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-13 12:33:40'),
(18, 10, 7, 2, 'expired', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-13 12:33:44'),
(19, 10, 3, 3, 'expired', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-13 12:33:48'),
(20, 10, 7, 1, 'expired', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-13 12:34:06'),
(21, 11, 10, 1, 'approved', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-13 12:37:41'),
(22, 11, 10, 1, 'rejected', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-13 12:39:13'),
(23, 11, 10, 1, 'approved', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-13 12:39:18'),
(24, 10, 11, 1, 'approved', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-13 12:39:39'),
(25, 10, 11, 1, 'rejected', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-13 12:39:46'),
(26, 10, 11, 1, 'rejected', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-13 12:44:14'),
(27, 10, 11, 1, 'approved', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-13 12:44:37'),
(28, 10, 11, 4, 'rejected', 'Hi, I would like to learn about...', '2025-12-13 18:40:01', '2025-12-13 13:05:58'),
(29, 11, 10, 2, 'expired', 'Hi, I would like to learn about...', '2025-12-13 18:40:24', '2025-12-13 13:10:24'),
(30, 11, 10, 1, 'expired', 'Hi, I would like to learn about...', '0000-00-00 00:00:00', '2025-12-13 14:49:40'),
(31, 11, 10, 4, 'expired', 'Hi, I would like to learn about...', '0000-00-00 00:00:00', '2025-12-13 14:49:54'),
(32, 11, 10, 1, 'rejected', 'Hi, I would like to learn about...', '2025-12-24 13:44:00', '2025-12-13 18:13:58'),
(33, 11, 10, 4, 'approved', 'Hi, I would like to learn about...', '2025-12-26 13:44:00', '2025-12-13 18:14:19'),
(34, 11, 10, 4, 'rejected', 'Hi, I would like to learn about...', '2025-12-26 13:44:00', '2025-12-13 18:14:21'),
(35, 10, 11, 3, 'expired', 'Hi, I would like to learn about...', '2025-12-12 14:10:00', '2025-12-13 18:41:03'),
(36, 11, 10, 4, 'expired', 'Hi, I would like to learn about...', '2025-12-14 04:19:00', '2025-12-13 21:49:34'),
(37, 11, 10, 4, 'expired', 'Hi, I would like to learn about...', '2025-12-14 04:20:00', '2025-12-13 21:49:57'),
(38, 11, 4, 3, 'expired', 'Hi, I would like to jlfghfgh fghfgh fghf hf lgfhfghfg..', '2025-12-14 04:20:00', '2025-12-13 21:50:28'),
(39, 11, 4, 3, 'expired', 'Hi, I would like to learn about...', '2025-12-14 03:24:00', '2025-12-13 21:52:48'),
(40, 11, 10, 2, 'expired', 'phy6', '2025-12-14 03:25:00', '2025-12-13 21:53:09'),
(41, 11, 4, 3, 'expired', 'Hi, I would like to learn about...', '2025-12-14 04:25:00', '2025-12-13 21:55:05'),
(42, 11, 10, 1, 'rejected', 'Hi, I would like to learn about...', '2025-12-14 08:26:00', '2025-12-13 21:56:53'),
(43, 11, 10, 1, 'approved', 'Hi, I would like to learn about...', '2025-12-14 08:27:00', '2025-12-13 21:57:59'),
(44, 11, 10, 4, 'expired', 'Hi, I woufhgf fhgfhf', '2025-12-14 04:28:00', '2025-12-13 21:58:44'),
(45, 11, 10, 4, 'expired', 'Hi, I would like to learn about...', '2025-12-14 04:30:00', '2025-12-13 22:00:06'),
(46, 11, 4, 3, 'expired', 'Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...Hi, I would like to learn about...', '2025-12-14 08:32:00', '2025-12-13 22:02:22'),
(47, 11, 4, 3, 'expired', 'Hi, I would like to learn about...', '2025-12-14 04:49:00', '2025-12-13 22:19:20'),
(48, 11, 10, 4, 'expired', 'Hi, I would like to learn about...', '2025-12-14 05:11:00', '2025-12-13 22:41:08'),
(49, 12, 11, 3, 'expired', 'Hi, I would like to learn about...', '2025-12-14 06:23:00', '2025-12-13 23:53:44'),
(50, 13, 12, 1, 'expired', 'Hi, I would like to learn about... ghjghjg', '2025-12-14 07:25:00', '2025-12-14 01:54:32'),
(51, 13, 10, 2, 'approved', 'Hi, I would like to learn about...', '2025-12-14 08:27:00', '2025-12-14 01:55:07'),
(52, 11, 10, 4, 'approved', 'Hi, I would like to learn about...', '2025-12-14 07:56:00', '2025-12-14 02:24:18'),
(53, 14, 11, 4, 'approved', 'hjgjhghj', '2025-12-15 11:34:00', '2025-12-15 05:04:48');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `skill_id` int(5) NOT NULL,
  `skill_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='This table stores all skills. No categories, no subskills.';

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`skill_id`, `skill_name`, `description`) VALUES
(1, 'drama', '46546kijkhjk'),
(2, 'physics', 'gdfereerererefg'),
(3, 'speaking', 'ghfhfh'),
(4, 'photography', 'jgkjkhk');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(5) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='This table stores all users. Everyone is equal';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password_hash`, `bio`, `created_at`) VALUES
(2, 'hg4565', 'g45646', 'harry89@gmail.com', '$2y$10$H0sZr', NULL, '2025-12-05 23:29:48'),
(3, 'manny', 'Wu', 'manny@asd.uk', '$2y$10$804KL', NULL, '2025-12-06 08:37:46'),
(4, 'genny', 'sam', 'gensam@fg.jh', '$2y$10$0nNTk', NULL, '2025-12-06 13:03:59'),
(5, 'ben', 'denis', 'ben@den.us', '$2y$10$GjidY', NULL, '2025-12-06 19:20:15'),
(6, 'se', 'dil', 'sd@gm.az', '$2y$10$eiLdu', NULL, '2025-12-08 10:34:41'),
(7, 'fanny', 'mars', 'fanma@gm.nz', '$2y$10$VsB2s', NULL, '2025-12-10 21:32:51'),
(8, 'sam', 'ma', 'samma@fg.pi', '$2y$10$KaOQEnKqNnc26l29eEjbPOFjJODhSSz2hs1dsY57lP6DwLK3tBajO', NULL, '2025-12-13 08:53:24'),
(9, 'harry', 'Lesly', 'harry898@gmail.com', '$2y$10$ppGbJ2Be/Gg3AragrOPsaug4ZFJLcf9OJad4WX3mJChSli8BSCzgG', NULL, '2025-12-13 11:31:12'),
(10, 'manny', 'sam', 'mansam@fgb.lk', '$2y$10$QoWLk12uPP8Nt1nh4hL5MeiD2skxi4bcX2wNoMip.AjIEczRu.We6', NULL, '2025-12-13 12:32:58'),
(11, 'sammy', 'man', 'samman@sm.mk', '$2y$10$5wZ.GtUXzMtE3nc8CO7t.eA7Ntgz.82Yntq.Vu4rv1Iq9Z.lBpXXO', NULL, '2025-12-13 12:35:49'),
(12, 'vins', 'carmen', 'vin@car.kj', '$2y$10$AvkE7EK87Bbzs1Cmjh8Gheg4Z4ZOyxxrK9dUDmBFeN/PcT2crg8Bm', NULL, '2025-12-13 23:41:28'),
(13, 'damian', 'francis', 'damn@fra.as', '$2y$10$Vd6xVAQAlNyX6o6ixLgJJei9orGXYVI4WOW3knX2mGW0fvEqljoVq', NULL, '2025-12-14 01:53:09'),
(14, 'axe', 'sam', 'ax@sa.zx', '$2y$10$EsW4dYyKwigaAC6WvMzusulszJtSkjduqBPjUI1bbYuAUUPc3OoRi', NULL, '2025-12-15 05:03:07');

-- --------------------------------------------------------

--
-- Table structure for table `user_skills`
--

CREATE TABLE `user_skills` (
  `user_skill_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `level` enum('Beginner','Intermediate','Advanced') NOT NULL,
  `skill_description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='This table stores skills & users. M:N cardinality in here.';

--
-- Dumping data for table `user_skills`
--

INSERT INTO `user_skills` (`user_skill_id`, `user_id`, `skill_id`, `level`, `skill_description`, `created_at`) VALUES
(2, 4, 2, 'Advanced', '0', '2025-12-06 13:06:13'),
(4, 6, 4, 'Advanced', '0', '2025-12-08 10:35:30'),
(5, 6, 3, 'Intermediate', '0', '2025-12-08 10:38:30'),
(7, 3, 3, 'Intermediate', '', '2025-12-10 09:29:53'),
(8, 3, 4, 'Beginner', 'dgfhfghfgh', '2025-12-10 09:30:37'),
(9, 3, 2, 'Beginner', 'gdfgdfg', '2025-12-10 09:32:01'),
(10, 3, 1, 'Intermediate', '987987', '2025-12-10 09:36:51'),
(12, 7, 2, 'Intermediate', 'fdfgdfgdfggh hghfghfg fghfghf', '2025-12-10 21:33:05'),
(13, 7, 3, 'Beginner', ';kl;kl;ip\';\'gfghfhgf', '2025-12-10 21:49:26'),
(14, 7, 4, 'Intermediate', 'gd eyrwiu v cbc b r er bv cf h h fhfhf  j65464f65gf4h65fgh', '2025-12-11 00:26:13'),
(15, 7, 1, 'Intermediate', 'khkl', '2025-12-11 00:30:55'),
(17, 4, 1, 'Beginner', 'hjgjgjghjg5636766574543878 jfj fk', '2025-12-11 01:41:07'),
(18, 4, 4, 'Beginner', '5282', '2025-12-11 01:48:30'),
(19, 4, 3, 'Intermediate', 'qwewqeqwe pipip zxczxczc n,mn ,n 789789 46465 1321 31', '2025-12-11 01:55:59'),
(20, 8, 1, 'Advanced', 'jhkjhk', '2025-12-13 08:53:38'),
(21, 9, 1, 'Intermediate', 'fhfghf', '2025-12-13 11:31:29'),
(22, 9, 4, 'Intermediate', 'jgkjkhk', '2025-12-13 11:31:47'),
(23, 10, 1, 'Intermediate', '46546', '2025-12-13 12:33:11'),
(24, 10, 4, 'Advanced', '45465', '2025-12-13 12:33:19'),
(25, 10, 2, 'Beginner', '465464', '2025-12-13 12:33:31'),
(26, 11, 4, 'Advanced', '<script>alert(\'Hacked\')</script>', '2025-12-13 12:35:58'),
(27, 11, 3, 'Beginner', '456', '2025-12-13 12:37:18'),
(28, 11, 1, 'Intermediate', '465', '2025-12-13 12:37:26'),
(29, 12, 1, 'Intermediate', 'ghjg', '2025-12-13 23:45:52'),
(30, 13, 1, 'Intermediate', 'hi drama', '2025-12-14 01:53:37'),
(31, 11, 2, 'Beginner', 'jghjghjghj', '2025-12-14 02:30:07'),
(32, 14, 1, 'Intermediate', '46', '2025-12-15 05:03:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `bookings_ibfk_1` (`learner_id`),
  ADD KEY `tutor_id` (`tutor_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`skill_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_skills`
--
ALTER TABLE `user_skills`
  ADD PRIMARY KEY (`user_skill_id`),
  ADD UNIQUE KEY `uq_user_skill` (`user_id`,`skill_id`),
  ADD KEY `user_skills_ibfk_1` (`skill_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `skill_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user_skills`
--
ALTER TABLE `user_skills`
  MODIFY `user_skill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`learner_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`tutor_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_skills`
--
ALTER TABLE `user_skills`
  ADD CONSTRAINT `user_skills_ibfk_1` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`skill_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_skills_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
