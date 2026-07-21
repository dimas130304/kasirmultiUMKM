@extends('layouts.app')
@section('content')
<x-page-card title="Daftar Pengguna" icon="fa-user">
    <a href="{{ route('users.tambah') }}" class="inline-block mb-4 rounded-lg bg-teal-600 text-white px-4 py-2 text-sm">Tambah Pengguna</a>
    <table class="w-full text-sm">
        <thead class="bg-slate-50"><tr><th class="px-3 py-2 text-left">Nama</th><th class="px-3 py-2">Email</th><th class="px-3 py-2">Level</th><th></th></tr></thead>
        <tbody>
        @foreach ($users as $u)
            <tr class="border-t">
                <td class="px-3 py-2">{{ $u->nama_user }}</td>
                <td class="px-3 py-2">{{ $u->email }}</td>
                <td class="px-3 py-2 text-center"><span class="rounded-full {{ $u->level === 'Admin' ? 'bg-violet-100 text-violet-800' : 'bg-blue-100 text-blue-800' }} px-2 py-0.5 text-xs">{{ $u->level }}</span></td>
                <td class="px-3 py-2 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('users.edit', ['id' => $u->id]) }}" class="rounded-lg bg-teal-100 text-teal-600 px-2 py-1 text-xs font-bold hover:bg-teal-200 transition" title="Edit">
                            <i class="fa fa-edit"></i>
                        </a>
                        <form action="{{ route('users.delete') }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?')" class="inline">
                            @csrf
                            <input type="hidden" name="id" value="{{ $u->id }}">
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
</x-page-card>
@endsection
