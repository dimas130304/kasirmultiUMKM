-- ==========================================
-- REVISI MULTI-UMKM & APRIORI PER BULAN
-- ==========================================

USE `kasir`;

-- 1. Buat tabel umkm
CREATE TABLE IF NOT EXISTS `umkm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_umkm` varchar(255) NOT NULL,
  `alamat_umkm` text DEFAULT NULL,
  `nama_pemilik` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'aktif' COMMENT 'aktif, nonaktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tambah kolom email_login dan umkm_id ke tabel login
ALTER TABLE `login` ADD COLUMN IF NOT EXISTS `email_login` varchar(255) DEFAULT NULL;
ALTER TABLE `login` ADD COLUMN IF NOT EXISTS `umkm_id` int(11) DEFAULT NULL;

-- 3. Tambah kolom bulan, nama_bulan, dan diterapkan ke apriori_hasil
ALTER TABLE `apriori_hasil` ADD COLUMN IF NOT EXISTS `bulan` varchar(50) DEFAULT NULL;
ALTER TABLE `apriori_hasil` ADD COLUMN IF NOT EXISTS `nama_bulan` varchar(100) DEFAULT NULL;
ALTER TABLE `apriori_hasil` ADD COLUMN IF NOT EXISTS `diterapkan` tinyint(1) DEFAULT 0;

-- 4. Tambah kolom umkm_id ke semua tabel data
ALTER TABLE `apriori_aktif` ADD COLUMN IF NOT EXISTS `umkm_id` int(11) DEFAULT NULL;
ALTER TABLE `apriori_dataset` ADD COLUMN IF NOT EXISTS `umkm_id` int(11) DEFAULT NULL;
ALTER TABLE `apriori_hasil` ADD COLUMN IF NOT EXISTS `umkm_id` int(11) DEFAULT NULL;
ALTER TABLE `apriori_hasil_dataset` ADD COLUMN IF NOT EXISTS `umkm_id` int(11) DEFAULT NULL;
ALTER TABLE `customer` ADD COLUMN IF NOT EXISTS `umkm_id` int(11) DEFAULT NULL;
ALTER TABLE `kategori` ADD COLUMN IF NOT EXISTS `umkm_id` int(11) DEFAULT NULL;
ALTER TABLE `keranjang` ADD COLUMN IF NOT EXISTS `umkm_id` int(11) DEFAULT NULL;
ALTER TABLE `keuangan_lainnya` ADD COLUMN IF NOT EXISTS `umkm_id` int(11) DEFAULT NULL;
ALTER TABLE `keuangan_ledger` ADD COLUMN IF NOT EXISTS `umkm_id` int(11) DEFAULT NULL;
ALTER TABLE `menu` ADD COLUMN IF NOT EXISTS `umkm_id` int(11) DEFAULT NULL;
ALTER TABLE `menu_stok` ADD COLUMN IF NOT EXISTS `umkm_id` int(11) DEFAULT NULL;
ALTER TABLE `profil_toko` ADD COLUMN IF NOT EXISTS `umkm_id` int(11) DEFAULT NULL;
ALTER TABLE `transaksi` ADD COLUMN IF NOT EXISTS `umkm_id` int(11) DEFAULT NULL;
ALTER TABLE `transaksi_produk` ADD COLUMN IF NOT EXISTS `umkm_id` int(11) DEFAULT NULL;
