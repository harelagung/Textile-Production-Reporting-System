-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 07 Nov 2025 pada 13.42
-- Versi server: 8.0.30
-- Versi PHP: 8.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_msep_4`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('project-msep-cache-902ba3cda1883801594b6e1b452790cc53948fda', 'i:1;', 1758978271),
('project-msep-cache-902ba3cda1883801594b6e1b452790cc53948fda:timer', 'i:1758978271;', 1758978271),
('project-msep-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:0:{}s:11:\"permissions\";a:0:{}s:5:\"roles\";a:0:{}}', 1762437012);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `constructions`
--

CREATE TABLE `constructions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock` decimal(10,1) UNSIGNED NOT NULL DEFAULT '0.0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `constructions`
--

INSERT INTO `constructions` (`id`, `name`, `stock`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'R30S 84X48-127 75\"', 1802.2, '2025-09-07 04:17:31', '2025-09-27 07:07:39', NULL),
(2, 'R30S 90X56-125 56\"', 368.4, '2025-09-07 04:18:14', '2025-11-05 13:57:03', NULL),
(3, 'R30S 90X56-125 75\"', 320.0, '2025-09-07 04:18:37', '2025-09-13 02:58:36', NULL),
(4, 'R30S 90X48-125', 258.0, '2025-09-07 04:18:52', '2025-09-20 08:23:34', NULL),
(5, 'R30S 90X56-95', 302.5, '2025-09-07 04:19:10', '2025-09-21 03:06:15', NULL),
(6, 'R30S 90X48-95', 749.9, '2025-09-07 04:19:22', '2025-09-27 07:07:56', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `departments`
--

CREATE TABLE `departments` (
  `id` bigint UNSIGNED NOT NULL,
  `kd_dept` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `departments`
--

INSERT INTO `departments` (`id`, `kd_dept`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'HRD', 'Personalia', '2025-09-07 02:43:06', '2025-09-21 02:50:17', NULL),
(2, 'WVG', 'Weaving', '2025-09-07 02:43:37', '2025-09-21 02:45:40', NULL),
(3, 'FNS', 'Finishing', '2025-09-07 03:14:47', '2025-09-21 02:54:08', NULL),
(4, 'TKM', 'Teknik Umum', '2025-09-21 02:54:38', '2025-09-21 02:54:38', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `machines`
--

CREATE TABLE `machines` (
  `id` bigint UNSIGNED NOT NULL,
  `kd_mach` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `construction_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `machines`
--

