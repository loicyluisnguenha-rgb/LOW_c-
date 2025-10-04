-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 04, 2025 at 08:53 PM
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
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('active','blocked') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `user_id`, `balance`, `status`, `created_at`) VALUES
(1, 8, 0.00, 'active', '2025-10-04 16:48:27'),
(2, 6, 700.00, 'active', '2025-10-04 16:48:27'),
(3, 7, 800.00, 'active', '2025-10-04 16:48:27'),
(5, 10, 1000.00, 'active', '2025-10-04 17:55:15');

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
(10, 5, 'Logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 13:25:59'),
(11, 8, 'Novo registo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 13:55:51'),
(12, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 13:56:23'),
(13, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 13:56:39'),
(14, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 13:59:00'),
(15, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 13:59:20'),
(16, 8, 'Logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 14:08:00'),
(17, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 14:08:16'),
(18, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 14:08:32'),
(19, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 14:21:15'),
(20, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 14:21:25'),
(21, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 14:23:04'),
(22, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 14:23:18'),
(23, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 14:25:34'),
(24, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 14:25:49'),
(25, 8, 'Logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 14:35:27'),
(26, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 14:35:43'),
(27, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 14:36:01'),
(28, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 14:37:27'),
(29, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 14:37:42'),
(30, 8, 'Logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 14:48:19'),
(31, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 14:49:43'),
(32, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 14:51:01'),
(33, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 14:51:14'),
(34, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 14:51:46'),
(35, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 14:52:31'),
(36, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 14:59:55'),
(37, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 15:00:10'),
(38, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 15:08:04'),
(39, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 15:08:22'),
(40, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 15:13:55'),
(41, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 15:14:13'),
(42, 7, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 15:14:48'),
(43, 7, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 15:15:16'),
(44, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 15:15:35'),
(45, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 15:15:51'),
(46, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 15:25:24'),
(47, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 15:25:36'),
(48, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 15:35:16'),
(49, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 15:35:41'),
(50, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 15:48:39'),
(51, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 15:49:04'),
(52, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 15:52:10'),
(53, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 15:52:29'),
(54, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 15:53:56'),
(55, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 15:54:17'),
(56, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 16:59:10'),
(57, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 16:59:26'),
(58, 8, 'Logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 17:27:25'),
(59, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 17:29:07'),
(60, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 17:29:21'),
(61, 9, 'Novo registo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 17:34:03'),
(62, 10, 'Novo registo (admin)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 17:54:51'),
(63, 8, 'Logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 17:55:29'),
(64, 10, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 17:55:54'),
(65, 10, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 17:56:16'),
(66, 10, 'Logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 17:59:16'),
(67, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 17:59:29'),
(68, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 17:59:52'),
(69, 8, 'Logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 18:02:00'),
(70, 8, 'Login iniciado (OTP enviado)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 18:06:19'),
(71, 8, 'OTP verificado e sessão criada', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 18:06:32'),
(72, 8, 'Logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-04 18:12:59');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `title` varchar(140) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(43, 6, '129853', '2025-09-23 17:31:57', 0, '2025-09-23 15:26:57'),
(44, 6, '175395', '2025-09-23 17:32:56', 0, '2025-09-23 15:27:56'),
(45, 6, '944319', '2025-09-23 17:32:56', 1, '2025-09-23 15:27:56'),
(46, 7, '577923', '2025-09-23 20:51:22', 0, '2025-09-23 18:46:22'),
(47, 7, '443550', '2025-09-23 20:52:06', 1, '2025-09-23 18:47:06'),
(48, 7, '139509', '2025-09-24 17:42:36', 1, '2025-09-24 15:37:36'),
(49, 7, '939872', '2025-09-24 17:49:50', 0, '2025-09-24 15:44:50'),
(50, 7, '464769', '2025-09-24 17:50:38', 1, '2025-09-24 15:45:38'),
(57, 8, '133457', '2025-10-04 16:00:46', 0, '2025-10-04 13:55:46'),
(58, 8, '465508', '2025-10-04 16:01:18', 1, '2025-10-04 13:56:18'),
(59, 8, '879336', '2025-10-04 16:03:54', 1, '2025-10-04 13:58:54'),
(60, 8, '475939', '2025-10-04 16:13:10', 1, '2025-10-04 14:08:10'),
(61, 8, '488777', '2025-10-04 16:26:09', 1, '2025-10-04 14:21:09'),
(62, 8, '666465', '2025-10-04 16:27:59', 1, '2025-10-04 14:22:59'),
(63, 8, '732193', '2025-10-04 16:30:28', 1, '2025-10-04 14:25:28'),
(64, 8, '940088', '2025-10-04 16:40:37', 1, '2025-10-04 14:35:37'),
(65, 8, '412523', '2025-10-04 16:42:21', 1, '2025-10-04 14:37:21'),
(66, 8, '283991', '2025-10-04 16:54:38', 0, '2025-10-04 14:49:38'),
(67, 8, '510113', '2025-10-04 16:55:56', 1, '2025-10-04 14:50:56'),
(68, 8, '540769', '2025-10-04 16:56:40', 1, '2025-10-04 14:51:40'),
(69, 8, '873252', '2025-10-04 17:04:49', 1, '2025-10-04 14:59:49'),
(70, 8, '387641', '2025-10-04 17:12:59', 1, '2025-10-04 15:07:59'),
(71, 8, '247685', '2025-10-04 17:18:50', 1, '2025-10-04 15:13:50'),
(72, 7, '401805', '2025-10-04 17:19:42', 1, '2025-10-04 15:14:42'),
(73, 8, '100762', '2025-10-04 17:20:30', 1, '2025-10-04 15:15:30'),
(74, 8, '455869', '2025-10-04 17:30:18', 1, '2025-10-04 15:25:18'),
(75, 8, '353797', '2025-10-04 17:40:10', 1, '2025-10-04 15:35:10'),
(76, 8, '472403', '2025-10-04 17:53:33', 1, '2025-10-04 15:48:33'),
(77, 8, '658805', '2025-10-04 17:57:05', 1, '2025-10-04 15:52:05'),
(78, 8, '658629', '2025-10-04 17:58:51', 1, '2025-10-04 15:53:51'),
(79, 8, '729287', '2025-10-04 19:04:05', 1, '2025-10-04 16:59:05'),
(80, 8, '426943', '2025-10-04 19:34:01', 1, '2025-10-04 17:29:01'),
(82, 10, '617830', '2025-10-04 20:00:49', 1, '2025-10-04 17:55:49'),
(83, 8, '878244', '2025-10-04 20:04:24', 1, '2025-10-04 17:59:24'),
(84, 8, '142847', '2025-10-04 20:11:14', 1, '2025-10-04 18:06:14');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrador do sistema'),
(2, 'user', 'Utilizador padrão');

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
(47, 8, '43eb55939ee286406cfcf497aeb4313c68a85dae287e744f2967786381455638', 0, '2025-10-04 18:49:04', '2025-10-04 17:49:04'),
(48, 8, '504f6829636aeca805131cda1809a5920dccfd7c18f761d97a065ccbad9d6b81', 0, '2025-10-04 18:52:29', '2025-10-04 17:52:29'),
(49, 8, '03a3569eadd1afec2ed0798865bfa68b7dfbe1dd25c5819726ad7cede0d1a000', 0, '2025-10-04 18:54:17', '2025-10-04 17:54:17');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `type` enum('deposit','withdraw','transfer_in','transfer_out') NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `account_id`, `type`, `amount`, `description`, `created_at`) VALUES
(1, 3, 'deposit', 1000.00, 'Deposito', '2025-10-04 17:02:49'),
(2, 1, 'transfer_out', 500.00, 'Transferência para conta 2', '2025-10-04 17:11:20'),
(3, 2, 'transfer_in', 500.00, 'Transferência recebida de conta 1', '2025-10-04 17:11:20'),
(4, 1, 'deposit', 500.00, 'Deposito', '2025-10-04 17:15:31'),
(5, 3, 'transfer_out', 200.00, 'Transferência para conta 2', '2025-10-04 17:15:59'),
(6, 2, 'transfer_in', 200.00, 'Transferência recebida de conta 3', '2025-10-04 17:15:59'),
(8, 5, 'withdraw', 500.00, 'Levantamento', '2025-10-04 17:58:25');

-- --------------------------------------------------------

--
-- Table structure for table `transfer_logs`
--

CREATE TABLE `transfer_logs` (
  `id` int(11) NOT NULL,
  `from_account_id` int(11) NOT NULL,
  `to_account_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transfer_logs`
--

INSERT INTO `transfer_logs` (`id`, `from_account_id`, `to_account_id`, `amount`, `created_at`) VALUES
(1, 1, 2, 500.00, '2025-10-04 17:11:20'),
(2, 3, 2, 200.00, '2025-10-04 17:15:59');

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
  `last_failed` timestamp NULL DEFAULT NULL,
  `role_id` int(11) DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `created_at`, `failed_attempts`, `last_failed`, `role_id`) VALUES
(6, 'ZzDU9kcC7UyEPtKQ2PKGgFpXi6VOje9qLpZSt9SG69n/', 'YyYQcVMyGkcr1jmFnBNBfl2F6Jk8XKQyjecRk0c90bCAOsGc5v7uJShl7m0oCEkpE40luoSf', '$2y$10$7n5iH3D4gFPF3JTVs18HYe2pJ0WOV4ANtgKekFyuWBhpuqtg.k3VO', '2025-09-23 17:26:57', 0, NULL, 2),
(7, 'ryD8yVaA2nzx8ejrioHRNqdSYpcH60lREy3KzPMo7b44o6gSgD80', '73/aPwlYuv/kVB8ZdwIm2/r2MVUeqk5bcX2NEbrx3MC9Nyo3LXXHxNaxAdI5o8Em', '$2y$10$dzy4DDmXuRca24mVYtUenOcIDzEoLfHYa//QGAnP11yUmhtyspFC2', '2025-09-23 20:46:22', 0, NULL, 2),
(8, 'jEKutFbnXgNMrPBqhDzyLjNi6pVomWOU8ScwtOrRxlN16AwZsUCTbBOJ', 'TZMqsrGhJUrM1r1SyhUB6gVRgkJYiW0FpGpP8zhkEaKIDJdYs19V82N79ywGGjLj/n4=', '$2y$10$g46m//Ls29W5eHiblcOxLuTKQQYtDAVmdO/HKiHY/BailEHKkmwja', '2025-10-04 15:55:46', 0, NULL, 1),
(10, 'R6QsJh79Vfv0fg6eYQ/1WF4aL+GBmW1HNwgJY7uawERob6eKLSs7w4hy', 'Fs31tt6vy3HyUz4ldqM/TIDNvi0/NWMxp5uKkyr5AYtQKSAAW1F5GMl7t8siBQIO+i0=', '$2y$10$Lg9e5QxS3JzZDUqVLTc9QOD3Y2vTjXREGTfcuc13wpLFd27BRE1n.', '2025-10-04 19:54:51', 0, NULL, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_accounts_user` (`user_id`);

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
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_transactions_account` (`account_id`);

--
-- Indexes for table `transfer_logs`
--
ALTER TABLE `transfer_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_transfer_from` (`from_account_id`),
  ADD KEY `fk_transfer_to` (`to_account_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_user_role` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transfer_logs`
--
ALTER TABLE `transfer_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_accounts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_transactions_account` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transfer_logs`
--
ALTER TABLE `transfer_logs`
  ADD CONSTRAINT `fk_transfer_from` FOREIGN KEY (`from_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_transfer_to` FOREIGN KEY (`to_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_logs_ibfk_1` FOREIGN KEY (`from_account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `transfer_logs_ibfk_2` FOREIGN KEY (`to_account_id`) REFERENCES `accounts` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
