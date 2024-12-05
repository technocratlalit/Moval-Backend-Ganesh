<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class InspectionAccidentDetail extends Model
{
    use HasFactory;

    protected $table = 'tbl_ms_inspection_accident_detail';
    protected $guarded  = [];
    protected $appends = [
        'accident_date',
        'accident_time',
        'survey_date',
        'survey_time',
    ];
     public static function getTableColumns(array $excludeColumns = [])
    {
        $columns = Schema::getColumnListing((new self)->getTable());

        // Exclude specified columns
        $filteredColumns = array_diff($columns, $excludeColumns);

        return $filteredColumns;
    }

    public function getAccidentDateAttribute()
    {
        return date('Y-m-d', strtotime($this->date_time_accident));
    }

    public function getAccidentTimeAttribute()
    {
        return date('H:i:s', strtotime($this->date_time_accident));
    }

    public function getSurveyDateAttribute()
    {
        return date('Y-m-d', strtotime($this->Survey_Date_time));
    }

    public function getSurveyTimeAttribute()
    {
        return date('H:i:s', strtotime($this->Survey_Date_time));
    }


}
