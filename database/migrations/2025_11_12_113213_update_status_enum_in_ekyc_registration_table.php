<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Untuk SQLite, gunakan cara lain karena tidak support MODIFY COLUMN
        if (DB::getDriverName() === 'sqlite') {
            // SQLite tidak support enum, jadi kita skip atau gunakan string
            // Dalam testing, kita bisa skip ini
            return;
        }

        // Ubah enum dengan raw SQL karena Laravel tidak bisa ubah enum langsung via Blueprint
        DB::statement("ALTER TABLE ekyc_registrations MODIFY COLUMN status ENUM('draft', 'submitted', 'accepted', 'rejected') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // Rollback ke enum semula
        DB::statement("ALTER TABLE ekyc_registrations MODIFY COLUMN status ENUM('draft', 'submitted') DEFAULT 'draft'");
    }
};