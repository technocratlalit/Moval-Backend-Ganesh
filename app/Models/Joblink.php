<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Joblink extends Model
{
    use HasApiTokens,HasFactory;
    // public $table = "tbl_ms_joblinks";
    public $table = "tbl_ms_inspection_links";
    

    public function getJobDetails(){
        return $this->belongsTo(Job::class,'inspection_id','id'); 
    }

    protected $fillable = [
        'inspection_id',
        'encoded_job_id',
        'link_createdate',
        'link_expdate',
        'status',
        'link_createdby',
         'submitted_date'
    ];
}
