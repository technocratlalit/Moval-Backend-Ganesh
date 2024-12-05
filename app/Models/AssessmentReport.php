<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentReport extends Model
{
    use HasFactory;
    public $table = "tbl_ms_assessment_reports";

    protected $fillable = [
        'inspection_id',
        'totalMetalNonIMT',
        'totalRubberNonIMT',
        'totalGlass',
        'totalFiber',
        'totalMetalIMT',
        'totalRubberIMT',
        'depMetalNonIMT',
        'depRubberNonIMT',
        'depGlass',
        'depFiber',
        'DepMetalIMT',
        'DepRubberIMT',
        'gstAmtMetal',
        'gstAmtRubber',
        'gstAmtGlass',
        'gstAmtIMTMetal',
        'gstAmtIMTRubber',
        'totallabour',
        'totalPainting',
        'totalPaintingIMT',
        'depAmtPainting',
        'depAmtPaintingIMT',
        'gstlabour',
        'gstPainting',
        'gstPaintingIMT',
    ];

    public static function createOrUpdate(array $attributes)
    {
        $inspectionId = $attributes['inspection_id'];

        $record = self::where('inspection_id', $inspectionId)->first();

        if ($record) {
            // Update existing record
            $record->update($attributes);
        } else {
            // Create new record
            $record = self::create($attributes);
        }

        return $record;
    }
}
