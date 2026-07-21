-- Tabel algoritma Apriori (sama seperti project teamilk)
-- Jalankan di database kasir via phpMyAdmin

USE `kasir`;

CREATE TABLE IF NOT EXISTS `apriori_dataset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl_transaksi` varchar(255) DEFAULT NULL,
  `no_transaksi` varchar(255) DEFAULT NULL,
  `dataset` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `apriori_hasil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keterangan` text DEFAULT NULL,
  `tgl_proses` timestamp NULL DEFAULT NULL,
  `min_support` varchar(11) NOT NULL,
  `min_confidence` varchar(11) NOT NULL,
  `total_data` int(11) NOT NULL,
  `data_rules` longtext DEFAULT NULL,
  `data_hasil` longtext DEFAULT NULL,
  `waktu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `apriori_hasil_dataset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl_transaksi` varchar(255) DEFAULT NULL,
  `no_transaksi` varchar(255) DEFAULT NULL,
  `dataset` text DEFAULT NULL,
  `id_hasil` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tambahan untuk fitur penerapan per bulan
ALTER TABLE `apriori_hasil` ADD COLUMN IF NOT EXISTS `bulan_data` varchar(20) DEFAULT NULL COMMENT 'Format Y-m, bulan sumber data yang dianalisis';

CREATE TABLE IF NOT EXISTS `apriori_aktif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `apriori_hasil_id` int(11) NOT NULL,
  `diterapkan_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `apriori_hasil_id` (`apriori_hasil_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Menyimpan rule apriori yang sedang aktif diterapkan';

-- Jalankan ini jika database sudah ada (update existing)
ALTER TABLE `apriori_hasil` MODIFY COLUMN `bulan_data` varchar(20) DEFAULT NULL;
