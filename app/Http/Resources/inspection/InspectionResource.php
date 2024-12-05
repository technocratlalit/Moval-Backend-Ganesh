<?php

namespace App\Http\Resources\inspection;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Inspection;


class InspectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $array = ['Mobile app', 'Motor Survey Link', 'Manual Upload'];

        $explodeAccidentDateTime = !empty($this->date_time_accident) ? explode(" ", $this->date_time_accident) : [];
        $accident_date = isset($explodeAccidentDateTime[0]) ? $explodeAccidentDateTime[0] : "";
        $accident_time = isset($explodeAccidentDateTime[1]) ? $explodeAccidentDateTime[1] : "";

        $explodeSurveyDateTime = !empty($this->Survey_Date_time) ? explode(" ", $this->Survey_Date_time) : [];
        $survey_date = isset($explodeSurveyDateTime[0]) ? $explodeSurveyDateTime[0] : "";
        $survey_time = isset($explodeSurveyDateTime[1]) ? $explodeSurveyDateTime[1] : "";

        return [
            'id' => $this->id,
            'inspection_id' => $this->id,
            'claim_type' => $this->claim_type,
            'claim_no' => $this->claim_no,
            'policy_no' => $this->policy_no,
            'policy_valid_from' => $this->policy_valid_from,
            'policy_valid_to' => $this->policy_valid_to,
            'client_branch' => $this->client_branch,
            'client_branch_id' => $this->client_branch_id,
            'admin_branch_id' => $this->admin_branch_id,
            'appointing_office_code' => $this->appointing_office_code,
            'operating_office_code' => $this->operating_office_code,
            'insured_name' => $this->insured_name,
            'branch_name' => getBranchbybranchid($this->branch_name),
            'insured_address' => $this->insured_address,
            'insured_mobile_no' => $this->insured_mobile_no,
            'vehicle_reg_no' => $this->vehicle_reg_no,
            'vehicle_type' => $this->vehicle_type,
            'date_purchase'=>$this->date_purchase,
            'date_registration' => $this->date_of_registration,
            'chassis_no' => $this->chassis_no,
            'engine_no' => $this->engine_no,
            'vehicle_make' => $this->vehicle_make,
            'vehicle_model' => $this->vehicle_model,
            'odometer_reading' => $this->odometer_reading,
            'date_time_accident' => $this->date_time_accident,
            'accident_date' => $accident_date,
            'accident_time' => $accident_time,
            'place_accident' => $this->place_accident,
            'date_time_appointment' => $this->date_time_appointment,
            'place_survey' => $this->place_survey,
            'workshop_name' => $this->workshop_name,
            'workshop_id' => $this->workshop_id,
            'workshop_branch_id' => $this->workshop_branch_id,
            'contact_person' => $this->contact_person,
            //   'contact_person_mobile' => $this->contact_person_mobile,
//        'contact_no' => $this->contact_person_mobile,
            'update_by' => $this->update_by,
            'updated_date' => $this->updated_date,
            'accident_brief_description' => $this->accident_brief_description,
            'damage_corroborates_with_cause_of_loss' => $this->damage_corroborates_with_cause_of_loss,
            'accompanied_insurer_officer_details' => $this->accompanied_insurer_officer_details,
            'major_physical_damages' => $this->major_physical_damages,
            'suspected_Internal_damages' => $this->suspected_Internal_damages,
            'spot_Survey_details' => $this->spot_Survey_details,
            'preexisting_old_damages' => $this->preexisting_old_damages,
            'preferred_mode_of_assessment' => $this->preferred_mode_of_assessment,
            'surveyor_APP_Token_Number' => $this->surveyor_APP_token_number,
            'ILA_Submitted_on' => $this->ILA_Submitted_on,
            'ILA_discussed_with' => $this->ILA_discussed_with,
            'Reference_No' => $this->Reference_No,
            'Chassis_No_PV' => $this->Chassis_No_PV,
            'Engine_No_PV' => $this->Engine_No_PV,
            'Survey_Date_time' => $this->Survey_Date_time,
            'survey_date' => $survey_date,
            'survey_time' => $survey_time,
            'Vehicular_document_observation' => $this->Vehicular_document_observation,
            'Job_Route' => $array[$this->Job_Route_To - 1],
            'loss_estimate' => $this->loss_estimate,
            'insurer_liability' => $this->insurer_liability,
            'vehicle_reg_no' => $this->vehicle_reg_no,
            'office_code' => $this->office_code,
            'contact_no' => $this->contact_no,
            'user_role' => $this->user_role,
            'client_id' => $this->client_id,
            'jobassignedto_workshopEmpid' => $this->jobassignedto_workshopEmpid,
            'jobjssignedto_surveyorEmpId' => $this->jobjssignedto_surveyorEmpId,
            'client_name' => getClientNameByClientid($this->client_id),
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
            'Job_Route_To' => isset($this->Job_Route_To) ? $this->Job_Route_To : "",
            // 'vehicle_reg_no' => vehicle_reg_no($this->job_id),
        ];


    }
}
