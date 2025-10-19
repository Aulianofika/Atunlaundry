<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('name')->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_kg' => 'required|numeric|min:0',
            'estimated_days' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
        ]);

        Service::create($request->all());

        return redirect()->route('services.index')
                         ->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_kg' => 'required|numeric|min:0',
            'estimated_days' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
        ]);

        $service->update($request->all());

        return redirect()->route('admin.services.index')
                         ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        // Optional: cek apakah ada order terkait sebelum hapus
        if ($service->orders()->count() > 0) {
            return redirect()->route('admin.services.index')
                             ->with('error', 'Cannot delete service because it has related orders.');
        }

        $service->delete();

        return redirect()->route('admin.services.index')
                         ->with('success', 'Service deleted successfully.');
    }
}
