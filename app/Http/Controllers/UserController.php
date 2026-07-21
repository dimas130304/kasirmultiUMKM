<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('user.edit', [
            'title_web' => 'Profil Saya',
            'user' => Login::find(Auth::id()),
        ]);
    }

    public function upd(Request $request)
    {
        $user = Login::findOrFail(Auth::id());
        $validated = $request->validate([
            'nama' => 'required|string',
            'user' => 'required|string',
            'email' => 'required|email',
            'telepon' => 'required|string',
            'alamat' => 'required|string',
            'pass' => 'nullable|string|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->nama_user = $validated['nama'];
        $user->user = $validated['user'];
        $user->email = $validated['email'];
        $user->telepon = $validated['telepon'];
        $user->alamat = $validated['alamat'];
        
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = 'user_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Delete old photo if exists
            if ($user->foto && $user->foto !== '-' && File::exists(public_path('assets/image/user/' . $user->foto))) {
                File::delete(public_path('assets/image/user/' . $user->foto));
            }
            
            $file->move(public_path('assets/image/user'), $filename);
            $user->foto = $filename;
        }

        if (! empty($validated['pass'])) {
            $user->pass = password_hash($validated['pass'], PASSWORD_DEFAULT);
        }
        $user->save();

        return redirect()->route('user.index')->with('success', 'Profil berhasil diperbarui.');
    }
}
