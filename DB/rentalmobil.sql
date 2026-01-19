-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 19, 2026 at 06:19 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rentalmobil`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `rental_id` bigint UNSIGNED NOT NULL,
  `kode_invoice` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_tagihan` int NOT NULL,
  `metode_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_dibayar` int DEFAULT NULL,
  `bukti_bayar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','menunggu_verifikasi','lunas') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_bayar` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `rental_id`, `kode_invoice`, `total_tagihan`, `metode_pembayaran`, `jumlah_dibayar`, `bukti_bayar`, `status`, `tanggal_bayar`, `created_at`, `updated_at`) VALUES
(4, 17, 'INV-20260114032856', 400000, 'transfer', 400000, '1768361558_civic turbo.jpeg', 'lunas', '2026-01-13 21:11:08', '2026-01-13 20:28:56', '2026-01-13 21:11:08'),
(5, 18, 'INV-20260114042245', 2800000, 'transfer', 2800000, '1768364579_civic turbo.jpeg', 'lunas', '2026-01-13 21:23:20', '2026-01-13 21:22:45', '2026-01-13 21:23:20'),
(9, 22, 'INV-20260114050035', 200000, 'transfer', 200000, '1768366965_WhatsApp Image 2025-11-29 at 6.21.10 PM (1).jpeg', 'lunas', '2026-01-13 22:06:39', '2026-01-13 22:00:35', '2026-01-13 22:06:39'),
(11, 24, 'INV-20260114064705', 1000000, 'transfer', 1000000, '1768373237_civic turbo.jpeg', 'lunas', '2026-01-13 23:47:33', '2026-01-13 23:47:05', '2026-01-13 23:47:33'),
(16, 29, 'INV-20260114065920', 1000000, 'transfer', 1000000, '1768374008_civic turbo.jpeg', 'lunas', '2026-01-14 00:00:33', '2026-01-13 23:59:20', '2026-01-14 00:00:33'),
(17, 30, 'INV-20260114132602', 200000, 'transfer', 200000, '1768397924_ChatGPT Image Dec 2, 2025, 03_15_56 PM.png', 'lunas', '2026-01-14 06:39:11', '2026-01-14 06:26:02', '2026-01-14 06:39:11'),
(19, 32, 'INV-20260114140013', 1800000, 'transfer', 1800000, '1768399242_ChatGPT Image Dec 2, 2025, 03_15_56 PM.png', 'lunas', '2026-01-14 07:01:01', '2026-01-14 07:00:13', '2026-01-14 07:01:01'),
(20, 41, 'INV-20260114144952', 200000, 'transfer', 200000, '1768402227_4.jpg', 'lunas', '2026-01-14 07:50:37', '2026-01-14 07:49:52', '2026-01-14 07:50:37'),
(21, 42, 'INV-20260114152825', 1200000, 'office', 1200000, NULL, 'lunas', '2026-01-14 08:28:33', '2026-01-14 08:28:25', '2026-01-14 08:28:33'),
(22, 43, 'INV-20260115035617', 300000, 'transfer', 300000, '1768449406_ChatGPT Image Dec 2, 2025, 03_15_56 PM.png', 'lunas', '2026-01-14 20:57:14', '2026-01-14 20:56:17', '2026-01-14 20:57:14'),
(23, 44, 'INV-20260115035818', 100000, 'transfer', 100000, '1768449560_ChatGPT Image Dec 2, 2025, 03_15_56 PM.png', 'lunas', '2026-01-14 20:59:29', '2026-01-14 20:58:18', '2026-01-14 20:59:29');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `karyawans`
--

CREATE TABLE `karyawans` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `karyawans`
--

