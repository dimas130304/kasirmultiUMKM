@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto">
    <x-page-card title="Penentuan Rule Apriori" icon="fa-cog">
        <div class="mb-6">
            <h3 class="font-semibold text-slate-800 mb-1">Parameter Algoritma</h3>
            <p class="text-sm text-slate-500">Tentukan nilai minimum support dan confidence, serta bulan data yang akan dianalisis.</p>
        </div>

        @if ($total_data > 0)
            <form action="{{ route('algoritma.hitung') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Pilihan Mode Periode --}}
                <div class="space-y-4">
                    <label class="block text-sm font-medium text-slate-700">Periode Analisis <span class="text-red-500">*</span></label>
                    <div class="flex p-1 bg-slate-100 rounded-xl w-fit">
                        <button type="button" id="btnSingle" onclick="setMode('single')" 
                            class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all duration-200 bg-white shadow-sm text-teal-600">
                            Satu Bulan
                        </button>
                        <button type="button" id="btnRange" onclick="setMode('range')" 
                            class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all duration-200 text-slate-500 hover:text-slate-700">
                            Rentang Bulan
                        </button>
                    </div>
                    <input type="hidden" name="mode_periode" id="mode_periode" value="single">

                    {{-- Input Satu Bulan --}}
                    <div id="areaSingle" class="space-y-2">
                        <div class="relative">
                            <input type="month" name="bulan" value="{{ old('bulan', now()->format('Y-m')) }}"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition">
                        </div>
                        <p class="text-[11px] text-slate-400 italic">* Analisis data transaksi pada satu bulan spesifik.</p>
                    </div>

                    {{-- Input Rentang Bulan --}}
                    <div id="areaRange" class="hidden space-y-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Mulai</label>
                                <input type="month" name="bulan_mulai" id="bulan_mulai" value="{{ old('bulan_mulai', now()->subMonths(5)->format('Y-m')) }}"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Hingga</label>
                                <input type="month" name="bulan_akhir" id="bulan_akhir" value="{{ old('bulan_akhir', now()->format('Y-m')) }}"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition">
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-400 italic">* Analisis data akumulasi dari bulan awal hingga bulan akhir.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">Min. Support (%)</label>
                        <div class="relative">
                            <input type="number" name="min_support" value="{{ old('min_support', 10) }}" min="0.1" max="100" step="0.1" required
                                class="w-full rounded-xl border border-slate-300 pl-4 pr-12 py-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition">
                            <span class="absolute right-4 top-3.5 text-slate-400 font-bold">%</span>
                        </div>
                        <p class="text-[11px] text-slate-400 italic">* Minimal kemunculan produk dalam seluruh transaksi. Semakin tinggi = semakin ketat.</p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">Min. Confidence (%)</label>
                        <div class="relative">
                            <input type="number" name="min_confidence" value="{{ old('min_confidence', 50) }}" min="0.1" max="100" step="0.1" required
                                class="w-full rounded-xl border border-slate-300 pl-4 pr-12 py-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition">
                            <span class="absolute right-4 top-3.5 text-slate-400 font-bold">%</span>
                        </div>
                        <p class="text-[11px] text-slate-400 italic">* Kekuatan hubungan antar produk. Semakin besar = semakin yakin relasinya.</p>
                    </div>
                </div>

                <div class="bg-blue-50 rounded-xl p-4 border border-blue-100 text-xs text-blue-800 leading-relaxed space-y-1">
                    <p><strong>Panduan Support & Confidence:</strong></p>
                    <p>• <strong>Support tinggi, Confidence rendah</strong> → banyak rule tapi kurang akurat</p>
                    <p>• <strong>Support rendah, Confidence tinggi</strong> → sedikit rule tapi lebih tepat sasaran</p>
                    <p>• Rekomendasi awal: Support <strong>10%</strong>, Confidence <strong>50%</strong></p>
                </div>

                <div class="bg-teal-50 rounded-xl p-4 border border-teal-100 flex items-start gap-3">
                    <i class="fa fa-info-circle text-teal-600 mt-0.5"></i>
                    <div class="text-xs text-teal-800 leading-relaxed">
                        Sistem akan menganalisis <strong>{{ number_format($total_data) }}</strong> transaksi yang ada di dataset.
                        Setelah selesai, Anda dapat meninjau hasilnya dan memutuskan apakah akan <strong>diterapkan</strong> untuk bulan berikutnya.
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                    <button type="submit" class="flex-1 rounded-xl bg-teal-600 text-white font-bold py-3 shadow-lg shadow-teal-600/30 hover:bg-teal-700 transition transform active:scale-[0.98]">
                        <i class="fa fa-play mr-2"></i> Jalankan Perhitungan
                    </button>
                    <a href="{{ route('algoritma.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-medium hover:bg-slate-50 transition">
                        Batal
                    </a>
                </div>
            </form>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa fa-database text-slate-400 text-2xl"></i>
                </div>
                <h4 class="font-bold text-slate-800">Dataset Kosong</h4>
                <p class="text-sm text-slate-500 mb-6">Silakan export data transaksi terlebih dahulu sebelum melakukan perhitungan.</p>
                <a href="{{ route('algoritma.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-teal-600 text-white px-6 py-2.5 font-medium hover:bg-teal-700 transition">
                    <i class="fa fa-arrow-left"></i> Kembali ke Export
                </a>
            </div>
        @endif
    </x-page-card>
</div>
@endsection

@push('scripts')
<script>
    function setMode(mode) {
        const btnSingle = document.getElementById('btnSingle');
        const btnRange = document.getElementById('btnRange');
        const areaSingle = document.getElementById('areaSingle');
        const areaRange = document.getElementById('areaRange');
        const modeInput = document.getElementById('mode_periode');

        modeInput.value = mode;

        if (mode === 'single') {
            btnSingle.classList.add('bg-white', 'shadow-sm', 'text-teal-600');
            btnSingle.classList.remove('text-slate-500', 'hover:text-slate-700');
            
            btnRange.classList.remove('bg-white', 'shadow-sm', 'text-teal-600');
            btnRange.classList.add('text-slate-500', 'hover:text-slate-700');
            
            areaSingle.classList.remove('hidden');
            areaRange.classList.add('hidden');
        } else {
            btnRange.classList.add('bg-white', 'shadow-sm', 'text-teal-600');
            btnRange.classList.remove('text-slate-500', 'hover:text-slate-700');
            
            btnSingle.classList.remove('bg-white', 'shadow-sm', 'text-teal-600');
            btnSingle.classList.add('text-slate-500', 'hover:text-slate-700');
            
            areaRange.classList.remove('hidden');
            areaSingle.classList.add('hidden');
        }
    }

    // Validasi form sebelum submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const mode = document.getElementById('mode_periode').value;
        if (mode === 'range') {
            const mulai = document.getElementById('bulan_mulai').value;
            const akhir = document.getElementById('bulan_akhir').value;
            
            if (mulai > akhir) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Rentang Tidak Valid',
                    text: 'Bulan awal tidak boleh lebih besar dari bulan akhir.'
                });
            }
        }
    });
</script>
@endpush
