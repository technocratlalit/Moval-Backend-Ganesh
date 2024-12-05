<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimateStructure extends Model
{
    use HasFactory;
    public $table = "tbl_ms_estimate_structures";
    
    protected $fillable = [
       'description',
       'structure_name',
       'gst',
       'imt_23',
       'qe',
       'qa',
       'est_rate',
       'ass_rate',
       'ai_part_amt',
       'est_amt',
       'ass_amt',
       'hsn_code',
       'category',
       'labour_type',
       'est_lab',
       'ass_lab',
       'lab_ai_amt',
       'painting_lab',
       'sac',
       'remarks',
       'e_sr_no',
       'b_sr_no',
       'billed_part_amt',
       'billed_lab_amt',
       'payable_amt'
    ];
}
