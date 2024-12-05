<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CabinBodyAssessmentModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_ms_cabin_body_assessment';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = [];
    protected $fillable = [];
}
