<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AppSetting extends Model
{
    use HasFactory,SoftDeletes;
    public $table = "tbl_settings";
    public $timestamps = false;

    protected $fillable = [
        'type',
        'message'
    ];
}
