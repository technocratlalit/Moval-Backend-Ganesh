<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Msbranchcontact extends Model
{
    use HasApiTokens,HasFactory;
    public $table = "tbl_ms_workshop_contact_person";
 
   protected $fillable = [
        'name',
        'mobile_no',
        'email',
        'created_by',
        'otp',
        'username',
        'workshop_branch_id',
        'created_at',
        'updated_at'    
    ]; 
}
