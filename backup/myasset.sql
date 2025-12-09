-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2025 at 06:59 AM
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
-- Database: `myasset`
--

-- --------------------------------------------------------

--
-- Table structure for table `aset`
--

CREATE TABLE `aset` (
  `id_aset` int(10) UNSIGNED NOT NULL,
  `kode_aset` varchar(50) NOT NULL,
  `qr_token` varchar(64) NOT NULL,
  `id_master_aset` int(10) UNSIGNED NOT NULL,
  `id_kategori` int(10) UNSIGNED NOT NULL,
  `id_subkategori` int(10) UNSIGNED DEFAULT NULL,
  `id_cabang` int(10) UNSIGNED NOT NULL,
  `nilai_perolehan` decimal(18,2) DEFAULT NULL,
  `nilai_buku` decimal(18,2) NOT NULL DEFAULT 0.00,
  `periode_perolehan` date DEFAULT NULL,
  `stock` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `kondisi` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Digunakan',
  `posisi` varchar(120) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT 'no-image.png',
  `expired_at` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `aset`
--

INSERT INTO `aset` (`id_aset`, `kode_aset`, `qr_token`, `id_master_aset`, `id_kategori`, `id_subkategori`, `id_cabang`, `nilai_perolehan`, `nilai_buku`, `periode_perolehan`, `stock`, `kondisi`, `status`, `posisi`, `gambar`, `expired_at`, `keterangan`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
(48, 'FUR-2023-004', 'd44eaf18edbfc990277a5b6a41e6500a', 26, 5, 2, 1, 1000000.00, 0.00, '2025-01-01', 4, 'Baik', 'Digunakan', 'Gedung A', '1760943753_d7079c0b363303677443.png', NULL, 'sdda', '2025-10-20 07:02:33', '2025-11-18 07:32:34', '2025-11-18 07:32:34', 2, 2, 2),
(51, 'FUR-2024-006', '407e278565371e351e913eb013add721', 24, 5, 2, 2, 2000000.00, 0.00, '2024-06-01', 3, 'Rusak Ringan', 'Digunakan', 'Lt 3', '1762972236_768177fe70ba2362bb42.jpg', '2026-01-08', '', '2025-10-20 07:04:10', '2025-11-18 07:32:48', '2025-11-18 07:32:48', 2, 2, 2),
(52, 'FUR-2023-007', '1d67cf549fbfa591403d428ec58ac55e', 26, 5, 2, 2, 1000000.00, 0.00, '2025-01-01', 0, 'Baik', 'Digunakan', NULL, '1760943753_d7079c0b363303677443.png', NULL, 'sdda', '2025-10-20 07:04:47', '2025-10-20 07:06:57', '2025-10-20 07:06:57', 2, 2, NULL),
(53, 'FUR-2024-007', '90ac311c377430685db5fe545ac9930a', 24, 5, 2, 1, 2000000.00, 0.00, '2024-06-01', 4, 'Baik', 'Tidak Digunakan', 'Gedung A', '1760943850_cb7487f4048feb5076f4.jpg', '2026-01-08', '', '2025-10-20 07:05:54', '2025-11-18 07:32:52', '2025-11-18 07:32:52', 2, 2, 2),
(54, 'ELC-2023-004', '5a54cd87b85006fffcdf30a888552228', 27, 6, 1, 2, 7500000.00, 7343750.00, '2023-06-01', 1, 'Baik', 'Tidak Digunakan', 'Lt 33', '1764057372_dc47091e31c96db9d1e5.jpg', '2024-06-11', '', '2025-10-20 08:37:40', '2025-12-09 12:39:38', NULL, 2, 2, NULL),
(55, 'ELC-2023-005', '7e29efce40ee62da602903edbed4f35d', 27, 6, 1, 1, 7500000.00, 0.00, '2023-06-01', 3, 'Baik', 'Digunakan', '', '1760980649_15e012cc28590ddaf448.jpg', '2024-06-11', '', '2025-10-20 08:42:24', '2025-11-18 07:33:01', '2025-11-18 07:33:01', 2, 2, 2),
(59, 'FUR-2023-008', '55662fa91f8f006f7a148009277ad29c', 31, 5, 2, 1, 5000000.00, 0.00, '2023-06-01', 1, 'Baik', 'Tidak Digunakan', 'Gedung B', NULL, NULL, '', '2025-10-20 18:25:55', '2025-10-20 18:26:40', '2025-10-20 18:26:40', 2, 2, 2),
(60, 'ELC-2024-001', '2d92734bc5bd5895eb16d3e7cc0dcce0', 32, 6, 1, 2, 5000.00, 0.00, '2024-06-01', 1, 'Rusak Berat', 'Hilang', '', NULL, '2026-02-17', '', '2025-10-20 18:31:36', '2025-10-20 18:31:43', '2025-10-20 18:31:43', 2, 2, 2),
(63, 'FUR-2023-009', '2359cdc3cf8e1519920e73dd251751b2', 26, 5, 2, 4, 1000000.00, 0.00, '2025-01-01', 1, 'Rusak Ringan', 'Tidak Digunakan', 'Gedung A', '1761626280_17a49db2279a15f0321e.jpg', NULL, '', '2025-10-28 04:38:00', '2025-11-18 07:33:04', '2025-11-18 07:33:04', 2, 2, 2),
(64, 'ELC-2025-001', 'a9845bcaaf6e8f5e34e251713951da9d', 34, 6, 1, 4, 15000000.00, 0.00, '2025-01-01', 1, 'Baik', 'Digunakan', 'Gedung A', '1761628291_553bd8dbd711cca2ab18.jpg', '2027-06-09', '', '2025-10-28 05:11:31', '2025-11-18 07:33:25', '2025-11-18 07:33:25', 2, 2, 2),
(65, 'ELC-2023-010', '5b6b8dc8439fbab37a85f68a3b93f9b0', 35, 6, 1, 4, 2000000.00, 0.00, '2023-02-01', 3, 'Rusak Berat', 'Hilang', 'Lt 3', '1762972528_edd8fa242309bab449b5.jpg', '2027-06-09', '', '2025-11-04 08:51:05', '2025-11-18 07:32:43', '2025-11-18 07:32:43', 2, 2, 2),
(66, 'ELC-2023-011', '53fbb6128d8d23c97fa75497c4ae97f0', 35, 6, 1, 2, 2000000.00, 0.00, '2023-02-01', 1, 'Rusak Ringan', 'Digunakan', 'Lt 3', '1762935860_b85a3c2a68c2a9ae3292.jpg', '2027-06-09', '', '2025-11-12 08:24:20', '2025-11-18 07:32:37', '2025-11-18 07:32:37', 2, 2, 2),
(67, 'ELC-2023-012', 'c39c00df586b4dfdbf8be605d4905c7c', 37, 6, 1, 4, 5000000.00, 0.00, '2023-06-01', 0, 'Rusak Ringan', 'Tidak Digunakan', 'Lt 3', '1762947188_1213196d044467dcbd51.jpg', NULL, '', '2025-11-12 11:33:08', '2025-11-18 12:26:36', '2025-11-18 12:26:36', 2, 2, NULL),
(68, 'ELC-2025-002', 'cfdafcbcf055d8b4b16c9d62f4719166', 34, 6, 1, 1, 15000000.00, 0.00, '2025-01-01', 2, 'Rusak Ringan', 'Digunakan', 'Lt 33', '1762950780_4cda5e6303713d31c74a.jpg', '2027-06-09', '', '2025-11-12 12:23:11', '2025-11-18 07:33:13', '2025-11-18 07:33:13', 2, 2, 2),
(69, 'FUR-2024-008', '30eabcb7e899bb966478fe64efe0af9d', 36, 5, 2, 4, 3000000.00, 0.00, '2024-05-01', 1, 'Rusak Ringan', 'Tidak Digunakan', 'df', NULL, NULL, '', '2025-11-12 12:27:23', '2025-11-12 12:33:44', '2025-11-12 12:33:44', 2, 2, 2),
(70, 'ELC-2025-003', 'cfa95531862c72232704c67f95ab5444', 38, 6, 1, 2, 50000.00, 0.00, '2025-11-01', 1, 'Rusak Ringan', 'Tidak Digunakan', 'Lt 3', '1762951489_b82318e57fae9f87c9e0.jpg', NULL, 'www', '2025-11-12 12:44:49', '2025-12-02 18:39:02', NULL, 2, 2, NULL),
(71, 'ELC-2025-004', '5477f2f9a725097df1edfd4f49329913', 38, 6, 1, 1, 50000.00, 0.00, '2025-11-01', 1, 'Baik', 'Digunakan', 'Lt 33', '1762951562_f99c26f13fcb48742fd6.jpg', NULL, 'ss', '2025-11-12 12:46:02', '2025-11-18 07:33:35', '2025-11-18 07:33:35', 2, 2, 2),
(72, 'ELC-2025-005', 'f0c662318fdf719da870664a4ca92be4', 38, 6, 1, 4, 50000.00, 0.00, '2025-11-01', 1, 'Baik', 'Digunakan', 'Lt 33', '1762951598_53c71b838a70ac6109ef.jpg', NULL, 'sf', '2025-11-12 12:46:38', '2025-11-18 07:33:21', '2025-11-18 07:33:21', 2, 2, 2),
(73, 'ELC-2023-013', 'd846bc5a368b9b89f3f216b11731c5f0', 37, 6, 1, 2, 5000000.00, 4895833.33, '2023-06-01', 1, 'Baik', 'Digunakan', '', '1762972596_c89e3dc8b0bdfd306fc5.jpg', NULL, 'hdg', '2025-11-12 17:27:39', '2025-12-09 12:39:38', NULL, 2, 2, NULL),
(74, 'FUR-2024-009', '6646bf12950ee0f1bfd752db7932faa7', 39, 5, 2, 2, 5000000.00, 4895833.33, '2024-06-01', 1, 'Baik', 'Digunakan', 'Lt 3', '1763033169_1d1514b02dcc33f58f28.jpg', NULL, 'Kondisi Baru', '2025-11-13 11:26:09', '2025-12-09 12:39:38', NULL, 2, 2, NULL),
(75, 'FUR-2024-010', '3f333919ee7982f151628f867bb6bbd2', 39, 5, 2, 4, 5000000.00, 0.00, '2024-06-01', 0, 'Baik', 'Digunakan', 'Lt 33', '1763036948_b4cadfdb6ea27741a5ec.jpg', NULL, '', '2025-11-13 12:29:09', '2025-12-02 19:14:38', '2025-12-02 19:14:38', 2, 2, 2),
(76, 'FUR-2024-011', 'a5f68624cf96cd5375bf8a435ffff923', 39, 5, 2, 1, 5000000.00, 4895833.33, '2024-06-01', 1, 'Baik', 'Digunakan', 'Lt 3', '1763037048_cb8a60cdb20440de6cf0.jpg', NULL, '', '2025-11-13 12:30:04', '2025-12-09 12:39:38', NULL, 2, 2, NULL),
(77, 'ELC-2022-002', '0f26ba462ae1dd801d0a2a062b30c611', 40, 6, 1, 2, 15000000.00, 0.00, '2022-06-01', 1, 'Baik', 'Digunakan', 'Lt 31', '1763038828_0e569466849570d7b019.jpg', NULL, '', '2025-11-13 13:00:28', '2025-11-18 07:32:40', '2025-11-18 07:32:40', 2, 2, 2),
(78, 'ELC-2023-014', 'b5622de91f806aa8aed73d40121284d0', 37, 6, 1, 1, 5000000.00, 4895833.33, '2023-06-01', 1, 'Baik', 'Tidak Digunakan', 'Lt 3', '1762972596_c89e3dc8b0bdfd306fc5.jpg', NULL, 'hdg', '2025-11-18 12:12:53', '2025-12-09 12:39:38', NULL, 2, 2, NULL),
(79, 'ELC-2025-006', '21b55f9558ecdc438790cb0cfd9c0ad5', 41, 6, 1, 1, 12000000.00, 0.00, '2025-02-01', 0, 'Baik', 'Digunakan', 'Gedung A', '1763555545_a5b7c473ef04723184a1.jpg', NULL, NULL, '2025-11-19 12:32:26', '2025-11-19 12:38:28', '2025-11-19 12:38:28', 2, 2, NULL),
(80, 'ELC-2025-007', '3073afa3f041f6be5147c54b7647a078', 41, 6, 1, 4, 12000000.00, 0.00, '2025-02-01', 0, 'Baik', 'Digunakan', NULL, '1763555545_a5b7c473ef04723184a1.jpg', NULL, NULL, '2025-11-19 12:34:25', '2025-12-02 19:14:38', '2025-12-02 19:14:38', 2, 2, 2),
(81, 'ELC-2025-008', 'fbd8ff74555e29dc25f2b92b83d22fa5', 41, 6, 1, 2, 12000000.00, 11750000.00, '2025-02-01', 2, 'Rusak Ringan', 'Digunakan', 'Lt 3', '1763555545_a5b7c473ef04723184a1.jpg', NULL, NULL, '2025-11-19 12:38:28', '2025-12-09 12:39:38', NULL, 2, 2, NULL),
(82, 'FUR-2025-002', '55eeb121d0624e2aa8772e450b5e0f7b', 25, 5, 2, 1, 54353463.00, 0.00, '2025-07-01', 0, 'Baik', 'Tidak Digunakan', 'Lt 3', '1763571830_7f1e4c18e941748a76af.jpg', NULL, NULL, '2025-11-19 17:03:50', '2025-11-19 17:08:55', '2025-11-19 17:08:55', 2, 2, NULL),
(83, 'FUR-2023-010', 'dc2c2d13ce09e74a14537baf3c662d90', 42, 5, 2, 1, 15000000.00, 14687500.00, '2023-06-01', 1, 'Baik', 'Digunakan', 'Lt 33', '1763572076_2b9827d20c0434312833.jpg', NULL, NULL, '2025-11-19 17:07:56', '2025-12-09 12:39:38', NULL, 2, 2, NULL),
(84, 'FUR-2025-003', '9379c5a5df91cd72e9c82eba25107c82', 25, 5, 2, 2, 54353463.00, 53221099.19, '2025-07-01', 1, 'Baik', 'Tidak Digunakan', NULL, '1763571830_7f1e4c18e941748a76af.jpg', NULL, NULL, '2025-11-19 17:08:55', '2025-12-09 12:39:38', NULL, 2, 2, NULL),
(86, 'COM-2024-001', 'cde775915d47f05e4bf0587eee1fcac5', 43, 3, 8, 1, 20000000.00, 0.00, '2024-05-01', 0, 'Baik', 'Digunakan', 'Lt 3', '1763574153_b90f2588c065d39e3618.png', NULL, NULL, '2025-11-19 17:42:37', '2025-11-19 17:43:50', '2025-11-19 17:43:50', 2, 2, NULL),
(87, 'COM-2024-002', '483ff696a289bc4199e6a57acdd0e0e3', 43, 3, 8, 2, 20000000.00, 19583333.33, '2024-05-01', 1, 'Baik', 'Digunakan', NULL, '1763574153_b90f2588c065d39e3618.png', NULL, NULL, '2025-11-19 17:43:50', '2025-12-09 12:39:38', NULL, 2, 2, NULL),
(88, 'FUR-2024-012', 'cbd62aef7d6a33acafe3ad2f27981e51', 44, 5, 2, 1, 2000000.00, 1958333.33, '2024-06-01', 2, 'Baik', 'Digunakan', 'Lt 33', '1764148860_bbcdfa842e5e6c216e8c.jpg', '2026-03-04', NULL, '2025-11-26 09:21:00', '2025-12-09 12:39:38', NULL, 2, 2, NULL),
(89, 'FUR-2024-014', '501eac6e31d55bf2d04074b1d083ff4c', 44, 5, 2, 2, 2000000.00, 1958333.33, '2024-06-01', 1, 'Baik', 'Digunakan', 'Lt 33', '1764154029_a5acdbbff00c616a615a.jpg', '2026-03-04', NULL, '2025-11-26 10:47:09', '2025-12-09 12:39:38', NULL, 2, 2, NULL),
(90, 'FUR-2023-011', 'c2419570b46432093640d362e3bd6e75', 45, 5, 2, 2, 5000000.00, 4895833.33, '2023-06-01', 1, 'Rusak Ringan', 'Tidak Digunakan', 'Lt 3', '1764177817_000ddcad547d38d295aa.jpg', NULL, NULL, '2025-11-26 17:23:38', '2025-12-09 12:39:38', NULL, 2, 2, NULL),
(91, 'ELC-2023-016', 'a49491cc84ef8e118c21d0eb0bfefbea', 46, 6, 1, 2, 15000000.00, 0.00, '2023-06-01', 0, 'Rusak Ringan', 'Dimutasi', 'Lt 3', '1764177847_8c879ed4b1934dae4e41.jpg', '2026-04-16', NULL, '2025-11-26 17:24:08', '2025-11-26 18:08:09', '2025-11-26 18:08:09', 2, 2, 2),
(92, 'ELC-2025-009', 'cf352d7ecd17a71090a15b1c12431cd6', 47, 6, 1, 2, 5000000.00, 4895833.33, '2025-07-01', 2, 'Baik', 'Tidak Digunakan', 'Lt 3', '1764181523_7e60f83a8c6bd4f17ca4.jpg', '2026-02-19', '', '2025-11-26 18:25:23', '2025-12-09 12:39:38', NULL, 2, 2, NULL),
(93, 'FUR-2023-012', '3d306a42347521858418fd0690befc3d', 48, 5, 2, 2, 5000000.00, 4895833.33, '2023-10-01', 1, 'Baik', 'Hilang', 'Gedung A', '1764181553_87be727372d31f355313.jpg', '2026-03-03', '', '2025-11-26 18:25:53', '2025-12-09 12:39:38', NULL, 2, 2, NULL),
(94, 'ELC-2025-010', '636671fb2843cc5ebc9cd7d3ba208a36', 47, 6, 1, 1, 5000000.00, 0.00, '2025-07-01', 0, 'Baik', 'Dimutasi', 'Lt 3', 'no-image.png', '2026-02-19', NULL, '2025-11-26 18:31:35', '2025-11-26 18:32:54', '2025-11-26 18:32:54', 2, 2, 2),
(95, 'KND-2023-001', '300bb8d916fac80bf0808d1a7ace48f9', 49, 13, 9, 2, 150000000.00, 148437500.00, '2023-02-01', 1, 'Baik', 'Tidak Digunakan', '', '1764650270_28c3f9a15456d7424abb.jpg', NULL, '', '2025-12-02 11:37:50', '2025-12-09 12:39:38', NULL, 2, 2, NULL),
(96, 'FUR-2024-015', 'd7d49734979267f92d6085ea2a610d0d', 36, 5, 2, 2, 3000000.00, 0.00, '2024-05-01', 1, 'Rusak Ringan', 'Digunakan', '', 'no-image.png', NULL, NULL, '2025-12-02 18:38:35', '2025-12-02 18:38:35', NULL, 2, 2, NULL),
(97, 'KND-2023-002', '33ee892ec3552a9a2d408d3eef1aa1f4', 49, 13, 9, 4, 150000000.00, 148437500.00, '2023-02-01', 1, 'Baik', 'Digunakan', 'Lt 3', '1764678313_804d27bb88343f52d4c9.jpg', NULL, NULL, '2025-12-02 19:25:13', '2025-12-09 12:39:38', NULL, 2, 2, NULL),
(98, 'KND-2025-001', '76c2b8defad15886b4b513ea58e8d082', 50, 13, 9, 4, 520000000.00, 0.00, '2025-12-01', 1, 'Baik', 'Digunakan', 'Lt 3', '1765259131_01d191253b5797b9dc6d.jpg', NULL, 'rrt', '2025-12-09 12:45:32', '2025-12-09 12:54:39', NULL, 2, 2, NULL),
(99, 'KND-2024-001', 'ffd53827789eae19e9b7af84cc9ded67', 51, 13, 9, 4, 92000000.00, 0.00, '2024-01-01', 1, 'Baik', 'Digunakan', 'Basement P1', '1765259606_681a3094bdae72c9bc4c.jpg', NULL, 'Beli Second', '2025-12-09 12:53:26', '2025-12-09 12:53:26', NULL, 2, 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `aset_atribut`
--

CREATE TABLE `aset_atribut` (
  `id_aset` int(10) UNSIGNED NOT NULL,
  `id_atribut` int(10) UNSIGNED NOT NULL,
  `nilai` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aset_atribut`
--

INSERT INTO `aset_atribut` (`id_aset`, `id_atribut`, `nilai`) VALUES
(48, 8, '54'),
(48, 9, '423'),
(48, 10, '24542'),
(48, 11, 'Besi'),
(51, 8, '23'),
(51, 9, '245'),
(51, 10, '453'),
(51, 11, 'Besi'),
(54, 2, '14-fc0888TU'),
(54, 3, '16'),
(54, 4, '256'),
(54, 6, 'Baik'),
(54, 17, 'Hp'),
(54, 19, '14'),
(63, 8, '54'),
(63, 9, '423'),
(63, 10, '24542'),
(63, 11, 'Besi'),
(64, 2, 'dg3456'),
(64, 3, '16'),
(64, 4, '256'),
(64, 6, 'Tidak ada Baterai'),
(64, 17, 'Asuz'),
(64, 19, '14'),
(65, 2, 'i124we'),
(65, 3, '8'),
(65, 4, '512'),
(65, 6, 'Tidak ada Baterai'),
(65, 17, 'Hp'),
(65, 19, '14'),
(66, 2, 'i124we'),
(66, 3, '8'),
(66, 4, '512'),
(66, 6, 'Tidak ada Baterai'),
(66, 17, 'Hp'),
(66, 19, '14'),
(68, 2, 'dg3456'),
(68, 3, '16'),
(68, 4, '256'),
(68, 6, 'Tidak ada Baterai'),
(68, 17, 'Asuz'),
(68, 19, '14'),
(73, 2, 'irte'),
(73, 3, '6'),
(73, 4, '256'),
(73, 6, 'Tidak ada Baterai'),
(73, 17, 'Hp'),
(73, 19, '14'),
(74, 8, '56'),
(74, 9, '43'),
(74, 10, '434'),
(74, 11, 'kayu'),
(75, 8, '56'),
(75, 9, '43'),
(75, 10, '434'),
(75, 11, 'kayu'),
(76, 8, '56'),
(76, 9, '43'),
(76, 10, '434'),
(76, 11, 'kayu'),
(77, 2, 'iidsaf4'),
(77, 3, '8'),
(77, 4, '512'),
(77, 6, 'Sedang'),
(77, 17, 'Hp'),
(77, 19, '14'),
(78, 2, 'irte'),
(78, 3, '6'),
(78, 4, '256'),
(78, 6, 'Tidak ada Baterai'),
(78, 17, 'Hp'),
(78, 19, '14'),
(79, 2, 'i124we'),
(79, 3, '16'),
(79, 4, '512'),
(79, 6, 'Baik'),
(79, 17, 'Samsung'),
(79, 19, '16'),
(80, 2, 'i124we'),
(80, 3, '16'),
(80, 4, '512'),
(80, 6, 'Baik'),
(80, 17, 'Samsung'),
(80, 19, '16'),
(81, 2, 'i124we'),
(81, 3, '16'),
(81, 4, '512'),
(81, 6, 'Baik'),
(81, 17, 'Samsung'),
(81, 19, '16'),
(82, 8, '34'),
(82, 9, '534'),
(82, 10, '435'),
(82, 11, 'kayu'),
(84, 8, '34'),
(84, 9, '534'),
(84, 10, '435'),
(84, 11, 'kayu'),
(86, 20, '12'),
(86, 21, '56'),
(86, 22, 'Indorak'),
(87, 20, '50'),
(87, 21, '50'),
(87, 22, 'Indodak'),
(88, 8, '23'),
(88, 9, '32'),
(88, 10, '32'),
(88, 11, 'kayu'),
(89, 8, '23'),
(89, 9, '32'),
(89, 10, '32'),
(89, 11, 'kayu'),
(90, 8, '34'),
(90, 9, '342'),
(90, 10, '234'),
(90, 11, 'kayu'),
(91, 2, 'i124we'),
(91, 3, '12'),
(91, 4, '254'),
(91, 6, 'Baik'),
(91, 17, 'Samsung'),
(91, 19, '41'),
(92, 2, 'i124we'),
(92, 3, '8'),
(92, 4, '256'),
(92, 6, 'Tidak ada Baterai'),
(92, 17, 'Hp'),
(92, 19, '14'),
(93, 8, '12'),
(93, 9, '12'),
(93, 10, '12'),
(93, 11, 'Besi'),
(94, 2, 'i124we'),
(94, 3, '8'),
(94, 4, '256'),
(94, 6, 'Tidak ada Baterai'),
(94, 17, 'Hp'),
(94, 19, '14'),
(95, 23, 'Avanza'),
(95, 26, '2017'),
(95, 27, '123'),
(95, 28, '456'),
(97, 23, 'Avanza'),
(97, 26, '2017'),
(97, 27, '123'),
(97, 28, '456'),
(98, 23, 'Honda CRV'),
(98, 26, '2021'),
(98, 27, '123456'),
(98, 28, '123445678'),
(99, 23, 'Avanza'),
(99, 26, '2016'),
(99, 27, '1234567'),
(99, 28, '1234567');

-- --------------------------------------------------------

--
-- Table structure for table `aset_counter`
--

CREATE TABLE `aset_counter` (
  `id_kategori` int(10) UNSIGNED NOT NULL,
  `tahun` int(11) NOT NULL,
  `last_no` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aset_counter`
--

INSERT INTO `aset_counter` (`id_kategori`, `tahun`, `last_no`) VALUES
(3, 2024, 2),
(5, 2023, 12),
(5, 2024, 15),
(5, 2025, 3),
(6, 2022, 2),
(6, 2023, 16),
(6, 2024, 1),
(6, 2025, 10),
(11, 2023, 1),
(13, 2023, 2),
(13, 2024, 1),
(13, 2025, 1);

-- --------------------------------------------------------

--
-- Table structure for table `atribut_aset`
--

CREATE TABLE `atribut_aset` (
  `id_atribut` int(10) UNSIGNED NOT NULL,
  `id_subkategori` int(10) UNSIGNED NOT NULL,
  `nama_atribut` varchar(100) NOT NULL,
  `kode_atribut` varchar(50) DEFAULT NULL,
  `tipe_input` enum('text','number','date','select','textarea') NOT NULL DEFAULT 'text',
  `satuan` varchar(20) DEFAULT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `options_json` text DEFAULT NULL,
  `urutan` int(5) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `atribut_aset`
--

INSERT INTO `atribut_aset` (`id_atribut`, `id_subkategori`, `nama_atribut`, `kode_atribut`, `tipe_input`, `satuan`, `is_required`, `options_json`, `urutan`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 1, 'Tipe', 'tipe', 'text', NULL, 1, NULL, 2, '2025-09-13 08:24:49', NULL, NULL),
(3, 1, 'RAM (GB)', 'ram', 'number', 'GB', 1, NULL, 3, '2025-09-13 08:24:49', NULL, NULL),
(4, 1, 'SSD (GB)', 'ssd', 'number', 'GB', 0, NULL, 4, '2025-09-13 08:24:49', NULL, NULL),
(6, 1, 'Kondisi Baterai', 'baterai', 'select', NULL, 0, '[\"Baik\",\"Sedang\",\"Tidak ada Baterai\"]', 6, '2025-09-13 08:24:49', '2025-10-08 07:51:53', NULL),
(8, 2, 'Panjang', 'Panjang', 'number', NULL, 1, NULL, 1, '2025-09-13 09:08:23', '2025-09-13 09:08:23', NULL),
(9, 2, 'Lebar', 'Lebar', 'number', NULL, 1, NULL, 2, '2025-09-13 09:09:10', '2025-09-13 09:09:10', NULL),
(10, 2, 'Tinggi', 'Tinggi', 'number', NULL, 1, NULL, 3, '2025-09-13 09:09:28', '2025-09-13 09:09:28', NULL),
(11, 2, 'Bahan', 'Bahan', 'text', NULL, 1, NULL, 4, '2025-09-13 09:09:50', '2025-09-13 09:09:50', NULL),
(13, 4, 'Panjang', NULL, 'number', 'Meter', 1, NULL, 0, '2025-10-07 06:09:58', '2025-10-07 06:09:58', NULL),
(14, 4, 'Lebar', NULL, 'number', 'Lebar', 1, NULL, 1, '2025-10-07 06:10:27', '2025-10-07 06:10:27', NULL),
(17, 1, 'Merek', NULL, 'text', NULL, 1, NULL, 1, '2025-10-27 06:30:20', '2025-10-27 06:30:20', NULL),
(18, 7, 'Panjang', NULL, 'number', NULL, 1, NULL, 1, '2025-10-28 04:52:05', '2025-10-28 04:52:05', NULL),
(19, 1, 'Layar', NULL, 'number', 'in', 1, NULL, 0, '2025-10-28 05:09:05', '2025-10-28 05:09:05', NULL),
(20, 8, 'Tinggi', NULL, 'number', 'cm', 1, NULL, 0, '2025-11-19 17:39:43', '2025-11-19 17:39:43', NULL),
(21, 8, 'Lebar', NULL, 'number', 'cm', 1, NULL, 1, '2025-11-19 17:40:10', '2025-11-19 17:40:10', NULL),
(22, 8, 'Merek', NULL, 'text', NULL, 1, NULL, 0, '2025-11-19 17:40:32', '2025-11-19 17:40:32', NULL),
(23, 9, 'Merek', NULL, 'text', NULL, 1, NULL, 0, '2025-12-02 11:29:20', '2025-12-02 11:29:20', NULL),
(25, 9, 'Jenis1', NULL, 'text', NULL, 1, NULL, 2, '2025-12-02 11:30:24', '2025-12-02 11:59:01', '2025-12-02 11:59:01'),
(26, 9, 'Tahun', NULL, 'number', NULL, 1, NULL, 4, '2025-12-02 11:31:13', '2025-12-02 11:31:13', NULL),
(27, 9, 'No Mesin', NULL, 'number', NULL, 1, NULL, 0, '2025-12-02 11:32:47', '2025-12-02 11:32:47', NULL),
(28, 9, 'No Rangka', NULL, 'number', NULL, 1, NULL, 0, '2025-12-02 11:33:01', '2025-12-02 11:33:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `auth_activation_attempts`
--

CREATE TABLE `auth_activation_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups`
--

CREATE TABLE `auth_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_groups`
--

INSERT INTO `auth_groups` (`id`, `name`, `description`) VALUES
(1, 'superadmin', 'Semua Cabang'),
(2, 'admin', 'Admin Cabang'),
(3, 'user', 'User Cabang');

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups_permissions`
--

CREATE TABLE `auth_groups_permissions` (
  `group_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `permission_id` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups_users`
--

CREATE TABLE `auth_groups_users` (
  `group_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_groups_users`
--

INSERT INTO `auth_groups_users` (`group_id`, `user_id`) VALUES
(1, 2),
(2, 3),
(2, 4),
(2, 7),
(2, 8),
(2, 9);

-- --------------------------------------------------------

--
-- Table structure for table `auth_logins`
--

CREATE TABLE `auth_logins` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_logins`
--

INSERT INTO `auth_logins` (`id`, `ip_address`, `email`, `user_id`, `date`, `success`) VALUES
(1, '::1', 'ewGFSDFSD', NULL, '2025-09-02 07:28:00', 0),
(2, '::1', 'Administrator', NULL, '2025-09-02 07:38:47', 0),
(3, '::1', 'Administrator', NULL, '2025-09-02 07:40:05', 0),
(4, '::1', 'Administrator', NULL, '2025-09-02 07:57:50', 0),
(5, '::1', 'Administrator', NULL, '2025-09-02 08:06:17', 0),
(6, '::1', 'Administrator', NULL, '2025-09-02 08:07:20', 0),
(7, '::1', 'budiagungofficial@gmail.com', NULL, '2025-09-02 08:13:52', 0),
(8, '::1', 'budiagungofficial@gmail.com', NULL, '2025-09-02 08:23:16', 0),
(9, '::1', 'budiagungofficial@gmail.com', NULL, '2025-09-02 08:24:47', 0),
(10, '::1', 'budiagungofficial@gmail.com', NULL, '2025-09-02 08:27:28', 0),
(11, '::1', 'Administrator', NULL, '2025-09-02 08:33:04', 0),
(12, '::1', 'Administrator', NULL, '2025-09-02 08:38:10', 0),
(13, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-02 08:58:47', 1),
(14, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-02 11:56:58', 1),
(15, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-03 12:15:16', 1),
(16, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-04 11:32:19', 1),
(17, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-04 11:50:42', 1),
(18, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-04 11:54:15', 1),
(19, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-05 06:16:54', 1),
(20, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-05 06:16:55', 1),
(21, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-06 14:15:32', 1),
(22, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-06 14:22:02', 1),
(23, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-07 06:29:06', 1),
(24, '::1', 'cbg001', NULL, '2025-09-07 07:08:04', 0),
(25, '::1', 'cbg001', NULL, '2025-09-07 07:08:14', 0),
(26, '::1', 'cbg001', NULL, '2025-09-07 07:08:37', 0),
(27, '::1', 'dewicbg001@gmail.com', NULL, '2025-09-07 07:09:17', 0),
(28, '::1', 'dewicbg001@gmail.com', NULL, '2025-09-07 07:09:26', 0),
(29, '::1', 'dewicbg001@gmail.com', NULL, '2025-09-07 07:09:34', 0),
(30, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-07 07:10:04', 1),
(31, '::1', 'dewicbg001@gmail.com', 3, '2025-09-07 07:11:00', 1),
(32, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-07 08:15:21', 1),
(33, '::1', 'agungcbg002@gmail.com', 4, '2025-09-07 08:19:04', 1),
(34, '::1', 'dewicbg001@gmail.com', 3, '2025-09-07 08:21:00', 1),
(35, '::1', 'dewicbg001@gmail.com', 3, '2025-09-07 14:40:38', 1),
(36, '::1', 'Administrator', NULL, '2025-09-07 15:36:22', 0),
(37, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-07 15:36:30', 1),
(38, '::1', 'dewicbg001@gmail.com', 3, '2025-09-07 15:48:48', 1),
(39, '::1', 'agungcbg002@gmail.com', 4, '2025-09-07 16:17:43', 1),
(40, '::1', 'dewicbg001@gmail.com', 3, '2025-09-07 16:19:03', 1),
(41, '::1', 'dewicbg001@gmail.com', 3, '2025-09-07 16:19:06', 1),
(42, '::1', 'agungcbg002@gmail.com', 4, '2025-09-07 16:20:12', 1),
(43, '::1', 'cbg001', NULL, '2025-09-07 16:22:15', 0),
(44, '::1', 'dewicbg001@gmail.com', 3, '2025-09-07 16:22:23', 1),
(45, '::1', 'agungcbg002@gmail.com', 4, '2025-09-07 16:23:39', 1),
(46, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-08 06:11:51', 1),
(47, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-08 06:11:52', 1),
(48, '::1', 'dewicbg001@gmail.com', 3, '2025-09-08 11:55:20', 1),
(49, '::1', 'agungcbg002@gmail.com', 4, '2025-09-08 12:12:16', 1),
(50, '::1', 'dewicbg001@gmail.com', 3, '2025-09-08 12:13:52', 1),
(51, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-09 03:42:55', 1),
(52, '::1', 'dewicbg001@gmail.com', 3, '2025-09-09 03:44:47', 1),
(53, '::1', 'agungcbg002@gmail.com', 4, '2025-09-09 04:43:03', 1),
(54, '::1', 'dewicbg001@gmail.com', 3, '2025-09-09 07:59:56', 1),
(55, '::1', 'dewicbg001@gmail.com', 3, '2025-09-09 11:17:24', 1),
(56, '::1', 'agungcbg002@gmail.com', 4, '2025-09-09 11:56:03', 1),
(57, '::1', 'dewicbg001@gmail.com', 3, '2025-09-10 05:21:21', 1),
(58, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-10 05:47:20', 1),
(59, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-10 12:46:57', 1),
(60, '::1', 'dewicbg001@gmail.com', 3, '2025-09-10 12:53:00', 1),
(61, '::1', 'dewicbg001@gmail.com', 3, '2025-09-12 07:41:16', 1),
(62, '::1', 'cbg001', NULL, '2025-09-12 11:17:47', 0),
(63, '::1', 'dewicbg001@gmail.com', 3, '2025-09-12 11:17:55', 1),
(64, '::1', 'cbg001', NULL, '2025-09-12 11:19:14', 0),
(65, '::1', 'dewicbg001@gmail.com', 3, '2025-09-12 11:28:33', 1),
(66, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-12 13:03:26', 1),
(67, '::1', 'dewicbg001@gmail.com', 3, '2025-09-12 17:37:13', 1),
(68, '::1', 'dewicbg001@gmail.com', 3, '2025-09-13 06:56:43', 1),
(69, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-13 07:04:43', 1),
(70, '::1', 'dewicbg001@gmail.com', 3, '2025-09-13 07:21:40', 1),
(71, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-13 07:32:23', 1),
(72, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-13 07:32:26', 1),
(73, '::1', 'dewicbg001@gmail.com', 3, '2025-09-13 07:38:39', 1),
(74, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-13 08:14:20', 1),
(75, '::1', 'Administrator', NULL, '2025-09-13 09:23:05', 0),
(76, '::1', 'Administrator', NULL, '2025-09-13 09:23:11', 0),
(77, '::1', 'Administrator', NULL, '2025-09-13 09:23:12', 0),
(78, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-13 09:23:21', 1),
(79, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-13 14:38:25', 1),
(80, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-15 13:40:25', 1),
(81, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-15 18:03:07', 1),
(82, '::1', 'Administrator', NULL, '2025-09-16 04:11:14', 0),
(83, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-16 04:11:21', 1),
(84, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-16 10:40:59', 1),
(85, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-16 10:58:20', 1),
(86, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-17 04:22:23', 1),
(87, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-17 11:16:46', 1),
(88, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-20 10:36:50', 1),
(89, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-22 11:57:38', 1),
(90, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-23 03:35:31', 1),
(91, '::1', 'administrator', NULL, '2025-09-23 11:43:02', 0),
(92, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-23 11:43:09', 1),
(93, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-24 06:23:43', 1),
(94, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-24 09:31:00', 1),
(95, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-24 11:55:30', 1),
(96, '::1', 'budiagungofficial@gmail.com', 2, '2025-09-27 08:49:05', 1),
(97, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-01 17:31:16', 1),
(98, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-02 07:57:35', 1),
(99, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-04 08:15:46', 1),
(100, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-06 05:52:45', 1),
(101, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-06 11:17:34', 1),
(102, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-07 04:02:24', 1),
(103, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-07 09:04:09', 1),
(104, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-07 17:29:27', 1),
(105, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-08 07:40:51', 1),
(106, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-08 11:10:30', 1),
(107, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-09 17:49:59', 1),
(108, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-18 12:45:12', 1),
(109, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-19 05:23:26', 1),
(110, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-19 12:26:20', 1),
(111, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-19 13:40:52', 1),
(112, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-20 04:30:33', 1),
(113, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-20 10:57:33', 1),
(114, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-20 16:45:09', 1),
(115, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-24 11:45:37', 1),
(116, '::1', 'buday96', NULL, '2025-10-24 12:22:25', 0),
(117, '::1', 'budi77@gmail.com', 6, '2025-10-24 12:22:32', 1),
(118, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-24 12:25:01', 1),
(119, '::1', 'agnes88@gmail.com', 7, '2025-10-24 12:45:08', 1),
(120, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-24 12:57:47', 1),
(121, '::1', '411222025@mahasiswa.undira.ac.id', 8, '2025-10-24 12:58:41', 1),
(122, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-24 16:44:52', 1),
(123, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-25 08:04:19', 1),
(124, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-27 04:36:59', 1),
(125, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-27 09:15:16', 1),
(126, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-28 03:56:35', 1),
(127, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-28 12:36:28', 1),
(128, '::1', 'budiagungofficial@gmail.com', 2, '2025-10-28 15:21:07', 1),
(129, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-03 03:34:29', 1),
(130, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-04 04:22:26', 1),
(131, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-04 06:29:24', 1),
(132, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-04 11:27:07', 1),
(133, '::1', 'administrator', NULL, '2025-11-07 06:42:32', 0),
(134, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-07 06:42:38', 1),
(135, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-07 11:23:32', 1),
(136, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-07 16:51:25', 1),
(137, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-12 04:44:27', 1),
(138, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-12 08:20:58', 1),
(139, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-12 17:25:15', 1),
(140, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-13 11:23:15', 1),
(141, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-13 17:39:59', 1),
(142, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-14 03:12:38', 1),
(143, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-14 03:12:41', 1),
(144, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-14 11:27:58', 1),
(145, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-15 06:42:44', 1),
(146, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-15 13:44:01', 1),
(147, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-16 12:14:55', 1),
(148, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-17 03:06:36', 1),
(149, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-17 04:14:09', 1),
(150, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-17 04:14:11', 1),
(151, '::1', 'administartor', NULL, '2025-11-17 06:44:30', 0),
(152, '::1', 'administartor', NULL, '2025-11-17 06:44:37', 0),
(153, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-17 06:44:59', 1),
(154, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-17 06:49:36', 1),
(155, '::1', 'administrator', NULL, '2025-11-18 03:58:04', 0),
(156, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-18 03:59:15', 1),
(157, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-18 11:27:34', 1),
(158, '::1', 'administartor', NULL, '2025-11-18 14:52:05', 0),
(159, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-18 14:52:18', 1),
(160, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-18 15:00:08', 1),
(161, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-19 03:44:35', 1),
(162, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-19 11:14:41', 1),
(163, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-19 16:46:40', 1),
(164, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-25 06:59:15', 1),
(165, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-25 07:34:53', 1),
(166, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-25 07:38:14', 1),
(167, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-25 07:38:17', 1),
(168, '::1', 'administrator', NULL, '2025-11-25 11:04:16', 0),
(169, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-25 11:04:25', 1),
(170, '::1', 'administrator', NULL, '2025-11-25 13:12:27', 0),
(171, '::1', 'administrator', NULL, '2025-11-25 13:12:29', 0),
(172, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-25 13:12:35', 1),
(173, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-26 03:16:41', 1),
(174, '::1', 'budiagungofficial@gmail.com', 2, '2025-11-26 16:44:23', 1),
(175, '::1', 'budiagungofficial@gmail.com', 2, '2025-12-02 10:33:01', 1),
(176, '::1', 'budiagungofficial@gmail.com', 2, '2025-12-02 18:15:02', 1),
(177, '::1', 'budiagungofficial@gmail.com', 2, '2025-12-03 18:24:58', 1),
(178, '::1', 'budiagungofficial@gmail.com', 2, '2025-12-09 11:20:50', 1);

-- --------------------------------------------------------

--
-- Table structure for table `auth_permissions`
--

CREATE TABLE `auth_permissions` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_reset_attempts`
--

CREATE TABLE `auth_reset_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_tokens`
--

CREATE TABLE `auth_tokens` (
  `id` int(11) UNSIGNED NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashedValidator` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `expires` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_users_permissions`
--

CREATE TABLE `auth_users_permissions` (
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `permission_id` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cabang`
--

CREATE TABLE `cabang` (
  `id_cabang` int(10) UNSIGNED NOT NULL,
  `kode_cabang` varchar(20) NOT NULL,
  `nama_cabang` varchar(100) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cabang`
--

INSERT INTO `cabang` (`id_cabang`, `kode_cabang`, `nama_cabang`, `alamat`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'cbg001', 'Jakarta', 'DKI jakarta', '2025-09-04 11:57:11', '2025-09-04 11:57:11', NULL),
(2, 'cbg002', 'Surabaya', 'jalan mangga\r\n', '2025-09-07 08:17:50', '2025-09-07 08:17:50', NULL),
(4, 'cbg003', 'Palembang', 'Jl. jakabaring No 12', '2025-10-28 04:37:13', '2025-10-28 04:37:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kategori_aset`
--

CREATE TABLE `kategori_aset` (
  `id_kategori` int(10) UNSIGNED NOT NULL,
  `kode_kategori` varchar(20) DEFAULT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori_aset`
--

INSERT INTO `kategori_aset` (`id_kategori`, `kode_kategori`, `nama_kategori`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 'COM', 'Computer Part', '2025-09-07 15:47:50', '2025-09-07 15:47:50', NULL),
(5, 'FUR', 'Furniture', '2025-09-09 04:54:53', NULL, NULL),
(6, 'ELC', 'Elektronik', '2025-09-13 08:24:49', NULL, NULL),
(8, 'MEB', 'Mebel', '2025-10-07 06:08:48', '2025-10-27 04:39:29', NULL),
(12, 'TES 1', 'Test 1', '2025-10-28 04:50:05', '2025-10-28 04:50:17', NULL),
(13, 'KND', 'Kendaraan', '2025-12-02 11:26:57', '2025-12-02 11:26:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kelompok_harta`
--

CREATE TABLE `kelompok_harta` (
  `id_kelompok_harta` int(10) UNSIGNED NOT NULL,
  `kode_kelompok` varchar(20) NOT NULL,
  `nama_kelompok` varchar(120) NOT NULL,
  `umur_tahun` int(10) UNSIGNED NOT NULL,
  `umur_bulan` int(10) UNSIGNED GENERATED ALWAYS AS (`umur_tahun` * 12) STORED,
  `tarif_persen_th` decimal(6,3) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelompok_harta`
--

INSERT INTO `kelompok_harta` (`id_kelompok_harta`, `kode_kelompok`, `nama_kelompok`, `umur_tahun`, `tarif_persen_th`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'K1', 'Peralatan Kantor', 4, 25.000, 1, '2025-11-03 14:24:14', NULL),
(2, 'K2', 'Kendaraan Operasional', 8, 12.500, 1, '2025-11-03 14:24:14', NULL),
(3, 'K3', 'Mesin Produksi', 16, 6.250, 1, '2025-11-03 14:24:14', NULL),
(4, 'K4', 'Bangunan', 20, 5.000, 1, '2025-11-03 14:24:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `master_aset`
--

CREATE TABLE `master_aset` (
  `id_master_aset` int(10) UNSIGNED NOT NULL,
  `kode_master` varchar(50) DEFAULT NULL,
  `nama_master` varchar(120) NOT NULL,
  `id_kategori` int(10) UNSIGNED NOT NULL,
  `id_subkategori` int(10) UNSIGNED NOT NULL,
  `id_kelompok_harta` int(10) UNSIGNED DEFAULT NULL,
  `nilai_perolehan_default` decimal(18,2) DEFAULT NULL,
  `periode_perolehan_default` date DEFAULT NULL,
  `tanggal_mulai_susut_default` date DEFAULT NULL,
  `expired_default` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_aset`
--

INSERT INTO `master_aset` (`id_master_aset`, `kode_master`, `nama_master`, `id_kategori`, `id_subkategori`, `id_kelompok_harta`, `nilai_perolehan_default`, `periode_perolehan_default`, `tanggal_mulai_susut_default`, `expired_default`, `created_at`, `updated_at`, `deleted_at`) VALUES
(24, 'KM-FUR-MEJA-00024', 'Meja kerja 102 x 454', 5, 2, NULL, 2000000.00, '2024-06-01', NULL, '2026-01-08', '2025-10-07 08:31:29', '2025-10-19 06:58:49', NULL),
(25, 'KM-FUR-MEJA-00025', 'Meja kerja 12 x 45', 5, 2, 1, 54353463.00, '2025-07-01', NULL, NULL, '2025-10-07 08:56:39', '2025-11-19 17:05:04', NULL),
(26, 'KM-FUR-MEJA-00026', 'Meja makan pantry', 5, 2, 1, 1000000.00, '2025-01-01', NULL, NULL, '2025-10-07 17:42:19', '2025-11-04 04:50:53', NULL),
(27, 'KM-ELC-LAPTO-00027', 'Laptop HP G8', 6, 1, 1, 7500000.00, '2023-06-01', NULL, '2024-06-11', '2025-10-07 17:59:14', '2025-11-25 08:45:23', NULL),
(28, 'KM-FUR-MEJA-00028', 'tesft v', 5, 2, NULL, 5000000.00, '2025-10-01', NULL, NULL, '2025-10-07 19:02:43', '2025-10-08 07:49:31', '2025-10-08 07:49:31'),
(30, 'KM-FUR-MEJA-00030', 'Meja kerja', 5, 2, NULL, NULL, NULL, NULL, NULL, '2025-10-20 18:17:40', '2025-10-20 18:18:05', '2025-10-20 18:18:05'),
(31, 'KM-FUR-MEJA-00031', 'Test Pintasan', 5, 2, NULL, 5000000.00, '2023-06-01', NULL, NULL, '2025-10-20 18:25:24', '2025-10-20 18:26:27', '2025-10-20 18:26:27'),
(32, 'KM-ELC-LAPTO-00032', 'ergfgsfg', 6, 1, NULL, 5000.00, '2024-06-01', NULL, '2026-02-17', '2025-10-20 18:27:25', '2025-10-20 18:31:52', '2025-10-20 18:31:52'),
(34, 'KM-ELC-LAPTO-00034', 'Laptop Asuz 12', 6, 1, NULL, 15000000.00, '2025-01-01', NULL, '2027-06-09', '2025-10-28 05:11:01', '2025-10-28 05:11:01', NULL),
(35, 'KM-ELC-LAPTO-00035', 'Hp core i9 2342', 6, 1, 1, 2000000.00, '2023-02-01', NULL, '2027-06-09', '2025-11-03 08:24:21', '2025-11-04 04:41:02', NULL),
(36, 'KM-FUR-MEJA-00036', 'tets', 5, 2, NULL, 3000000.00, '2024-05-01', NULL, NULL, '2025-11-12 09:38:09', '2025-11-12 09:38:09', NULL),
(37, 'KM-ELC-LAPTO-00037', 'Master aset test', 6, 1, 1, 5000000.00, '2023-06-01', NULL, NULL, '2025-11-12 11:32:31', '2025-11-19 18:02:12', NULL),
(38, 'KM-ELC-LAPTO-00038', 'test 1', 6, 1, NULL, 50000.00, NULL, NULL, NULL, '2025-11-12 12:44:33', '2025-11-12 12:44:33', NULL),
(39, 'KM-FUR-MEJA-00039', 'meja security', 5, 2, 1, 5000000.00, '2024-06-01', NULL, NULL, '2025-11-13 11:25:46', '2025-11-13 17:40:33', NULL),
(40, 'KM-ELC-LAPTO-00040', 'Master aset baru', 6, 1, 1, 15000000.00, '2022-06-01', NULL, NULL, '2025-11-13 13:00:11', '2025-11-13 13:01:14', NULL),
(41, 'KM-ELC-LAPTO-00041', 'Laptop samsung s23', 6, 1, 1, 12000000.00, '2025-02-01', NULL, NULL, '2025-11-19 12:32:04', '2025-11-19 17:05:49', NULL),
(42, 'KM-FUR-MEJA-00042', 'Meja TV meeting', 5, 2, 1, 15000000.00, '2023-06-01', NULL, NULL, '2025-11-19 17:07:28', '2025-11-19 17:07:28', NULL),
(43, 'KM-COM-SERVE-00043', 'Rak server', 3, 8, 1, 20000000.00, '2024-05-01', NULL, NULL, '2025-11-19 17:41:56', '2025-11-25 08:46:19', NULL),
(44, 'KM-FUR-MEJA-00044', 'Rabu', 5, 2, 1, 2000000.00, '2024-06-01', NULL, '2026-03-04', '2025-11-26 09:20:35', '2025-11-26 09:20:35', NULL),
(45, 'KM-FUR-MEJA-00045', 'kamis', 5, 2, 1, 5000000.00, '2023-06-01', NULL, NULL, '2025-11-26 17:22:27', '2025-11-26 17:22:27', NULL),
(46, 'KM-ELC-LAPTO-00046', 'jumat', 6, 1, 2, 15000000.00, '2023-06-01', NULL, '2026-04-16', '2025-11-26 17:23:10', '2025-11-26 17:23:10', NULL),
(47, 'KM-ELC-LAPTO-00047', 'senin', 6, 1, 1, 5000000.00, '2025-07-01', NULL, '2026-02-19', '2025-11-26 18:23:27', '2025-11-26 18:23:27', NULL),
(48, 'KM-FUR-MEJA-00048', 'selasa', 5, 2, 1, 5000000.00, '2023-10-01', NULL, '2026-03-03', '2025-11-26 18:24:55', '2025-11-26 18:24:55', NULL),
(49, 'KM-KND-MOBIL-00049', 'Mobil Avanza 1767', 13, 9, 2, 150000000.00, '2023-02-01', NULL, NULL, '2025-12-02 11:36:51', '2025-12-02 11:36:51', NULL),
(50, 'KM-KND-MOBIL-00050', 'Mobil CRV 1222', 13, 9, 2, 520000000.00, '2025-12-01', NULL, NULL, '2025-12-09 12:44:25', '2025-12-09 12:44:25', NULL),
(51, 'KM-KND-MOBIL-00051', 'Mobil Xenia 1902', 13, 9, 2, 92000000.00, '2024-01-01', NULL, NULL, '2025-12-09 12:52:07', '2025-12-09 12:52:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `master_aset_atribut`
--

CREATE TABLE `master_aset_atribut` (
  `id_master_aset` int(10) UNSIGNED NOT NULL,
  `id_atribut` int(10) UNSIGNED NOT NULL,
  `nilai_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_aset_atribut`
--

INSERT INTO `master_aset_atribut` (`id_master_aset`, `id_atribut`, `nilai_default`) VALUES
(24, 8, '23'),
(24, 9, '245'),
(24, 10, '453'),
(24, 11, 'Besi'),
(25, 8, '34'),
(25, 9, '534'),
(25, 10, '435'),
(25, 11, 'kayu'),
(26, 8, '54'),
(26, 9, '423'),
(26, 10, '24542'),
(26, 11, 'Besi'),
(27, 2, '14-fc0888TU'),
(27, 3, '16'),
(27, 4, '256'),
(27, 6, 'Baik'),
(27, 17, 'Hp'),
(27, 19, '14'),
(28, 8, '776'),
(28, 9, '46'),
(28, 10, '46'),
(28, 11, 'kayu'),
(34, 2, 'dg3456'),
(34, 3, '16'),
(34, 4, '256'),
(34, 6, 'Tidak ada Baterai'),
(34, 17, 'Asuz'),
(34, 19, '14'),
(35, 2, 'i124we'),
(35, 3, '8'),
(35, 4, '512'),
(35, 6, 'Tidak ada Baterai'),
(35, 17, 'Hp'),
(35, 19, '14'),
(37, 2, 'irte'),
(37, 3, '6'),
(37, 4, '256'),
(37, 6, 'Tidak ada Baterai'),
(37, 17, 'Hp'),
(37, 19, '14'),
(39, 8, '56'),
(39, 9, '43'),
(39, 10, '434'),
(39, 11, 'kayu'),
(40, 2, 'iidsaf4'),
(40, 3, '8'),
(40, 4, '512'),
(40, 6, 'Sedang'),
(40, 17, 'Hp'),
(40, 19, '14'),
(41, 2, 'i124we'),
(41, 3, '16'),
(41, 4, '512'),
(41, 6, 'Baik'),
(41, 17, 'Samsung'),
(41, 19, '16'),
(42, 8, '45'),
(42, 9, '23'),
(42, 10, '34'),
(42, 11, 'kayu'),
(43, 20, '50'),
(43, 21, '50'),
(43, 22, 'Indodak'),
(44, 8, '23'),
(44, 9, '32'),
(44, 10, '32'),
(44, 11, 'kayu'),
(45, 8, '34'),
(45, 9, '342'),
(45, 10, '234'),
(45, 11, 'kayu'),
(46, 2, 'i124we'),
(46, 3, '12'),
(46, 4, '254'),
(46, 6, 'Baik'),
(46, 17, 'Samsung'),
(46, 19, '41'),
(47, 2, 'i124we'),
(47, 3, '8'),
(47, 4, '256'),
(47, 6, 'Tidak ada Baterai'),
(47, 17, 'Hp'),
(47, 19, '14'),
(48, 8, '12'),
(48, 9, '12'),
(48, 10, '12'),
(48, 11, 'Besi'),
(49, 23, 'Avanza'),
(49, 26, '2017'),
(49, 27, '123'),
(49, 28, '456'),
(50, 23, 'Honda CRV'),
(50, 26, '2021'),
(50, 27, '123456'),
(50, 28, '123445678'),
(51, 23, 'Avanza'),
(51, 26, '2016'),
(51, 27, '1234567'),
(51, 28, '1234567');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2017-11-20-223112', 'Myth\\Auth\\Database\\Migrations\\CreateAuthTables', 'default', 'Myth\\Auth', 1756711886, 1),
(2, '2025-09-02-000001', 'App\\Database\\Migrations\\CreateCabangTable', 'default', 'App', 1756789453, 2),
(3, '2025-09-02-000002', 'App\\Database\\Migrations\\CreateKategoriAsetTable', 'default', 'App', 1756789453, 2),
(4, '2025-09-02-000003', 'App\\Database\\Migrations\\CreateAsetTable', 'default', 'App', 1756789453, 2),
(5, '2025-09-02-000004', 'App\\Database\\Migrations\\CreateMutasiAsetTable', 'default', 'App', 1756791765, 3),
(6, '2025-09-02-000007', 'App\\Database\\Migrations\\AddIdCabangToUsers', 'default', 'App', 1756791765, 3),
(7, '2025-09-09-000001', 'App\\Database\\Migrations\\CreateSubkategoriAset', 'default', 'App', 1757392374, 4),
(8, '2025-09-09-000002', 'App\\Database\\Migrations\\CreateAtributDefinisi', 'default', 'App', 1757392374, 4),
(9, '2025-09-09-000003', 'App\\Database\\Migrations\\CreateAtributOpsi', 'default', 'App', 1757392374, 4),
(10, '2025-09-09-000004', 'App\\Database\\Migrations\\CreateAsetAtribut', 'default', 'App', 1757392374, 4),
(11, '2025-09-13-000001', 'App\\Database\\Migrations\\AddIdSubkategoriToAset', 'default', 'App', 1757751803, 5),
(12, '2025-09-13-000002', 'App\\Database\\Migrations\\CreateSubkategoriAset', 'default', 'App', 1757751803, 5),
(13, '2025-09-13-000003', 'App\\Database\\Migrations\\CreateAtributAset', 'default', 'App', 1757751803, 5),
(14, '2025-09-13-000004', 'App\\Database\\Migrations\\CreateAsetAtribut', 'default', 'App', 1757751803, 5),
(15, '2025-10-04-000001', 'App\\Database\\Migrations\\CreateMasterAset', 'default', 'App', 1759576879, 6);

-- --------------------------------------------------------

--
-- Table structure for table `mutasi_aset`
--

CREATE TABLE `mutasi_aset` (
  `id_mutasi` int(10) UNSIGNED NOT NULL,
  `kode_mutasi` varchar(50) NOT NULL,
  `tanggal_mutasi` datetime NOT NULL,
  `id_cabang_asal` int(10) UNSIGNED NOT NULL,
  `id_cabang_tujuan` int(10) UNSIGNED NOT NULL,
  `status` enum('pending','dikirim','diterima','dibatalkan') NOT NULL DEFAULT 'pending',
  `catatan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mutasi_aset`
--

INSERT INTO `mutasi_aset` (`id_mutasi`, `kode_mutasi`, `tanggal_mutasi`, `id_cabang_asal`, `id_cabang_tujuan`, `status`, `catatan`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'MT-20251126-0001', '2025-11-26 17:19:41', 2, 1, 'diterima', '', '2025-11-26 17:19:41', '2025-11-26 17:19:41', 2, 2),
(2, 'MT-20251126-0002', '2025-11-26 17:25:03', 1, 4, 'diterima', 'Untuk Manager', '2025-11-26 17:25:03', '2025-11-26 17:25:03', 2, 2),
(3, 'MT-20251126-0003', '2025-11-26 17:43:37', 4, 1, 'diterima', 'ok', '2025-11-26 17:43:37', '2025-11-26 17:43:37', 2, 2),
(4, 'MT-20251126-0004', '2025-11-26 18:06:08', 1, 2, 'diterima', 'ok', '2025-11-26 18:06:08', '2025-11-26 18:08:09', 2, 2),
(5, 'MT-20251126-0005', '2025-11-26 18:26:31', 4, 2, 'diterima', 'ok', '2025-11-26 18:26:31', '2025-11-26 18:30:46', 2, 2),
(6, 'MT-20251126-0006', '2025-11-26 18:32:37', 1, 2, 'diterima', 'test', '2025-11-26 18:32:37', '2025-11-26 18:32:54', 2, 2),
(7, 'MT-20251202-0007', '2025-12-02 12:00:43', 1, 4, 'diterima', 'Untuk kebutuhan Operasional', '2025-12-02 12:00:43', '2025-12-02 12:01:04', 2, 2),
(8, 'MT-20251202-0008', '2025-12-02 12:40:24', 4, 2, 'diterima', '', '2025-12-02 12:40:24', '2025-12-02 12:40:41', 2, 2),
(9, 'MT-20251202-0009', '2025-12-02 19:05:14', 2, 1, 'diterima', 'untuk kebutuhan operasional management', '2025-12-02 19:05:14', '2025-12-02 19:05:28', 2, 2),
(10, 'MT-20251202-0010', '2025-12-02 19:11:40', 1, 2, 'diterima', 'test', '2025-12-02 19:11:40', '2025-12-02 19:11:58', 2, 2),
(11, 'MT-20251202-0011', '2025-12-02 19:14:24', 4, 2, 'diterima', '', '2025-12-02 19:14:24', '2025-12-02 19:14:38', 2, 2),
(12, 'MT-20251209-0012', '2025-12-09 12:54:29', 1, 4, 'diterima', 'Untuk Operasional Kepala Cabang', '2025-12-09 12:54:29', '2025-12-09 12:54:39', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `mutasi_aset_detail`
--

CREATE TABLE `mutasi_aset_detail` (
  `id_detail` int(10) UNSIGNED NOT NULL,
  `id_mutasi` int(10) UNSIGNED NOT NULL,
  `id_aset_asal` int(10) UNSIGNED NOT NULL,
  `qty` int(10) UNSIGNED NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mutasi_aset_detail`
--

INSERT INTO `mutasi_aset_detail` (`id_detail`, `id_mutasi`, `id_aset_asal`, `qty`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 89, 1, '', '2025-11-26 17:19:41', '2025-11-26 17:19:41'),
(2, 2, 91, 1, 'ok', '2025-11-26 17:25:03', '2025-11-26 17:25:03'),
(3, 2, 90, 1, 'ok', '2025-11-26 17:25:03', '2025-11-26 17:25:03'),
(4, 3, 91, 1, 'ok', '2025-11-26 17:43:37', '2025-11-26 17:43:37'),
(5, 3, 90, 1, 'ok', '2025-11-26 17:43:37', '2025-11-26 17:43:37'),
(6, 4, 91, 1, 'ok', '2025-11-26 18:06:08', '2025-11-26 18:06:08'),
(7, 4, 90, 1, 'ok', '2025-11-26 18:06:08', '2025-11-26 18:06:08'),
(8, 5, 92, 1, 'ok', '2025-11-26 18:26:31', '2025-11-26 18:26:31'),
(9, 5, 93, 1, 'ok', '2025-11-26 18:26:31', '2025-11-26 18:26:31'),
(10, 6, 94, 1, 'test', '2025-11-26 18:32:37', '2025-11-26 18:32:37'),
(11, 7, 95, 1, '', '2025-12-02 12:00:43', '2025-12-02 12:00:43'),
(12, 8, 95, 1, '', '2025-12-02 12:40:24', '2025-12-02 12:40:24'),
(13, 9, 95, 1, 'ok', '2025-12-02 19:05:15', '2025-12-02 19:05:15'),
(14, 10, 95, 1, 'ok', '2025-12-02 19:11:40', '2025-12-02 19:11:40'),
(15, 11, 80, 1, '', '2025-12-02 19:14:24', '2025-12-02 19:14:24'),
(16, 11, 75, 1, '', '2025-12-02 19:14:24', '2025-12-02 19:14:24'),
(17, 11, 84, 1, '', '2025-12-02 19:14:24', '2025-12-02 19:14:24'),
(18, 12, 98, 1, 'Mobil Baru', '2025-12-09 12:54:29', '2025-12-09 12:54:29');

-- --------------------------------------------------------

--
-- Table structure for table `penyusutan_aset`
--

CREATE TABLE `penyusutan_aset` (
  `id_penyusutan` int(10) UNSIGNED NOT NULL,
  `id_aset` int(10) UNSIGNED NOT NULL,
  `tahun` smallint(5) UNSIGNED NOT NULL,
  `bulan` tinyint(3) UNSIGNED NOT NULL,
  `nilai_perolehan` decimal(18,2) NOT NULL,
  `umur_ekonomis_bulan` int(10) UNSIGNED NOT NULL,
  `metode_penyusutan` enum('GARIS_LURUS') NOT NULL DEFAULT 'GARIS_LURUS',
  `beban_penyusutan_bulan` decimal(18,2) NOT NULL,
  `akumulasi_sampai_bulan_ini` decimal(18,2) NOT NULL,
  `nilai_buku` decimal(18,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penyusutan_aset`
--

INSERT INTO `penyusutan_aset` (`id_penyusutan`, `id_aset`, `tahun`, `bulan`, `nilai_perolehan`, `umur_ekonomis_bulan`, `metode_penyusutan`, `beban_penyusutan_bulan`, `akumulasi_sampai_bulan_ini`, `nilai_buku`, `created_at`) VALUES
(1, 84, 2025, 12, 54353463.00, 48, 'GARIS_LURUS', 1132363.81, 1132363.81, 53221099.19, '2025-12-09 12:39:38'),
(2, 54, 2025, 12, 7500000.00, 48, 'GARIS_LURUS', 156250.00, 156250.00, 7343750.00, '2025-12-09 12:39:38'),
(3, 78, 2025, 12, 5000000.00, 48, 'GARIS_LURUS', 104166.67, 104166.67, 4895833.33, '2025-12-09 12:39:38'),
(4, 73, 2025, 12, 5000000.00, 48, 'GARIS_LURUS', 104166.67, 104166.67, 4895833.33, '2025-12-09 12:39:38'),
(5, 76, 2025, 12, 5000000.00, 48, 'GARIS_LURUS', 104166.67, 104166.67, 4895833.33, '2025-12-09 12:39:38'),
(6, 74, 2025, 12, 5000000.00, 48, 'GARIS_LURUS', 104166.67, 104166.67, 4895833.33, '2025-12-09 12:39:38'),
(7, 81, 2025, 12, 12000000.00, 48, 'GARIS_LURUS', 250000.00, 250000.00, 11750000.00, '2025-12-09 12:39:38'),
(8, 83, 2025, 12, 15000000.00, 48, 'GARIS_LURUS', 312500.00, 312500.00, 14687500.00, '2025-12-09 12:39:38'),
(9, 87, 2025, 12, 20000000.00, 48, 'GARIS_LURUS', 416666.67, 416666.67, 19583333.33, '2025-12-09 12:39:38'),
(10, 88, 2025, 12, 2000000.00, 48, 'GARIS_LURUS', 41666.67, 41666.67, 1958333.33, '2025-12-09 12:39:38'),
(11, 89, 2025, 12, 2000000.00, 48, 'GARIS_LURUS', 41666.67, 41666.67, 1958333.33, '2025-12-09 12:39:38'),
(12, 90, 2025, 12, 5000000.00, 48, 'GARIS_LURUS', 104166.67, 104166.67, 4895833.33, '2025-12-09 12:39:38'),
(13, 92, 2025, 12, 5000000.00, 48, 'GARIS_LURUS', 104166.67, 104166.67, 4895833.33, '2025-12-09 12:39:38'),
(14, 93, 2025, 12, 5000000.00, 48, 'GARIS_LURUS', 104166.67, 104166.67, 4895833.33, '2025-12-09 12:39:38'),
(15, 95, 2025, 12, 150000000.00, 96, 'GARIS_LURUS', 1562500.00, 1562500.00, 148437500.00, '2025-12-09 12:39:38'),
(16, 97, 2025, 12, 150000000.00, 96, 'GARIS_LURUS', 1562500.00, 1562500.00, 148437500.00, '2025-12-09 12:39:38');

-- --------------------------------------------------------

--
-- Table structure for table `subkategori_aset`
--

CREATE TABLE `subkategori_aset` (
  `id_subkategori` int(10) UNSIGNED NOT NULL,
  `id_kategori` int(10) UNSIGNED NOT NULL,
  `nama_subkategori` varchar(100) NOT NULL,
  `slug` varchar(120) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subkategori_aset`
--

INSERT INTO `subkategori_aset` (`id_subkategori`, `id_kategori`, `nama_subkategori`, `slug`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 6, 'Laptop', 'laptop', '2025-09-13 08:24:49', NULL, NULL),
(2, 5, 'Meja', 'meja', '2025-09-13 09:05:26', '2025-09-13 09:05:26', NULL),
(4, 8, 'Kayu', 'kayu', '2025-10-07 06:09:10', '2025-10-07 06:09:10', NULL),
(7, 12, 'Kaca1', 'kaca1', '2025-10-28 04:51:36', '2025-10-28 05:05:15', NULL),
(8, 3, 'Server', 'server', '2025-11-19 17:38:22', '2025-11-19 17:38:22', NULL),
(9, 13, 'Mobil', 'mobil', '2025-12-02 11:27:17', '2025-12-02 11:27:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `user_image` varchar(255) NOT NULL DEFAULT 'default.jpg',
  `password_hash` varchar(255) NOT NULL,
  `reset_hash` varchar(255) DEFAULT NULL,
  `reset_at` datetime DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `activate_hash` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `status_message` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `id_cabang` int(10) UNSIGNED DEFAULT NULL,
  `force_pass_reset` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `full_name`, `user_image`, `password_hash`, `reset_hash`, `reset_at`, `reset_expires`, `activate_hash`, `status`, `status_message`, `active`, `id_cabang`, `force_pass_reset`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'budiagungofficial@gmail.com', 'Administrator', 'Budi Agung', '1756986014_96f90c3ae5b6f38f15b6.jpg', '$2y$10$I3WxqzpDzLMdDjJbdlYEx.vSKB/u9yVWLs3vQ4nE8.NGTk9zGLQOu', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, '2025-09-02 08:57:53', '2025-09-04 11:46:54', NULL),
(3, 'dewicbg001@gmail.com', 'cbg001', 'Dewi Anjani', '1757232540_0ab1bffca85c875c5f54.jpg', '$2y$10$VFR.g9kkFqg7VMUbq1fPqOrOrgInOe82lnHDVTkKgEF9W3y54X.4O', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '2025-09-04 11:58:38', '2025-09-07 08:10:15', NULL),
(4, 'agungcbg002@gmail.com', 'cbg002', 'Agung Candra', '1757233174_16e45d7db7ca3381d6fd.jpeg', '$2y$10$S0JRi28bC6t/SSzMCeBxDeXMhdcrYdQWLj9Wz7A9.vbbYDuSDun4O', NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, 0, '2025-09-07 08:18:40', '2025-11-15 12:49:18', NULL),
(7, 'agnes88@gmail.com', 'agnes123', 'agnes monika', '1761310621_9776e40fa7419cb58cff.jpg', '$2y$10$qjE2JISCEL.5fX8yrcgwOeCt5vp.u6w.EkdcS/7CEWdrCrVuXvNr.', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '2025-10-24 12:42:07', '2025-10-24 12:57:01', NULL),
(8, '411222025@mahasiswa.undira.ac.id', 'Buday99', 'Budi Agung', 'default.jpg', '$2y$10$TWKpjlhsgPMZJbaVb.K9s.zBb2c/V0eIyFL.P8CePJnC6yldZaMqi', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '2025-10-24 12:58:23', '2025-10-24 12:58:23', NULL),
(9, 'aminsuroto22@gmail.com', 'Amin22', 'Amin Suroto', 'default.jpg', '$2y$10$aVrJYJmO8jDkSk7uke5WG.ybAeAUcqLt6F6GvvCq4Svrx2R6P2v5u', NULL, NULL, NULL, NULL, NULL, NULL, 1, 4, 0, '2025-11-18 04:44:33', '2025-11-18 04:45:34', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aset`
--
ALTER TABLE `aset`
  ADD PRIMARY KEY (`id_aset`),
  ADD UNIQUE KEY `kode_aset_2` (`kode_aset`),
  ADD UNIQUE KEY `kode_aset_3` (`kode_aset`),
  ADD UNIQUE KEY `qr_token` (`qr_token`),
  ADD UNIQUE KEY `uq_aset_qr_token` (`qr_token`),
  ADD UNIQUE KEY `uniq_kode_aset_per_cabang` (`kode_aset`,`id_cabang`),
  ADD UNIQUE KEY `uniq_aset_master_cabang` (`id_master_aset`,`id_cabang`),
  ADD UNIQUE KEY `uniq_aset__master_cabang` (`id_master_aset`,`id_cabang`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_cabang` (`id_cabang`),
  ADD KEY `fk_aset_subkategori` (`id_subkategori`),
  ADD KEY `idx_aset_kat_periode` (`id_kategori`,`periode_perolehan`);

--
-- Indexes for table `aset_atribut`
--
ALTER TABLE `aset_atribut`
  ADD PRIMARY KEY (`id_aset`,`id_atribut`),
  ADD KEY `id_atribut` (`id_atribut`),
  ADD KEY `idx_aset_atribut__aset` (`id_aset`),
  ADD KEY `idx_aset_atribut__atribut` (`id_atribut`);

--
-- Indexes for table `aset_counter`
--
ALTER TABLE `aset_counter`
  ADD PRIMARY KEY (`id_kategori`,`tahun`);

--
-- Indexes for table `atribut_aset`
--
ALTER TABLE `atribut_aset`
  ADD PRIMARY KEY (`id_atribut`),
  ADD UNIQUE KEY `uniq_atribut__subkategori_kode` (`id_subkategori`,`kode_atribut`),
  ADD KEY `id_subkategori` (`id_subkategori`);

--
-- Indexes for table `auth_activation_attempts`
--
ALTER TABLE `auth_activation_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_groups`
--
ALTER TABLE `auth_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_groups_permissions`
--
ALTER TABLE `auth_groups_permissions`
  ADD KEY `auth_groups_permissions_permission_id_foreign` (`permission_id`),
  ADD KEY `group_id_permission_id` (`group_id`,`permission_id`);

--
-- Indexes for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD KEY `auth_groups_users_user_id_foreign` (`user_id`),
  ADD KEY `group_id_user_id` (`group_id`,`user_id`);

--
-- Indexes for table `auth_logins`
--
ALTER TABLE `auth_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_permissions`
--
ALTER TABLE `auth_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_reset_attempts`
--
ALTER TABLE `auth_reset_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_tokens_user_id_foreign` (`user_id`),
  ADD KEY `selector` (`selector`);

--
-- Indexes for table `auth_users_permissions`
--
ALTER TABLE `auth_users_permissions`
  ADD KEY `auth_users_permissions_permission_id_foreign` (`permission_id`),
  ADD KEY `user_id_permission_id` (`user_id`,`permission_id`);

--
-- Indexes for table `cabang`
--
ALTER TABLE `cabang`
  ADD PRIMARY KEY (`id_cabang`),
  ADD UNIQUE KEY `kode_cabang` (`kode_cabang`);

--
-- Indexes for table `kategori_aset`
--
ALTER TABLE `kategori_aset`
  ADD PRIMARY KEY (`id_kategori`),
  ADD UNIQUE KEY `kode_kategori` (`kode_kategori`);

--
-- Indexes for table `kelompok_harta`
--
ALTER TABLE `kelompok_harta`
  ADD PRIMARY KEY (`id_kelompok_harta`),
  ADD UNIQUE KEY `uq_kode_kelompok` (`kode_kelompok`);

--
-- Indexes for table `master_aset`
--
ALTER TABLE `master_aset`
  ADD PRIMARY KEY (`id_master_aset`),
  ADD UNIQUE KEY `uq_kode_master_live` (`kode_master`,`deleted_at`),
  ADD KEY `idx_master__kat` (`id_kategori`),
  ADD KEY `idx_master__subkat` (`id_subkategori`),
  ADD KEY `idx_master_kelompok` (`id_kelompok_harta`);

--
-- Indexes for table `master_aset_atribut`
--
ALTER TABLE `master_aset_atribut`
  ADD PRIMARY KEY (`id_master_aset`,`id_atribut`),
  ADD KEY `idx_maa__master` (`id_master_aset`),
  ADD KEY `idx_maa__atribut` (`id_atribut`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mutasi_aset`
--
ALTER TABLE `mutasi_aset`
  ADD PRIMARY KEY (`id_mutasi`),
  ADD UNIQUE KEY `kode_mutasi` (`kode_mutasi`),
  ADD KEY `fk_mutasi_cabang_asal` (`id_cabang_asal`),
  ADD KEY `fk_mutasi_cabang_tujuan` (`id_cabang_tujuan`);

--
-- Indexes for table `mutasi_aset_detail`
--
ALTER TABLE `mutasi_aset_detail`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `fk_mutasi_detail_header` (`id_mutasi`),
  ADD KEY `fk_mutasi_detail_aset` (`id_aset_asal`);

--
-- Indexes for table `penyusutan_aset`
--
ALTER TABLE `penyusutan_aset`
  ADD PRIMARY KEY (`id_penyusutan`),
  ADD UNIQUE KEY `unique_aset_periode` (`id_aset`,`tahun`,`bulan`),
  ADD KEY `idx_aset` (`id_aset`);

--
-- Indexes for table `subkategori_aset`
--
ALTER TABLE `subkategori_aset`
  ADD PRIMARY KEY (`id_subkategori`),
  ADD UNIQUE KEY `uniq_subkategori__kat_slug` (`id_kategori`,`slug`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `idx_subkat__kat` (`id_kategori`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_users_id_cabang` (`id_cabang`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aset`
--
ALTER TABLE `aset`
  MODIFY `id_aset` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `atribut_aset`
--
ALTER TABLE `atribut_aset`
  MODIFY `id_atribut` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `auth_activation_attempts`
--
ALTER TABLE `auth_activation_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_groups`
--
ALTER TABLE `auth_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `auth_logins`
--
ALTER TABLE `auth_logins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179;

--
-- AUTO_INCREMENT for table `auth_permissions`
--
ALTER TABLE `auth_permissions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_reset_attempts`
--
ALTER TABLE `auth_reset_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cabang`
--
ALTER TABLE `cabang`
  MODIFY `id_cabang` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kategori_aset`
--
ALTER TABLE `kategori_aset`
  MODIFY `id_kategori` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `kelompok_harta`
--
ALTER TABLE `kelompok_harta`
  MODIFY `id_kelompok_harta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `master_aset`
--
ALTER TABLE `master_aset`
  MODIFY `id_master_aset` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `mutasi_aset`
--
ALTER TABLE `mutasi_aset`
  MODIFY `id_mutasi` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `mutasi_aset_detail`
--
ALTER TABLE `mutasi_aset_detail`
  MODIFY `id_detail` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `penyusutan_aset`
--
ALTER TABLE `penyusutan_aset`
  MODIFY `id_penyusutan` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `subkategori_aset`
--
ALTER TABLE `subkategori_aset`
  MODIFY `id_subkategori` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aset`
--
ALTER TABLE `aset`
  ADD CONSTRAINT `fk_aset_cabang` FOREIGN KEY (`id_cabang`) REFERENCES `cabang` (`id_cabang`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_aset_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_aset` (`id_kategori`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_aset_master` FOREIGN KEY (`id_master_aset`) REFERENCES `master_aset` (`id_master_aset`),
  ADD CONSTRAINT `fk_aset_subkategori` FOREIGN KEY (`id_subkategori`) REFERENCES `subkategori_aset` (`id_subkategori`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `aset_atribut`
--
ALTER TABLE `aset_atribut`
  ADD CONSTRAINT `fk_aset_atribut__aset` FOREIGN KEY (`id_aset`) REFERENCES `aset` (`id_aset`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_aset_atribut__atribut` FOREIGN KEY (`id_atribut`) REFERENCES `atribut_aset` (`id_atribut`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asetatribut_aset` FOREIGN KEY (`id_aset`) REFERENCES `aset` (`id_aset`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asetatribut_atribut` FOREIGN KEY (`id_atribut`) REFERENCES `atribut_aset` (`id_atribut`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `atribut_aset`
--
ALTER TABLE `atribut_aset`
  ADD CONSTRAINT `fk_atribut_subkategori` FOREIGN KEY (`id_subkategori`) REFERENCES `subkategori_aset` (`id_subkategori`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_groups_permissions`
--
ALTER TABLE `auth_groups_permissions`
  ADD CONSTRAINT `auth_groups_permissions_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auth_groups_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD CONSTRAINT `auth_groups_users_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD CONSTRAINT `auth_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_users_permissions`
--
ALTER TABLE `auth_users_permissions`
  ADD CONSTRAINT `auth_users_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auth_users_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `master_aset`
--
ALTER TABLE `master_aset`
  ADD CONSTRAINT `fk_ma_kat` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_aset` (`id_kategori`),
  ADD CONSTRAINT `fk_ma_sub` FOREIGN KEY (`id_subkategori`) REFERENCES `subkategori_aset` (`id_subkategori`),
  ADD CONSTRAINT `fk_master__kat` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_aset` (`id_kategori`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_master__subkat` FOREIGN KEY (`id_subkategori`) REFERENCES `subkategori_aset` (`id_subkategori`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_master_kelompok` FOREIGN KEY (`id_kelompok_harta`) REFERENCES `kelompok_harta` (`id_kelompok_harta`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `master_aset_atribut`
--
ALTER TABLE `master_aset_atribut`
  ADD CONSTRAINT `fk_maa__atribut` FOREIGN KEY (`id_atribut`) REFERENCES `atribut_aset` (`id_atribut`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_maa__master` FOREIGN KEY (`id_master_aset`) REFERENCES `master_aset` (`id_master_aset`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_maa_attr` FOREIGN KEY (`id_atribut`) REFERENCES `atribut_aset` (`id_atribut`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_maa_master` FOREIGN KEY (`id_master_aset`) REFERENCES `master_aset` (`id_master_aset`) ON DELETE CASCADE;

--
-- Constraints for table `mutasi_aset`
--
ALTER TABLE `mutasi_aset`
  ADD CONSTRAINT `fk_mutasi_cabang_asal` FOREIGN KEY (`id_cabang_asal`) REFERENCES `cabang` (`id_cabang`),
  ADD CONSTRAINT `fk_mutasi_cabang_tujuan` FOREIGN KEY (`id_cabang_tujuan`) REFERENCES `cabang` (`id_cabang`);

--
-- Constraints for table `mutasi_aset_detail`
--
ALTER TABLE `mutasi_aset_detail`
  ADD CONSTRAINT `fk_mutasi_detail_aset` FOREIGN KEY (`id_aset_asal`) REFERENCES `aset` (`id_aset`),
  ADD CONSTRAINT `fk_mutasi_detail_header` FOREIGN KEY (`id_mutasi`) REFERENCES `mutasi_aset` (`id_mutasi`) ON DELETE CASCADE;

--
-- Constraints for table `penyusutan_aset`
--
ALTER TABLE `penyusutan_aset`
  ADD CONSTRAINT `fk_peny_aset` FOREIGN KEY (`id_aset`) REFERENCES `aset` (`id_aset`) ON DELETE CASCADE;

--
-- Constraints for table `subkategori_aset`
--
ALTER TABLE `subkategori_aset`
  ADD CONSTRAINT `fk_subkat__kat` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_aset` (`id_kategori`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_subkategori_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_aset` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_cabang` FOREIGN KEY (`id_cabang`) REFERENCES `cabang` (`id_cabang`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
