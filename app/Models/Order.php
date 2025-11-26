<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'user_id',
        'service_id',
        'service_ids',
        'order_type',
        'customer_name',
        'customer_phone',
        'customer_address',
        'pickup_method',
        'weight',
        'price',
        'view_proof',
        'status',
        'payment_proof',
        'payment_verified',
        'items_description',
        'notes',
        'estimated_completion',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'price' => 'decimal:2',
        'payment_verified' => 'boolean',
        'estimated_completion' => 'datetime',
        'service_ids' => 'array',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the service for the order.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Generate unique order code
     */
    public static function generateOrderCode()
    {
        do {
            $code = 'ATN-' . now()->format('Ymd') . '-' . strtoupper(Str::random(3));
        } while (self::where('order_code', $code)->exists());

        return $code;
    }

    /**
     * Get status display name
     */
    public function getStatusDisplayAttribute()
    {
        return match($this->status) {
            'waiting_for_pickup' => 'Waiting for Pickup',
            'picked_and_weighed' => 'Picked & Weighed',
            'waiting_for_payment' => 'Waiting for Payment',
            'waiting_for_admin_verification' => 'Waiting for Admin Verification',
            'processed' => 'Processed (Washed/Ironed)',
            'completed' => 'Completed',
            default => 'Unknown'
        };
    }
}
