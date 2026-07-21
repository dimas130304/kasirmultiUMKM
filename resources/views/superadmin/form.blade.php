@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">
    <div class="flex items-center gap-3">
        <a href="{{ route('superadmin.index') }}" class="h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition flex items-center justify-center text-slate-600">
            <i class="fa fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight">{{ $umkm ? 'Edit UMKM' : 'Registrasi UMKM Baru' }}</h1>
            <p class="text-xs text-slate-500">{{ $umkm ? 'Perbarui data tenant UMKM.' : 'Daftarkan UMKM baru beserta akun administratornya.' }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6">
        <form method="POST" action="{{ $umkm ? route('superadmin.update', $umkm->id) : route('superadmin.store') }}" class="space-y-6">
            @csrf

            {{-- Informasi Toko --}}
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-teal-600 uppercase tracking-wider border-b border-slate-100 pb-2"><i class="fa fa-building mr-1"></i> Informasi Toko / UMKM</h3>
                
                <div class="space-y-1.5">
                    <label for="nama_umkm" class="block text-sm font-medium text-slate-700">Nama UMKM <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_umkm" id="nama_umkm" value="{{ old('nama_umkm', $umkm->nama_umkm ?? '') }}" required
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 outline-none transition"
                        placeholder="Masukkan nama UMKM">
                    @error('nama_umkm') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="alamat_umkm" class="block text-sm font-medium text-slate-700">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="alamat_umkm" id="alamat_umkm" rows="3" required
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 outline-none transition resize-none"
                        placeholder="Alamat lengkap lokasi toko">{{ old('alamat_umkm', $umkm->alamat_umkm ?? '') }}</textarea>
                    @error('alamat_umkm') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Informasi Pemilik --}}
            <div class="space-y-4 pt-2">
                <h3 class="text-sm font-bold text-teal-600 uppercase tracking-wider border-b border-slate-100 pb-2"><i class="fa fa-user mr-1"></i> Data Pemilik / Akun Utama</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="nama_pemilik" class="block text-sm font-medium text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pemilik" id="nama_pemilik" value="{{ old('nama_pemilik', $umkm->nama_pemilik ?? '') }}" required
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 outline-none transition"
                            placeholder="Nama pemilik / penanggung jawab">
                        @error('nama_pemilik') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="telepon" class="block text-sm font-medium text-slate-700">No. Telepon</label>
                        <input type="text" name="telepon" id="telepon" value="{{ old('telepon', $umkm->telepon ?? '') }}"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 outline-none transition"
                            placeholder="08xxxxxxxxxx">
                        @error('telepon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="email" class="block text-sm font-medium text-slate-700">Email Utama <span class="text-red-500">*</span> <span class="text-slate-400 text-xs">(Digunakan untuk login Admin)</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $umkm->email ?? '') }}" required
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 outline-none transition"
                        placeholder="email@toko.com">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                @if(!$umkm)
                    <div class="space-y-1.5">
                        <label for="admin_pass" class="block text-sm font-medium text-slate-700">Password Admin UMKM <span class="text-red-500">*</span></label>
                        <input type="password" name="admin_pass" id="admin_pass" required
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 outline-none transition"
                            placeholder="Minimal 6 karakter">
                        @error('admin_pass') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                @else
                    <div class="space-y-1.5">
                        <label for="status" class="block text-sm font-medium text-slate-700">Status Keaktifan</label>
                        <select name="status" id="status" required
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 outline-none transition">
                            <option value="aktif" @selected(old('status', $umkm->status) === 'aktif')>Aktif</option>
                            <option value="nonaktif" @selected(old('status', $umkm->status) === 'nonaktif')>Nonaktif</option>
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <button type="submit" 
                    class="flex-1 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 shadow-lg shadow-teal-600/20 transition flex items-center justify-center gap-2 transform active:scale-95">
                    <i class="fa fa-save"></i> Simpan UMKM
                </button>
                <a href="{{ route('superadmin.index') }}" 
                    class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
