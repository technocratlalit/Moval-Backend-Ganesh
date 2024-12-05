<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store_Attachment extends Model
{
    use HasFactory,SoftDeletes;
    public $table = "tbl_ms_attachement";

     protected $fillable = [
        'inspection_id',
        'attachments',
        'created_at',
        'updated_at',
    ];
}
