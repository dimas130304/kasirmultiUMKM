<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk {{ $t->no_bon ?? '' }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 100%;
            max-width: 300px; /* Cocok untuk 58mm atau 80mm */
            margin: 0 auto;
            padding: 10px;
            color: #000;
            font-size: 12px;
            line-height: 1.4;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .header {
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }
        .shop-name {
            font-size: 16px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        
        .meta {
            margin-bottom: 10px;
            font-size: 11px;
        }
        .meta div {
            display: flex;
            justify-content: space-between;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .item-row td {
            padding: 4px 0;
            vertical-align: top;
        }
        .item-name {
            display: block;
            margin-bottom: 2px;
        }
        .item-details {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            padding-left: 5px;
        }
        
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        
        .totals {
            margin-top: 5px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        .grand-total {
            font-size: 14px;
            margin: 8px 0;
            padding: 5px 0;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
        }
        
        .footer {
            margin-top: 15px;
            font-size: 11px;
            font-style: italic;
        }

        @media print {
            body {
                width: 100%;
                max-width: none;
                margin: 0;
                padding: 5mm;
            }
            @page {
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header text-center">
        <h1 class="shop-name font-bold">{{ $pp->nama_toko ?? 'KASIR UMKM' }}</h1>
        <p>{{ $pp->alamat_toko ?? '' }}</p>
        <p>{{ $pp->telepon_toko ?? '' }}</p>
    </div>

    <div class="meta">
        <div>
            <span>No. Bon:</span>
            <span class="font-bold">{{ $t->no_bon }}</span>
        </div>
        <div>
            <span>Tgl:</span>
            <span>{{ $t->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div>
            <span>Kasir:</span>
            <span>{{ auth()->user()->nama_user ?? 'Admin' }}</span>
        </div>
        <div>
            <span>Customer:</span>
            <span>{{ $t->nama ?? ($t->atas_nama ?: 'Umum') }}</span>
        </div>
    </div>

    <div class="divider"></div>

    <table>
        @php $subtotal_items = 0; @endphp
        @forelse ($tp as $line)
            @php 
                $sub = $line->harga_jual * $line->qty;
                $subtotal_items += $sub;
            @endphp
            <tr class="item-row">
                <td colspan="2">
                    <span class="item-name">{{ $line->nama_menu }}</span>
                    <div class="item-details">
                        <span>{{ $line->qty }} x {{ number_format($line->harga_jual, 0, ',', '.') }}</span>
                        <span class="font-bold">Rp {{ number_format($sub, 0, ',', '.') }}</span>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="text-center" style="padding: 20px 0;">
                    <p style="color: red; font-style: italic;">Detail item tidak ditemukan</p>
                </td>
            </tr>
        @endforelse
    </table>

    <div class="divider"></div>

    <div class="totals">
        <div class="total-row">
            <span>Total Item:</span>
            <span>Rp {{ number_format($subtotal_items, 0, ',', '.') }}</span>
        </div>
        
        @if($t->diskon > 0)
        <div class="total-row">
            <span>Diskon:</span>
            <span>- Rp {{ number_format($t->diskon, 0, ',', '.') }}</span>
        </div>
        @endif

        @if($t->pajak > 0)
        <div class="total-row">
            <span>Pajak:</span>
            <span>+ Rp {{ number_format($t->pajak, 0, ',', '.') }}</span>
        </div>
        @endif

        @if($t->voucher > 0)
        <div class="total-row">
            <span>Voucher:</span>
            <span>- Rp {{ number_format($t->voucher, 0, ',', '.') }}</span>
        </div>
        @endif

        <div class="total-row grand-total font-bold">
            <span>GRAND TOTAL:</span>
            <span>Rp {{ number_format($t->grandtotal, 0, ',', '.') }}</span>
        </div>

        <div class="total-row">
            <span>Bayar:</span>
            <span>Rp {{ number_format($t->dibayar, 0, ',', '.') }}</span>
        </div>

        <div class="total-row">
            <span>Kembali:</span>
            <span>Rp {{ number_format($t->dibayar - $t->grandtotal, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="footer text-center">
        <p>{{ $pp->footer_struk ?? 'Terima Kasih Atas Kunjungan Anda' }}</p>
        <p>Selamat Menikmati!</p>
    </div>
</body>
</html>
