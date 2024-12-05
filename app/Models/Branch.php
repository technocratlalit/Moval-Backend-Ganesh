<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
 
class Branch extends Model
{
    use HasFactory,SoftDeletes;
    public $table = "tbl_ms_admin_branch";

    protected $fillable = [ 
        'admin_branch_name',
        'contact_person',
        'mobile_no',
        'email',
        'address',
        'created_by',
        'admin_id',
        'created_at',
        'updated_at'    
    ];

    public function getSop()
    {
        return $this->hasMany(SopMaster::class, 'admin_branch_id', 'id');
    }

    public function getSopPagination($perPage = 10, $page = 1)
    {
        return $this->getSop()->paginate($perPage, ['*'], 'page', $page);
    }

    public function paginated($perPage = 10, $page = 1)
    {
        return $this->paginate($perPage, ['*'], 'page', $page);
    }
    public function admins()
    {
        return $this->belongsTo(Admin_ms::class, 'admin_id', 'id');
    }
    
}
