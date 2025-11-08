<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('ekyc_registrations', function (Blueprint $table) {
            $table->text('alamat_domisili')->nullable()->after('sumber_informasi');
        });
    }

    public function down(): void {
        Schema::table('ekyc_registrations', function (Blueprint $table) {
            $table->dropColumn('alamat_domisili');
        });
    }
};
