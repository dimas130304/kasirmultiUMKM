<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title_web }} &mdash; KASIR MULTI-UMKM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
        body {
            font-family: 'Outfit', sans-serif;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white;
                color: black;
            }
            .print-card {
                border: none !important;
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen py-8 px-4 flex flex-col items-center">

    <!-- Actions (no print) -->
    <div class="no-print mb-6 flex flex-col sm:flex-row gap-3 w-full max-w-md">
        <button onclick="window.print()" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-teal-600/25 transition active:scale-95 flex items-center justify-center gap-2 text-xs">
            <i class="fa fa-print"></i> Cetak Struk
        </button>
        
        @php
            // Clean WA phone number
            $phone = $profil->telepon_toko ?? '';
            $phoneClean = preg_replace('/\D/', '', $phone);
            if ($phoneClean && strpos($phoneClean, '0') === 0) {
                $phoneClean = '62' . substr($phoneClean, 1);
            }
            
            // Generate WA template text
            $itemsText = "";
            foreach($items as $i) {
                $itemsText .= "- " . $i->nama_menu . " (x" . $i->qty . ")\n";
            }
            
            $waMessage = "Halo *" . $umkm->nama_umkm . "*, saya ingin melakukan konfirmasi pembayaran pesanan:\n\n"
                       . "• *No. Bon*: " . $transaksi->no_bon . "\n"
                       . "• *Atas Nama*: " . $transaksi->atas_nama . "\n"
                       . "• *Tipe Order*: " . ($transaksi->pesanan === 'dine-in' ? 'Makan di Tempat' : 'Bawa Pulang') . "\n"
                       . "• *Total Tagihan*: Rp " . number_format($transaksi->grandtotal, 0, ',', '.') . "\n"
                       . "• *Status*: Belum Lunas (Bayar Nanti)\n\n"
                       . "*Detail Pesanan*:\n" . $itemsText . "\n"
                       . "Mohon segera diproses ya. Terima kasih!";
            
            $waUrl = "https://wa.me/" . $phoneClean . "?text=" . urlencode($waMessage);
        @endphp

        @if($phoneClean)
            <a href="{{ $waUrl }}" target="_blank" class="bg-emerald-600 hover:bg-emerald-750 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-emerald-600/20 transition active:scale-95 flex items-center justify-center gap-2 text-xs text-center">
                <i class="fa fa-whatsapp text-lg"></i> Konfirmasi ke Kasir (WhatsApp)
            </a>
        @endif
        
        <a href="{{ route('landing.umkm-menu', $umkm->kode_umkm) }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold py-2.5 px-5 rounded-xl transition active:scale-95 text-center text-xs">
            Kembali ke Menu
        </a>
    </div>

    <!-- Receipt Card -->
    <div class="print-card bg-white border border-slate-200 rounded-3xl shadow-xl p-6 sm:p-8 max-w-md w-full relative overflow-hidden">
        <!-- Top color bar -->
        <div class="absolute top-0 left-0 right-0 h-3 bg-gradient-to-r from-teal-500 via-teal-600 to-teal-700"></div>

        <!-- Receipt Header -->
        <div class="text-center pb-5 border-b border-dashed border-slate-200 mt-4">
            <h1 class="font-black text-lg text-slate-800 tracking-wide">{{ $umkm->nama_umkm }}</h1>
            <p class="text-xs text-slate-500 mt-1 max-w-[280px] mx-auto">{{ $profil->alamat_toko ?? $umkm->alamat_umkm }}</p>
            <p class="text-[10px] text-slate-400 mt-1">Telp: {{ $profil->telepon_toko ?? $umkm->telepon }}</p>
        </div>

        <!-- Order Info Grid -->
        <div class="py-5 grid grid-cols-2 gap-y-3.5 gap-x-2 text-xs border-b border-dashed border-slate-200">
            <div>
                <span class="block text-slate-400 text-[10px] uppercase font-bold tracking-wider">No. Bon / Invoice</span>
                <span class="font-mono font-bold text-slate-800">{{ $transaksi->no_bon }}</span>
            </div>
            <div class="text-right">
                <span class="block text-slate-400 text-[10px] uppercase font-bold tracking-wider">Atas Nama</span>
                <span class="font-semibold text-slate-800">{{ $transaksi->atas_nama }}</span>
            </div>
            <div>
                <span class="block text-slate-400 text-[10px] uppercase font-bold tracking-wider">Tipe Pesanan</span>
                <span class="font-semibold text-slate-800 flex items-center gap-1">
                    @if($transaksi->pesanan === 'dine-in')
                        <i class="fa fa-cutlery text-teal-500 text-[10px]"></i> Dine-In
                    @else
                        <i class="fa fa-shopping-basket text-teal-500 text-[10px]"></i> Takeaway
                    @endif
                </span>
            </div>
            <div class="text-right">
                <span class="block text-slate-400 text-[10px] uppercase font-bold tracking-wider">Waktu / Tanggal</span>
                <span class="text-slate-600 font-semibold">{{ $transaksi->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="col-span-2">
                <span class="block text-slate-400 text-[10px] uppercase font-bold tracking-wider">Status Pemesanan</span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 mt-1 rounded-full bg-amber-50 border border-amber-200 text-amber-700 font-extrabold text-[10px] uppercase tracking-wider">
                    <i class="fa fa-clock-o text-[11px] animate-spin"></i> {{ $transaksi->status }}
                </span>
            </div>
        </div>

        <!-- Items Ordered -->
        <div class="py-5 border-b border-dashed border-slate-200">
            <h4 class="text-slate-400 text-[10px] uppercase font-bold tracking-wider mb-4">Rincian Item</h4>
            <div class="space-y-4">
                @foreach($items as $i)
                    <div class="flex justify-between items-start text-xs gap-3">
                        <div class="min-w-0 flex-1">
                            <span class="font-bold text-slate-800 block truncate">{{ $i->nama_menu }}</span>
                            <span class="text-[10px] text-slate-400 block mt-0.5" x-text="">
                                {{ $i->qty }} x Rp {{ number_format($i->harga_jual, 0, ',', '.') }}
                                @if($i->keterangan)
                                    <span class="italic text-teal-500 font-normal">({{ $i->keterangan }})</span>
                                @endif
                            </span>
                        </div>
                        <span class="font-bold text-slate-700 flex-shrink-0">
                            Rp {{ number_format($i->harga_jual * $i->qty, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Calculations -->
        <div class="py-5 border-b border-dashed border-slate-200 text-xs space-y-2">
            @php
                $subtotal = $items->sum(fn($it) => $it->harga_jual * $it->qty);
            @endphp
            <div class="flex justify-between text-slate-500">
                <span>Subtotal</span>
                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            @if($transaksi->diskon > 0)
                <div class="flex justify-between text-green-600 font-semibold">
                    <span>Diskon</span>
                    <span>-Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</span>
                </div>
            @endif
            @if($transaksi->pajak > 0)
                <div class="flex justify-between text-slate-500">
                    <span>Pajak</span>
                    <span>+Rp {{ number_format($transaksi->pajak, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="flex justify-between font-black text-slate-800 text-sm border-t border-slate-100 pt-3 mt-1.5">
                <span>Total Bayar</span>
                <span class="text-teal-600">Rp {{ number_format($transaksi->grandtotal, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Payment Reference (if any bank details are configured) -->
        @if($profil && $profil->nama_bank && $profil->no_rekening)
            <div class="bg-teal-50/50 rounded-2xl p-4 border border-teal-100/30 mt-6 text-xs no-print">
                <h5 class="font-bold text-teal-800 flex items-center gap-1.5 mb-2">
                    <i class="fa fa-credit-card"></i> Informasi Transfer Bank
                </h5>
                <div class="space-y-1 text-slate-700 font-medium">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Bank:</span>
                        <span class="font-bold text-slate-800">{{ $profil->nama_bank }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Nomor Rekening:</span>
                        <span class="font-mono font-bold text-slate-800">{{ $profil->no_rekening }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Atas Nama:</span>
                        <span class="font-bold text-slate-800">{{ $profil->atas_nama_rekening }}</span>
                    </div>
                    @if($profil->catatan_pembayaran)
                        <div class="border-t border-teal-100/50 pt-1.5 mt-1.5 text-[10px] text-slate-500 italic">
                            * {{ $profil->catatan_pembayaran }}
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="bg-amber-50 border border-amber-200 text-amber-800 text-[10px] rounded-2xl p-3.5 mt-6 text-center">
                <i class="fa fa-info-circle text-xs mb-1 block"></i>
                Silakan lakukan pembayaran tunai secara langsung di kasir / meja pelayanan.
            </div>
        @endif

        <!-- Footer Note -->
        <div class="text-center text-[10px] text-slate-400 mt-6 pt-2 border-t border-slate-100">
            <p>{{ $profil->footer_struk ?? 'Terima kasih atas pesanan Anda!' }}</p>
            <p class="mt-1 font-semibold text-slate-500">KASIR MULTI-UMKM &mdash; Multi-UMKM Platform</p>
        </div>
    </div>

</body>
</html>
