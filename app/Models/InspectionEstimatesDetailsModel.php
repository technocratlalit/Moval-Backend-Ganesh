<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionEstimatesDetailsModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_ms_inspection_estimates_details';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $fillable = [];

    public $timestamps = false;
}
