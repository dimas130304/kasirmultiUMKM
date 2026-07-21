# Panduan Export Excel & PDF

Aplikasi ini sudah dilengkapi dengan fitur export otomatis ke Excel dan PDF yang langsung terdownload ke file.

## 📊 Fitur Export

### 1. Laporan Penjualan
- **URL**: `/laporan`
- **Export Excel**: Klik tombol "Export Excel" → File CSV otomatis terdownload
- **Export PDF**: Klik tombol "Export PDF" → File PDF otomatis terdownload
- **Format**: CSV (kompatibel dengan Excel, LibreOffice, Google Sheets)
- **Nama File**: `laporan-transaksi-YYYYMMDD_HHmmss.csv` dan `.pdf`

### 2. History Per Produk
- **URL**: `/laporan/produk`
- **Export Excel**: Klik tombol "Export Excel" → File CSV otomatis terdownload
- **Format**: CSV dengan detail per produk
- **Nama File**: `history-produk-YYYYMMDD_HHmmss.csv`

### 3. Cash Flow / Arus Kas
- **URL**: `/laporan/cash`
- **Export Excel**: Klik tombol "Export Excel" → File CSV otomatis terdownload
- **Export PDF**: Klik tombol "Export PDF" → File PDF otomatis terdownload
- **Nama File**: `cashflow-YYYY-MM.csv` dan `.pdf`

### 4. Keuangan Lainnya
- **URL**: `/admin/keuangan/lain`
- **Export Excel**: Klik tombol "Export Excel" → File CSV otomatis terdownload
- **Nama File**: `keuangan-lainnya-YYYYMMDD_HHmmss.csv`

## 📥 Cara Download

### Di Browser
1. Klik tombol **"Export Excel"** atau **"Export PDF"**
2. File otomatis terdownload ke folder Downloads Anda
3. Buka file dengan Excel, LibreOffice, atau aplikasi office lainnya

### Format File
- **Excel/CSV**: Dapat dibuka di MS Excel, Google Sheets, LibreOffice Calc
- **PDF**: Dapat dibuka di Adobe Reader, browser, atau viewer PDF lainnya
- **Encoding**: UTF-8 dengan BOM (agar Excel membaca karakter Indonesia dengan benar)

## 🎯 Fitur Filtering

Sebelum export, Anda bisa:
- **Filter Periode**: Pilih tanggal "Dari" dan "Sampai" untuk membatasi data
- **Filter per Bulan**: Untuk Cash Flow, pilih bulan dan tahun
- Export akan otomatis mengikuti filter yang dipilih

## 💾 Lokasi File

File akan tersimpan di:
- **Windows**: `C:\Users\[NamaUser]\Downloads\`
- **Mac**: `~/Downloads/`
- **Linux**: `~/Downloads/` atau sesuai pengaturan browser

## 🔧 Troubleshooting

### File tidak terdownload
1. Periksa pengaturan browser untuk download
2. Cek apakah popup blocker diaktifkan
3. Refresh browser dan coba lagi

### File terbuka di browser, tidak terdownload
- Ini terjadi pada file PDF di beberapa browser
- Gunakan **Ctrl+S** (Windows) atau **Cmd+S** (Mac) untuk save
- Atau klik **⋯ > Download** di browser

### Karakter Indonesia tidak terlihat
- File sudah menggunakan UTF-8 dengan BOM
- Pastikan aplikasi office Anda menggunakan encoding UTF-8
- Di Excel: File > Options > Advanced > Display Options

## 📝 Notes

- Data yang di-export adalah data yang sesuai dengan filter yang dipilih
- Timestamp pada nama file menunjukkan kapan export dilakukan
- File tidak disimpan di server, hanya terdownload langsung ke komputer Anda

---

Untuk informasi lebih lanjut, hubungi admin atau lihat dokumentasi aplikasi.
