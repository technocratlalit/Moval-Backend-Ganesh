<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CabinBodyTaxSettingModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_ms_cabin_body_tax_settings';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $fillable = [];

    protected $hidden = ['id', 'deleted_at', 'created_at', 'updated_at'];
}
