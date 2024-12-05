<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortcutsModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_shortcuts';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = [];
    protected $fillable = [];
}
