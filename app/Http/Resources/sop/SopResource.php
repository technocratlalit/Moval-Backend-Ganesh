<?php

namespace App\Http\Resources\sop;
use App\Models\SopMaster;
use App\Models\SopField;
use Illuminate\Http\Resources\Json\JsonResource;

class SopResource extends JsonResource
{
	public function toArray($request)
    {   
	$id=$request->id;
	$sop_master=SopMaster::where('id',$id)->first();
	$sop_details=SopField::where('sop_id',$id)->get();
	
      $data['sop_master']= [
            'id' => $sop_master->id,
            'sop_name' => $sop_master->sop_name,
            'admin_branch_id' => $sop_master->admin_branch_id,
            'admin_id' => $sop_master->admin_id,
            'is_location_allowed' => $sop_master->is_location_allowed,
            'can_record_video' => $sop_master->can_record_video,
            'vehichle_images_field_label' => json_decode($sop_master->vehichle_images_field_label),
            'document_image_field_label' => json_decode($sop_master->document_image_field_label),
            'created_at' => $sop_master->created_at->format('Y-m-d H:i:s'),
            'updated_at' => ($sop_master->updated_at)?$sop_master->updated_at->format('Y-m-d H:i:s'):"",
        ];
	$i=0;
	
/*	foreach($sop_details as $sopd){
		$data['sop_details'][$i]= [
            'id' => $sopd->id,
            'sop_id' => $sopd->sop_id,
              'type' => $sopd->type,
            'form_field_lable' => $sopd->form_field_lable,
            'created_at' => $sopd->created_at->format('Y-m-d H:i:s'),
            'updated_at' => ($sopd->updated_at)?$sopd->updated_at->format('Y-m-d H:i:s'):"",
        ];
		$i++;
	}*/
		return $data;
	}
}


?>