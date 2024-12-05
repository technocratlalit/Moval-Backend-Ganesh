<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ClientBranchMs extends Model
{
    use HasFactory,SoftDeletes;
    public $table = "tbl_ms_client_branches";
    protected $fillable = [
                'client_id',
                'office_code',
                'office_name',
                'office_address',
                'gst_no',
                'contact_detail',
                'manager_name',
                'manager_email',
                'manager_mobile_no',
                'within_state',
                'mode_of_payment',
                'amount_per_job',
                'status',
                'parent_admin_id'
            ];

    public function get_all_clients_branches()
    {
        return $this->belongsTo(Clientms::class, 'client');
    }
}










