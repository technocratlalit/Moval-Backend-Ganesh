<?php

namespace App\Models\vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleVarient extends Model
{
    use HasFactory;
    public $table = "tbl_vehicle_varient";
    protected $hidden = ['created_at','updated_at'];
    protected $fillable = [
        'name',
        'maker_id',
        'created_by_id',
        'seats'
    ];
}
