<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SopField extends Model
{
    use HasFactory,SoftDeletes;
    public $table = "tbl_ms_sop_details";

    protected $fillable = [
        'sop_id',
        'form_field_lable',
        'created_at',
        'updated_at'    
    ];
}
