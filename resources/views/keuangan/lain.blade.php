@extends('layouts.app')
@section('content')
<x-page-card title="Keuangan Lainnya" icon="fa-money">
    <div class="flex flex-wrap gap-2 mb-4">
        <a href="{{ route('keuangan.excel') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 text-sm font-medium transition-colors">
            <i class="fa fa-file-excel-o"></i> Download Excel
        </a>
    </div>
    <div class="overflow-x-auto rounded-xl border border-slate-200">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Keterangan</th>
                    <th class="px-3 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Pemasukan</th>
                    <th class="px-3 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengeluaran</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @forelse ($rows as $r)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-3 py-2.5">{{ $r->nama_urusan ?? $r->keterangan ?? '-' }}</td>
                    <td class="px-3 py-2.5 text-right text-emerald-600 font-medium">{{ $r->jumlah_masuk > 0 ? 'Rp ' . number_format($r->jumlah_masuk) : '-' }}</td>
                    <td class="px-3 py-2.5 text-right text-red-600 font-medium">{{ $r->jumlah_keluar > 0 ? 'Rp ' . number_format($r->jumlah_keluar) : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-3 py-10 text-center text-slate-400">
                        <i class="fa fa-inbox text-2xl mb-2 block"></i> Belum ada data
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $rows->links() }}</div>
</x-page-card>
@endsection
