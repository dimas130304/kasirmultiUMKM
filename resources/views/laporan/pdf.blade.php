<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title_pdf }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 11pt; margin: 24px; color: #111; }
        h4 { text-align: center; margin: 0.25rem 0; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        td, th { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .bg-gray { background: #eee; font-weight: bold; }
        .bg-green { background: #cdf59a; font-weight: bold; }
        .text-right { text-align: right; }
        .no-print { display: block; }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
@php
    $gr = (int) ($total->gr ?? 0);
    $gm = (int) ($total->gm ?? 0);
    $operasional = $gr - $gm;
    $msk = $keuangan->sum('jumlah_masuk');
    $klr = $keuangan->sum('jumlah_keluar');
    $keuanganNet = $msk - $klr;
    $labaBersih = $operasional + $keuanganNet;
@endphp

{{-- Toolbar (tidak ikut cetak) --}}
<div class="no-print" style="position:fixed;top:10px;right:10px;display:flex;gap:8px;z-index:999;">
    <button onclick="window.print()"
        style="background:#0f172a;color:#fff;padding:8px 18px;border:none;border-radius:8px;cursor:pointer;font-size:13px;">
        🖨️ Cetak / Simpan PDF
    </button>
    <button onclick="window.close()"
        style="background:#e2e8f0;color:#1e293b;padding:8px 16px;border:none;border-radius:8px;cursor:pointer;font-size:13px;">
        ✕ Tutup
    </button>
</div>

<h4>CASH FLOW USAHA</h4>
<h4>{{ $periode }}</h4>

<table>
    <tr>
        <th colspan="2">Arus Kas yang berasal dari Kegiatan Operasional</th>
    </tr>
    <tr>
        <td>Kas yang diterima oleh Penjualan Produk</td>
        <td class="text-right">Rp {{ number_format($gr, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <th colspan="2">Dikurangi :</th>
    </tr>
    <tr>
        <td>Pemodalan oleh Penjualan Produk</td>
        <td class="text-right">(Rp {{ number_format($gm, 0, ',', '.') }})</td>
    </tr>
    <tr class="bg-gray">
        <th>Aliran Kas Bersih dari Kegiatan Operasional</th>
        <th class="text-right">Rp {{ number_format($operasional, 0, ',', '.') }}</th>
    </tr>
    <tr>
        <th colspan="2">Aliran Pemasukan Kas yang berasal dari Aktivitas Keuangan Lainnya</th>
    </tr>
    @foreach ($keuangan as $row)
        <tr>
            <td>{{ $row->ket ?? $row->nama_urusan ?? '-' }}</td>
            <td class="text-right">
                @if ($row->jenis === 'Pemasukan')
                    Rp {{ number_format($row->jumlah_masuk, 0, ',', '.') }}
                @else
                    (Rp {{ number_format($row->jumlah_keluar, 0, ',', '.') }})
                @endif
            </td>
        </tr>
    @endforeach
    <tr class="bg-gray">
        <th>Total Kas Bersih yang berasal dari Aktivitas Keuangan Lainnya</th>
        <th class="text-right">Rp {{ number_format($keuanganNet, 0, ',', '.') }}</th>
    </tr>
    <tr class="bg-green">
        <th>Laba Bersih Bulan ini ({{ $periode }})</th>
        <th class="text-right">Rp {{ number_format($labaBersih, 0, ',', '.') }}</th>
    </tr>
</table>

<p style="margin-top:20px;text-align:right;font-size:9pt;color:#888;">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>

@if(isset($autoPrint) && $autoPrint)
<script>
    window.addEventListener('load', function () {
        setTimeout(function () { window.print(); }, 400);
    });
</script>
@endif
</body>
</html>
