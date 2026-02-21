-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 20 Feb 2026 pada 08.46
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `noc_reporting`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `backbone_incidents`
--

CREATE TABLE `backbone_incidents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `backbone_link_id` bigint(20) UNSIGNED NOT NULL,
  `incident_date` datetime NOT NULL,
  `latency` varchar(255) DEFAULT NULL,
  `down_status` tinyint(1) NOT NULL DEFAULT 0,
  `duration` int(11) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `backbone_incidents`
--

INSERT INTO `backbone_incidents` (`id`, `backbone_link_id`, `incident_date`, `latency`, `down_status`, `duration`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-02-10 07:03:14', '46ms', 0, 45, 'Sample incident', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(2, 1, '2026-02-15 07:03:14', '46ms', 0, 111, 'Sample incident', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(3, 1, '2026-01-24 07:03:14', '17ms', 0, 70, 'Sample incident', '2026-02-20 00:03:14', '2026-02-20 00:03:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `backbone_links`
--

CREATE TABLE `backbone_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `node_a` varchar(255) NOT NULL,
  `node_b` varchar(255) NOT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `media` varchar(255) DEFAULT NULL,
  `capacity` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `backbone_links`
--

INSERT INTO `backbone_links` (`id`, `node_a`, `node_b`, `provider`, `media`, `capacity`, `created_at`, `updated_at`) VALUES
(1, 'POP Main', 'Data Center A', 'Self', 'FO', '10 Gbps', '2026-02-20 00:03:14', '2026-02-20 00:03:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `service_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bandwidth` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `customers`
--

INSERT INTO `customers` (`id`, `name`, `address`, `service_type_id`, `bandwidth`, `status`, `created_at`, `updated_at`) VALUES
(1, 'PT Maju Jaya', NULL, 1, '100 Mbps', 'active', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(2, 'Cafe Relax', NULL, 2, '50 Mbps', 'active', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(3, 'Netlink', 'Jl. Gatot Subroto', 5, '1 Gbps', 'active', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(4, 'New Reseller Client', '123 Main St', 5, '100 Mbps', 'active', '2026-02-20 00:40:05', '2026-02-20 00:40:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `customer_incidents`
--

CREATE TABLE `customer_incidents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `incident_date` datetime NOT NULL,
  `physical_issue` tinyint(1) NOT NULL DEFAULT 0,
  `backbone_issue` tinyint(1) NOT NULL DEFAULT 0,
  `layer_issue` varchar(255) DEFAULT NULL,
  `duration` int(11) NOT NULL COMMENT 'Duration in minutes',
  `root_cause` text DEFAULT NULL,
  `status` enum('open','closed') NOT NULL DEFAULT 'open',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `customer_incidents`
--

INSERT INTO `customer_incidents` (`id`, `customer_id`, `incident_date`, `physical_issue`, `backbone_issue`, `layer_issue`, `duration`, `root_cause`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-01-11 09:03:14', 1, 1, 'Layer 3', 159, 'Fiber cut or power outage', 'open', 'Generated incident', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(2, 1, '2026-01-27 02:03:14', 1, 0, 'Layer 3', 49, 'Fiber cut or power outage', 'open', 'Generated incident', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(3, 1, '2026-02-01 13:03:14', 0, 0, 'Layer 3', 92, 'Fiber cut or power outage', 'open', 'Generated incident', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(4, 1, '2026-02-15 03:03:14', 0, 0, 'Layer 2', 274, 'Fiber cut or power outage', 'closed', 'Generated incident', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(5, 1, '2025-12-24 18:03:14', 1, 0, 'Layer 3', 157, 'Fiber cut or power outage', 'open', 'Generated incident', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(6, 2, '2026-01-14 06:03:14', 1, 0, 'Layer 2', 22, 'Fiber cut or power outage', 'closed', 'Generated incident', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(7, 2, '2026-02-01 02:03:14', 1, 1, 'Layer 2', 182, 'Fiber cut or power outage', 'open', 'Generated incident', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(8, 2, '2026-02-08 22:03:14', 0, 0, 'Layer 3', 44, 'Fiber cut or power outage', 'open', 'Generated incident', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(9, 2, '2026-02-02 02:03:14', 1, 0, 'Layer 3', 30, 'Fiber cut or power outage', 'open', 'Generated incident', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(10, 2, '2026-02-12 08:03:14', 1, 0, 'Layer 3', 104, 'Fiber cut or power outage', 'closed', 'Generated incident', '2026-02-20 00:03:14', '2026-02-20 00:03:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `devices`
--

CREATE TABLE `devices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `device_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `devices`
--

INSERT INTO `devices` (`id`, `name`, `device_type_id`, `brand`, `model`, `serial_number`, `location_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Router Core', 1, 'MikroTik', 'CCR1072', 'SN001', 1, 'active', '2026-02-20 00:03:13', '2026-02-20 00:03:13'),
(2, 'Switch Access', 2, 'Cisco', '2960', 'SN002', 1, 'active', '2026-02-20 00:03:13', '2026-02-20 00:03:13'),
(3, 'OLT 1', 3, 'ZTE', 'C320', 'SN003', 2, 'active', '2026-02-20 00:03:13', '2026-02-20 00:03:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `device_reports`
--

CREATE TABLE `device_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `device_id` bigint(20) UNSIGNED NOT NULL,
  `month` date NOT NULL,
  `physical_status` varchar(255) NOT NULL,
  `psu_status` varchar(255) NOT NULL,
  `fan_status` varchar(255) NOT NULL,
  `layer2_status` varchar(255) NOT NULL,
  `layer3_status` varchar(255) NOT NULL,
  `cpu_status` varchar(255) NOT NULL,
  `throughput_in` varchar(255) NOT NULL,
  `throughput_out` varchar(255) NOT NULL,
  `duration_downtime` int(11) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `device_reports`
--

INSERT INTO `device_reports` (`id`, `device_id`, `month`, `physical_status`, `psu_status`, `fan_status`, `layer2_status`, `layer3_status`, `cpu_status`, `throughput_in`, `throughput_out`, `duration_downtime`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-02-01', 'Good', 'Normal', 'Normal', 'Up', 'Up', '22%', '658 Mbps', '767 Mbps', 82, 'Sample report', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(2, 1, '2026-01-01', 'Good', 'Normal', 'Normal', 'Up', 'Up', '24%', '112 Mbps', '652 Mbps', 52, 'Sample report', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(3, 1, '2025-12-01', 'Good', 'Normal', 'Normal', 'Up', 'Up', '43%', '880 Mbps', '658 Mbps', 28, 'Sample report', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(4, 2, '2026-02-01', 'Good', 'Normal', 'Normal', 'Up', 'Up', '22%', '454 Mbps', '878 Mbps', 5, 'Sample report', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(5, 2, '2026-01-01', 'Good', 'Normal', 'Normal', 'Up', 'Up', '23%', '960 Mbps', '441 Mbps', 94, 'Sample report', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(6, 2, '2025-12-01', 'Good', 'Normal', 'Normal', 'Up', 'Up', '15%', '837 Mbps', '756 Mbps', 34, 'Sample report', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(7, 3, '2026-02-01', 'Good', 'Normal', 'Normal', 'Up', 'Up', '16%', '369 Mbps', '340 Mbps', 8, 'Sample report', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(8, 3, '2026-01-01', 'Good', 'Normal', 'Normal', 'Up', 'Up', '24%', '714 Mbps', '401 Mbps', 87, 'Sample report', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(9, 3, '2025-12-01', 'Good', 'Normal', 'Normal', 'Up', 'Up', '37%', '773 Mbps', '813 Mbps', 44, 'Sample report', '2026-02-20 00:03:14', '2026-02-20 00:03:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `device_types`
--

CREATE TABLE `device_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `device_types`
--

INSERT INTO `device_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Router', '2026-02-20 00:03:13', '2026-02-20 00:03:13'),
(2, 'Switch', '2026-02-20 00:03:13', '2026-02-20 00:03:13'),
(3, 'OLT', '2026-02-20 00:03:13', '2026-02-20 00:03:13'),
(4, 'Server', '2026-02-20 00:03:13', '2026-02-20 00:03:13'),
(5, 'Wireless', '2026-02-20 00:03:13', '2026-02-20 00:03:13'),
(6, 'Firewall', '2026-02-20 00:27:07', '2026-02-20 00:27:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `type` enum('POP','DC','BTS') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `locations`
--

INSERT INTO `locations` (`id`, `name`, `address`, `type`, `created_at`, `updated_at`) VALUES
(1, 'POP Main', 'Jl. Sudirman No. 1', 'POP', '2026-02-20 00:03:13', '2026-02-20 00:03:13'),
(2, 'Data Center A', 'Cyber Building', 'DC', '2026-02-20 00:03:13', '2026-02-20 00:03:13'),
(3, 'BTS Tower X', 'Hilltop', 'BTS', '2026-02-20 00:03:13', '2026-02-20 00:03:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_20_041446_add_role_to_users_table', 1),
(5, '2026_02_20_042406_create_locations_table', 1),
(6, '2026_02_20_042407_create_devices_table', 1),
(7, '2026_02_20_042408_create_customers_table', 1),
(8, '2026_02_20_042408_create_resellers_table', 1),
(9, '2026_02_20_042409_create_backbone_links_table', 1),
(10, '2026_02_20_042409_create_upstreams_table', 1),
(11, '2026_02_20_044718_create_device_reports_table', 1),
(12, '2026_02_20_044719_create_backbone_incidents_table', 1),
(13, '2026_02_20_044719_create_upstream_reports_table', 1),
(14, '2026_02_20_053325_create_customer_incidents_table', 1),
(15, '2026_02_20_055002_create_service_logs_table', 1),
(16, '2026_02_20_055640_create_monthly_summaries_table', 1),
(17, '2026_02_20_065742_create_device_types_table', 1),
(18, '2026_02_20_065742_create_service_types_table', 1),
(19, '2026_02_20_065743_add_details_to_devices_customers_resellers_table', 1),
(20, '2026_02_20_073318_add_address_to_customers_table', 2),
(21, '2026_02_20_073318_merge_resellers_to_customers_table', 2),
(22, '2026_02_20_073359_add_address_to_customers_table', 2),
(23, '2026_02_20_073405_merge_resellers_to_customers_table', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `monthly_summaries`
--

CREATE TABLE `monthly_summaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `month` varchar(255) NOT NULL,
  `total_incident` int(11) NOT NULL DEFAULT 0,
  `total_downtime` int(11) NOT NULL DEFAULT 0,
  `sla_percentage` decimal(5,2) NOT NULL DEFAULT 100.00,
  `total_activation` int(11) NOT NULL DEFAULT 0,
  `total_upgrade` int(11) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `service_logs`
--

CREATE TABLE `service_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('activation','deactivation','upgrade','downgrade') NOT NULL,
  `old_bandwidth` varchar(255) DEFAULT NULL,
  `new_bandwidth` varchar(255) DEFAULT NULL,
  `request_date` date NOT NULL,
  `execute_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `service_logs`
--

INSERT INTO `service_logs` (`id`, `customer_id`, `type`, `old_bandwidth`, `new_bandwidth`, `request_date`, `execute_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'downgrade', '100 Mbps', '74 Mbps', '2026-02-07', '2026-02-02', 'Downgrade request', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(2, 1, 'upgrade', '100 Mbps', '11 Mbps', '2026-02-18', '2026-01-24', 'Upgrade request', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(3, 1, 'downgrade', '100 Mbps', '74 Mbps', '2026-02-05', '2026-02-03', 'Downgrade request', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(4, 2, 'deactivation', '50 Mbps', NULL, '2026-02-04', NULL, 'Deactivation request', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(5, 2, 'deactivation', '50 Mbps', NULL, '2026-01-29', '2026-01-24', 'Deactivation request', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(6, 2, 'deactivation', '50 Mbps', NULL, '2026-01-18', '2026-02-13', 'Deactivation request', '2026-02-20 00:03:14', '2026-02-20 00:03:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `service_types`
--

CREATE TABLE `service_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `service_types`
--

INSERT INTO `service_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Dedicated', '2026-02-20 00:03:13', '2026-02-20 00:03:13'),
(2, 'Broadband', '2026-02-20 00:03:13', '2026-02-20 00:03:13'),
(3, 'Metro Ethernet', '2026-02-20 00:03:13', '2026-02-20 00:03:13'),
(4, 'VoIP', '2026-02-20 00:27:33', '2026-02-20 00:27:33'),
(5, 'Reseller', '2026-02-20 00:34:24', '2026-02-20 00:34:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
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
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('5Po2i8ZEuzBkkOYdKs1rdiJgTr599Y9ECoggYlrp', NULL, '127.0.0.1', 'Go-http-client/1.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYnh4UGFtcGhVNTk2R0xkNXF1dzBJMGxFVHRHMnEyZ2F6YkF0d2NVeSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2N1c3RvbWVycyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMxOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvY3VzdG9tZXJzIjtzOjU6InJvdXRlIjtzOjE1OiJjdXN0b21lcnMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1771573215),
('eouXWK9RUUXRLf2dQreNbV2xtT6b28hm4g6wvfzn', 1, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQXIyVHRjZjYyREFidWYwbUZEQ0FUUmQ2R1JLRnBPRWpZSkU1Z1FlQSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2NhdGlvbnMiO3M6NToicm91dGUiO3M6MTU6ImxvY2F0aW9ucy5pbmRleCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1771573510),
('EZBQBjsxnCUIWwc4AbDkXeuFj3XQet2kx6Chcj5W', NULL, '127.0.0.1', 'Go-http-client/1.1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiTTlENDZpU1ZxSDJKQWdVVFgzSVBpNlVCYkFKT2lydlRIZEpxeTdVaSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771573215),
('qDV4j1ZA4zDua9v83hM9zuRbToJETJlOgvYGILPV', NULL, '127.0.0.1', 'Go-http-client/1.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiV3JNSlRzQjNDREpuNGlaNTVIMDhVa0g0Zm05MzB5TFA3b3VWWXBWMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771573215),
('YcSagWgvWPvB49SchzLp60hNtvIheQRDVadd2CUG', NULL, '127.0.0.1', 'Go-http-client/1.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNXZTSnY2NU53NDJwRFNIYlZnbmREZWRqSVI1NEdTbURtT0pYZXBNSiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovL2xvY2FsaG9zdDo4MDAwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771573214);

-- --------------------------------------------------------

--
-- Struktur dari tabel `upstreams`
--

CREATE TABLE `upstreams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `peer_name` varchar(255) NOT NULL,
  `capacity` varchar(255) DEFAULT NULL,
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `upstreams`
--

INSERT INTO `upstreams` (`id`, `peer_name`, `capacity`, `location_id`, `created_at`, `updated_at`) VALUES
(1, 'Telkom', '10 Gbps', 2, '2026-02-20 00:03:14', '2026-02-20 00:03:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `upstream_reports`
--

CREATE TABLE `upstream_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `upstream_id` bigint(20) UNSIGNED NOT NULL,
  `month` date NOT NULL,
  `status_l1` varchar(255) NOT NULL,
  `status_l2` varchar(255) NOT NULL,
  `status_l3` varchar(255) NOT NULL,
  `advertise` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `upstream_reports`
--

INSERT INTO `upstream_reports` (`id`, `upstream_id`, `month`, `status_l1`, `status_l2`, `status_l3`, `advertise`, `duration`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-02-01', 'Up', 'Up', 'Up', 'Accepted', 23, 'Sample upstream report', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(2, 1, '2026-01-01', 'Up', 'Up', 'Up', 'Accepted', 14, 'Sample upstream report', '2026-02-20 00:03:14', '2026-02-20 00:03:14'),
(3, 1, '2025-12-01', 'Up', 'Up', 'Up', 'Accepted', 0, 'Sample upstream report', '2026-02-20 00:03:14', '2026-02-20 00:03:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','noc','manager') NOT NULL DEFAULT 'noc',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@example.com', 'admin', '2026-02-20 00:03:13', '$2y$12$o7owfsCCexkoSW1oxGZX9.iBUEcNMWC7bcjzwCD9vdmiJyDLXI/Wu', '8v4inq7UaLbXxeg8zv8QIOGCbQqPv29CSNPHNaePCSDL3AjHeoEoLwaqWJUN', '2026-02-20 00:03:13', '2026-02-20 00:03:13'),
(2, 'NOC User', 'noc@example.com', 'noc', '2026-02-20 00:03:13', '$2y$12$Y9X.i166.x84uSATFyCcI.Gc89xYWqBZk.VtTRODIDu5SB0lvXdbe', '3wWvkVFJrS', '2026-02-20 00:03:13', '2026-02-20 00:03:13'),
(3, 'Manager User', 'manager@example.com', 'manager', '2026-02-20 00:03:13', '$2y$12$dqZfh2LGjm3tCqyTXvm4PeOgbgV3LCH1BvZ8bT2qj9vlk6ckPQhgG', 'rJRZPrPbfC', '2026-02-20 00:03:13', '2026-02-20 00:03:13');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `backbone_incidents`
--
ALTER TABLE `backbone_incidents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `backbone_incidents_backbone_link_id_foreign` (`backbone_link_id`),
  ADD KEY `backbone_incidents_incident_date_index` (`incident_date`);

--
-- Indeks untuk tabel `backbone_links`
--
ALTER TABLE `backbone_links`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_service_type_id_foreign` (`service_type_id`);

--
-- Indeks untuk tabel `customer_incidents`
--
ALTER TABLE `customer_incidents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_incidents_customer_id_foreign` (`customer_id`);

--
-- Indeks untuk tabel `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `devices_location_id_foreign` (`location_id`),
  ADD KEY `devices_device_type_id_foreign` (`device_type_id`);

--
-- Indeks untuk tabel `device_reports`
--
ALTER TABLE `device_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `device_reports_device_id_foreign` (`device_id`),
  ADD KEY `device_reports_month_index` (`month`);

--
-- Indeks untuk tabel `device_types`
--
ALTER TABLE `device_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `device_types_name_unique` (`name`);

--
-- Indeks untuk tabel `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `monthly_summaries`
--
ALTER TABLE `monthly_summaries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `monthly_summaries_month_unique` (`month`),
  ADD KEY `monthly_summaries_created_by_foreign` (`created_by`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `service_logs`
--
ALTER TABLE `service_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_logs_customer_id_foreign` (`customer_id`);

--
-- Indeks untuk tabel `service_types`
--
ALTER TABLE `service_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `service_types_name_unique` (`name`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `upstreams`
--
ALTER TABLE `upstreams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `upstreams_location_id_foreign` (`location_id`);

--
-- Indeks untuk tabel `upstream_reports`
--
ALTER TABLE `upstream_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `upstream_reports_upstream_id_foreign` (`upstream_id`),
  ADD KEY `upstream_reports_month_index` (`month`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `backbone_incidents`
--
ALTER TABLE `backbone_incidents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `backbone_links`
--
ALTER TABLE `backbone_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `customer_incidents`
--
ALTER TABLE `customer_incidents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `devices`
--
ALTER TABLE `devices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `device_reports`
--
ALTER TABLE `device_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `device_types`
--
ALTER TABLE `device_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `monthly_summaries`
--
ALTER TABLE `monthly_summaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `service_logs`
--
ALTER TABLE `service_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `service_types`
--
ALTER TABLE `service_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `upstreams`
--
ALTER TABLE `upstreams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `upstream_reports`
--
ALTER TABLE `upstream_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `backbone_incidents`
--
ALTER TABLE `backbone_incidents`
  ADD CONSTRAINT `backbone_incidents_backbone_link_id_foreign` FOREIGN KEY (`backbone_link_id`) REFERENCES `backbone_links` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_service_type_id_foreign` FOREIGN KEY (`service_type_id`) REFERENCES `service_types` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `customer_incidents`
--
ALTER TABLE `customer_incidents`
  ADD CONSTRAINT `customer_incidents_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `devices`
--
ALTER TABLE `devices`
  ADD CONSTRAINT `devices_device_type_id_foreign` FOREIGN KEY (`device_type_id`) REFERENCES `device_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `devices_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `device_reports`
--
ALTER TABLE `device_reports`
  ADD CONSTRAINT `device_reports_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `monthly_summaries`
--
ALTER TABLE `monthly_summaries`
  ADD CONSTRAINT `monthly_summaries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `service_logs`
--
ALTER TABLE `service_logs`
  ADD CONSTRAINT `service_logs_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `upstreams`
--
ALTER TABLE `upstreams`
  ADD CONSTRAINT `upstreams_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `upstream_reports`
--
ALTER TABLE `upstream_reports`
  ADD CONSTRAINT `upstream_reports_upstream_id_foreign` FOREIGN KEY (`upstream_id`) REFERENCES `upstreams` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
