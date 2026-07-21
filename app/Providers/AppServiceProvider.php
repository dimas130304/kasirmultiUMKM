<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('layouts.app', function ($view) {
            if (! auth()->check()) {
                return;
            }
            
            $today = now()->toDateString();
            $umkmId = auth()->user()->umkm_id;
            
            // Hitung berdasarkan pesanan hari ini untuk badge sidebar
            // Gunakan query builder langsung untuk menghindari isu scope jika ada
            $orderTotal = \App\Models\Transaksi::where('umkm_id', $umkmId)
                ->whereDate('date', $today)
                ->count();
                
            $orderDitempat = \App\Models\Transaksi::where('umkm_id', $umkmId)
                ->where('pesanan', 'Ditempat')
                ->whereDate('date', $today)
                ->count();
                
            $orderBooking = \App\Models\Transaksi::where('umkm_id', $umkmId)
                ->where('pesanan', 'Booking')
                ->whereDate('date', $today)
                ->count();
            
            $view->with([
                'sidebarTotal' => $orderTotal,
                'sidebarDitempat' => $orderDitempat,
                'sidebarBooking' => $orderBooking,
            ]);
        });
    }
}
