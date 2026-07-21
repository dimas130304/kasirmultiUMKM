@extends('layouts.app')

@section('content')
<x-page-card title="Daftar Persediaan Produk" icon="fa-cubes">
    <div class="flex flex-wrap gap-2 mb-4">
        <a href="{{ route('menu.stok') }}" class="rounded-lg bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 text-sm font-medium">
            <i class="fa fa-plus"></i> Entry Stok
        </a>
        <a href="{{ route('menu.index') }}" class="rounded-lg bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 text-sm font-medium">
            <i class="fa fa-list"></i> Daftar Produk
        </a>
    </div>

    <form method="GET" class="flex flex-wrap gap-2 mb-4 text-sm">
        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama / kode..."
            class="rounded-lg border border-slate-300 px-3 py-2 min-w-[180px]">
        <select name="id_kategori" class="rounded-lg border border-slate-300 px-3 py-2">
            <option value="">Semua kategori</option>
            @foreach ($kat as $k)
                <option value="{{ $k->id }}" @selected(request('id_kategori') == $k->id)>{{ $k->kategori }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-lg bg-teal-600 text-white px-4 py-2 font-medium">Filter</button>
        @if (request()->hasAny(['cari', 'id_kategori']))
            <a href="{{ route('menu.persediaan') }}" class="rounded-lg bg-slate-200 text-slate-700 px-4 py-2">Reset</a>
        @endif
    </form>

    <div class="overflow-x-auto rounded-xl border border-slate-200">
        <table class="w-full text-sm">
            <thead class="bg-slate-800 text-white">
                <tr>
                    <th class="px-3 py-2 text-left w-12">No</th>
                    <th class="px-3 py-2 text-left">Gambar</th>
                    <th class="px-3 py-2 text-left">Kode</th>
                    <th class="px-3 py-2 text-left">Kategori</th>
                    <th class="px-3 py-2 text-left">Nama Produk</th>
                    <th class="px-3 py-2 text-center">Stok</th>
                    <th class="px-3 py-2 text-right">Harga Pokok</th>
                    <th class="px-3 py-2 text-right">Harga Jual</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($menus as $i => $m)
                @php
                    $stokRendah = $m->stok <= 5;
                @endphp
                <tr class="border-t border-slate-100 hover:bg-slate-50 {{ $stokRendah ? 'bg-amber-50/60' : '' }}">
                    <td class="px-3 py-2">{{ $menus->firstItem() + $i }}</td>
                    <td class="px-3 py-2">
                        @include('menu.partials.gambar', ['gambar' => $m->gambar, 'size' => 'sm'])
                    </td>
                    <td class="px-3 py-2 font-mono text-xs">{{ $m->kode_menu }}</td>
                    <td class="px-3 py-2">{{ $m->nama_kategori ?? '-' }}</td>
                    <td class="px-3 py-2 font-medium">{{ $m->nama }}</td>
                    <td class="px-3 py-2 text-center">
                        <span class="inline-flex min-w-[2rem] justify-center rounded-full px-2 py-0.5 text-xs font-bold
                            {{ $stokRendah ? 'bg-amber-200 text-amber-900' : ($m->stok > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800') }}">
                            {{ $m->stok }}
                        </span>
                    </td>
                    <td class="px-3 py-2 text-right">Rp {{ number_format($m->harga_pokok, 0, ',', '.') }}</td>
                    <td class="px-3 py-2 text-right">Rp {{ number_format($m->harga_jual, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-3 py-12 text-center text-slate-500">Belum ada produk.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <p class="mt-2 text-xs text-slate-500"><span class="inline-block w-3 h-3 rounded bg-amber-200 align-middle mr-1"></span> Stok ≤ 5 ditandai kuning (perlu restock)</p>
    <div class="mt-4">{{ $menus->links() }}</div>
</x-page-card>
@endsection
