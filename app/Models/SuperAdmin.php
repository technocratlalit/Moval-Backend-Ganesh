<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuperAdmin extends Model
{
    use HasApiTokens,HasFactory;
    public $table = "tbl_super_admin";
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'mobile_no',
        'status',
    ];
}
