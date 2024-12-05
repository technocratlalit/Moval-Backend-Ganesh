<?php

namespace App\Http\Resources\client;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientBranchResourcems extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
       /* return [
            'id' => $this->id,
            'client_name' => $this->client_name,
            'contact_details' => $this->contact_details,,
            'client_address' => $this->client_address,
            'office_name' => $this->office_name,
            'office_code' => $this->office_code,
            'office_address' => $this->office_address,
            'gst_no' =>$this->gst_no,
            'contact_detail' => $this->contact_detail,
            'manager_name' => $this->manager_name,
            'manager_email' => $this->manager_email,
            'manager_mobile_no' => $this->manager_mobile_no,,
            'within_state' => $this->within_state,
            'mode_of_payment' => $this->mode_of_payment,
            'amount_per_job' => $this->amount_per_job, 
            'status' => $this->status,,
            'parent_admin_id' => $this->parent_admin_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];*/
        
        return [
            'id' => $this->id,
          //  'name' => $this->name,
            'client_name' => $this->client_name,
            'client_id' => $this->client_id,
        //    'contact_person_name' => $this->contact_person_name,
            'registered_mobile_no' => $this->registered_mobile_no,
            'mode_of_payment' => $this->mode_of_payment,
            'amount_per_job' => $this->amount_per_job,
            'office_name' => $this->office_name,
            'office_address' => $this->office_address,
            'office_code' => $this->office_code,
            'gst_no' => $this->gst_no,
            'contact_detail' => $this->contact_detail,
            'manager_name' => $this->manager_name,
            'manager_mobile_no' => $this->manager_mobile_no,
            'within_state' => $this->within_state,
            'status' => $this->status == "1" ? "Active" : "Inactive",
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'manager_email' => $this->manager_email,
        ];
        
    }
}


