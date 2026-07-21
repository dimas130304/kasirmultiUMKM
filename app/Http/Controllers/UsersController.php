<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UsersController extends Controller
{
    public function index(): View
    {
        return view('users.index', [
            'title_web' => 'Daftar Pengguna',
            'users' => Login::active()->where('umkm_id', Auth::user()->umkm_id)->orderByDesc('id')->get(),
        ]);
    }

    public function dataUsers()
    {
        return response()->json(['data' => Login::active()->where('umkm_id', Auth::user()->umkm_id)->get()]);
    }

    public function tambah(): View
    {
        return view('users.form', ['title_web' => 'Tambah Pengguna', 'user' => null]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'pass' => 'required|string|min:6',
            'email' => ['required', 'email', Rule::unique('login', 'email')],
            'telepon' => 'required|string',
            'alamat' => 'required|string',
            'level' => ['required', Rule::in(['Admin', 'Kasir'])],
        ]);

        Login::create([
            'nama_user' => $validated['nama'],
            'user' => $validated['email'], // email sebagai username
            'pass' => password_hash($validated['pass'], PASSWORD_DEFAULT),
            'email' => $validated['email'],
            'email_login' => $validated['email'],
            'telepon' => $validated['telepon'],
            'alamat' => $validated['alamat'],
            'level' => $validated['level'],
            'foto' => '-',
            'tgl_bergabung' => now()->format('Y-m-d'),
            'umkm_id' => Auth::user()->umkm_id,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(Request $request): View
    {
        return view('users.form', [
            'title_web' => 'Edit Pengguna',
            'user' => Login::active()->where('umkm_id', Auth::user()->umkm_id)->findOrFail((int) $request->query('id')),
        ]);
    }

    public function upd(Request $request)
    {
        $user = Login::where('umkm_id', Auth::user()->umkm_id)->findOrFail((int) $request->input('id'));
        $validated = $request->validate([
            'nama' => 'required|string',
            'email' => ['required', 'email', Rule::unique('login', 'email')->ignore($user->id)],
            'telepon' => 'required|string',
            'alamat' => 'required|string',
            'level' => ['required', Rule::in(['Admin', 'Kasir'])],
            'pass' => 'nullable|string|min:6',
        ]);

        $user->nama_user = $validated['nama'];
        $user->user = $validated['email'];
        $user->email = $validated['email'];
        $user->email_login = $validated['email'];
        $user->telepon = $validated['telepon'];
        $user->alamat = $validated['alamat'];
        $user->level = $validated['level'];
        if (! empty($validated['pass'])) {
            $user->pass = password_hash($validated['pass'], PASSWORD_DEFAULT);
        }
        $user->save();

        return redirect()->route('users.edit', ['id' => $user->id])->with('success', 'Pengguna diperbarui.');
    }

    public function delete(Request $request)
    {
        Login::where('id', (int) $request->input('id'))
            ->where('umkm_id', Auth::user()->umkm_id)
            ->update(['deleted_at' => now()->format('Y-m-d H:i:s')]);

        return redirect()->route('users.index')->with('success', 'Pengguna dihapus.');
    }
}

