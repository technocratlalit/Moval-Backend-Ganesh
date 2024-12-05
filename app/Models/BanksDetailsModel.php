<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BanksDetailsModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_ms_banks_details';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = [];
    protected $fillable = [];

}
