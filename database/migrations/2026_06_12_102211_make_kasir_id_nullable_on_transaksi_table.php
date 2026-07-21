<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE transaksi MODIFY kasir_id int(11) NULL');
        DB::statement('ALTER TABLE transaksi MODIFY customer_id int(11) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE transaksi MODIFY kasir_id int(11) NOT NULL');
        DB::statement('ALTER TABLE transaksi MODIFY customer_id int(11) NOT NULL');
    }
};