INSERT INTO `karyawans` (`id`, `nama`, `email`, `no_telp`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 'Kayla', 'kayla@gmail.com', '092872728922', 'Jl. Raya', '2026-01-04 05:44:04', '2026-01-04 05:44:04');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_25_133713_create_mobils_table', 1),
(5, '2025_11_26_112458_create_penyewas_table', 1),
(6, '2025_11_26_123609_create_rentals_table', 1),
(7, '2025_11_26_130648_create_pembayaran_table', 1),
(8, '2025_11_28_125239_create_pengembalians_table', 1),
(9, '2025_11_29_123030_create_karyawans_table', 1),
(10, '2025_12_08_135242_add_user_id_to_penyewa_table', 1),
(11, '2026_01_01_115751_create_ulasans_table', 1),
(12, '2026_01_13_140741_create_invoices_table', 2),
(13, '2026_01_15_084005_add_service_to_mobils_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `mobils`
--

CREATE TABLE `mobils` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_mobil` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `merk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plat_nomor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` int NOT NULL,
  `harga_sewa` int NOT NULL,
  `status` enum('tersedia','disewa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tersedia',
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tgl_servis_terakhir` date DEFAULT NULL,
  `interval_servis` int NOT NULL DEFAULT '3',
  `catatan_servis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mobils`
--

INSERT INTO `mobils` (`id`, `nama_mobil`, `merk`, `plat_nomor`, `tahun`, `harga_sewa`, `status`, `gambar`, `deskripsi`, `created_at`, `updated_at`, `tgl_servis_terakhir`, `interval_servis`, `catatan_servis`) VALUES
(1, 'Civic Turbo', 'Civic Turbo', 'MN 9090 B', 2020, 200000, 'disewa', 'mobil/9cB3QDUlUZarTKPii16Qsq2gwJbI45YFDGmXkvmV.jpg', NULL, '2026-01-04 05:43:10', '2026-01-15 23:15:42', '2026-01-16', 3, 'celap celup'),
(2, 'Honda Jazz', 'Toyota 123', 'VK 0987 A', 2021, 100000, 'tersedia', 'mobil/ZyLxB0fLKj39Bkqwe7R9bpylTU6sEfboAbsx74fc.jpg', 'Warna : putih\r\nKeadaan : Sangat baik\r\nKapasitas : 5 orang', '2026-01-04 18:11:48', '2026-01-15 23:24:56', '2026-01-16', 3, 'Ganti oli'),
(3, 'Avanza G', 'Toyota', 'S 1234 AB', 2021, 350000, 'tersedia', 'mobil/aMMoPnGJbID4SBkP6xjzLmRLvk3ITA1rXkk76VUc.jpg', 'Warna : hitam\r\nBahan bakar : turbo\r\nKapasitas : 4 orang', '2026-01-15 23:53:22', '2026-01-18 19:59:26', '2026-01-19', 6, 'Ganti sparepart, ganti oli, ganti mesin dalam'),
(4, 'Avanza Veloz', 'Toyota', 'AK 1V4 JA', 2022, 150000, 'tersedia', 'mobil/CngnnJT25Q1LgNvilNNO0VZF6JqeLfiId5Ry1tbQ.jpg', NULL, '2026-01-18 18:09:15', '2026-01-18 20:20:08', '2026-01-19', 3, 'Ganti oli, ganti sparepart');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` bigint UNSIGNED NOT NULL,
  `rental_id` bigint UNSIGNED NOT NULL,
  `total_harga` int NOT NULL,
  `dp` int NOT NULL DEFAULT '0',
  `sisa_bayar` int NOT NULL,
  `metode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengembalians`
--

CREATE TABLE `pengembalians` (
  `id` bigint UNSIGNED NOT NULL,
  `rental_id` bigint UNSIGNED NOT NULL,
  `tgl_kembali` date NOT NULL,
  `kondisi_mobil` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `denda` int NOT NULL DEFAULT '0',
  `total_bayar` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penyewas`
--

CREATE TABLE `penyewas` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `foto_ktp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penyewas`
--

INSERT INTO `penyewas` (`id`, `nama`, `email`, `no_telp`, `pekerjaan`, `alamat`, `foto_ktp`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'mikhaila', 'user@gmail.com', '09827357227', 'Wirausaha', 'Jl Tegger', '1768404505_civic turbo.jpeg', '2026-01-04 05:54:36', '2026-01-14 08:28:25', 1),
(2, 'Vika Anjani', 'user1@gmail.com', '9398373839', 'Owner Software House Kiri Aja', 'Jl raya', '1768449377_ChatGPT Image Dec 2, 2025, 03_15_56 PM.png', '2026-01-13 06:29:12', '2026-01-14 20:56:17', 3),
(3, 'Admin', 'admin@gmail.com', '0938383', 'Owner Software House Kiri Aja', 'Jl. raya', '1768449498_ChatGPT Image Dec 2, 2025, 03_15_56 PM.png', '2026-01-14 20:58:18', '2026-01-14 20:58:18', 4);

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

CREATE TABLE `rentals` (
  `id` bigint UNSIGNED NOT NULL,
  `penyewa_id` bigint UNSIGNED NOT NULL,
  `mobil_id` bigint UNSIGNED NOT NULL,
  `karyawan_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tgl_sewa` date NOT NULL,
  `tgl_kembali` date NOT NULL,
  `lama_sewa` int NOT NULL,
  `total_harga` decimal(12,2) NOT NULL,
  `dp` decimal(12,2) DEFAULT NULL,
  `sisa_bayar` decimal(12,2) DEFAULT NULL,
  `denda` decimal(12,2) DEFAULT '0.00',
  `status` enum('booking','disewa','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'booking',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rentals`
--

INSERT INTO `rentals` (`id`, `penyewa_id`, `mobil_id`, `karyawan_id`, `tgl_sewa`, `tgl_kembali`, `lama_sewa`, `total_harga`, `dp`, `sisa_bayar`, `denda`, `status`, `created_at`, `updated_at`) VALUES
(17, 1, 2, NULL, '2026-01-14', '2026-01-18', 4, 400000.00, 400000.00, 0.00, 0.00, 'selesai', '2026-01-13 20:28:56', '2026-01-13 21:13:22'),
(18, 1, 1, NULL, '2026-01-14', '2026-01-28', 14, 2800000.00, 2800000.00, 0.00, 0.00, 'selesai', '2026-01-13 21:22:45', '2026-01-13 21:23:47'),
(22, 2, 1, NULL, '2026-01-14', '2026-01-15', 1, 200000.00, 200000.00, 0.00, 0.00, 'selesai', '2026-01-13 22:00:35', '2026-01-13 23:33:15'),
(24, 1, 1, NULL, '2026-01-14', '2026-01-19', 5, 1000000.00, 1000000.00, 0.00, 0.00, 'selesai', '2026-01-13 23:47:05', '2026-01-13 23:48:32'),
(29, 1, 1, NULL, '2026-01-14', '2026-01-19', 5, 1000000.00, 1000000.00, 0.00, 0.00, 'selesai', '2026-01-13 23:59:20', '2026-01-14 06:55:25'),
(30, 2, 2, NULL, '2026-01-14', '2026-01-16', 2, 200000.00, 200000.00, 0.00, 0.00, 'selesai', '2026-01-14 06:26:02', '2026-01-14 06:59:53'),
(32, 2, 1, '1', '2026-01-14', '2026-01-23', 9, 1800000.00, 1800000.00, 0.00, 0.00, 'selesai', '2026-01-14 07:00:13', '2026-01-14 07:04:25'),
(41, 1, 1, '1', '2026-01-14', '2026-01-15', 1, 200000.00, 200000.00, 0.00, 0.00, 'selesai', '2026-01-14 07:49:52', '2026-01-14 08:26:21'),
(42, 1, 1, '1', '2026-01-14', '2026-01-20', 6, 1200000.00, 1200000.00, 0.00, 0.00, 'disewa', '2026-01-14 08:28:25', '2026-01-14 08:28:33'),
(43, 2, 2, '1', '2026-01-15', '2026-01-18', 3, 300000.00, 300000.00, 0.00, 0.00, 'selesai', '2026-01-14 20:56:17', '2026-01-14 20:57:48'),
(44, 3, 2, '1', '2026-01-15', '2026-01-16', 1, 100000.00, 100000.00, 0.00, 0.00, 'selesai', '2026-01-14 20:58:18', '2026-01-14 21:00:34');

-- --------------------------------------------------------

--
-- Table structure for table `ulasans`
--

CREATE TABLE `ulasans` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `mobil_id` bigint UNSIGNED DEFAULT NULL,
  `bintang` int NOT NULL,
  `komentar` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `balasan_admin` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ulasans`
--

INSERT INTO `ulasans` (`id`, `user_id`, `mobil_id`, `bintang`, `komentar`, `balasan_admin`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 5, 'Bagus', NULL, '2026-01-04 05:54:55', '2026-01-04 05:54:55'),
(2, 1, 2, 4, 'Pelayanan ramah', NULL, '2026-01-08 23:52:42', '2026-01-08 23:52:42'),
(3, 1, NULL, 4, 'Keren', NULL, '2026-01-14 03:16:01', '2026-01-14 03:16:01'),
(4, 3, NULL, 5, 'MURAHHHH BANGET, rekomended dehh', NULL, '2026-01-14 03:22:19', '2026-01-14 03:22:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'mikhaila', 'user@gmail.com', '$2y$12$eLIKI0EhV4YrAig2lnR3xeiu4Kt97SD22WWkO3G17K.jMh29ir2sK', 'user', NULL, NULL),
(3, 'Vika Anjani', 'user1@gmail.com', '$2y$12$8UeCWRATJa2tZzGIFBpvx.C02UTehWegdSaqfUqVu6LPVjNLVcJ/i', 'user', '2026-01-04 05:02:56', '2026-01-04 05:02:56'),
(4, 'Admin', 'admin@gmail.com', '$2y$12$NOZWBJbq6SH3msWokMn7CeulejeL5iLcLJWWq7V0wy7EyzjsasEve', 'admin', '2026-01-04 05:36:53', '2026-01-04 05:36:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_kode_invoice_unique` (`kode_invoice`),
  ADD KEY `invoices_rental_id_foreign` (`rental_id`);

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
-- Indexes for table `karyawans`
--
ALTER TABLE `karyawans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `karyawans_email_unique` (`email`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mobils`
--
ALTER TABLE `mobils`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pembayaran_rental_id_foreign` (`rental_id`);

--
-- Indexes for table `pengembalians`
--
ALTER TABLE `pengembalians`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengembalians_rental_id_foreign` (`rental_id`);

--
-- Indexes for table `penyewas`
--
ALTER TABLE `penyewas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `penyewas_email_unique` (`email`),
  ADD KEY `penyewas_user_id_foreign` (`user_id`);

--
-- Indexes for table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rentals_penyewa_id_foreign` (`penyewa_id`),
  ADD KEY `rentals_mobil_id_foreign` (`mobil_id`);

--
-- Indexes for table `ulasans`
--
ALTER TABLE `ulasans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ulasans_user_id_foreign` (`user_id`),
  ADD KEY `ulasans_mobil_id_foreign` (`mobil_id`);

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
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `karyawans`
--
ALTER TABLE `karyawans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `mobils`
--
ALTER TABLE `mobils`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pengembalians`
--
ALTER TABLE `pengembalians`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penyewas`
--
ALTER TABLE `penyewas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `ulasans`
--
ALTER TABLE `ulasans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_rental_id_foreign` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_rental_id_foreign` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pengembalians`
--
ALTER TABLE `pengembalians`
  ADD CONSTRAINT `pengembalians_rental_id_foreign` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penyewas`
--
ALTER TABLE `penyewas`
  ADD CONSTRAINT `penyewas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `rentals_mobil_id_foreign` FOREIGN KEY (`mobil_id`) REFERENCES `mobils` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rentals_penyewa_id_foreign` FOREIGN KEY (`penyewa_id`) REFERENCES `penyewas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ulasans`
--
ALTER TABLE `ulasans`
  ADD CONSTRAINT `ulasans_mobil_id_foreign` FOREIGN KEY (`mobil_id`) REFERENCES `mobils` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ulasans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
