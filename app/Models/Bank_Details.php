<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Bank_Details extends Model
{
    use HasFactory,SoftDeletes;

    public $table = "tbl_ms_bank_details";

    protected $fillable = [
        'bank_code',
        'bank_name',
        'branch_address',
        'account_number',
        'account_type',
        'ifsc',
        'micr',
        'created_by',
        'admin_id',
        'admin_branch_id',
        'updated_at'
    ];

}
