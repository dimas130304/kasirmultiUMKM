@extends('layouts.guest')

@section('title', 'Registrasi')

@section('content')
    <div class="bg-gradient-to-r from-brand-navy to-brand-navy-mid px-6 py-8 text-white">
        <h1 class="text-2xl font-bold flex items-center gap-2">
            <i class="fa fa-user-plus text-teal-300"></i> Registrasi Akun UMKM
        </h1>
        <p class="mt-1 text-sm text-slate-300">Daftarkan UMKM Anda untuk mengakses sistem kasir</p>
    </div>
    <div class="px-6 py-8">
        <form method="POST" action="{{ route('register.proses') }}" class="space-y-4">
            @csrf

            {{-- Info UMKM --}}
            <div class="bg-teal-50 border border-teal-200 rounded-xl p-4 mb-2">
                <p class="text-xs font-bold text-teal-700 uppercase tracking-wider mb-3"><i class="fa fa-building mr-1"></i> Informasi UMKM</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="nama_umkm" class="block text-sm font-medium text-slate-700 mb-1">Nama UMKM <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_umkm" id="nama_umkm" value="{{ old('nama_umkm') }}"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 outline-none"
                            placeholder="Contoh: Warung Kopi Barokah" required>
                        @error('nama_umkm')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="alamat_umkm" class="block text-sm font-medium text-slate-700 mb-1">Alamat UMKM <span class="text-red-500">*</span></label>
                        <textarea name="alamat_umkm" id="alamat_umkm" rows="2" required
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 outline-none resize-none"
                            placeholder="Alamat lengkap UMKM">{{ old('alamat_umkm') }}</textarea>
                        @error('alamat_umkm')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Info Pemilik --}}
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                <p class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-3"><i class="fa fa-user mr-1"></i> Data Pemilik / Admin</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 outline-none"
                            placeholder="Nama pemilik" required>
                        @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="telepon" class="block text-sm font-medium text-slate-700 mb-1">No. Telepon <span class="text-red-500">*</span></label>
                        <input type="text" name="telepon" id="telepon" value="{{ old('telepon') }}"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 outline-none"
                            placeholder="08xxxxxxxxxx" required>
                        @error('telepon')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span> <span class="text-slate-400 text-xs">(digunakan untuk login)</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 outline-none"
                            placeholder="nama@email.com" required>
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="pass" class="block text-sm font-medium text-slate-700 mb-1">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="pass" id="pass"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 outline-none"
                            placeholder="Min. 6 karakter" required>
                        @error('pass')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="pass_confirm" class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                        <input type="password" name="pass_confirm" id="pass_confirm"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 outline-none"
                            placeholder="Ulangi password" required>
                        @error('pass_confirm')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <p class="text-xs text-slate-500">
                <i class="fa fa-info-circle text-teal-600"></i>
                Setelah daftar, Anda akan masuk sebagai <strong>Admin</strong> UMKM dan dapat menambahkan akun kasir dari menu manajemen pengguna.
            </p>
            <button type="submit"
                class="w-full rounded-lg bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3 px-4 shadow-lg shadow-teal-600/25 transition flex items-center justify-center gap-2">
                <i class="fa fa-check-circle"></i> Daftar Sekarang
            </button>
        </form>
    </div>
    <div class="border-t border-slate-100 bg-slate-50 px-6 py-4 text-center text-sm text-slate-600">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-teal-600 hover:text-teal-700">Masuk di sini</a>
    </div>
@endsection
