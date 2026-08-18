<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraCharge extends Model
{
    use HasFactory;

    protected $table = 'tbl_extra_charge';
    protected $primaryKey = 'extra_id';

    protected $fillable = [
        'booking_id',
        'stage',
        'amount',
        'reason',
        'client_response',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }
}
