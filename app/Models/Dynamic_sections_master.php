<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
class Dynamic_sections_master extends Model
{
    use HasFactory,SoftDeletes;
    public $table = "tbl_ms_dynamic_sections_master";

    protected $fillable = [
        'admin_id',
        'SrNo',
        'Heading',
        'Details',
		'admin_branch_id',
		'updated_by',
		'created_by',
    ];

     public static function getTableColumns(array $excludeColumns = [])
    {
        $columns = Schema::getColumnListing((new self)->getTable());

        // Exclude specified columns
        $filteredColumns = array_diff($columns, $excludeColumns);

        return $filteredColumns;
    }
}