INSERT INTO `machines` (`id`, `kd_mach`, `construction_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'A01', 2, '2025-09-07 12:27:39', '2025-11-05 13:57:03', NULL),
(2, 'A02', 1, '2025-09-07 13:18:01', '2025-09-27 07:07:13', NULL),
(3, 'B01', 2, '2025-09-07 13:18:24', '2025-09-27 07:08:20', NULL),
(4, 'B02', 5, '2025-09-07 13:18:39', '2025-09-12 13:27:49', NULL),
(5, 'C01', 6, '2025-09-07 13:18:46', '2025-09-27 07:07:56', NULL),
(6, 'C02', 6, '2025-09-07 13:18:54', '2025-09-27 06:47:29', NULL),
(7, 'A03', 5, '2025-09-08 14:37:52', '2025-09-21 02:59:32', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_02_194302_create_permission_tables', 1),
(5, '2025_09_07_072357_create_departments_table', 2),
(6, '2025_09_07_072417_create_positions_table', 2),
(7, '2025_09_07_072503_create_contructions_table', 2),
(9, '2025_09_07_072503_create_cosntructions_table', 3),
(10, '2025_09_07_072554_create_machines_table', 4),
(11, '2025_09_07_072617_create_shifts_table', 4),
(12, '2025_09_07_072618_create_reports_table', 4),
(13, '2025_09_07_093019_add_department_and_position_to_users', 5),
(14, '2025_09_08_201711_add_overtime_to_reports', 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 3),
(2, 'App\\Models\\User', 7),
(3, 'App\\Models\\User', 8);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('harel.nugroho29@gmail.com', '$2y$10$e0xFq5euPuEnUJv7yLXM1u3uhlzJkewy0GAvMRpRcxxis/EViU1xi', '2025-09-21 02:57:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `positions`
--

CREATE TABLE `positions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `positions`
--

INSERT INTO `positions` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Leader', '2025-09-07 03:20:48', '2025-09-21 02:53:35', NULL),
(2, 'Operator', '2025-09-07 03:21:23', '2025-09-07 03:21:23', NULL),
(3, 'Foreman', '2025-09-07 03:21:29', '2025-09-07 03:21:29', NULL),
(4, 'Supervisor', '2025-09-07 03:21:34', '2025-09-07 03:21:34', NULL),
(5, 'Manager', '2025-09-07 03:21:39', '2025-09-07 03:21:39', NULL),
(6, 'Staff', '2025-09-07 03:21:47', '2025-09-07 03:21:47', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `reports`
--

CREATE TABLE `reports` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `shift_id` bigint UNSIGNED NOT NULL,
  `machine_id` bigint UNSIGNED NOT NULL,
  `construction_id` bigint UNSIGNED NOT NULL,
  `stock` decimal(10,1) UNSIGNED NOT NULL DEFAULT '0.0',
  `eff` decimal(10,2) NOT NULL DEFAULT '0.00',
  `overtime` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `shift_id`, `machine_id`, `construction_id`, `stock`, `eff`, `overtime`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 7, 1, 1, 1, 32.0, 60.68, 2, '2025-09-20 03:12:43', '2025-09-20 03:12:43', NULL),
(9, 7, 1, 6, 6, 31.8, 54.12, 2, '2025-09-20 09:03:30', '2025-09-20 09:03:30', NULL),
(10, 7, 1, 5, 6, 39.1, 66.54, 2, '2025-09-20 09:05:04', '2025-09-20 09:05:04', NULL),
(11, 1, 1, 2, 1, 39.0, 93.02, NULL, '2025-09-20 09:18:30', '2025-09-20 09:18:30', NULL),
(12, 1, 3, 3, 5, 35.3, 77.87, 1, '2025-09-20 09:22:53', '2025-09-20 09:22:53', NULL),
(17, 3, 1, 5, 6, 30.2, 51.39, 2, '2025-09-27 06:47:18', '2025-09-27 06:47:18', NULL),
(18, 3, 1, 6, 6, 31.8, 54.12, 2, '2025-09-27 06:47:29', '2025-09-27 06:47:29', NULL),
(19, 3, 1, 2, 1, 25.0, 47.70, 2, '2025-09-27 06:47:42', '2025-09-27 07:12:53', NULL),
(20, 3, 2, 1, 2, 39.0, 77.43, 2, '2025-09-27 07:07:00', '2025-09-27 07:07:00', NULL),
(21, 3, 2, 2, 1, 39.2, 74.80, 2, '2025-09-27 07:07:13', '2025-09-27 07:07:13', NULL),
(22, 3, 2, 5, 1, 38.0, 72.51, 2, '2025-09-27 07:07:39', '2025-09-27 07:07:39', NULL),
(23, 3, 3, 5, 6, 39.0, 66.37, 2, '2025-09-27 07:07:56', '2025-09-27 07:07:56', NULL),
(24, 3, 3, 1, 2, 31.8, 63.14, 2, '2025-09-27 07:08:03', '2025-09-27 07:08:03', NULL),
(25, 3, 3, 3, 2, 30.2, 59.96, 2, '2025-09-27 07:08:20', '2025-09-27 07:08:20', NULL),
(27, 8, 1, 1, 2, 30.2, 74.95, NULL, '2025-11-05 13:57:03', '2025-11-05 13:57:03', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2025-09-02 13:27:29', '2025-09-02 13:27:29'),
(2, 'Super Admin', 'web', '2025-09-02 13:27:37', '2025-09-02 13:27:37'),
(3, 'User', 'web', '2025-09-02 13:27:43', '2025-09-02 13:27:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('2RK0sIuDtMXmmGmfvAahZ9Bd4n0sbbYNbNV6njso', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWEpoWW1BZGFsenIzZ2lnUnNuSmhIZm9KUDlOa3RtR3RuV3ZiS0puMSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fX0=', 1761739134),
('IfEGEUTtpDuomwUvCcaM6k4bfnVRt9QQke3SLxlD', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQkZtZ3FjZE81ZU50RDJqTHpKbjRva3VOV3o1MkFNMXpoemxmMnlUSSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fX0=', 1762351802),
('IRbjBJQeHqwHlhqkwAKbxuDVV4u5uVvZuBvnbtAQ', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiTmdxOHRHSkZtcGt5V3FBaHFCZmVWS2FDYVRvNmU3eFRaZkFhWHc4RiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9jb25zdHJ1Y3Rpb25zIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MztzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJEpxa1FPa3ZVVkRPalhXUy9iNWR4N2U0NWZISDRQdzZvelB3TW9aRHFZRldJY0k4RGtXUmdlIjtzOjg6ImZpbGFtZW50IjthOjA6e31zOjY6InRhYmxlcyI7YToxOntzOjQxOiI2YzBjYzM4YmU0ZTI0YTUyYjVlMDJmMjRhMjAzY2U0Yl9wZXJfcGFnZSI7czozOiJhbGwiO319', 1760244775);

-- --------------------------------------------------------

--
-- Struktur dari tabel `shifts`
--

CREATE TABLE `shifts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `duration_hours` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `shifts`
--

INSERT INTO `shifts` (`id`, `name`, `start_time`, `end_time`, `duration_hours`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Shift 1', '08:00:01', '16:00:00', 8, '2025-09-07 04:27:30', '2025-09-11 13:12:33', NULL),
(2, 'Shift 2', '16:00:01', '00:00:00', 8, '2025-09-07 04:31:57', '2025-09-11 13:13:34', NULL),
(3, 'Shift 3', '00:00:01', '08:00:00', 8, '2025-09-07 04:32:18', '2025-09-11 13:13:53', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `nip` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `position_id` bigint UNSIGNED DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nip`, `name`, `email`, `email_verified_at`, `department_id`, `position_id`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '19231641', 'Harel Agung Nugroho', 'harel.nugroho29@gmail.com', '2025-09-02 12:47:33', 1, 6, '$2y$10$MTmtjIaD47r1Xh4tVucYwuiyFsaNQdR.Ue9l3eBV1OWDqUXkwUS1e', 'fhoFAZ15rqoq3bxvEVHS41woSptOfgFoj8I6BCcsxr5lMXSVOTtrvnGE2tNJ', '2025-09-02 12:46:58', '2025-09-07 03:27:31', NULL),
(3, '12345678', 'admin', 'harelagungn@gmail.com', '2025-09-02 13:25:17', 2, 6, '$2y$12$JqkQOkvUVDOjXWS/b5dx7e45fHH4Pw6ozPwMoZDqYFWIcI8DkWRge', NULL, '2025-09-02 13:22:44', '2025-09-07 03:31:16', NULL),
(7, '21023001', 'Ahmad Sahroni', 'hypereacts3@gmail.com', NULL, 1, 5, '$2y$10$ZcSoHUbOVkodzhdciN1Jv.UnmHUXLERV3cseAMwp8lUJIcpZj.4Ny', 'nTjG9ryvWdnpvn3kO8wnqMNbyNXInIB4AATdC2ujMYyK7SJRDL3LADGCsr95', '2025-09-07 03:56:18', '2025-09-09 14:16:12', NULL),
(8, '11112222', 'Uya Kayu', 'uyauye@email.com', NULL, 2, 2, '$2y$10$nkO.ruGKsaoTQ.COGlT7yeqiXk77RW57mk7JqxlMestXUr1TaWNoO', NULL, '2025-10-06 11:41:22', '2025-11-05 14:09:15', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `constructions`
--
ALTER TABLE `constructions`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_kd_dept_unique` (`kd_dept`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `machines`
--
ALTER TABLE `machines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `machines_kd_mach_unique` (`kd_mach`),
  ADD KEY `machines_construction_id_foreign` (`construction_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indeks untuk tabel `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indeks untuk tabel `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_user_id_foreign` (`user_id`),
  ADD KEY `reports_shift_id_foreign` (`shift_id`),
  ADD KEY `reports_machine_id_foreign` (`machine_id`),
  ADD KEY `reports_construction_id_foreign` (`construction_id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indeks untuk tabel `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_nip_unique` (`nip`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_department_id_foreign` (`department_id`),
  ADD KEY `users_position_id_foreign` (`position_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `constructions`
--
ALTER TABLE `constructions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `machines`
--
ALTER TABLE `machines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `positions`
--
ALTER TABLE `positions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `machines`
--
ALTER TABLE `machines`
  ADD CONSTRAINT `machines_construction_id_foreign` FOREIGN KEY (`construction_id`) REFERENCES `constructions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_construction_id_foreign` FOREIGN KEY (`construction_id`) REFERENCES `constructions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reports_machine_id_foreign` FOREIGN KEY (`machine_id`) REFERENCES `machines` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reports_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
