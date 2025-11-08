<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('ekyc_registrations', function (Blueprint $table) {
            $table->string('provinsi')->nullable()->after('asal_sma');
            $table->string('kota_kab')->nullable()->after('provinsi');
            $table->string('kecamatan')->nullable()->after('kota_kab');
            $table->string('kode_pos')->nullable()->after('kecamatan');
            $table->string('nama_ibu')->nullable()->after('kode_pos');
            $table->string('sumber_informasi')->nullable()->after('nama_ibu');
        });
    }

    public function down(): void {
        Schema::table('ekyc_registrations', function (Blueprint $table) {
            $table->dropColumn(['provinsi', 'kota_kab', 'kecamatan', 'kode_pos', 'nama_ibu', 'sumber_informasi']);
        });
    }
};
