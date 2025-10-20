<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    private function checkAdmin()
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    /** Tampilkan daftar promosi */
    public function index()
    {
        $this->checkAdmin();

        $promotions = Promotion::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.promo.index', compact('promotions'));
    }

    /** Form tambah promosi */
    public function create()
    {
        $this->checkAdmin();

        return view('admin.promo.create');
    }

    /** Simpan promosi baru */
    public function store(Request $request)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_percentage' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'required|boolean',
        ]);

        Promotion::create($validated);

        return redirect()->route('promotions.index')->with('success', 'Promosi berhasil ditambahkan!');
    }

    /** Form edit promosi */
    public function edit(Promotion $promotion)
    {
        $this->checkAdmin();

        return view('admin.promo.edit', compact('promotion'));
    }

    /** Update promosi */
    public function update(Request $request, Promotion $promotion)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_percentage' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'required|boolean',
        ]);

        $promotion->update($validated);

        return redirect()->route('promotions.index')->with('success', 'Promosi berhasil diperbarui!');
    }

    /** Hapus promosi */
    public function destroy(Promotion $promotion)
    {
        $this->checkAdmin();

        $promotion->delete();

        return redirect()->route('promotions.index')->with('success', 'Promosi berhasil dihapus!');
    }
}
