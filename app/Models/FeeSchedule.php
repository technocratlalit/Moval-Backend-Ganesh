<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
class FeeSchedule extends Model
{
    use HasFactory,HasApiTokens;
    
    public $table = "tbl_ms_fee_schedule";
    protected $fillable = [
        'cgst',
        'sgst',
        'igst',
        'level1',
        'level2',
        'level3',
        'level4',
        'level5',
        'level5_percent',
        'spot_survey_fee',
        'reinspection_fee',
        'verification_fee',
        'conveyance_a',
        'conveyance_b',
        'conveyance_c',
        'city_category',
        'created_by',
        'updated_by',
        'client_id',
    ];
     public static function getTableColumns(array $excludeColumns = [])
    {
        $columns = Schema::getColumnListing((new self)->getTable());

        // Exclude specified columns
        $filteredColumns = array_diff($columns, $excludeColumns);

        return $filteredColumns;
    }
}
