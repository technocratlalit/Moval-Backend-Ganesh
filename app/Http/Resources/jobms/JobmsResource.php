<?php

namespace App\Http\Resources\jobms;

use Illuminate\Http\Resources\Json\JsonResource;

class JobmsResource extends JsonResource
{
    public function toArray($request)
    {
        $array = ['Mobile app', 'Motor Survey Link', 'Manual Upload'];
        return  [
            'id' => $this->id,
            'claim_type' => $this->claim_type,
            'Job_Route_To' => $this->Job_Route_To,
            'Job_Route' => $array[$this->Job_Route_To - 1],
            'loss_estimate' => $this->loss_estimate,
            'insurer_liability' => $this->insurer_liability,
            'vehicle_reg_no' => $this->vehicle_reg_no,
            'insured_name' => $this->insured_name,
            'place_survey' => $this->place_survey,
            'office_code' => $this->office_code,
            'workshop_id' => $this->workshop_id,
            'workshop_branch_id' => $this->workshop_branch_id,
            'contact_person' => $this->contact_person,
            'contact_no' => $this->contact_no,
            'user_role' => $this->user_role,
            'client_id' => $this->client_id,
            'client_branch_id' => $this->client_branch_id,
            'jobassignedto_workshopEmpid' => $this->jobassignedto_workshopEmpid,
            'jobjssignedto_surveyorEmpId' => $this->jobjssignedto_surveyorEmpId,
//            'client_name' => getClientNameByClientid($this->getClient->client_id),
            'client_name' => isset($this->getClient->client_name) ? $this->getClient->client_name : '',
            'admin_branch_id' => $this->admin_branch_id,
            'branch_name' => getBranchbybranchid($this->branch_name),
            'date_of_appointment' => substr($this->date_of_appointment, 0, 10),
            'sop_id' => $this->sop_id,
            'created_by' => $this->created_by,
            'assigned_to' => getassignedname($this->user_role, $this->jobassignedto_workshopEmpid, $this->jobjssignedto_surveyorEmpId),
            'submitted_by' => submitted_by($this->id),
            'approved_by' => approved_by($this->id),
            'assigned_by' => adminname($this->assigned_by),
            'assigned_on' => $this->assigned_on,
            'upload_type' => $this->upload_type,
            'updated_by' => $this->updated_by,
            'no_of_photos' => photosuploaded1($this->id),
            'no_of_documents' => photosuploaded2($this->id),
            'created_at' => ($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : "",
            'updated_at' => ($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : "",
            'job_status' => $this->job_status,
            'workshop_name' => isset($this->get_workshop_branch__details->workshop_branch_name) ? $this->get_workshop_branch__details->workshop_branch_name : '',
            'report_no' => $this->Reference_No ?? $this->Reference_No,
            'claim_no' => $this->claim_no ?? $this->claim_no,
            'policy_no' => $this->policy_no ?? $this->policy_no,
        ];
    }
}
