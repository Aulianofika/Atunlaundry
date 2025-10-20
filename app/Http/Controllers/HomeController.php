<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Promotion;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)->get();
        $promotions = Promotion::orderBy('created_at', 'desc')->get();
        
        return view('home', compact('services', 'promotions'));
    }
}
