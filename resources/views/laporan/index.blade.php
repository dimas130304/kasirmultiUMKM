@extends('layouts.app')
@section('content')
<x-page-card title="Laporan Transaksi Penjualan" icon="fa-bar-chart">
    <div class="flex flex-wrap gap-2 mb-4">
        <form method="GET" class="flex flex-wrap gap-2 text-sm flex-1">
            <input type="date" name="dari" value="{{ request('dari') }}" class="rounded-lg border px-3 py-2">
            <input type="date" name="sampai" value="{{ request('sampai') }}" class="rounded-lg border px-3 py-2">
            <button class="rounded-lg bg-teal-600 text-white px-4 py-2"><i class="fa fa-search mr-1"></i> Filter</button>
            <a href="{{ route('laporan.index') }}" class="rounded-lg bg-slate-200 text-slate-700 px-4 py-2"><i class="fa fa-refresh"></i></a>
        </form>
        <a href="{{ route('laporan.export-excel', request()->only(['dari','sampai'])) }}"
            class="rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 text-sm font-medium flex items-center gap-1">
            <i class="fa fa-file-excel-o"></i> Download Excel
        </a>
        <button onclick="window.print()"
            class="rounded-lg bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-sm font-medium flex items-center gap-1">
            <i class="fa fa-print"></i> Print / PDF
        </button>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
        <div class="bg-teal-50 border border-teal-100 rounded-xl p-3 text-center">
            <p class="text-xs text-teal-600 font-medium">Total Transaksi</p>
            <p class="text-xl font-black text-teal-800">{{ $orders->total() }}</p>
        </div>
        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-3 text-center">
            <p class="text-xs text-emerald-600 font-medium">Total Pendapatan</p>
            <p class="text-sm font-black text-emerald-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 text-center">
            <p class="text-xs text-blue-600 font-medium">Lunas</p>
            <p class="text-xl font-black text-blue-800">{{ $jumlahLunas }}</p>
        </div>
        <div class="bg-amber-50 border border-amber-100 rounded-xl p-3 text-center">
            <p class="text-xs text-amber-600 font-medium">Bayar Nanti</p>
            <p class="text-xl font-black text-amber-800">{{ $jumlahBelumLunas }}</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-3 py-3 text-center">Tanggal</th>
                    <th class="px-3 py-3 text-center">No Bon</th>
                    <th class="px-3 py-3 text-center">Customer</th>
                    <th class="px-3 py-3 text-center">Kasir</th>
                    <th class="px-3 py-3 text-center">Tipe</th>
                    <th class="px-3 py-3 text-center">Status</th>
                    <th class="px-3 py-3 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @forelse ($orders as $o)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-3 py-3 text-center">{{ $o->date }}</td>
                    <td class="px-3 py-3 text-center font-mono text-xs">{{ $o->no_bon }}</td>
                    <td class="px-3 py-3 text-center">{{ $o->nama_customer ?? 'Umum' }}</td>
                    <td class="px-3 py-3 text-center text-slate-600">{{ $o->nama_user }}</td>
                    <td class="px-3 py-3 text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-blue-100 text-blue-800">
                            {{ $o->pesanan ?? 'Ditempat' }}
                        </span>
                    </td>
                    <td class="px-3 py-3 text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $o->status == 'Lunas' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ $o->status }}
                        </span>
                    </td>
                    <td class="px-3 py-3 text-right font-bold text-slate-800">Rp {{ number_format($o->grandtotal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-10 text-center text-slate-400">
                        <i class="fa fa-inbox text-2xl mb-2"></i>
                        <p>Belum ada data transaksi.</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $orders->withQueryString()->links() }}</div>
</x-page-card>
@endsection
