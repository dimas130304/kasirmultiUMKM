<?php

namespace App\Http\Controllers;

use App\Models\AprioriDataset;
use App\Models\AprioriHasil;
use App\Models\AprioriHasilDataset;
use App\Models\TransaksiProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AlgoritmaController extends Controller
{
    public function index(): View
    {
        return view('algoritma.index', [
            'title_web' => 'Export Transaksi Apriori',
            'dataset' => AprioriDataset::orderByDesc('id')->get(),
        ]);
    }

    public function rule(): View
    {
        // Pastikan total_data hanya menghitung milik UMKM yang login
        $totalData = AprioriDataset::count();

        return view('algoritma.rule', [
            'title_web' => 'Penentuan Rule',
            'total_data' => $totalData,
        ]);
    }

    public function hasil(): View
    {
        $hasilList = AprioriHasil::where('umkm_id', Auth::user()->umkm_id)->orderByDesc('id')->get();

        // Ambil ID yang sedang aktif/diterapkan berdasarkan UMKM
        $activeId = DB::table('apriori_aktif')
            ->where('umkm_id', Auth::user()->umkm_id)
            ->value('apriori_hasil_id');

        return view('algoritma.hasil', [
            'title_web' => 'Hasil Algoritma',
            'hasil' => $hasilList,
            'activeId' => $activeId,
        ]);
    }

    public function detail(string $umkm_code, $id): View
    {
        $row = AprioriHasil::where('umkm_id', Auth::user()->umkm_id)
            ->with('hasilDataset')
            ->findOrFail((int) $id);

        return view('algoritma.detail', [
            'title_web' => 'Detail Hasil',
            'row' => $row,
        ]);
    }

    public function proses(Request $request)
    {
        $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date',
        ]);

        $awal = $request->tgl_awal;
        $akhir = $request->tgl_akhir;

        $items = TransaksiProduk::whereBetween('date', [$awal, $akhir])
            ->orderBy('no_bon')
            ->get();

        if ($items->isEmpty()) {
            return back()->with('failed', 'Tidak ada data transaksi pada periode tersebut.');
        }

        $grouped = [];
        foreach ($items as $it) {
            $grouped[$it->no_bon]['tgl'] = $it->date;
            $grouped[$it->no_bon]['items'][] = $it->nama_menu;
        }

        DB::transaction(function () use ($grouped) {
            AprioriDataset::query()->delete();
            foreach ($grouped as $no_bon => $data) {
                AprioriDataset::create([
                    'tgl_transaksi' => is_string($data['tgl']) ? $data['tgl'] : $data['tgl']->format('Y-m-d'),
                    'no_transaksi' => $no_bon,
                    'dataset' => implode(',', array_unique($data['items'])),
                ]);
            }
        });

        return redirect()->route('algoritma.index')->with('success', 'Berhasil export ' . count($grouped) . ' data transaksi.');
    }

    public function reset(Request $request)
    {
        AprioriDataset::where('umkm_id', Auth::user()->umkm_id)->delete();

        return redirect()->route('algoritma.index')->with('success', 'Dataset berhasil dikosongkan.');
    }

    public function hitung(Request $request)
    {
        try {
            // 0. Proactive Fix: Pastikan kolom bulan_data cukup panjang untuk rentang (Fix SQLSTATE[22001])
            try {
                $columnInfo = DB::select("SHOW COLUMNS FROM apriori_hasil LIKE 'bulan_data'");
                if (!empty($columnInfo)) {
                    $type = $columnInfo[0]->Type;
                    if (str_contains($type, 'varchar(7)')) {
                        DB::statement("ALTER TABLE apriori_hasil MODIFY COLUMN bulan_data VARCHAR(20)");
                    }
                }
            } catch (\Exception $e) {
                \Log::warning('Gagal auto-update kolom bulan_data: ' . $e->getMessage());
            }

            $request->validate([
                'min_support' => 'required|numeric|min:0.1|max:100',
                'min_confidence' => 'required|numeric|min:0.1|max:100',
                'mode_periode' => 'required|in:single,range',
                'bulan' => 'required_if:mode_periode,single|nullable|string|max:7',
                'bulan_mulai' => 'required_if:mode_periode,range|nullable|string|max:7',
                'bulan_akhir' => 'required_if:mode_periode,range|nullable|string|max:7',
            ]);

            $minSupport = $request->min_support;
            $minConfidence = $request->min_confidence;
            $mode = $request->mode_periode;
            $mulai = $request->bulan_mulai;
            $akhir = $request->bulan_akhir;
            $bulan = $request->bulan;

            // Validasi tambahan untuk rentang bulan
            if ($mode === 'range') {
                if (!$mulai || !$akhir) {
                    return back()->with('failed', 'Bulan mulai dan bulan akhir harus diisi untuk mode rentang.')->withInput();
                }
                if ($mulai > $akhir) {
                    return back()->with('failed', 'Bulan mulai tidak boleh lebih besar dari bulan akhir.')->withInput();
                }
            }

            $umkmId = Auth::user()->umkm_id;

            // 1. Ambil transaksi dari TransaksiProduk berdasarkan mode periode
            $query = TransaksiProduk::where('umkm_id', $umkmId);

            if ($mode === 'single') {
                $query->where('periode', $bulan);
                $periodeInfo = $bulan;
            } else {
                $query->whereBetween('periode', [$mulai, $akhir]);
                $periodeInfo = $mulai . ' s/d ' . $akhir;
            }

            $items = $query->orderBy('no_bon')->get();

            if ($items->isEmpty()) {
                $msg = $mode === 'single' ? "Bulan $bulan" : "Rentang $mulai s/d $akhir";
                return back()->with('failed', "Tidak ada data transaksi pada periode $msg. Pastikan Anda sudah mengimpor data transaksi.")->withInput();
            }

            // 2. Kelompokkan produk berdasarkan No Bon
            $grouped = [];
            foreach ($items as $it) {
                $grouped[$it->no_bon]['tgl'] = $it->date;
                $grouped[$it->no_bon]['items'][] = strtoupper(trim($it->nama_menu)); // Trim & Upper for consistency
            }

            // 3. Simpan ke AprioriDataset (hapus data lama milik UMKM ini terlebih dahulu)
            DB::beginTransaction();
            try {
                AprioriDataset::where('umkm_id', $umkmId)->delete();
                foreach ($grouped as $no_bon => $data) {
                    $unique_items = array_unique($data['items']);
                    // Pastikan hanya menyimpan transaksi yang valid (minimal 1 item)
                    if (count($unique_items) > 0) {
                        AprioriDataset::create([
                            'tgl_transaksi' => is_string($data['tgl']) ? $data['tgl'] : $data['tgl']->format('Y-m-d'),
                            'no_transaksi' => $no_bon,
                            'dataset' => implode(',', $unique_items),
                            'umkm_id' => $umkmId,
                        ]);
                    }
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw new \Exception("Gagal menyiapkan dataset: " . $e->getMessage());
            }

            // 4. Ambil dataset terbaru untuk diproses
            $dataset = AprioriDataset::where('umkm_id', $umkmId)->get();
            if ($dataset->isEmpty()) {
                throw new \Exception("Dataset kosong setelah proses persiapan.");
            }

            $startTime = microtime(true);

            $transactions = [];
            $multiItemCount = 0;
            foreach ($dataset as $row) {
                $items = array_filter(array_map('trim', explode(',', $row->dataset)));
                if (count($items) > 1) $multiItemCount++;
                $transactions[] = $items;
            }

            $totalTransactions = count($transactions);
            
            // Validasi: Jika terlalu banyak transaksi tunggal, Apriori tidak akan efektif
            if ($multiItemCount === 0) {
                return back()->with('failed', "Gagal menghasilkan rule. Dari $totalTransactions transaksi, tidak ada satupun transaksi yang berisi lebih dari 1 produk. Algoritma Apriori memerlukan transaksi dengan minimal 2 produk untuk mencari hubungan (asosiasi).")->withInput();
            }

            $minSupportCount = ($minSupport / 100) * $totalTransactions;

            // 1-Itemsets (Menghitung frekuensi kemunculan tiap produk)
            $itemCounts = [];
            foreach ($transactions as $t) {
                foreach ($t as $item) {
                    $itemCounts[$item] = ($itemCounts[$item] ?? 0) + 1;
                }
            }

            $frequent1Itemsets = [];
            foreach ($itemCounts as $item => $count) {
                if ($count >= $minSupportCount) {
                    $frequent1Itemsets[$item] = $count;
                }
            }

            // Jika tidak ada item yang lolos support minimal
            if (empty($frequent1Itemsets)) {
                $maxSupportFound = !empty($itemCounts) ? (max($itemCounts) / $totalTransactions) * 100 : 0;
                return back()->with('failed', "Tidak ada produk yang memenuhi syarat Minimum Support $minSupport%. Produk dengan support tertinggi hanya " . round($maxSupportFound, 2) . "%. Silakan turunkan nilai Minimum Support.")->withInput();
            }

            // 2-Itemsets (C2 -> L2) - Menghitung kombinasi pasangan produk
            $pairCounts = [];
            $itemsList = array_keys($frequent1Itemsets);
            $numItems = count($itemsList);

            foreach ($transactions as $t) {
                $tSet = array_flip($t); 
                for ($i = 0; $i < $numItems; $i++) {
                    if (!isset($tSet[$itemsList[$i]])) continue;
                    for ($j = $i + 1; $j < $numItems; $j++) {
                        if (isset($tSet[$itemsList[$j]])) {
                            $pair = [$itemsList[$i], $itemsList[$j]];
                            sort($pair);
                            $pairStr = implode(',', $pair);
                            $pairCounts[$pairStr] = ($pairCounts[$pairStr] ?? 0) + 1;
                        }
                    }
                }
            }

            $rules = [];
            foreach ($pairCounts as $pairStr => $count) {
                if ($count >= $minSupportCount) {
                    $pair = explode(',', $pairStr);
                    
                    /**
                     * RUMUS SUPPORT (Pasar Belanja):
                     * Support(A,B) = (Jumlah Transaksi mengandung A & B) / (Total Transaksi)
                     * Contoh: (300 / 1000) * 100 = 30%
                     */
                    $support = ($count / $totalTransactions) * 100;

                    // Hitung Confidence A -> B
                    /**
                     * RUMUS CONFIDENCE:
                     * Confidence(A -> B) = (Jumlah Transaksi mengandung A & B) / (Jumlah Transaksi mengandung A)
                     * Contoh: (300 / 800) * 100 = 37.5%
                     */
                    if (isset($frequent1Itemsets[$pair[0]]) && $frequent1Itemsets[$pair[0]] > 0) {
                        $confAB = ($count / $frequent1Itemsets[$pair[0]]) * 100;
                        if ($confAB >= $minConfidence) {
                            $rules[] = [
                                'if' => $pair[0],
                                'then' => $pair[1],
                                'support' => round($support, 2),
                                'confidence' => round($confAB, 2),
                            ];
                        }
                    }

                    // Hitung Confidence B -> A
                    if (isset($frequent1Itemsets[$pair[1]]) && $frequent1Itemsets[$pair[1]] > 0) {
                        $confBA = ($count / $frequent1Itemsets[$pair[1]]) * 100;
                        if ($confBA >= $minConfidence) {
                            $rules[] = [
                                'if' => $pair[1],
                                'then' => $pair[0],
                                'support' => round($support, 2),
                                'confidence' => round($confBA, 2),
                            ];
                        }
                    }
                }
            }

            // Jika ada pasangan item tapi tidak ada yang lolos confidence
            if (!empty($pairCounts) && empty($rules)) {
                return back()->with('failed', "Ditemukan pasangan produk yang sering dibeli bersama, namun tidak ada yang memenuhi Minimum Confidence $minConfidence%. Silakan turunkan nilai Minimum Confidence.")->withInput();
            }

            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 4);

            $months = [
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ];
            
            if ($mode === 'single') {
                $parts = explode('-', $bulan);
                $namaBulan = (isset($months[$parts[1]]) ? $months[$parts[1]] : $parts[1]) . ' ' . $parts[0];
            } else {
                $partsMulai = explode('-', $mulai);
                $partsAkhir = explode('-', $akhir);
                $namaMulai = (isset($months[$partsMulai[1]]) ? $months[$partsMulai[1]] : $partsMulai[1]) . ' ' . $partsMulai[0];
                $namaAkhir = (isset($months[$partsAkhir[1]]) ? $months[$partsAkhir[1]] : $partsAkhir[1]) . ' ' . $partsAkhir[0];
                $namaBulan = $namaMulai . ' - ' . $namaAkhir;
            }

            DB::transaction(function () use ($dataset, $minSupport, $minConfidence, $totalTransactions, $rules, $executionTime, $mode, $bulan, $mulai, $akhir, $namaBulan, $periodeInfo, $umkmId) {
                $hasil = AprioriHasil::create([
                    'keterangan' => 'Proses periode ' . $periodeInfo . ' pada ' . now()->format('d/m/Y H:i'),
                    'tgl_proses' => now(),
                    'min_support' => $minSupport,
                    'min_confidence' => $minConfidence,
                    'total_data' => $totalTransactions,
                    'total_rule' => count($rules),
                    'data_rules' => json_encode($rules),
                    'data_hasil' => json_encode($rules),
                    'waktu' => $executionTime . ' detik',
                    'bulan_data' => $mode === 'single' ? $bulan : $mulai . ' - ' . $akhir,
                    'bulan' => $periodeInfo,
                    'nama_bulan' => $namaBulan,
                    'diterapkan' => 0,
                    'umkm_id' => $umkmId,
                ]);

                foreach ($dataset as $row) {
                    AprioriHasilDataset::create([
                        'tgl_transaksi' => $row->tgl_transaksi,
                        'no_transaksi' => $row->no_transaksi,
                        'dataset' => $row->dataset,
                        'id_hasil' => $hasil->id,
                    ]);
                }
            });

            return redirect()->route('algoritma.hasil')->with('success', 'Perhitungan selesai. Silakan terapkan hasil jika sudah sesuai.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Apriori Hitung Error: ' . $e->getMessage());
            $errorMsg = $e->getMessage();
            if (str_contains($errorMsg, 'SQLSTATE[22001]')) {
                $errorMsg = "Data terlalu panjang untuk disimpan (Rentang bulan). Sistem telah mencoba memperbaiki skema, silakan coba lagi.";
            }
            return back()->with('failed', 'Terjadi kesalahan sistem: ' . $errorMsg)->withInput();
        }
    }

    /**
     * Terapkan hasil apriori tertentu sebagai rule aktif untuk rekomendasi
     */
    public function terapkan(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:apriori_hasil,id',
        ]);

        $id = (int) $request->input('id');

        // Simpan atau update apriori_aktif (scoped to UMKM)
        DB::table('apriori_aktif')->where('umkm_id', Auth::user()->umkm_id)->delete();
        DB::table('apriori_aktif')->insert([
            'apriori_hasil_id' => $id,
            'diterapkan_at' => now(),
            'umkm_id' => Auth::user()->umkm_id,
        ]);

        // Update status kolom diterapkan di apriori_hasil (scoped to UMKM)
        AprioriHasil::where('umkm_id', Auth::user()->umkm_id)->update(['diterapkan' => 0]);
        AprioriHasil::where('id', $id)->where('umkm_id', Auth::user()->umkm_id)->update(['diterapkan' => 1]);

        $row = AprioriHasil::where('id', $id)->where('umkm_id', Auth::user()->umkm_id)->firstOrFail();

        return redirect()->route('algoritma.hasil')
            ->with('success', 'Hasil apriori dari <strong>' . ($row->nama_bulan ?? $row->bulan_data ?? $row->tgl_proses->format('m/Y')) . '</strong> berhasil diterapkan sebagai rekomendasi aktif.');
    }

    /**
     * API: ambil rekomendasi produk berdasarkan rule aktif
     * Dipanggil saat kasir klik produk
     */
    public function rekomendasi(Request $request)
    {
        $produkNama = $request->input('nama');
        $umkmId = $request->input('umkm_id') ?: (Auth::check() ? Auth::user()->umkm_id : null);

        if (!$produkNama) {
            return response()->json(['rekomendasi' => []]);
        }

        if (!$umkmId) {
            return response()->json(['rekomendasi' => [], 'pesan' => 'UMKM ID tidak ditentukan.']);
        }

        // Ambil rule aktif (scoped to UMKM)
        $aktif = DB::table('apriori_aktif')->where('umkm_id', $umkmId)->first();
        if (!$aktif) {
            return response()->json(['rekomendasi' => [], 'pesan' => 'Belum ada rule apriori yang diterapkan.']);
        }

        $hasil = AprioriHasil::find($aktif->apriori_hasil_id);
        if (!$hasil) {
            return response()->json(['rekomendasi' => []]);
        }

        // Ambil gambar untuk produk utama (trigger)
        $mainMenu = \App\Models\Menu::withoutGlobalScopes()
            ->where('umkm_id', $umkmId)
            ->where('nama', 'LIKE', $produkNama)
            ->first();
        $mainGambar = $mainMenu ? $mainMenu->gambar : null;

        $rules = json_decode($hasil->data_rules, true);
        $rekomendasi = [];

        foreach ($rules as $rule) {
            if (strtolower($rule['if']) === strtolower($produkNama)) {
                // Cari gambar menu rekomendasi
                $recMenu = \App\Models\Menu::withoutGlobalScopes()
                    ->where('umkm_id', $umkmId)
                    ->where('nama', 'LIKE', $rule['then'])
                    ->first();
                $recGambar = $recMenu ? $recMenu->gambar : null;

                $rekomendasi[] = [
                    'produk' => $rule['then'],
                    'confidence' => $rule['confidence'],
                    'support' => $rule['support'],
                    'gambar' => $recGambar,
                ];
            }
        }

        // Urutkan berdasarkan confidence tertinggi, max 3
        usort($rekomendasi, fn($a, $b) => $b['confidence'] <=> $a['confidence']);
        $rekomendasi = array_slice($rekomendasi, 0, 3);

        $infoRule = $aktif ? [
            'bulan' => $hasil->nama_bulan ?? $hasil->bulan_data ?? $hasil->tgl_proses->format('m/Y'),
            'diterapkan' => \Carbon\Carbon::parse($aktif->diterapkan_at)->format('d/m/Y'),
        ] : null;

        return response()->json([
            'rekomendasi' => $rekomendasi,
            'info' => $infoRule,
            'main_gambar' => $mainGambar,
        ]);
    }

    public function delete(Request $request)
    {
        $id = (int) $request->input('id');

        // Jangan hapus kalau sedang aktif (scoped to UMKM)
        $aktif = DB::table('apriori_aktif')
            ->where('apriori_hasil_id', $id)
            ->where('umkm_id', Auth::user()->umkm_id)
            ->first();
            
        if ($aktif) {
            return redirect()->route('algoritma.hasil')
                ->with('failed', 'Tidak dapat menghapus hasil yang sedang diterapkan sebagai rekomendasi aktif.');
        }

        AprioriHasil::where('id', $id)->where('umkm_id', Auth::user()->umkm_id)->delete();

        return redirect()->route('algoritma.hasil')->with('success', 'Data dihapus.');
    }
}
