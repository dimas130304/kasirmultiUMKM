@extends('layouts.app')
@section('content')
<x-page-card title="Keuangan Ledger" icon="fa-money">
    <a href="{{ route('keuangan.lain') }}" class="inline-block mb-4 text-teal-600 text-sm font-medium">Keuangan Lainnya →</a>
    <table class="w-full text-sm">
        <thead class="bg-slate-50"><tr><th class="px-3 py-2">No Ledger</th><th class="px-3 py-2">Keterangan</th><th class="px-3 py-2">Jenis</th></tr></thead>
        <tbody>
        @foreach ($ledgers as $l)
            <tr class="border-t"><td class="px-3 py-2">{{ $l->no_ledger }}</td><td class="px-3 py-2">{{ $l->keterangan }}</td><td class="px-3 py-2">{{ $l->jenis }}</td></tr>
        @endforeach
        </tbody>
    </table>
</x-page-card>
@endsection
