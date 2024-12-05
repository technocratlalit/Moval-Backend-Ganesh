<?php

namespace App\Http\Resources\branch;
use App\Models\Branch;
use App\Models\ClientBranchMs;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResourcems extends JsonResource
{  
   public function toArray($request)
    { 
	  $id=$request->id;
	   $all=ClientBranchMs::where('id',$id)->first();
  return [
            'id' => $all->id,
            'office_code' => $all->office_code,
            'office_name' => $all->office_name,
            'office_address' => $all->office_address,
            'gst_no' => $all->gst_no,
            'contact_detail' => $all->contact_detail,
            'manager_name' => $all->manager_name,
            'manager_email' => $all->manager_email,
            'manager_mobile_no' => $all->manager_mobile_no,
            'within_state' => $all->within_state,
            'mode_of_payment' => $all->mode_of_payment,
            'admin_id' => $all->admin_id,
            'created_by' => $all->created_by,
			'created_at' => $all->created_at->format('Y-m-d H:i:s'),
            'updated_at' => ($all->updated_at)?$all->updated_at->format('Y-m-d H:i:s'):"",
        ];
		
		
		
	}
  
}


