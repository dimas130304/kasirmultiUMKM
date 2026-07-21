<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        return view('customer.index', [
            'title_web' => 'Customer',
            'customers' => Customer::orderByDesc('id')->paginate(20),
        ]);
    }

    public function dataCustomer()
    {
        return response()->json(['data' => Customer::orderBy('nama')->get()]);
    }

    public function cekCustomer(Request $request)
    {
        $c = Customer::where('nama', 'like', '%'.$request->input('term', '').'%')->limit(10)->get();

        return response()->json($c);
    }

    public function tambah(): View
    {
        return view('customer.form', ['title_web' => 'Tambah Customer', 'customer' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'hp' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string',
        ]);
        $last = Customer::orderByDesc('id')->first();
        $data['kode_customer'] = 'C'.str_pad((string) (($last?->id ?? 0) + 1), 4, '0', STR_PAD_LEFT);
        Customer::create($data);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil ditambahkan.');
    }

    public function detail(Request $request): View
    {
        return view('customer.detail', [
            'title_web' => 'Detail Customer',
            'customer' => Customer::findOrFail((int) $request->query('id')),
        ]);
    }

    public function edit(Request $request): View
    {
        return view('customer.form', [
            'title_web' => 'Edit Customer',
            'customer' => Customer::findOrFail((int) $request->query('id')),
        ]);
    }

    public function update(Request $request)
    {
        $customer = Customer::findOrFail((int) $request->input('id'));
        $customer->update($request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'hp' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string',
        ]));

        return redirect()->route('customer.index')->with('success', 'Customer berhasil diperbarui.');
    }

    public function delete(Request $request)
    {
        Customer::where('id', (int) $request->input('id'))->delete();

        return redirect()->route('customer.index')->with('success', 'Customer berhasil dihapus.');
    }
}
