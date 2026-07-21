@extends('layouts.app')
@section('content')
<div class="space-y-4">

    {{-- Info rule aktif --}}
    @if($activeId)
    <div class="bg-teal-50 border border-teal-300 rounded-xl px-5 py-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-teal-500 text-white flex items-center justify-center shrink-0">
            <i class="fa fa-check"></i>
        </div>
        <div class="flex-1">
            <p class="font-bold text-teal-800 text-sm">Rule Apriori Aktif</p>
            <p class="text-teal-700 text-xs">
                @php $activeRow = $hasil->firstWhere('id', $activeId); @endphp
                @if($activeRow)
                    Bulan data: <strong>{{ $activeRow->nama_bulan ?? $activeRow->bulan_data ?? $activeRow->tgl_proses->format('m/Y') }}</strong>
                    — Support {{ $activeRow->min_support }}%, Confidence {{ $activeRow->min_confidence }}%
                    — {{ count(json_decode($activeRow->data_rules)) }} rule
                @endif
            </p>
        </div>
        <span class="bg-teal-500 text-white text-xs px-3 py-1 rounded-full font-bold">AKTIF</span>
    </div>
    @else
    <div class="bg-amber-50 border border-amber-300 rounded-xl px-5 py-4 flex items-center gap-3">
        <i class="fa fa-warning text-amber-500 text-xl"></i>
        <p class="text-amber-800 text-sm">Belum ada rule apriori yang diterapkan. Klik tombol <strong>Terapkan</strong> pada hasil yang ingin diaktifkan untuk rekomendasi di halaman kasir.</p>
    </div>
    @endif

    <x-page-card title="Riwayat Hasil Algoritma Apriori" icon="fa-check-circle">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-4 py-3 text-left">No.</th>
                        <th class="px-4 py-3 text-left">Bulan Data</th>
                        <th class="px-4 py-3 text-left">Tanggal Proses</th>
                        <th class="px-4 py-3 text-center">Support</th>
                        <th class="px-4 py-3 text-center">Confidence</th>
                        <th class="px-4 py-3 text-center">Total Data</th>
                        <th class="px-4 py-3 text-center">Rule</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($hasil as $i => $row)
                        @php $isActive = $row->id == $activeId; @endphp
                        <tr class="hover:bg-slate-50 transition {{ $isActive ? 'bg-teal-50' : '' }}">
                            <td class="px-4 py-3 text-slate-500">{{ $i + 1 }}</td>
                            <td class="px-4 py-3">
                                <span class="font-semibold text-slate-800">
                                    @if($row->nama_bulan)
                                        {{ $row->nama_bulan }}
                                    @elseif($row->bulan_data && str_contains($row->bulan_data, '-'))
                                        @php
                                            $months = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
                                            $display = $row->bulan_data;
                                            if (str_contains($row->bulan_data, ' - ')) {
                                                $parts = explode(' - ', $row->bulan_data);
                                                $res = [];
                                                foreach($parts as $p) {
                                                    $sub = explode('-', $p);
                                                    if(count($sub) == 2) $res[] = ($months[$sub[1]] ?? $sub[1]) . ' ' . $sub[0];
                                                }
                                                $display = count($res) == 2 ? implode(' - ', $res) : $row->bulan_data;
                                            } else {
                                                $sub = explode('-', $row->bulan_data);
                                                if(count($sub) == 2) $display = ($months[$sub[1]] ?? $sub[1]) . ' ' . $sub[0];
                                            }
                                        @endphp
                                        {{ $display }}
                                    @else
                                        {{ $row->bulan_data ?? '-' }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-800">{{ $row->tgl_proses->format('d/m/Y') }}</div>
                                <div class="text-[10px] text-slate-400">{{ $row->tgl_proses->format('H:i:s') }}</div>
                            </td>
                            <td class="px-4 py-3 text-center font-bold text-teal-600">{{ $row->min_support }}%</td>
                            <td class="px-4 py-3 text-center font-bold text-teal-600">{{ $row->min_confidence }}%</td>
                            <td class="px-4 py-3 text-center">{{ number_format($row->total_data) }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded-full text-xs font-bold">
                                    {{ count(json_decode($row->data_rules ?? '[]')) }} rule
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($isActive)
                                    <span class="bg-teal-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold">AKTIF</span>
                                @else
                                    <span class="bg-slate-200 text-slate-500 text-[10px] px-2 py-0.5 rounded-full">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('algoritma.detail', $row->id) }}" class="rounded-lg bg-teal-50 text-teal-600 px-3 py-1.5 text-xs font-bold hover:bg-teal-100 transition">
                                        <i class="fa fa-eye mr-1"></i> Detail
                                    </a>
                                    @if(!$isActive)
                                    <form action="{{ route('algoritma.terapkan') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $row->id }}">
                                        <button type="submit" class="rounded-lg bg-blue-50 text-blue-600 px-3 py-1.5 text-xs font-bold hover:bg-blue-100 transition"
                                            onclick="return confirm('Terapkan hasil apriori bulan {{ $row->nama_bulan ?? $row->bulan_data ?? $row->tgl_proses->format('m/Y') }} sebagai rekomendasi aktif?')">
                                            <i class="fa fa-play mr-1"></i> Terapkan
                                        </button>
                                    </form>
                                    <button type="button" onclick="confirmDelete({{ $row->id }})" class="rounded-lg bg-red-50 text-red-600 px-3 py-1.5 text-xs font-bold hover:bg-red-100 transition">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    @else
                                    <span class="rounded-lg bg-teal-100 text-teal-700 px-3 py-1.5 text-xs font-bold">
                                        <i class="fa fa-check mr-1"></i> Diterapkan
                                    </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center text-slate-400">
                                <i class="fa fa-history mb-2 text-2xl"></i>
                                <p>Belum ada riwayat perhitungan algoritma.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-page-card>
</div>

<form id="formDelete" action="{{ route('algoritma.delete') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="id" id="deleteId">
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Hasil?',
        text: "Data hasil perhitungan ini akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteId').value = id;
            document.getElementById('formDelete').submit();
        }
    });
}
</script>
@endpush
@endsection
