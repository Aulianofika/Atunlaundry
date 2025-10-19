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
        $services = [
            [
                'name' => 'Regular Laundry',
                'description' => 'Wash, dry, and fold service',
                'price_per_kg' => 8000,
                'estimated_days' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Express Laundry',
                'description' => 'Same day service (within 6 hours)',
                'price_per_kg' => 12000,
                'estimated_days' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Ironing Only',
                'description' => 'Ironing service for clean clothes',
                'price_per_kg' => 5000,
                'estimated_days' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Dry Clean',
                'description' => 'Professional dry cleaning service',
                'price_per_kg' => 15000,
                'estimated_days' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Wash & Iron',
                'description' => 'Complete wash and iron service',
                'price_per_kg' => 10000,
                'estimated_days' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
