<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'banner_image_url',
        'start_date', 'end_date', 'location', 'address',
        'price', 'quota', 'status', 'category_id', 'organizer_id',
        'terms_conditions', 'refund_policy', 'contact_email',
        'contact_phone', 'meta_title', 'meta_description',
        'payment_qr_code', 'payment_account_name', 'payment_account_number',
        'payment_bank_name', 'payment_instructions', 'ulid',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(callback: function ($model) {
            if (empty($model->ulid)) {
                $model->ulid = (string) Str::ulid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'ulid';
    }

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }

    public function organizer()
    {
        return $this->belongsTo(Organizer::class, 'organizer_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getAvailableQuota()
    {
        return $this->quota - $this->bookings()->sum('quantity');
    }

    public function hasAvailableQuota($quantity = 1)
    {
        return $this->getAvailableQuota() >= $quantity;
    }
}
