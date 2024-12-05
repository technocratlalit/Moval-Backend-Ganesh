<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jobms extends Model
{
    use HasApiTokens,HasFactory;
    public $table = "tbl_ms_job";
    protected $fillable = [
        'claim_type',
        'Job_Route_To',
        'vehicle_reg_no',
        'insured_name',
        'place_survey',
        'workshop_id',
        'workshop_branch_id',
        'user_role',
        'contact_no',
        'contact_person',
        'client_id',
        'admin_branch_id',
        'date_time_appoinment',
        'sop_id',
        'branch_name',
        'jobjssignedto_surveyorEmpId',
        'jobassignedto_workshopEmpid',
        'job_status',
        'assigned_on',
        'created_by',
        'updated_by',
        'upload_type',
        'admin_id',
        'signature_image',
        'video_file',
        'job_remark',
        'submitted_by',
        'submitted_by_role',
        'assigned_by',
        'approved_by',
    ];
}
