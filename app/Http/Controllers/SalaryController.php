<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index()
    {
        $this->checkAdmin();
        $salaries = Salary::orderBy('year', 'desc')->orderBy('month', 'desc')->paginate(15);
        $totalSalaries = Salary::sum('total_salary');
        
        return view('admin.salaries.index', compact('salaries', 'totalSalaries'));
    }

    public function create()
    {
        $this->checkAdmin();
        return view('admin.salaries.create');
    }

    public function store(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'employee_name' => 'required|string|max:255',
            'position' => 'required|string|max:100',
            'base_salary' => 'required|numeric|min:0',
            'allowance' => 'nullable|numeric|min:0',
            'deduction' => 'nullable|numeric|min:0',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
            'status' => 'required|in:pending,paid',
        ]);

        $data = $request->all();
        $data['total_salary'] = $data['base_salary'] + ($data['allowance'] ?? 0) - ($data['deduction'] ?? 0);

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
            'base_salary' => 'required|numeric|min:0',
            'allowance' => 'nullable|numeric|min:0',
            'deduction' => 'nullable|numeric|min:0',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
            'status' => 'required|in:pending,paid',
        ]);

        $data = $request->all();
        $data['total_salary'] = $data['base_salary'] + ($data['allowance'] ?? 0) - ($data['deduction'] ?? 0);

        $salary->update($data);

        return redirect()->route('admin.salaries.index')->with('success', 'Gaji karyawan berhasil diperbarui!');
    }

    public function destroy(Salary $salary)
    {
        $this->checkAdmin();
        $salary->delete();
        return redirect()->route('admin.salaries.index')->with('success', 'Gaji karyawan berhasil dihapus!');
    }

    protected function checkAdmin()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403);
        }
    }
}
