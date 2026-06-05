-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2026 at 02:08 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `evaluasi_panitia`
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
-- Table structure for table `committee_members`
--

CREATE TABLE `committee_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `division_id` bigint(20) UNSIGNED NOT NULL,
  `position` varchar(255) NOT NULL DEFAULT 'anggota',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `committee_members`
--

INSERT INTO `committee_members` (`id`, `user_id`, `division_id`, `position`, `created_at`, `updated_at`) VALUES
(1, 16, 2, 'anggota', '2026-05-30 03:16:22', '2026-05-30 03:16:22'),
(2, 17, 2, 'anggota', '2026-05-30 03:20:11', '2026-05-30 03:20:11'),
(3, 15, 2, 'anggota', '2026-06-01 04:12:02', '2026-06-01 04:12:02'),
(4, 20, 3, 'anggota', '2026-06-01 05:08:23', '2026-06-01 05:08:23'),
(5, 21, 3, 'anggota', '2026-06-01 05:11:15', '2026-06-01 05:11:15'),
(6, 22, 3, 'anggota', '2026-06-01 05:12:23', '2026-06-01 05:12:23');

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

CREATE TABLE `divisions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `divisions`
--

INSERT INTO `divisions` (`id`, `event_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(2, 3, 'divisi acara', NULL, '2026-05-27 07:28:55', '2026-05-27 07:28:55'),
(3, 4, 'acara', NULL, '2026-06-01 05:05:35', '2026-06-01 05:05:35'),
(4, 4, 'perkap', NULL, '2026-06-01 05:05:45', '2026-06-01 05:05:45');

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `evaluator_id` bigint(20) UNSIGNED NOT NULL,
  `evaluatee_id` bigint(20) UNSIGNED NOT NULL,
  `final_score` decimal(8,2) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `evaluations`
--

INSERT INTO `evaluations` (`id`, `event_id`, `evaluator_id`, `evaluatee_id`, `final_score`, `feedback`, `created_at`, `updated_at`) VALUES
(1, 3, 17, 1, 5.00, NULL, '2026-05-30 03:20:44', '2026-05-30 03:20:44'),
(8, 4, 22, 5, 3.67, 'sip', '2026-06-01 06:17:18', '2026-06-01 06:17:18'),
(9, 4, 22, 4, 4.00, 'sip', '2026-06-01 06:17:29', '2026-06-01 06:17:29'),
(10, 4, 20, 6, 4.00, 'sip', '2026-06-01 06:17:55', '2026-06-01 06:17:55'),
(11, 4, 20, 5, 4.00, 'sip', '2026-06-01 06:18:08', '2026-06-01 06:18:08'),
(12, 4, 21, 6, 5.00, 'sip', '2026-06-01 06:18:33', '2026-06-01 06:18:33'),
(13, 4, 21, 4, 5.00, 'sip', '2026-06-01 06:18:43', '2026-06-01 06:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_criterias`
--

CREATE TABLE `evaluation_criterias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `weight` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `evaluation_criterias`
--

INSERT INTO `evaluation_criterias` (`id`, `name`, `weight`, `created_at`, `updated_at`) VALUES
(1, 'Kerja Sama (Cooperation)', 1, '2026-05-27 07:23:39', '2026-05-27 07:23:39'),
(2, 'Disiplin (Discipline)', 1, '2026-05-27 07:23:39', '2026-05-27 07:23:39'),
(3, 'Tanggung Jawab (Responsibility)', 1, '2026-05-27 07:23:39', '2026-05-27 07:23:39');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_details`
--

CREATE TABLE `evaluation_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `evaluation_id` bigint(20) UNSIGNED NOT NULL,
  `criteria_id` bigint(20) UNSIGNED NOT NULL,
  `score` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `evaluation_details`
--

INSERT INTO `evaluation_details` (`id`, `evaluation_id`, `criteria_id`, `score`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 5.00, '2026-05-30 03:20:44', '2026-05-30 03:20:44'),
(2, 1, 2, 5.00, '2026-05-30 03:20:44', '2026-05-30 03:20:44'),
(3, 1, 3, 5.00, '2026-05-30 03:20:44', '2026-05-30 03:20:44'),
(4, 8, 1, 3.00, '2026-06-01 06:17:18', '2026-06-01 06:17:18'),
(5, 8, 2, 3.00, '2026-06-01 06:17:18', '2026-06-01 06:17:18'),
(6, 8, 3, 5.00, '2026-06-01 06:17:18', '2026-06-01 06:17:18'),
(7, 9, 1, 4.00, '2026-06-01 06:17:29', '2026-06-01 06:17:29'),
(8, 9, 2, 4.00, '2026-06-01 06:17:29', '2026-06-01 06:17:29'),
(9, 9, 3, 4.00, '2026-06-01 06:17:29', '2026-06-01 06:17:29'),
(10, 10, 1, 4.00, '2026-06-01 06:17:55', '2026-06-01 06:17:55'),
(11, 10, 2, 5.00, '2026-06-01 06:17:55', '2026-06-01 06:17:55'),
(12, 10, 3, 3.00, '2026-06-01 06:17:55', '2026-06-01 06:17:55'),
(13, 11, 1, 4.00, '2026-06-01 06:18:08', '2026-06-01 06:18:08'),
(14, 11, 2, 4.00, '2026-06-01 06:18:08', '2026-06-01 06:18:08'),
(15, 11, 3, 4.00, '2026-06-01 06:18:08', '2026-06-01 06:18:08'),
(16, 12, 1, 5.00, '2026-06-01 06:18:33', '2026-06-01 06:18:33'),
(17, 12, 2, 5.00, '2026-06-01 06:18:33', '2026-06-01 06:18:33'),
(18, 12, 3, 5.00, '2026-06-01 06:18:33', '2026-06-01 06:18:33'),
(19, 13, 1, 5.00, '2026-06-01 06:18:43', '2026-06-01 06:18:43'),
(20, 13, 2, 5.00, '2026-06-01 06:18:43', '2026-06-01 06:18:43'),
(21, 13, 3, 5.00, '2026-06-01 06:18:43', '2026-06-01 06:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('draft','active','completed') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `description`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`, `admin_id`) VALUES
(1, 'germada', NULL, '2026-05-21', '2026-05-21', 'active', '2026-05-21 07:02:01', '2026-05-21 10:13:43', NULL),
(3, 'pkkmb 2026', NULL, '2026-05-05', '2026-05-05', 'active', '2026-05-25 09:22:19', '2026-05-25 09:22:19', NULL),
(4, 'mobil lejen', NULL, '2026-06-01', '2026-06-01', 'active', '2026-06-01 05:05:17', '2026-06-01 05:05:17', 19);

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
(10, '0001_01_01_000000_create_users_table', 1),
(11, '0001_01_01_000001_create_cache_table', 1),
(12, '0001_01_01_000002_create_jobs_table', 1),
(13, '2026_04_06_000001_create_events_table', 1),
(14, '2026_04_06_000002_create_divisions_table', 1),
(15, '2026_04_06_000003_create_committee_members_table', 1),
(16, '2026_04_06_000004_create_evaluation_criterias_table', 1),
(17, '2026_04_06_000005_create_evaluations_table', 1),
(18, '2026_04_06_000006_create_evaluation_details_table', 1),
(19, '2026_05_27_165459_add_profile_photo_to_users_table', 2),
(20, '2026_06_01_112435_add_admin_id_to_events_table', 3);

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
('r5AYWuCZGUC8WKqeKd7aWp0oysYm38V1uMuo7cyX', 22, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTmpRRms3cVZFa2ZFQlExNGxnQk5ZVlM0dENiZDZlNlFZQzhScWFpVyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9oYXNpbC1ldmFsdWFzaS1wYW5pdGlhIjtzOjU6InJvdXRlIjtzOjE5OiJoYXNpbC1wYW5pdGlhLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjI7fQ==', 1780320325);

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
  `profile_photo` varchar(255) DEFAULT NULL,
  `role` enum('admin','evaluator','panitia') NOT NULL DEFAULT 'panitia',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `profile_photo`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Demo User', 'demo@example.com', NULL, '$2y$12$C4Zdsikn/3UD5A/8b.nfHeKR72gR45Jvd9683.crTYQiFRovlIaIm', NULL, 'admin', NULL, '2026-04-18 08:53:59', '2026-04-18 08:53:59'),
(4, 'rayhanwahyu', 'rayhanwahyu@mhs.unesa.ac.id', NULL, '$2y$12$wThoDAX3ZEvAooX8LqsgxulNiYWI6wxqM5SAX6Jfg3H9Q6Oju0UY6', NULL, 'panitia', NULL, '2026-04-27 05:27:44', '2026-04-27 05:27:44'),
(5, 'bowo', 'bowo@gmail.com', NULL, '$2y$12$tWyyWyRfUns3Ze3NvXvsr.GUtOdFR1kioBQN7YB/fR5z5rZNze5dS', NULL, 'admin', NULL, '2026-04-27 05:29:42', '2026-04-27 05:29:42'),
(6, 'anjay', 'anjay@gmail.com', NULL, '$2y$12$fsm87DY0x286xluo6NWNRuVKBwxTe709Y/qTN60ftF/ysDc9Bc5Hq', NULL, 'admin', NULL, '2026-04-27 05:48:07', '2026-04-27 05:48:07'),
(7, 'rayhanwahyu', 'rayhan@gmail.com', NULL, '$2y$12$33vMJ799HH/MA2IU8xA74.aJbiC8MxQtFh0fzdouOXfui/lNVSTg2', NULL, 'admin', NULL, '2026-04-27 07:37:56', '2026-04-27 07:37:56'),
(8, 'techa', 'techa@gmail.com', NULL, '$2y$12$9CGNtAPNZ3nbeRClLsgUkuAS2GJKmcYufwPIrZZ8P97St5ydFn0Oa', 'profile_photos/gBVMuBQary94DffbABdxFfNG7dsD39Bq64x8Wht8.jpg', 'admin', NULL, '2026-04-27 07:43:41', '2026-05-30 03:39:37'),
(9, 'amel', 'amel@mhs.unesa.ac.id', NULL, '$2y$12$gpUsqdOISaLcUUZTi/r5Nuw6mrSASFIcb8BnHO5VFqg5PXcJahkVS', NULL, 'panitia', NULL, '2026-04-27 09:00:10', '2026-04-27 09:00:10'),
(10, '1234567', '1234567@mhs.unesa.ac.id', NULL, '$2y$12$1qPsXt39oPsuxjtROIiqOOYPQ8j1UAx3e1Fa/ocUo7TRx2I/6eL/.', NULL, 'panitia', 'YO4hsT8aOo6pDEKWaDG7H9wPm9jMFUBGAVAtmUJ0elwyqbdSSvsJjzzUC1IV', '2026-04-27 09:02:51', '2026-04-27 09:02:51'),
(11, 'fatecha', 'techa24@gmail.com', NULL, '$2y$12$7z5fBrmU/g5a7gjNkAA3wOU2y3oehcIkHbP2vJ.0ewR6vo6KciQsm', NULL, 'admin', NULL, '2026-04-28 01:28:10', '2026-04-28 01:28:10'),
(12, 'rayhanbowo', 'wahyu@mhs.unesa.ac.id', NULL, '$2y$12$3LnTEo6I85JOUc/Gx401ae0JrNIQyRzxdUDbG878gGHOv3FnPHVZO', NULL, 'panitia', NULL, '2026-04-28 01:30:59', '2026-04-28 01:30:59'),
(13, 'faisal', 'faisal@mhs.unesa.ac.id', NULL, '$2y$12$ehV9Nu54hk5fxw5kC2j3OedYbbuHwuPyozytToi83t59mXLmdPnjK', NULL, 'panitia', NULL, '2026-05-22 07:09:02', '2026-05-22 07:09:02'),
(14, 'alda', 'alda@mhs.unesa.ac.id', NULL, '$2y$12$VCJ.mqCHury0Z9/OC/fIFuFdS.u6iTv9A0gZK8541PPcDzCHQH7Mq', NULL, 'panitia', NULL, '2026-05-22 07:14:40', '2026-05-22 07:14:40'),
(15, 'satriaaa', 'satria@mhs.unesa.ac.id', NULL, '$2y$12$dt8z.JCNr9oPsxdrO.2ajORnG3mO7O4P6dHGiwgi/WOw2UKD0Vy8W', 'profile_photos/iMxLb5pqQk9Ppmj9nHOXyHVD21YDZSeyaknu0Buh.png', 'panitia', NULL, '2026-05-27 08:13:03', '2026-05-27 09:57:48'),
(16, 'risaddd', 'risad@mhs.unesa.ac.id', NULL, '$2y$12$g6gVJ0wqobZM2EwHKg0Jr.jFbNzaUgQHkgeoepl.YYXFa8fH8iJb6', NULL, 'panitia', NULL, '2026-05-30 03:16:08', '2026-05-30 03:16:08'),
(17, 'wahyuuu', 'wahyu123@mhs.unesa.ac.id', NULL, '$2y$12$RLyR0azNqK9GpBGzCVQZx.6tmRwqJlYXlR1/m5oPzMrfNUDIM/W3q', NULL, 'panitia', NULL, '2026-05-30 03:20:01', '2026-05-30 03:20:01'),
(18, 'putu', 'putu@gmail.com', NULL, '$2y$12$i2GUR51fWuaIl4cMLm01iuPlLOMd9FVD4/NaHJINJZmgMpZOqGhdy', NULL, 'admin', NULL, '2026-06-01 04:27:48', '2026-06-01 04:27:48'),
(19, 'farid', 'farid@gmail.com', NULL, '$2y$12$GGS32K86dOW7Fn2Jh821xOcCJ2lD1kPi9ZFvjKICs3CTzjAkox3WC', NULL, 'admin', NULL, '2026-06-01 05:02:43', '2026-06-01 05:02:43'),
(20, 'Harumi', 'harumi@mhs.unesa.ac.id', NULL, '$2y$12$1gpBAJ.OJJMImXxb0vJt0OGUA3gAtdZtTFVu7Iw76SxXN0d/DASr.', NULL, 'panitia', NULL, '2026-06-01 05:06:30', '2026-06-01 05:06:30'),
(21, 'Amelanov', 'amelanov@mhs.unesa.ac.id', NULL, '$2y$12$SJkPUPJ6U68UosbHU8ExaesQMEZrEyMxNboLkrkTX.UMvG4QJNsLq', NULL, 'panitia', NULL, '2026-06-01 05:11:08', '2026-06-01 05:11:08'),
(22, 'Aruna', 'aruna@mhs.unesa.ac.id', NULL, '$2y$12$dcuSN9LOQMuljWEWXdyWFeEVbuBXuda8eWjl3VVYS0xTZCRPetTrO', NULL, 'panitia', NULL, '2026-06-01 05:12:14', '2026-06-01 05:12:14');

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
-- Indexes for table `committee_members`
--
ALTER TABLE `committee_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `committee_members_user_id_foreign` (`user_id`),
  ADD KEY `committee_members_division_id_foreign` (`division_id`);

--
-- Indexes for table `divisions`
--
ALTER TABLE `divisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `divisions_event_id_foreign` (`event_id`);

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evaluations_event_id_foreign` (`event_id`),
  ADD KEY `evaluations_evaluator_id_foreign` (`evaluator_id`),
  ADD KEY `evaluations_evaluatee_id_foreign` (`evaluatee_id`);

--
-- Indexes for table `evaluation_criterias`
--
ALTER TABLE `evaluation_criterias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_details`
--
ALTER TABLE `evaluation_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evaluation_details_evaluation_id_foreign` (`evaluation_id`),
  ADD KEY `evaluation_details_criteria_id_foreign` (`criteria_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_reserved_at_available_at_index` (`queue`,`reserved_at`,`available_at`);

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
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `committee_members`
--
ALTER TABLE `committee_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `divisions`
--
ALTER TABLE `divisions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `evaluation_criterias`
--
ALTER TABLE `evaluation_criterias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `evaluation_details`
--
ALTER TABLE `evaluation_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `committee_members`
--
ALTER TABLE `committee_members`
  ADD CONSTRAINT `committee_members_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `committee_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `divisions`
--
ALTER TABLE `divisions`
  ADD CONSTRAINT `divisions_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_evaluatee_id_foreign` FOREIGN KEY (`evaluatee_id`) REFERENCES `committee_members` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_evaluator_id_foreign` FOREIGN KEY (`evaluator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `evaluation_details`
--
ALTER TABLE `evaluation_details`
  ADD CONSTRAINT `evaluation_details_criteria_id_foreign` FOREIGN KEY (`criteria_id`) REFERENCES `evaluation_criterias` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluation_details_evaluation_id_foreign` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
