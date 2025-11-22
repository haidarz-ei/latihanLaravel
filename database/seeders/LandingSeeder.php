<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LandingSetting;
use App\Models\LandingProgram;
use App\Models\LandingNavLink;
use App\Models\LandingFooterLink;

class LandingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** =============================
            * Landing Setting (hero section)
        * ================================= */
        $setting = [
            ['hero_title', 'Kampus Vokasi Terbaik di Indonesia untuk Masa Depan Gemilang', 'Text'],
            ['hero_subtitle', 'LP3I hadir dengan fokus pendidikan vokasi yang relevan dengan dunia kerja.
                                Raih keterampilan praktis dan peluang karier lebih cepat bersama kami.', 'Text'],
            ['hero_image', 'landing/hero-lp3i.jpg', 'Image'],
            ['footer_text', '©️ 2025 LP3I College-All Rights Reserved', 'Text'],
        ];

        foreach ($setting as $item) {
            LandingSetting::updateOrCreate(
                ['key' => $item[0]],
                ['value' => $item[1], 'type' => $item[2], 'status' => 1]
            );
        }

        /** =============================
            * Navigation Menu
        * ================================= */

        $navLinks =[
            ['Beranda', '#beranda', 1],
            ['Program', '#program', 2],
            ['Tentang', '#tentang', 3],
            ['Kontak', '#kontak', 4],
        ];

        foreach ($navLinks as $link) {
            LandingNavLink::updateOrCreate(
                ['label' => $link[0]],
                ['url' => $link[1], 'position' => $link[2], 'status' => 1]
            );
        }

        /** =============================
            * Program Pendidikan Section
        * ================================= */

        $programs = [
            ['Administrasi Bisnis', 'Belajar pengelolaan bisnis, administrasi perkantoran, dan dunia manajemen modern', null, 1],
            ['Informatika & Komputer', 'Program vokasi untuk dunia IT: pemrograman, jaringan, dan data', null, 2],
            ['Digital Marketing', 'Menguasai strategi pemasaran digital dan kebutuhan industri', null, 3],
        ];

        foreach ($programs as $program) {
            LandingProgram::updateOrCreate(
                ['title' => $program[0]],
                [
                    'description' => $program[1], 
                    'image' => $program[2], 
                    'position' => $program[3], 
                    'status' => 1
                ]
            );
        }

        /** =============================
            * Footer Links Section
        * ================================= */
        $footerLinks =[
            ['Beranda', '#beranda', 1],
            ['Program', '#program', 2],
            ['Tentang', '#tentang', 3],
            ['Kontak', '#kontak', 4],
            ['Email:', 'mailto:info@lp3i.ac.id', 5],
            ['Telp: (021) 12345678', 'tel:+62212345678', 6],
        ];

        foreach ($footerLinks as $link) {
            LandingFooterLink::updateOrCreate(
                ['label' => $link[0]],
                ['url' => $link[1], 'position' => $link[2], 'status' => 1]
            );
        }
    }
}

                    

                    
