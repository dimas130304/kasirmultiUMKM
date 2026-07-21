<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    if (!Schema::hasColumn('umkm', 'kode_umkm')) {
        Schema::table('umkm', function ($table) {
            $table->string('kode_umkm', 50)->nullable()->after('id');
        });
        echo "Added kode_umkm column.\n";
    } else {
        echo "kode_umkm column already exists.\n";
    }

    // Assign codes to existing rows that have NULL or empty kode_umkm
    $umkms = DB::table('umkm')->whereNull('kode_umkm')->orWhere('kode_umkm', '')->orderBy('id')->get();
    foreach ($umkms as $u) {
        $code = 'UMKM' . str_pad($u->id, 5, '0', STR_PAD_LEFT);
        DB::table('umkm')->where('id', $u->id)->update(['kode_umkm' => $code]);
        echo "Updated UMKM ID {$u->id} with code {$code}\n";
    }
    
    // Add unique constraint if not exists
    // (We make sure it's populated first)
    try {
        Schema::table('umkm', function ($table) {
            $table->string('kode_umkm', 50)->unique()->change();
        });
        echo "Made kode_umkm unique.\n";
    } catch (\Exception $e) {
        echo "Could not make unique (maybe already unique): " . $e->getMessage() . "\n";
    }

    print_r(DB::table('umkm')->get()->toArray());

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
