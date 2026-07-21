@extends('layouts.app')
@section('content')
<x-page-card :title="$title_web" icon="fa-user-plus">
    <form method="POST" action="{{ $user ? route('users.upd') : route('users.add') }}" class="max-w-2xl space-y-6">
        @csrf
        @if ($user)<input type="hidden" name="id" value="{{ $user->id }}">@endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    <i class="fa fa-user text-slate-400 mr-2"></i>
                    Nama Lengkap
                    <span class="text-red-500 font-medium">*</span>
                </label>
                <input name="nama" value="{{ old('nama', $user->nama_user ?? '') }}" required placeholder="Masukkan nama lengkap pengguna" class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-slate-800 text-base focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all duration-200 placeholder:text-slate-400">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    <i class="fa fa-lock text-slate-400 mr-2"></i>
                    Password
                    @if($user)
                        <span class="text-slate-400 font-normal text-xs">(kosongkan jika tidak diubah)</span>
                    @else
                        <span class="text-red-500 font-medium">*</span>
                    @endif
                </label>
                <input type="password" name="pass" {{ $user ? '' : 'required' }} placeholder="Masukkan password minimal 6 karakter" class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-slate-800 text-base focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all duration-200 placeholder:text-slate-400">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    <i class="fa fa-shield text-slate-400 mr-2"></i>
                    Level Pengguna
                    <span class="text-red-500 font-medium">*</span>
                </label>
                <select name="level" required class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-slate-800 text-base focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all duration-200 bg-white">
                    <option value="" class="text-slate-400">— Pilih Level —</option>
                    <option value="Admin" @selected(old('level', $user->level ?? '') === 'Admin')>Admin (Dapat mengelola semua data)</option>
                    <option value="Kasir" @selected(old('level', $user->level ?? '') === 'Kasir')>Kasir (Hanya dapat melakukan transaksi)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    <i class="fa fa-envelope text-slate-400 mr-2"></i>
                    Email
                    <span class="text-red-500 font-medium">*</span>
                </label>
                <input name="email" type="email" value="{{ old('email', $user->email ?? '') }}" required placeholder="nama@email.com" class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-slate-800 text-base focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all duration-200 placeholder:text-slate-400">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    <i class="fa fa-phone text-slate-400 mr-2"></i>
                    Nomor Telepon
                </label>
                <input name="telepon" value="{{ old('telepon', $user->telepon ?? '') }}" placeholder="0812-3456-7890" class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-slate-800 text-base focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all duration-200 placeholder:text-slate-400">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    <i class="fa fa-map-marker text-slate-400 mr-2"></i>
                    Alamat Lengkap
                </label>
                <textarea name="alamat" placeholder="Masukkan alamat lengkap (opsional)" rows="3" class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-slate-800 text-base focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all duration-200 resize-none placeholder:text-slate-400">{{ old('alamat', $user->alamat ?? '') }}</textarea>
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full group relative overflow-hidden rounded-2xl bg-gradient-to-r from-teal-600 via-teal-500 to-emerald-500 text-white font-bold px-8 py-4 shadow-xl shadow-teal-600/30 transition-all duration-300 hover:shadow-2xl hover:-translate-y-0.5 active:scale-[0.98]">
                <span class="relative flex items-center justify-center gap-2 text-lg">
                    <i class="fa fa-save"></i>
                    Simpan Pengguna
                </span>
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 via-teal-500 to-teal-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </button>
        </div>
    </form>
</x-page-card>
@endsection
