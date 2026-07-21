@extends('layouts.app')
@section('content')
<div class="space-y-4">
    <div class="flex items-center gap-4 mb-2">
        <a href="{{ route('algoritma.hasil') }}" class="w-10 h-10 rounded-full bg-white shadow-sm border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-50 transition">
            <i class="fa fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-slate-800">Detail Hasil Perhitungan</h2>
            <p class="text-xs text-slate-500">Dihasilkan pada {{ $row->tgl_proses->format('d F Y, H:i') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-1 space-y-4">
            <x-page-card title="Parameter & Info" icon="fa-info-circle">
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                        <span class="text-sm text-slate-500">Periode Data</span>
                        <span class="font-bold text-slate-800">{{ $row->nama_bulan ?? $row->bulan_data ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                        <span class="text-sm text-slate-500">Min. Support</span>
                        <span class="font-bold text-teal-600">{{ $row->min_support }}%</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                        <span class="text-sm text-slate-500">Min. Confidence</span>
                        <span class="font-bold text-teal-600">{{ $row->min_confidence }}%</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                        <span class="text-sm text-slate-500">Total Transaksi</span>
                        <span class="font-bold text-slate-800">{{ number_format($row->total_data) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-500">Waktu Eksekusi</span>
                        <span class="font-mono text-xs text-slate-600">{{ $row->waktu }}</span>
                    </div>
                </div>
            </x-page-card>

            <div class="bg-gradient-to-br from-teal-600 to-teal-700 rounded-2xl p-6 text-white shadow-lg shadow-teal-600/20">
                <h4 class="font-bold mb-2">Kesimpulan</h4>
                <p class="text-sm text-teal-50 leading-relaxed">
                    Berdasarkan analisis terhadap {{ $row->total_data }} transaksi, ditemukan {{ count(json_decode($row->data_rules)) }} aturan asosiasi yang memenuhi kriteria support {{ $row->min_support }}% and confidence {{ $row->min_confidence }}%.
                </p>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-4">
            <x-page-card title="Association Rules (Aturan Asosiasi)" icon="fa-lightbulb-o">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-3 text-left">Jika Membeli (Antecedent)</th>
                                <th class="px-4 py-3 text-center w-10"></th>
                                <th class="px-4 py-3 text-left">Maka Membeli (Consequent)</th>
                                <th class="px-4 py-3 text-center">Support</th>
                                <th class="px-4 py-3 text-center">Confidence</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @php $rules = json_decode($row->data_rules); @endphp
                            @forelse ($rules as $rule)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-4">
                                        <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-lg font-medium border border-slate-200">
                                            {{ $rule->if }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <i class="fa fa-arrow-right text-teal-500"></i>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="bg-teal-50 text-teal-700 px-3 py-1 rounded-lg font-medium border border-teal-200">
                                            {{ $rule->then }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center font-bold text-slate-600">
                                        {{ $rule->support }}%
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                            {{ $rule->confidence }}%
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-12 text-center text-slate-400">
                                        <i class="fa fa-times-circle mb-2 text-2xl"></i>
                                        <p>Tidak ada aturan yang memenuhi kriteria.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-page-card>
        </div>
    </div>
</div>
@endsection
