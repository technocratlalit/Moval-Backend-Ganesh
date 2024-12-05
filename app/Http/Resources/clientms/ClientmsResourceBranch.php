<?php

namespace App\Http\Resources\clientms;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\BranchContactPerson;
use App\Models\ClientBranch;


class ClientmsResourceBranch extends JsonResource
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
            'branch_id' => $this->branch_id,
            'branch_name' => $this->branch_name,
            'name' => $this->name,
            'designation' => $this->designation,
            'email' => $this->email,
            'mobile_no' => $this->mobile_no,
            'landline_no' => $this->landline_no,
            'mobile_no' => $this->mobile_no,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
          //  'branch_id' => $this->branch_id,
            'parent_admin_id' => $this->parent_admin_id,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
        ];
    }
}




