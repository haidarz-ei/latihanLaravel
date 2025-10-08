<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/AdminKey.json');

        if (!File::exists($path)) {
            throw new \Exception("Admin password tidak ditemukan! Buat file AdminKey.json dulu.");
        }

        $json = File::get($path);
        $data = json_decode($json, true);
        $password = $data['admin_password'] ?? null;

        if (empty($password)) {
            throw new \Exception("Admin password kosong di AdminKey.json!");
        }

        User::updateOrCreate(
            ['email' => 'zzhaidar36@gmail.com'],
            [
                'name' => 'haidar',
                'password' => Hash::make($password),
            ]
        );

        $this->command->info("User admin berhasil dibuat / diupdate.");
    }
}
