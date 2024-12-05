<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feebill_report extends Model
{
    use HasFactory,SoftDeletes;
    public $table = "tbl_ms_inspection_feebill_report";

    protected $fillable = [
        'id',
        'bill_no',
        'bill_date',
        'issued_to',
        'payment_by',
        'surveyFee',
        'conveyanceFee',
        'vehiclePhotographs',
        'miscellaneous',
        'survey_fee_total',
        'conveyance_fee_total',
        'photographs_amount_total',
        'miscellaneous_amount_total',
        'amount_before_tax',
        'cash_receipted',
        'cgst_percentage',
        'sgst_percentage',
        'igst_percentage',
        'gst_amount',
        'amount_after_tax',
        'bank_details',
        'bank_code',
        'bank_id',
        'comment',
        'inspection_id',
        'created_by',
        'updated_by',
    ];

    protected $guarded = [];

    protected $casts = [
        'surveyFee' => 'json',
        'conveyanceFee' => 'json',
        'vehiclePhotographs' => 'json',
        'miscellaneous' => 'json',
        'bank_details' => 'json',
    ];

    public function get_bank_details()
    {
        return $this->belongsTo(BanksDetailsModel::class, 'bank_id', 'id');
    }

    public function get_inspection()
    {
        return $this->belongsTo(Inspection::class, 'inspection_id', 'id');
    }
}
