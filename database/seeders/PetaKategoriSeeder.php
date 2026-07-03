<?php

namespace Database\Seeders;

use App\Models\PetaKategori;
use Illuminate\Database\Seeder;

class PetaKategoriSeeder extends Seeder
{
    public function run(): void
    {
        $default = [
            ['nama' => 'Pemerintahan',  'ikon' => 'ti-building',       'warna' => '#3B82F6'],
            ['nama' => 'Kesehatan',     'ikon' => 'ti-heart-plus',      'warna' => '#EF4444'],
            ['nama' => 'Pendidikan',    'ikon' => 'ti-school',          'warna' => '#F59E0B'],
            ['nama' => 'Ibadah',        'ikon' => 'ti-building-mosque', 'warna' => '#10B981'],
            ['nama' => 'Ekonomi',       'ikon' => 'ti-shopping-cart',   'warna' => '#F97316'],
            ['nama' => 'Infrastruktur', 'ikon' => 'ti-road',            'warna' => '#6B7280'],
            ['nama' => 'Pariwisata',    'ikon' => 'ti-camera',          'warna' => '#8B5CF6'],
            ['nama' => 'Pertanian',     'ikon' => 'ti-plant',           'warna' => '#65A30D'],
        ];

        foreach ($default as $item) {
            PetaKategori::firstOrCreate(['nama' => $item['nama']], $item);
        }
    }
}
