@extends('layouts.app')
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <x-page-card :title="$edit ? 'Edit Kategori' : 'Tambah Kategori'" icon="fa-tags">
            <form method="POST" action="{{ $edit ? route('kategori.update') : route('kategori.store') }}" class="space-y-3">
                @csrf
                @if ($edit)<input type="hidden" name="id" value="{{ $edit->id }}">@endif
                <input type="text" name="kategori" value="{{ old('kategori', $edit->kategori ?? '') }}" required placeholder="Nama kategori" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                <button class="w-full rounded-lg bg-teal-600 text-white py-2 font-medium">{{ $edit ? 'Update' : 'Simpan' }}</button>
                @if ($edit)<a href="{{ route('kategori.index') }}" class="block text-center text-sm text-slate-500 mt-2">Batal</a>@endif
            </form>
        </x-page-card>
    </div>
    <div class="lg:col-span-2">
        <x-page-card title="Daftar Kategori" icon="fa-list">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50"><tr><th class="px-3 py-2 text-left">#</th><th class="px-3 py-2 text-left">Kategori</th><th class="px-3 py-2"></th></tr></thead>
                    <tbody>
                    @foreach ($kat as $k)
                        <tr class="border-t">
                            <td class="px-3 py-2">{{ $k->id }}</td>
                            <td class="px-3 py-2 font-medium">{{ $k->kategori }}</td>
                            <td class="px-3 py-2 text-right space-x-2">
                                <a href="{{ route('kategori.index', ['id' => $k->id]) }}" class="text-teal-600 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('kategori.delete') }}" class="inline" onsubmit="return confirm('Hapus?')">
                                    @csrf<input type="hidden" name="id" value="{{ $k->id }}">
                                    <button class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </x-page-card>
    </div>
</div>
@endsection
