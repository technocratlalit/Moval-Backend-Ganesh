<?php

namespace App\Http\Resources\workshop;
use App\Models\Workshop;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkshopResource extends JsonResource
{
	public function toArray($request)
    {   
   $id=$request->id;
	$sop_master=Workshop::where('id',$id)->first();
	
      return [
            'id' => $sop_master->id,
            'workshop_name' => $sop_master->workshop_name,
            'address' => $sop_master->address,
            'gst_no' => $sop_master->gst_no,
            'contact_detail' => $sop_master->contact_detail,
            'admin_id' => $sop_master->admin_id,
            'admin_branch_id' => $sop_master->admin_branch_id,
            'created_at' => $sop_master->created_at->format('Y-m-d H:i:s'),
            'updated_at' => ($sop_master->updated_at)?$sop_master->updated_at->format('Y-m-d H:i:s'):"",
        ];
	
	}
}


?>