<?php

namespace App\Models\vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleClass extends Model
{
    use HasFactory;
    public $table = "tbl_vehicle_class";
    protected $hidden = ['created_at','updated_at'];
    protected $fillable = [
        'name',
        'created_by_id',
    ];
}
