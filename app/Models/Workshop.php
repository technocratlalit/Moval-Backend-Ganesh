<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workshop extends Model
{
    use HasFactory,SoftDeletes;
    public $table = "tbl_ms_workshop";

    protected $fillable = [
        'workshop_name',
        'address',
        'gst_no',
        'contact_detail',
        'admin_id',
		'admin_branch_id',
        'created_at',
        'updated_at',
        'main_admin_id',
        'is_local_workshop',
    ]; 

    public function get_all_workshop_branches()
    {
        return $this->hasMany(WorkshopBranchMs::class, 'workshop_id', 'id');
    }
    

    

   
 
 
}
