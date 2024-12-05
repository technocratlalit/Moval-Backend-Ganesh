<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssismentDetail extends Model
{
    use HasApiTokens,HasFactory;
    public $table = "tbl_ms_assessment_details";
    protected $fillable = [
        'alldetails',
        'inspection_id',
        'created_by',
        'updated_by',
    ];
    
}
