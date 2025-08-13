<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Booking extends Model
{

    use HasFactory;
    protected $fillable = [
        'booking_code', 'user_id', 'event_id', 'quantity',
        'unit_price', 'total_amount', 'discount_amount', 'final_amount',
        'status', 'payment_method', 'payment_reference', 'payment_date',
        'booking_date', 'expired_at', 'notes', 'booker_name',
        'booker_email', 'booker_phone', 'discount_code_id',
        'ticket_qr_code_path', 'ticket_pdf_path', 'entry_validated_at',
        'payment_proof_path', 'ulid'
    ];

    protected $casts = [
        'booking_date' => 'datetime',
        'payment_date' => 'datetime',
        'expired_at' => 'datetime',
        'entry_validated_at' => 'datetime',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->ulid)) {
                $model->ulid = (string) Str::ulid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'ulid';
    }

    /**
     * Relasi ke DiscountCode. (Pastikan ini ada dan tidak di-comment)
     */
    public function discountCode()
    {
        return $this->belongsTo(DiscountCode::class);
    }

    public function paymentLogs()
    {
        return $this->hasMany(PaymentLog::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function ticketValidations()
    {
        return $this->hasMany(TicketValidation::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function isValidated()
    {
        return !is_null($this->entry_validated_at);
    }

    public function canBeValidated()
    {
        return $this->status === 'paid' && !$this->isValidated();
    }
}
