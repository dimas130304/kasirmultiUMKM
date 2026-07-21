@extends('layouts.app')

@section('content')
<x-page-card title="Detail Customer" icon="fa-user">
    <div class="max-w-2xl">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-xl font-bold text-slate-800">Informasi Pelanggan</h2>
            <div class="flex gap-2">
                <a href="{{ route('customer.edit', ['id' => $customer->id]) }}" class="rounded-lg bg-teal-100 text-teal-600 px-4 py-2 text-sm font-bold hover:bg-teal-200 transition">
                    <i class="fa fa-edit mr-1"></i> Edit
                </a>
                <a href="{{ route('customer.index') }}" class="rounded-lg bg-slate-100 text-slate-600 px-4 py-2 text-sm font-bold hover:bg-slate-200 transition">
                    <i class="fa fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-6 rounded-2xl border border-slate-100">
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kode Customer</label>
                <p class="font-mono text-slate-700 font-bold">{{ $customer->kode_customer }}</p>
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama Lengkap</label>
                <p class="text-slate-700 font-bold">{{ $customer->nama }}</p>
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nomor HP / WhatsApp</label>
                <p class="text-slate-700">{{ $customer->hp ?: '-' }}</p>
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Terdaftar Pada</label>
                <p class="text-slate-700">{{ $customer->created_at ? $customer->created_at->format('d M Y H:i') : '-' }}</p>
            </div>
            <div class="col-span-1 md:col-span-2 space-y-1">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Alamat</label>
                <p class="text-slate-700 whitespace-pre-line">{{ $customer->alamat ?: '-' }}</p>
            </div>
            @if($customer->keterangan)
            <div class="col-span-1 md:col-span-2 space-y-1">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Keterangan</label>
                <p class="text-slate-700 italic">{{ $customer->keterangan }}</p>
            </div>
            @endif
        </div>
    </div>
</x-page-card>
@endsection
