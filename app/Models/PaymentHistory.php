<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentHistory extends Model
{
    use HasFactory,SoftDeletes;
    public $table = "tbl_job_payment_history";

    protected $fillable = [
        'inspection_id',
        'client_id',
        'payment_link_reference_id',
        'payment_id',
        'payment_link_id',
        'callback_signature',
        'link_status',
        'payment_status',
        'payment_link',
        'order_id'
    ];

}
