@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto" x-data="stokForm()">
    <x-page-card title="Entry Persediaan Produk" icon="fa-plus-square">
        <form method="POST" action="{{ route('menu.pasok') }}" @submit="if (!menuId) { $event.preventDefault(); alert('Pilih produk terlebih dahulu.'); }">
            @csrf
            <input type="hidden" name="id" x-model="menuId">
            <input type="hidden" name="stoka" x-model="stokLama">

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Produk</label>
                    <div class="flex gap-2">
                        <div class="relative flex-1" @click.outside="openDropdown = false">
                            <input type="text" name="nama_menu" x-model="namaMenu" required
                                @input="openDropdown = true; menuId = ''; pickerSearch = namaMenu"
                                @focus="openDropdown = true"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none"
                                placeholder="Ketik nama / kode produk...">
                            
                            {{-- Autocomplete Dropdown --}}
                            <div x-show="openDropdown" x-cloak
                                class="absolute left-0 right-0 mt-1 max-h-60 overflow-y-auto rounded-lg border border-slate-200 bg-white shadow-xl z-50 divide-y divide-slate-100">
                                <template x-for="item in filteredMenus" :key="item.id">
                                    <button type="button" @click="pilih(item.id, item.nama, item.stok)"
                                        class="w-full text-left px-4 py-3 hover:bg-teal-50 transition-colors text-sm flex justify-between items-center">
                                        <div class="min-w-0 flex-1 pr-2">
                                            <span class="font-bold text-slate-800 block truncate" x-text="item.nama"></span>
                                            <span class="text-xs text-slate-400 font-mono" x-text="item.kode_menu"></span>
                                        </div>
                                        <span class="text-xs font-black text-teal-600 bg-teal-50 border border-teal-100 px-2 py-1 rounded-lg shrink-0" x-text="'Stok: ' + item.stok"></span>
                                    </button>
                                </template>
                                <div x-show="filteredMenus.length === 0"
                                    class="p-4 text-center text-xs text-slate-400 italic">
                                    Produk tidak ditemukan
                                </div>
                            </div>
                        </div>
                        <button type="button" @click="showPicker = true"
                            class="rounded-lg bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 text-sm font-medium whitespace-nowrap transition-colors flex items-center gap-1.5 shrink-0">
                            <i class="fa fa-search"></i> Cari Produk
                        </button>
                    </div>
                </div>

                <div x-show="menuId" x-cloak>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah Stok Baru</label>
                    <input type="number" name="stok" x-model.number="stokBaru" min="0" required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <p class="mt-1 text-xs text-slate-500">
                        Stok saat ini: <span class="font-semibold" x-text="stokLama"></span>
                        — ubah angka di atas untuk set stok baru.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 mt-6 pt-4 border-t border-slate-100">
                <button type="submit" class="rounded-lg bg-teal-600 hover:bg-teal-700 text-white px-5 py-2 text-sm font-medium">
                    <i class="fa fa-save"></i> Simpan Stok
                </button>
                <a href="{{ route('menu.persediaan') }}" class="rounded-lg bg-slate-600 hover:bg-slate-700 text-white px-5 py-2 text-sm font-medium">
                    <i class="fa fa-list"></i> Daftar Stok
                </a>
                <a href="{{ route('menu.index') }}" class="rounded-lg bg-slate-200 hover:bg-slate-300 text-slate-700 px-5 py-2 text-sm font-medium">
                    Kembali
                </a>
            </div>
        </form>
    </x-page-card>

    {{-- Modal pilih produk --}}
    <div x-show="showPicker" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60"
        @keydown.escape.window="showPicker = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden"
            @click.outside="showPicker = false">
            <div class="bg-gradient-to-r from-teal-600 to-teal-500 px-5 py-3 text-white font-semibold flex justify-between items-center shrink-0">
                <span><i class="fa fa-cubes"></i> Pilih Produk</span>
                <button type="button" @click="showPicker = false" class="text-white/80 hover:text-white"><i class="fa fa-times"></i></button>
            </div>
            <div class="p-4 border-b border-slate-100 shrink-0">
                <input type="text" x-model="pickerSearch" placeholder="Filter cepat nama / kode..."
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div class="overflow-auto flex-1 p-4">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 sticky top-0">
                        <tr>
                            <th class="px-2 py-2 text-left">Kode</th>
                            <th class="px-2 py-2 text-left">Nama</th>
                            <th class="px-2 py-2 text-left">Kategori</th>
                            <th class="px-2 py-2 text-center">Stok</th>
                            <th class="px-2 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($menus as $m)
                        <tr class="border-t border-slate-100 hover:bg-slate-50"
                            data-search="{{ strtolower($m->nama.' '.$m->kode_menu) }}"
                            x-show="rowMatch($el)">
                            <td class="px-2 py-2 font-mono text-xs">{{ $m->kode_menu }}</td>
                            <td class="px-2 py-2 font-medium">{{ $m->nama }}</td>
                            <td class="px-2 py-2 text-xs">{{ $m->nama_kategori ?? '-' }}</td>
                            <td class="px-2 py-2 text-center font-bold">{{ $m->stok }}</td>
                            <td class="px-2 py-2 text-right">
                                <button type="button"
                                    @click="pilih({{ $m->id }}, @js($m->nama), {{ $m->stok }})"
                                    class="rounded-lg bg-teal-600 text-white px-3 py-1 text-xs font-medium hover:bg-teal-700">
                                    <i class="fa fa-check"></i> Pilih
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function stokForm() {
    return {
        showPicker: false,
        pickerSearch: '',
        menuId: '',
        namaMenu: '',
        stokBaru: 0,
        stokLama: 0,
        openDropdown: false,
        allMenus: @js($menus),
        get filteredMenus() {
            const s = this.namaMenu.trim().toLowerCase();
            if (!s) return this.allMenus.slice(0, 10);
            return this.allMenus.filter(m => 
                (m.nama || '').toLowerCase().includes(s) || 
                (m.kode_menu || '').toLowerCase().includes(s)
            ).slice(0, 10);
        },
        rowMatch(el) {
            const s = this.pickerSearch.trim().toLowerCase();
            if (!s) return true;
            return (el.dataset.search || '').includes(s);
        },
        pilih(id, nama, stok) {
            this.menuId = id;
            this.namaMenu = nama;
            this.stokLama = stok;
            this.stokBaru = stok;
            this.showPicker = false;
            this.openDropdown = false;
        },
    };
}
</script>
@endpush
