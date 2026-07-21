@extends('layouts.app')
@section('content')
<x-page-card title="Produk Terlaris — Bulan Ini" icon="fa-star">
    <div class="overflow-x-auto rounded-xl border border-slate-200">
        <table class="w-full text-sm">
            <thead class="bg-slate-800 text-white">
                <tr>
                    <th class="px-3 py-2 text-left">#</th>
                    <th class="px-3 py-2 text-left">Kode</th>
                    <th class="px-3 py-2 text-left">Nama Produk</th>
                    <th class="px-3 py-2 text-left">Kategori</th>
                    <th class="px-3 py-2 text-center">Qty Terjual</th>
                    <th class="px-3 py-2 text-right">Total Penjualan</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($terlaris as $i => $row)
                <tr class="border-t hover:bg-slate-50">
                    <td class="px-3 py-2">{{ $i + 1 }}</td>
                    <td class="px-3 py-2 font-mono text-xs">{{ $row->kode_menu }}</td>
                    <td class="px-3 py-2 font-medium">{{ $row->nama_menu }}</td>
                    <td class="px-3 py-2">{{ $row->kategori }}</td>
                    <td class="px-3 py-2 text-center font-bold text-teal-700">{{ $row->total_qty }}</td>
                    <td class="px-3 py-2 text-right">Rp {{ number_format($row->total_jual, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-3 py-8 text-center text-slate-500">Belum ada data penjualan bulan ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <a href="{{ route('laporan.produk') }}" class="inline-block mt-4 text-teal-600 text-sm"><i class="fa fa-arrow-left"></i> History per produk</a>
</x-page-card>
@endsection
