<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LaporanController extends Controller
{
    private const BULAN_ID = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
    ];

    /** @return array{periode: string, periodeKey: string, total: object, keuangan: \Illuminate\Support\Collection, m: string, y: string} */
    private function cashFlowData(?string $m, ?string $y): array
    {
        if ($m && $y) {
            $periodeKey = $y.'-'.$m;
            $periode = 'Periode '.(self::BULAN_ID[$m] ?? $m).' '.$y;
        } else {
            $periodeKey = now()->format('Y-m');
            $m = now()->format('m');
            $y = now()->format('Y');
            $periode = 'Periode '.(self::BULAN_ID[$m] ?? $m).' '.$y;
        }

        $total = DB::table('transaksi')
            ->where('umkm_id', auth()->user()->umkm_id)
            ->where('periode', $periodeKey)
            ->where('status', '!=', 'Bayar Nanti')
            ->selectRaw('COALESCE(SUM(grandtotal), 0) as gr, COALESCE(SUM(grandmodal), 0) as gm, COALESCE(SUM(total_qty), 0) as qty')
            ->first();

        $keuangan = DB::table('keuangan_lainnya')
            ->leftJoin('keuangan_ledger', 'keuangan_lainnya.no_ledger', '=', 'keuangan_ledger.no_ledger')
            ->where('keuangan_lainnya.umkm_id', auth()->user()->umkm_id)
            ->where('keuangan_lainnya.periode', $periodeKey)
            ->select('keuangan_ledger.keterangan as ket', 'keuangan_lainnya.*')
            ->get();

        return [
            'periode' => $periode,
            'periodeKey' => $periodeKey,
            'total' => $total,
            'keuangan' => $keuangan,
            'm' => $m,
            'y' => $y,
        ];
    }

    private function buildOrderQuery(Request $request)
    {
        return Transaksi::query()
            ->leftJoin('customer', 'transaksi.customer_id', '=', 'customer.id')
            ->leftJoin('login', 'transaksi.kasir_id', '=', 'login.id')
            ->select('transaksi.*', 'customer.nama as nama_customer', 'login.nama_user')
            ->when($request->filled('dari'), fn ($q) => $q->whereDate('transaksi.date', '>=', $request->dari))
            ->when($request->filled('sampai'), fn ($q) => $q->whereDate('transaksi.date', '<=', $request->sampai))
            ->orderByDesc('transaksi.id');
    }

    private function getPeriodeLabel(Request $request): string
    {
        if ($request->filled('dari') && $request->filled('sampai')) {
            return 'Periode ' . $request->dari . ' s.d. ' . $request->sampai;
        }
        return 'Semua Periode';
    }

    public function index(Request $request): View
    {
        $query = $this->buildOrderQuery($request);
        $orders = $query->paginate(30)->withQueryString();

        $allOrders = $this->buildOrderQuery($request)->get();
        $totalPendapatan = $allOrders->where('status', 'Lunas')->sum('grandtotal');
        $jumlahLunas = $allOrders->where('status', 'Lunas')->count();
        $jumlahBelumLunas = $allOrders->where('status', '!=', 'Lunas')->count();

        return view('laporan.index', [
            'title_web' => 'Laporan Penjualan',
            'orders' => $orders,
            'totalPendapatan' => $totalPendapatan,
            'jumlahLunas' => $jumlahLunas,
            'jumlahBelumLunas' => $jumlahBelumLunas,
        ]);
    }

    public function exportExcel(Request $request)
    {
        $orders = $this->buildOrderQuery($request)->get();
        $periode = $this->getPeriodeLabel($request);

        $filename = 'laporan-transaksi-' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($orders, $periode) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8 agar Excel baca dengan benar

            fputcsv($file, ['Laporan Transaksi Penjualan']);
            fputcsv($file, [$periode]);
            fputcsv($file, []);
            fputcsv($file, ['No', 'Tanggal', 'No Bon', 'Customer', 'Kasir', 'Tipe', 'Status', 'Total']);

            $no = 1;
            foreach ($orders as $o) {
                fputcsv($file, [
                    $no++,
                    $o->date,
                    $o->no_bon,
                    $o->nama_customer ?? 'Umum',
                    $o->nama_user,
                    $o->pesanan ?? 'Ditempat',
                    $o->status,
                    $o->grandtotal,
                ]);
            }

            fputcsv($file, []);
            fputcsv($file, ['', '', '', '', '', '', 'TOTAL', $orders->sum('grandtotal')]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $orders = $this->buildOrderQuery($request)->get();
        $periode = $this->getPeriodeLabel($request);

        $filename = 'laporan-transaksi-' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($orders, $periode) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['LAPORAN TRANSAKSI PENJUALAN']);
            fputcsv($file, [$periode]);
            fputcsv($file, ['Dicetak: ' . now()->format('d/m/Y H:i')]);
            fputcsv($file, []);
            fputcsv($file, ['No', 'Tanggal', 'No Bon', 'Customer', 'Kasir', 'Tipe', 'Status', 'Total']);

            $no = 1;
            foreach ($orders as $o) {
                fputcsv($file, [
                    $no++,
                    $o->date,
                    $o->no_bon,
                    $o->nama_customer ?? 'Umum',
                    $o->nama_user,
                    $o->pesanan ?? 'Ditempat',
                    $o->status,
                    $o->grandtotal,
                ]);
            }

            fputcsv($file, []);
            fputcsv($file, ['', '', '', '', '', '', 'TOTAL', $orders->sum('grandtotal')]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function dataOrder()
    {
        return response()->json(['data' => []]);
    }

    public function cash(Request $request): View
    {
        $data = $this->cashFlowData($request->input('m'), $request->input('y'));

        return view('laporan.cash', array_merge($data, [
            'title_web' => 'Cash Flow',
        ]));
    }

    public function pdf(Request $request)
    {
        $data = $this->cashFlowData($request->input('m'), $request->input('y'));

        $filename = 'cashflow-' . ($data['periodeKey'] ?? now()->format('Y-m')) . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['CASH FLOW USAHA']);
            fputcsv($file, [$data['periode']]);
            fputcsv($file, ['Dicetak: ' . now()->format('d/m/Y H:i')]);
            fputcsv($file, []);

            fputcsv($file, ['PENJUALAN']);
            fputcsv($file, ['Keterangan', 'Nominal']);
            fputcsv($file, ['Kas yang diterima dari Penjualan', 'Rp ' . number_format($data['total']->gr, 0, ',', '.')]);
            fputcsv($file, ['Dikurangi: Pemodalan', '(Rp ' . number_format($data['total']->gm, 0, ',', '.') . ')']);
            fputcsv($file, ['Total Qty', $data['total']->qty]);
            fputcsv($file, []);

            if ($data['keuangan']->count() > 0) {
                fputcsv($file, ['KEUANGAN LAINNYA']);
                fputcsv($file, ['Keterangan', 'Nominal']);
                foreach ($data['keuangan'] as $k) {
                    fputcsv($file, [$k->ket ?? $k->nama_urusan, 'Rp ' . number_format($k->nominal, 0, ',', '.')]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function produk(Request $request): View
    {
        $a = $request->input('a');
        $b = $request->input('b');

        $baseQuery = DB::table('transaksi_produk')
            ->leftJoin('transaksi', 'transaksi_produk.no_bon', '=', 'transaksi.no_bon')
            ->leftJoin('customer', 'transaksi.customer_id', '=', 'customer.id')
            ->leftJoin('login', 'transaksi.kasir_id', '=', 'login.id')
            ->where('transaksi_produk.umkm_id', auth()->user()->umkm_id);

        if ($request->filled('a') && $request->filled('b')) {
            $baseQuery->whereBetween('transaksi_produk.date', [$a, $b]);
            $periode = 'Periode '.$a.' s.d. '.$b;
            $excelUrl = route('laporan.produk-excel', ['a' => $a, 'b' => $b]);
        } else {
            $baseQuery->where('transaksi_produk.periode', now()->format('Y-m'));
            $periode = 'Periode '.now()->translatedFormat('F Y');
            $excelUrl = route('laporan.produk-excel');
        }

        $total = (clone $baseQuery)->selectRaw(
            'COALESCE(SUM(transaksi_produk.harga_beli * transaksi_produk.qty), 0) as hb,
             COALESCE(SUM(transaksi_produk.harga_jual * transaksi_produk.qty), 0) as hj,
             COALESCE(SUM(transaksi_produk.qty), 0) as qty'
        )->first();

        $items = (clone $baseQuery)
            ->select(
                'transaksi_produk.*',
                'customer.nama as nama_customer',
                'login.nama_user',
                'transaksi.atas_nama',
                'transaksi.pesanan',
                'transaksi.status',
                'transaksi.customer_id'
            )
            ->orderByDesc('transaksi_produk.id')
            ->paginate(25)
            ->withQueryString();

        return view('laporan.produk', [
            'title_web' => 'History Per Menu',
            'periode' => $periode,
            'total' => $total,
            'urlexcel' => $excelUrl,
            'items' => $items,
            'filterA' => $a,
            'filterB' => $b,
        ]);
    }

    public function dataProduk(Request $request)
    {
        return response()->json([
            'draw' => (int) $request->input('draw', 1),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
        ]);
    }

    public function produkExcel(Request $request)
    {
        $a = $request->input('a');
        $b = $request->input('b');

        $baseQuery = DB::table('transaksi_produk')
            ->leftJoin('transaksi', 'transaksi_produk.no_bon', '=', 'transaksi.no_bon')
            ->leftJoin('customer', 'transaksi.customer_id', '=', 'customer.id')
            ->leftJoin('login', 'transaksi.kasir_id', '=', 'login.id')
            ->where('transaksi_produk.umkm_id', auth()->user()->umkm_id)
            ->select(
                'transaksi_produk.*',
                'customer.nama as nama_customer',
                'login.nama_user',
                'transaksi.atas_nama',
                'transaksi.pesanan',
                'transaksi.status',
                'transaksi.customer_id'
            );

        if ($a && $b) {
            $baseQuery->whereBetween('transaksi_produk.date', [$a, $b]);
            $periode = 'Periode ' . $a . ' s.d. ' . $b;
        } else {
            $baseQuery->where('transaksi_produk.periode', now()->format('Y-m'));
            $periode = 'Periode ' . now()->translatedFormat('F Y');
        }

        $items = $baseQuery->orderByDesc('transaksi_produk.id')->get();

        $filename = 'history-produk-' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($items, $periode) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            fputcsv($file, ['History Per Produk']);
            fputcsv($file, [$periode]);
            fputcsv($file, []);
            fputcsv($file, ['No', 'Tanggal', 'Kode', 'Nama Produk', 'Kategori', 'Qty',
                            'Harga Beli', 'Harga Jual', 'Total Beli', 'Total Jual',
                            'Atas Nama', 'Customer', 'Jenis', 'Status']);

            $no = 1;
            foreach ($items as $row) {
                fputcsv($file, [
                    $no++,
                    $row->date,
                    $row->kode_menu,
                    $row->nama_menu,
                    $row->kategori,
                    $row->qty,
                    $row->harga_beli,
                    $row->harga_jual,
                    $row->harga_beli * $row->qty,
                    $row->harga_jual * $row->qty,
                    $row->atas_nama ?? '-',
                    ($row->customer_id ?? 0) == 0 ? '-' : ($row->nama_customer ?? '-'),
                    $row->pesanan ?? '-',
                    $row->status ?? '-',
                ]);
            }

            fputcsv($file, []);
            fputcsv($file, ['', '', '', '', 'TOTAL', $items->sum('qty'),
                            $items->sum('harga_beli'), $items->sum('harga_jual'),
                            $items->sum(fn($r) => $r->harga_beli * $r->qty),
                            $items->sum(fn($r) => $r->harga_jual * $r->qty)]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function cashFlowExcel(Request $request)
    {
        $data = $this->cashFlowData($request->input('m'), $request->input('y'));
        $filename = 'cashflow-' . ($data['periodeKey'] ?? now()->format('Y-m')) . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            fputcsv($file, ['Cash Flow ' . $data['periode']]);
            fputcsv($file, []);
            fputcsv($file, ['PENJUALAN']);
            fputcsv($file, ['Total Penjualan', 'Rp ' . number_format($data['total']->gr, 0, ',', '.')]);
            fputcsv($file, ['Total Modal', 'Rp ' . number_format($data['total']->gm, 0, ',', '.')]);
            fputcsv($file, ['Total Qty', $data['total']->qty]);
            fputcsv($file, []);

            if ($data['keuangan']->count() > 0) {
                fputcsv($file, ['KEUANGAN LAINNYA']);
                fputcsv($file, ['Keterangan', 'Nominal']);
                foreach ($data['keuangan'] as $k) {
                    fputcsv($file, [$k->ket, 'Rp ' . number_format($k->nominal, 0, ',', '.')]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function terlaris(): View
    {
        $terlaris = DB::table('transaksi_produk')
            ->select(
                'nama_menu',
                'kode_menu',
                'kategori',
                DB::raw('SUM(qty) as total_qty'),
                DB::raw('SUM(harga_jual * qty) as total_jual')
            )
            ->where('periode', now()->format('Y-m'))
            ->groupBy('nama_menu', 'kode_menu', 'kategori')
            ->orderByDesc('total_qty')
            ->limit(20)
            ->get();

        return view('laporan.terlaris', [
            'title_web' => 'Produk Terlaris',
            'terlaris' => $terlaris,
        ]);
    }
}
