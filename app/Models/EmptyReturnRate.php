<?php
// app/Models/EmptyReturnRate.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmptyReturnRate extends Model
{
    use HasFactory;

    protected $fillable = ['depot_name', 'container_size', 'rate'];

    public static function getRate($depot, $containerSize)
    {
        return self::where('depot_name', $depot)
                   ->where('container_size', $containerSize)
                   ->value('rate') ?? 0;
    }
}