<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Client extends Model
{
    use HasFactory,SoftDeletes;
    public $table = "tbl_clients";

    protected $fillable = [
        'name',
        'contact_person_name',
        'email',
        'address',
        'registered_mobile_no',
        'mode_of_payment',
        'mobile_no',
        'amount_per_job',
        'created_by',
        'modified_by',
        'status',
        'parent_admin_id'
    ];
}
