<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterAlamatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_alamat')->insert([
            [
                'provinsi' => 'Jawa Barat',
                'kota' => 'Bandung',
                'kecamatan' => 'Rancasari',
                'kode_pos' => '40292',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'provinsi' => 'Jawa Barat',
                'kota' => 'Bandung',
                'kecamatan' => 'Babakan Ciparay',
                'kode_pos' => '40224',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'provinsi' => 'Jawa Timur',
                'kota' => 'Surabaya',
                'kecamatan' => 'Tegalsari',
                'kode_pos' => '60131',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'provinsi' => 'Jawa Tengah',
                'kota' => 'Semarang',
                'kecamatan' => 'Tegalsari',
                'kode_pos' => '50241',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
