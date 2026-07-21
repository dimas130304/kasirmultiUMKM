<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Transaksi</title>
<style>
    body { font-family: Arial, sans-serif; font-size: 11px; margin: 20px; color: #1e293b; }
    h2 { text-align:center; margin-bottom: 2px; font-size: 14px; }
    p.sub { text-align:center; color:#555; margin-bottom: 12px; font-size: 11px; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #0f172a; color: #fff; padding: 6px 8px; text-align: center; }
    td { padding: 5px 8px; border-bottom: 1px solid #e2e8f0; }
    .right { text-align:right; }
    .center { text-align:center; }
    .badge-lunas { background:#d1fae5; color:#065f46; padding:1px 6px; border-radius:9px; }
    .badge-nanti { background:#fef3c7; color:#92400e; padding:1px 6px; border-radius:9px; }
    tfoot td { font-weight: bold; background: #f8fafc; }
    @media print {
        .no-print { display: none !important; }
        body { margin: 0; }
    }
</style>
</head>
<body>
<h2>Laporan Transaksi Penjualan</h2>
<p class="sub">{{ $periode }}</p>
<table>
    <thead>
        <tr>
            <th>No</th><th>Tanggal</th><th>No Bon</th><th>Customer</th><th>Kasir</th><th>Status</th><th>Total</th>
        </tr>
    </thead>
    <tbody>
    @php $no = 1; $grand = 0; @endphp
    @foreach ($orders as $o)
        @php $grand += $o->grandtotal; @endphp
        <tr>
            <td class="center">{{ $no++ }}</td>
            <td class="center">{{ $o->date }}</td>
            <td class="center" style="font-size:10px;">{{ $o->no_bon }}</td>
            <td>{{ $o->nama_customer ?? 'Umum' }}</td>
            <td>{{ $o->nama_user }}</td>
            <td class="center"><span class="{{ $o->status=='Lunas' ? 'badge-lunas' : 'badge-nanti' }}">{{ $o->status }}</span></td>
            <td class="right">Rp {{ number_format($o->grandtotal, 0, ',', '.') }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6" class="right">TOTAL PENDAPATAN</td>
            <td class="right">Rp {{ number_format($grand, 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
<p style="margin-top:20px; text-align:right; font-size:10px; color:#888;">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>

{{-- Toolbar (tidak ikut cetak) --}}
<div class="no-print" style="position:fixed; top:10px; right:10px; display:flex; gap:8px; z-index:999;">
    <button onclick="window.print()"
        style="background:#0f172a;color:#fff;padding:8px 16px;border:none;border-radius:8px;cursor:pointer;font-size:13px;display:flex;align-items:center;gap:6px;">
        🖨️ Cetak / Simpan PDF
    </button>
    <button onclick="window.close()"
        style="background:#e2e8f0;color:#1e293b;padding:8px 16px;border:none;border-radius:8px;cursor:pointer;font-size:13px;">
        ✕ Tutup
    </button>
</div>

@if(isset($autoPrint) && $autoPrint)
<script>
    // Auto-open print dialog agar langsung muncul Save as PDF
    window.addEventListener('load', function () {
        setTimeout(function () { window.print(); }, 400);
    });
</script>
@endif
</body>
</html>
