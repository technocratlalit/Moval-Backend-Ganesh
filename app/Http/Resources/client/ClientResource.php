<?php

namespace App\Http\Resources\client;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\BranchContactPerson;
use App\Models\ClientBranch;


class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {   

        $clientBranch = ClientBranch::where('client_id', '=', $this->id)->where('status', '=', '1')->select('name','id')->get();
        foreach($clientBranch as $clientBranchObj){
            $branch_contact = BranchContactPerson::where('branch_id', '=', $clientBranchObj->id)->where('status', '=', '1')->select('name','id')->get();
           $clientBranchObj->branch_contact = $branch_contact;
        }

        return [
            'id' => $this->id,
            'branch_list' => $clientBranch,
            'contact_person_name' => $this->contact_person_name,
            'registered_mobile_no' => $this->registered_mobile_no,
            'mode_of_payment' => $this->mode_of_payment,
            'amount_per_job' => $this->amount_per_job,
            'name' => $this->name,
            'email' => $this->email,
            'mobile_no' => $this->mobile_no,
            'address' => $this->address,
            'created_by_admin' => $this->created_by_admin,
            'modified_by_admin' => $this->modified_by_admin,
            'status' => $this->status == "1" ? "Active" : "Inactive",
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
