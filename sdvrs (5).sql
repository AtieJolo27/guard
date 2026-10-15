-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2026 at 06:31 AM
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
-- Database: `sdvrs`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-illuminate:queue:restart', 'i:1791775685;', 2107135685);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `created_at`, `updated_at`) VALUES
(1, 'College of Informatics and Computing Sciences - ARASOF-Nasugbu Campus', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(2, 'College of Criminal Justice Education - ARASOF-Nasugbu Campus', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(3, 'College of Health and Sciences - ARASOF-Nasugbu Campus', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(4, 'College of Arts and Sciences - ARASOF-Nasugbu Campus', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(5, 'College of Accountancy, Business, Economics and International Hospitality Management - ARASOF-Nasugbu Campus', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(6, 'College of Teacher Education - ARASOF-Nasugbu Campus', '2026-04-23 06:33:45', '2026-04-23 06:33:45');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guards`
--

CREATE TABLE `guards` (
  `guard_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `violations_logged` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guards`
--

INSERT INTO `guards` (`guard_id`, `name`, `email`, `password`, `violations_logged`, `created_at`, `updated_at`) VALUES
(3, 'Albert Himaya', 'guard3@gmail.com', '$2y$12$De9288hyb2sorFbZLZeFv.xk/2LZh8LZFgAuCec9TJkmdggVSQjK.', 0, '2026-05-07 19:36:56', '2026-05-07 19:36:56'),
(5, 'Jolo Atie', 'jolo@gmail.com', '$2y$12$az44X6UMmQDDHzHWWLnw/OIY706o6TBB2vW/EfBIdrJuORbbl8zuO', 0, '2026-05-11 09:26:06', '2026-05-11 09:26:06'),
(6, 'John', 'johndoe@gmail.com', '$2a$12$GELQodtog76yRGbUqI1M1Oe5WAZ.rQc5Yo6Wa1ccneqHbSgdA1ux6', 0, NULL, NULL),
(7, 'John', 'john@gmail.com', 'johndoe', 0, NULL, NULL),
(9, 'Ariel Rivera', 'ariel.rivera@g.batstate-u.edu.ph', '$2y$12$3.u8Cn3qCpgJS8Xi7Ciw3eI9rTAtZuQ14ROg5dyrdMY/U3r9WEE7u', 0, '2026-10-11 19:44:07', '2026-10-11 19:44:07');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_08_14_170933_add_two_factor_columns_to_users_table', 1),
(5, '2026_04_04_000000_create_departments_table', 1),
(6, '2026_04_05_150855_create_programs_table', 1),
(7, '2026_04_05_150928_create_violation_descriptions_table', 1),
(8, '2026_04_05_150952_create_violations_table', 1),
(9, '2026_04_05_151030_create_students_table', 1),
(10, '2026_04_05_151055_create_records_table', 1),
(11, '2026_04_05_151117_create_suspensions_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `program_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `program` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`program_id`, `department_id`, `program`, `created_at`, `updated_at`) VALUES
(1, 5, 'Bachelor of Science in Accountancy', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(2, 5, 'Bachelor of Science in Management Accounting', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(3, 5, 'Bachelor of Science in Business Administration major in Human Resource Management', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(4, 5, 'Bachelor of Science in Business Administration major in Marketing Management', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(5, 5, 'Bachelor of Science in Business Administration major in Financial Management', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(6, 5, 'Bachelor of Science in Hospitality Management', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(7, 5, 'Bachelor of Science in Tourism Management', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(8, 4, 'Bachelor of Arts in Communication', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(9, 4, 'Bachelor of Science in Psychology', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(10, 4, 'Bachelor of Science in Fisheries and Aquatic Sciences', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(11, 4, 'Bachelor of Science in Food Technology', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(12, 2, 'Bachelor of Science in Criminology', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(13, 3, 'Bachelor of Science in Nursing', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(14, 3, 'Bachelor of Science in Nutrition and Dietetics', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(15, 1, 'Bachelor of Science in Information Technology', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(16, 6, 'Bachelor of Elementary Education', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(17, 6, 'Bachelor of Physical Education', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(18, 6, 'Bachelor of Secondary Education major in English', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(19, 6, 'Bachelor of Secondary Education major in Science', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(20, 6, 'Bachelor of Secondary Education major in Mathematics', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(21, 6, 'Bachelor of Secondary Education major in Filipino', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(22, 6, 'Bachelor of Secondary Education major in Social Studies', '2026-04-23 06:33:45', '2026-04-23 06:33:45');

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE `records` (
  `record_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `violation_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `records`
--

INSERT INTO `records` (`record_id`, `student_id`, `violation_id`, `created_at`, `updated_at`) VALUES
(1, 1, 8, '2026-04-16 06:33:46', '2026-04-16 06:33:46'),
(2, 2, 6, '2026-04-18 06:33:46', '2026-04-18 06:33:46'),
(3, 4, 11, '2026-04-20 06:33:46', '2026-04-20 06:33:46'),
(15, 2, 22, '2026-05-14 19:45:59', '2026-05-14 19:45:59'),
(16, 2, 22, '2026-05-14 19:46:31', '2026-05-14 19:46:31'),
(17, 2, 22, '2026-05-14 19:58:51', '2026-05-14 19:58:51'),
(18, 2, 22, '2026-05-14 19:59:07', '2026-05-14 19:59:07'),
(19, 2, 22, '2026-05-14 19:59:57', '2026-05-14 19:59:57'),
(20, 2, 22, '2026-05-14 20:00:16', '2026-05-14 20:00:16'),
(21, 2, 21, '2026-05-14 20:00:39', '2026-05-14 20:00:39'),
(22, 2, 22, '2026-05-14 20:00:50', '2026-05-14 20:00:50'),
(23, 2, 22, '2026-05-14 20:01:17', '2026-05-14 20:01:17'),
(24, 2, 22, '2026-05-14 20:01:26', '2026-05-14 20:01:26'),
(25, 2, 22, '2026-05-14 20:01:38', '2026-05-14 20:01:38'),
(26, 2, 22, '2026-05-14 20:02:59', '2026-05-14 20:02:59'),
(27, 2, 22, '2026-05-14 20:03:11', '2026-05-14 20:03:11'),
(28, 2, 22, '2026-05-14 20:03:24', '2026-05-14 20:03:24'),
(29, 2, 22, '2026-05-14 20:03:36', '2026-05-14 20:03:36'),
(30, 2, 22, '2026-05-14 20:05:03', '2026-05-14 20:05:03'),
(31, 2, 22, '2026-05-14 20:05:13', '2026-05-14 20:05:13'),
(32, 2, 22, '2026-05-14 20:05:40', '2026-05-14 20:05:40'),
(33, 2, 22, '2026-05-14 20:05:50', '2026-05-14 20:05:50'),
(34, 2, 22, '2026-05-14 20:06:00', '2026-05-14 20:06:00'),
(35, 2, 22, '2026-05-14 20:06:55', '2026-05-14 20:06:55'),
(37, 5, 9, '2026-09-23 16:50:08', '2026-09-23 16:50:08'),
(38, 3, 27, '2026-09-23 16:56:10', '2026-09-23 16:56:10'),
(39, 5, 27, '2026-09-28 16:38:35', '2026-09-28 16:38:35'),
(40, 4, 27, '2026-09-28 16:41:57', '2026-09-28 16:41:57'),
(41, 5, 1, '2026-09-28 16:44:47', '2026-09-28 16:44:47'),
(42, 5, 27, '2026-10-11 19:15:17', '2026-10-11 19:15:17'),
(43, 5, 27, '2026-10-11 19:16:47', '2026-10-11 19:16:47'),
(44, 5, 27, '2026-10-11 19:26:11', '2026-10-11 19:26:11'),
(45, 5, 27, '2026-10-11 19:29:56', '2026-10-11 19:29:56'),
(46, 5, 27, '2026-10-11 19:30:50', '2026-10-11 19:30:50'),
(47, 5, 27, '2026-10-11 19:31:25', '2026-10-11 19:31:25'),
(48, 5, 17, '2026-10-11 19:31:51', '2026-10-11 19:31:51'),
(49, 5, 9, '2026-10-11 19:34:51', '2026-10-11 19:34:51'),
(50, 4, 17, '2026-10-11 19:35:58', '2026-10-11 19:35:58'),
(51, 2, 21, '2026-10-14 19:22:23', '2026-10-14 19:22:23'),
(52, 2, 27, '2026-10-14 19:23:49', '2026-10-14 19:23:49'),
(53, 2, 27, '2026-10-14 19:24:35', '2026-10-14 19:24:35');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('2tz41wopo3gp95BejsCMqpZh7RrgE93LcufG5IU0', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiS25xeEkwUmR3RmtYR3kyNGFDZENrS3AwbW9iaW56TTdOWUdFWTRzYiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037997),
('2W372OAkKI5OdZDpmoe8ft6pqmLzpfi5h7PfWVzd', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiV05VdU1LUml3T2RQNjhJcjU1REozb1JZdFJtRE1HRGR2eHp1MjY0cSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792038214),
('58WjgOg0b3fhUZfWcbACbg5QVbxxZCPWVSGGeVoZ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQjFEeHk4WVZZWk5yYzVadHYzQU9QQUdVSFYwMm9ENDkyQmNxZXpKQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792038215),
('5GkNXxbAI54NZVMeWrmenL46allD0ldx6aEl2gOJ', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiV2pUMkVhaU1iMUNKdFduM05KNElNYnRzU2ltcEVqdkFxZ2ZQTk5CaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1792038606),
('6U3NNMl0DwxlsFYgURiCSa9S7Xk1JV0OeI9GngPw', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZkhXWXp6RTR0bFVrcXo2SXdmT2RvRnpvOTRGRkJQSkJQeHlaZjdZWiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792038426),
('6XVamPwcEIfBooIjfgoiT0po6zrlE2PqtPAsUReI', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiS1cwaDhkd1ZRMnNMdkZPSXAyYTdRRWVlZHVVekdXdWRVRGhqMVVZciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1792037802),
('84bASsSN1C8u42pVc7VTgsJNUEYqfzrbEKi9sTUj', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUXh4TkhsSTVoVGxSMjBGUU5ZMHBOU0RZcWt2ZWZCamU2aUF5bW80RiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037418),
('8B6q4GdtB0TJhoFtuS9vk3Rf4p2Mhtv89h9Utbkc', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWDhCTGduMlVmTVIyWUVLZTBmMXBOWXM5c3dBRnVoNm1DWHo5MzlFeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1792037908),
('8St8XhLadxNJZRJANagRGuwIL1cit5OtCTDJy6go', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYkUwV0RKVzhyblZqbVVodVpTeWpUak5uSDZadGs1ZVFmeGRHNlBTZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1792037520),
('9nvyCElKFO7Wy4OkaX1hyx7dJg1zT7nA5LVqvh9R', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY1hqWUlsa3lCTHhVQmZ5cjJIZ0pPaXBkNFBZMTE4bVYzRnZtbnlxYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1792037993),
('9vGBLoHbYSCDSrHHKV43r6KDSczV02oNC91fvujz', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidUVsak56M2NMTTZKcXlCTXdFNzdCTnU4NVljdEF5NWlEZWp0V1ExbyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1792037414),
('9Y7T9TCZttq8vh2u8LN1xJ24D3lI4KR6DZ7LKz0x', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYkhqYjdsSkpzd01qSVJoUUxQT3NPelpBNURDQW5SZlBBOVdqUGhtdCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792038062),
('aCYG2BtOtcS0Xr09k2D9tndYnP2sSWuTZNI1eZoS', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidEdxTmJyWEpjcnJWUzJ1WjJ3a2Zia29kNkhWdGNCYTR1RmU4ZFhJeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037418),
('aRQqOWuV3dB1Q2SuaSpnphMND4VfnADCnfEl8rvR', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYU1nQTFkNnRGVmhGbmRFcmI0NU9XajhzS05jcWpqQ2t4c3hqdjJKeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037911),
('BvowHi1xiqlLMsUIC2axI7VCAUBQi6fWK2BD4A53', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVFdQU2xKWUdxR1F4bHRYa3BNNUlFUEpVcUR2TjMweWs5U3Rvc0c5VSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1792038643),
('cjnhjlyfF4mN3kqQr8iH27Z84ITI91EkVTBL1GKm', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUUp0WlQxQ1dFWTF6RnFQQUNBVkZTM0FyT25DVzE0cXBnWWR5c0FJciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1792038211),
('clv2BNPc9wdX22PE6TvRliIWuQz4lQ2fonM6plTv', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYTl3dTZZR01WeENlR3IzMWtCSWphYTVsc05hQmZONDBNZlRnUEdjWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792036333),
('cn4BlhqhNoo0VjCAwSsNdpl862Wc53SW02WCyc1I', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM2NCWm1EbTVqOUtaUkJXOEg3bWtOMVF2bHZPc3hmaTRFaFhYTElhUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792038078),
('eTasDZSauFLgcdTwUflcQKwqZsIUNq0xnwqPDRFx', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQnRYYWdsRHV0RUR0Ujl5MWd6SUhybElyVW93SFF4NWRDVWJnWUkzSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1792037113),
('Fa6OwtkecNv79bRT8xyn13RYEPXmdMjUdAX5SuP0', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib1dDdjlKSG5WNE4ycjNHWmF1dEdkZTZ4bkxxa0pxWVNEZ0xHMHJpUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037908),
('fXKAdu0EV3S6tq63U1ug9mFnbOWpMJmQ3US2mbUn', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUDJZTnljWDFKZVkzcnFKbTVEajB1S01WM1BZNnN4UWpjbHI2NkRncyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037803),
('g8ugmCWZIE1lRGTyWMn0EIn63O8MkhUIkhJLvkx6', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUWZ6YmNyRUlTWTZjQWNGc2tLZW9ZWUphYjhmbE9ISlFBSlJHZm5FdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1792038423),
('GEKQuyjLkhbPVRnQwbub1GjmaRNAA8wWN4ltdhFF', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieW9EQk1nalpvZkQzV1VsbEFIMEhmYXJHNW1NdDNyWWxOT2Z1Njk1TyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037113),
('gULZpR84xGLnMfhJ3CPrUAC0TAynQzIf04j6co2l', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia2J0ZDgzYzJVanJSMEI0WEF1all6bU85bE04SU9PU3JTTXVORHpMSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1792037569),
('H62xwyR9AD9ZrAlZg6iik2FE7mdSS4ni9eKraE5R', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSUN1UTM4UW1XamM3YUNnbFI1YkFwWklpalBZZVpTdDVnWlUxQzVlaSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037524),
('H8jGBZ7jetpSUkQM9HiylE2waaP4ndY30fJVSFm1', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOVk3dE42T3l1ZXVqQ2JOd0VadGN1OENJM21UeHBtVUZHcE1WeHVtaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1792036329),
('hffjXgxPW7IVHwfdigwBn67qWE5AdnrHOGK0doGB', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiS3FsMEdMbmNZY3p4VHplcG5uUE9mN1doanQxY2JGMDM3Q0NraDVFbSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037807),
('HjYoNgDAIzYgKuxKrGruY1230kytWx9gSaG9dQQK', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia1JYMFFYcHBmMFZvQTg2S2piVDVqS2JkYnpsNVZmS2xwZ3NkTVlPMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792034319),
('k3B0dAL3zrIh878sshsp63Ia9aLmUkWOWcxdjx69', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSDg3dnEwNGtSSVdDbjloTTg3a1U4QjJrRnlUSTR2b2xXd1ViNnRqRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792038077),
('KnjKzN5aBm1GaJ0FywGXr4CtRI9B9sJwLGzayzgn', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQjFyZEFqYXJuMWVoWk44aXNReFdjZ05ZeDJaa3FMMm01ZFdUaUZ5SCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792036330),
('LKUciBF3Uo1ismlv6NGgKheOPofjCmqVEBogOmYM', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUm5rbVNnZjFOMXFwOUJmdzdFUHdzRmpJT2ptVmFoTkZ6S0pqQjFsNCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792038423),
('mHRGbNp6dbLMPhQ2X2LJPCjeNJoN40qrDbBMPEmQ', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiN1Rqd3U0WkdHSjdoc2hVYXZsUlh5ZHVEWlJMakNSV0V0RGFLRlF5OSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792038075),
('Mnh68ZA4nlo4s1W3KzOahOchbigAY1DS9qD08UDQ', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSUFMMGR6YUFpUUVnRU1PejBRa3F4eVA5MXJ0VVBPaklSd1hxRkFmUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792038607),
('MOuurYqoodBcotHpcuBPV3Fmm7XyaVl5ppOwJOHT', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieVFqbDBWODZGelpSNkRPQ2NyZHZVUUp1NU9UMkxGRzRDQURuYzJ0ZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792038211),
('nd0uRe5s0pWVQp3ncGD4dQyCVu54ChvopihS2nXM', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWjhuRW82aVpUeGZRY2FXTTBZTWlSb1lnQVEyS3cyemNaUkwxdE8xTCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1792038061),
('nyKI8QT7ADs7fpRv2XzxQ7Y0anw4wgJe5aPltaYi', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVnhCQlRPcnhoVG9lbHhFNHZIZW5DTVdJdE5xbTNRUVpRZG1mQVJUSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037739),
('Oc1MtAKhma1YPGgGT4Q7acRC37XHXNvjQgbdoRWb', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib3pOQko2T2VXVkdUNTMzNW5hYTZVSUsxT1p4Rm41eE5WbXpONGkwZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037570),
('OtWfqdz6dayHLX2nbaP5kIE7qZ4eM8r7wjS43twT', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQmNNbHRzN3BKNTh6ZDBhRWRVWkJkRWE2SGJIWm81MkFtZldHVzhBVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037739),
('PTmJ3UM5n7vAnny2PgBS7vYdx5V4rPHID1ODN1KN', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieVNBOEhvQm9KT0VqWGM5am82QWtCNkZveHRYT0FBb1dJZ0lPcHZvVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037573),
('PVdiWDdvcDWUoW7V9MFtWg6VOvfZiozLG8pMrj54', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRTNiZnBEVFZlR1BxNEpxRGxQVWhNcnliSWNCbTE0QkcwdHR5bjhzZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037415),
('qg2slTwxeTDnhAKpFREtzPFld6Dkz4JpJ5hOObLn', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ1Q5TUM1OGlkV2pwdDFZUXpxMkpDVUZJNldiWktkZFhMM0hhTXE1cyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037117),
('Qo8IqiKqv1TSkRhVO1PTr4GJYgFfH5npocrAjCPr', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQUpNMTI2cGhQc3VmQThobW5NWlp1QmY4dXBRY3Bnemh5SFNndnNLdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037996),
('r3OEETRLUxy7dYmUh2kipHtHJ1t1Bs9af6HRCITs', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZHhmOEx0N0VUT254UjA2YWZld2pMckFIYUs5S25jUmNVTWhDbERRSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792038643),
('rFOWNoDxft5E6AD3q08ZUClB9rC4ddqiCSjn5aQR', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVWVTejF0a2JITXVvT2twV09sa1c1V0NFbG4zemRGRDl2Tk9wRXFqTCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037993),
('rjlPnxyuQZPXEnYWNVB6JQ7l1cyAQTVTpCejN90L', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiSjdZRW83b2N2VGVSMWZSOXZMbGZpRkpidVdlN21jbWVQa2JHblc4YyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjg6Imd1YXJkX2lkIjtpOjk7czoxMDoiZ3VhcmRfbmFtZSI7czoxMjoiQXJpZWwgUml2ZXJhIjtzOjExOiJndWFyZF9lbWFpbCI7czozMjoiYXJpZWwucml2ZXJhQGcuYmF0c3RhdGUtdS5lZHUucGgiO30=', 1792038081),
('sjBnwxf6pPhz7d27loGzh342htzsWBclFspiWJVT', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQTRJMW1VTjdaRlhRbUJ5VXp3NVNmZ3NnUDlJcExBYXo5bVZNcjQyUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037911),
('taCMmXs3Az95ACVfmGYQ0wpa3NfyGqxJWF0u7a9i', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiaGVSMzVYRkNvVTh5ZnFQR2FsNVlybWZyMW9tYURtV0V2VWxwSGhlZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjg6Imd1YXJkX2lkIjtpOjk7czoxMDoiZ3VhcmRfbmFtZSI7czoxMjoiQXJpZWwgUml2ZXJhIjtzOjExOiJndWFyZF9lbWFpbCI7czozMjoiYXJpZWwucml2ZXJhQGcuYmF0c3RhdGUtdS5lZHUucGgiO30=', 1792038000),
('tPV1GwshXH2Fso8VRVjvYThc3KgY7REUkZKpHeKL', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiTlRJTjF3SHg3R0lMWjc1TzdiVnJkdXNIYnFlZFBzR2RFdk9YWVUxaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjg6Imd1YXJkX2lkIjtpOjk7czoxMDoiZ3VhcmRfbmFtZSI7czoxMjoiQXJpZWwgUml2ZXJhIjtzOjExOiJndWFyZF9lbWFpbCI7czozMjoiYXJpZWwucml2ZXJhQGcuYmF0c3RhdGUtdS5lZHUucGgiO30=', 1792038219),
('tRSRWpcNy1hcujTO0cNHFKnvAyfAGmwfWmcG8ZW3', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSW84UVBwUllNdGtaREQxSWd5OU1CajZqWmh4aUJNZ2NKM3ZkOHhhbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037521),
('vfLJNTvvYkosnjH0ZKrCXCr20rz7YNZhkL5Ph8Fu', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMEdrQllnZm1BdFNLaUU5enhRdFdORkxrakxpcGtIS1R1Q0xkZjVIWiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037806),
('VgnxrpsaFnnhlwn7Gu9a01mmNoVt8JtgVdqFe1GS', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMVk5QzJYOXd5bmtkczloZzVsbEk4ek0yck9KdVlwcE5WRG1HUGJrMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037573),
('wfwwUIsDU8VDhrTR6koueKwt0RDgqEIr5Q9OIWv3', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZElXUHhQTzFjRElnNmJlVk9aUnVRMnk5MUZRbWFjOVdYM1plSEJ5TCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1792038074),
('XvVFdysE9yIve0yUq01hR65TyTOdaUUxZfpLANLK', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib21XVFFNZG5rWGtVdHBTeUVNNlRLckhEd2lyZWlZeXJMYWU3cXhzeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1792037736),
('ykYDDRW7FCXwfR4ExRpk6S9ubCueCEJmB1LzA7OE', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRFN6RmRhYlg2SjdodFVGY3RXR01OZkZQV1BrcGMyZ3hRUDNwRHVQTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037736),
('zCjdyJlYbFjtXVSVkip2TAS8DpJrII2wcbjSiBam', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.8078.4 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM3NqWDczWm01bUpNOVFEeDl4OWNnZHhzeHh1RzA3RjJRcEpLa1AwRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792037524),
('ZeNM6rYNPRED0CD1FnaFIivaFrWTSpyOWJV4n9TJ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiaVFNWXVOMjdneE5ybGt0dkt5cGxsaTBTZzRmMUdVaXdkRkV6T09WOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjg6Imd1YXJkX2lkIjtpOjk7czoxMDoiZ3VhcmRfbmFtZSI7czoxMjoiQXJpZWwgUml2ZXJhIjtzOjExOiJndWFyZF9lbWFpbCI7czozMjoiYXJpZWwucml2ZXJhQGcuYmF0c3RhdGUtdS5lZHUucGgiO30=', 1792038233);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `sr_code` varchar(255) NOT NULL,
  `program_id` bigint(20) UNSIGNED NOT NULL,
  `gsuite` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `student_profile` varchar(255) DEFAULT NULL,
  `year_level` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `student_name`, `sr_code`, `program_id`, `gsuite`, `password`, `student_profile`, `year_level`, `created_at`, `updated_at`) VALUES
(1, 'Francis Dave H. Limboc', '23-70565', 15, '23-70565@g.batstate-u.edu.ph', '$2y$12$8KhQcDeb/WrAyuKAtW/8O.EEhWxV4fGlOM4LaaEoSrJkQgVAH772S', 'studentpfp/pfp.png', 'Third Year', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(2, 'John Carlo M. Barcelon', '23-77394', 15, '23-77394@g.batstate-u.edu.ph', '$2y$12$MJrdAjVvyVQM5/n1lvlk7OGOVHg9hcGIsxnLzo5cQguEuc7sRNyjS', 'studentpfp/carlopfp.png', 'Second Year', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(3, 'Immanuel S. Siyasat', '23-78101', 15, '23-78101@g.batstate-u.edu.ph', '$2y$12$GRB2sI4YwIgnCphuEP8W9.4UdJURFKasxLm0wNzffUtlSEKgdwVIK', 'studentpfp/immanpfp.png', 'First Year', '2026-04-23 06:33:46', '2026-04-23 06:33:46'),
(4, 'Christoper C. Ditaunon', '23-70269', 15, '23-70269@g.batstate-u.edu.ph', '$2y$12$SQlkSKQgIIRBQBmETwfWPOip49qxjcLIFzzVxUvdHguW8cqIQRPpW', 'studentpfp/adengpfp.jpg', 'Third Year', '2026-04-23 06:33:46', '2026-04-23 06:33:46'),
(5, 'Jolo G. Atie', '23-76468', 15, '23-76468@g.batstate-u.edu.ph', '$2y$12$cMgu4WomfBv6sW2gkHJFIOhcjxd8TgRT1H61OVeMyTGREaxpWn8XC', 'studentpfp/jolopfp.jpg', 'Third Year', '2026-04-23 06:33:46', '2026-04-23 06:33:46');

-- --------------------------------------------------------

--
-- Table structure for table `suspensions`
--

CREATE TABLE `suspensions` (
  `suspension_id` bigint(20) UNSIGNED NOT NULL,
  `record_id` bigint(20) UNSIGNED NOT NULL,
  `num_of_days_suspended` int(11) NOT NULL,
  `remaining_days` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suspensions`
--

INSERT INTO `suspensions` (`suspension_id`, `record_id`, `num_of_days_suspended`, `remaining_days`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 0, '2026-04-23 06:33:46', '2026-04-23 06:33:46'),
(2, 3, 5, 0, '2026-04-23 06:33:46', '2026-04-23 06:33:46'),
(13, 15, 9, 0, '2026-05-14 19:45:59', '2026-05-14 19:58:14'),
(14, 16, 12, 0, '2026-05-14 19:46:31', '2026-05-14 19:58:26'),
(15, 17, 12, 0, '2026-05-14 19:58:51', '2026-05-14 19:59:14'),
(16, 18, 12, 0, '2026-05-14 19:59:07', '2026-05-14 19:59:31'),
(17, 19, 12, 0, '2026-05-14 19:59:57', '2026-05-14 20:00:20'),
(18, 20, 12, 0, '2026-05-14 20:00:16', '2026-05-14 20:00:45'),
(19, 21, 9, 0, '2026-05-14 20:00:39', '2026-05-14 20:01:04'),
(20, 22, 12, 0, '2026-05-14 20:00:50', '2026-05-14 20:01:28'),
(21, 23, 12, 0, '2026-05-14 20:01:17', '2026-05-14 20:01:53'),
(22, 24, 12, 0, '2026-05-14 20:01:26', '2026-05-14 20:02:18'),
(23, 25, 12, 0, '2026-05-14 20:01:38', '2026-05-14 20:02:43'),
(24, 26, 12, 0, '2026-05-14 20:02:59', '2026-05-14 20:03:22'),
(25, 27, 12, 0, '2026-05-14 20:03:11', '2026-05-14 20:03:47'),
(26, 28, 12, 0, '2026-05-14 20:03:24', '2026-05-14 20:04:12'),
(27, 29, 12, 0, '2026-05-14 20:03:36', '2026-05-14 20:04:36'),
(28, 30, 12, 0, '2026-05-14 20:05:03', '2026-05-14 20:05:26'),
(29, 31, 12, 0, '2026-05-14 20:05:13', '2026-05-14 20:05:51'),
(30, 32, 12, 0, '2026-05-14 20:05:40', '2026-05-14 20:06:16'),
(31, 33, 12, 0, '2026-05-14 20:05:50', '2026-05-14 20:06:41'),
(32, 34, 12, 0, '2026-05-14 20:06:00', '2026-05-14 20:07:06'),
(33, 35, 12, 0, '2026-05-14 20:06:55', '2026-05-14 20:07:30'),
(36, 37, 3, 3, '2026-09-23 16:50:08', '2026-09-23 16:50:08'),
(37, 42, 3, 3, '2026-10-11 19:15:17', '2026-10-11 19:15:17'),
(38, 45, 3, 3, '2026-10-11 19:29:56', '2026-10-11 19:29:56'),
(39, 48, 6, 6, '2026-10-11 19:31:51', '2026-10-11 19:31:51'),
(40, 49, 4, 4, '2026-10-11 19:34:51', '2026-10-11 19:34:51'),
(41, 50, 6, 6, '2026-10-11 19:35:58', '2026-10-11 19:35:58'),
(42, 51, 12, 12, '2026-10-14 19:22:23', '2026-10-14 19:22:23'),
(43, 53, 3, 3, '2026-10-14 19:24:35', '2026-10-14 19:24:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `violations`
--

CREATE TABLE `violations` (
  `violation_id` bigint(20) UNSIGNED NOT NULL,
  `violation_name` varchar(255) NOT NULL,
  `violation_weight` varchar(255) NOT NULL,
  `description_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `violations`
--

INSERT INTO `violations` (`violation_id`, `violation_name`, `violation_weight`, `description_id`, `created_at`, `updated_at`) VALUES
(1, 'Unbecoming Behavior', 'MINOR', 1, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(2, 'Classroom Rules Violation', 'MINOR', 2, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(3, 'Excessive Noise', 'MINOR', 3, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(4, 'Unauthorized Posting', 'MINOR', 4, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(5, 'Provocation to Fight', 'MINOR', 5, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(6, 'Littering / Spitting', 'MINOR', 6, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(7, 'Cutting Classes', 'MAJOR Set A', 7, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(8, 'Academic Dishonesty – Plagiarism', 'MAJOR Set A', 8, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(9, 'Defiance of University Authority', 'MAJOR Set A', 9, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(10, 'Disruptive Use of Mobile Device', 'MAJOR Set A', 10, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(11, 'Smoking on Campus', 'MAJOR Set B', 11, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(12, 'Possession / Use of Alcohol', 'MAJOR Set B', 12, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(13, 'Bullying / Intimidation', 'MAJOR Set B', 13, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(14, 'Unrecognized Fraternity / Sorority Membership', 'MAJOR Set B', 14, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(15, 'Vandalism', 'MAJOR Set C', 15, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(16, 'Slight Physical Assault', 'MAJOR Set C', 16, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(17, 'Possession of Bladed Weapons', 'MAJOR Set C', 17, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(18, 'Theft of Personal Property', 'MAJOR Set D', 18, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(19, 'Lewd / Immoral Conduct', 'MAJOR Set D', 19, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(20, 'Forgery / False Documents', 'MAJOR Set D', 20, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(21, 'Drug Possession / Use', 'MAJOR Set E', 21, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(22, 'Unlawful Possession of Firearms', 'MAJOR Set E', 22, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(23, 'Involvement in Hazing', 'MAJOR Set E', 23, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(24, 'High Crime (Rape / Arson / Robbery)', 'MAJOR Set F', 24, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(25, 'Criminal Conviction (Moral Turpitude)', 'MAJOR Set F', 25, '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(27, 'No ID', 'MINOR', 26, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `violation_descriptions`
--

CREATE TABLE `violation_descriptions` (
  `description_id` bigint(20) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `violation_descriptions`
--

INSERT INTO `violation_descriptions` (`description_id`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Behavior unbecoming of a young adult including howling, loitering, public display of affection, eating in class, and similar acts.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(2, 'Violation of classroom rules set by the instructor.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(3, 'Making excessive noise inside or outside the classroom.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(4, 'Posting of notices or posters without prior written approval from the university.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(5, 'Provocation to fight, whether verbal or physical.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(6, 'Spitting, littering, or throwing waste within campus premises.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(7, 'Cutting classes at any time of the day without valid excuse.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(8, 'Academic dishonesty including cheating during examinations or plagiarism in any academic work.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(9, 'Acts of defiance to any legal order issued by a University authority.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(10, 'Use of mobile phones or gadgets in a manner that disrupts classes, meetings, or official functions.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(11, 'Smoking anywhere within University premises at any time.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(12, 'Possession of or intoxication with alcoholic beverages on campus or at university-sponsored activities.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(13, 'Abusive behavior including rude language, threatening remarks, intimidation, or bullying of any member of the university community.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(14, 'Membership in or affiliation with fraternities or sororities not recognized by the University.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(15, 'Vandalism or destructive acts that damage campus property such as defacing walls, wrecking fixtures, or smashing windows.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(16, 'Physical assault causing slight physical injury against any individual on campus or at university activities.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(17, 'Bringing bladed objects, brass or metal knuckles, or similar weapons inside University premises without written permission.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(18, 'Theft of personal property belonging to any member of the University community.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(19, 'Lewd, indecent, or immoral conduct on campus or at university-sponsored activities.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(20, 'Furnishing false information or submitting fake or forged University documents.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(21, 'Possession, use, selling, or distribution of dangerous drugs or prohibited substances.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(22, 'Unlawful possession of firearms, explosives, incendiary devices, or deadly weapons on campus.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(23, 'Involvement in hazing activities whether within or outside University jurisdiction.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(24, 'Acts constituting high crime including but not limited to rape, arson, and robbery, whether attempted or consummated.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(25, 'Conviction of a criminal offense before any court of law involving moral turpitude.', '2026-04-23 06:33:45', '2026-04-23 06:33:45'),
(26, 'No Identification Card', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `guards`
--
ALTER TABLE `guards`
  ADD PRIMARY KEY (`guard_id`),
  ADD UNIQUE KEY `guards_email_unique` (`email`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`program_id`),
  ADD KEY `programs_department_id_foreign` (`department_id`);

--
-- Indexes for table `records`
--
ALTER TABLE `records`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `records_student_id_foreign` (`student_id`),
  ADD KEY `records_violation_id_foreign` (`violation_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `students_sr_code_unique` (`sr_code`),
  ADD UNIQUE KEY `students_gsuite_unique` (`gsuite`),
  ADD KEY `students_program_id_foreign` (`program_id`);

--
-- Indexes for table `suspensions`
--
ALTER TABLE `suspensions`
  ADD PRIMARY KEY (`suspension_id`),
  ADD KEY `suspensions_record_id_foreign` (`record_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `violations`
--
ALTER TABLE `violations`
  ADD PRIMARY KEY (`violation_id`),
  ADD KEY `violations_description_id_foreign` (`description_id`);

--
-- Indexes for table `violation_descriptions`
--
ALTER TABLE `violation_descriptions`
  ADD PRIMARY KEY (`description_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guards`
--
ALTER TABLE `guards`
  MODIFY `guard_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `program_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `record_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `suspensions`
--
ALTER TABLE `suspensions`
  MODIFY `suspension_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `violations`
--
ALTER TABLE `violations`
  MODIFY `violation_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `violation_descriptions`
--
ALTER TABLE `violation_descriptions`
  MODIFY `description_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `programs`
--
ALTER TABLE `programs`
  ADD CONSTRAINT `programs_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE;

--
-- Constraints for table `records`
--
ALTER TABLE `records`
  ADD CONSTRAINT `records_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `records_violation_id_foreign` FOREIGN KEY (`violation_id`) REFERENCES `violations` (`violation_id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`program_id`) ON DELETE CASCADE;

--
-- Constraints for table `suspensions`
--
ALTER TABLE `suspensions`
  ADD CONSTRAINT `suspensions_record_id_foreign` FOREIGN KEY (`record_id`) REFERENCES `records` (`record_id`) ON DELETE CASCADE;

--
-- Constraints for table `violations`
--
ALTER TABLE `violations`
  ADD CONSTRAINT `violations_description_id_foreign` FOREIGN KEY (`description_id`) REFERENCES `violation_descriptions` (`description_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
