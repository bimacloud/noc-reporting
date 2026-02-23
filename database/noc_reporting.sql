-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 23 Feb 2026 pada 08.50
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
  `resolve_date` datetime DEFAULT NULL,
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

INSERT INTO `backbone_incidents` (`id`, `backbone_link_id`, `incident_date`, `resolve_date`, `latency`, `down_status`, `duration`, `notes`, `created_at`, `updated_at`) VALUES
(4, 4, '2026-02-23 07:23:00', '2026-02-23 07:34:00', '12', 1, 11, 'Kabel putus', '2026-02-23 00:23:59', '2026-02-23 00:35:00'),
(5, 4, '2026-02-23 14:38:00', '2026-02-23 14:39:00', NULL, 1, 1, 'looo', '2026-02-23 00:39:02', '2026-02-23 00:39:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `backbone_links`
--

CREATE TABLE `backbone_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `node_a` varchar(255) NOT NULL,
  `node_b` varchar(255) NOT NULL,
  `node_c` varchar(255) DEFAULT NULL,
  `node_d` varchar(255) DEFAULT NULL,
  `node_e` varchar(255) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `media` varchar(255) DEFAULT NULL,
  `capacity` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `backbone_links`
--

INSERT INTO `backbone_links` (`id`, `node_a`, `node_b`, `node_c`, `node_d`, `node_e`, `provider`, `media`, `capacity`, `created_at`, `updated_at`) VALUES
(4, 'IDC3D', 'Banyumas', NULL, NULL, NULL, 'IFORTE', 'Fiber Optic', '1G', '2026-02-23 00:00:48', '2026-02-23 00:05:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `backbone_logs`
--

CREATE TABLE `backbone_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `backbone_link_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('activation','deactivation','upgrade','downgrade') NOT NULL,
  `old_capacity` varchar(255) DEFAULT NULL,
  `new_capacity` varchar(255) DEFAULT NULL,
  `request_date` date NOT NULL,
  `execute_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `backbone_logs`
--

INSERT INTO `backbone_logs` (`id`, `backbone_link_id`, `type`, `old_capacity`, `new_capacity`, `request_date`, `execute_date`, `notes`, `created_at`, `updated_at`) VALUES
(2, 4, 'upgrade', '2G', '3G', '2026-02-23', '2026-02-23', NULL, '2026-02-23 00:03:31', '2026-02-23 00:03:31'),
(3, 4, 'downgrade', '3G', '1G', '2026-02-23', '2026-02-23', NULL, '2026-02-23 00:05:38', '2026-02-23 00:05:38');

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
  `registration_date` date DEFAULT NULL,
  `service_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bandwidth` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `customers`
--

INSERT INTO `customers` (`id`, `name`, `address`, `registration_date`, `service_type_id`, `bandwidth`, `status`, `created_at`, `updated_at`) VALUES
(43, 'Yanto Slamet Riyadi', 'Purbayasa, Purbalingga', '2023-12-22', 5, '1000', 'active', '2026-02-22 22:12:42', '2026-02-22 22:12:42'),
(44, 'Eri Febrianto', 'Kawunganten, Cilacap', NULL, 5, '1000', 'active', '2026-02-22 22:12:42', '2026-02-23 00:08:27'),
(45, 'Muhammad Mujahidin', 'Kawunganten, Cilacap', NULL, 5, '1000', 'active', '2026-02-22 22:12:42', '2026-02-23 00:41:35'),
(46, 'Sutarso Purnomo', 'Kawunganten, Cilacap', NULL, 5, '600', 'active', '2026-02-22 22:12:42', '2026-02-22 22:12:42'),
(47, 'Yusuf Fathoni', 'Kawunganten, Cilacap', NULL, 5, '850', 'active', '2026-02-22 22:12:42', '2026-02-22 22:12:42'),
(48, 'Aris Susanto', 'Kawunganten, Cilacap', NULL, 5, '1000', 'active', '2026-02-22 22:12:42', '2026-02-22 22:12:42'),
(49, 'Ade Prabowo', 'Kawunganten, Cilacap', NULL, 5, '300', 'active', '2026-02-22 22:12:42', '2026-02-22 22:12:42'),
(50, 'Edwin Wijayanto', 'Sidareja, Cilacap', NULL, 5, '1550', 'active', '2026-02-22 22:12:42', '2026-02-22 22:12:42'),
(51, 'Edi Susilo Prasetyo', 'Karangpucung, Cilacap', NULL, 5, '650', 'active', '2026-02-22 22:12:42', '2026-02-22 22:12:42'),
(52, 'Beni Wijaya', 'Susukan, Banjarnegara', NULL, 5, '150', 'active', '2026-02-22 22:12:42', '2026-02-22 22:12:42'),
(53, 'Gus Ibnu', 'Kroya, Cilacap', '2026-06-24', 5, '500', 'active', '2026-02-22 22:12:42', '2026-02-22 22:12:42'),
(54, 'Hendra Alexander', 'Cimanggu, Cilacap', NULL, 5, '500', 'active', '2026-02-22 22:12:42', '2026-02-22 22:12:42'),
(55, 'M. Hasyim Asyarifudin', 'Belik. Kab Pemalang', '2026-06-08', 5, '300', 'active', '2026-02-22 22:12:42', '2026-02-22 22:12:42'),
(56, 'Feber Rudi Lambok Nababan', 'Mrebet, Purbalingga', '2024-09-03', 5, '100', 'active', '2026-02-22 22:12:42', '2026-02-22 22:12:42'),
(57, 'Rinto', 'Gandrungmangu, Cilacap', NULL, 5, '1000', 'active', '2026-02-22 22:12:42', '2026-02-22 22:12:42'),
(58, 'Ahmad Mustaqim Udin', 'Kedungreja, Cilacap', NULL, 5, '150', 'active', '2026-02-22 22:12:42', '2026-02-22 22:35:58'),
(59, 'Yogi', 'Karangpucung, Cilacap', NULL, 5, '500', 'active', '2026-02-22 22:12:42', '2026-02-22 22:12:42'),
(60, 'IRIN', 'Karangpucung, Cilacap', NULL, 5, '250', 'active', '2026-02-22 22:12:42', '2026-02-22 22:12:42'),
(61, 'INU', 'Karangpucung, Cilacap', NULL, 5, '600', 'active', '2026-02-22 22:12:42', '2026-02-22 22:12:42'),
(62, 'Woyo Pujiono', 'Susukan, Banjarnegara', NULL, 5, '1000', 'active', '2026-02-22 22:12:42', '2026-02-22 22:12:42');

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

-- --------------------------------------------------------

--
-- Struktur dari tabel `devices`
--

CREATE TABLE `devices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `netbox_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `device_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `platform` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `location_id` bigint(20) UNSIGNED DEFAULT NULL,
  `site_id` bigint(20) UNSIGNED DEFAULT NULL,
  `site_name` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `devices`
--

INSERT INTO `devices` (`id`, `netbox_id`, `name`, `ip_address`, `role`, `device_type_id`, `brand`, `manufacturer`, `platform`, `model`, `serial_number`, `location_id`, `site_id`, `site_name`, `status`, `created_at`, `updated_at`) VALUES
(23, 22, 'CRS-309-KWT', NULL, 'Distribution Switch', NULL, 'Mikrotik', 'Mikrotik', 'ROS7', 'CRS309-1G-8S+', 'HEM08KZMZ51', 29, 18, 'KAWUNGANTEN', 'active', '2026-02-20 20:52:21', '2026-02-20 20:52:21'),
(24, 21, 'CSS-PUS-BOJONG', NULL, 'Distribution Switch', NULL, 'Mikrotik', 'Mikrotik', 'SWOS', 'CSS610-8G-2S+', 'D2770D4F09EC', 30, 18, 'KAWUNGANTEN', 'active', '2026-02-20 20:52:21', '2026-02-20 20:52:21'),
(25, 20, 'CSS-PUS-SWD', NULL, 'Distribution Switch', NULL, 'Mikrotik', 'Mikrotik', 'SWOS', 'CSS610-8G-2S+', 'D19C0E99B595', 32, 18, 'KAWUNGANTEN', 'active', '2026-02-20 20:52:21', '2026-02-20 20:52:21'),
(26, 3, 'IDC3D-AGG-DIST-1-C01-C1072', NULL, 'AGG Router', NULL, 'Mikrotik', 'Mikrotik', 'ROS7', 'CCR1072-1G-8S+', 'D8410FCA9156', 26, 16, 'IDC3D', 'active', '2026-02-20 20:52:21', '2026-02-20 20:52:21'),
(27, 2, 'IDC3D-AGG-DIST-2-CCR2216', NULL, 'AGG Router', NULL, 'Mikrotik', 'Mikrotik', NULL, 'CCR2216-1G-12XS-2XQ', 'HE508VBK7ER', 26, 16, 'IDC3D', 'active', '2026-02-20 20:52:21', '2026-02-20 20:52:21'),
(28, 1, 'IDC3D-CNT-1-CCR2216', NULL, 'BORDER-ROUTER', NULL, 'Mikrotik', 'Mikrotik', 'ROS7', 'CCR2116-12G-4S+', 'HD808BE4HXH', 26, 16, 'IDC3D', 'active', '2026-02-20 20:52:21', '2026-02-20 20:52:21'),
(29, 4, 'IDC3D-IPT-1-MIK-AI', '10.255.255.4', 'BORDER-ROUTER', NULL, 'Mikrobit', 'Mikrobit', 'x86_64', 'Mikrobit AINOS V3', '', 26, 16, 'IDC3D', 'active', '2026-02-20 20:52:21', '2026-02-20 20:52:21'),
(30, 5, 'IDC3D-PIK-SWC01-C518', NULL, 'AGG Switch', NULL, 'Mikrotik', 'Mikrotik', 'ROS7', 'CRS518-16XS-2XQ', 'HDA085FRMKB', 28, 24, 'TELUK', 'inactive', '2026-02-20 20:52:21', '2026-02-20 20:52:21'),
(31, 6, 'IDC3D-PIK-SWD01-CRS326', NULL, 'CORE DISTRIBUSION', NULL, 'Mikrotik', 'Mikrotik', 'ROS7', 'CRS326-24S+2Q+', 'D8500D3C3821', 26, 16, 'IDC3D', 'active', '2026-02-20 20:52:21', '2026-02-20 20:52:21'),
(32, 7, 'IDC3D-RR-1-C2004', NULL, 'RR', NULL, 'Mikrotik', 'Mikrotik', 'ROS7', 'CRS326-24S+2Q+', 'HE908YKS76B', 26, 16, 'IDC3D', 'active', '2026-02-20 20:52:21', '2026-02-20 20:52:21'),
(33, 32, 'NEUYG-PIK-BITBOX-TD1', NULL, 'Distribution Router', NULL, 'BitBox', 'BitBox', 'x86_64', 'BitBox TERA E1200DP 8G-4S+', '', 39, 20, 'NEUCETRIX-YOGYA', 'active', '2026-02-20 20:52:21', '2026-02-20 20:52:21'),
(34, 25, 'NEUYG-PIK-SWC01-C309', NULL, 'AGG Switch', NULL, 'Mikrotik', 'Mikrotik', 'ROS6', 'CRS309-1G-8S+', 'HDC0840B75N', 39, 20, 'NEUCETRIX-YOGYA', 'active', '2026-02-20 20:52:21', '2026-02-20 20:52:21'),
(35, 38, 'PIK-APJII-SW-01', NULL, 'CORE DISTRIBUSION', NULL, 'Huawei', 'Huawei', 'Huawei', 'CE6855-48S6Q-HI', '2102350RTCDMK9001371', 38, 19, 'MMR APJII', 'active', '2026-02-20 20:52:21', '2026-02-20 20:52:21'),
(36, 28, 'PIK-BMS-CCR1009', NULL, 'Distribution Router', NULL, 'Mikrotik', 'Mikrotik', 'ROS7', 'CCR1009-7G-1C-1S+', 'E3200D18F435', 45, 13, 'Banyumas', 'active', '2026-02-20 20:52:21', '2026-02-20 20:52:21'),
(37, 37, 'PIK-CYBER-CNT-1', NULL, 'BORDER-ROUTER', NULL, 'BitBox', 'BitBox', 'VYOS', 'BITBOX Tera PMAX i9700plus-8G-4S+', 'I970020230915001', 27, 15, 'Cyber 1 Lantai 1', 'active', '2026-02-20 20:52:21', '2026-02-20 20:52:21'),
(38, 34, 'PIK-CYBER-OTB', NULL, 'OTB', NULL, 'RIV', 'RIV', NULL, 'ODF 48 Core LC', '', 27, 15, 'Cyber 1 Lantai 1', 'active', '2026-02-20 20:52:21', '2026-02-20 20:52:21'),
(39, 35, 'PIK-CYBER-RR-1', NULL, 'RR', NULL, 'Mikrotik', 'Mikrotik', 'ROS7', 'CCR2004-1G-12S+', 'HDD08BA8CZ3', 27, 15, 'Cyber 1 Lantai 1', 'active', '2026-02-20 20:52:21', '2026-02-20 20:52:21'),
(40, 36, 'PIK-CYBER-SW-1', NULL, 'CORE DISTRIBUSION', NULL, 'Mikrotik', 'Mikrotik', 'ROS7', 'CRS326-24S+2Q+', 'HCS08CXJA41', 27, 15, 'Cyber 1 Lantai 1', 'active', '2026-02-20 20:52:21', '2026-02-20 20:52:21'),
(41, 39, 'PIK-IDC-SWD-02', NULL, 'CORE DISTRIBUSION', NULL, 'Mikrotik', 'Mikrotik', 'ROS7', 'CRS326-24S+2Q+', 'HJQ0AX2MMKJ', 26, 16, 'IDC3D', 'active', '2026-02-20 20:52:21', '2026-02-20 20:52:21'),
(43, 18, 'PIK-KRPCG-CRS309-1G-8S+', NULL, 'Distribution Switch', NULL, 'Mikrotik', 'Mikrotik', 'ROS7', 'CRS309-1G-8S+', '', NULL, 17, 'Karangpucung', 'active', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(44, 17, 'PIK-KRPCG-RB5009', '10.255.255.130', 'Distribution Router', NULL, 'Mikrotik', 'Mikrotik', 'ROS7', 'RB5009UG+S+', '', NULL, 17, 'Karangpucung', 'active', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(45, 16, 'PIK-LGS-RB5009', NULL, 'Distribution Router', NULL, 'Mikrotik', 'Mikrotik', 'ROS7', 'RB5009UG+S+', '', 36, 23, 'Sumbang', 'active', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(46, 33, 'PIK-PBYS-CSS326', NULL, 'Distribution Switch', NULL, 'Mikrotik', 'Mikrotik', 'SWOS', 'CSS326-24G-2S+', 'D2770C3A94D8', 40, 21, 'Purbalingga', 'active', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(47, 9, 'PIK-PWT-ARSA-CRS309', NULL, 'POP Switch', NULL, 'Mikrotik', 'Mikrotik', 'ROS7', 'CRS309-1G-8S+', 'HEM08T1QNME', 41, 24, 'TELUK', 'active', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(48, 10, 'PIK-PWT-ARSA-CRS326-24G-2S+', NULL, 'Distribution Switch', NULL, 'Mikrotik', 'Mikrotik', 'ROS7', 'CRS326-24G-2S+RM', 'F5F60F0C2AA8', 41, 24, 'TELUK', 'active', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(49, 26, 'PIK-SDGRN-CRS326', NULL, 'POP Switch', NULL, 'Mikrotik', 'Mikrotik', 'ROS6', 'CRS326-24G-2S+RM', 'F5F60FB519F8', 45, 13, 'Banyumas', 'active', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(50, 29, 'PIK07-KRA', NULL, 'Distribution Switch', NULL, 'Mikrotik', 'Mikrotik', 'ROS6', 'CRS305-1G-4S+', '', NULL, 22, 'SIADREJA', 'active', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(51, 23, 'PUSKO01-GDM-CRS310-1G-5S-4S+', NULL, 'Distribution Switch', NULL, 'Mikrotik', 'Mikrotik', 'ROS7', 'CRS310-1G-5S-4S+', 'HH50A52G35Y', 43, 22, 'SIADREJA', 'active', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(52, 27, 'PUSKO01-KDR-CRS309', NULL, 'Distribution Switch', NULL, 'Mikrotik', 'Mikrotik', 'ROS7', 'CRS309-1G-8S+', 'D8480F1E37A5', 44, 22, 'SIADREJA', 'active', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(53, 15, 'PUSKO01-KDRJ-CCR2116-12G-4S+', NULL, 'Distribution Router', NULL, 'Mikrotik', 'Mikrotik', 'ROS7', 'CCR2116-12G-4S+', '', 42, 22, 'SIADREJA', 'active', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(54, 14, 'PUSKO02-KWT-CCR2116-12G-4S+', NULL, 'Distribution Router', NULL, 'Mikrotik', 'Mikrotik', 'ROS7', 'CCR2116-12G-4S+', 'HC807K01541', 29, 18, 'KAWUNGANTEN', 'active', '2026-02-20 21:00:36', '2026-02-20 21:00:36'),
(55, 19, 'PUSKO02-KWT-CRS326-24G-2S+', NULL, 'Distribution Switch', NULL, 'Mikrotik', 'Mikrotik', 'ROS7', 'CRS326-24G-2S+RM', 'F5F60FF450D5', 31, 18, 'KAWUNGANTEN', 'active', '2026-02-20 21:00:36', '2026-02-20 21:00:36'),
(56, 31, 'PWT-PIK-C02-CCR1036-12G-4S', NULL, 'Distribution Router', NULL, 'Mikrotik', 'Mikrotik', 'ROS7', 'CCR1036-12G-4S+', 'D8340C75AE21', 34, 14, 'BATURADEN', 'active', '2026-02-20 21:00:36', '2026-02-20 21:00:36'),
(57, 30, 'PWT-PIK-KTS-CRS310-1G-5S-4S+', NULL, 'Distribution Switch', NULL, 'Mikrotik', 'Mikrotik', 'ROS7', 'CRS310-1G-5S-4S+', 'HH50ABPGRME', 34, 14, 'BATURADEN', 'active', '2026-02-20 21:00:36', '2026-02-20 21:00:36'),
(58, 12, 'Server Farm 1', NULL, 'Server Farm', NULL, 'DELL', 'DELL', NULL, 'PowerEdge R610', '', 41, 24, 'TELUK', 'active', '2026-02-20 21:00:36', '2026-02-20 21:00:36'),
(59, 11, 'Server Farm 2', NULL, 'Server Farm', NULL, 'DELL', 'DELL', NULL, 'PowerEdge R630', '', 41, 24, 'TELUK', 'active', '2026-02-20 21:00:36', '2026-02-20 21:00:36'),
(60, 13, 'Server Farm 3', NULL, 'Server Farm', NULL, 'DELL', 'DELL', NULL, 'PowerEdge R740', '', 41, 24, 'TELUK', 'active', '2026-02-20 21:00:36', '2026-02-20 21:00:36'),
(61, 8, 'SW-Border-iForte', NULL, 'Border Switch', NULL, 'Huawei', 'Huawei', NULL, 'SW-iForte', '', 26, 16, 'IDC3D', 'active', '2026-02-20 21:00:36', '2026-02-20 21:00:36'),
(62, 24, 'YG-NEUCIX-PIK-C01-CCR1036', NULL, 'BORDER-ROUTER', NULL, 'Mikrotik', 'Mikrotik', 'ROS6', 'CCR1036-8G-2S+', '9F1D0827B10A', 39, 20, 'NEUCETRIX-YOGYA', 'active', '2026-02-20 21:00:36', '2026-02-20 21:00:36');

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
  `site_id` bigint(20) UNSIGNED DEFAULT NULL,
  `site_name` varchar(255) DEFAULT NULL,
  `netbox_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `tenant` varchar(255) DEFAULT NULL,
  `device_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `status` varchar(255) DEFAULT 'active',
  `type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `locations`
--

INSERT INTO `locations` (`id`, `site_id`, `site_name`, `netbox_id`, `name`, `slug`, `address`, `description`, `tenant`, `device_count`, `status`, `type`, `created_at`, `updated_at`) VALUES
(26, 16, 'IDC3D', 1, 'IDC3D', 'idc3d', NULL, '', 'APIK Media', 8, 'active', 'location', '2026-02-20 20:50:36', '2026-02-20 20:50:36'),
(27, 15, 'Cyber 1 Lantai 1', 22, 'IPDN', 'ipdn', NULL, '', 'Raja Data', 4, 'active', 'location', '2026-02-20 20:50:36', '2026-02-20 20:50:36'),
(28, 24, 'TELUK', 21, 'Inventory', 'inventory', NULL, '', NULL, 1, 'active', 'location', '2026-02-20 20:50:36', '2026-02-20 20:50:36'),
(29, 18, 'KAWUNGANTEN', 7, 'Karangbawang', 'karangbawang', NULL, '', NULL, 5, 'active', 'location', '2026-02-20 20:50:36', '2026-02-20 20:50:36'),
(30, 18, 'KAWUNGANTEN', 8, 'Bojong', 'bojong', NULL, '', NULL, 1, 'active', 'location', '2026-02-20 20:50:36', '2026-02-20 20:50:36'),
(31, 18, 'KAWUNGANTEN', 18, 'Gunungsari', 'gunungsari', NULL, '', NULL, 1, 'active', 'location', '2026-02-20 20:50:36', '2026-02-20 20:50:36'),
(32, 18, 'KAWUNGANTEN', 10, 'Sarwadadi', 'sarwadadi', NULL, '', NULL, 1, 'active', 'location', '2026-02-20 20:50:36', '2026-02-20 20:50:36'),
(33, 18, 'KAWUNGANTEN', 9, 'Tegalsari', 'tegalsari', NULL, '', NULL, 0, 'active', 'location', '2026-02-20 20:50:36', '2026-02-20 20:50:36'),
(34, 14, 'BATURADEN', 14, 'Kutasari', 'kutasari', NULL, '', NULL, 2, 'active', 'location', '2026-02-20 20:50:36', '2026-02-20 20:50:36'),
(35, 14, 'BATURADEN', 15, 'Karangmangu', 'karangmangu', NULL, '', NULL, 0, 'active', 'location', '2026-02-20 20:50:36', '2026-02-20 20:50:36'),
(36, 23, 'Sumbang', 17, 'Linggasari', 'linggasari', NULL, '', NULL, 1, 'active', 'location', '2026-02-20 20:50:36', '2026-02-20 20:50:36'),
(37, 19, 'MMR APJII', 2, 'MMR APJII', 'mmr-apjii', NULL, '', 'APJII', 1, 'active', 'location', '2026-02-20 20:50:36', '2026-02-20 20:50:36'),
(38, 19, 'MMR APJII', 20, 'MMR Bersama K7', 'mmr-bersama-k7', NULL, '', 'PUSKOMEDIA', 1, 'active', 'location', '2026-02-20 20:50:36', '2026-02-20 20:50:36'),
(39, 20, 'NEUCETRIX-YOGYA', 3, 'NEUCETRIX-YOGYA', 'neucetrix-yogya', NULL, '', NULL, 3, 'active', 'location', '2026-02-20 20:50:36', '2026-02-20 20:50:36'),
(40, 21, 'Purbalingga', 19, 'Purbayasa', 'purbayasa', NULL, '', NULL, 1, 'active', 'location', '2026-02-20 20:50:36', '2026-02-20 20:50:36'),
(41, 24, 'TELUK', 4, 'R-Server', 'R-Server', NULL, '', 'Divisi Teknis', 5, 'active', 'location', '2026-02-20 20:50:36', '2026-02-20 20:50:36'),
(42, 22, 'SIADREJA', 11, 'Sidareja', 'sidareja', NULL, '', NULL, 3, 'active', 'location', '2026-02-20 20:50:36', '2026-02-20 20:50:36'),
(43, 22, 'SIADREJA', 13, 'Gandrungmanis', 'Gandrungmanis', NULL, '', NULL, 1, 'active', 'location', '2026-02-20 20:50:36', '2026-02-20 20:50:36'),
(44, 22, 'SIADREJA', 12, 'Kedungreja', 'kedungreja', NULL, '', NULL, 1, 'active', 'location', '2026-02-20 20:50:36', '2026-02-20 20:50:36'),
(45, 13, 'Banyumas', 16, 'Sudagaran', 'sudagaran', NULL, '', NULL, 2, 'active', 'location', '2026-02-20 20:50:36', '2026-02-20 20:50:36');

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
(23, '2026_02_20_073405_merge_resellers_to_customers_table', 2),
(24, '2026_02_20_080040_add_netbox_fields_to_locations_and_devices', 3),
(25, '2026_02_21_020000_add_netbox_sync_fields', 3),
(26, '2026_02_21_030000_alter_locations_devices_for_netbox', 3),
(27, '2026_02_21_040001_create_site_groups_table', 4),
(28, '2026_02_21_040002_create_sites_table', 4),
(29, '2026_02_21_040003_alter_locations_for_hierarchy', 4),
(30, '2026_02_21_040004_add_site_id_to_devices', 4),
(31, '2026_02_21_035534_create_netbox_sync_logs_table', 5),
(32, '2026_02_21_035534_fix_location_id_nullable_in_devices', 5),
(33, '2026_02_21_045748_add_registration_date_to_customers_table', 6),
(34, '2026_02_23_054510_add_node_c_d_e_to_backbone_links_table', 7),
(35, '2026_02_23_055436_create_providers_table', 8),
(36, '2026_02_23_061140_add_provider_and_asn_to_upstreams_table', 9),
(37, '2026_02_23_065424_create_backbone_logs_table', 10),
(38, '2026_02_23_072101_add_resolve_date_to_backbone_incidents_table', 11);

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
-- Struktur dari tabel `netbox_sync_logs`
--

CREATE TABLE `netbox_sync_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `entity_type` varchar(255) NOT NULL,
  `netbox_id` bigint(20) UNSIGNED DEFAULT NULL,
  `entity_name` varchar(255) DEFAULT NULL,
  `status` enum('ok','skipped','error') NOT NULL,
  `message` text DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `synced_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `netbox_sync_logs`
--

INSERT INTO `netbox_sync_logs` (`id`, `entity_type`, `netbox_id`, `entity_name`, `status`, `message`, `payload`, `synced_at`, `created_at`, `updated_at`) VALUES
(1, 'devices', 22, 'CRS-309-KWT', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(2, 'devices', 21, 'CSS-PUS-BOJONG', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(3, 'devices', 20, 'CSS-PUS-SWD', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(4, 'devices', 3, 'IDC3D-AGG-DIST-1-C01-C1072', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(5, 'devices', 2, 'IDC3D-AGG-DIST-2-CCR2216', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(6, 'devices', 1, 'IDC3D-CNT-1-CCR2216', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(7, 'devices', 4, 'IDC3D-IPT-1-MIK-AI', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(8, 'devices', 5, 'IDC3D-PIK-SWC01-C518', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(9, 'devices', 6, 'IDC3D-PIK-SWD01-CRS326', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(10, 'devices', 7, 'IDC3D-RR-1-C2004', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(11, 'devices', 32, 'NEUYG-PIK-BITBOX-TD1', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(12, 'devices', 25, 'NEUYG-PIK-SWC01-C309', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(13, 'devices', 38, 'PIK-APJII-SW-01', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(14, 'devices', 28, 'PIK-BMS-CCR1009', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(15, 'devices', 37, 'PIK-CYBER-CNT-1', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(16, 'devices', 34, 'PIK-CYBER-OTB', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(17, 'devices', 35, 'PIK-CYBER-RR-1', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(18, 'devices', 36, 'PIK-CYBER-SW-1', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(19, 'devices', 39, 'PIK-IDC-SWD-02', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(20, 'devices', 18, 'PIK-KRPCG-CRS309-1G-8S+', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(21, 'devices', 17, 'PIK-KRPCG-RB5009', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(22, 'devices', 16, 'PIK-LGS-RB5009', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(23, 'devices', 33, 'PIK-PBYS-CSS326', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(24, 'devices', 9, 'PIK-PWT-ARSA-CRS309', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(25, 'devices', 10, 'PIK-PWT-ARSA-CRS326-24G-2S+', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(26, 'devices', 26, 'PIK-SDGRN-CRS326', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(27, 'devices', 29, 'PIK07-KRA', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(28, 'devices', 23, 'PUSKO01-GDM-CRS310-1G-5S-4S+', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(29, 'devices', 27, 'PUSKO01-KDR-CRS309', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(30, 'devices', 15, 'PUSKO01-KDRJ-CCR2116-12G-4S+', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:35', '2026-02-20 21:00:35', '2026-02-20 21:00:35'),
(31, 'devices', 14, 'PUSKO02-KWT-CCR2116-12G-4S+', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:36', '2026-02-20 21:00:36', '2026-02-20 21:00:36'),
(32, 'devices', 19, 'PUSKO02-KWT-CRS326-24G-2S+', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:36', '2026-02-20 21:00:36', '2026-02-20 21:00:36'),
(33, 'devices', 31, 'PWT-PIK-C02-CCR1036-12G-4S', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:36', '2026-02-20 21:00:36', '2026-02-20 21:00:36'),
(34, 'devices', 30, 'PWT-PIK-KTS-CRS310-1G-5S-4S+', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:36', '2026-02-20 21:00:36', '2026-02-20 21:00:36'),
(35, 'devices', 12, 'Server Farm 1', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:36', '2026-02-20 21:00:36', '2026-02-20 21:00:36'),
(36, 'devices', 11, 'Server Farm 2', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:36', '2026-02-20 21:00:36', '2026-02-20 21:00:36'),
(37, 'devices', 13, 'Server Farm 3', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:36', '2026-02-20 21:00:36', '2026-02-20 21:00:36'),
(38, 'devices', 8, 'SW-Border-iForte', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:36', '2026-02-20 21:00:36', '2026-02-20 21:00:36'),
(39, 'devices', 24, 'YG-NEUCIX-PIK-C01-CCR1036', 'ok', 'Synced OK', NULL, '2026-02-20 21:00:36', '2026-02-20 21:00:36', '2026-02-20 21:00:36'),
(40, 'site_groups', 2, 'DACEN', 'ok', 'Synced OK', NULL, '2026-02-20 21:53:30', '2026-02-20 21:53:30', '2026-02-20 21:53:30'),
(41, 'site_groups', 4, 'POP', 'ok', 'Synced OK', NULL, '2026-02-20 21:53:31', '2026-02-20 21:53:31', '2026-02-20 21:53:31'),
(42, 'site_groups', 3, 'DACEN DIY', 'ok', 'Synced OK', NULL, '2026-02-20 21:53:31', '2026-02-20 21:53:31', '2026-02-20 21:53:31'),
(43, 'site_groups', 1, 'DACEN JAKARTA', 'ok', 'Synced OK', NULL, '2026-02-20 21:53:31', '2026-02-20 21:53:31', '2026-02-20 21:53:31'),
(44, 'site_groups', 8, 'POP-BANJARNEGARA', 'ok', 'Synced OK', NULL, '2026-02-20 21:53:31', '2026-02-20 21:53:31', '2026-02-20 21:53:31'),
(45, 'site_groups', 5, 'POP-BANYUMAS', 'ok', 'Synced OK', NULL, '2026-02-20 21:53:31', '2026-02-20 21:53:31', '2026-02-20 21:53:31'),
(46, 'site_groups', 9, 'POP-BREBES', 'ok', 'Synced OK', NULL, '2026-02-20 21:53:31', '2026-02-20 21:53:31', '2026-02-20 21:53:31'),
(47, 'site_groups', 6, 'POP-CILACAP', 'ok', 'Synced OK', NULL, '2026-02-20 21:53:31', '2026-02-20 21:53:31', '2026-02-20 21:53:31'),
(48, 'site_groups', 10, 'POP-PEMALANG', 'ok', 'Synced OK', NULL, '2026-02-20 21:53:31', '2026-02-20 21:53:31', '2026-02-20 21:53:31'),
(49, 'site_groups', 7, 'POP-PUBALINGGA', 'ok', 'Synced OK', NULL, '2026-02-20 21:53:31', '2026-02-20 21:53:31', '2026-02-20 21:53:31'),
(50, 'sites', 8, 'Banyumas', 'ok', 'Synced OK', NULL, '2026-02-20 21:55:55', '2026-02-20 21:55:55', '2026-02-20 21:55:55'),
(51, 'sites', 7, 'BATURADEN', 'ok', 'Synced OK', NULL, '2026-02-20 21:55:55', '2026-02-20 21:55:55', '2026-02-20 21:55:55'),
(52, 'sites', 12, 'Cyber 1 Lantai 1', 'ok', 'Synced OK', NULL, '2026-02-20 21:55:55', '2026-02-20 21:55:55', '2026-02-20 21:55:55'),
(53, 'sites', 1, 'IDC3D', 'ok', 'Synced OK', NULL, '2026-02-20 21:55:55', '2026-02-20 21:55:55', '2026-02-20 21:55:55'),
(54, 'sites', 10, 'Karangpucung', 'ok', 'Synced OK', NULL, '2026-02-20 21:55:55', '2026-02-20 21:55:55', '2026-02-20 21:55:55'),
(55, 'sites', 5, 'KAWUNGANTEN', 'ok', 'Synced OK', NULL, '2026-02-20 21:55:55', '2026-02-20 21:55:55', '2026-02-20 21:55:55'),
(56, 'sites', 2, 'MMR APJII', 'ok', 'Synced OK', NULL, '2026-02-20 21:55:55', '2026-02-20 21:55:55', '2026-02-20 21:55:55'),
(57, 'sites', 3, 'NEUCETRIX-YOGYA', 'ok', 'Synced OK', NULL, '2026-02-20 21:55:55', '2026-02-20 21:55:55', '2026-02-20 21:55:55'),
(58, 'sites', 11, 'Purbalingga', 'ok', 'Synced OK', NULL, '2026-02-20 21:55:55', '2026-02-20 21:55:55', '2026-02-20 21:55:55'),
(59, 'sites', 6, 'SIADREJA', 'ok', 'Synced OK', NULL, '2026-02-20 21:55:55', '2026-02-20 21:55:55', '2026-02-20 21:55:55'),
(60, 'sites', 9, 'Sumbang', 'ok', 'Synced OK', NULL, '2026-02-20 21:55:55', '2026-02-20 21:55:55', '2026-02-20 21:55:55'),
(61, 'sites', 4, 'TELUK', 'ok', 'Synced OK', NULL, '2026-02-20 21:55:55', '2026-02-20 21:55:55', '2026-02-20 21:55:55'),
(62, 'locations', 1, 'IDC3D', 'ok', 'Synced OK', NULL, '2026-02-20 21:56:02', '2026-02-20 21:56:02', '2026-02-20 21:56:02'),
(63, 'locations', 22, 'IPDN', 'ok', 'Synced OK', NULL, '2026-02-20 21:56:02', '2026-02-20 21:56:02', '2026-02-20 21:56:02'),
(64, 'locations', 21, 'Inventory', 'ok', 'Synced OK', NULL, '2026-02-20 21:56:02', '2026-02-20 21:56:02', '2026-02-20 21:56:02'),
(65, 'locations', 7, 'Karangbawang', 'ok', 'Synced OK', NULL, '2026-02-20 21:56:02', '2026-02-20 21:56:02', '2026-02-20 21:56:02'),
(66, 'locations', 8, 'Bojong', 'ok', 'Synced OK', NULL, '2026-02-20 21:56:02', '2026-02-20 21:56:02', '2026-02-20 21:56:02'),
(67, 'locations', 18, 'Gunungsari', 'ok', 'Synced OK', NULL, '2026-02-20 21:56:02', '2026-02-20 21:56:02', '2026-02-20 21:56:02'),
(68, 'locations', 10, 'Sarwadadi', 'ok', 'Synced OK', NULL, '2026-02-20 21:56:02', '2026-02-20 21:56:02', '2026-02-20 21:56:02'),
(69, 'locations', 9, 'Tegalsari', 'ok', 'Synced OK', NULL, '2026-02-20 21:56:02', '2026-02-20 21:56:02', '2026-02-20 21:56:02'),
(70, 'locations', 14, 'Kutasari', 'ok', 'Synced OK', NULL, '2026-02-20 21:56:02', '2026-02-20 21:56:02', '2026-02-20 21:56:02'),
(71, 'locations', 15, 'Karangmangu', 'ok', 'Synced OK', NULL, '2026-02-20 21:56:02', '2026-02-20 21:56:02', '2026-02-20 21:56:02'),
(72, 'locations', 17, 'Linggasari', 'ok', 'Synced OK', NULL, '2026-02-20 21:56:02', '2026-02-20 21:56:02', '2026-02-20 21:56:02'),
(73, 'locations', 2, 'MMR APJII', 'ok', 'Synced OK', NULL, '2026-02-20 21:56:02', '2026-02-20 21:56:02', '2026-02-20 21:56:02'),
(74, 'locations', 20, 'MMR Bersama K7', 'ok', 'Synced OK', NULL, '2026-02-20 21:56:02', '2026-02-20 21:56:02', '2026-02-20 21:56:02'),
(75, 'locations', 3, 'NEUCETRIX-YOGYA', 'ok', 'Synced OK', NULL, '2026-02-20 21:56:02', '2026-02-20 21:56:02', '2026-02-20 21:56:02'),
(76, 'locations', 19, 'Purbayasa', 'ok', 'Synced OK', NULL, '2026-02-20 21:56:02', '2026-02-20 21:56:02', '2026-02-20 21:56:02'),
(77, 'locations', 4, 'R-Server', 'ok', 'Synced OK', NULL, '2026-02-20 21:56:02', '2026-02-20 21:56:02', '2026-02-20 21:56:02'),
(78, 'locations', 11, 'Sidareja', 'ok', 'Synced OK', NULL, '2026-02-20 21:56:02', '2026-02-20 21:56:02', '2026-02-20 21:56:02'),
(79, 'locations', 13, 'Gandrungmanis', 'ok', 'Synced OK', NULL, '2026-02-20 21:56:02', '2026-02-20 21:56:02', '2026-02-20 21:56:02'),
(80, 'locations', 12, 'Kedungreja', 'ok', 'Synced OK', NULL, '2026-02-20 21:56:02', '2026-02-20 21:56:02', '2026-02-20 21:56:02'),
(81, 'locations', 16, 'Sudagaran', 'ok', 'Synced OK', NULL, '2026-02-20 21:56:02', '2026-02-20 21:56:02', '2026-02-20 21:56:02'),
(82, 'site_groups', 2, 'DACEN', 'ok', 'Synced OK', NULL, '2026-02-22 21:49:50', '2026-02-22 21:49:50', '2026-02-22 21:49:50'),
(83, 'site_groups', 4, 'POP', 'ok', 'Synced OK', NULL, '2026-02-22 21:49:50', '2026-02-22 21:49:50', '2026-02-22 21:49:50'),
(84, 'site_groups', 3, 'DACEN DIY', 'ok', 'Synced OK', NULL, '2026-02-22 21:49:50', '2026-02-22 21:49:50', '2026-02-22 21:49:50'),
(85, 'site_groups', 1, 'DACEN JAKARTA', 'ok', 'Synced OK', NULL, '2026-02-22 21:49:50', '2026-02-22 21:49:50', '2026-02-22 21:49:50'),
(86, 'site_groups', 8, 'POP-BANJARNEGARA', 'ok', 'Synced OK', NULL, '2026-02-22 21:49:50', '2026-02-22 21:49:50', '2026-02-22 21:49:50'),
(87, 'site_groups', 5, 'POP-BANYUMAS', 'ok', 'Synced OK', NULL, '2026-02-22 21:49:50', '2026-02-22 21:49:50', '2026-02-22 21:49:50'),
(88, 'site_groups', 9, 'POP-BREBES', 'ok', 'Synced OK', NULL, '2026-02-22 21:49:50', '2026-02-22 21:49:50', '2026-02-22 21:49:50'),
(89, 'site_groups', 6, 'POP-CILACAP', 'ok', 'Synced OK', NULL, '2026-02-22 21:49:50', '2026-02-22 21:49:50', '2026-02-22 21:49:50'),
(90, 'site_groups', 10, 'POP-PEMALANG', 'ok', 'Synced OK', NULL, '2026-02-22 21:49:50', '2026-02-22 21:49:50', '2026-02-22 21:49:50'),
(91, 'site_groups', 7, 'POP-PUBALINGGA', 'ok', 'Synced OK', NULL, '2026-02-22 21:49:50', '2026-02-22 21:49:50', '2026-02-22 21:49:50'),
(92, 'devices', 22, 'CRS-309-KWT', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(93, 'devices', 21, 'CSS-PUS-BOJONG', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(94, 'devices', 20, 'CSS-PUS-SWD', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(95, 'devices', 3, 'IDC3D-AGG-DIST-1-C01-C1072', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(96, 'devices', 2, 'IDC3D-AGG-DIST-2-CCR2216', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(97, 'devices', 1, 'IDC3D-CNT-1-CCR2216', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(98, 'devices', 4, 'IDC3D-IPT-1-MIK-AI', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(99, 'devices', 5, 'IDC3D-PIK-SWC01-C518', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(100, 'devices', 6, 'IDC3D-PIK-SWD01-CRS326', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(101, 'devices', 7, 'IDC3D-RR-1-C2004', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(102, 'devices', 32, 'NEUYG-PIK-BITBOX-TD1', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(103, 'devices', 25, 'NEUYG-PIK-SWC01-C309', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(104, 'devices', 38, 'PIK-APJII-SW-01', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(105, 'devices', 28, 'PIK-BMS-CCR1009', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(106, 'devices', 37, 'PIK-CYBER-CNT-1', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(107, 'devices', 34, 'PIK-CYBER-OTB', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(108, 'devices', 35, 'PIK-CYBER-RR-1', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(109, 'devices', 36, 'PIK-CYBER-SW-1', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(110, 'devices', 39, 'PIK-IDC-SWD-02', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(111, 'devices', 18, 'PIK-KRPCG-CRS309-1G-8S+', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(112, 'devices', 17, 'PIK-KRPCG-RB5009', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(113, 'devices', 16, 'PIK-LGS-RB5009', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(114, 'devices', 33, 'PIK-PBYS-CSS326', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(115, 'devices', 9, 'PIK-PWT-ARSA-CRS309', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(116, 'devices', 10, 'PIK-PWT-ARSA-CRS326-24G-2S+', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(117, 'devices', 26, 'PIK-SDGRN-CRS326', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(118, 'devices', 29, 'PIK07-KRA', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(119, 'devices', 23, 'PUSKO01-GDM-CRS310-1G-5S-4S+', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(120, 'devices', 27, 'PUSKO01-KDR-CRS309', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(121, 'devices', 15, 'PUSKO01-KDRJ-CCR2116-12G-4S+', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(122, 'devices', 14, 'PUSKO02-KWT-CCR2116-12G-4S+', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(123, 'devices', 19, 'PUSKO02-KWT-CRS326-24G-2S+', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(124, 'devices', 31, 'PWT-PIK-C02-CCR1036-12G-4S', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(125, 'devices', 30, 'PWT-PIK-KTS-CRS310-1G-5S-4S+', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(126, 'devices', 12, 'Server Farm 1', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(127, 'devices', 11, 'Server Farm 2', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(128, 'devices', 13, 'Server Farm 3', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(129, 'devices', 8, 'SW-Border-iForte', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52'),
(130, 'devices', 24, 'YG-NEUCIX-PIK-C01-CCR1036', 'ok', 'Synced OK', NULL, '2026-02-23 00:41:52', '2026-02-23 00:41:52', '2026-02-23 00:41:52');

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
-- Struktur dari tabel `providers`
--

CREATE TABLE `providers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `providers`
--

INSERT INTO `providers` (`id`, `name`, `type`, `created_at`, `updated_at`) VALUES
(1, 'IFORTE', 'Provider', '2026-02-22 23:00:06', '2026-02-22 23:00:06'),
(2, 'Fiberstar', 'Provider', '2026-02-22 23:00:12', '2026-02-22 23:00:12'),
(3, 'RBN', 'NAP', '2026-02-22 23:00:18', '2026-02-22 23:00:22');

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
(7, 44, 'upgrade', '600', '1000', '2026-02-23', '2026-02-23', NULL, '2026-02-23 00:08:27', '2026-02-23 00:08:27'),
(8, 45, 'downgrade', '200', '1000', '2026-02-23', '2026-02-23', NULL, '2026-02-23 00:41:35', '2026-02-23 00:41:35');

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
('TuGSsgBuVpuT77c6cKpmb9xpbdxr3yUchIiDTp2h', 1, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUVdtOE9EZXh0UENraUpFRHlEblV5VGpCYTVNTjNJY0NUbXhBNmtNYSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMxOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjk6ImRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1771832860);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sites`
--

CREATE TABLE `sites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `netbox_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT 'active',
  `region` varchar(255) DEFAULT NULL,
  `site_group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `site_group_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `device_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sites`
--

INSERT INTO `sites` (`id`, `netbox_id`, `name`, `slug`, `status`, `region`, `site_group_id`, `site_group_name`, `address`, `description`, `device_count`, `created_at`, `updated_at`) VALUES
(13, 8, 'Banyumas', 'banyumas', 'active', 'JATENG', 16, 'POP-BANYUMAS', '', '', 2, '2026-02-20 20:50:32', '2026-02-20 20:50:32'),
(14, 7, 'BATURADEN', 'baturaden', 'active', 'JATENG', 16, 'POP-BANYUMAS', '', '', 2, '2026-02-20 20:50:32', '2026-02-20 20:50:32'),
(15, 12, 'Cyber 1 Lantai 1', 'cyber-1', 'active', 'JAKARTA', 14, 'DACEN JAKARTA', '', '', 4, '2026-02-20 20:50:32', '2026-02-20 20:50:32'),
(16, 1, 'IDC3D', 'idc3d', 'active', 'JAKARTA', 14, 'DACEN JAKARTA', '', '', 8, '2026-02-20 20:50:32', '2026-02-20 20:50:32'),
(17, 10, 'Karangpucung', 'karangpucung', 'active', 'JATENG', 18, 'POP-CILACAP', '', '', 2, '2026-02-20 20:50:32', '2026-02-20 20:50:32'),
(18, 5, 'KAWUNGANTEN', 'kawunganten', 'active', 'JATENG', 18, 'POP-CILACAP', '', '', 5, '2026-02-20 20:50:32', '2026-02-20 20:50:32'),
(19, 2, 'MMR APJII', 'mmr-apjii', 'active', 'JAKARTA', 14, 'DACEN JAKARTA', '', '', 1, '2026-02-20 20:50:32', '2026-02-20 20:50:32'),
(20, 3, 'NEUCETRIX-YOGYA', 'neucetrix-yogya', 'active', 'DIY', 13, 'DACEN DIY', '', '', 3, '2026-02-20 20:50:32', '2026-02-20 20:50:32'),
(21, 11, 'Purbalingga', 'purbalingga', 'active', 'JATENG', 20, 'POP-PUBALINGGA', '', '', 1, '2026-02-20 20:50:32', '2026-02-20 20:50:32'),
(22, 6, 'SIADREJA', 'siadreja', 'active', 'JATENG', 18, 'POP-CILACAP', '', '', 4, '2026-02-20 20:50:32', '2026-02-20 20:50:32'),
(23, 9, 'Sumbang', 'sumbang', 'active', 'JATENG', 16, 'POP-BANYUMAS', '', '', 1, '2026-02-20 20:50:32', '2026-02-20 20:50:32'),
(24, 4, 'TELUK', 'teluk', 'active', 'JATENG', 16, 'POP-BANYUMAS', '', '', 6, '2026-02-20 20:50:32', '2026-02-20 20:50:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `site_groups`
--

CREATE TABLE `site_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `netbox_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `parent_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `depth` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `site_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `site_groups`
--

INSERT INTO `site_groups` (`id`, `netbox_id`, `name`, `slug`, `parent_id`, `parent_name`, `description`, `depth`, `site_count`, `created_at`, `updated_at`) VALUES
(11, 2, 'DACEN', 'dacen', NULL, NULL, '', 0, 4, '2026-02-20 20:50:25', '2026-02-20 20:50:25'),
(12, 4, 'POP', 'pop', NULL, NULL, '', 0, 8, '2026-02-20 20:50:25', '2026-02-20 20:50:25'),
(13, 3, 'DACEN DIY', 'dacen-diy', 11, 'DACEN', '', 1, 1, '2026-02-20 20:50:25', '2026-02-20 20:50:25'),
(14, 1, 'DACEN JAKARTA', 'dacen-jakarta', 11, 'DACEN', '', 1, 3, '2026-02-20 20:50:25', '2026-02-20 20:50:25'),
(15, 8, 'POP-BANJARNEGARA', 'pop-banjarnegara', 12, 'POP', '', 1, 0, '2026-02-20 20:50:25', '2026-02-20 20:50:25'),
(16, 5, 'POP-BANYUMAS', 'pop-bms', 12, 'POP', '', 1, 4, '2026-02-20 20:50:25', '2026-02-20 20:50:25'),
(17, 9, 'POP-BREBES', 'pop-brebes', 12, 'POP', '', 1, 0, '2026-02-20 20:50:25', '2026-02-20 20:50:25'),
(18, 6, 'POP-CILACAP', 'pop-cilacap', 12, 'POP', '', 1, 3, '2026-02-20 20:50:25', '2026-02-20 20:50:25'),
(19, 10, 'POP-PEMALANG', 'pop-pemalang', 12, 'POP', '', 1, 0, '2026-02-20 20:50:25', '2026-02-20 20:50:25'),
(20, 7, 'POP-PUBALINGGA', 'pop-pubalingga', 12, 'POP', '', 1, 1, '2026-02-20 20:50:25', '2026-02-20 20:50:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `upstreams`
--

CREATE TABLE `upstreams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `peer_name` varchar(255) NOT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `asn` varchar(255) DEFAULT NULL,
  `capacity` varchar(255) DEFAULT NULL,
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Indeks untuk tabel `backbone_logs`
--
ALTER TABLE `backbone_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `backbone_logs_backbone_link_id_foreign` (`backbone_link_id`);

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
  ADD UNIQUE KEY `devices_netbox_id_unique` (`netbox_id`),
  ADD KEY `devices_location_id_foreign` (`location_id`),
  ADD KEY `devices_device_type_id_foreign` (`device_type_id`),
  ADD KEY `devices_site_id_foreign` (`site_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `locations_netbox_id_unique` (`netbox_id`),
  ADD KEY `locations_site_id_foreign` (`site_id`);

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
-- Indeks untuk tabel `netbox_sync_logs`
--
ALTER TABLE `netbox_sync_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `providers`
--
ALTER TABLE `providers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `providers_name_unique` (`name`);

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
-- Indeks untuk tabel `sites`
--
ALTER TABLE `sites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sites_netbox_id_unique` (`netbox_id`),
  ADD KEY `sites_site_group_id_foreign` (`site_group_id`);

--
-- Indeks untuk tabel `site_groups`
--
ALTER TABLE `site_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `site_groups_netbox_id_unique` (`netbox_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `backbone_links`
--
ALTER TABLE `backbone_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `backbone_logs`
--
ALTER TABLE `backbone_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT untuk tabel `customer_incidents`
--
ALTER TABLE `customer_incidents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `devices`
--
ALTER TABLE `devices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT untuk tabel `monthly_summaries`
--
ALTER TABLE `monthly_summaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `netbox_sync_logs`
--
ALTER TABLE `netbox_sync_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT untuk tabel `providers`
--
ALTER TABLE `providers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `service_logs`
--
ALTER TABLE `service_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `service_types`
--
ALTER TABLE `service_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `sites`
--
ALTER TABLE `sites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `site_groups`
--
ALTER TABLE `site_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
-- Ketidakleluasaan untuk tabel `backbone_logs`
--
ALTER TABLE `backbone_logs`
  ADD CONSTRAINT `backbone_logs_backbone_link_id_foreign` FOREIGN KEY (`backbone_link_id`) REFERENCES `backbone_links` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `devices_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `device_reports`
--
ALTER TABLE `device_reports`
  ADD CONSTRAINT `device_reports_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `locations`
--
ALTER TABLE `locations`
  ADD CONSTRAINT `locations_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL;

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
-- Ketidakleluasaan untuk tabel `sites`
--
ALTER TABLE `sites`
  ADD CONSTRAINT `sites_site_group_id_foreign` FOREIGN KEY (`site_group_id`) REFERENCES `site_groups` (`id`) ON DELETE SET NULL;

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
