<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dynamic_sections extends Model
{
    use HasFactory,SoftDeletes;
    public $table = "tbl_ms_dynamic_sections";

    protected $fillable = [
        'inspection_id',
        'SrNo',
        'Heading',
        'Details',
        'section_type',
        'add_Report',
        'created_by',
        'updated_by'
    ];
}
