<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\Order;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index()
    {
        $this->checkAdmin();
        $salaries = Salary::orderBy('year', 'desc')->orderBy('month', 'desc')->paginate(15);
        $totalSalaries = Salary::sum('total_salary');
        $totalKgCompleted = Salary::sum('total_kg_completed');
        
        return view('admin.salaries.index', compact('salaries', 'totalSalaries', 'totalKgCompleted'));
    }

    public function create()
    {
        $this->checkAdmin();
        
        // Get current month's completed orders for reference
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Get total kg from completed orders this month
        $monthlyKgCompleted = Order::where('status', 'completed')
            ->whereMonth('updated_at', $currentMonth)
            ->whereYear('updated_at', $currentYear)
            ->sum('weight');
            
        return view('admin.salaries.create', compact('monthlyKgCompleted', 'currentMonth', 'currentYear'));
    }

    public function store(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'employee_name' => 'required|string|max:255',
            'position' => 'required|string|max:100',
            'total_kg_completed' => 'required|numeric|min:0',
            'rate_per_kg' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
            'status' => 'required|in:pending,paid',
            'notes' => 'nullable|string|max:500',
        ]);

        $data = $request->all();
        $data['bonus'] = $data['bonus'] ?? 0;
        // Calculate total salary: (kg * rate) + bonus
        $data['total_salary'] = ($data['total_kg_completed'] * $data['rate_per_kg']) + $data['bonus'];

        Salary::create($data);

        return redirect()->route('admin.salaries.index')->with('success', 'Gaji karyawan berhasil ditambahkan!');
    }

    public function edit(Salary $salary)
    {
        $this->checkAdmin();
        return view('admin.salaries.edit', compact('salary'));
    }

    public function update(Request $request, Salary $salary)
    {
        $this->checkAdmin();
        $request->validate([
            'employee_name' => 'required|string|max:255',
            'position' => 'required|string|max:100',
            'total_kg_completed' => 'required|numeric|min:0',
            'rate_per_kg' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
            'status' => 'required|in:pending,paid',
            'notes' => 'nullable|string|max:500',
        ]);

        $data = $request->all();
        $data['bonus'] = $data['bonus'] ?? 0;
        // Calculate total salary: (kg * rate) + bonus
        $data['total_salary'] = ($data['total_kg_completed'] * $data['rate_per_kg']) + $data['bonus'];

        $salary->update($data);

        return redirect()->route('admin.salaries.index')->with('success', 'Gaji karyawan berhasil diperbarui!');
    }

    public function destroy(Salary $salary)
    {
        $this->checkAdmin();
        $salary->delete();
        return redirect()->route('admin.salaries.index')->with('success', 'Gaji karyawan berhasil dihapus!');
    }

    /**
     * Get completed kg for a specific employee in a month/year
     * This can be called via AJAX to auto-fill kg data
     */
    public function getMonthlyKg(Request $request)
    {
        $this->checkAdmin();
        
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        
        $totalKg = Order::where('status', 'completed')
            ->whereMonth('updated_at', $month)
            ->whereYear('updated_at', $year)
            ->sum('weight');
            
        return response()->json([
            'total_kg' => $totalKg,
            'month' => $month,
            'year' => $year
        ]);
    }

    protected function checkAdmin()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403);
        }
    }
}
