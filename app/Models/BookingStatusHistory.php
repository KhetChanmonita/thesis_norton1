<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'tbl_booking_status_history';
    protected $primaryKey = 'history_id';

    protected $fillable = [
        'booking_id',
        'total_price',
        'completed_date',
    ];

    protected $casts = [
        'completed_date' => 'date',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }
}
