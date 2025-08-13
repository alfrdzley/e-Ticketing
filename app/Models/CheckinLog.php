<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckinLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id', 'action', 'performed_by', 'performed_at',
        'location', 'device_info', 'ip_address', 'notes'
    ];

    protected $casts = [
        'performed_at' => 'datetime',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
