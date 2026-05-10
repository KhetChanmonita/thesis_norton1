<?php
// app/Models/Port.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    use HasFactory;

    protected $fillable = ['name_en', 'name_km', 'type'];

    public function originRates()
    {
        return $this->hasMany(TransportationRate::class, 'origin_port_id');
    }
}