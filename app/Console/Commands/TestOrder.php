<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\LandingController;
use Illuminate\Http\Request;

class TestOrder extends Command
{
    protected $signature = 'test:order';
    protected $description = 'Test order placement';

    public function handle()
    {
        $controller = new LandingController();
        
        $request = Request::create('/menu-umkm/UMKM00001/order', 'POST', [
            'atas_nama' => 'dimass',
            'pesanan' => 'dine-in',
            'cart' => [
                [
                    'id_menu' => 1,
                    'qty' => 1,
                    'keterangan' => ''
                ]
            ]
        ]);

        try {
            $response = $controller->placeOrder($request, 'UMKM00001');
            $this->info("Response: " . $response->getContent());
        } catch (\Exception $e) {
            $this->error("Exception: " . $e->getMessage());
            $this->error("File: " . $e->getFile() . ":" . $e->getLine());
        }
    }
}
