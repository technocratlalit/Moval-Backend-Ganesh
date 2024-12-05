<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
// use Illuminate\Database\Eloquent\SoftDeletes;

class ImportantUpdates extends Model
{
    use HasFactory,HasApiTokens;
    public $table = "tbl_important_updates";
    protected $fillable = [
        'message',
        'to_admin_ids',
        'seen_admin_ids',
    ];
}
