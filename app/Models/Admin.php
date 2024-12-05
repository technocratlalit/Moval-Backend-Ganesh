<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Model
{
    use HasApiTokens,HasFactory;
    public $table = "tbl_admin";
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'mobile_no',
        'parent_id',
        'status',
        'is_set_password',
        'can_download_report',
        'letter_head_img',
        'letter_footer_img',
        'signature_img',
        'reference_no_prefix',
        'designation',
        'membership_no',
        'each_report_cost',
        'number_of_photograph',
        'duraton_delete_photo',
        'billing_start_from',
        'next_billing_date',
        'report_no_start_from',
        'authorized_person_name',
    ];
}
