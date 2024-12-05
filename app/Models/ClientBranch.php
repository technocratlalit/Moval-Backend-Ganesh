<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ClientBranch extends Model
{
    use HasFactory,SoftDeletes;
    public $table = "tbl_client_branches";
    protected $fillable = [
        'name',
        'client_id',
        'contact_person_name',
        'email',
        'address',
        'registered_mobile_no',
        'mode_of_payment',
        'mobile_no',
        'amount_per_job',
        'manager_email',
        'status',
        'parent_admin_id'
    ];
}
