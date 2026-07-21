@extends('layouts.app')

@php
    $gr = (int) ($total->gr ?? 0);
    $gm = (int) ($total->gm ?? 0);
    $operasional = $gr - $gm;
    $msk = $keuangan->sum('jumlah_masuk');
    $klr = $keuangan->sum('jumlah_keluar');
    $keuanganNet = $msk - $klr;
    $labaBersih = $operasional + $keuanganNet;
    $tahunSekarang = (int) date('Y');
@endphp

@section('content')
<x-page-card :title="'Cash Flow — ' . $periode" icon="fa-money">
    <div class="flex flex-wrap gap-2 mb-4">
        <button type="button" onclick="document.getElementById('filterModal').classList.remove('hidden')"
            class="rounded-lg bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 text-sm font-medium">
            <i class="fa fa-search"></i> Pencarian
        </button>
        <a href="{{ route('laporan.cash-excel', request()->only(['m', 'y'])) }}"
            class="rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 text-sm font-medium">
            <i class="fa fa-file-excel-o"></i> Download Excel
        </a>
        <button onclick="window.print()"
            class="rounded-lg bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-sm font-medium">
            <i class="fa fa-print"></i> Print / PDF
        </button>
        <a href="{{ route('laporan.cash') }}" class="rounded-lg bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 text-sm font-medium">
            <i class="fa fa-refresh"></i> Refresh
        </a>
        <a href="{{ route('laporan.index') }}" class="rounded-lg bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 text-sm font-medium">
            <i class="fa fa-arrow-left"></i> Laporan Transaksi
        </a>
    </div>

    <div class="text-center mb-6">
        <h2 class="text-lg font-bold text-slate-800">Cash Flow Usaha</h2>
        <p class="text-teal-700 font-medium">{{ $periode }}</p>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200">
        <table class="w-full text-sm">
            <tbody>
                <tr class="bg-slate-800 text-white">
                    <th colspan="2" class="px-4 py-3 text-left font-semibold">Arus Kas yang berasal dari Kegiatan Operasional</th>
                </tr>
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3 text-slate-700">Kas yang diterima oleh Penjualan Produk</td>
                    <td class="px-4 py-3 text-right font-medium whitespace-nowrap">Rp {{ number_format($gr, 0, ',', '.') }}</td>
                </tr>
                <tr class="bg-slate-50">
                    <th colspan="2" class="px-4 py-2 text-left text-slate-600">Dikurangi :</th>
                </tr>
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3 text-slate-700">Pemodalan oleh Penjualan Produk</td>
                    <td class="px-4 py-3 text-right text-red-600 font-medium whitespace-nowrap">(Rp {{ number_format($gm, 0, ',', '.') }})</td>
                </tr>
                <tr class="bg-slate-100 font-semibold">
                    <td class="px-4 py-3">Aliran Kas Bersih dari Kegiatan Operasional</td>
                    <td class="px-4 py-3 text-right text-teal-800 whitespace-nowrap">Rp {{ number_format($operasional, 0, ',', '.') }}</td>
                </tr>

                <tr class="bg-slate-800 text-white">
                        <th colspan="2" class="px-4 py-3 text-left font-semibold">Aliran Pemasukan Kas yang berasal dari Aktivitas Keuangan Lainnya</th>
                    </tr>
                    {{-- Form Input Keuangan --}}
                    <tr class="bg-slate-50">
                        <td colspan="2" class="px-4 py-4">
                            <form id="form-keuangan" method="POST" action="{{ route('keuangan.store-lain') }}" class="space-y-3">
                                @csrf
                                <input type="hidden" name="date" value="{{ \Illuminate\Support\Carbon::parse($periodeKey)->format('Y-m-d') }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Transaksi <span class="text-red-500">*</span></label>
                                        <select name="jenis" id="jenis" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                                            <option value="">-- Pilih Jenis --</option>
                                            <option value="Pemasukan">Pemasukan</option>
                                            <option value="Pengeluaran">Pengeluaran</option>
                                        </select>
                                    </div>
                                    <div id="jumlah-container">
                                        <label id="jumlah-label" class="block text-sm font-medium text-slate-700 mb-1">Jumlah (Rp) <span class="text-red-500">*</span></label>
                                        <input type="text" id="jumlah-input" name="jumlah" required placeholder="Masukkan jumlah" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                                        <input type="hidden" id="jumlah_masuk" name="jumlah_masuk" value="0">
                                        <input type="hidden" id="jumlah_keluar" name="jumlah_keluar" value="0">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Keterangan <span class="text-red-500">*</span></label>
                                    <input type="text" name="keterangan" required placeholder="Masukkan keterangan transaksi" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                                </div>
                                <div class="flex justify-end pt-2">
                                    <button type="submit" class="rounded-lg bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 text-sm font-medium transition-colors flex items-center gap-2">
                                        <i class="fa fa-save"></i> Simpan Transaksi
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    {{-- Daftar Transaksi --}}
                    @forelse ($keuangan as $row)
                        <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-2 text-slate-700">
                                <div class="flex items-center justify-between">
                                    <span>{{ $row->keterangan ?? $row->nama_urusan ?? '-' }}</span>
                                    <form method="POST" action="{{ route('keuangan.delete-lain') }}" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $row->id }}">
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm ml-2">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="px-4 py-2 text-right whitespace-nowrap">
                                @if ($row->jenis === 'Pemasukan')
                                    <span class="text-emerald-600 font-semibold">Rp {{ number_format($row->jumlah_masuk, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-red-600 font-semibold">(Rp {{ number_format($row->jumlah_keluar, 0, ',', '.') }})</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-4 py-4 text-center text-slate-500 italic">Tidak ada transaksi keuangan lainnya pada periode ini.</td>
                        </tr>
                    @endforelse
                <tr class="bg-slate-100 font-semibold">
                    <td class="px-4 py-3">Total Kas Bersih yang berasal dari Aktivitas Keuangan Lainnya</td>
                    <td class="px-4 py-3 text-right text-teal-800 whitespace-nowrap">Rp {{ number_format($keuanganNet, 0, ',', '.') }}</td>
                </tr>

                <tr class="bg-lime-200 font-bold text-slate-900">
                    <td class="px-4 py-4">Laba Bersih Bulan ini ({{ $periode }})</td>
                    <td class="px-4 py-4 text-right text-lg whitespace-nowrap">Rp {{ number_format($labaBersih, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
        <div class="rounded-lg bg-slate-50 border border-slate-200 px-4 py-3">
            <p class="text-slate-500">Total Qty Terjual</p>
            <p class="font-bold text-slate-800">{{ number_format($total->qty ?? 0) }} item</p>
        </div>
        <div class="rounded-lg bg-teal-50 border border-teal-200 px-4 py-3">
            <p class="text-teal-600">Operasional Bersih</p>
            <p class="font-bold text-teal-800">Rp {{ number_format($operasional, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-lg bg-lime-50 border border-lime-200 px-4 py-3">
            <p class="text-lime-700">Laba Bersih</p>
            <p class="font-bold text-lime-900">Rp {{ number_format($labaBersih, 0, ',', '.') }}</p>
        </div>
    </div>
</x-page-card>

<div id="filterModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="bg-gradient-to-r from-teal-600 to-teal-500 px-5 py-3 text-white font-semibold flex justify-between items-center">
            <span><i class="fa fa-search"></i> Pencarian Data</span>
            <button type="button" onclick="document.getElementById('filterModal').classList.add('hidden')" class="text-white/80 hover:text-white">
                <i class="fa fa-times"></i>
            </button>
        </div>
        <form method="GET" action="{{ route('laporan.cash') }}" class="p-5 space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Bulan</label>
                <select name="m" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <option value="">— Pilih Bulan —</option>
                    @php $bulanList = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember']; @endphp
                    @foreach ($bulanList as $val => $nama)
                        <option value="{{ $val }}" @selected($m === $val)>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tahun</label>
                <select name="y" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <option value="">— Pilih Tahun —</option>
                    @for ($t = 2021; $t <= $tahunSekarang; $t++)
                        <option value="{{ $t }}" @selected($y == (string) $t)>{{ $t }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="document.getElementById('filterModal').classList.add('hidden')"
                    class="rounded-lg bg-slate-200 text-slate-700 px-4 py-2 text-sm">Tutup</button>
                <button type="submit" class="rounded-lg bg-teal-600 text-white px-4 py-2 text-sm font-medium">Cari</button>
            </div>
        </form>
    </div>
</div>
@endsection
