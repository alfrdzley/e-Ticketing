<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketValidation extends Model
{
    protected $fillable = [
        'booking_id',
        'validated_at',
        'validated_by',
        'entry_gate',
        'validation_notes',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
