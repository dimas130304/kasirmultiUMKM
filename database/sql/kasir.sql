-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 05, 2025 at 09:37 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kasir`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;

CREATE TABLE `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_customer` varchar(50) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `hp` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_customer` (`kode_customer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=7;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `kode_customer`, `nama`, `alamat`, `hp`, `keterangan`, `created_at`) VALUES
(5, 'C0001', 'online', 'Jakarta', '628', '', '2025-05-27 16:34:20'),
(6, 'C0006', 'umum', 'ujung harapan', '628996803889', '', '2025-06-22 18:32:28');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `kategori` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `kategori`) VALUES
(1, 'MINUMAN'),
(8, 'MAKANAN');

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `kode_menu` varchar(255) DEFAULT NULL,
  `kategori` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `harga_beli` int(11) NOT NULL,
  `harga_jual` int(11) NOT NULL,
  `login_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keuangan_lainnya`
--

CREATE TABLE `keuangan_lainnya` (
  `id` int(11) NOT NULL,
  `no_ledger` varchar(255) DEFAULT NULL,
  `nama_urusan` varchar(255) DEFAULT NULL,
  `jenis` varchar(255) DEFAULT NULL,
  `jumlah_masuk` int(11) NOT NULL,
  `jumlah_keluar` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `date` date DEFAULT NULL,
  `periode` varchar(255) DEFAULT NULL,
  `year` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keuangan_ledger`
--

CREATE TABLE `keuangan_ledger` (
  `id` int(11) NOT NULL,
  `no_ledger` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `jenis` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `nama_user` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telepon` varchar(255) NOT NULL,
  `foto` text NOT NULL,
  `level` varchar(255) DEFAULT NULL,
  `tgl_bergabung` varchar(255) NOT NULL,
  `deleted_at` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `user`, `pass`, `nama_user`, `alamat`, `email`, `telepon`, `foto`, `level`, `tgl_bergabung`, `deleted_at`) VALUES
(5, 'admin', '$2y$10$VlK4m.B9vmVSQiSPHrzSd.aM3ZXasbthoJEhK0DtSHC8zvQFcCLXq', 'Dimas Cafe Admin', '-', 'Dimascoffeecafe@gmail.com', '08996803892', 'user_1633736604.jpeg', 'Admin', '2019-09-11', '2021-07-27 12:25:48'),
(13, 'dimas', '$2y$10$Y2U0SpJ7n1SxgNTGcsM7Qe/o60zlS8JbiXvmQWM8XlBVQwoFsnleW', 'Dimas Adi Perdana', 'bekasi', 'dimas', '08996803892', '-', 'Kasir', '2025-05-27', NULL),
(14, 'darus', '$2y$10$uxQoKBjQMkFBNh9qj5oLO.LB2Qrh7F40bUlziyexREFYURhEyu.Ju', 'darus', 'bekasi timur', 'darus', '0867454367', '-', 'Admin', '2025-06-23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `kode_menu` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `harga_pokok` int(11) NOT NULL,
  `harga_jual` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `id_kategori`, `kode_menu`, `nama`, `harga_pokok`, `harga_jual`, `stok`, `keterangan`, `gambar`, `created_at`) VALUES
(1, 1, 'P0001', 'good day freeze', 5000, 8000, 18, '', 'produk_1748354436.jpg', '2025-05-27 09:00:36'),
(2, 1, 'P0002', 'Nutrisari All Varian rasa', 2000, 5000, 7, '', 'produk_1750247868.jpg', '2025-06-18 06:57:48'),
(3, 1, 'P0003', 'Good Day Cappuccino', 3000, 8000, 20, '', 'produk_1750248124.jpg', '2025-06-18 07:02:04'),
(4, 1, 'P0004', 'Es Teh Manis', 2000, 4000, 15, '', 'produk_1750248341.jpg', '2025-06-18 07:05:41'),
(5, 1, 'P0005', 'kopi susu', 2000, 5000, 10, '', 'produk_1750248606.jpg', '2025-06-18 07:10:06'),
(6, 8, 'P0006', 'Kentang Goreng', 4000, 10000, 5, '', 'produk_1750249274.png', '2025-06-18 07:21:14'),
(7, 8, 'P0007', 'Pisang Goreng', 5000, 10000, 19, '', 'produk_1750249471.png', '2025-06-18 07:24:31'),
(8, 8, 'P0008', 'Roti Bakar', 4000, 12000, 19, '', 'produk_1750249609.png', '2025-06-18 07:26:49'),
(9, 1, 'P0009', 'Abc Klepon', 3000, 7000, 21, '', 'produk_1750523089.png', '2025-06-21 11:18:26'),
(10, 1, 'P00010', 'Drink Beng-Beng', 4000, 8000, 18, '', 'produk_1750523275.png', '2025-06-21 11:27:55'),
(11, 1, 'P00011', 'Susu Dancow Coklat', 4000, 10000, 20, '', 'produk_1750523578.png', '2025-06-21 11:32:58'),
(12, 1, 'P00012', 'Susu Dancow putih', 4000, 10000, 20, '', 'produk_1750523889.png', '2025-06-21 11:38:09'),
(13, 1, 'P00013', 'Chocolatos Macha', 3000, 7000, 19, '', 'produk_1750524120.png', '2025-06-21 11:42:00'),
(14, 1, 'P00014', 'Chocolatos Coklat', 3000, 7000, 17, '', 'produk_1750524345.png', '2025-06-21 11:45:45'),
(15, 8, 'P00015', 'Mie indomie goreng', 3000, 10000, 19, '', 'produk_1750607186.png', '2025-06-22 10:46:26'),
(16, 8, 'P00016', 'Mie indomie rebus', 3000, 10000, 19, '', 'produk_1750607376.png', '2025-06-22 10:49:36');

-- --------------------------------------------------------

--
-- Table structure for table `menu_stok`
--

CREATE TABLE `menu_stok` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `stok_awal` int(11) NOT NULL,
  `stok_akhir` int(11) NOT NULL,
  `date` date NOT NULL,
  `periode` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_stok`
--

INSERT INTO `menu_stok` (`id`, `menu_id`, `stok_awal`, `stok_akhir`, `date`, `periode`) VALUES
(1, 1, 50, 0, '2025-05-27', '2025-05'),
(2, 2, 50, 0, '2025-06-18', '2025-06'),
(3, 3, 50, 0, '2025-06-18', '2025-06'),
(4, 4, 20, 0, '2025-06-18', '2025-06'),
(5, 5, 25, 0, '2025-06-18', '2025-06'),
(6, 14, 20, 0, '2025-06-21', '2025-06'),
(7, 13, 20, 0, '2025-06-21', '2025-06'),
(8, 12, 20, 0, '2025-06-21', '2025-06'),
(9, 11, 20, 0, '2025-06-21', '2025-06'),
(10, 10, 20, 0, '2025-06-21', '2025-06'),
(11, 9, 20, 0, '2025-06-21', '2025-06'),
(12, 8, 15, 0, '2025-06-21', '2025-06'),
(13, 7, 15, 0, '2025-06-21', '2025-06'),
(14, 6, 20, 0, '2025-06-21', '2025-06'),
(15, 16, 25, 0, '2025-06-22', '2025-06'),
(16, 15, 25, 0, '2025-06-22', '2025-06'),
(17, 8, 25, 5, '2025-06-25', '2025-06'),
(18, 16, 25, 10, '2025-06-25', '2025-06'),
(19, 15, 25, 15, '2025-06-25', '2025-06'),
(20, 14, 25, 10, '2025-06-25', '2025-06'),
(21, 13, 25, 10, '2025-06-25', '2025-06'),
(22, 12, 25, 15, '2025-06-25', '2025-06'),
(23, 11, 25, 15, '2025-06-25', '2025-06'),
(24, 10, 25, 10, '2025-06-25', '2025-06'),
(25, 9, 25, 10, '2025-06-25', '2025-06'),
(26, 7, 25, 5, '2025-06-25', '2025-06'),
(27, 6, 25, 3, '2025-06-25', '2025-06'),
(28, 4, 25, 10, '2025-06-25', '2025-06'),
(29, 2, 25, 10, '2025-06-25', '2025-06');

-- --------------------------------------------------------

--
-- Table structure for table `profil_toko`
--

CREATE TABLE `profil_toko` (
  `id` int(11) NOT NULL,
  `nama_toko` varchar(255) NOT NULL,
  `alamat_toko` text NOT NULL,
  `telepon_toko` varchar(25) DEFAULT NULL,
  `email_toko` varchar(255) DEFAULT NULL,
  `pemilik_toko` varchar(255) DEFAULT NULL,
  `website_toko` varchar(255) DEFAULT NULL,
  `tgl_update` datetime DEFAULT NULL,
  `os` int(11) DEFAULT NULL,
  `print` int(11) DEFAULT NULL,
  `print_default` int(11) DEFAULT NULL,
  `driver` varchar(255) DEFAULT NULL,
  `footer_struk` varchar(255) DEFAULT NULL,
  `pajak` int(11) NOT NULL,
  `voucher` int(11) NOT NULL,
  `diskon` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profil_toko`
--

INSERT INTO `profil_toko` (`id`, `nama_toko`, `alamat_toko`, `telepon_toko`, `email_toko`, `pemilik_toko`, `website_toko`, `tgl_update`, `os`, `print`, `print_default`, `driver`, `footer_struk`, `pajak`, `voucher`, `diskon`, `user_id`) VALUES
(1, 'Dimas coffee cafe', 'Babelan, Bekasi', '', '', 'Affan', '', '2025-05-01 05:25:19', 1, 1, 1, '', 'Terima Kasih\r\nSelamat Menikmati', 1, 1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `no_bon` varchar(255) DEFAULT NULL,
  `kasir_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `atas_nama` varchar(255) DEFAULT NULL,
  `pesanan` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `diskon` int(11) NOT NULL,
  `pajak` int(11) NOT NULL,
  `voucher` int(11) NOT NULL,
  `grandmodal` int(11) NOT NULL,
  `grandtotal` int(11) NOT NULL,
  `total_qty` int(11) NOT NULL,
  `dibayar` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `date` date DEFAULT NULL,
  `periode` varchar(255) DEFAULT NULL,
  `year` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `no_bon`, `kasir_id`, `customer_id`, `atas_nama`, `pesanan`, `status`, `diskon`, `pajak`, `voucher`, `grandmodal`, `grandtotal`, `total_qty`, `dibayar`, `created_at`, `date`, `periode`, `year`) VALUES
(192, 'B001', 5, 0, 'abe', 'Ditempat', 'Lunas', 0, 0, 0, 6000, 17000, 2, 20000, '2025-06-21 11:49:05', '2025-06-21', '2025-06', '2025'),
(193, 'B00193', 5, 0, 'acil', 'Ditempat', 'Lunas', 0, 0, 3000, 7000, 15000, 2, 20000, '2025-06-21 11:51:16', '2025-06-21', '2025-06', '2025'),
(194, 'B00194', 5, 0, 'di', 'Ditempat', 'Lunas', 0, 0, 0, 9000, 24000, 3, 25000, '2025-06-21 11:53:02', '2025-06-21', '2025-06', '2025'),
(195, 'B00195', 5, 0, 'bay', 'Ditempat', 'Lunas', 0, 0, 0, 14000, 26000, 3, 50000, '2025-06-21 11:55:20', '2025-06-21', '2025-06', '2025'),
(196, 'B00196', 5, 0, 'yu', 'Ditempat', 'Lunas', 0, 0, 0, 6000, 14000, 2, 20000, '2025-06-21 11:56:22', '2025-06-21', '2025-06', '2025'),
(197, 'B00197', 5, 0, 'yayu', 'Ditempat', 'Lunas', 0, 0, 0, 19000, 36000, 4, 50000, '2025-06-21 11:57:14', '2025-06-21', '2025-06', '2025'),
(198, 'B00198', 5, 0, 'cil', 'Ditempat', 'Lunas', 0, 0, 0, 2000, 4000, 1, 5000, '2025-06-21 11:57:49', '2025-06-21', '2025-06', '2025'),
(199, 'B00199', 5, 0, 'loy', 'Ditempat', 'Lunas', 0, 0, 0, 6000, 14000, 2, 20000, '2025-06-21 11:58:32', '2025-06-21', '2025-06', '2025'),
(200, 'B00200', 5, 0, 'doy', 'Ditempat', 'Lunas', 0, 0, 0, 12000, 26000, 3, 50000, '2025-06-21 11:59:14', '2025-06-21', '2025-06', '2025'),
(201, 'B00201', 5, 0, 'bon', 'Ditempat', 'Lunas', 0, 0, 0, 7000, 15000, 2, 15000, '2025-06-21 11:59:58', '2025-06-21', '2025-06', '2025'),
(202, 'B00202', 5, 0, 'dan', 'Ditempat', 'Lunas', 0, 0, 0, 18000, 45000, 7, 50000, '2025-06-22 10:39:44', '2025-06-22', '2025-06', '2025'),
(203, 'B00203', 5, 0, 'nia', 'Ditempat', 'Lunas', 0, 0, 0, 4000, 10000, 2, 10000, '2025-06-22 10:41:36', '2025-06-22', '2025-06', '2025'),
(204, 'B00204', 5, 0, 'gan', 'Ditempat', 'Lunas', 0, 0, 0, 25000, 54000, 6, 100000, '2025-06-22 10:42:41', '2025-06-22', '2025-06', '2025'),
(205, 'B00205', 5, 0, 'ma', 'Ditempat', 'Lunas', 0, 0, 0, 8000, 16000, 4, 20000, '2025-06-22 10:43:21', '2025-06-22', '2025-06', '2025'),
(206, 'B00206', 5, 0, 'daf', 'Ditempat', 'Lunas', 0, 0, 0, 15000, 42000, 6, 50000, '2025-06-22 10:54:41', '2025-06-22', '2025-06', '2025'),
(207, 'B00207', 5, 0, 'ky', 'Ditempat', 'Lunas', 0, 0, 0, 15000, 24000, 3, 25000, '2025-06-22 10:55:27', '2025-06-22', '2025-06', '2025'),
(208, 'B00208', 5, 0, 'agi', 'Ditempat', 'Lunas', 0, 0, 0, 24000, 72000, 8, 100000, '2025-06-22 10:56:10', '2025-06-22', '2025-06', '2025'),
(209, 'B00209', 5, 0, 'gia', 'Booking', 'Bayar Nanti', 0, 0, 0, 14000, 30000, 4, 0, '2025-06-22 11:00:55', '2025-06-22', '2025-06', '2025'),
(210, 'B00210', 5, 0, 'rido', 'Ditempat', 'Lunas', 0, 0, 0, 10000, 24000, 3, 25000, '2025-06-22 11:01:51', '2025-06-22', '2025-06', '2025'),
(211, 'B00211', 5, 0, 'ayu', 'Ditempat', 'Lunas', 0, 0, 0, 8000, 20000, 3, 20000, '2025-06-22 11:05:44', '2025-06-22', '2025-06', '2025'),
(212, 'B00212', 5, 0, 'aci', 'Ditempat', 'Lunas', 0, 0, 0, 10000, 23000, 4, 25000, '2025-06-22 11:07:02', '2025-06-22', '2025-06', '2025'),
(213, 'B00213', 5, 0, 'iwan', 'Ditempat', 'Lunas', 0, 0, 0, 16000, 42000, 5, 50000, '2025-06-22 11:29:34', '2025-06-22', '2025-06', '2025'),
(214, 'B00214', 5, 0, 'yyu', 'Ditempat', 'Lunas', 0, 0, 0, 7000, 17000, 2, 20000, '2025-06-22 11:30:21', '2025-06-22', '2025-06', '2025'),
(215, 'B00215', 5, 0, 'luy', 'Ditempat', 'Lunas', 0, 0, 0, 18000, 49000, 7, 50000, '2025-06-22 11:33:23', '2025-06-22', '2025-06', '2025'),
(216, 'B00216', 5, 0, 'kuya', 'Ditempat', 'Lunas', 0, 0, 0, 10000, 26000, 3, 30000, '2025-06-22 11:38:06', '2025-06-22', '2025-06', '2025'),
(217, 'B00217', 5, 5, 'doy', 'Ditempat', 'Lunas', 0, 0, 0, 6000, 14000, 2, 15000, '2025-06-22 11:38:55', '2025-06-22', '2025-06', '2025'),
(218, 'B00218', 5, 6, 'ciloy', 'Ditempat', 'Lunas', 0, 0, 0, 13000, 31000, 4, 50000, '2025-06-23 11:15:57', '2025-06-23', '2025-06', '2025'),
(219, 'B00219', 5, 0, 'kun', 'Ditempat', 'Lunas', 0, 0, 0, 18000, 50000, 8, 50000, '2025-06-23 11:21:05', '2025-06-23', '2025-06', '2025'),
(220, 'B00220', 5, 0, 'day', 'Ditempat', 'Lunas', 0, 0, 0, 12000, 31000, 4, 50000, '2025-06-23 11:21:39', '2025-06-23', '2025-06', '2025'),
(221, 'B00221', 5, 0, 'du', 'Ditempat', 'Lunas', 0, 0, 0, 11000, 24000, 3, 25000, '2025-06-23 11:22:16', '2025-06-23', '2025-06', '2025'),
(222, 'B00222', 5, 0, 'kol', 'Ditempat', 'Lunas', 0, 0, 0, 13000, 30000, 3, 30000, '2025-06-23 11:22:48', '2025-06-23', '2025-06', '2025'),
(223, 'B00223', 5, 0, 'dik', 'Ditempat', 'Lunas', 0, 0, 0, 22000, 53000, 7, 60000, '2025-06-23 11:23:38', '2025-06-23', '2025-06', '2025'),
(224, 'B00224', 5, 0, 'dis', 'Ditempat', 'Lunas', 0, 0, 0, 19000, 51000, 6, 55000, '2025-06-23 11:24:33', '2025-06-23', '2025-06', '2025'),
(225, 'B00225', 5, 0, 'pir', 'Ditempat', 'Lunas', 0, 0, 0, 27000, 66000, 7, 100000, '2025-06-23 11:25:52', '2025-06-23', '2025-06', '2025'),
(226, 'B00226', 5, 0, 'san', 'Ditempat', 'Lunas', 0, 0, 0, 45000, 110000, 12, 120000, '2025-06-23 11:26:44', '2025-06-23', '2025-06', '2025'),
(227, 'B00227', 5, 0, 'lang', 'Ditempat', 'Lunas', 0, 0, 0, 18000, 40000, 5, 50000, '2025-06-23 11:28:07', '2025-06-23', '2025-06', '2025'),
(228, 'B00228', 5, 0, 'ikin', 'Ditempat', 'Lunas', 0, 0, 0, 20000, 32000, 4, 50000, '2025-06-23 11:28:44', '2025-06-23', '2025-06', '2025'),
(229, 'B00229', 5, 0, 'yel', 'Ditempat', 'Lunas', 0, 0, 0, 10000, 25000, 4, 50000, '2025-06-23 11:30:10', '2025-06-23', '2025-06', '2025'),
(230, 'B00230', 5, 0, 'myuk', 'Ditempat', 'Lunas', 0, 0, 0, 23000, 64000, 7, 100000, '2025-06-23 11:30:56', '2025-06-23', '2025-06', '2025'),
(231, 'B00231', 5, 0, 'rus', 'Ditempat', 'Lunas', 0, 0, 0, 16000, 42000, 4, 50000, '2025-06-23 11:32:03', '2025-06-23', '2025-06', '2025'),
(232, 'B00232', 5, 0, 'nus', 'Ditempat', 'Lunas', 0, 0, 0, 23000, 53000, 7, 55000, '2025-06-23 11:33:49', '2025-06-23', '2025-06', '2025'),
(233, 'B00233', 5, 0, 'dila', 'Ditempat', 'Lunas', 0, 0, 0, 14000, 26000, 3, 30000, '2025-06-23 11:36:36', '2025-06-23', '2025-06', '2025'),
(234, 'B00234', 5, 0, 'cuk', 'Ditempat', 'Lunas', 0, 0, 0, 14000, 40000, 6, 40000, '2025-06-23 11:37:05', '2025-06-23', '2025-06', '2025'),
(235, 'B00235', 5, 0, 'dako', 'Ditempat', 'Lunas', 0, 0, 0, 17000, 40000, 4, 50000, '2025-06-23 11:41:27', '2025-06-23', '2025-06', '2025'),
(236, 'B00236', 5, 0, 'raf', 'Ditempat', 'Lunas', 0, 0, 0, 17000, 33000, 4, 35000, '2025-06-25 10:56:36', '2025-06-25', '2025-06', '2025'),
(237, 'B00237', 5, 0, 'wal', 'Ditempat', 'Lunas', 0, 0, 0, 15000, 44000, 5, 50000, '2025-06-25 10:57:27', '2025-06-25', '2025-06', '2025'),
(238, 'B00238', 5, 0, 'bo', 'Ditempat', 'Lunas', 0, 0, 0, 17000, 39000, 5, 50000, '2025-06-25 10:58:13', '2025-06-25', '2025-06', '2025'),
(239, 'B00239', 5, 0, 'fii', 'Ditempat', 'Lunas', 0, 0, 0, 7000, 14000, 2, 15000, '2025-06-25 11:25:59', '2025-06-25', '2025-06', '2025'),
(240, 'B00240', 5, 0, 'jul', 'Ditempat', 'Lunas', 0, 0, 0, 14000, 26000, 3, 30000, '2025-06-25 11:27:00', '2025-06-25', '2025-06', '2025'),
(241, 'B00241', 5, 0, 'teng', 'Ditempat', 'Lunas', 0, 0, 0, 9000, 18000, 2, 20000, '2025-06-26 10:51:23', '2025-06-26', '2025-06', '2025'),
(242, 'B00242', 5, 0, 'ril', 'Ditempat', 'Lunas', 0, 0, 0, 10000, 27000, 4, 30000, '2025-06-26 10:51:52', '2025-06-26', '2025-06', '2025'),
(243, 'B00243', 5, 0, 'rul', 'Ditempat', 'Lunas', 0, 0, 0, 14000, 29000, 4, 30000, '2025-06-26 10:52:22', '2025-06-26', '2025-06', '2025'),
(244, 'B00244', 5, 0, 'din', 'Ditempat', 'Lunas', 0, 0, 0, 6000, 18000, 2, 20000, '2025-06-26 10:53:12', '2025-06-26', '2025-06', '2025'),
(245, 'B00245', 5, 0, 'dan', 'Ditempat', 'Lunas', 0, 0, 0, 13000, 30000, 3, 30000, '2025-06-26 10:53:39', '2025-06-26', '2025-06', '2025'),
(246, 'B00246', 5, 0, 'yogi', 'Ditempat', 'Lunas', 0, 0, 0, 12000, 26000, 3, 50000, '2025-06-26 10:54:06', '2025-06-26', '2025-06', '2025'),
(247, 'B00247', 5, 0, 'luna', 'Ditempat', 'Lunas', 0, 0, 0, 5000, 14000, 2, 20000, '2025-06-26 10:54:32', '2025-06-26', '2025-06', '2025'),
(248, 'B00248', 5, 0, 'juna', 'Ditempat', 'Lunas', 0, 0, 0, 11000, 25000, 4, 25000, '2025-06-26 10:55:07', '2025-06-26', '2025-06', '2025'),
(249, 'B00249', 5, 0, 'pul', 'Ditempat', 'Lunas', 0, 0, 0, 14000, 35000, 5, 40000, '2025-06-26 10:55:49', '2025-06-26', '2025-06', '2025'),
(250, 'B00250', 5, 0, 'adila', 'Ditempat', 'Lunas', 0, 0, 0, 10000, 24000, 3, 25000, '2025-06-26 11:22:00', '2025-06-26', '2025-06', '2025'),
(251, 'B00251', 5, 0, 'doyok', 'Ditempat', 'Lunas', 0, 0, 0, 7000, 17000, 2, 20000, '2025-06-26 11:49:23', '2025-06-26', '2025-06', '2025'),
(252, 'B00252', 5, 0, 'masdim', 'Ditempat', 'Lunas', 0, 0, 0, 6000, 14000, 2, 20000, '2025-06-26 12:11:44', '2025-06-26', '2025-06', '2025'),
(253, 'B00253', 5, 0, 'couy', 'Ditempat', 'Lunas', 0, 0, 0, 10000, 24000, 3, 25000, '2025-06-29 11:20:42', '2025-06-29', '2025-06', '2025'),
(254, 'B00254', 5, 6, 'kulas', 'Ditempat', 'Lunas', 0, 0, 0, 6000, 15000, 2, 20000, '2025-06-29 11:22:09', '2025-06-29', '2025-06', '2025'),
(255, 'B00255', 5, 6, 'celos', 'Ditempat', 'Lunas', 0, 0, 0, 5000, 14000, 2, 15000, '2025-06-29 11:23:24', '2025-06-29', '2025-06', '2025'),
(256, 'B00256', 5, 6, 'lida', 'Ditempat', 'Lunas', 0, 0, 0, 14000, 26000, 3, 50000, '2025-06-29 11:24:11', '2025-06-29', '2025-06', '2025'),
(257, 'B00257', 5, 6, 'ando', 'Ditempat', 'Lunas', 0, 0, 0, 8000, 22000, 2, 25000, '2025-06-29 11:25:45', '2025-06-29', '2025-06', '2025'),
(258, 'B00258', 5, 5, 'LIHA', 'Ditempat', 'Lunas', 0, 0, 0, 7000, 18000, 2, 20000, '2025-06-29 11:30:59', '2025-06-29', '2025-06', '2025'),
(259, 'B00259', 5, 6, 'KULO', 'Ditempat', 'Lunas', 0, 0, 0, 6000, 17000, 2, 20000, '2025-06-29 11:31:37', '2025-06-29', '2025-06', '2025'),
(260, 'B00260', 5, 6, 'CIKA', 'Ditempat', 'Lunas', 0, 0, 0, 10000, 25000, 4, 50000, '2025-06-29 11:32:16', '2025-06-29', '2025-06', '2025'),
(261, 'B00261', 5, 6, 'NDII', 'Ditempat', 'Lunas', 0, 0, 0, 9000, 18000, 3, 20000, '2025-06-29 11:33:00', '2025-06-29', '2025-06', '2025'),
(262, 'B00262', 5, 6, 'hana', 'Ditempat', 'Lunas', 0, 0, 0, 17000, 44000, 5, 50000, '2025-06-29 11:42:02', '2025-06-29', '2025-06', '2025'),
(263, 'B00263', 5, 6, 'dera', 'Ditempat', 'Lunas', 0, 0, 0, 10000, 30000, 4, 30000, '2025-06-29 11:42:41', '2025-06-29', '2025-06', '2025'),
(264, 'B00264', 5, 6, 'umum', 'Ditempat', 'Lunas', 0, 0, 0, 17000, 41000, 5, 50000, '2025-07-01 12:32:00', '2025-07-01', '2025-07', '2025'),
(265, 'B00265', 5, 6, 'zak', 'Ditempat', 'Lunas', 0, 0, 0, 20000, 54000, 6, 60000, '2025-07-01 12:32:52', '2025-07-01', '2025-07', '2025'),
(266, 'B00266', 5, 5, 'alvi', 'Ditempat', 'Lunas', 0, 0, 0, 5000, 14000, 2, 14000, '2025-07-01 12:36:56', '2025-07-01', '2025-07', '2025'),
(267, 'B00267', 5, 6, 'pung', 'Ditempat', 'Lunas', 0, 0, 0, 13000, 26000, 3, 50000, '2025-07-01 12:37:45', '2025-07-01', '2025-07', '2025'),
(268, 'B00268', 5, 6, 'cin', 'Ditempat', 'Lunas', 0, 0, 0, 4000, 10000, 2, 10000, '2025-07-01 12:38:15', '2025-07-01', '2025-07', '2025'),
(269, 'B00269', 5, 6, 'lamin', 'Ditempat', 'Lunas', 0, 0, 0, 15000, 26000, 3, 30000, '2025-07-01 12:39:14', '2025-07-01', '2025-07', '2025'),
(270, 'B00270', 5, 6, 'umum', 'Ditempat', 'Lunas', 0, 0, 0, 13000, 35000, 4, 35000, '2025-07-01 12:40:39', '2025-07-01', '2025-07', '2025'),
(271, 'B00271', 5, 6, 'kul', 'Ditempat', 'Lunas', 0, 0, 0, 12000, 30000, 3, 50000, '2025-07-01 12:41:13', '2025-07-01', '2025-07', '2025'),
(272, 'B00272', 5, 5, 'tung', 'Ditempat', 'Lunas', 0, 0, 0, 7000, 19000, 2, 50000, '2025-07-01 12:42:03', '2025-07-01', '2025-07', '2025'),
(273, 'B00273', 5, 6, 'ipal', 'Ditempat', 'Lunas', 0, 0, 0, 6000, 14000, 2, 15000, '2025-07-03 02:34:07', '2025-07-03', '2025-07', '2025'),
(274, 'B00274', 5, 5, 'cula', 'Ditempat', 'Lunas', 0, 0, 0, 8000, 18000, 2, 20000, '2025-07-03 02:34:33', '2025-07-03', '2025-07', '2025'),
(275, 'B00275', 5, 5, 'cinta', 'Ditempat', 'Lunas', 0, 0, 0, 8000, 18000, 2, 20000, '2025-07-03 02:35:00', '2025-07-03', '2025-07', '2025'),
(276, 'B00276', 5, 6, 'luna', 'Ditempat', 'Lunas', 0, 0, 0, 10000, 25000, 4, 25000, '2025-07-03 02:35:28', '2025-07-03', '2025-07', '2025'),
(277, 'B00277', 5, 6, 'bokir', 'Ditempat', 'Lunas', 0, 0, 0, 9000, 20000, 3, 20000, '2025-07-03 02:36:00', '2025-07-03', '2025-07', '2025'),
(278, 'B00278', 5, 6, 'hen', 'Ditempat', 'Lunas', 0, 0, 0, 4000, 10000, 2, 10000, '2025-07-03 02:36:22', '2025-07-03', '2025-07', '2025'),
(279, 'B00279', 5, 6, 'ali', 'Ditempat', 'Lunas', 0, 0, 0, 12000, 32000, 3, 50000, '2025-07-03 02:36:47', '2025-07-03', '2025-07', '2025'),
(280, 'B00280', 5, 6, 'boris', 'Ditempat', 'Lunas', 0, 0, 0, 9000, 24000, 3, 25000, '2025-07-03 02:37:19', '2025-07-03', '2025-07', '2025'),
(281, 'B00281', 5, 6, 'ting', 'Ditempat', 'Lunas', 0, 0, 0, 16000, 36000, 6, 50000, '2025-07-03 02:37:52', '2025-07-03', '2025-07', '2025'),
(282, 'B00282', 5, 0, 'pe', 'Ditempat', 'Lunas', 0, 0, 0, 3000, 7000, 1, 10000, '2025-07-05 02:24:17', '2025-07-05', '2025-07', '2025');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_produk`
--

CREATE TABLE `transaksi_produk` (
  `id` int(11) NOT NULL,
  `no_bon` varchar(255) DEFAULT NULL,
  `kode_menu` varchar(255) DEFAULT NULL,
  `kategori` varchar(255) DEFAULT NULL,
  `nama_menu` varchar(255) DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `harga_beli` int(11) NOT NULL,
  `harga_jual` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `pesan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `date` date DEFAULT NULL,
  `periode` varchar(255) DEFAULT NULL,
  `year` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_produk`
--

INSERT INTO `transaksi_produk` (`id`, `no_bon`, `kode_menu`, `kategori`, `nama_menu`, `qty`, `harga_beli`, `harga_jual`, `keterangan`, `pesan`, `created_at`, `date`, `periode`, `year`) VALUES
(389, 'B001', 'P0002', 'MINUMAN', 'Nutrisari All Varian rasa', 1, 2000, 5000, NULL, NULL, '2025-06-21 11:49:05', '2025-06-21', '2025-06', '2025'),
(390, 'B001', 'P0008', 'MAKANAN', 'Roti Bakar', 1, 4000, 12000, NULL, NULL, '2025-06-21 11:49:05', '2025-06-21', '2025-06', '2025'),
(391, 'B00193', 'P0003', 'MINUMAN', 'Good Day Cappuccino', 1, 3000, 8000, NULL, NULL, '2025-06-21 11:51:16', '2025-06-21', '2025-06', '2025'),
(392, 'B00193', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-21 11:51:16', '2025-06-21', '2025-06', '2025'),
(393, 'B00194', 'P0003', 'MINUMAN', 'Good Day Cappuccino', 3, 3000, 8000, NULL, NULL, '2025-06-21 11:53:02', '2025-06-21', '2025-06', '2025'),
(394, 'B00195', 'P0001', 'MINUMAN', 'good day freeze', 2, 5000, 8000, NULL, NULL, '2025-06-21 11:55:20', '2025-06-21', '2025-06', '2025'),
(395, 'B00195', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-21 11:55:20', '2025-06-21', '2025-06', '2025'),
(396, 'B00196', 'P0009', 'MINUMAN', 'Abc Klepon', 2, 3000, 7000, NULL, NULL, '2025-06-21 11:56:22', '2025-06-21', '2025-06', '2025'),
(397, 'B00197', 'P0001', 'MINUMAN', 'good day freeze', 3, 5000, 8000, NULL, NULL, '2025-06-21 11:57:14', '2025-06-21', '2025-06', '2025'),
(398, 'B00197', 'P0008', 'MAKANAN', 'Roti Bakar', 1, 4000, 12000, NULL, NULL, '2025-06-21 11:57:14', '2025-06-21', '2025-06', '2025'),
(399, 'B00198', 'P0004', 'MINUMAN', 'Es Teh Manis', 1, 2000, 4000, NULL, NULL, '2025-06-21 11:57:49', '2025-06-21', '2025-06', '2025'),
(400, 'B00199', 'P00014', 'MINUMAN', 'Chocolatos Coklat', 2, 3000, 7000, NULL, NULL, '2025-06-21 11:58:32', '2025-06-21', '2025-06', '2025'),
(401, 'B00200', 'P00010', 'MINUMAN', 'Drink Beng-Beng', 2, 4000, 8000, NULL, NULL, '2025-06-21 11:59:14', '2025-06-21', '2025-06', '2025'),
(402, 'B00200', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-21 11:59:14', '2025-06-21', '2025-06', '2025'),
(403, 'B00201', 'P0005', 'MINUMAN', 'kopi susu', 1, 2000, 5000, NULL, NULL, '2025-06-21 11:59:58', '2025-06-21', '2025-06', '2025'),
(404, 'B00201', 'P0007', 'MAKANAN', 'Pisang Goreng', 1, 5000, 10000, NULL, NULL, '2025-06-21 11:59:58', '2025-06-21', '2025-06', '2025'),
(405, 'B00202', 'P0002', 'MINUMAN', 'Nutrisari All Varian rasa', 5, 2000, 5000, NULL, NULL, '2025-06-22 10:39:44', '2025-06-22', '2025-06', '2025'),
(406, 'B00202', 'P0006', 'MAKANAN', 'Kentang Goreng', 2, 4000, 10000, NULL, NULL, '2025-06-22 10:39:44', '2025-06-22', '2025-06', '2025'),
(407, 'B00203', 'P0002', 'MINUMAN', 'Nutrisari All Varian rasa', 2, 2000, 5000, NULL, NULL, '2025-06-22 10:41:36', '2025-06-22', '2025-06', '2025'),
(408, 'B00204', 'P0003', 'MINUMAN', 'Good Day Cappuccino', 2, 3000, 8000, NULL, NULL, '2025-06-22 10:42:41', '2025-06-22', '2025-06', '2025'),
(409, 'B00204', 'P0001', 'MINUMAN', 'good day freeze', 2, 5000, 8000, NULL, NULL, '2025-06-22 10:42:41', '2025-06-22', '2025-06', '2025'),
(410, 'B00204', 'P0008', 'MAKANAN', 'Roti Bakar', 1, 4000, 12000, NULL, NULL, '2025-06-22 10:42:41', '2025-06-22', '2025-06', '2025'),
(411, 'B00204', 'P0007', 'MAKANAN', 'Pisang Goreng', 1, 5000, 10000, NULL, NULL, '2025-06-22 10:42:41', '2025-06-22', '2025-06', '2025'),
(412, 'B00205', 'P0004', 'MINUMAN', 'Es Teh Manis', 4, 2000, 4000, NULL, NULL, '2025-06-22 10:43:21', '2025-06-22', '2025-06', '2025'),
(413, 'B00206', 'P0004', 'MINUMAN', 'Es Teh Manis', 3, 2000, 4000, NULL, NULL, '2025-06-22 10:54:41', '2025-06-22', '2025-06', '2025'),
(414, 'B00206', 'P00016', 'MAKANAN', 'Mie indomie rebus', 3, 3000, 10000, NULL, NULL, '2025-06-22 10:54:41', '2025-06-22', '2025-06', '2025'),
(415, 'B00207', 'P0001', 'MINUMAN', 'good day freeze', 3, 5000, 8000, NULL, NULL, '2025-06-22 10:55:27', '2025-06-22', '2025-06', '2025'),
(416, 'B00208', 'P0003', 'MINUMAN', 'Good Day Cappuccino', 4, 3000, 8000, NULL, NULL, '2025-06-22 10:56:10', '2025-06-22', '2025-06', '2025'),
(417, 'B00208', 'P00015', 'MAKANAN', 'Mie indomie goreng', 4, 3000, 10000, NULL, NULL, '2025-06-22 10:56:10', '2025-06-22', '2025-06', '2025'),
(418, 'B00209', 'P00010', 'MINUMAN', 'Drink Beng-Beng', 2, 4000, 8000, NULL, NULL, '2025-06-22 11:00:55', '2025-06-22', '2025-06', '2025'),
(419, 'B00209', 'P0009', 'MINUMAN', 'Abc Klepon', 2, 3000, 7000, NULL, NULL, '2025-06-22 11:00:55', '2025-06-22', '2025-06', '2025'),
(420, 'B00210', 'P00013', 'MINUMAN', 'Chocolatos Macha', 1, 3000, 7000, NULL, NULL, '2025-06-22 11:01:51', '2025-06-22', '2025-06', '2025'),
(421, 'B00210', 'P00014', 'MINUMAN', 'Chocolatos Coklat', 1, 3000, 7000, NULL, NULL, '2025-06-22 11:01:51', '2025-06-22', '2025-06', '2025'),
(422, 'B00210', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-22 11:01:51', '2025-06-22', '2025-06', '2025'),
(423, 'B00211', 'P0002', 'MINUMAN', 'Nutrisari All Varian rasa', 2, 2000, 5000, NULL, NULL, '2025-06-22 11:05:44', '2025-06-22', '2025-06', '2025'),
(424, 'B00211', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-22 11:05:44', '2025-06-22', '2025-06', '2025'),
(425, 'B00212', 'P0003', 'MINUMAN', 'Good Day Cappuccino', 1, 3000, 8000, NULL, NULL, '2025-06-22 11:07:02', '2025-06-22', '2025-06', '2025'),
(426, 'B00212', 'P0009', 'MINUMAN', 'Abc Klepon', 1, 3000, 7000, NULL, NULL, '2025-06-22 11:07:02', '2025-06-22', '2025-06', '2025'),
(427, 'B00212', 'P0004', 'MINUMAN', 'Es Teh Manis', 2, 2000, 4000, NULL, NULL, '2025-06-22 11:07:02', '2025-06-22', '2025-06', '2025'),
(428, 'B00213', 'P00016', 'MAKANAN', 'Mie indomie rebus', 2, 3000, 10000, NULL, NULL, '2025-06-22 11:29:34', '2025-06-22', '2025-06', '2025'),
(429, 'B00213', 'P00010', 'MINUMAN', 'Drink Beng-Beng', 1, 4000, 8000, NULL, NULL, '2025-06-22 11:29:34', '2025-06-22', '2025-06', '2025'),
(430, 'B00213', 'P00014', 'MINUMAN', 'Chocolatos Coklat', 2, 3000, 7000, NULL, NULL, '2025-06-22 11:29:34', '2025-06-22', '2025-06', '2025'),
(431, 'B00214', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-22 11:30:21', '2025-06-22', '2025-06', '2025'),
(432, 'B00214', 'P0009', 'MINUMAN', 'Abc Klepon', 1, 3000, 7000, NULL, NULL, '2025-06-22 11:30:21', '2025-06-22', '2025-06', '2025'),
(433, 'B00215', 'P0002', 'MINUMAN', 'Nutrisari All Varian rasa', 5, 2000, 5000, NULL, NULL, '2025-06-22 11:33:23', '2025-06-22', '2025-06', '2025'),
(434, 'B00215', 'P0008', 'MAKANAN', 'Roti Bakar', 2, 4000, 12000, NULL, NULL, '2025-06-22 11:33:23', '2025-06-22', '2025-06', '2025'),
(435, 'B00216', 'P0003', 'MINUMAN', 'Good Day Cappuccino', 2, 3000, 8000, NULL, NULL, '2025-06-22 11:38:06', '2025-06-22', '2025-06', '2025'),
(436, 'B00216', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-22 11:38:06', '2025-06-22', '2025-06', '2025'),
(437, 'B00217', 'P00014', 'MINUMAN', 'Chocolatos Coklat', 1, 3000, 7000, NULL, NULL, '2025-06-22 11:38:55', '2025-06-22', '2025-06', '2025'),
(438, 'B00217', 'P00013', 'MINUMAN', 'Chocolatos Macha', 1, 3000, 7000, NULL, NULL, '2025-06-22 11:38:55', '2025-06-22', '2025-06', '2025'),
(439, 'B00218', 'P00014', 'MINUMAN', 'Chocolatos Coklat', 1, 3000, 7000, NULL, NULL, '2025-06-23 11:15:57', '2025-06-23', '2025-06', '2025'),
(440, 'B00218', 'P00013', 'MINUMAN', 'Chocolatos Macha', 2, 3000, 7000, NULL, NULL, '2025-06-23 11:15:57', '2025-06-23', '2025-06', '2025'),
(441, 'B00218', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-23 11:15:57', '2025-06-23', '2025-06', '2025'),
(442, 'B00219', 'P0002', 'MINUMAN', 'Nutrisari All Varian rasa', 5, 2000, 5000, NULL, NULL, '2025-06-23 11:16:33', '2025-06-23', '2025-06', '2025'),
(443, 'B00219', 'P0002', 'MINUMAN', 'Nutrisari All Varian rasa', 5, 2000, 5000, NULL, NULL, '2025-06-23 11:18:40', '2025-06-23', '2025-06', '2025'),
(444, 'B00219', 'P0002', 'MINUMAN', 'Nutrisari All Varian rasa', 6, 2000, 5000, NULL, NULL, '2025-06-23 11:21:05', '2025-06-23', '2025-06', '2025'),
(445, 'B00219', 'P00015', 'MAKANAN', 'Mie indomie goreng', 1, 3000, 10000, NULL, NULL, '2025-06-23 11:21:05', '2025-06-23', '2025-06', '2025'),
(446, 'B00219', 'P00016', 'MAKANAN', 'Mie indomie rebus', 1, 3000, 10000, NULL, NULL, '2025-06-23 11:21:05', '2025-06-23', '2025-06', '2025'),
(447, 'B00220', 'P0002', 'MINUMAN', 'Nutrisari All Varian rasa', 1, 2000, 5000, NULL, NULL, '2025-06-23 11:21:39', '2025-06-23', '2025-06', '2025'),
(448, 'B00220', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-23 11:21:39', '2025-06-23', '2025-06', '2025'),
(449, 'B00220', 'P0003', 'MINUMAN', 'Good Day Cappuccino', 2, 3000, 8000, NULL, NULL, '2025-06-23 11:21:39', '2025-06-23', '2025-06', '2025'),
(450, 'B00221', 'P00014', 'MINUMAN', 'Chocolatos Coklat', 1, 3000, 7000, NULL, NULL, '2025-06-23 11:22:16', '2025-06-23', '2025-06', '2025'),
(451, 'B00221', 'P0009', 'MINUMAN', 'Abc Klepon', 1, 3000, 7000, NULL, NULL, '2025-06-23 11:22:16', '2025-06-23', '2025-06', '2025'),
(452, 'B00221', 'P0007', 'MAKANAN', 'Pisang Goreng', 1, 5000, 10000, NULL, NULL, '2025-06-23 11:22:16', '2025-06-23', '2025-06', '2025'),
(453, 'B00222', 'P00011', 'MINUMAN', 'Susu Dancow Coklat', 1, 4000, 10000, NULL, NULL, '2025-06-23 11:22:48', '2025-06-23', '2025-06', '2025'),
(454, 'B00222', 'P00012', 'MINUMAN', 'Susu Dancow putih', 1, 4000, 10000, NULL, NULL, '2025-06-23 11:22:48', '2025-06-23', '2025-06', '2025'),
(455, 'B00222', 'P0007', 'MAKANAN', 'Pisang Goreng', 1, 5000, 10000, NULL, NULL, '2025-06-23 11:22:48', '2025-06-23', '2025-06', '2025'),
(456, 'B00223', 'P0005', 'MINUMAN', 'kopi susu', 2, 2000, 5000, NULL, NULL, '2025-06-23 11:23:38', '2025-06-23', '2025-06', '2025'),
(457, 'B00223', 'P00013', 'MINUMAN', 'Chocolatos Macha', 1, 3000, 7000, NULL, NULL, '2025-06-23 11:23:38', '2025-06-23', '2025-06', '2025'),
(458, 'B00223', 'P00014', 'MINUMAN', 'Chocolatos Coklat', 1, 3000, 7000, NULL, NULL, '2025-06-23 11:23:38', '2025-06-23', '2025-06', '2025'),
(459, 'B00223', 'P0009', 'MINUMAN', 'Abc Klepon', 1, 3000, 7000, NULL, NULL, '2025-06-23 11:23:38', '2025-06-23', '2025-06', '2025'),
(460, 'B00223', 'P0007', 'MAKANAN', 'Pisang Goreng', 1, 5000, 10000, NULL, NULL, '2025-06-23 11:23:38', '2025-06-23', '2025-06', '2025'),
(461, 'B00223', 'P0008', 'MAKANAN', 'Roti Bakar', 1, 4000, 12000, NULL, NULL, '2025-06-23 11:23:38', '2025-06-23', '2025-06', '2025'),
(462, 'B00224', 'P00016', 'MAKANAN', 'Mie indomie rebus', 2, 3000, 10000, NULL, NULL, '2025-06-23 11:24:33', '2025-06-23', '2025-06', '2025'),
(463, 'B00224', 'P0003', 'MINUMAN', 'Good Day Cappuccino', 2, 3000, 8000, NULL, NULL, '2025-06-23 11:24:33', '2025-06-23', '2025-06', '2025'),
(464, 'B00224', 'P00010', 'MINUMAN', 'Drink Beng-Beng', 1, 4000, 8000, NULL, NULL, '2025-06-23 11:24:33', '2025-06-23', '2025-06', '2025'),
(465, 'B00224', 'P0009', 'MINUMAN', 'Abc Klepon', 1, 3000, 7000, NULL, NULL, '2025-06-23 11:24:33', '2025-06-23', '2025-06', '2025'),
(466, 'B00225', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-23 11:25:52', '2025-06-23', '2025-06', '2025'),
(467, 'B00225', 'P00011', 'MINUMAN', 'Susu Dancow Coklat', 1, 4000, 10000, NULL, NULL, '2025-06-23 11:25:52', '2025-06-23', '2025-06', '2025'),
(468, 'B00225', 'P00012', 'MINUMAN', 'Susu Dancow putih', 1, 4000, 10000, NULL, NULL, '2025-06-23 11:25:52', '2025-06-23', '2025-06', '2025'),
(469, 'B00225', 'P00014', 'MINUMAN', 'Chocolatos Coklat', 1, 3000, 7000, NULL, NULL, '2025-06-23 11:25:52', '2025-06-23', '2025-06', '2025'),
(470, 'B00225', 'P0009', 'MINUMAN', 'Abc Klepon', 1, 3000, 7000, NULL, NULL, '2025-06-23 11:25:52', '2025-06-23', '2025-06', '2025'),
(471, 'B00225', 'P0008', 'MAKANAN', 'Roti Bakar', 1, 4000, 12000, NULL, NULL, '2025-06-23 11:25:52', '2025-06-23', '2025-06', '2025'),
(472, 'B00225', 'P0007', 'MAKANAN', 'Pisang Goreng', 1, 5000, 10000, NULL, NULL, '2025-06-23 11:25:52', '2025-06-23', '2025-06', '2025'),
(473, 'B00226', 'P0001', 'MINUMAN', 'good day freeze', 3, 5000, 8000, NULL, NULL, '2025-06-23 11:26:44', '2025-06-23', '2025-06', '2025'),
(474, 'B00226', 'P0003', 'MINUMAN', 'Good Day Cappuccino', 3, 3000, 8000, NULL, NULL, '2025-06-23 11:26:44', '2025-06-23', '2025-06', '2025'),
(475, 'B00226', 'P00016', 'MAKANAN', 'Mie indomie rebus', 2, 3000, 10000, NULL, NULL, '2025-06-23 11:26:44', '2025-06-23', '2025-06', '2025'),
(476, 'B00226', 'P00015', 'MAKANAN', 'Mie indomie goreng', 2, 3000, 10000, NULL, NULL, '2025-06-23 11:26:44', '2025-06-23', '2025-06', '2025'),
(477, 'B00226', 'P0008', 'MAKANAN', 'Roti Bakar', 1, 4000, 12000, NULL, NULL, '2025-06-23 11:26:44', '2025-06-23', '2025-06', '2025'),
(478, 'B00226', 'P0007', 'MAKANAN', 'Pisang Goreng', 1, 5000, 10000, NULL, NULL, '2025-06-23 11:26:44', '2025-06-23', '2025-06', '2025'),
(479, 'B00227', 'P00013', 'MINUMAN', 'Chocolatos Macha', 2, 3000, 7000, NULL, NULL, '2025-06-23 11:28:07', '2025-06-23', '2025-06', '2025'),
(480, 'B00227', 'P00010', 'MINUMAN', 'Drink Beng-Beng', 2, 4000, 8000, NULL, NULL, '2025-06-23 11:28:07', '2025-06-23', '2025-06', '2025'),
(481, 'B00227', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-23 11:28:07', '2025-06-23', '2025-06', '2025'),
(482, 'B00228', 'P0001', 'MINUMAN', 'good day freeze', 4, 5000, 8000, NULL, NULL, '2025-06-23 11:28:44', '2025-06-23', '2025-06', '2025'),
(483, 'B00229', 'P0002', 'MINUMAN', 'Nutrisari All Varian rasa', 3, 2000, 5000, NULL, NULL, '2025-06-23 11:30:10', '2025-06-23', '2025-06', '2025'),
(484, 'B00229', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-23 11:30:10', '2025-06-23', '2025-06', '2025'),
(485, 'B00230', 'P0003', 'MINUMAN', 'Good Day Cappuccino', 2, 3000, 8000, NULL, NULL, '2025-06-23 11:30:56', '2025-06-23', '2025-06', '2025'),
(486, 'B00230', 'P0001', 'MINUMAN', 'good day freeze', 1, 5000, 8000, NULL, NULL, '2025-06-23 11:30:56', '2025-06-23', '2025-06', '2025'),
(487, 'B00230', 'P00016', 'MAKANAN', 'Mie indomie rebus', 2, 3000, 10000, NULL, NULL, '2025-06-23 11:30:56', '2025-06-23', '2025-06', '2025'),
(488, 'B00230', 'P00015', 'MAKANAN', 'Mie indomie goreng', 2, 3000, 10000, NULL, NULL, '2025-06-23 11:30:56', '2025-06-23', '2025-06', '2025'),
(489, 'B00231', 'P00011', 'MINUMAN', 'Susu Dancow Coklat', 1, 4000, 10000, NULL, NULL, '2025-06-23 11:32:03', '2025-06-23', '2025-06', '2025'),
(490, 'B00231', 'P00012', 'MINUMAN', 'Susu Dancow putih', 2, 4000, 10000, NULL, NULL, '2025-06-23 11:32:03', '2025-06-23', '2025-06', '2025'),
(491, 'B00231', 'P0008', 'MAKANAN', 'Roti Bakar', 1, 4000, 12000, NULL, NULL, '2025-06-23 11:32:03', '2025-06-23', '2025-06', '2025'),
(492, 'B00232', 'P0002', 'MINUMAN', 'Nutrisari All Varian rasa', 3, 2000, 5000, NULL, NULL, '2025-06-23 11:33:49', '2025-06-23', '2025-06', '2025'),
(493, 'B00232', 'P00010', 'MINUMAN', 'Drink Beng-Beng', 2, 4000, 8000, NULL, NULL, '2025-06-23 11:33:49', '2025-06-23', '2025-06', '2025'),
(494, 'B00232', 'P0007', 'MAKANAN', 'Pisang Goreng', 1, 5000, 10000, NULL, NULL, '2025-06-23 11:33:49', '2025-06-23', '2025-06', '2025'),
(495, 'B00232', 'P0008', 'MAKANAN', 'Roti Bakar', 1, 4000, 12000, NULL, NULL, '2025-06-23 11:33:49', '2025-06-23', '2025-06', '2025'),
(496, 'B00233', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-23 11:36:36', '2025-06-23', '2025-06', '2025'),
(497, 'B00233', 'P0001', 'MINUMAN', 'good day freeze', 2, 5000, 8000, NULL, NULL, '2025-06-23 11:36:36', '2025-06-23', '2025-06', '2025'),
(498, 'B00234', 'P0002', 'MINUMAN', 'Nutrisari All Varian rasa', 2, 2000, 5000, NULL, NULL, '2025-06-23 11:37:05', '2025-06-23', '2025-06', '2025'),
(499, 'B00234', 'P0005', 'MINUMAN', 'kopi susu', 2, 2000, 5000, NULL, NULL, '2025-06-23 11:37:05', '2025-06-23', '2025-06', '2025'),
(500, 'B00234', 'P00015', 'MAKANAN', 'Mie indomie goreng', 1, 3000, 10000, NULL, NULL, '2025-06-23 11:37:05', '2025-06-23', '2025-06', '2025'),
(501, 'B00234', 'P00016', 'MAKANAN', 'Mie indomie rebus', 1, 3000, 10000, NULL, NULL, '2025-06-23 11:37:05', '2025-06-23', '2025-06', '2025'),
(502, 'B00235', 'P0007', 'MAKANAN', 'Pisang Goreng', 1, 5000, 10000, NULL, NULL, '2025-06-23 11:41:27', '2025-06-23', '2025-06', '2025'),
(503, 'B00235', 'P00011', 'MINUMAN', 'Susu Dancow Coklat', 2, 4000, 10000, NULL, NULL, '2025-06-23 11:41:27', '2025-06-23', '2025-06', '2025'),
(504, 'B00235', 'P00012', 'MINUMAN', 'Susu Dancow putih', 1, 4000, 10000, NULL, NULL, '2025-06-23 11:41:27', '2025-06-23', '2025-06', '2025'),
(505, 'B00236', 'P00013', 'MINUMAN', 'Chocolatos Macha', 1, 3000, 7000, NULL, NULL, '2025-06-25 10:56:36', '2025-06-25', '2025-06', '2025'),
(506, 'B00236', 'P0001', 'MINUMAN', 'good day freeze', 2, 5000, 8000, NULL, NULL, '2025-06-25 10:56:36', '2025-06-25', '2025-06', '2025'),
(507, 'B00236', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-25 10:56:36', '2025-06-25', '2025-06', '2025'),
(508, 'B00237', 'P0003', 'MINUMAN', 'Good Day Cappuccino', 3, 3000, 8000, NULL, NULL, '2025-06-25 10:57:27', '2025-06-25', '2025-06', '2025'),
(509, 'B00237', 'P00016', 'MAKANAN', 'Mie indomie rebus', 2, 3000, 10000, NULL, NULL, '2025-06-25 10:57:27', '2025-06-25', '2025-06', '2025'),
(510, 'B00238', 'P0007', 'MAKANAN', 'Pisang Goreng', 1, 5000, 10000, NULL, NULL, '2025-06-25 10:58:13', '2025-06-25', '2025-06', '2025'),
(511, 'B00238', 'P0005', 'MINUMAN', 'kopi susu', 1, 2000, 5000, NULL, NULL, '2025-06-25 10:58:13', '2025-06-25', '2025-06', '2025'),
(512, 'B00238', 'P00013', 'MINUMAN', 'Chocolatos Macha', 2, 3000, 7000, NULL, NULL, '2025-06-25 10:58:13', '2025-06-25', '2025-06', '2025'),
(513, 'B00238', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-25 10:58:13', '2025-06-25', '2025-06', '2025'),
(514, 'B00239', 'P0004', 'MINUMAN', 'Es Teh Manis', 1, 2000, 4000, NULL, NULL, '2025-06-25 11:25:59', '2025-06-25', '2025-06', '2025'),
(515, 'B00239', 'P0007', 'MAKANAN', 'Pisang Goreng', 1, 5000, 10000, NULL, NULL, '2025-06-25 11:25:59', '2025-06-25', '2025-06', '2025'),
(516, 'B00240', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-25 11:27:00', '2025-06-25', '2025-06', '2025'),
(517, 'B00240', 'P0001', 'MINUMAN', 'good day freeze', 2, 5000, 8000, NULL, NULL, '2025-06-25 11:27:00', '2025-06-25', '2025-06', '2025'),
(518, 'B00241', 'P0001', 'MINUMAN', 'good day freeze', 1, 5000, 8000, NULL, NULL, '2025-06-26 10:51:23', '2025-06-26', '2025-06', '2025'),
(519, 'B00241', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-26 10:51:23', '2025-06-26', '2025-06', '2025'),
(520, 'B00242', 'P0002', 'MINUMAN', 'Nutrisari All Varian rasa', 3, 2000, 5000, NULL, NULL, '2025-06-26 10:51:52', '2025-06-26', '2025-06', '2025'),
(521, 'B00242', 'P0008', 'MAKANAN', 'Roti Bakar', 1, 4000, 12000, NULL, NULL, '2025-06-26 10:51:52', '2025-06-26', '2025-06', '2025'),
(522, 'B00243', 'P0009', 'MINUMAN', 'Abc Klepon', 1, 3000, 7000, NULL, NULL, '2025-06-26 10:52:22', '2025-06-26', '2025-06', '2025'),
(523, 'B00243', 'P00013', 'MINUMAN', 'Chocolatos Macha', 2, 3000, 7000, NULL, NULL, '2025-06-26 10:52:22', '2025-06-26', '2025-06', '2025'),
(524, 'B00243', 'P0001', 'MINUMAN', 'good day freeze', 1, 5000, 8000, NULL, NULL, '2025-06-26 10:52:22', '2025-06-26', '2025-06', '2025'),
(525, 'B00244', 'P00015', 'MAKANAN', 'Mie indomie goreng', 1, 3000, 10000, NULL, NULL, '2025-06-26 10:53:12', '2025-06-26', '2025-06', '2025'),
(526, 'B00244', 'P0003', 'MINUMAN', 'Good Day Cappuccino', 1, 3000, 8000, NULL, NULL, '2025-06-26 10:53:12', '2025-06-26', '2025-06', '2025'),
(527, 'B00245', 'P00011', 'MINUMAN', 'Susu Dancow Coklat', 1, 4000, 10000, NULL, NULL, '2025-06-26 10:53:39', '2025-06-26', '2025-06', '2025'),
(528, 'B00245', 'P00012', 'MINUMAN', 'Susu Dancow putih', 1, 4000, 10000, NULL, NULL, '2025-06-26 10:53:39', '2025-06-26', '2025-06', '2025'),
(529, 'B00245', 'P0007', 'MAKANAN', 'Pisang Goreng', 1, 5000, 10000, NULL, NULL, '2025-06-26 10:53:39', '2025-06-26', '2025-06', '2025'),
(530, 'B00246', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-26 10:54:06', '2025-06-26', '2025-06', '2025'),
(531, 'B00246', 'P00010', 'MINUMAN', 'Drink Beng-Beng', 2, 4000, 8000, NULL, NULL, '2025-06-26 10:54:06', '2025-06-26', '2025-06', '2025'),
(532, 'B00247', 'P0004', 'MINUMAN', 'Es Teh Manis', 1, 2000, 4000, NULL, NULL, '2025-06-26 10:54:32', '2025-06-26', '2025-06', '2025'),
(533, 'B00247', 'P00015', 'MAKANAN', 'Mie indomie goreng', 1, 3000, 10000, NULL, NULL, '2025-06-26 10:54:32', '2025-06-26', '2025-06', '2025'),
(534, 'B00248', 'P0005', 'MINUMAN', 'kopi susu', 3, 2000, 5000, NULL, NULL, '2025-06-26 10:55:07', '2025-06-26', '2025-06', '2025'),
(535, 'B00248', 'P0007', 'MAKANAN', 'Pisang Goreng', 1, 5000, 10000, NULL, NULL, '2025-06-26 10:55:07', '2025-06-26', '2025-06', '2025'),
(536, 'B00249', 'P0006', 'MAKANAN', 'Kentang Goreng', 2, 4000, 10000, NULL, NULL, '2025-06-26 10:55:49', '2025-06-26', '2025-06', '2025'),
(537, 'B00249', 'P0002', 'MINUMAN', 'Nutrisari All Varian rasa', 3, 2000, 5000, NULL, NULL, '2025-06-26 10:55:49', '2025-06-26', '2025-06', '2025'),
(538, 'B00250', 'P00014', 'MINUMAN', 'Chocolatos Coklat', 1, 3000, 7000, NULL, NULL, '2025-06-26 11:22:00', '2025-06-26', '2025-06', '2025'),
(539, 'B00250', 'P00013', 'MINUMAN', 'Chocolatos Macha', 1, 3000, 7000, NULL, NULL, '2025-06-26 11:22:00', '2025-06-26', '2025-06', '2025'),
(540, 'B00250', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-26 11:22:00', '2025-06-26', '2025-06', '2025'),
(541, 'B00251', 'P00014', 'MINUMAN', 'Chocolatos Coklat', 1, 3000, 7000, NULL, NULL, '2025-06-26 11:49:23', '2025-06-26', '2025-06', '2025'),
(542, 'B00251', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-26 11:49:23', '2025-06-26', '2025-06', '2025'),
(543, 'B00252', 'P00014', 'MINUMAN', 'Chocolatos Coklat', 1, 3000, 7000, NULL, NULL, '2025-06-26 12:11:44', '2025-06-26', '2025-06', '2025'),
(544, 'B00252', 'P00013', 'MINUMAN', 'Chocolatos Macha', 1, 3000, 7000, NULL, NULL, '2025-06-26 12:11:44', '2025-06-26', '2025-06', '2025'),
(545, 'B00253', 'P00014', 'MINUMAN', 'Chocolatos Coklat', 1, 3000, 7000, NULL, NULL, '2025-06-29 11:20:42', '2025-06-29', '2025-06', '2025'),
(546, 'B00253', 'P00013', 'MINUMAN', 'Chocolatos Macha', 1, 3000, 7000, NULL, NULL, '2025-06-29 11:20:42', '2025-06-29', '2025-06', '2025'),
(547, 'B00253', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-29 11:20:42', '2025-06-29', '2025-06', '2025'),
(548, 'B00254', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-29 11:22:09', '2025-06-29', '2025-06', '2025'),
(549, 'B00254', 'P0002', 'MINUMAN', 'Nutrisari All Varian rasa', 1, 2000, 5000, NULL, NULL, '2025-06-29 11:22:09', '2025-06-29', '2025-06', '2025'),
(550, 'B00255', 'P00015', 'MAKANAN', 'Mie indomie goreng', 1, 3000, 10000, NULL, NULL, '2025-06-29 11:23:24', '2025-06-29', '2025-06', '2025'),
(551, 'B00255', 'P0004', 'MINUMAN', 'Es Teh Manis', 1, 2000, 4000, NULL, NULL, '2025-06-29 11:23:24', '2025-06-29', '2025-06', '2025'),
(552, 'B00256', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-29 11:24:11', '2025-06-29', '2025-06', '2025'),
(553, 'B00256', 'P0001', 'MINUMAN', 'good day freeze', 2, 5000, 8000, NULL, NULL, '2025-06-29 11:24:11', '2025-06-29', '2025-06', '2025'),
(554, 'B00257', 'P00012', 'MINUMAN', 'Susu Dancow putih', 1, 4000, 10000, NULL, NULL, '2025-06-29 11:25:45', '2025-06-29', '2025-06', '2025'),
(555, 'B00257', 'P0008', 'MAKANAN', 'Roti Bakar', 1, 4000, 12000, NULL, NULL, '2025-06-29 11:25:45', '2025-06-29', '2025-06', '2025'),
(556, 'B00258', 'P00016', 'MAKANAN', 'Mie indomie rebus', 1, 3000, 10000, NULL, NULL, '2025-06-29 11:30:59', '2025-06-29', '2025-06', '2025'),
(557, 'B00258', 'P00010', 'MINUMAN', 'Drink Beng-Beng', 1, 4000, 8000, NULL, NULL, '2025-06-29 11:30:59', '2025-06-29', '2025-06', '2025'),
(558, 'B00259', 'P00014', 'MINUMAN', 'Chocolatos Coklat', 1, 3000, 7000, NULL, NULL, '2025-06-29 11:31:37', '2025-06-29', '2025-06', '2025'),
(559, 'B00259', 'P00015', 'MAKANAN', 'Mie indomie goreng', 1, 3000, 10000, NULL, NULL, '2025-06-29 11:31:37', '2025-06-29', '2025-06', '2025'),
(560, 'B00260', 'P0002', 'MINUMAN', 'Nutrisari All Varian rasa', 3, 2000, 5000, NULL, NULL, '2025-06-29 11:32:16', '2025-06-29', '2025-06', '2025'),
(561, 'B00260', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-06-29 11:32:16', '2025-06-29', '2025-06', '2025'),
(562, 'B00261', 'P0007', 'MAKANAN', 'Pisang Goreng', 1, 5000, 10000, NULL, NULL, '2025-06-29 11:33:00', '2025-06-29', '2025-06', '2025'),
(563, 'B00261', 'P0004', 'MINUMAN', 'Es Teh Manis', 2, 2000, 4000, NULL, NULL, '2025-06-29 11:33:00', '2025-06-29', '2025-06', '2025'),
(564, 'B00262', 'P0003', 'MINUMAN', 'Good Day Cappuccino', 3, 3000, 8000, NULL, NULL, '2025-06-29 11:42:02', '2025-06-29', '2025-06', '2025'),
(565, 'B00262', 'P0006', 'MAKANAN', 'Kentang Goreng', 2, 4000, 10000, NULL, NULL, '2025-06-29 11:42:02', '2025-06-29', '2025-06', '2025'),
(566, 'B00263', 'P00016', 'MAKANAN', 'Mie indomie rebus', 2, 3000, 10000, NULL, NULL, '2025-06-29 11:42:41', '2025-06-29', '2025-06', '2025'),
(567, 'B00263', 'P0002', 'MINUMAN', 'Nutrisari All Varian rasa', 2, 2000, 5000, NULL, NULL, '2025-06-29 11:42:41', '2025-06-29', '2025-06', '2025'),
(568, 'B00264', 'P0002', 'MINUMAN', 'Nutrisari All Varian rasa', 1, 2000, 5000, NULL, NULL, '2025-07-01 12:32:00', '2025-07-01', '2025-07', '2025'),
(569, 'B00264', 'P00010', 'MINUMAN', 'Drink Beng-Beng', 1, 4000, 8000, NULL, NULL, '2025-07-01 12:32:00', '2025-07-01', '2025-07', '2025'),
(570, 'B00264', 'P0003', 'MINUMAN', 'Good Day Cappuccino', 1, 3000, 8000, NULL, NULL, '2025-07-01 12:32:00', '2025-07-01', '2025-07', '2025'),
(571, 'B00264', 'P0006', 'MAKANAN', 'Kentang Goreng', 2, 4000, 10000, NULL, NULL, '2025-07-01 12:32:00', '2025-07-01', '2025-07', '2025'),
(572, 'B00265', 'P0008', 'MAKANAN', 'Roti Bakar', 2, 4000, 12000, NULL, NULL, '2025-07-01 12:32:52', '2025-07-01', '2025-07', '2025'),
(573, 'B00265', 'P00011', 'MINUMAN', 'Susu Dancow Coklat', 1, 4000, 10000, NULL, NULL, '2025-07-01 12:32:52', '2025-07-01', '2025-07', '2025'),
(574, 'B00265', 'P00012', 'MINUMAN', 'Susu Dancow putih', 1, 4000, 10000, NULL, NULL, '2025-07-01 12:32:52', '2025-07-01', '2025-07', '2025'),
(575, 'B00265', 'P0002', 'MINUMAN', 'Nutrisari All Varian rasa', 2, 2000, 5000, NULL, NULL, '2025-07-01 12:32:52', '2025-07-01', '2025-07', '2025'),
(576, 'B00266', 'P0004', 'MINUMAN', 'Es Teh Manis', 1, 2000, 4000, NULL, NULL, '2025-07-01 12:34:54', '2025-07-01', '2025-07', '2025'),
(577, 'B00266', 'P00015', 'MAKANAN', 'Mie indomie goreng', 1, 3000, 10000, NULL, NULL, '2025-07-01 12:34:54', '2025-07-01', '2025-07', '2025'),
(578, 'B00267', 'P00010', 'MINUMAN', 'Drink Beng-Beng', 1, 4000, 8000, NULL, NULL, '2025-07-01 12:37:45', '2025-07-01', '2025-07', '2025'),
(579, 'B00267', 'P0001', 'MINUMAN', 'good day freeze', 1, 5000, 8000, NULL, NULL, '2025-07-01 12:37:45', '2025-07-01', '2025-07', '2025'),
(580, 'B00267', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-07-01 12:37:45', '2025-07-01', '2025-07', '2025'),
(581, 'B00268', 'P0005', 'MINUMAN', 'kopi susu', 2, 2000, 5000, NULL, NULL, '2025-07-01 12:38:15', '2025-07-01', '2025-07', '2025'),
(582, 'B00269', 'P0001', 'MINUMAN', 'good day freeze', 2, 5000, 8000, NULL, NULL, '2025-07-01 12:39:14', '2025-07-01', '2025-07', '2025'),
(583, 'B00269', 'P0007', 'MAKANAN', 'Pisang Goreng', 1, 5000, 10000, NULL, NULL, '2025-07-01 12:39:14', '2025-07-01', '2025-07', '2025'),
(584, 'B00270', 'P00016', 'MAKANAN', 'Mie indomie rebus', 2, 3000, 10000, NULL, NULL, '2025-07-01 12:40:39', '2025-07-01', '2025-07', '2025'),
(585, 'B00270', 'P00010', 'MINUMAN', 'Drink Beng-Beng', 1, 4000, 8000, NULL, NULL, '2025-07-01 12:40:39', '2025-07-01', '2025-07', '2025'),
(586, 'B00270', 'P00014', 'MINUMAN', 'Chocolatos Coklat', 1, 3000, 7000, NULL, NULL, '2025-07-01 12:40:39', '2025-07-01', '2025-07', '2025'),
(587, 'B00271', 'P00011', 'MINUMAN', 'Susu Dancow Coklat', 2, 4000, 10000, NULL, NULL, '2025-07-01 12:41:13', '2025-07-01', '2025-07', '2025'),
(588, 'B00271', 'P00012', 'MINUMAN', 'Susu Dancow putih', 1, 4000, 10000, NULL, NULL, '2025-07-01 12:41:13', '2025-07-01', '2025-07', '2025'),
(589, 'B00272', 'P0008', 'MAKANAN', 'Roti Bakar', 1, 4000, 12000, NULL, NULL, '2025-07-01 12:42:03', '2025-07-01', '2025-07', '2025'),
(590, 'B00272', 'P0009', 'MINUMAN', 'Abc Klepon', 1, 3000, 7000, NULL, NULL, '2025-07-01 12:42:03', '2025-07-01', '2025-07', '2025'),
(591, 'B00273', 'P00013', 'MINUMAN', 'Chocolatos Macha', 1, 3000, 7000, NULL, NULL, '2025-07-03 02:34:07', '2025-07-03', '2025-07', '2025'),
(592, 'B00273', 'P00014', 'MINUMAN', 'Chocolatos Coklat', 1, 3000, 7000, NULL, NULL, '2025-07-03 02:34:07', '2025-07-03', '2025-07', '2025'),
(593, 'B00274', 'P00010', 'MINUMAN', 'Drink Beng-Beng', 1, 4000, 8000, NULL, NULL, '2025-07-03 02:34:33', '2025-07-03', '2025-07', '2025'),
(594, 'B00274', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-07-03 02:34:33', '2025-07-03', '2025-07', '2025'),
(595, 'B00275', 'P00015', 'MAKANAN', 'Mie indomie goreng', 1, 3000, 10000, NULL, NULL, '2025-07-03 02:35:00', '2025-07-03', '2025-07', '2025'),
(596, 'B00275', 'P0001', 'MINUMAN', 'good day freeze', 1, 5000, 8000, NULL, NULL, '2025-07-03 02:35:00', '2025-07-03', '2025-07', '2025'),
(597, 'B00276', 'P0002', 'MINUMAN', 'Nutrisari All Varian rasa', 3, 2000, 5000, NULL, NULL, '2025-07-03 02:35:28', '2025-07-03', '2025-07', '2025'),
(598, 'B00276', 'P0006', 'MAKANAN', 'Kentang Goreng', 1, 4000, 10000, NULL, NULL, '2025-07-03 02:35:28', '2025-07-03', '2025-07', '2025'),
(599, 'B00277', 'P0005', 'MINUMAN', 'kopi susu', 2, 2000, 5000, NULL, NULL, '2025-07-03 02:36:00', '2025-07-03', '2025-07', '2025'),
(600, 'B00277', 'P0007', 'MAKANAN', 'Pisang Goreng', 1, 5000, 10000, NULL, NULL, '2025-07-03 02:36:00', '2025-07-03', '2025-07', '2025'),
(601, 'B00278', 'P0005', 'MINUMAN', 'kopi susu', 2, 2000, 5000, NULL, NULL, '2025-07-03 02:36:22', '2025-07-03', '2025-07', '2025'),
(602, 'B00279', 'P00012', 'MINUMAN', 'Susu Dancow putih', 1, 4000, 10000, NULL, NULL, '2025-07-03 02:36:47', '2025-07-03', '2025-07', '2025'),
(603, 'B00279', 'P00011', 'MINUMAN', 'Susu Dancow Coklat', 1, 4000, 10000, NULL, NULL, '2025-07-03 02:36:47', '2025-07-03', '2025-07', '2025'),
(604, 'B00279', 'P0008', 'MAKANAN', 'Roti Bakar', 1, 4000, 12000, NULL, NULL, '2025-07-03 02:36:47', '2025-07-03', '2025-07', '2025'),
(605, 'B00280', 'P0009', 'MINUMAN', 'Abc Klepon', 2, 3000, 7000, NULL, NULL, '2025-07-03 02:37:19', '2025-07-03', '2025-07', '2025'),
(606, 'B00280', 'P00016', 'MAKANAN', 'Mie indomie rebus', 1, 3000, 10000, NULL, NULL, '2025-07-03 02:37:19', '2025-07-03', '2025-07', '2025'),
(607, 'B00281', 'P0004', 'MINUMAN', 'Es Teh Manis', 4, 2000, 4000, NULL, NULL, '2025-07-03 02:37:52', '2025-07-03', '2025-07', '2025'),
(608, 'B00281', 'P0006', 'MAKANAN', 'Kentang Goreng', 2, 4000, 10000, NULL, NULL, '2025-07-03 02:37:52', '2025-07-03', '2025-07', '2025'),
(609, 'B00282', 'P00014', 'MINUMAN', 'Chocolatos Coklat', 1, 3000, 7000, NULL, NULL, '2025-07-05 02:24:17', '2025-07-05', '2025-07', '2025');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keuangan_lainnya`
--
ALTER TABLE `keuangan_lainnya`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keuangan_ledger`
--
ALTER TABLE `keuangan_ledger`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_stok`
--
ALTER TABLE `menu_stok`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profil_toko`
--
ALTER TABLE `profil_toko`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi_produk`
--
ALTER TABLE `transaksi_produk`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2003;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `menu_stok`
--
ALTER TABLE `menu_stok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `profil_toko`
--
ALTER TABLE `profil_toko`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=283;

--
-- AUTO_INCREMENT for table `transaksi_produk`
--
ALTER TABLE `transaksi_produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=610;

--
-- AUTO_INCREMENT for table `keuangan_lainnya`
--
ALTER TABLE `keuangan_lainnya`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keuangan_ledger`
--
ALTER TABLE `keuangan_ledger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
