<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clientms extends Model
{
    use HasFactory,SoftDeletes;
    public $table = "tbl_ms_clients";

    
    

    protected $fillable = [
        // 'name',
        // 'contact_person_name',
        // 'email',
        'client_name',
        'client_address',
        'contact_details',
        // 'address',
        // 'registered_mobile_no',
        // 'mode_of_payment',
        // 'mobile_no',
        // 'amount_per_job',
        'created_by',
        'modified_by',
        'status',
        'parent_admin_id',
        'admin_id',
		'admin_branch_id',
		'bank_id',
    ];

    public function get_all_clients_branches()
    {
        return $this->hasMany(ClientBranchMs::class, 'client_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin_ms::class, 'parent_admin_id', 'id');
    }
}
