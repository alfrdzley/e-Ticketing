<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasUlids;

    protected $fillable = [
        'booking_id',
        'midtrans_order_id',
        'quantity',
        'total_price',
        'status',
        'payment_method',
        'snap_token',
        'paid_at',
        'midtrans_response',
        'transaction_id',
        'fraud_status',
        'payment_type',
        'gross_amount',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'gross_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'midtrans_response' => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->midtrans_order_id)) {
                $model->midtrans_order_id =
                    'TXN-'.strtoupper(Str::random(10));
            }
        });
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'ulid');
    }

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            Booking::class,
            'ulid',
            'id',
            'booking_id',
            'user_id',
        );
    }

    public function event(): HasOneThrough
    {
        return $this->hasOneThrough(
            Event::class,
            Booking::class,
            'ulid',
            'id',
            'booking_id',
            'event_id',
        );
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isFailed(): bool
    {
        return in_array($this->status, ['failed', 'expired', 'cancelled']);
    }
}
