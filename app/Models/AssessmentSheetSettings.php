<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentSheetSettings extends Model
{
    use HasFactory;
    public $table = "tbl_ms_assessmentsheet_settings";
    protected $fillable = [
        "inspection_id",
        "display_ai",
        "display_hsn",
        "copy_est_amt",
        "description_in_uppercase",
        "description_in_sentancecase",
        "display_bill_sr_no",
        "display_gst_summary",
        "display_gst_summary_part_category_wise"
    ];
}
