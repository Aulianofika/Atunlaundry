<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure storage dir exists
        Storage::makeDirectory('public/scale_proofs');
        Storage::makeDirectory('public/payment_proofs');

        $serviceKg = Service::where('unit', 'Per KG')->first();
        $serviceHelai = Service::where('unit', 'Per Helai')->first();

        $user = User::first();

        // Create sample orders
        Order::create([
            'order_code' => 'ATN-TEST-001',
            'user_id' => $user ? $user->id : null,
            'service_id' => $serviceKg ? $serviceKg->id : null,
            'order_type' => 'manual',
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081234567890',
            'customer_address' => 'Jl. Merdeka No. 1',
            'pickup_method' => 'pickup',
            'weight' => 3.00,
            'price' => ($serviceKg ? ($serviceKg->price_per_kg * 3) : 15000),
            'status' => 'picked_and_weighed',
            'payment_proof' => null,
            'weigh_proof' => null,
            'notes' => 'Contoh order dry clean',
        ]);

        Order::create([
            'order_code' => 'ATN-TEST-002',
            'user_id' => $user ? $user->id : null,
            'service_id' => $serviceHelai ? $serviceHelai->id : null,
            'order_type' => 'manual',
            'customer_name' => 'Siti Aminah',
            'customer_phone' => '089876543210',
            'customer_address' => 'Jl. Kebon Jeruk 2',
            'pickup_method' => 'delivery',
            'weight' => null,
            'price' => 70000,
            'status' => 'waiting_for_pickup',
            'notes' => 'Cuci sepatu 2 helai',
        ]);

    }
}
