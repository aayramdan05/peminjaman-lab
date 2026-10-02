<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 11; $i++) {
            \App\Models\Lab::create([
                'name' => "Lab D$i",
                'capacity' => 40,
                'pc_specs' => 'Intel Core i5 Gen 12, 16GB RAM, 512GB SSD, Monitor 24 inch',
                'facilities' => 'AC, Proyektor, Papan Tulis, Koneksi Internet LAN',
                'image_path' => null,
                'layout_image_path' => null,
            ]);
        }
    }
}
