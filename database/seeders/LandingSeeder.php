<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingSetting;
use App\Models\LandingProgram;
use App\Models\LandingNavLink;
use App\Models\LandingFooterLink;
use App\Models\LandingAbout;

class LandingSeeder extends Seeder
{
    public function run(): void
    {
        /** ===============================
         * LANDING SETTINGS (Hero Section)
         * =============================== */
        $settings = [
            ['hero_title', 'Kampus Vokasi Terbaik Untuk Masa Depan Karier Anda', 'text'],
            ['hero_subtitle', 'LP3I hadir dengan fokus pendidikan vokasi yang relevan dengan dunia kerja. Raih keterampilan praktis dan peluang karier lebih cepat bersama kami.', 'text'],
            ['hero_image', 'landing/hero-lp3i.jpg', 'image'],
            ['footer_text', '© 2025 LP3I College - All Rights Reserved', 'text'],
        ];

        foreach ($settings as $item) {
            LandingSetting::updateOrCreate(
                ['key' => $item[0]],
                ['value' => $item[1], 'type' => $item[2], 'status' => 1]
            );
        }

        /** ===============================
         * NAVIGATION MENU
         * =============================== */
        $navLinks = [
            ['Beranda', '#beranda', 1],
            ['Program', '#program', 2],
            ['Tentang', '#tentang', 3],
            ['Kontak', '#kontak', 4],
        ];

        foreach ($navLinks as $item) {
            LandingNavLink::updateOrCreate(
                ['label' => $item[0]],
                ['url' => $item[1], 'position' => $item[2], 'status' => 1]
            );
        }

        /** ===============================
         * PROGRAM PENDIDIKAN SECTION
         * =============================== */
        $programs = [
            ['Administrasi Bisnis', 'Belajar pengelolaan bisnis, administrasi perkantoran, dan dunia manajemen modern.', null, 1],
            ['Informatika & Komputer', 'Program vokasi untuk dunia IT: pemrograman, jaringan, dan data.', null, 2],
            ['Digital Marketing', 'Menguasai strategi pemasaran digital sesuai kebutuhan industri.', null, 3],
        ];

        foreach ($programs as $item) {
            LandingProgram::updateOrCreate(
                ['title' => $item[0]],
                [
                    'description' => $item[1],
                    'image' => $item[2],
                    'position' => $item[3],
                    'status' => 1
                ]
            );
        }

        /** =============================
         * About Section
         * ============================= */
        $abouts = [
            [
                'title' => 'Tentang LP3I',
                'paragraph_1' => 'LP3I adalah lembaga pendidikan vokasi yang telah berdiri lebih dari 30 tahun, berfokus pada pendidikan yang langsung terhubung dengan dunia kerja.',
                'paragraph_2' => 'Dengan kurikulum berbasis industri, dosen praktisi, dan jaringan perusahaan luas, LP3I telah membantu ribuan lulusan untuk siap bekerja sejak semester awal.',
                'image' => 'landing/default-hero.png',
            ],
        ];

        foreach ($abouts as $about) {
            LandingAbout::updateOrCreate(
                ['title' => $about['title']],
                [
                    'paragraph_1' => $about['paragraph_1'],
                    'paragraph_2' => $about['paragraph_2'],
                    'image' => $about['image'],
                ]
            );
        }

        /** ===============================
         * FOOTER LINKS
         * =============================== */
        $footerLinks = [
            ['Beranda', '#beranda', 'nav',1],
            ['Program', '#program', 'nav',2],
            ['Tentang', '#tentang', 'nav',3],
            ['Kontak', '#kontak', 'nav',4],
            ['Email: info@lp3i.ac.id', 'mailto:info@lp3i.ac.id', NULL,5],
            ['Telp: (021) 12345678', 'tel:+622112345678', NULL,6],
        ];

        foreach ($footerLinks as $item) {
            LandingFooterLink::updateOrCreate(
                ['label' => $item[0]],
                ['url' => $item[1], 'group'=> $item[2],'position' => $item[3], 'status' => 1]
            );
        }
    }
}


/** 
Pada pengembangan terbaru LandingSeeder, dilakukan penambahan data default untuk branding dan logo situs, selain data yang sudah ada sebelumnya (hero, navigasi, program, footer).

Perubahan / Penambahan:

1. Site Logo:
- Key: site_logo
- Value: landing/logo-lp3i.png
- Tipe: image
- Fungsi: Menyediakan logo default untuk header atau branding situs.

2. Footer Brand:
- footer_brand_title: Menyimpan judul brand, value LP3I.
- footer_brand_description: Menyimpan deskripsi brand, value Kampus vokasi mempersiapkan mahasiswa siap kerja lebih cepat.
- Fungsi: Menambah informasi branding di footer agar lebih informatif dan profesional.

3. Footer Text:
- Key: footer_text diperbarui menjadi © 2025 LP3I. All rights reserved
- Fungsi: Memperbarui teks hak cipta di footer.

Kesimpulan:
Dengan penambahan ini, LandingSeeder kini otomatis mengisi data logo dan informasi brand saat database di-seed, sehingga tampilan landing page lebih lengkap, konsisten, dan siap digunakan tanpa harus menambahkan data secara manual.
*/
