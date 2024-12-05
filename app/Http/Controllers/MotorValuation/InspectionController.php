<?php

namespace App\Http\Controllers\MotorValuation;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Jobms;
use App\Models\Admin_ms;
use App\Models\TaxSetting;
use App\Models\Manuallupload;
use App\Models\Inspection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\job\JobResource;
use App\Http\Resources\inspection\InspectionResource;
use App\Http\Resources\jobms\JobmsResource;
use DB;
use App\Http\Controllers\BaseController;
use App\Models\InspectionEstimatesDetailsModel;
use Illuminate\Http\JsonResponse;

class InspectionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {


        $inspection = Inspection::where('id', $id)->first();

        if (!empty($inspection)) {
            return $this->sendResponse(new InspectionResource($inspection), "Job Details Fetched Inspection", 200);
        } else {
            return $this->sendError('error', "invalid Job id", 201);
        }
    }


    public function update(Request $request)
    {


        $fields = $request->validate(['Job_Route_To' => 'nullable|numeric', 'inspection_id' => 'required|numeric']);


        // $jobms = Inspection::where('id', $request->inspection_id)->first();
        $jobms = Inspection::find($request->inspection_id);
        //dd($jobms->adminMs);
        $adminMs = $jobms->adminMs;

        //$adminMs = Admin_ms::where('id',$branchAdmin->parent_id)->first();

        $msg = "";
        $route = "";
        if (isset($request->Job_Route_To) && $request->Job_Route_To != "") {
            $route = $request->Job_Route_To;
        }
        if ($jobms && $route != "") {
            $jobcurrent_route = $jobms->Job_Route_To;
            $jobcurrent_status = $jobms->job_status;

            $job_status = $jobcurrent_status;
            $jobms->Job_Route_To = $jobcurrent_route;

            //jobassignedto_workshopEmpid,jobjssignedto_surveyorEmpId

            if ($jobcurrent_route == 1) {

                if ($fields['Job_Route_To'] == 2) {
                    $job_status = "pending";
                    $jobms->jobassignedto_workshopEmpid = null;
                    $jobms->jobjssignedto_surveyorEmpId = null;
                    $jobms->Job_Route_To = 2;
                }


                if ($fields['Job_Route_To'] == 3) {
                    $jobms->Job_Route_To = 3;
                    $job_status = "submitted";
                    $jobms->jobassignedto_workshopEmpid = null;
                    $jobms->jobjssignedto_surveyorEmpId = null;
                }
            } else if ($jobcurrent_route == 2) {
                if ($fields['Job_Route_To'] == 1) {
                    $job_status = "created";
                    $jobms->Job_Route_To = 1;
                }
            } else if ($jobcurrent_route == 3) {

                if ($fields['Job_Route_To'] == 2 || $fields['Job_Route_To'] == 1) {

                    $filescount = Manuallupload::where('inspection_id', $fields['inspection_id'])->count();
                    if ($filescount > 0) {

                        $msg = "Job route cannot be changed,images already uploaded";
                    } else {
                        // Inspection::where('id', $request->inspection_id)->delete();
                        if ($fields['Job_Route_To'] == 2) {
                            $jobms->Job_Route_To = 2;
                            $job_status = "pending";
                            $jobms->jobassignedto_workshopEmpid = null;
                            $jobms->jobjssignedto_surveyorEmpId = null;
                        }
                        if ($fields['Job_Route_To'] == 1) {

                            $jobms->Job_Route_To = 1;
                            $job_status = "created";
                            $jobms->jobassignedto_workshopEmpid = null;
                            $jobms->jobjssignedto_surveyorEmpId = null;
                        }
                    }
                }
            }
            $jobms->job_status = $job_status;
            $jobms->update();
        }

        if ($jobms->inspection_reference_no == "" || $jobms->inspection_reference_no == null) {
            // Check if $adminMs is not null before accessing its properties
            if ($adminMs !== null) {
                $reference_no_prefix = $adminMs->reference_no_prefix;
                $report_no_start_from = $adminMs->report_no_start_from;

                $maxIntBillNo = Inspection::where('admin_id', $adminMs->id)->max("int_reference_no");

                $dates = date("Y");
                $tdate = date("y");
                $datee = $tdate + 1;

                if ($maxIntBillNo) {

                    $bill_no_start_from = $maxIntBillNo + 1;

                    $billnumber = $reference_no_prefix . '/' . $dates . '-' . $datee . '/' . $bill_no_start_from;

                    if (Inspection::where(['id' => $request->inspection_id, 'is_generated' => 0])->exists()) {
                        $jobms->inspection_reference_no = $billnumber;
                        $jobms->int_reference_no = $bill_no_start_from;
                        $jobms->is_generated = 1;
                        $jobms->update();
                    }

                } else {
                    $jobms->inspection_reference_no = $reference_no_prefix . '/' . $dates . '-' . $datee . '/' . $report_no_start_from;
                    $jobms->int_reference_no = $report_no_start_from;
                    $jobms->is_generated = 1;
                    $jobms->update();
                }

            }
        } else {
            if (isset($request->Reference_No)) {
                $jobms->inspection_reference_no = $request->Reference_No;
                $jobms->update();
            }
        }

        $Survey_Date_time = !empty($request->survey_date) ? date('Y-m-d', strtotime($request->survey_date)) : '';
        $date_time_accident=($request->filled("accident_date") ? date('Y-m-d', strtotime($request->accident_date)) : '');
        // $date_time_accident = !empty($request->date_accident) ? date('Y-m-d', strtotime($request->date_accident)) : '';
        if(!empty($request->survey_time)) {
            $Survey_Date_time .= " ".date('H:i:s', strtotime($request->survey_time));
        }
        if(!empty($request->accident_time)) {
            $date_time_accident .= " ".date('H:i:s', strtotime($request->accident_time));
        }

        $inspection_Date = array(
            'client_branch_id' => $request->client_branch_id,
            'claim_type' => $request->claim_type,
            'claim_no' => $request->claim_no,
            'policy_no' => $request->policy_no,
            'policy_valid_from' => $request->policy_valid_from,
            'policy_valid_to' => $request->policy_valid_to,
            'client_id' => $request->client_id,
            'client_name' => $request->client_name,
            'branch_name' => $request->branch_name,
            'appointing_office_code' => $request->appointing_office_code,
            'operating_office_code' => $request->operating_office_code,
            'insured_name' => $request->insured_name,
            'insured_address' => $request->insured_address,
            'admin_branch_id' => $request->admin_branch_id,
            'insured_mobile_no' => $request->insured_mobile_no,
            'vehicle_reg_no' => $request->vehicle_reg_no,
            'vehicle_type' => $request->vehicle_type,
            'date_purchase'=>$request->date_purchase,
            'date_of_registration' => $request->date_registration,
            'chassis_no' => $request->chassis_no,
            'engine_no' => $request->engine_no,
            'vehicle_make' => $request->vehicle_make,
            'vehicle_model' => $request->vehicle_model,
            'odometer_reading' => $request->odometer_reading,
            'date_time_accident' => $date_time_accident,//$request->date_time_accident,
            'place_accident' => $request->place_accident,
            'date_of_appointment' => $request->date_of_appointment,
            'place_survey' => $request->place_survey,
            'workshop_name' => $request->workshop_name,
            'workshop_branch_id' => $request->workshop_branch_id,
            'contact_person' => $request->contact_person,
            'contact_person_mobile' => $request->contact_person_mobile,
            'accident_brief_description' => $request->accident_brief_description,
            'damage_corroborates_with_cause_of_loss' => $request->damage_corroborates_with_cause_of_loss,
            'accompanied_insurer_officer_details' => $request->accompanied_insurer_officer_details,
            'major_physical_damages' => $request->major_physical_damages,
            'suspected_Internal_damages' => $request->suspected_Internal_damages,
            'spot_Survey_details' => $request->spot_Survey_details,
            'preexisting_old_damages' => $request->preexisting_old_damages,
            'preferred_mode_of_assessment' => $request->preferred_mode_of_assessment,
            'surveyor_APP_token_number' => $request->surveyor_APP_Token_Number,
            'ILA_discussed_with' => $request->ILA_discussed_with,
            "ILA_Submitted_on" => $request->ILA_Submitted_on,
            "Chassis_No_PV" => $request->Chassis_No_PV,
            "Engine_No_PV" => $request->Engine_No_PV,
            "Survey_Date_time" => $Survey_Date_time,//$request->Survey_Date_time,
            "Vehicular_document_observation" => $request->Vehicular_document_observation,
            'update_by' => $request->update_by,
            'updated_date' => date("Y-m-d"),
            'Reference_No' => $jobms->inspection_reference_no,
            'contact_no' => $request->contact_no
        );

        $inspection = Inspection::where('id', $request->inspection_id)->update($inspection_Date);

        $Reference_No = rand(22222, 9999);
        if (!$inspection) {
            $data = array(
                'inspection_id' => $request->inspection_id,
                'Reference_no' => $jobms->inspection_reference_no,
            );
            // $admin = Inspection::create($inspection_Date);


            $taxdetail = TaxSetting::where('inspection_id', $request->inspection_id)->first();
            if (!$taxdetail) {
                $MetalDepPer = getdeprecationpercentagebymonth($request->inspection_id, 'Metal');
                $GlassDepPer = getdeprecationpercentagebymonth($request->inspection_id, 'Glass');
                $RubberDepPer = getdeprecationpercentagebymonth($request->inspection_id, 'Rubber');
                $FibreDepPer = getdeprecationpercentagebymonth($request->inspection_id, 'Fibre');


                $taxdata = array(
                    'inspection_id' => $request->inspection_id,
                    'MetalDepPer' => $MetalDepPer,
                    'RubberDepPer' => $RubberDepPer,
                    'GlassDepPer' => $GlassDepPer,
                    'FibreDepPer' => $FibreDepPer,
                    'GSTLabourPer' => 18,
                    'GSTEstimatedPartsPer' => 28,
                    'GSTAssessedPartsPer' => 28,
                    'IMT23DepPer' => 50

                );

                $taxdetail = TaxSetting::create($taxdata);
            }

            //return $this->sendError('Successfully',"Successfully Upated Inspection",200);
            return $this->sendResponse($data, 'Successfully Upated Inspection.' . $msg . '', 200);
        } else {


            $data = array(
                // 'id' => $request->inspection_id,
                // 'inspection_id' => $inspection->inspection_id,
                'Reference_no' => $jobms->inspection_reference_no,
            );
            $admin = Inspection::where('id', $request->inspection_id)->update($inspection_Date);
            //return $this->sendError('Successfully',"Successfully Upated Inspection",200);


            $taxdetail = TaxSetting::where('inspection_id', $request->inspection_id)->first();

            $MetalDepPer = getdeprecationpercentagebymonth($request->inspection_id, 'Metal');
            $GlassDepPer = getdeprecationpercentagebymonth($request->inspection_id, 'Glass');
            $RubberDepPer = getdeprecationpercentagebymonth($request->inspection_id, 'Rubber');
            $FibreDepPer = getdeprecationpercentagebymonth($request->inspection_id, 'Fibre');


            $taxdata = array(
                'inspection_id' => $request->inspection_id,
                'MetalDepPer' => $MetalDepPer,
                'RubberDepPer' => $RubberDepPer,
                'GlassDepPer' => $GlassDepPer,
                'FibreDepPer' => $FibreDepPer
            );
            if (!$taxdetail) {
                $taxdetail = TaxSetting::create($taxdata);
            } else {
                TaxSetting::where('inspection_id', $request->inspection_id)->update($taxdata);
            }

            return $this->sendResponse($data, 'Successfully Upated Inspection.', 200);
        }
    }

    public function updatejob(Request $request, $jobid)
    {
        $created_by = Auth::user()->id;
        $jobm = Inspection::where('id', $jobid)->first();
        if ($jobm) {
            $jobm->job_status = "approved";
            $jobm->approved_by = $created_by;
            $jobm->update();
            return $this->sendResponse("Success", 'Successfully Upated job status.', 200);
        } else {
            return $this->sendError('error', "invalid Job id", 201);
        }
    }


    public function update_inputbox(Request $request)
    {
        $inspection = Inspection::where('id', $request->inspection_id)->first();
        if (!$inspection) {
            return $this->sendError('There is no records for this Job id in Inspection table so please save Inspection records', "", 201);
        }
        if (isset($request->TowingCharges)) {
            $request->TowingCharges ?? 0;
        }

        #Less figure amt
        $ImposedClause = ($request->ImposedClause > 0) ? $request->ImposedClause : 0.00;
        $CompulsoryDeductable = ($request->CompulsoryDeductable > 0) ? $request->CompulsoryDeductable : 0.00;
        $less_voluntary_excess = ($request->less_voluntary_excess > 0) ? $request->less_voluntary_excess : 0.00;
        $SalvageAmt = ($request->SalvageAmt > 0) ? $request->SalvageAmt : 0.00;
        $CustomerLiability = ($request->CustomerLiability > 0) ? $request->CustomerLiability : 0.00;

        #Add figure amt
        $TowingCharges = ($request->TowingCharges > 0) ? $request->TowingCharges : 0.00;
        $additional_towing = ($request->additional_towing > 0) ? $request->additional_towing : 0.00;

        $InsurerLiability = ($request->InsurerLiability > 0) ? $request->InsurerLiability : 0.00;
//        $InsurerLiability = $InsurerLiability - ($ImposedClause + $CompulsoryDeductable + $less_voluntary_excess + $SalvageAmt);
        $inspection_Date = array(
            'LossEstimate' => $request->LossEstimate,
            'ImposedClause' => $ImposedClause,
            'SalvageAmt' => $SalvageAmt,
            'CompulsoryDeductable' => $CompulsoryDeductable,
            'NetLiabilityOnRepairBasis' => $request->NetLiabilityOnRepairBasis,
            'InsurerLiability' => $InsurerLiability,
            'CustomerLiability' => $CustomerLiability,
            'TowingCharges' => $TowingCharges,
            'less_voluntary_excess' => $less_voluntary_excess,
            'additional_towing' => $additional_towing,
        );
        $admin = Inspection::where('id', $request->inspection_id)->update($inspection_Date);
        return $this->sendResponse("Success", 'Upated Successfully.', 200);
    }

    public function saveUpdateEstimatesDetails(Request $request, $inspection_id=null){

        $inspection = !empty($inspection_id) ? Inspection::find($inspection_id) : null;

        if ($inspection && !is_null($inspection) && !empty($request->all())) {
            $estimates = InspectionEstimatesDetailsModel::where(['inspection_id' => $inspection_id])->first();
            if($estimates){
                $estimates->details = json_encode($request->all(), true);
                $estimates->updated_by = Auth::user()->id;
                $estimates->save();
            } else {
                InspectionEstimatesDetailsModel::create([
                    'inspection_id' => $inspection_id,
                    'details' => json_encode($request->all(), true),
                    'created_by' => Auth::user()->id,
                ]);
            }
            $estimates = InspectionEstimatesDetailsModel::where(['inspection_id' => $inspection_id])->first();
            return $this->sendResponse('success', $estimates, 201);
        } else {
            return $this->sendError('error', "invalid Job id", 400);
        }
    }

    public function getEstimatesDetails($inspection_id=null){
        $inspection = !empty($inspection_id) ? Inspection::find($inspection_id) : null;
        if ($inspection && !is_null($inspection)) {
            $details = [];
            $estimates = InspectionEstimatesDetailsModel::where(['inspection_id' => $inspection_id])->first();
            if($estimates){
                $details = !empty($estimates->details) ? json_decode($estimates->details, true) : [];
            }
            return $this->sendResponse($details, "Estimates Details Fetched Inspection", 200);
        } else {
            return $this->sendError('error', "invalid Job id", 201);
        }
    }
}
