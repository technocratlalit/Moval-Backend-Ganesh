<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin_ms extends Model
{
    use HasApiTokens, HasFactory, SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('withTrashed', function ($query) {
            $query->withTrashed();
        });
    }

    public function getSuperAdmin()
    {
        return $this->hasOne(SuperAdmin_ms::class, 'id', 'super_admin_id');
    }

    public function parent()
    {
        return $this->belongsTo(Admin_ms::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Admin_ms::class, 'parent_id', 'id')->withTrashed();
    }

    public function getAllDescendantsAttribute()
    {
        return $this->children->flatMap(function ($child) {
            return [$child, $child->getAllDescendantsAttribute()];
        });
    }

    public function subChildren()
    {
        return $this->children()->with('subChildren');
    }


    public function branchAdminChildren()
    {
        return $this->hasMany(Branch::class, 'admin_id', 'id')->withTrashed();
    }

    public function paginatedbranchAdmin($perPage = 10, $page = 1)
    {
        return $this->branchAdminChildren()->paginate($perPage, ['*'], 'page', $page);
    }


    public function paginated($perPage = 10, $page = 1)
    {
        return $this->paginate($perPage, ['*'], 'page', $page);
    }

    public function paginatedChildren($perPage = 10, $page = 1)
    {
        return $this->children()->paginate($perPage, ['*'], 'page', $page);
    }

    public function get_all_branches()
    {
        return $this->hasMany(Branch::class, 'admin_id', 'id');
    }

    public function getBranch()
    {
        return $this->hasOne(Branch::class, 'admin_id', 'id');
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class, 'admin_id', 'id');
    }

    public function get_employees()
    {
        return $this->hasMany(Employeems::class, 'created_by', 'id');
    }

    public function get_all_clients()
    {
        return $this->hasMany(Clientms::class, 'admin_id', 'id');
    }

    public function get_workshop()
    {
        return $this->hasMany(Workshop::class, 'admin_id', 'id');
    }

    public function get_workshop_branches()
    {
        return $this->get_workshop()->hasMany(WorkshopBranchMs::class, 'workshop_id', 'id');
    }

    public function get_sop()
    {
        return $this->hasMany(SopMaster::class, 'admin_id', 'id');
    }

    public function get_jobs()
    {
        return $this->hasMany(Inspection::class, 'admin_id', 'id');
    }

    public $table = "tbl_ms_admin";
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
        'role',
        'admin_branch_id',
        'branch_admin',
        'admin_id',
        'super_admin_id',
        'branch_admin_id'
    ];
}
