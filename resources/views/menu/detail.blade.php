@extends('layouts.app')
@section('content')
<x-page-card title="Detail Produk" icon="fa-cube">

    {{-- Back button --}}
    <div class="mb-5">
        <a href="{{ route('menu.index') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors">
            <i class="fa fa-arrow-left"></i> Kembali ke Daftar Produk
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Gambar Produk --}}
        <div class="lg:col-span-1">
            <div class="rounded-2xl overflow-hidden border border-slate-200 bg-white shadow-sm">
                @php
                    $gambarPath = public_path('assets/image/produk/' . $menu->gambar);
                    $hasGambar = $menu->gambar && $menu->gambar !== '-' && file_exists($gambarPath);
                @endphp
                @if ($hasGambar)
                    <img src="{{ asset('assets/image/produk/' . $menu->gambar) }}"
                         alt="{{ $menu->nama }}"
                         class="w-full aspect-square object-cover">
                @else
                    <div class="w-full aspect-square flex flex-col items-center justify-center bg-slate-50 text-slate-300">
                        <i class="fa fa-image text-6xl mb-3"></i>
                        <span class="text-sm font-medium">Tidak ada gambar</span>
                    </div>
                @endif
            </div>

            {{-- Status Stok --}}
            <div class="mt-4 rounded-xl border p-4 text-center
                {{ $menu->stok <= 0 ? 'bg-red-50 border-red-200' : ($menu->stok <= 5 ? 'bg-amber-50 border-amber-200' : 'bg-emerald-50 border-emerald-200') }}">
                <p class="text-xs font-semibold uppercase tracking-widest mb-1
                    {{ $menu->stok <= 0 ? 'text-red-400' : ($menu->stok <= 5 ? 'text-amber-400' : 'text-emerald-400') }}">
                    Status Stok
                </p>
                <p class="text-3xl font-black
                    {{ $menu->stok <= 0 ? 'text-red-600' : ($menu->stok <= 5 ? 'text-amber-600' : 'text-emerald-600') }}">
                    {{ $menu->stok }}
                </p>
                <p class="text-xs mt-1
                    {{ $menu->stok <= 0 ? 'text-red-500' : ($menu->stok <= 5 ? 'text-amber-500' : 'text-emerald-500') }}">
                    @if ($menu->stok <= 0) Stok Habis
                    @elseif ($menu->stok <= 5) Stok Hampir Habis
                    @else Stok Tersedia
                    @endif
                </p>
            </div>
        </div>

        {{-- Right: Info Produk --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Nama & Kode --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold mb-2
                            {{ ($menu->kategori->kategori ?? '') == 'MINUMAN' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }}">
                            {{ $menu->kategori->kategori ?? 'Tanpa Kategori' }}
                        </span>
                        <h2 class="text-2xl font-black text-slate-800">{{ $menu->nama }}</h2>
                        <p class="text-sm text-slate-400 font-mono mt-1">{{ $menu->kode_menu }}</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('menu.edit') }}?id={{ $menu->id }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium transition-colors shadow-sm">
                            <i class="fa fa-pencil"></i> Edit
                        </a>
                        <button type="button"
                            onclick="confirmDelete({{ $menu->id }}, '{{ addslashes($menu->nama) }}')"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 text-sm font-medium transition-colors border border-red-200">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>

            {{-- Harga --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Harga Pokok</p>
                    <p class="text-2xl font-black text-slate-700">
                        Rp {{ number_format($menu->harga_pokok, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-slate-400 mt-1">Modal / HPP</p>
                </div>
                <div class="bg-gradient-to-br from-teal-50 to-emerald-50 rounded-2xl border border-teal-200 shadow-sm p-5">
                    <p class="text-xs font-semibold text-teal-500 uppercase tracking-wider mb-1">Harga Jual</p>
                    <p class="text-2xl font-black text-teal-700">
                        Rp {{ number_format($menu->harga_jual, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-teal-500 mt-1">
                        Margin:
                        @php
                            $margin = $menu->harga_pokok > 0
                                ? round((($menu->harga_jual - $menu->harga_pokok) / $menu->harga_pokok) * 100, 1)
                                : 0;
                        @endphp
                        <span class="font-bold {{ $margin >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                            {{ $margin }}%
                        </span>
                    </p>
                </div>
            </div>

            {{-- Info Tambahan --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-100 bg-slate-50">
                    <h4 class="text-sm font-bold text-slate-600 uppercase tracking-wider">Informasi Tambahan</h4>
                </div>
                <div class="divide-y divide-slate-50">
                    <div class="flex items-center px-5 py-3.5">
                        <span class="w-40 text-sm text-slate-400 font-medium flex items-center gap-2">
                            <i class="fa fa-tag w-4 text-center text-slate-300"></i> Kode Menu
                        </span>
                        <span class="text-sm font-mono font-bold text-slate-700">{{ $menu->kode_menu }}</span>
                    </div>
                    <div class="flex items-center px-5 py-3.5">
                        <span class="w-40 text-sm text-slate-400 font-medium flex items-center gap-2">
                            <i class="fa fa-th-large w-4 text-center text-slate-300"></i> Kategori
                        </span>
                        <span class="text-sm font-semibold text-slate-700">{{ $menu->kategori->kategori ?? '-' }}</span>
                    </div>
                    <div class="flex items-center px-5 py-3.5">
                        <span class="w-40 text-sm text-slate-400 font-medium flex items-center gap-2">
                            <i class="fa fa-cubes w-4 text-center text-slate-300"></i> Stok Saat Ini
                        </span>
                        <span class="text-sm font-bold {{ $menu->stok <= 5 ? 'text-red-600' : 'text-slate-700' }}">
                            {{ $menu->stok }} unit
                        </span>
                    </div>
                    <div class="flex items-center px-5 py-3.5">
                        <span class="w-40 text-sm text-slate-400 font-medium flex items-center gap-2">
                            <i class="fa fa-money w-4 text-center text-slate-300"></i> Keuntungan
                        </span>
                        <span class="text-sm font-bold text-emerald-600">
                            Rp {{ number_format($menu->harga_jual - $menu->harga_pokok, 0, ',', '.') }} / pcs
                        </span>
                    </div>
                    @if ($menu->keterangan)
                    <div class="flex items-start px-5 py-3.5">
                        <span class="w-40 text-sm text-slate-400 font-medium flex items-center gap-2 mt-0.5">
                            <i class="fa fa-file-text w-4 text-center text-slate-300"></i> Keterangan
                        </span>
                        <span class="text-sm text-slate-700 flex-1">{{ $menu->keterangan }}</span>
                    </div>
                    @endif
                    <div class="flex items-center px-5 py-3.5">
                        <span class="w-40 text-sm text-slate-400 font-medium flex items-center gap-2">
                            <i class="fa fa-calendar w-4 text-center text-slate-300"></i> Dibuat
                        </span>
                        <span class="text-sm text-slate-700">
                            {{ $menu->created_at ? \Carbon\Carbon::parse($menu->created_at)->translatedFormat('d F Y') : '-' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('menu.stok') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium transition-colors shadow-sm">
                    <i class="fa fa-plus-square"></i> Entry Stok
                </a>
                <a href="{{ route('menu.persediaan') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium transition-colors">
                    <i class="fa fa-list"></i> Daftar Persediaan
                </a>
            </div>
        </div>
    </div>
</x-page-card>

{{-- Modal Konfirmasi Hapus --}}
<div id="modal-hapus" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="flex items-center gap-4 p-6 border-b border-slate-100">
                <div class="h-12 w-12 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                    <i class="fa fa-trash text-red-500 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Hapus Produk</h3>
                    <p class="text-sm text-slate-500">Tindakan ini tidak dapat dibatalkan</p>
                </div>
                <button onclick="closeDeleteModal()" class="ml-auto text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                    <i class="fa fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-6">
                <p class="text-slate-600 text-sm">
                    Apakah Anda yakin ingin menghapus produk
                    <span id="modal-nama-produk" class="font-bold text-slate-800"></span>?
                </p>
                <div class="mt-3 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700 flex items-start gap-2">
                    <i class="fa fa-exclamation-triangle mt-0.5 flex-shrink-0"></i>
                    <span>Data produk yang dihapus tidak dapat dipulihkan kembali.</span>
                </div>
            </div>
            <div class="flex justify-end gap-3 px-6 pb-6">
                <button type="button" onclick="closeDeleteModal()"
                    class="px-5 py-2 rounded-lg text-sm font-medium bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                    Batal
                </button>
                <form id="form-hapus" method="POST" action="{{ route('menu.delete') }}">
                    @csrf
                    <input type="hidden" name="id" id="input-hapus-id">
                    <button type="submit"
                        class="px-5 py-2 rounded-lg text-sm font-medium bg-red-600 hover:bg-red-700 text-white transition-colors shadow-sm flex items-center gap-2">
                        <i class="fa fa-trash"></i> Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id, nama) {
    document.getElementById('input-hapus-id').value = id;
    document.getElementById('modal-nama-produk').textContent = '"' + nama + '"';
    document.getElementById('modal-hapus').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeDeleteModal() {
    document.getElementById('modal-hapus').classList.add('hidden');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteModal();
});
</script>
@endpush
@endsection
