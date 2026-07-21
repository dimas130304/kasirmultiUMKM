@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <div class="flex items-center gap-3">
        <a href="{{ route('superadmin.index') }}" class="h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition flex items-center justify-center text-slate-600">
            <i class="fa fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight">Detail UMKM</h1>
            <p class="text-xs text-slate-500">Melihat profil detail dan daftar pengguna dari tenant.</p>
        </div>
    </div>

    {{-- Detail Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-teal-600 to-teal-500 px-6 py-5 text-white flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold tracking-[0.2em] uppercase text-teal-200">Tenant UMKM</span>
                <h2 class="text-xl font-bold">{{ $umkm->nama_umkm }}</h2>
            </div>
            <div class="px-3 py-1 rounded-full text-xs font-bold bg-white/10 border border-white/20">
                Status: <span class="capitalize">{{ $umkm->status }}</span>
            </div>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Pemilik / Penanggung Jawab</span>
                    <span class="text-slate-800 font-semibold text-sm">{{ $umkm->nama_pemilik ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Alamat Lengkap</span>
                    <span class="text-slate-700 text-sm leading-relaxed block mt-1">{{ $umkm->alamat_umkm ?? '-' }}</span>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Email Kontak</span>
                    <span class="text-slate-800 font-semibold text-sm">{{ $umkm->email ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">No. Telepon</span>
                    <span class="text-slate-800 font-semibold text-sm">{{ $umkm->telepon ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Pengguna Card --}}
    <x-page-card title="Daftar Pengguna / Karyawan" icon="fa-users">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Telepon</th>
                        <th class="px-4 py-3 text-center">Level / Role</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($umkm->users as $u)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3.5 font-semibold text-slate-800">{{ $u->nama_user }}</td>
                            <td class="px-4 py-3.5 text-slate-600">{{ $u->email }}</td>
                            <td class="px-4 py-3.5 text-slate-500">{{ $u->telepon ?? '-' }}</td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold {{ $u->level === 'Admin' ? 'bg-violet-100 text-violet-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $u->level }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-400">
                                <i class="fa fa-users mb-2 text-xl"></i>
                                <p class="text-sm">Belum ada pengguna terdaftar untuk UMKM ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-page-card>
</div>
@endsection
