<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store_Attachment_Master extends Model
{
    use HasFactory,SoftDeletes;
    public $table = "tbl_ms_attachements_master";

    protected $fillable = [
        'admin_branch_id',
        'admin_id',
        'key',
        'value',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];
}
