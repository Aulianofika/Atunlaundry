<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_name',
        'position',
        'total_kg_completed',
        'rate_per_kg',
        'bonus',
        'total_salary',
        'notes',
        'month',
        'year',
        'status', // paid, pending
    ];

    protected $casts = [
        'total_kg_completed' => 'decimal:2',
        'rate_per_kg' => 'decimal:2',
        'bonus' => 'decimal:2',
        'total_salary' => 'decimal:2',
    ];

    /**
     * Calculate total salary based on kg completed
     * Formula: (total_kg_completed * rate_per_kg) + bonus
     */
    public function calculateTotalSalary(): float
    {
        return ($this->total_kg_completed * $this->rate_per_kg) + $this->bonus;
    }

    /**
     * Get formatted month name in Indonesian
     */
    public function getMonthNameAttribute(): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $months[$this->month] ?? 'Unknown';
    }
}
