<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory,SoftDeletes;
    public $table = "tbl_job";

    protected $fillable = [
        'vehicle_reg_no',
        'owner_name',
        'address',
        'contact_mobile_no',
        'address',
        'inspection_place',
        'latitude',
        'longitude',
        'requested_by',
        'approved_by',
        'assigned_date',
        'rejected_by',
        'submitted_by',
        'submission_date',
        'completed_by',
        'completed_at',
        'approval_date',
        'rejected_date',
        'remark',
        'job_status',
        'created_by',
        'created_by_id',
        'admin_remark',
        'is_offline',
        'mail_sent_to_client',
        'mail_sent_to_employee',
        'mail_sent_date',
        'branch_id',
        'payment_link_tracking_id',
        'payment_link_send_date',
        'payment_status',
        'mail_send_by',
        'vehicle_owner_signature',
        'is_outside_job',
        'parent_admin_id',
        'contact_person_id',
        'job_report_no',
        'photos_delete_date'
    ];
}
