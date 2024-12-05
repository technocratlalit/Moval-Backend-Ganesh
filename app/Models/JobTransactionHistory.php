<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTransactionHistory extends Model
{
    use HasFactory,SoftDeletes;
    public $table = "tbl_job_transaction_history";
    public $timestamps = false;
    protected $fillable = [
        'inspection_id',
        'status',
        'user_type',
        'user_id',
        'on_date_time',
    ];
}
