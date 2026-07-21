<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Kategori;
use App\Models\Keranjang;
use App\Models\Menu;
use App\Models\ProfilToko;
use App\Models\Transaksi;
use App\Models\TransaksiProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KasirController extends Controller
{
    public function index(Request $request): View
    {
        $last = Transaksi::orderByDesc('id')->first();
        $nextId = $last ? $last->id + 1 : 1;

        $query = Menu::query()
            ->leftJoin('kategori', 'menu.id_kategori', '=', 'kategori.id')
            ->select('menu.*', 'kategori.kategori as nama_kategori');

        if ($request->filled('id')) {
            $query->where('menu.id_kategori', (int) $request->id);
        }
        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('menu.nama', 'like', "%{$cari}%")
                    ->orWhere('kategori.kategori', 'like', "%{$cari}%");
            });
        }

        $total = (clone $query)->count();
        $halperpage = 12;
        $pages = max(1, (int) ceil($total / $halperpage));

        return view('kasir.index', [
            'title_web' => 'Kasir',
            'kat' => Kategori::orderBy('kategori')->get(),
            'no_bon' => 'B00'.$nextId,
            'pp' => ProfilToko::first(),
            'halperpage' => $halperpage,
            'pages' => $pages,
            'filterId' => $request->id,
            'filterCari' => $request->cari,
            'filterNm' => $request->nm,
            'customers' => Customer::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $noBon = $request->input('no_bon');
        $voucher = (int) preg_replace('/\D/', '', (string) $request->input('voucher', 0));
        $grandtotal = (int) preg_replace('/\D/', '', (string) $request->input('grandtotal', 0));
        $dibayar = (int) preg_replace('/\D/', '', (string) $request->input('dibayar', 0));
        $status = $request->input('status');

        if (in_array($status, ['Lunas', 'Debit BCA', 'Debit Mandiri', 'Debit BNI'], true)) {
            if ($dibayar === 0 || $dibayar < $grandtotal) {
                return response('Kurang', 400);
            }
        }

        $cart = Keranjang::where('login_id', Auth::id())->get();
        if ($cart->isEmpty()) {
            return response('Kosong', 400);
        }

        $totalQty = 0;
        $grandmodal = 0;
        $lines = [];
        $stockUpdates = [];

        foreach ($cart as $item) {
            $menu = Menu::find($item->id_menu);
            if (! $menu) {
                continue;
            }
            $qty = (int) $item->qty;
            $totalQty += $qty;
            $stockUpdates[] = ['id' => $menu->id, 'stok' => $menu->stok - $qty];
            $grandmodal += $item->harga_beli * $qty;
            $lines[] = [
                'no_bon' => $noBon,
                'kode_menu' => $item->kode_menu,
                'nama_menu' => $item->nama,
                'kategori' => $item->kategori,
                'qty' => $qty,
                'harga_beli' => $item->harga_beli,
                'harga_jual' => $item->harga_jual,
                'keterangan' => $item->keterangan,
                'umkm_id' => Auth::user()->umkm_id,
                'created_at' => now(),
                'date' => now()->toDateString(),
                'periode' => now()->format('Y-m'),
                'year' => now()->format('Y'),
            ];
        }

        DB::transaction(function () use ($stockUpdates, $lines, $noBon, $request, $voucher, $grandtotal, $totalQty, $grandmodal, $dibayar) {
            foreach ($stockUpdates as $row) {
                Menu::where('id', $row['id'])->update(['stok' => $row['stok']]);
            }
            if ($lines) {
                TransaksiProduk::insert($lines);
            }
            Transaksi::create([
                'no_bon' => $noBon,
                'kasir_id' => Auth::id(),
                'customer_id' => $request->input('customer_id'),
                'atas_nama' => $request->input('atas_nama'),
                'pesanan' => $request->input('pesanan'),
                'status' => $request->input('status'),
                'diskon' => $request->input('diskon'),
                'pajak' => $request->input('pajak'),
                'voucher' => $voucher,
                'grandmodal' => $grandmodal,
                'grandtotal' => $grandtotal,
                'total_qty' => $totalQty,
                'dibayar' => $dibayar,
                'created_at' => now(),
                'date' => now()->toDateString(),
                'periode' => now()->format('Y-m'),
                'year' => now()->format('Y'),
            ]);
            Keranjang::where('login_id', Auth::id())->delete();
        });

        return response($noBon);
    }

    public function show(Request $request): View
    {
        $noBon = $request->query('id');
        $t = Transaksi::query()
            ->leftJoin('customer', 'transaksi.customer_id', '=', 'customer.id')
            ->select('customer.nama', 'customer.hp', 'transaksi.*')
            ->where('transaksi.no_bon', $noBon)
            ->firstOrFail();
            
        // Mengambil detail produk tanpa filter global scope umkm agar item lama tetap muncul
        $tp = TransaksiProduk::withoutGlobalScope('umkm')->where('no_bon', $t->no_bon)->get();

        return view('kasir.cetak', [
            't' => $t,
            'tp' => $tp,
            'pp' => ProfilToko::first(),
        ]);
    }

    public function print(Request $request): View
    {
        $noBon = $request->query('id');
        $t = Transaksi::query()
            ->leftJoin('customer', 'transaksi.customer_id', '=', 'customer.id')
            ->select('customer.nama', 'transaksi.*')
            ->where('transaksi.no_bon', $noBon)
            ->firstOrFail();
            
        $tp = TransaksiProduk::withoutGlobalScope('umkm')->where('no_bon', $t->no_bon)->get();

        return view('kasir.cetak', [
            't' => $t,
            'tp' => $tp,
            'pp' => ProfilToko::first(),
            'cetak' => $request->input('cetak'),
            'os' => $request->input('os'),
        ]);
    }

    public function addCart(Request $request)
    {
        $menu = Menu::query()
            ->leftJoin('kategori', 'menu.id_kategori', '=', 'kategori.id')
            ->select('kategori.kategori as nama_kategori', 'menu.*')
            ->where('menu.id', (int) $request->input('id'))
            ->first();

        if (! $menu) {
            return response('', 404);
        }

        $keranjang = Keranjang::where('id_menu', $menu->id)
            ->where('login_id', Auth::id())
            ->first();

        $data = [
            'id_menu' => $menu->id,
            'kode_menu' => $menu->kode_menu,
            'kategori' => $menu->nama_kategori,
            'nama' => $menu->nama,
            'gambar' => $menu->gambar,
            'harga_beli' => $menu->harga_pokok,
            'harga_jual' => $menu->harga_jual,
            'login_id' => Auth::id(),
        ];

        if (! $keranjang) {
            Keranjang::create(array_merge($data, ['qty' => 1]));
        } else {
            $keranjang->update(array_merge($data, ['qty' => $keranjang->qty + 1]));
        }

        return response('', 204);
    }

    public function cart(): View|string
    {
        $items = Keranjang::where('login_id', Auth::id())->orderBy('id')->get();
        if ($items->isEmpty()) {
            return '<center><b class="text-red-600">*** Belum ada item yang dipilih ***</b></center>';
        }

        return view('kasir.partials.keranjang', ['items' => $items]);
    }

    public function updateCart(Request $request)
    {
        $menu = Menu::query()
            ->leftJoin('kategori', 'menu.id_kategori', '=', 'kategori.id')
            ->select('kategori.kategori as nama_kategori', 'menu.*')
            ->where('menu.id', (int) $request->query('id'))
            ->first();

        if (! $menu) {
            return response('', 404);
        }

        $keranjang = Keranjang::where('id_menu', $menu->id)
            ->where('login_id', Auth::id())
            ->first();

        if (! $keranjang) {
            return response('', 404);
        }

        $qt = (int) $request->input('qt', 1);
        if ($menu->stok <= $qt && $request->input('type') !== 'minus') {
            return response('<script>alert("Stok produk tidak mencukupi.");</script>');
        }

        $item = [
            'id_menu' => $menu->id,
            'kode_menu' => $menu->kode_menu,
            'kategori' => $menu->nama_kategori,
            'nama' => $menu->nama,
            'gambar' => $menu->gambar,
            'keterangan' => $keranjang->keterangan,
            'harga_beli' => $menu->harga_pokok,
            'harga_jual' => $menu->harga_jual,
        ];

        $type = $request->input('type');
        if ($type === 'minus') {
            $qty = max(1, $keranjang->qty - 1);
        } elseif ($type === 'keyup') {
            $qty = max(1, min($qt, $menu->stok));
        } else {
            $qty = $keranjang->qty + 1;
        }

        $keranjang->update(array_merge($item, ['qty' => $qty]));

        return response('', 204);
    }

    public function updateketCart(Request $request)
    {
        Keranjang::where('id_menu', (int) $request->query('id'))
            ->where('login_id', Auth::id())
            ->update(['keterangan' => $request->input('qt')]);

        return response('', 204);
    }

    public function cartTable(): View|string
    {
        $items = Keranjang::where('login_id', Auth::id())->orderBy('id')->get();
        if ($items->isEmpty()) {
            return '<center><b class="text-red-600">*** Belum ada item yang dipilih ***</b></center>';
        }

        return view('kasir.partials.table', ['items' => $items]);
    }

    public function addCartByName(Request $request)
    {
        $nama = $request->input('nama');
        $menu = Menu::query()
            ->leftJoin('kategori', 'menu.id_kategori', '=', 'kategori.id')
            ->select('kategori.kategori as nama_kategori', 'menu.*')
            ->where('menu.nama', $nama)
            ->first();

        if (! $menu) {
            return response()->json(['error' => 'Produk tidak ditemukan.'], 404);
        }

        $keranjang = Keranjang::where('id_menu', $menu->id)
            ->where('login_id', Auth::id())
            ->first();

        $data = [
            'id_menu' => $menu->id,
            'kode_menu' => $menu->kode_menu,
            'kategori' => $menu->nama_kategori,
            'nama' => $menu->nama,
            'gambar' => $menu->gambar,
            'harga_beli' => $menu->harga_pokok,
            'harga_jual' => $menu->harga_jual,
            'login_id' => Auth::id(),
        ];

        if (! $keranjang) {
            Keranjang::create(array_merge($data, ['qty' => 1]));
        } else {
            $keranjang->update(array_merge($data, ['qty' => $keranjang->qty + 1]));
        }

        return response()->json(['success' => true]);
    }

    public function delCart(Request $request)
    {
        Keranjang::where('id_menu', $request->input('id_menu'))
            ->where('login_id', Auth::id())
            ->delete();

        return response('', 204);
    }
}
