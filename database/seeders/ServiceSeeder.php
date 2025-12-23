<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks and clear existing services
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Service::query()->delete();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $services = [
            [
                'name' => 'Family',
                'unit' => 'Kg',
                'description' => 'Paket cuci keluarga lengkap',
                'price_per_kg' => 5000,
                'estimated_days' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Handuk',
                'unit' => 'Kg',
                'description' => 'Cuci handuk',
                'price_per_kg' => 7000,
                'estimated_days' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Selimut Tebal',
                'unit' => 'Pcs',
                'description' => 'Cuci selimut tebal',
                'price_per_kg' => 35000,
                'estimated_days' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Selimut Sedang',
                'unit' => 'Pcs',
                'description' => 'Cuci selimut sedang',
                'price_per_kg' => 25000,
                'estimated_days' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Selimut Kecil',
                'unit' => 'Pcs',
                'description' => 'Cuci selimut kecil',
                'price_per_kg' => 15000,
                'estimated_days' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Bed Cover Jumbo',
                'unit' => 'Pcs',
                'description' => 'Cuci bed cover ukuran jumbo',
                'price_per_kg' => 35000,
                'estimated_days' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Bed Cover Besar',
                'unit' => 'Pcs',
                'description' => 'Cuci bed cover ukuran besar',
                'price_per_kg' => 25000,
                'estimated_days' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Bed Cover Kecil',
                'unit' => 'Pcs',
                'description' => 'Cuci bed cover ukuran kecil',
                'price_per_kg' => 15000,
                'estimated_days' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Seprei',
                'unit' => 'Kg',
                'description' => 'Cuci seprei',
                'price_per_kg' => 10000,
                'estimated_days' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Gorden',
                'unit' => 'Kg',
                'description' => 'Cuci gorden/tirai',
                'price_per_kg' => 12000,
                'estimated_days' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Setrika Saja',
                'unit' => 'Kg',
                'description' => 'Layanan setrika saja',
                'price_per_kg' => 3000,
                'estimated_days' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Cuci Saja',
                'unit' => 'Kg',
                'description' => 'Layanan cuci saja tanpa setrika',
                'price_per_kg' => 3000,
                'estimated_days' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Jaket',
                'unit' => 'Pcs',
                'description' => 'Cuci jaket',
                'price_per_kg' => 15000,
                'estimated_days' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
