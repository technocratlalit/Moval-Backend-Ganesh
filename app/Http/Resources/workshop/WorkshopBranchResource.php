<?php

namespace App\Http\Resources\workshop;
use App\Models\Msbranchcontact;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkshopBranchResource extends JsonResource
{
	public function toArray($request)
    {   
   $id=$request->id;
	$sop_master=Msbranchcontact::where('id',$id)->first();
	
      return [
            'id' => $sop_master->id,
            'name' => $sop_master->name,
            'mobile_no' => $sop_master->mobile_no,
            'email' => $sop_master->email,
            'otp' => $sop_master->otp,
            'created_by' => $sop_master->created_by,
            'username' => $sop_master->username,
            'workshop_branch_id' => $sop_master->workshop_branch_id,
            'created_at' => $sop_master->created_at->format('Y-m-d H:i:s'),
            'updated_at' => ($sop_master->updated_at)?$sop_master->updated_at->format('Y-m-d H:i:s'):"",
        ];
	
	}
}


?>