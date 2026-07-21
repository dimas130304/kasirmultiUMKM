<?php

use App\Http\Controllers\AlgoritmaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('menu-umkm/{code}', [LandingController::class, 'umkmMenu'])->name('landing.umkm-menu');
Route::post('menu-umkm/{code}/order', [LandingController::class, 'placeOrder'])->name('landing.place-order');
Route::get('menu-umkm/receipt/{no_bon}', [LandingController::class, 'viewReceipt'])->name('landing.view-receipt');
Route::get('algoritma/rekomendasi', [AlgoritmaController::class, 'rekomendasi'])->name('algoritma.rekomendasi');

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::post('login', [LoginController::class, 'proses'])->name('login.proses');
    Route::get('register', [RegisterController::class, 'index'])->name('register');
    Route::post('register', [RegisterController::class, 'proses'])->name('register.proses');
});

Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/', [SuperAdminController::class, 'index'])->name('index');
    Route::get('create', [SuperAdminController::class, 'create'])->name('create');
    Route::post('store', [SuperAdminController::class, 'store'])->name('store');
    Route::get('detail/{id}', [SuperAdminController::class, 'detail'])->name('detail');
    Route::get('edit/{id}', [SuperAdminController::class, 'edit'])->name('edit');
    Route::post('update/{id}', [SuperAdminController::class, 'update'])->name('update');
    Route::post('toggle-status/{id}', [SuperAdminController::class, 'toggleStatus'])->name('toggle-status');
});

