<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'tbl_expense';
    protected $primaryKey = 'expense_id';

    protected $fillable = [
        'truck_id',
        'driver_id',
        'booking_id',
        'expense_type',
        'amount',
        'driver_allowance',
        'expense_date',
        'description',
    ];

    protected $casts = [
        'expense_date' => 'date',
    ];

    public function truck()
    {
        return $this->belongsTo(Truck::class, 'truck_id', 'truck_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'driver_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }
}
