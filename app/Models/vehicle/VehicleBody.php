<?php

namespace App\Models\vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleBody extends Model
{
    use HasFactory;
    public $table = "tbl_vehicle_body";
    protected $hidden = ['created_at','updated_at'];
    protected $fillable = [
        'name',
        'created_by_id',
    ];
}