Route::prefix('{umkm_code}')->group(function () {
    Route::get('auth-callback', [LoginController::class, 'authCallback'])->name('auth-callback');

    Route::middleware('auth')->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');

        Route::prefix('kasir')->name('kasir.')->group(function () {
                Route::get('/', [KasirController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'dtmenu', [MenuController::class, 'dtmenu'])->name('dtmenu');
                Route::post('store', [KasirController::class, 'store'])->name('store');
                Route::get('show', [KasirController::class, 'show'])->name('show');
                Route::get('print', [KasirController::class, 'print'])->name('print');
                Route::post('add-cart', [KasirController::class, 'addCart'])->name('add-cart');
                Route::post('add-cart-by-name', [KasirController::class, 'addCartByName'])->name('add-cart-by-name');
                Route::get('cart', [KasirController::class, 'cart'])->name('cart');
                Route::post('update-cart', [KasirController::class, 'updateCart'])->name('update-cart');
                Route::post('updateket-cart', [KasirController::class, 'updateketCart'])->name('updateket-cart');
                Route::get('cart-table', [KasirController::class, 'cartTable'])->name('cart-table');
                Route::post('del-cart', [KasirController::class, 'delCart'])->name('del-cart');
                Route::patch('update-status', [OrderController::class, 'updateStatus'])->name('update-status');
            });

        Route::prefix('order')->name('order.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('data-order', [OrderController::class, 'dataOrder'])->name('data-order');
            Route::get('edit', [OrderController::class, 'edit'])->name('edit');
            Route::post('updated', [OrderController::class, 'updated'])->name('updated');
            Route::post('add-cart', [OrderController::class, 'addCart'])->name('add-cart');
            Route::get('cart', [OrderController::class, 'cart'])->name('cart');
            Route::post('update-cart', [OrderController::class, 'updateCart'])->name('update-cart');
            Route::post('updateket-cart', [OrderController::class, 'updateketCart'])->name('updateket-cart');
            Route::get('cart-table', [OrderController::class, 'cartTable'])->name('cart-table');
            Route::post('del-cart', [OrderController::class, 'delCart'])->name('del-cart');
        });

        Route::prefix('customer')->name('customer.')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('index');
            Route::get('data-customer', [CustomerController::class, 'dataCustomer'])->name('data-customer');
            Route::get('cek-customer', [CustomerController::class, 'cekCustomer'])->name('cek-customer');
            Route::get('tambah', [CustomerController::class, 'tambah'])->name('tambah');
            Route::post('store', [CustomerController::class, 'store'])->name('store');
            Route::get('detail', [CustomerController::class, 'detail'])->name('detail');
            Route::get('edit', [CustomerController::class, 'edit'])->name('edit');
            Route::post('update', [CustomerController::class, 'update'])->name('update');
            Route::post('delete', [CustomerController::class, 'delete'])->name('delete');
        });

        Route::get('user', [UserController::class, 'index'])->name('user.index');
        Route::post('user/upd', [UserController::class, 'upd'])->name('user.upd');
        


        Route::middleware('admin')->group(function () {
            Route::get('home', [HomeController::class, 'index'])->name('home');

            Route::prefix('menu')->name('menu.')->group(function () {
                Route::get('/', [MenuController::class, 'index'])->name('index');
                Route::get('import', [MenuController::class, 'import'])->name('import');
                Route::post('proses-import', [MenuController::class, 'prosesImport'])->name('proses-import');
                Route::match(['get', 'post'], 'data-menu', [MenuController::class, 'dataMenu'])->name('data-menu');
                Route::get('stok', [MenuController::class, 'stok'])->name('stok');
                Route::get('persediaan', [MenuController::class, 'persediaan'])->name('persediaan');
                Route::match(['get', 'post'], 'get-menu', [MenuController::class, 'getMenu'])->name('get-menu');
                Route::post('pasok', [MenuController::class, 'pasok'])->name('pasok');
                Route::get('tambah', [MenuController::class, 'tambah'])->name('tambah');
                Route::post('store', [MenuController::class, 'store'])->name('store');
                Route::get('detail', [MenuController::class, 'detail'])->name('detail');
                Route::get('edit', [MenuController::class, 'edit'])->name('edit');
                Route::post('update', [MenuController::class, 'update'])->name('update');
                Route::post('delete', [MenuController::class, 'delete'])->name('delete');
            });

            Route::prefix('kategori')->name('kategori.')->group(function () {
                Route::get('/', [KategoriController::class, 'index'])->name('index');
                Route::post('store', [KategoriController::class, 'store'])->name('store');
                Route::post('update', [KategoriController::class, 'update'])->name('update');
                Route::post('delete', [KategoriController::class, 'delete'])->name('delete');
            });

            Route::prefix('users')->name('users.')->group(function () {
                Route::get('/', [UsersController::class, 'index'])->name('index');
                Route::get('data-users', [UsersController::class, 'dataUsers'])->name('data-users');
                Route::get('tambah', [UsersController::class, 'tambah'])->name('tambah');
                Route::post('add', [UsersController::class, 'add'])->name('add');
                Route::get('edit', [UsersController::class, 'edit'])->name('edit');
                Route::post('upd', [UsersController::class, 'upd'])->name('upd');
                Route::post('delete', [UsersController::class, 'delete'])->name('delete');
            });

            Route::prefix('laporan')->name('laporan.')->group(function () {
                Route::get('/', [LaporanController::class, 'index'])->name('index');
                Route::get('data-order', [LaporanController::class, 'dataOrder'])->name('data-order');
                Route::get('excel', [LaporanController::class, 'excel'])->name('excel');
                Route::get('export-excel', [LaporanController::class, 'exportExcel'])->name('export-excel');
                Route::get('export-pdf', [LaporanController::class, 'exportPdf'])->name('export-pdf');
                Route::get('cash', [LaporanController::class, 'cash'])->name('cash');
                Route::get('cash-excel', [LaporanController::class, 'cashFlowExcel'])->name('cash-excel');
                Route::get('cash-pdf', [LaporanController::class, 'pdf'])->name('cash-pdf');
                Route::get('pdf', [LaporanController::class, 'pdf'])->name('pdf');
                Route::get('produk', [LaporanController::class, 'produk'])->name('produk');
                Route::get('data-produk', [LaporanController::class, 'dataProduk'])->name('data-produk');
                Route::get('produk-excel', [LaporanController::class, 'produkExcel'])->name('produk-excel');
                Route::get('terlaris', [LaporanController::class, 'terlaris'])->name('terlaris');
            });

            Route::prefix('info')->name('info.')->group(function () {
                Route::get('/', [InfoController::class, 'index'])->name('index');
                Route::post('update', [InfoController::class, 'update'])->name('update');
                Route::get('print', [InfoController::class, 'print'])->name('print');
                Route::get('print-qr', [InfoController::class, 'printQr'])->name('print-qr');
            });

            Route::prefix('algoritma')->name('algoritma.')->group(function () {
                Route::get('/', [AlgoritmaController::class, 'index'])->name('index');
                Route::get('rule', [AlgoritmaController::class, 'rule'])->name('rule');
                Route::get('hasil', [AlgoritmaController::class, 'hasil'])->name('hasil');
                Route::get('detail/{id}', [AlgoritmaController::class, 'detail'])->name('detail');
                Route::post('proses', [AlgoritmaController::class, 'proses'])->name('proses');
                Route::post('import', [AlgoritmaController::class, 'import'])->name('import');
                Route::post('hitung', [AlgoritmaController::class, 'hitung'])->name('hitung');
                Route::post('reset', [AlgoritmaController::class, 'reset'])->name('reset');
                Route::post('delete', [AlgoritmaController::class, 'delete'])->name('delete');
                Route::post('terapkan', [AlgoritmaController::class, 'terapkan'])->name('terapkan');
            });

            Route::prefix('keuangan')->name('keuangan.')->group(function () {
                Route::get('/', [KeuanganController::class, 'index'])->name('index');
                Route::post('store', [KeuanganController::class, 'store'])->name('store');
                Route::post('update', [KeuanganController::class, 'update'])->name('update');
                Route::post('delete', [KeuanganController::class, 'delete'])->name('delete');
                Route::get('lain', [KeuanganController::class, 'lain'])->name('lain');
                Route::get('edit', [KeuanganController::class, 'edit'])->name('edit');
                Route::get('data', [KeuanganController::class, 'data'])->name('data');
                Route::get('excel', [KeuanganController::class, 'excel'])->name('excel');
                Route::post('store-lain', [KeuanganController::class, 'storeLain'])->name('store-lain');
                Route::post('update-lain', [KeuanganController::class, 'updateLain'])->name('update-lain');
                Route::post('delete-lain', [KeuanganController::class, 'deleteLain'])->name('delete-lain');
            });
        });
    });
});
