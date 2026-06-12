<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'tbl_booking';
    protected $primaryKey = 'booking_id';

    protected $fillable = [
        'schedule_id',
        'truck_id',
        'customer_id',
        'booking_type',
        'container_number',
        'pickup_location',
        'dropoff_location',
        'dropoff_location_link',
        'pick_up_date',
        'drop_off_date',
        'cargo_weight',
        'booking_date',
        'status',
        'total_price',
        'cargo_list_file',
        'payment_status',
        'access_token',
    ];

    protected $casts = [
        'pick_up_date'  => 'date',
        'drop_off_date' => 'date',
        'booking_date'  => 'date',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'schedule_id');
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class, 'truck_id', 'truck_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'booking_id', 'booking_id');
    }

    public function extraCharges()
    {
        return $this->hasMany(ExtraCharge::class, 'booking_id', 'booking_id');
    }

    public function statusHistory()
    {
        return $this->hasMany(BookingStatusHistory::class, 'booking_id', 'booking_id');
    }
}
