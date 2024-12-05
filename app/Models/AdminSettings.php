<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminSettings extends Model
{
    use HasApiTokens,HasFactory;
    public $table = "tbl_admin_settings";
    public $timestamps = false;
    protected $fillable = [
        'admin_id',
        'type',
        'message'
    ];
}
