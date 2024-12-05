<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workshopbranchms extends Model
{
    use HasFactory,SoftDeletes;
    public $table = "tbl_ms_workshop_branch";


   protected $fillable = [ 
        'workshop_branch_name',
        'address',
        'gst_no',
        'contact_details',
        'manager_name',
        'manager_mobile_num',
        'manager_email',
		'workshop_id',
		'created_by',
		'modified_by',
        'created_at',
        'updated_at'    
    ]; 

    public function get_all_workshop_branches()
    {
        return $this->belongsTo(WorkshopBranchMs::class, 'workshop_id', 'id');
    }

    public function get_workshop()
    {
        return $this->hasOne(Workshop::class, 'id', 'workshop_id');
    }
}
