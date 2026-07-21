@foreach ($hasil as $r)
<div class="rounded-xl border border-slate-200 bg-white overflow-hidden hover:shadow-lg hover:border-teal-300 transition group">
    <button type="button" class="pilih w-full text-left"
        data-id="{{ $r->id }}"
        data-nama="{{ $r->nama }}"
        data-harga="{{ $r->harga_jual }}"
        data-gambar="{{ $r->gambar }}">
        <div class="aspect-[4/3] bg-slate-100 flex items-center justify-center overflow-hidden">
            @if ($r->gambar && $r->gambar !== '-' && $r->gambar !== '')
                <img src="{{ asset('assets/image/produk/'.$r->gambar) }}" alt="{{ $r->nama }}" class="w-full h-full object-cover group-hover:scale-105 transition" onerror="this.onerror=null;this.src='{{ asset('assets/image/no_screenshot.png') }}';">
            @else
                <i class="fa fa-image text-4xl text-slate-300"></i>
            @endif
        </div>
        <div class="p-3">
            <p class="text-xs text-teal-600 font-medium">{{ $r->nama_kategori ?? $r->kategori }}</p>
            <p class="font-semibold text-slate-800 text-sm line-clamp-2">{{ $r->nama }}</p>
            <p class="text-emerald-600 font-bold mt-1">Rp {{ number_format($r->harga_jual, 0, ',', '.') }}</p>
            <p class="text-xs text-slate-500 mt-1">Stok: {{ $r->stok }}x</p>
        </div>
    </button>
</div>
@endforeach

{{-- Modal Rekomendasi Apriori --}}
<div id="modalRekomendasi" class="fixed inset-0 z-[80] hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="tutupModalRek()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="relative bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-md p-0 shadow-2xl relative z-10 transition-transform duration-300 transform translate-y-0 max-h-[90vh] overflow-hidden flex flex-col">
            {{-- Big Product Banner at the Top --}}
            <div class="relative w-full h-52 bg-slate-100 shrink-0">
                <img id="rekBannerImg" class="w-full h-full object-cover hidden">
                <div id="rekBannerPlaceholder" class="w-full h-full bg-gradient-to-br from-teal-50 to-emerald-50 flex items-center justify-center text-teal-600">
                    <i class="fa fa-image text-5xl opacity-40"></i>
                </div>
                {{-- Close Button --}}
                <button onclick="tutupModalRek()" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-black/40 text-white flex items-center justify-center hover:bg-black/60 transition shadow">
                    <i class="fa fa-times text-base"></i>
                </button>
            </div>

            {{-- Product Info Section --}}
            <div class="px-5 py-4 border-b border-slate-100 bg-white">
                <span class="bg-teal-50 text-teal-700 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider mb-1 inline-block">Produk dipilih</span>
                <h3 class="font-extrabold text-xl text-slate-900 leading-tight" id="rekNamaProduk">—</h3>
                <p class="text-emerald-600 font-extrabold text-base mt-1" id="rekHargaProduk">—</p>
            </div>

            <div class="p-5 overflow-y-auto custom-scrollbar bg-slate-50/50">
                {{-- Rekomendasi --}}
                <div id="rekLoadingArea" class="text-center py-6 text-slate-400 hidden">
                    <i class="fa fa-spinner fa-spin text-2xl mb-2"></i>
                    <p class="text-sm">Mengambil rekomendasi...</p>
                </div>

                <div id="rekKosong" class="hidden text-center py-4">
                    <i class="fa fa-info-circle text-slate-300 text-2xl mb-2"></i>
                    <p class="text-sm text-slate-400">Tidak ada rekomendasi untuk produk ini.</p>
                </div>

                <div id="rekArea" class="hidden">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">
                        <i class="fa fa-lightbulb-o text-amber-500 mr-1 text-base"></i>
                        Pembeli lain biasanya juga membeli:
                    </p>
                    <div id="rekList" class="space-y-3"></div>
                    <p class="text-[10px] text-slate-400 mt-3 text-center" id="rekInfoBulan"></p>
                </div>
            </div>

            <div class="px-5 pb-5 pt-4 border-t border-slate-100 flex gap-3 shrink-0 bg-white shadow-[0_-10px_20px_rgba(0,0,0,0.02)]">
                <button id="btnTambahLangsung"
                    class="flex-1 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 transition"
                    onclick="tambahProdukLangsung()">
                    <i class="fa fa-cart-plus mr-2"></i> Tambah ke Keranjang
                </button>
                <button onclick="tutupModalRek()" class="px-5 py-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition font-medium">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Logika dipindahkan ke index.blade.php untuk efisiensi & menghindari bug redeclaring
</script>
