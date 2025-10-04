-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 04, 2025 at 03:09 PM
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
-- Database: `simple_otp_crud`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `ip`, `user_agent`, `created_at`) VALUES
(1, 5, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 11:38:05'),
(2, 5, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 11:39:24'),
(3, 5, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 11:54:49'),
(4, 5, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 12:02:27'),
(5, 5, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 12:57:27'),
(6, 5, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 12:57:49'),
(7, NULL, 'Logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 13:02:23'),
(8, 5, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 13:02:39'),
(9, 5, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 13:02:54');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `notes` text DEFAULT NULL,
  `owner_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `title`, `notes`, `owner_id`, `created_at`, `updated_at`) VALUES
(2, 'Caixa', 'Pedras', 5, '2025-09-23 17:26:02', NULL),
(3, 'Caixa', 'Pedras', 7, '2025-09-24 17:49:52', NULL),
(4, 'Outra Caixa', 'Mais Pedras', 7, '2025-09-24 17:50:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `otp_codes`
--

CREATE TABLE `otp_codes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` char(6) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp_codes`
--

INSERT INTO `otp_codes` (`id`, `user_id`, `code`, `expires_at`, `used`, `created_at`) VALUES
(28, 5, '884846', '2025-09-19 14:47:54', 0, '2025-09-19 12:42:54'),
(29, 5, '468689', '2025-09-19 14:48:25', 0, '2025-09-19 12:43:25'),
(30, 5, '764938', '2025-09-19 14:49:23', 0, '2025-09-19 12:44:23'),
(31, 5, '419090', '2025-09-19 14:50:57', 0, '2025-09-19 12:45:57'),
(32, 5, '354065', '2025-09-19 14:52:55', 0, '2025-09-19 12:47:55'),
(33, 5, '513285', '2025-09-19 14:53:52', 0, '2025-09-19 12:48:52'),
(34, 5, '142269', '2025-09-19 15:00:03', 1, '2025-09-19 12:55:03'),
(35, 5, '324345', '2025-09-19 15:04:39', 0, '2025-09-19 12:59:39'),
(36, 5, '227861', '2025-09-19 15:06:17', 0, '2025-09-19 13:01:17'),
(37, 5, '981335', '2025-09-19 15:08:08', 1, '2025-09-19 13:03:08'),
(38, 5, '578016', '2025-09-19 15:10:07', 1, '2025-09-19 13:05:07'),
(39, 5, '521563', '2025-09-19 15:14:26', 1, '2025-09-19 13:09:26'),
(40, 5, '346913', '2025-09-19 15:18:34', 1, '2025-09-19 13:13:34'),
(41, 5, '619940', '2025-09-19 18:14:32', 1, '2025-09-19 16:09:32'),
(42, 5, '297954', '2025-09-23 17:30:25', 1, '2025-09-23 15:25:25'),
(43, 6, '129853', '2025-09-23 17:31:57', 0, '2025-09-23 15:26:57'),
(44, 6, '175395', '2025-09-23 17:32:56', 0, '2025-09-23 15:27:56'),
(45, 6, '944319', '2025-09-23 17:32:56', 1, '2025-09-23 15:27:56'),
(46, 7, '577923', '2025-09-23 20:51:22', 0, '2025-09-23 18:46:22'),
(47, 7, '443550', '2025-09-23 20:52:06', 1, '2025-09-23 18:47:06'),
(48, 7, '139509', '2025-09-24 17:42:36', 1, '2025-09-24 15:37:36'),
(49, 7, '939872', '2025-09-24 17:49:50', 0, '2025-09-24 15:44:50'),
(50, 7, '464769', '2025-09-24 17:50:38', 1, '2025-09-24 15:45:38'),
(51, 5, '189667', '2025-10-04 13:43:05', 0, '2025-10-04 11:38:05'),
(52, 5, '606253', '2025-10-04 13:44:24', 0, '2025-10-04 11:39:24'),
(53, 5, '719010', '2025-10-04 13:59:49', 0, '2025-10-04 11:54:49'),
(54, 5, '214870', '2025-10-04 14:07:27', 0, '2025-10-04 12:02:27'),
(55, 5, '888968', '2025-10-04 15:02:22', 1, '2025-10-04 12:57:22'),
(56, 5, '862137', '2025-10-04 15:07:34', 1, '2025-10-04 13:02:34');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` char(64) NOT NULL,
  `revoked` tinyint(1) NOT NULL DEFAULT 0,
  `expires_at` datetime NOT NULL DEFAULT (current_timestamp() + interval 7 day),
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `token`, `revoked`, `expires_at`, `created_at`) VALUES
(27, 7, '87934791a192e288f8602c9d53c9049a06a6f8b588187a0d2d0ded971130eccf', 0, '2025-10-01 17:46:01', '2025-09-24 17:46:01'),
(29, 5, '4cf53744aac4cd191254e80df38d9a2a6dc747c35b34c2fda4f5454a9b6a0d08', 0, '2025-10-04 16:02:54', '2025-10-04 15:02:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(190) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `failed_attempts` int(11) DEFAULT 0,
  `last_failed` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `created_at`, `failed_attempts`, `last_failed`) VALUES
(5, 'zTdgKpkXgUqLscfJjqa0EaaAk2dn8ipCZRI1I2Ml5zzR', 'dWpMto+fkpiTgdBwiEnGFF92pqVHmp8+LezekRw8FI9qstJRQJJSjrdE1HYPYMI68QI=', '$2y$10$paiu6/Ittb3CX4FbzZowxOcayii8.y2/SdXDmkM.siMAqmlhgVH.6', '2025-09-19 14:42:54', 0, NULL),
(6, 'ZzDU9kcC7UyEPtKQ2PKGgFpXi6VOje9qLpZSt9SG69n/', 'YyYQcVMyGkcr1jmFnBNBfl2F6Jk8XKQyjecRk0c90bCAOsGc5v7uJShl7m0oCEkpE40luoSf', '$2y$10$7n5iH3D4gFPF3JTVs18HYe2pJ0WOV4ANtgKekFyuWBhpuqtg.k3VO', '2025-09-23 17:26:57', 0, NULL),
(7, 'Go/qQ7/uIeDh8o8oL2DZNUdU9Lk7Cz4WmpLl9vfu9+Rp', 'V9Ne/QYZ4Nv6tRgEW3sK+TCUW06MdRG8Zh4U2NkahgDfWClA8A3UpWbbCA4SvlWS', '$2y$10$dzy4DDmXuRca24mVYtUenOcIDzEoLfHYa//QGAnP11yUmhtyspFC2', '2025-09-23 20:46:22', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `code` (`code`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD CONSTRAINT `otp_codes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
