<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table      = 'tbl_contact';
    protected $primaryKey = 'contact_id';

    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'company_name',
        'inquiry_type',
        'message',
        'status',
    ];
}
