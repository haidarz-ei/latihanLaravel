<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('landing_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('Unique key, contoh: hero_title, site_title');
            $table->text('value')->nullable()->comment('Value dari settingan, bisa berupa teks atau json string untuk multi-field');
            $table->string('type')->default('string')->comment('text, image, url, json');
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_settings');
    }
};

/*
Catatan:
- Gunakan value longText agar bisa menyimpan paragraf panjang atau JSON.
- key unik memudahkan query where('key','hero_title').
*/