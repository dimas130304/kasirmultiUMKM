<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Code Stand - {{ $umkm->nama_umkm }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white;
                color: black;
            }
            .page-card {
                border: none !important;
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }
        }
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap');
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex flex-col items-center justify-center p-4">

    <!-- Floating Action Button for print (hidden during print) -->
    <div class="no-print mb-6 flex gap-3">
        <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-indigo-600/25 transition active:scale-95 flex items-center gap-2">
            <i class="fa fa-print"></i> Cetak QR Code
        </button>
        <button onclick="window.close()" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold py-2.5 px-5 rounded-xl transition active:scale-95">
            Tutup Halaman
        </button>
    </div>

    <!-- Stand Card Container -->
    <div class="page-card bg-white border border-slate-200 rounded-3xl shadow-2xl p-8 max-w-sm w-full text-center relative overflow-hidden flex flex-col items-center">
        <!-- Top decorative badge/shape -->
        <div class="absolute top-0 left-0 right-0 h-4 bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-600"></div>
        
        <div class="mt-4 mb-2">
            <span class="inline-flex items-center gap-1 bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                <i class="fa fa-shopping-bag"></i> {{ $umkm->nama_umkm }}
            </span>
        </div>

        <h1 class="text-xl font-extrabold text-slate-800 leading-snug">
            SCAN UNTUK PESAN
        </h1>
        <p class="text-xs text-slate-500 mt-1 max-w-[240px] mx-auto">
            Pesan langsung dari meja Anda tanpa harus mengantre di kasir.
        </p>

        <!-- QR Code Frame -->
        <div class="my-6 p-4 bg-slate-50 rounded-2xl border border-slate-100 shadow-inner relative group">
            @php
                $menuUrl = route('landing.umkm-menu', $umkm->kode_umkm);
                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($menuUrl);
            @endphp
            <img src="{{ $qrUrl }}" alt="QR Code Menu" class="w-56 h-56 mx-auto bg-white rounded-lg p-1">
        </div>

        <!-- Instructions -->
        <div class="w-full text-left bg-slate-50 rounded-2xl p-4 border border-slate-100 mb-4">
            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5 text-center">Petunjuk Pemesanan:</h4>
            <div class="space-y-2 text-xs text-slate-600">
                <div class="flex gap-2">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-white font-bold text-[10px] flex-shrink-0">1</span>
                    <span>Pindai/scan QR Code di atas menggunakan kamera HP Anda.</span>
                </div>
                <div class="flex gap-2">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-white font-bold text-[10px] flex-shrink-0">2</span>
                    <span>Pilih menu favorit Anda dan klik tombol keranjang.</span>
                </div>
                <div class="flex gap-2">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-white font-bold text-[10px] flex-shrink-0">3</span>
                    <span>Tinjau keranjang, lihat rekomendasi pelengkap, dan checkout.</span>
                </div>
            </div>
        </div>

        <!-- Branding Footer -->
        <div class="border-t border-slate-100 pt-4 w-full flex justify-between items-center text-[10px] text-slate-400">
            <span>Kode Toko: <strong class="font-mono text-slate-600">{{ $umkm->kode_umkm }}</strong></span>
            <span>Powered by <strong class="text-indigo-600">KASIR MULTI-UMKM</strong></span>
        </div>
    </div>

</body>
</html>
