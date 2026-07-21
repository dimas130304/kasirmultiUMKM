@extends('layouts.app')
@section('content')
<div class="space-y-4">
    <x-page-card title="Algoritma Apriori — Export Dataset" icon="fa-sitemap">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold text-slate-800 mb-2">Export Data Transaksi</h3>
                <p class="text-sm text-slate-500 mb-4">Pilih rentang tanggal transaksi yang ingin dianalisis menggunakan algoritma Apriori.</p>
                <form action="{{ route('algoritma.proses') }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-medium text-slate-500">Tanggal Awal</label>
                            <input type="date" name="tgl_awal" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-slate-500">Tanggal Akhir</label>
                            <input type="date" name="tgl_akhir" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 rounded-lg bg-teal-600 text-white px-4 py-2 text-sm font-medium hover:bg-teal-700 transition">
                            <i class="fa fa-download mr-1"></i> Export Dataset
                        </button>
                        <button type="button" onclick="confirmReset()" class="rounded-lg bg-slate-200 text-slate-700 px-4 py-2 text-sm font-medium hover:bg-slate-300 transition">
                            <i class="fa fa-refresh"></i>
                        </button>
                    </div>
                </form>
                <form id="formReset" action="{{ route('algoritma.reset') }}" method="POST" class="hidden">@csrf</form>
            </div>
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 flex flex-col justify-center items-center text-center">
                <div class="w-12 h-12 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center mb-3">
                    <i class="fa fa-database text-xl"></i>
                </div>
                <h4 class="font-bold text-slate-800 text-lg">{{ number_format(count($dataset)) }}</h4>
                <p class="text-sm text-slate-500 mb-4">Total Transaksi di Dataset</p>
                <div class="flex gap-2">
                    <a href="{{ route('algoritma.rule') }}" class="rounded-lg bg-teal-600 text-white px-4 py-2 text-sm font-medium">Lanjut ke Perhitungan</a>
                    <a href="{{ route('algoritma.hasil') }}" class="rounded-lg bg-slate-700 text-white px-4 py-2 text-sm font-medium">Lihat Hasil</a>
                </div>
            </div>
        </div>
    </x-page-card>

    <x-page-card title="Daftar Dataset" icon="fa-table">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-4 py-2 text-left">No.</th>
                        <th class="px-4 py-2 text-left">Tanggal</th>
                        <th class="px-4 py-2 text-left">No. Transaksi</th>
                        <th class="px-4 py-2 text-left">Produk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($dataset as $i => $row)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-2 text-slate-500">{{ $i + 1 }}</td>
                            <td class="px-4 py-2">{{ $row->tgl_transaksi }}</td>
                            <td class="px-4 py-2 font-mono text-xs">{{ $row->no_transaksi }}</td>
                            <td class="px-4 py-2">
                                <div class="flex flex-wrap gap-1">
                                    @foreach (explode(',', $row->dataset) as $item)
                                        <span class="bg-teal-50 text-teal-700 px-2 py-0.5 rounded text-[10px] border border-teal-100">{{ $item }}</span>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-400">
                                <i class="fa fa-info-circle mb-2 text-2xl"></i>
                                <p>Belum ada data di dataset. Silakan export data terlebih dahulu.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-page-card>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmReset() {
    Swal.fire({
        title: 'Kosongkan Dataset?',
        text: "Semua data transaksi yang sudah diexport akan dihapus!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0d9488',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Kosongkan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formReset').submit();
        }
    });
}
</script>
@endpush
@endsection
