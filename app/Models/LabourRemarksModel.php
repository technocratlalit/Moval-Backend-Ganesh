<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabourRemarksModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_labour_remarks';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = [];
    protected $fillable = [];
}
