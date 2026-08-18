<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportBooking extends Model
{
    protected $table = 'tbl_import_booking';

    protected $fillable = [
        'company_name',
        'bill_booking',
        'container_size',
        'container_price',
        'pickup_date',
        'delivery_date',
        'pickup_location',
        'dropoff_location',
        'dropoff_location_link',
        'document_holder_phone',
        'delivery_contact_phone',
        'status',
        'booking_code',
        'truck_id',
        'cargo_list_file_urls',
        'cargo_weight',
        'payment_status',
        'customer_full_name',
        'customer_email',
        'customer_address',
        'customer_phone',
    ];

    protected $casts = [
        'pickup_date'         => 'date',
        'delivery_date'       => 'date',
        'container_price'     => 'float',
        'cargo_weight'        => 'float',
        'cargo_list_file_urls' => 'array',
    ];
}