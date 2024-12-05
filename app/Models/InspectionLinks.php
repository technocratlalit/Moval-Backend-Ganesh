<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionLinks extends Model
{
    use HasFactory;
    protected $table = 'tbl_ms_inspection_links';
    protected $guarded  = [];
}
