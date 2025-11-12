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
        // Ubah enum dengan raw SQL karena Laravel tidak bisa ubah enum langsung via Blueprint
        DB::statement("ALTER TABLE ekyc_registrations MODIFY COLUMN status ENUM('draft', 'submitted', 'accepted', 'rejected') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback ke enum semula
        DB::statement("ALTER TABLE ekyc_registrations MODIFY COLUMN status ENUM('draft', 'submitted') DEFAULT 'draft'");
    }
};