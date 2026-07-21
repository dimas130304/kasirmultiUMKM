<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Menu;
use App\Models\MenuStok;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    private function menuListQuery(Request $request): Builder
    {
        return Menu::query()
            ->leftJoin('kategori', 'menu.id_kategori', '=', 'kategori.id')
            ->select('menu.*', 'kategori.kategori as nama_kategori')
            ->when($request->filled('cari'), function ($q) use ($request) {
                $cari = $request->cari;
                $q->where(function ($q) use ($cari) {
                    $q->where('menu.nama', 'like', "%{$cari}%")
                        ->orWhere('menu.kode_menu', 'like', "%{$cari}%")
                        ->orWhere('kategori.kategori', 'like', "%{$cari}%");
                });
            })
            ->when($request->filled('id_kategori'), fn ($q) => $q->where('menu.id_kategori', (int) $request->id_kategori))
            ->orderBy('menu.nama');
    }

    public function index(): View
    {
        return view('menu.index', [
            'title_web' => 'Daftar Produk',
            'menus' => Menu::with('kategori')->orderBy('nama')->paginate(20),
            'kat' => Kategori::orderBy('kategori')->get(),
        ]);
    }

    public function dtmenu(Request $request): View
    {
        $page = max(1, (int) $request->input('pageHome', 1));
        $perPage = 12;
        $offset = ($page - 1) * $perPage;

        $query = Menu::query()
            ->leftJoin('kategori', 'menu.id_kategori', '=', 'kategori.id')
            ->select('menu.*', 'kategori.kategori as nama_kategori');

        if ($request->filled('id')) {
            $query->where('menu.id_kategori', (int) $request->id);
        } elseif ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('menu.nama', 'like', "%{$cari}%")
                    ->orWhere('kategori.kategori', 'like', "%{$cari}%");
            });
        }

        $hasil = $query->orderBy('menu.nama')->offset($offset)->limit($perPage)->get();

        return view('kasir.partials.menu-grid', ['hasil' => $hasil]);
    }

    public function import(): View
    {
        return view('menu.import', ['title_web' => 'Import Produk']);
    }

    public function prosesImport()
    {
        return redirect()->route('menu.index')->with('failed', 'Fitur import sedang dimigrasi. Gunakan aplikasi CI sementara atau tunggu update.');
    }

    public function dataMenu(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $length = max(1, min(150, (int) $request->input('length', 10)));
        $search = $request->input('search.value', '');

        $base = Menu::query()
            ->leftJoin('kategori', 'menu.id_kategori', '=', 'kategori.id')
            ->select('menu.*', 'kategori.kategori');

        $recordsTotal = (clone $base)->count();

        if ($search !== '') {
            $base->where(function ($q) use ($search) {
                $q->where('menu.kode_menu', 'like', "%{$search}%")
                    ->orWhere('kategori.kategori', 'like', "%{$search}%")
                    ->orWhere('menu.nama', 'like', "%{$search}%");
            });
        }

        if ($request->filled('id')) {
            $base->where('menu.id_kategori', (int) $request->id);
        }

        $recordsFiltered = (clone $base)->count();

        $orderCol = (int) ($request->input('order.0.column', 0));
        $orderDir = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $columns = ['menu.id', 'menu.gambar', 'menu.kode_menu', 'kategori.kategori', 'menu.nama', 'menu.harga_pokok', 'menu.harga_jual', 'menu.stok'];
        $orderBy = $columns[$orderCol] ?? 'menu.id';

        $rows = $base->orderBy($orderBy, $orderDir)
            ->offset($start)
            ->limit($length)
            ->get()
            ->map(fn ($row) => [
                'id' => $row->id,
                'gambar' => $row->gambar,
                'kode_menu' => $row->kode_menu,
                'kategori' => $row->kategori,
                'nama' => $row->nama,
                'harga_pokok' => $row->harga_pokok,
                'harga_jual' => $row->harga_jual,
                'stok' => $row->stok,
                'keterangan' => $row->keterangan,
            ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $rows,
        ]);
    }

    public function stok(Request $request): View
    {
        return view('menu.stok', [
            'title_web' => 'Entry Persediaan Produk',
            'menus' => $this->menuListQuery($request)->limit(500)->get(),
            'kat' => Kategori::orderBy('kategori')->get(),
        ]);
    }

    public function persediaan(Request $request): View
    {
        return view('menu.persediaan', [
            'title_web' => 'Daftar Persediaan Produk',
            'menus' => $this->menuListQuery($request)->paginate(20)->withQueryString(),
            'kat' => Kategori::orderBy('kategori')->get(),
        ]);
    }

    public function getMenu(Request $request): JsonResponse
    {
        $menu = Menu::find((int) $request->input('id'));
        if (! $menu) {
            return response()->json('');
        }

        return response()->json([[
            'id' => $menu->id,
            'nama' => $menu->nama,
            'stok' => $menu->stok,
            'kode_menu' => $menu->kode_menu,
        ]]);
    }

    public function pasok(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:menu,id',
            'stok' => 'required|integer|min:0',
            'stoka' => 'required|integer|min:0',
            'nama_menu' => 'nullable|string|max:255',
        ]);

        $id = (int) $validated['id'];
        $stokBaru = (int) $validated['stok'];
        $stokLama = (int) $validated['stoka'];

        MenuStok::create([
            'menu_id' => $id,
            'stok_awal' => $stokBaru,
            'stok_akhir' => $stokLama,
            'date' => now()->format('Y-m-d'),
            'periode' => now()->format('Y-m'),
        ]);

        Menu::where('id', $id)->update(['stok' => $stokBaru]);

        $nama = $validated['nama_menu'] ?? Menu::find($id)?->nama ?? 'produk';

        return redirect()->route('menu.stok')->with('success', "Berhasil update stok {$nama}!");
    }

    public function tambah(): View
    {
        $last = Menu::orderByDesc('id')->first();
        $next = $last ? $last->id + 1 : 1;

        return view('menu.form', [
            'title_web' => 'Tambah Produk',
            'kat' => Kategori::orderBy('kategori')->get(),
            'kode' => 'P000'.$next,
            'menu' => null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kategori' => 'required|integer',
            'kode_menu' => 'required|string|max:50',
            'nama' => 'required|string|max:255',
            'harga_pokok' => 'required|integer|min:0',
            'harga_jual' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048',
        ]);

        $data = array_merge($validated, [
            'stok' => 0,
            'created_at' => now(),
            'gambar' => '-',
        ]);

        if ($request->hasFile('gambar')) {
            $name = 'produk_'.time().'.'.$request->file('gambar')->extension();
            $request->file('gambar')->move(public_path('assets/image/produk'), $name);
            $data['gambar'] = $name;
        }

        Menu::create($data);

        return redirect()->route('menu.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function detail(Request $request): View
    {
        $menu = Menu::with('kategori')->findOrFail((int) $request->query('id'));

        return view('menu.detail', ['title_web' => 'Detail Produk', 'menu' => $menu]);
    }

    public function edit(Request $request): View
    {
        $menu = Menu::findOrFail((int) $request->query('id'));

        return view('menu.form', [
            'title_web' => 'Edit Produk',
            'kat' => Kategori::orderBy('kategori')->get(),
            'kode' => $menu->kode_menu,
            'menu' => $menu,
        ]);
    }

    public function update(Request $request)
    {
        $menu = Menu::findOrFail((int) $request->input('id'));
        $validated = $request->validate([
            'id_kategori' => 'required|integer',
            'kode_menu' => 'required|string|max:50',
            'nama' => 'required|string|max:255',
            'harga_pokok' => 'required|integer|min:0',
            'harga_jual' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $name = 'produk_'.time().'.'.$request->file('gambar')->extension();
            $request->file('gambar')->move(public_path('assets/image/produk'), $name);
            $validated['gambar'] = $name;
        }

        $menu->update($validated);

        return redirect()->route('menu.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function delete(Request $request)
    {
        Menu::where('id', (int) $request->input('id'))->delete();

        return redirect()->route('menu.index')->with('success', 'Produk berhasil dihapus.');
    }
}
