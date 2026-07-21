@extends('layouts.app')
@section('content')
<x-page-card :title="$title_web" icon="fa-cube">
    <form method="POST" action="{{ $menu ? route('menu.update') : route('menu.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl">
        @csrf
        @if ($menu)<input type="hidden" name="id" value="{{ $menu->id }}">@endif

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">
                <i class="fa fa-qrcode text-slate-400 mr-2"></i>
                Kode Produk
                <span class="text-red-500 font-medium">*</span>
            </label>
            <input name="kode_menu" value="{{ old('kode_menu', $menu->kode_menu ?? $kode) }}" required placeholder="Contoh: KOPI001" class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-slate-800 text-base focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all duration-200 placeholder:text-slate-400">
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">
                <i class="fa fa-tags text-slate-400 mr-2"></i>
                Kategori
                <span class="text-red-500 font-medium">*</span>
            </label>
            <select name="id_kategori" required class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-slate-800 text-base focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all duration-200 bg-white">
                <option value="" class="text-slate-400">— Pilih Kategori —</option>
                @foreach ($kat as $k)
                    <option value="{{ $k->id }}" @selected(old('id_kategori', $menu->id_kategori ?? '') == $k->id)>{{ $k->kategori }}</option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 mb-2">
                <i class="fa fa-coffee text-slate-400 mr-2"></i>
                Nama Produk
                <span class="text-red-500 font-medium">*</span>
            </label>
            <input name="nama" value="{{ old('nama', $menu->nama ?? '') }}" required placeholder="Masukkan nama produk" class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-slate-800 text-base focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all duration-200 placeholder:text-slate-400">
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">
                <i class="fa fa-money text-slate-400 mr-2"></i>
                Harga Pokok
                <span class="text-red-500 font-medium">*</span>
            </label>
            <div class="relative">
                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-semibold">Rp</span>
                <input type="number" name="harga_pokok" value="{{ old('harga_pokok', $menu->harga_pokok ?? 0) }}" required placeholder="0" class="w-full rounded-2xl border-2 border-slate-200 pl-12 pr-5 py-3.5 text-slate-800 text-base focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all duration-200 placeholder:text-slate-400">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">
                <i class="fa fa-tag text-slate-400 mr-2"></i>
                Harga Jual
                <span class="text-red-500 font-medium">*</span>
            </label>
            <div class="relative">
                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-semibold">Rp</span>
                <input type="number" name="harga_jual" value="{{ old('harga_jual', $menu->harga_jual ?? 0) }}" required placeholder="0" class="w-full rounded-2xl border-2 border-slate-200 pl-12 pr-5 py-3.5 text-slate-800 text-base focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all duration-200 placeholder:text-slate-400">
            </div>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 mb-2">
                <i class="fa fa-file-text-o text-slate-400 mr-2"></i>
                Keterangan / Deskripsi
            </label>
            <textarea name="keterangan" placeholder="Deskripsikan produk Anda (opsional)" rows="4" class="w-full rounded-2xl border-2 border-slate-200 px-5 py-3.5 text-slate-800 text-base focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all duration-200 resize-none placeholder:text-slate-400">{{ old('keterangan', $menu->keterangan ?? '') }}</textarea>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 mb-2">
                <i class="fa fa-image text-slate-400 mr-2"></i>
                Gambar Produk
            </label>
            <div class="border-3 border-dashed border-slate-200 rounded-3xl p-8 text-center hover:border-teal-400 transition-all duration-200 bg-gradient-to-br from-slate-50 to-slate-100">
                <div class="flex flex-col items-center gap-3">
                    <div class="h-16 w-16 rounded-2xl bg-teal-100 flex items-center justify-center text-teal-600 text-3xl">
                        <i class="fa fa-cloud-upload"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-700">Klik atau seret gambar ke sini</p>
                        <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG. Ukuran maks: 2MB</p>
                    </div>
                </div>
                <input type="file" name="gambar" accept="image/*" class="mt-4 block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-2xl file:border-0 file:text-sm file:font-bold file:bg-teal-100 file:text-teal-700 hover:file:bg-teal-200 cursor-pointer transition-all duration-200">
            </div>
        </div>

        <div class="md:col-span-2 flex gap-4 pt-4">
            <button type="submit" class="flex-1 group relative overflow-hidden rounded-2xl bg-gradient-to-r from-teal-600 via-teal-500 to-emerald-500 text-white font-bold px-8 py-4 shadow-xl shadow-teal-600/30 transition-all duration-300 hover:shadow-2xl hover:-translate-y-0.5 active:scale-[0.98]">
                <span class="relative flex items-center justify-center gap-2 text-lg">
                    <i class="fa fa-save"></i>
                    Simpan Produk
                </span>
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 via-teal-500 to-teal-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </button>
            <a href="{{ route('menu.index') }}" class="flex-1 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-8 py-4 text-center transition-all duration-200 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </form>
</x-page-card>
@endsection
