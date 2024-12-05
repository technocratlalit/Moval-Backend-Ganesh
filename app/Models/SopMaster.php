<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SopMaster extends Model
{
    use HasFactory,SoftDeletes;
    public $table = "tbl_ms_sop_master";

    public function getAdminBranch(){
        return $this->hasOne(Branch::class , 'id' ,'admin_branch_id');
    }

    protected $fillable = [ 
        'sop_name',
        'admin_branch_id',
        'admin_id',
        'created_at',
        'updated_at' ,
        'vehichle_images_field_label',
        'document_image_field_label',
        'super_admin_id',
        "is_location_allowed"
    ];

}
