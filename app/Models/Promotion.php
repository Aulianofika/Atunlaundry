<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'discount_percentage',
        'discount_amount',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Scope for active promotions
     */
    public function scopeActive($query)
    {
        return $query
        ->where(function ($q) {
            $q->where('is_active', true)
              ->orWhereNull('is_active');
        })
        ->where(function ($q) {
            $q->where('start_date', '<=', now())
              ->orWhereNull('start_date');
        })
        ->where(function ($q) {
            $q->where('end_date', '>=', now())
              ->orWhereNull('end_date');
        });

    }
}
