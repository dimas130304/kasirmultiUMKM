<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KategoriController extends Controller
{
    public function index(Request $request): View
    {
        $edit = $request->filled('id')
            ? Kategori::find((int) $request->id)
            : null;

        return view('kategori.index', [
            'title_web' => 'Kategori',
            'kat' => Kategori::orderBy('kategori')->get(),
            'edit' => $edit,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['kategori' => 'required|string|max:255']);
        Kategori::create(['kategori' => $request->kategori]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'kategori' => 'required|string|max:255',
        ]);

        Kategori::where('id', $request->id)->update(['kategori' => $request->kategori]);

        return redirect()->route('kategori.index', ['id' => $request->id])->with('success', 'Kategori berhasil diperbarui.');
    }

    public function delete(Request $request)
    {
        Kategori::where('id', (int) $request->input('id'))->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
