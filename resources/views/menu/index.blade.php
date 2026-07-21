@extends('layouts.app')
@section('content')
<x-page-card title="Daftar Produk / Menu" icon="fa-cubes">
    {{-- Alert Messages --}}
    @if (session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 flex items-center gap-2">
            <i class="fa fa-check-circle text-emerald-500"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Action Buttons --}}
    <div class="flex flex-wrap gap-2 mb-5">
        <a href="{{ route('menu.tambah') }}" class="inline-flex items-center gap-2 rounded-lg bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 text-sm font-medium transition-colors shadow-sm">
            <i class="fa fa-plus"></i> Tambah
        </a>
        <a href="{{ route('menu.stok') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-700 hover:bg-slate-800 text-white px-4 py-2 text-sm transition-colors">
            <i class="fa fa-plus-square"></i> Entry Stok
        </a>
        <a href="{{ route('menu.persediaan') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 text-sm transition-colors">
            <i class="fa fa-list"></i> Daftar Stok
        </a>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-xl border border-slate-200">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kode</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Produk</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Harga Jual</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Stok</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @forelse ($menus as $m)
                <tr class="hover:bg-teal-50/40 transition-colors group">
                    <td class="px-4 py-3 font-mono text-xs text-slate-500">{{ $m->kode_menu }}</td>
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $m->nama }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ ($m->kategori->kategori ?? '') == 'MINUMAN' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }}">
                            {{ $m->kategori->kategori ?? '-' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right font-medium text-slate-700">Rp {{ number_format($m->harga_jual, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center justify-center min-w-[2rem] px-2 py-0.5 rounded-lg text-xs font-bold
                            {{ $m->stok <= 5 ? 'bg-red-100 text-red-700' : ($m->stok <= 10 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                            {{ $m->stok }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1.5">
                            {{-- Detail --}}
                            <a href="{{ route('menu.detail') }}?id={{ $m->id }}"
                               title="Lihat Detail"
                               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                                <i class="fa fa-eye"></i>
                                <span class="hidden sm:inline">Detail</span>
                            </a>
                            {{-- Edit --}}
                            <a href="{{ route('menu.edit') }}?id={{ $m->id }}"
                               title="Edit Produk"
                               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-teal-50 text-teal-700 hover:bg-teal-100 transition-colors">
                                <i class="fa fa-pencil"></i>
                                <span class="hidden sm:inline">Edit</span>
                            </a>
                            {{-- Hapus --}}
                            <button type="button"
                                title="Hapus Produk"
                                onclick="confirmDelete({{ $m->id }}, '{{ addslashes($m->nama) }}')"
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                                <i class="fa fa-trash"></i>
                                <span class="hidden sm:inline">Hapus</span>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-slate-400">
                        <i class="fa fa-inbox text-3xl mb-2 block"></i>
                        Belum ada produk
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $menus->links() }}</div>
</x-page-card>

{{-- Modal Konfirmasi Hapus --}}
<div id="modal-hapus" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    {{-- Dialog --}}
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all">
            {{-- Header --}}
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
            {{-- Body --}}
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
            {{-- Footer --}}
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

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteModal();
});
</script>
@endpush
@endsection
