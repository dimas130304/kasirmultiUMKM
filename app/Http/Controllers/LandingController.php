<?php

namespace App\Http\Controllers;

use App\Models\Login;
use App\Models\Menu;
use App\Models\ProfilToko;
use App\Models\Transaksi;
use App\Models\TransaksiProduk;
use App\Models\Umkm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectLoggedIn();
        }

        $umkms = Umkm::where('status', 'aktif')->with(['profilToko', 'users'])->get();

        return view('landing.index', [
            'title_web' => 'KASIR MULTI-UMKM & Multi-UMKM',
            'umkms' => $umkms,
            'menu_count' => Menu::withoutGlobalScopes()->count(),
            'user_count' => Login::active()->count(),
        ]);
    }

    public function umkmMenu($code): View
    {
        $umkm = Umkm::where('kode_umkm', $code)->where('status', 'aktif')->with('users')->firstOrFail();
        $profil = ProfilToko::withoutGlobalScopes()->where('umkm_id', $umkm->id)->first();
        $menus = Menu::withoutGlobalScopes()->where('umkm_id', $umkm->id)->with('kategori')->get();
        $categories = \App\Models\Kategori::withoutGlobalScopes()->where('umkm_id', $umkm->id)->get();

        return view('landing.menu', [
            'title_web' => 'Menu ' . $umkm->nama_umkm,
            'umkm' => $umkm,
            'profil' => $profil,
            'menus' => $menus,
            'categories' => $categories,
        ]);
    }

    public function placeOrder(Request $request, $code)
    {
        $request->validate([
            'atas_nama' => 'required|string|max:100',
            'pesanan' => 'required|in:dine-in,takeaway',
            'cart' => 'required|array',
            'cart.*.id_menu' => 'required|integer',
            'cart.*.qty' => 'required|integer|min:1',
        ]);

        $umkm = Umkm::where('kode_umkm', $code)->where('status', 'aktif')->firstOrFail();
        $profil = ProfilToko::withoutGlobalScopes()->where('umkm_id', $umkm->id)->first();
        
        $pajakPersen = $profil ? (int)$profil->pajak : 0;
        $diskonPersen = $profil ? (int)$profil->diskon : 0;

        $last = Transaksi::withoutGlobalScopes()->orderByDesc('id')->first();
        $nextId = $last ? $last->id + 1 : 1;
        $noBon = 'B00' . $nextId;

        $totalQty = 0;
        $grandmodal = 0;
        $subtotal = 0;
        $lines = [];
        $stockUpdates = [];

        foreach ($request->input('cart') as $item) {
            $menu = Menu::withoutGlobalScopes()->where('umkm_id', $umkm->id)->find($item['id_menu']);
            if (!$menu) {
                return response()->json(['error' => 'Menu tidak valid'], 400);
            }
            $qty = (int)$item['qty'];
            if ($qty > $menu->stok) {
                return response()->json(['error' => 'Stok menu ' . $menu->nama . ' tidak mencukupi'], 400);
            }
            $totalQty += $qty;
            $grandmodal += $menu->harga_pokok * $qty;
            $subtotal += $menu->harga_jual * $qty;

            $lines[] = [
                'no_bon' => $noBon,
                'kode_menu' => $menu->kode_menu,
                'nama_menu' => $menu->nama,
                'kategori' => $menu->kategori->kategori ?? '-',
                'qty' => $qty,
                'harga_beli' => $menu->harga_pokok,
                'harga_jual' => $menu->harga_jual,
                'keterangan' => $item['keterangan'] ?? '',
                'umkm_id' => $umkm->id,
                'created_at' => now(),
                'date' => now()->toDateString(),
                'periode' => now()->format('Y-m'),
                'year' => now()->format('Y'),
            ];

            $stockUpdates[] = [
                'id' => $menu->id,
                'stok' => $menu->stok - $qty
            ];
        }

        if (empty($lines)) {
            return response()->json(['error' => 'Keranjang kosong'], 400);
        }

        $diskon = ($subtotal * $diskonPersen) / 100;
        $pajak = (($subtotal - $diskon) * $pajakPersen) / 100;
        $grandtotal = $subtotal - $diskon + $pajak;

        DB::transaction(function () use ($stockUpdates, $lines, $noBon, $umkm, $request, $grandmodal, $grandtotal, $totalQty, $diskon, $pajak) {
            foreach ($stockUpdates as $row) {
                Menu::withoutGlobalScopes()->where('id', $row['id'])->update(['stok' => $row['stok']]);
            }
            if ($lines) {
                TransaksiProduk::insert($lines);
            }
            Transaksi::create([
                'no_bon' => $noBon,
                'kasir_id' => null,
                'customer_id' => null,
                'atas_nama' => $request->input('atas_nama'),
                'pesanan' => $request->input('pesanan'),
                'status' => 'Bayar Nanti',
                'diskon' => $diskon,
                'pajak' => $pajak,
                'voucher' => 0,
                'grandmodal' => $grandmodal,
                'grandtotal' => $grandtotal,
                'total_qty' => $totalQty,
                'dibayar' => 0,
                'created_at' => now(),
                'date' => now()->toDateString(),
                'periode' => now()->format('Y-m'),
                'year' => now()->format('Y'),
                'umkm_id' => $umkm->id,
            ]);
        });

        return response()->json([
            'no_bon' => $noBon,
            'redirect_url' => route('landing.view-receipt', $noBon)
        ]);
    }

    public function viewReceipt($no_bon): View
    {
        $transaksi = Transaksi::withoutGlobalScopes()->where('no_bon', $no_bon)->firstOrFail();
        $umkm = Umkm::findOrFail($transaksi->umkm_id);
        $profil = ProfilToko::withoutGlobalScopes()->where('umkm_id', $umkm->id)->first();
        $items = TransaksiProduk::withoutGlobalScopes()->where('no_bon', $no_bon)->get();

        return view('landing.receipt', [
            'title_web' => 'Struk Pembayaran #' . $no_bon,
            'transaksi' => $transaksi,
            'umkm' => $umkm,
            'profil' => $profil,
            'items' => $items,
        ]);
    }

    private function redirectLoggedIn(): RedirectResponse
    {
        $user = Auth::user();

        if ($user->level === 'SuperAdmin') {
            return redirect()->route('superadmin.index');
        }

        $umkm = $user->umkm;
        $umkmCode = $umkm ? $umkm->kode_umkm : null;

        if (!$umkmCode) {
            return redirect()->route('landing');
        }

        if ($user->level === 'Admin') {
            return redirect()->route('home', ['umkm_code' => $umkmCode]);
        }

        return redirect()->route('kasir.index', ['umkm_code' => $umkmCode]);
    }
}
