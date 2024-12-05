<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobFile extends Model
{
    use HasFactory,SoftDeletes;
    // public $table = "tbl_job_files";
    public $table = "tbl_ms_inspection_files";

    protected $fillable = [
        'name',
        'inspection_id',
        'type'
    ];
}
