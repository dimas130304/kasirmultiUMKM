<?php

namespace App\Http\Controllers;

use App\Models\KeuanganLainnya;
use App\Models\KeuanganLedger;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KeuanganController extends Controller
{
    public function index(): View
    {
        return view('keuangan.index', [
            'title_web' => 'Keuangan Ledger',
            'ledgers' => KeuanganLedger::whereNull('deleted_at')->orderByDesc('id')->get(),
        ]);
    }

    public function store(Request $request)
    {
        KeuanganLedger::create($request->only(['no_ledger', 'keterangan', 'jenis']) + ['created_at' => now()]);

        return back()->with('success', 'Ledger ditambahkan.');
    }

    public function update(Request $request)
    {
        KeuanganLedger::where('id', $request->id)->update($request->only(['no_ledger', 'keterangan', 'jenis', 'updated_at']) + ['updated_at' => now()]);

        return back()->with('success', 'Ledger diperbarui.');
    }

    public function delete(Request $request)
    {
        KeuanganLedger::where('id', $request->id)->update(['deleted_at' => now()]);

        return back()->with('success', 'Ledger dihapus.');
    }

    public function lain(): View
    {
        return view('keuangan.lain', [
            'title_web' => 'Keuangan Lainnya',
            'rows' => KeuanganLainnya::orderByDesc('id')->paginate(20),
        ]);
    }

    public function edit(Request $request): View
    {
        return view('keuangan.edit', [
            'title_web' => 'Edit Keuangan',
            'row' => KeuanganLainnya::findOrFail((int) $request->query('id')),
        ]);
    }

    public function data()
    {
        return response()->json(['data' => KeuanganLainnya::latest()->limit(100)->get()]);
    }

    public function excel()
    {
        $rows = KeuanganLainnya::orderByDesc('id')->get();

        $filename = 'keuangan-lainnya-' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            fputcsv($file, ['Laporan Keuangan Lainnya']);
            fputcsv($file, ['Dicetak: ' . now()->format('d/m/Y H:i')]);
            fputcsv($file, []);
            fputcsv($file, ['No', 'Tanggal', 'Periode', 'Keterangan', 'Jenis', 'Pemasukan', 'Pengeluaran']);

            $no = 1;
            foreach ($rows as $row) {
                fputcsv($file, [
                    $no++,
                    $row->date ?? $row->created_at?->format('Y-m-d'),
                    $row->periode ?? '-',
                    $row->keterangan ?? ($row->nama_urusan ?? '-'),
                    $row->jenis ?? '-',
                    $row->jumlah_masuk ?? 0,
                    $row->jumlah_keluar ?? 0,
                ]);
            }

            fputcsv($file, []);
            fputcsv($file, ['', '', '', '', 'TOTAL',
                $rows->sum('jumlah_masuk'),
                $rows->sum('jumlah_keluar'),
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function storeLain(Request $request)
    {
        $data = $request->all();
        $jumlah = (int) $request->input('jumlah', 0);
        if ($request->input('jenis') === 'Pemasukan') {
            $data['jumlah_masuk'] = $jumlah;
            $data['jumlah_keluar'] = 0;
        } else {
            $data['jumlah_masuk'] = 0;
            $data['jumlah_keluar'] = $jumlah;
        }

        // Parse date from request or fallback to today
        $dateStr = $request->input('date') ?: now()->toDateString();
        $date = \Illuminate\Support\Carbon::parse($dateStr);
        
        $data['date'] = $date->toDateString();
        $data['periode'] = $date->format('Y-m');
        $data['year'] = $date->format('Y');
        $data['user_id'] = auth()->id();
        $data['created_at'] = now();

        KeuanganLainnya::create($data);

        return back()->with('success', 'Data keuangan disimpan.');
    }

    public function updateLain(Request $request)
    {
        KeuanganLainnya::where('id', $request->id)->update($request->except(['_token', 'id']));

        return back()->with('success', 'Data diperbarui.');
    }

    public function deleteLain(Request $request)
    {
        KeuanganLainnya::where('id', $request->id)->delete();

        return back()->with('success', 'Data dihapus.');
    }
}
