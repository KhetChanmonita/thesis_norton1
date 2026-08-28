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
        'booked_by_user_id',
        'booking_type',
        'container_number',
        'container_size',
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

    protected $appends = ['formatted_id'];

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

    public function bookedByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'booked_by_user_id', 'user_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'booking_id', 'booking_id');
    }

    public function extraCharges()
    {
        return $this->hasMany(ExtraCharge::class, 'booking_id', 'booking_id');
    }

    public function secondStageCharges()
    {
        return $this->hasMany(ExtraCharge::class, 'booking_id', 'booking_id')->where('stage', 'second');
    }

    // Total amount due for the final (second) payment
    public function getFinalPaymentAmountAttribute(): float
    {
        $base        = round(($this->total_price ?? 0) * 0.5, 2);
        $secondExtra = (float) $this->secondStageCharges()->sum('amount');
        return round($base + $secondExtra, 2);
    }

    public function statusHistory()
    {
        return $this->hasMany(BookingStatusHistory::class, 'booking_id', 'booking_id');
    }

    public function costSheet()
    {
        return $this->hasOne(CostSheet::class, 'booking_id', 'booking_id');
    }

    public function getFormattedIdAttribute()
    {
        $date = $this->booking_date ?? $this->created_at;
        return 'LS' . $date->format('ym') . '-' . $this->booking_id;
    }
}
