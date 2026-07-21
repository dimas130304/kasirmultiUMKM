@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
    <div class="bg-gradient-to-r from-brand-navy to-brand-navy-mid px-6 py-8 text-white">
        <h1 class="text-2xl font-bold flex items-center gap-2">
            <i class="fa fa-sign-in text-teal-300"></i> Masuk
        </h1>
        <p class="mt-1 text-sm text-slate-300">Masuk ke sistem KASIR MULTI UMKM</p>
    </div>
    <div class="px-6 py-8">
        <form method="POST" action="{{ route('login.proses') }}" class="space-y-5">
            @csrf
            <div>
                <label for="user" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="user" id="user" value="{{ old('user') }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-slate-800 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 outline-none transition"
                    placeholder="Masukkan email" required autocomplete="email" autofocus>
            </div>
            <div>
                <label for="pass" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input type="password" name="pass" id="pass"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-slate-800 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 outline-none transition"
                    placeholder="Masukkan password" required autocomplete="current-password">
            </div>
            <button type="submit"
                class="w-full rounded-lg bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3 px-4 shadow-lg shadow-teal-600/25 transition flex items-center justify-center gap-2">
                <i class="fa fa-unlock-alt"></i> Masuk ke Sistem
            </button>
        </form>
    </div>
    <div class="border-t border-slate-100 bg-slate-50 px-6 py-4 text-center text-sm text-slate-600">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-teal-600 hover:text-teal-700">Daftar di sini</a>
    </div>
@endsection
