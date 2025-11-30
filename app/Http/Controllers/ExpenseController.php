<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $this->checkAdmin();
        $query = Expense::query();

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        // Filter by month/year
        if ($request->filled('month')) {
            $query->whereMonth('expense_date', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('expense_date', $request->year);
        }
        // Search by title/description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate(15);
        $totalExpenses = $query->sum('amount');

        // Monthly/yearly summary
        $monthlySummary = Expense::selectRaw('MONTH(expense_date) as month, YEAR(expense_date) as year, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.expenses.index', compact('expenses', 'totalExpenses', 'monthlySummary'));
    }

    public function create()
    {
        $this->checkAdmin();
        return view('admin.expenses.create');
    }

    public function store(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'expense_date' => 'required|date',
        ]);

        Expense::create($request->all());

        return redirect()->route('admin.expenses.index')->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

    public function edit(Expense $expense)
    {
        $this->checkAdmin();
        return view('admin.expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $this->checkAdmin();
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'expense_date' => 'required|date',
        ]);

        $expense->update($request->all());

        return redirect()->route('admin.expenses.index')->with('success', 'Pengeluaran berhasil diperbarui!');
    }

    public function destroy(Expense $expense)
    {
        $this->checkAdmin();
        $expense->delete();
        return redirect()->route('admin.expenses.index')->with('success', 'Pengeluaran berhasil dihapus!');
    }

    protected function checkAdmin()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403);
        }
    }
}
