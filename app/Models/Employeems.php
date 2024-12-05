<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employeems extends Model
{
    use HasFactory,HasApiTokens,SoftDeletes;  //HasApiTokens
    public $table = "tbl_ms_employee";
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_id',
        'address',
        'job_assigned_to_guest',
        'is_guest_employee',
        'mobile_no',
        'created_by',
        'modified_by',
        'firebase_token',
        'status',
        'amount_per_job',
        'parent_admin_id',
		'admin_branch_id',
		'admin_id'
    ];

}
