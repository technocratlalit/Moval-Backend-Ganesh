<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manuallupload extends Model
{
    use HasApiTokens,HasFactory;
    // public $table = "tbl_ms_job_files";
    public $table = "tbl_ms_inspection_files";
    protected $fillable = [
        'inspection_id',
        'sop_id',
        'sop_label',
        'original_file_name',
        'photosheet_selected',
        'edited_file_name',
        'original_image_uploaded_date',
        'edited_image_uploaded_date',
        'original_image_edited_date',
        'edited_image_edited_date',
        'uploaded_by',
        'edited_by',
        'file_type',
        'ai_box_coordinate',
        'final_box_coordinate', 
    ];
}
 