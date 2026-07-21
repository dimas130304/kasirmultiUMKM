<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    DB::statement("CREATE TABLE IF NOT EXISTS apriori_dataset (id int(11) NOT NULL AUTO_INCREMENT, tgl_transaksi varchar(255) DEFAULT NULL, no_transaksi varchar(255) DEFAULT NULL, dataset text DEFAULT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    DB::statement("CREATE TABLE IF NOT EXISTS apriori_hasil (id int(11) NOT NULL AUTO_INCREMENT, keterangan text DEFAULT NULL, tgl_proses timestamp NULL DEFAULT NULL, min_support varchar(11) NOT NULL, min_confidence varchar(11) NOT NULL, total_data int(11) NOT NULL, data_rules longtext DEFAULT NULL, data_hasil longtext DEFAULT NULL, waktu varchar(255) DEFAULT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    DB::statement("CREATE TABLE IF NOT EXISTS apriori_hasil_dataset (id int(11) NOT NULL AUTO_INCREMENT, tgl_transaksi varchar(255) DEFAULT NULL, no_transaksi varchar(255) DEFAULT NULL, dataset text DEFAULT NULL, id_hasil int(11) NOT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    echo "Tables created successfully\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
