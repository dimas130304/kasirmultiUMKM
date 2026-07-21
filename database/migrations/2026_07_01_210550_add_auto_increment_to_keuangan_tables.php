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
        DB::statement('ALTER TABLE `keuangan_lainnya` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;');
        DB::statement('ALTER TABLE `keuangan_ledger` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE `keuangan_lainnya` MODIFY `id` INT(11) NOT NULL;');
        DB::statement('ALTER TABLE `keuangan_ledger` MODIFY `id` INT(11) NOT NULL;');
    }
};
