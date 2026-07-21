<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Kategori;
use App\Models\Login;
use App\Models\Menu;
use App\Models\Transaksi;
use App\Models\TransaksiProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $year = $request->input('thn', date('Y'));
        
        // Stats
        $stats = [
            'total_kategori' => Kategori::count(),
            'total_menu' => Menu::count(),
            'total_customer' => Customer::count(),
            'total_transaksi' => Transaksi::count(),
            'total_pendapatan' => Transaksi::sum('grandtotal'),
            'transaksi_hari_ini' => Transaksi::whereDate('date', date('Y-m-d'))->count(),
            'pendapatan_hari_ini' => Transaksi::whereDate('date', date('Y-m-d'))->sum('grandtotal'),
        ];

        // Chart Data (Monthly Sales)
        $monthlySales = Transaksi::select(
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(grandtotal) as total')
            )
            ->whereYear('date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlySales[$i] ?? 0;
        }

        // Recent Transactions
        $recentTransactions = Transaksi::with('kasir')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        // Best Sellers
        $bestSellers = TransaksiProduk::select('nama_menu', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('nama_menu')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $availableYears = Transaksi::select(DB::raw('DISTINCT YEAR(date) as year'))
            ->orderByDesc('year')
            ->pluck('year');

        return view('home.index', [
            'title_web' => 'Dashboard Overview',
            'stats' => $stats,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'recentTransactions' => $recentTransactions,
            'bestSellers' => $bestSellers,
            'years' => $availableYears,
            'selectedYear' => $year,
        ]);
    }
}
