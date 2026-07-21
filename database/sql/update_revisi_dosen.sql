-- =============================================================
-- UPDATE REVISI SESUAI BIMBINGAN DOSEN
-- Jalankan file ini di phpMyAdmin pada database kasir
-- =============================================================

USE `kasir`;

-- 1. Tambah kolom bulan_data di tabel apriori_hasil
ALTER TABLE `apriori_hasil`
    ADD COLUMN IF NOT EXISTS `bulan_data` varchar(7) DEFAULT NULL COMMENT 'Format Y-m, bulan sumber data (contoh: 2025-05)';

-- 2. Buat tabel apriori_aktif (rule yang sedang diterapkan untuk rekomendasi)
CREATE TABLE IF NOT EXISTS `apriori_aktif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `apriori_hasil_id` int(11) NOT NULL COMMENT 'ID dari apriori_hasil yang sedang aktif',
  `diterapkan_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu rule ini diterapkan',
  PRIMARY KEY (`id`),
  KEY `fk_apriori_aktif_hasil` (`apriori_hasil_id`),
  CONSTRAINT `fk_apriori_aktif_hasil` FOREIGN KEY (`apriori_hasil_id`) REFERENCES `apriori_hasil` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
  COMMENT='Menyimpan satu rule apriori yang sedang aktif diterapkan sebagai rekomendasi di halaman kasir';

-- =============================================================
-- CATATAN PERUBAHAN LAIN (tidak perlu SQL, sudah di kode):
-- - Form Register: hapus username, pakai email sebagai login
-- - Form Register: tambah nama_umkm & alamat_umkm
-- - Form Login: label Username -> Email
-- - Kasir: hapus opsi "Booking" di Tipe Order
-- - Menu sidebar: "Customer" -> "Kategori Customer"
-- - Laporan: tambah tombol Export Excel & Export PDF
-- - Apriori Rule: tambah pilihan bulan data
-- - Apriori Hasil: tambah tombol Terapkan, badge AKTIF
-- - Kasir produk: muncul modal rekomendasi apriori sebelum tambah ke keranjang
-- =============================================================
