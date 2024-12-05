<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PODTemplate extends Model
{
    use HasFactory;
    public $table = "tbl_ms_pod_templates";
    protected $fillable = [
        'admin_branch_id',
        'particuler_of_damage',
    ];

}
