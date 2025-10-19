<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\Service;
use App\Models\Promotion;
use App\Models\Expense;
use Illuminate\Support\Facades\Hash;

class AdminDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only create sample data, no additional admin users
        // Admin is only Atun (already created in AdminSeeder)

        // Create sample promotions
        $promotions = [
            [
                'title' => 'New Customer Discount',
                'description' => 'Get 20% off your first order!',
                'discount_percentage' => 20.00,
                'discount_amount' => null,
                'start_date' => now()->subDays(30),
                'end_date' => now()->addDays(30),
                'is_active' => true,
            ],
            [
                'title' => 'Weekend Special',
                'description' => 'Special rates for weekend orders',
                'discount_percentage' => 15.00,
                'discount_amount' => null,
                'start_date' => now()->startOfWeek(),
                'end_date' => now()->endOfWeek(),
                'is_active' => true,
            ],
            [
                'title' => 'Bulk Order Discount',
                'description' => 'Order 10kg or more and get 10% off',
                'discount_percentage' => 10.00,
                'discount_amount' => null,
                'start_date' => now()->subDays(7),
                'end_date' => now()->addDays(60),
                'is_active' => true,
            ],
        ];

        foreach ($promotions as $promotion) {
            Promotion::create($promotion);
        }

        // Create sample expenses
        $expenses = [
            [
                'title' => 'Electricity Bill',
                'description' => 'Monthly electricity bill for laundry equipment',
                'amount' => 2500000,
                'category' => 'utilities',
                'expense_date' => now()->subDays(5),
            ],
            [
                'title' => 'Detergent Purchase',
                'description' => 'Bulk purchase of laundry detergents',
                'amount' => 1500000,
                'category' => 'supplies',
                'expense_date' => now()->subDays(3),
            ],
            [
                'title' => 'Equipment Maintenance',
                'description' => 'Regular maintenance of washing machines',
                'amount' => 800000,
                'category' => 'maintenance',
                'expense_date' => now()->subDays(1),
            ],
            [
                'title' => 'Water Bill',
                'description' => 'Monthly water bill',
                'amount' => 1200000,
                'category' => 'utilities',
                'expense_date' => now()->subDays(2),
            ],
        ];

        foreach ($expenses as $expense) {
            Expense::create($expense);
        }

        // Create sample orders for demonstration
        $services = Service::all();
        $customers = User::where('role', 'customer')->get();
        
        if ($customers->count() == 0) {
            // Create sample customers if none exist
            $sampleCustomers = [
                [
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                    'phone' => '081234567893',
                    'role' => 'customer',
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                ],
                [
                    'name' => 'Jane Smith',
                    'email' => 'jane@example.com',
                    'phone' => '081234567894',
                    'role' => 'customer',
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                ],
                [
                    'name' => 'Bob Johnson',
                    'email' => 'bob@example.com',
                    'phone' => '081234567895',
                    'role' => 'customer',
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                ],
            ];

            foreach ($sampleCustomers as $customer) {
                User::create($customer);
            }
            $customers = User::where('role', 'customer')->get();
        }

        // Create sample orders
        $orderStatuses = [
            'waiting_for_pickup',
            'picked_and_weighed',
            'waiting_for_payment',
            'waiting_for_admin_verification',
            'processed',
            'completed'
        ];

        for ($i = 0; $i < 15; $i++) {
            $service = $services->random();
            $customer = $customers->random();
            $status = $orderStatuses[array_rand($orderStatuses)];
            
            $weight = rand(1, 10) + (rand(0, 9) / 10); // Random weight between 1.0 and 10.9
            $price = $weight * $service->price_per_kg;

            Order::create([
                'order_code' => Order::generateOrderCode(),
                'user_id' => $customer->id,
                'service_id' => $service->id,
                'order_type' => rand(0, 1) ? 'login' : 'manual',
                'customer_name' => $customer->name,
                'customer_phone' => $customer->phone,
                'customer_address' => 'Sample Address ' . ($i + 1) . ', City',
                'pickup_method' => rand(0, 1) ? 'pickup' : 'delivery',
                'weight' => $weight,
                'price' => $price,
                'status' => $status,
                'payment_verified' => in_array($status, ['processed', 'completed']),
                'notes' => $i % 3 == 0 ? 'Special instructions for order ' . ($i + 1) : null,
                'estimated_completion' => now()->addDays(rand(1, 3)),
            ]);
        }
    }
}