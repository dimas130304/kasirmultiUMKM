<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Transaksi::query()
            ->leftJoin('customer', 'transaksi.customer_id', '=', 'customer.id')
            ->leftJoin('login', 'transaksi.kasir_id', '=', 'login.id')
            ->select('transaksi.*', 'customer.nama as nama_customer', 'customer.hp as hp', 'login.nama_user');

        if ($request->jenis == '1') {
            $query->where('pesanan', 'Ditempat');
        } elseif ($request->jenis == '2') {
            $query->where('pesanan', 'Booking');
        } elseif ($request->jenis == '3') {
            $query->where('pesanan', 'Delivery');
        } elseif ($request->jenis == '4') {
            $query->where('status', 'Bayar Nanti');
        }

        // Date filters
        if ($request->filled('dari')) {
            $query->whereDate('transaksi.date', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('transaksi.date', '<=', $request->sampai);
        }

        // If no date filters and no jenis filter, default to today
        if (!$request->filled('dari') && !$request->filled('sampai') && !$request->jenis) {
            $query->whereDate('date', today());
        }

        return view('order.index', [
            'title_web' => 'Daftar Order',
            'orders' => $query->orderByDesc('transaksi.id')->paginate(25)->withQueryString(),
            'jenis' => $request->jenis,
        ]);
    }

    public function dataOrder()
    {
        return response()->json(['data' => []]);
    }

    public function edit(Request $request): View
    {
        $trx = Transaksi::where('no_bon', $request->query('id'))->firstOrFail();

        return view('order.edit', ['title_web' => 'Edit Order', 'trx' => $trx]);
    }

    public function updated(Request $request)
    {
        return redirect()->route('order.index')->with('success', 'Order diperbarui.');
    }

    /**
     * API: Update Transaction Status
     */
    public function updateStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|integer|exists:transaksi,id',
                'status' => 'required|string',
            ]);

            $trx = Transaksi::findOrFail($validated['id']);
            $newStatus = ucfirst(strtolower($validated['status']));

            // Mapping khusus jika input adalah "bayar nanti"
            if (strtolower($validated['status']) === 'bayar nanti') {
                $newStatus = 'Bayar Nanti';
            }

            // Logika Bisnis: Cegah perubahan dari "Lunas" ke status lain kecuali Admin
            if ($trx->status === 'Lunas' && $newStatus !== 'Lunas') {
                if (auth()->user()->level !== 'Admin') {
                    return response()->json([
                        'error' => 'Hanya Admin yang dapat mengubah status transaksi yang sudah Lunas.'
                    ], 403);
                }
            }

            $updateData = [
                'status' => $newStatus,
                'status_updated_at' => now(),
                'status_updated_by' => auth()->id(),
            ];

            // Jika status diubah menjadi Lunas, pastikan kolom 'dibayar' diperbarui setara grandtotal
            if ($newStatus === 'Lunas') {
                $updateData['dibayar'] = $trx->grandtotal;
            }

            $trx->update($updateData);

            return response()->json([
                'message' => 'Status transaksi berhasil diperbarui menjadi ' . $newStatus,
                'data' => $trx->fresh()
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Input tidak valid.',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan pada server.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function addCart(Request $request)
    {
        return app(KasirController::class)->addCart($request);
    }

    public function cart(Request $request)
    {
        return app(KasirController::class)->cart();
    }

    public function updateCart(Request $request)
    {
        return app(KasirController::class)->updateCart($request);
    }

    public function updateketCart(Request $request)
    {
        return app(KasirController::class)->updateketCart($request);
    }

    public function cartTable(Request $request)
    {
        return app(KasirController::class)->cartTable();
    }

    public function delCart(Request $request)
    {
        return app(KasirController::class)->delCart($request);
    }
}
