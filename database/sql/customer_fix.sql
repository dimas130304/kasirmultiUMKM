-- Perbaikan tabel customer (database: kasir)
-- Jalankan di phpMyAdmin atau: mysql -u root -p kasir < customer_fix.sql

USE `kasir`;

-- Opsi A: tabel belum ada / boleh dihapus (data customer hilang)
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `customer` (`id`, `kode_customer`, `nama`, `alamat`, `hp`, `keterangan`, `created_at`) VALUES
(5, 'C0001', 'online', 'Jakarta', '628', '', '2025-05-27 16:34:20'),
(6, 'C0006', 'umum', 'ujung harapan', '628996803889', '', '2025-06-22 18:32:28');

ALTER TABLE `customer` AUTO_INCREMENT = 7;
