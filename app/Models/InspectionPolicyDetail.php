<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class InspectionPolicyDetail extends Model
{
    use HasFactory;
    protected $table = 'tbl_ms_inspection_policy_detail';
    protected $guarded = [];

    public function get_client_branch()
    {
        return $this->hasmany(ClientBranchMs::class, 'client_id', 'client_branch_id');
    }

    public function get_appointing_office()
    {
        return $this->hasOne(ClientBranchMs::class, 'id', 'appointing_office_code');
    }

    public function get_insurer()
    {
        return $this->hasOne(ClientBranchMs::class, 'client_id', 'client_id');
    }

    public static function getTableColumns(array $excludeColumns = [])
    {
        $columns = Schema::getColumnListing((new self)->getTable());

        // Exclude specified columns
        $filteredColumns = array_diff($columns, $excludeColumns);

        return $filteredColumns;
    }

    public function get_cabin_load_body_ass()
    {
        return $this->hasOne(CabinBodyAssessmentModel::class, 'inspection_id', 'inspection_id');
    }

    public function get_cabin_load_body_tax()
    {
        return $this->hasOne(CabinBodyTaxSettingModel::class, 'inspection_id', 'inspection_id');
    }
}
