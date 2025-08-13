<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'type', 'value', 'min_purchase', 'max_discount',
        'usage_limit', 'used_count', 'valid_from', 'valid_until',
        'applicable_events', 'is_active'
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'applicable_events' => 'array',
        'is_active' => 'boolean',
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function isValid()
    {
        return $this->is_active &&
            $this->valid_from <= now() &&
            $this->valid_until >= now() &&
            ($this->usage_limit == 0 || $this->used_count < $this->usage_limit);
    }
}
