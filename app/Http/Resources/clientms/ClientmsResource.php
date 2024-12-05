<?php

namespace App\Http\Resources\clientms;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\BranchContactPerson;
use App\Models\ClientBranch;


class ClientmsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {    
        return [
            'id' => $this->id,
            'client_branch_id' => $this->client_branch_id,
          //  'contact_person_name' => $this->contact_person_name,
          //  'registered_mobile_no' => $this->registered_mobile_no,
            'mode_of_payment' => $this->mode_of_payment,
            'amount_per_job' => $this->amount_per_job,
            'client_name' => $this->client_name,
            'manager_email' => $this->manager_email,
            'contact_details' => $this->contact_details,
            'client_address' => $this->client_address,
            'created_by_admin' => $this->created_by,
            'created_by_name' => adminname($this->created_by),
            'modified_by_name' => adminname($this->modified_by),
            'bank_id' => $this->bank_id,
            'modified_by_admin' => $this->modified_by,
            'admin_branch_id' => $this->admin_branch_id,
            'within_state' => $this->within_state,
            'office_code' => $this->office_code,
            'office_address' => $this->office_address,
            'gst_no' => $this->gst_no,
            'office_name' => getBranchbybranchid($this->admin_branch_id),
            'status' => $this->status == "1" ? "Active" : "Inactive",
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}









