<?php
// app/Models/Province.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $fillable = ['name_en', 'name_km'];

    public function transportationRates()
    {
        return $this->hasMany(TransportationRate::class, 'destination_province_id');
    }
}