<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxSetting extends Model
{
    use HasFactory, SoftDeletes;
    public $table = "tbl_ms_tax_dep_settings";

    protected $fillable = [
        'inspection_id',
        'IsZeroDep',
        'DepBasedOn',
        'MetalDepPer',
        'RubberDepPer',
        'GlassDepPer',
        'FibreDepPer',
        'GSTonEstimatedLab',
        'GstonAssessedLab',
        'GSTLabourPer',
        'GSTEstimatedPartsPer',
        'GSTAssessedPartsPer',
        'IMT23DepPer',
        'MutipleGSTonParts',
        'MultipleGSTonLab',
        'IGSTonPartsAndLab',
        'MultipleGSTonBilled',
        'GSTBilledPartPer',
    ];
}
