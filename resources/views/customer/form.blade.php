@extends('layouts.app')
@section('content')
<x-page-card :title="$title_web" icon="fa-user">
    <form method="POST" action="{{ $customer ? route('customer.update') : route('customer.store') }}" class="max-w-2xl space-y-6">
        @csrf
        @if ($customer)<input type="hidden" name="id" value="{{ $customer->id }}">@endif

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">
                <i class="fa fa-user-o text-slate-400 mr-2"></i>
                Nama Customer
                <span class="text-red-500 font-medium">*</span>
            </label>
            <input name="nama" value="{{ old('nama', $customer->nama ?? '') }}" required placeholder="Masukkan nama customer" class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-slate-800 text-base focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all duration-200 placeholder:text-slate-400">
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">
                <i class="fa fa-mobile text-slate-400 mr-2"></i>
                Nomor Handphone
            </label>
            <input name="hp" value="{{ old('hp', $customer->hp ?? '') }}" placeholder="0812-3456-7890" class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-slate-800 text-base focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all duration-200 placeholder:text-slate-400">
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">
                <i class="fa fa-map text-slate-400 mr-2"></i>
                Alamat Lengkap
            </label>
            <textarea name="alamat" placeholder="Masukkan alamat lengkap customer (opsional)" rows="4" class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-slate-800 text-base focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all duration-200 resize-none placeholder:text-slate-400">{{ old('alamat', $customer->alamat ?? '') }}</textarea>
        </div>

        <div class="pt-4 flex gap-4">
            <button type="submit" class="flex-1 group relative overflow-hidden rounded-2xl bg-gradient-to-r from-teal-600 via-teal-500 to-emerald-500 text-white font-bold px-8 py-4 shadow-xl shadow-teal-600/30 transition-all duration-300 hover:shadow-2xl hover:-translate-y-0.5 active:scale-[0.98]">
                <span class="relative flex items-center justify-center gap-2 text-lg">
                    <i class="fa fa-save"></i>
                    Simpan Customer
                </span>
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 via-teal-500 to-teal-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </button>
            <a href="{{ route('customer.index') }}" class="flex-1 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-8 py-4 text-center transition-all duration-200 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </form>
</x-page-card>
@endsection
