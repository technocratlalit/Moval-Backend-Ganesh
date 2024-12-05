<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class JobDetail extends Model
{
    use HasFactory,SoftDeletes;
    public $table = "tbl_job_detail";
    protected $fillable = [
        'inspection_id', 'vehicle_class', 'registration_date', 'type_of_body', 'manufactoring_year', 'maker', 'model', 'chassis_no', 'engine_no', 'rc_status', 'seating_capacity', 'issuing_authority', 'fuel_type', 'color', 'odometer_reading', 'fitness_valid_upto', 'laden_weight', 'unladen_weight', 'requested_value','engine_transmission', 'electrical_gadgets', 'right_side', 'left_body', 'front_body', 'back_body', 'load_body', 'all_glass_condition', 'cabin_condition', 'head_lamp', 'tyres_condition', 'maintenance', 'other_damages'
    ];


}
