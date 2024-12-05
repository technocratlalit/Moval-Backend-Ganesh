<?php

namespace App\Http\Resources\branch;
use App\Models\Branch;
use App\Models\Admin_ms;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
{  
   public function toArray($request)
    { 
     


	   $id=$request->id;
	   $all=Branch::where('id',$id)->first();
	   
	   $letter_head_img = "";
        if (isset($all->letter_head_img) && $all->letter_head_img != "") {
           $letter_head_img =  asset('public/storage/' . $all->letter_head_img);
        }

        $letter_footer_img = "";
        if (isset($all->letter_footer_img) && $all->letter_footer_img != "") {
           $letter_footer_img =  asset('public/storage/' . $all->letter_footer_img);
        }

        $signature_img = "";
        if (isset($all->signature_img) && $all->signature_img != "") {
           $signature_img =  asset('public/storage/' . $all->signature_img);
        }
       return [
            'id' => $all->id,
            'branch_name' => $all->admin_branch_name,
            'admin_branch_name' => $all->admin_branch_name,
            'email' => $all->email,
            'address' => $all->address,
            'mobile_no' => $all->mobile_no,
            'contact_person' => $all->contact_person,
            'admin_id' => $all->admin_id,
            'created_by' => $all->created_by,
			   'created_at' => $all->created_at->format('Y-m-d H:i:s'),
            'updated_at' => ($all->updated_at)?$all->updated_at->format('Y-m-d H:i:s'):"",
            'letter_head_img' => (!empty($all->letter_head_img)) ? $letter_head_img : $letter_head_img,
            'letter_footer_img' => (!empty($all->letter_footer_img)) ? $letter_footer_img : $letter_footer_img,
            'signature_img' => (!empty($all->signature_img)) ? $signature_img : $signature_img,
        ];
		
		
		
	}
  
}