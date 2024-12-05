<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssismentDetailList extends Model
{
    use HasApiTokens,HasFactory;
    public $table = "tbl_ms_assessment_detail_list";
    protected $fillable = [
        'description',
        'gst',
        'qe',
        'qa',
        'est_rate',
        'ass_rate',
        'imt_23',
        'ai_part_amt',
        'est_amt',
        'ass_amt',
        'hsn_code',
        'category',
        'est_lab',
        'ass_lab',
        'painting_lab',
        'sac',
        'remarks',
        'e_sr_no',
        'b_sr_no',
        'billed_part_amt',
        'billed_lab_amt',
        'billed_labour_amt',
        'payable_amt',
        'labour_type',
        'assisment_id',
        'lab_ai_amt',
        'inspection_id',
        'created_by',
        'updated_by',
    ];
}
