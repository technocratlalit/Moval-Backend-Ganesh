<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class BranchContactPersonms extends Model
{
    use HasFactory,SoftDeletes;
    public $table = "tbl_ms_client_contact_person";

    protected $fillable = [
        'name',
        'designation',
        'email',
        'mobile_no',
        'landline_no',
        'created_by',
        'modified_by',
        'status',
        'client_branch_id',
        'parent_admin_id'
    ];
}
