<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminInvoice extends Model
{
    use HasApiTokens,HasFactory;
    public $table = "tbl_admin_invoice";
    protected $fillable = [
        'admin_id',
        'invoice_date',
        'number_of_reports',
        'report_cost',
        'bill_amount',
        'payment_status',
        'payment_date',
        'last_date_of_payment',
        'payment_mode',
        'paid_amount',
        'name',
        'address'
    ];
}
