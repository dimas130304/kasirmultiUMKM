@extends('layouts.app')
@section('content')
<x-page-card title="Kategori Customer" icon="fa-users">
    <a href="{{ route('customer.tambah') }}" class="inline-block mb-4 rounded-lg bg-teal-600 text-white px-4 py-2 text-sm"><i class="fa fa-plus"></i> Tambah Customer</a>
    <table class="w-full text-sm">
        <thead class="bg-slate-50"><tr><th class="px-3 py-2 text-left">Kode</th><th class="px-3 py-2 text-left">Nama</th><th class="px-3 py-2 text-left">HP</th><th class="px-3 py-2 text-left">Alamat</th><th></th></tr></thead>
        <tbody>
        @foreach ($customers as $c)
            <tr class="border-t">
                <td class="px-3 py-2">{{ $c->kode_customer }}</td>
                <td class="px-3 py-2">{{ $c->nama }}</td>
                <td class="px-3 py-2">{{ $c->hp }}</td>
                <td class="px-3 py-2">{{ $c->alamat }}</td>
                <td class="px-3 py-2 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('customer.detail', ['id' => $c->id]) }}" class="rounded-lg bg-blue-100 text-blue-600 px-2 py-1 text-xs font-bold hover:bg-blue-200 transition" title="Detail">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a href="{{ route('customer.edit', ['id' => $c->id]) }}" class="rounded-lg bg-teal-100 text-teal-600 px-2 py-1 text-xs font-bold hover:bg-teal-200 transition" title="Edit">
                            <i class="fa fa-edit"></i>
                        </a>
                        <form action="{{ route('customer.delete') }}" method="POST" onsubmit="return confirm('Hapus customer ini?')" class="inline">
                            @csrf
                            <input type="hidden" name="id" value="{{ $c->id }}">
                            <button type="submit" class="rounded-lg bg-red-100 text-red-600 px-2 py-1 text-xs font-bold hover:bg-red-200 transition" title="Hapus">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $customers->links() }}</div>
</x-page-card>
@endsection
