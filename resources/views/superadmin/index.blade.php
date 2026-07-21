@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Manajemen UMKM</h1>
            <p class="text-sm text-slate-500">Kelola pendaftaran, status keaktifan, dan detail UMKM yang terdaftar di sistem.</p>
        </div>
        <a href="{{ route('superadmin.create') }}" 
            class="rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-5 py-3 shadow-lg shadow-indigo-600/20 transition flex items-center gap-2 transform active:scale-95">
            <i class="fa fa-plus-circle"></i> Tambah UMKM Baru
        </a>
    </div>

    {{-- Stats Widgets --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa fa-building"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total UMKM</p>
                <div class="flex items-baseline gap-2">
                    <p class="text-xl font-black text-slate-800">{{ $stats['total_umkm'] }}</p>
                    <p class="text-[10px] text-emerald-600 font-bold"><i class="fa fa-caret-up"></i> {{ $stats['new_this_month'] }} baru</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fa fa-money"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Omzet Platform</p>
                <p class="text-xl font-black text-slate-800">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Transaksi</p>
                <div class="flex items-baseline gap-2">
                    <p class="text-xl font-black text-slate-800">{{ number_format($stats['total_transaksi'], 0, ',', '.') }}</p>
                    <p class="text-[10px] text-blue-600 font-bold">{{ $stats['transaksi_today'] }} hari ini</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                <i class="fa fa-users"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Pengguna</p>
                <p class="text-xl font-black text-slate-800">{{ number_format($stats['total_users'], 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <x-page-card title="Daftar Tenant UMKM" icon="fa-building">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-3 text-center w-12">No</th>
                                <th class="px-4 py-3 text-left">Kode</th>
                                <th class="px-4 py-3 text-left">Nama UMKM</th>
                                <th class="px-4 py-3 text-left">Kontak & User</th>
                                <th class="px-4 py-3 text-center">Transaksi</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($umkmList as $i => $row)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-4 text-center text-slate-400 font-medium">{{ $i + 1 }}</td>
                                    <td class="px-4 py-4 font-mono font-bold text-slate-600">{{ $row->kode_umkm }}</td>
                                    <td class="px-4 py-4">
                                        <div class="font-bold text-slate-800">{{ $row->nama_umkm }}</div>
                                        <div class="text-[10px] text-slate-400 flex items-center gap-1 mt-0.5">
                                            <i class="fa fa-user"></i> {{ $row->nama_pemilik }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-xs text-slate-600">{{ $row->email }}</div>
                                        <div class="text-[10px] text-slate-400 mt-1">
                                            <span class="bg-slate-100 px-1.5 py-0.5 rounded text-slate-600 font-bold">{{ $row->users_count }} User</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="font-black text-slate-700">{{ $row->transaksi_count }}</div>
                                        <div class="text-[10px] text-slate-400 uppercase font-bold">Order</div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        @if($row->status === 'aktif')
                                            <span class="bg-emerald-100 text-emerald-800 text-[10px] px-2 py-0.5 rounded-full font-black uppercase">Aktif</span>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-[10px] px-2 py-0.5 rounded-full font-black uppercase">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('superadmin.detail', $row->id) }}" class="h-8 w-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition shadow-sm" title="Detail">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('superadmin.edit', $row->id) }}" class="h-8 w-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition shadow-sm" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('superadmin.toggle-status', $row->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="h-8 w-8 rounded-lg {{ $row->status === 'aktif' ? 'bg-red-50 text-red-600 hover:bg-red-600' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-600' }} flex items-center justify-center hover:text-white transition shadow-sm" title="{{ $row->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="fa {{ $row->status === 'aktif' ? 'fa-ban' : 'fa-check' }}"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                             @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-12 text-center text-slate-400 italic">Belum ada UMKM terdaftar</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-page-card>
        </div>

        <div class="space-y-6">
            <x-page-card title="Top Performance" icon="fa-trophy">
                <div class="space-y-4">
                    @forelse($topUmkm as $idx => $top)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="h-8 w-8 rounded-lg {{ $idx == 0 ? 'bg-amber-100 text-amber-600' : ($idx == 1 ? 'bg-slate-200 text-slate-600' : 'bg-orange-100 text-orange-600') }} flex items-center justify-center font-black text-sm">
                                {{ $idx + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-slate-800 truncate text-sm">{{ $top->nama_umkm }}</p>
                                <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">Rp {{ number_format($top->transaksi_sum_grandtotal ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-black text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full font-bold">TOP</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-xs text-slate-400 py-4 italic">Belum ada data transaksi</p>
                    @endforelse
                </div>
            </x-page-card>
        </div>
    </div>
</div>
@endsection
