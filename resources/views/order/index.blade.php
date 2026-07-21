@extends('layouts.app')
@section('content')
<x-page-card title="Daftar Order" icon="fa-list-alt">

    {{-- Filter Tabs --}}
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('order.index') }}"
            class="rounded-xl px-5 py-2.5 font-semibold transition {{ !$jenis ? 'bg-teal-600 text-white shadow-lg shadow-teal-600/20' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
            <i class="fa fa-list mr-2"></i>Semua Hari Ini
        </a>
        <a href="{{ route('order.index', ['jenis' => 1]) }}"
            class="rounded-xl px-5 py-2.5 font-semibold transition {{ $jenis == 1 ? 'bg-teal-600 text-white shadow-lg shadow-teal-600/20' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
            <i class="fa fa-cutlery mr-2"></i>Di Tempat
        </a>
        <a href="{{ route('order.index', ['jenis' => 2]) }}"
            class="rounded-xl px-5 py-2.5 font-semibold transition {{ $jenis == 2 ? 'bg-teal-600 text-white shadow-lg shadow-teal-600/20' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
            <i class="fa fa-calendar-check-o mr-2"></i>Booking
        </a>
    </div>

    {{-- Date Filters --}}
    <div class="flex flex-wrap gap-3 items-center mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            @if(request('jenis'))
                <input type="hidden" name="jenis" value="{{ request('jenis') }}">
            @endif
            <input type="date" name="dari" value="{{ request('dari') }}" class="rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/30 transition-all">
            <input type="date" name="sampai" value="{{ request('sampai') }}" class="rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/30 transition-all">
            <button type="submit" class="rounded-xl bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 text-sm font-semibold flex items-center gap-2 transition-all">
                <i class="fa fa-search"></i> Filter
            </button>
            <a href="{{ request('jenis') ? route('order.index', ['jenis' => request('jenis')]) : route('order.index') }}" class="rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-3 text-sm font-semibold flex items-center gap-1 transition-all">
                <i class="fa fa-refresh"></i>
            </a>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-slate-50 to-slate-100">
                    <tr class="text-slate-700 border-b border-slate-200">
                        <th class="px-6 py-4 text-left font-bold text-xs uppercase tracking-wide">No Bon</th>
                        <th class="px-6 py-4 text-left font-bold text-xs uppercase tracking-wide">Customer</th>
                        <th class="px-6 py-4 text-center font-bold text-xs uppercase tracking-wide">Tipe</th>
                        <th class="px-6 py-4 text-center font-bold text-xs uppercase tracking-wide">Status</th>
                        <th class="px-6 py-4 text-right font-bold text-xs uppercase tracking-wide">Total</th>
                        <th class="px-6 py-4 text-right font-bold text-xs uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @forelse ($orders as $o)
                    <tr class="hover:bg-slate-50/80 transition-all" id="row-{{ $o->id }}">
                        {{-- No Bon --}}
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-3 py-1 rounded-xl">{{ $o->no_bon }}</span>
                            <p class="text-[11px] text-slate-400 mt-1.5">{{ $o->created_at ? \Carbon\Carbon::parse($o->created_at)->format('d/m H:i') : '-' }}</p>
                        </td>
                        {{-- Customer --}}
                        <td class="px-6 py-4">
                            <p class="font-semibold text-slate-900">{{ $o->nama_customer ?? $o->atas_nama }}</p>
                            @if($o->nama_user)
                                <p class="text-[11px] text-slate-400 mt-1">via {{ $o->nama_user }}</p>
                            @endif
                        </td>
                        {{-- Tipe --}}
                        <td class="px-6 py-4 text-center">
                            @if($o->pesanan === 'Delivery')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800">
                                    <i class="fa fa-truck"></i> Delivery
                                </span>
                            @elseif($o->pesanan === 'Booking')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                                    <i class="fa fa-calendar-check-o"></i> Booking
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">
                                    <i class="fa fa-cutlery"></i> Ditempat
                                </span>
                            @endif
                        </td>
                        {{-- Status --}}
                        <td class="px-6 py-4 text-center status-container">
                            @php
                                $statusClass = match($o->status) {
                                    'Lunas'      => 'bg-emerald-100 text-emerald-800',
                                    'Bayar Nanti'=> 'bg-amber-100 text-amber-800',
                                    default      => 'bg-slate-100 text-slate-700',
                                };
                            @endphp
                            <span class="status-badge inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-bold {{ $statusClass }}">
                                @if($o->status === 'Lunas') <i class="fa fa-check-circle"></i>
                                @elseif($o->status === 'Bayar Nanti') <i class="fa fa-clock-o"></i>
                                @endif
                                {{ $o->status }}
                            </span>
                        </td>
                        {{-- Total --}}
                        <td class="px-6 py-4 text-right">
                            <span class="font-black text-slate-900">Rp {{ number_format($o->grandtotal, 0, ',', '.') }}</span>
                        </td>
                        {{-- Aksi --}}
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 action-container flex-wrap">
                                {{-- Detail --}}
                                <a href="{{ route('kasir.show', ['id' => $o->no_bon]) }}" target="_blank"
                                title="Lihat Detail"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-blue-50 text-blue-700 px-3.5 py-2 text-xs font-bold hover:bg-blue-100 transition-all border border-blue-100">
                                <i class="fa fa-eye"></i>
                            </a>

                                {{-- Tandai Lunas --}}
                                @if($o->status !== 'Lunas')
                                <button onclick="updateStatus(this, {{ $o->id }}, 'lunas')"
                                    class="btn-lunas inline-flex items-center gap-1.5 rounded-xl bg-teal-50 text-teal-800 px-3.5 py-2 text-xs font-bold hover:bg-teal-100 transition-all border border-teal-100"
                                    title="Tandai Lunas">
                                    <i class="fa fa-check-circle"></i> <span class="hidden sm:inline">Lunas</span>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center gap-3 text-slate-400">
                                <i class="fa fa-shopping-basket text-6xl mb-2"></i>
                                <p class="text-base font-semibold">Belum ada order yang masuk</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 flex justify-center">{{ $orders->withQueryString()->links() }}</div>

</x-page-card>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function updateStatus(btn, id, status) {
    const row = $('#row-' + id);
    const statusBadge = row.find('.status-badge');
    const btnLunas = row.find('.btn-lunas');

    Swal.fire({
        title: 'Konfirmasi',
        text: 'Tandai transaksi ini sebagai LUNAS?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0d9488',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Lunas!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

            $.ajax({
                url: '{{ route("kasir.update-status") }}',
                method: 'POST',
                data: { _method: 'PATCH', _token: '{{ csrf_token() }}', id: id, status: status },
                success: function(response) {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, timer: 1500, showConfirmButton: false });

                    const newStatus = response.data.status;
                    if (statusBadge.length) {
                        statusBadge.html('<i class="fa fa-check-circle"></i> ' + newStatus);
                        statusBadge.removeClass('bg-amber-100 text-amber-800 bg-slate-100 text-slate-700')
                                   .addClass('bg-emerald-100 text-emerald-800');
                    }
                    // Hapus semua tombol aksi kecuali tombol detail
                    row.find('.btn-lunas').remove();
                    row.find('a[href*="wa.me"]').closest('a').remove();
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON?.error || 'Gagal mengubah status.';
                    Swal.fire({ icon: 'error', title: 'Gagal', text: msg });
                }
            });
        }
    });
}
</script>
@endpush
