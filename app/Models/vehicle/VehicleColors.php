<?php

namespace App\Models\vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleColors extends Model
{
    use HasFactory;
    public $table = "tbl_vehicle_colors";
    protected $hidden = ['created_at','updated_at'];
    protected $fillable = [
        'name',
        'created_by_id',
    ];
}
