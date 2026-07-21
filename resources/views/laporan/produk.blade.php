@extends('layouts.app')

@section('content')
<x-page-card :title="'History Per Produk — ' . $periode" icon="fa-cubes">
    <div class="flex flex-wrap gap-2 mb-4">
        <button type="button" onclick="document.getElementById('filterModal').classList.remove('hidden')"
            class="rounded-lg bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 text-sm font-medium">
            <i class="fa fa-search"></i> Pencarian
        </button>
        <a href="{{ route('laporan.produk-excel', array_filter(['a' => $filterA, 'b' => $filterB])) }}"
            class="rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 text-sm font-medium inline-flex items-center gap-1">
            <i class="fa fa-file-excel-o"></i> Download Excel
        </a>
        <a href="{{ route('laporan.produk') }}" class="rounded-lg bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 text-sm font-medium">
            <i class="fa fa-refresh"></i> Refresh
        </a>
        <a href="{{ route('laporan.index') }}" class="rounded-lg bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 text-sm font-medium">
            <i class="fa fa-arrow-left"></i> Laporan Transaksi
        </a>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200">
        <table class="w-full text-sm">
            <thead class="bg-slate-800 text-white">
                <tr>
                    <th class="px-3 py-2 text-left">No</th>
                    <th class="px-3 py-2 text-left">Kode</th>
                    <th class="px-3 py-2 text-left">Nama Produk</th>
                    <th class="px-3 py-2 text-left">Kategori</th>
                    <th class="px-3 py-2 text-center">Qty</th>
                    <th class="px-3 py-2 text-right">Harga Beli</th>
                    <th class="px-3 py-2 text-right">Harga Jual</th>
                    <th class="px-3 py-2 text-left">Atas Nama</th>
                    <th class="px-3 py-2 text-left">Customer</th>
                    <th class="px-3 py-2 text-left">Jenis</th>
                    <th class="px-3 py-2 text-left">Status</th>
                    <th class="px-3 py-2 text-left">Tanggal</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($items as $i => $row)
                <tr class="border-t border-slate-100 hover:bg-slate-50">
                    <td class="px-3 py-2">{{ $items->firstItem() + $i }}</td>
                    <td class="px-3 py-2 font-mono text-xs">{{ $row->kode_menu }}</td>
                    <td class="px-3 py-2 font-medium">{{ $row->nama_menu }}</td>
                    <td class="px-3 py-2">{{ $row->kategori }}</td>
                    <td class="px-3 py-2 text-center">{{ $row->qty }}</td>
                    <td class="px-3 py-2 text-right">Rp {{ number_format($row->harga_beli * $row->qty, 0, ',', '.') }}</td>
                    <td class="px-3 py-2 text-right">Rp {{ number_format($row->harga_jual * $row->qty, 0, ',', '.') }}</td>
                    <td class="px-3 py-2">{{ $row->atas_nama ?? '-' }}</td>
                    <td class="px-3 py-2">{{ ($row->customer_id ?? 0) == 0 ? '-' : ($row->nama_customer ?? '-') }}</td>
                    <td class="px-3 py-2">{{ $row->pesanan ?? '-' }}</td>
                    <td class="px-3 py-2">
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs">{{ $row->status ?? '-' }}</span>
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap">{{ $row->date }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="px-3 py-12 text-center text-slate-500">Tidak ada data untuk periode ini.</td>
                </tr>
            @endforelse
            </tbody>
            <tfoot class="bg-teal-50 font-semibold">
                <tr>
                    <td colspan="4" class="px-3 py-3 text-right">Total</td>
                    <td class="px-3 py-3 text-center">{{ number_format($total->qty ?? 0) }}</td>
                    <td class="px-3 py-3 text-right">Rp {{ number_format($total->hb ?? 0, 0, ',', '.') }}</td>
                    <td class="px-3 py-3 text-right">Rp {{ number_format($total->hj ?? 0, 0, ',', '.') }}</td>
                    <td colspan="2" class="px-3 py-3">Keuntungan</td>
                    <td colspan="3" class="px-3 py-3 text-teal-700">
                        Rp {{ number_format(($total->hj ?? 0) - ($total->hb ?? 0), 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="mt-4">{{ $items->links() }}</div>
</x-page-card>

{{-- Modal filter --}}
<div id="filterModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="bg-gradient-to-r from-teal-600 to-teal-500 px-5 py-3 text-white font-semibold flex justify-between items-center">
            <span><i class="fa fa-search"></i> Pencarian Data</span>
            <button type="button" onclick="document.getElementById('filterModal').classList.add('hidden')" class="text-white/80 hover:text-white">
                <i class="fa fa-times"></i>
            </button>
        </div>
        <form method="GET" action="{{ route('laporan.produk') }}" class="p-5 space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="a" value="{{ $filterA }}" required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Akhir</label>
                <input type="date" name="b" value="{{ $filterB }}" required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="document.getElementById('filterModal').classList.add('hidden')"
                    class="rounded-lg bg-slate-200 text-slate-700 px-4 py-2 text-sm">Tutup</button>
                <button type="submit" class="rounded-lg bg-teal-600 text-white px-4 py-2 text-sm font-medium">Cari</button>
            </div>
        </form>
    </div>
</div>
@endsection
