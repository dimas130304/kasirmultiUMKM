@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- Header Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Revenue Today --}}
        <a href="{{ route('laporan.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition group cursor-pointer">
            <div class="h-12 w-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl group-hover:bg-teal-600 group-hover:text-white transition">
                <i class="fa fa-money"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Pendapatan Hari Ini</p>
                <p class="text-xl font-bold text-slate-900">Rp {{ number_format($stats['pendapatan_hari_ini'], 0, ',', '.') }}</p>
            </div>
        </a>

        {{-- Sales Today --}}
        <a href="{{ route('order.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition group cursor-pointer">
            <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl group-hover:bg-blue-600 group-hover:text-white transition">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Transaksi Hari Ini</p>
                <p class="text-xl font-bold text-slate-900">{{ number_format($stats['transaksi_hari_ini']) }}</p>
            </div>
        </a>

        {{-- Total Menu --}}
        <a href="{{ route('menu.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition group cursor-pointer">
            <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl group-hover:bg-amber-600 group-hover:text-white transition">
                <i class="fa fa-cubes"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Produk</p>
                <p class="text-xl font-bold text-slate-900">{{ number_format($stats['total_menu']) }}</p>
            </div>
        </a>

        {{-- Total Revenue --}}
        <a href="{{ route('laporan.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition group cursor-pointer">
            <div class="h-12 w-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl group-hover:bg-indigo-600 group-hover:text-white transition">
                <i class="fa fa-line-chart"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Omzet</p>
                <p class="text-xl font-bold text-slate-900">Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</p>
            </div>
        </a>
    </div>

    {{-- Chart & Best Sellers --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa fa-bar-chart text-teal-600"></i> Grafik Penjualan
                </h3>
                <form action="{{ route('home') }}" method="GET" class="flex gap-2">
                    <select name="thn" onchange="this.form.submit()" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium outline-none focus:ring-2 focus:ring-teal-500/20">
                        @foreach ($years as $y)
                            <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="p-6">
                <div class="h-[300px]">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa fa-trophy text-amber-500"></i> Produk Terlaris
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    @forelse ($bestSellers as $i => $item)
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-full bg-slate-50 flex items-center justify-center text-sm font-bold text-slate-400">
                                #{{ $i + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ $item->nama_menu }}</p>
                                <p class="text-xs text-slate-500">{{ number_format($item->total_qty) }} terjual</p>
                            </div>
                            <div class="h-1.5 w-16 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-teal-500" style="width: {{ min(100, ($item->total_qty / max(1, $bestSellers[0]->total_qty)) * 100) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400">
                            <i class="fa fa-info-circle mb-2 text-2xl"></i>
                            <p class="text-sm">Belum ada data</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fa fa-history text-blue-600"></i> Transaksi Terbaru
            </h3>
            <a href="{{ route('laporan.index') }}" class="text-xs font-bold text-teal-600 hover:text-teal-700">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-widest font-bold">
                    <tr>
                        <th class="px-6 py-3 text-left">No. Bon</th>
                        <th class="px-6 py-3 text-left">Customer</th>
                        <th class="px-6 py-3 text-left">Kasir</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($recentTransactions as $row)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-mono text-xs text-slate-600">{{ $row->no_bon }}</td>
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $row->atas_nama ?? 'Umum' }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $row->kasir->nama_user ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $row->status == 'Lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $row->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-slate-900">Rp {{ number_format($row->grandtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Belum ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(13, 148, 136, 0.2)');
    gradient.addColorStop(1, 'rgba(13, 148, 136, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Pendapatan',
                data: {!! json_encode($chartData) !!},
                borderColor: '#0d9488',
                backgroundColor: gradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#0d9488',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 12,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                    ticks: {
                        font: { size: 11 },
                        callback: function(value) {
                            if (value >= 1000000) return 'Rp ' + (value/1000000) + 'jt';
                            if (value >= 1000) return 'Rp ' + (value/1000) + 'rb';
                            return 'Rp ' + value;
                        }
                    }
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { font: { size: 11 } }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
