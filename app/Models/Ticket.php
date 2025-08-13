<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 'ticket_code', 'attendee_name', 'attendee_email',
        'attendee_phone', 'seat_number', 'special_requirements',
        'is_checked_in', 'checked_in_at', 'checked_in_by', 'ulid'
    ];

    protected $casts = [
        'is_checked_in' => 'boolean',
        'checked_in_at' => 'datetime',
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

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function checkinLogs()
    {
        return $this->hasMany(CheckinLog::class);
    }

    public function checkedInBy()
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

}
