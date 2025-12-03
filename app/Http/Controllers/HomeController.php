<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Promotion;

class HomeController extends Controller
{
    public function index()
    {
        // Auto-seed services if database is empty (fallback for hosting environments)
        if (Service::count() === 0) {
            $this->seedDefaultServices();
        }

        // present services in creation order (oldest first)
        $services = Service::where('is_active', true)->orderBy('id')->get();
        $promotions = Promotion::orderBy('created_at', 'desc')->get();
        
        return view('home', compact('services', 'promotions'));
    }

    /**
     * Seed default services if database is empty.
     */
    private function seedDefaultServices()
    {
        Service::create([
            'name' => 'Regular Laundry',
            'description' => 'Wash, dry, and fold service',
            'price_per_kg' => 8000,
            'estimated_days' => 2,
            'is_active' => true,
        ]);
        Service::create([
            'name' => 'Express Laundry',
            'description' => 'Same day service (within 6 hours)',
            'price_per_kg' => 12000,
            'estimated_days' => 1,
            'is_active' => true,
        ]);
        Service::create([
            'name' => 'Ironing Only',
            'description' => 'Ironing service for clean clothes',
            'price_per_kg' => 5000,
            'estimated_days' => 1,
            'is_active' => true,
        ]);
        Service::create([
            'name' => 'Dry Clean',
            'description' => 'Professional dry cleaning service',
            'price_per_kg' => 15000,
            'estimated_days' => 3,
            'is_active' => true,
        ]);
        Service::create([
            'name' => 'Wash & Iron',
            'description' => 'Complete wash and iron service',
            'price_per_kg' => 10000,
            'estimated_days' => 2,
            'is_active' => true,
        ]);
    }
}
