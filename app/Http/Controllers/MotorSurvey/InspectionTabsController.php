<?php

namespace App\Http\Controllers\MotorSurvey;

// use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;


use App\Models\{
    AssismentDetailList,
    InspectionAccidentDetail,
    InspectionAttachment,
    InspectionFeebillReport,
    InspectionFiles,
    InspectionPolicyDetail,
    InspectionLinks,
    InspectionVehicleDetail,
    Inspection,
    Admin_ms,
    ReInsepection,
    FinalReportComment,
    InspectionAccecidentCause,
    Dynamic_sections_master,
    FeeSchedule,
    Store_Attachment_Master,
    Dynamic_sections,
    Feebill_report,
    PODTemplate,
    Bank_Details,
    BanksDetailsModel
};

use Illuminate\Support\Facades\Auth;
use Mpdf\Tag\Em;

class InspectionTabsController extends BaseController
{

    public function getApprovedData(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'inspection_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $type = $request->input('type');
        $with = $request->input('with');
        $inspection_id = $request->input('inspection_id');
        $excludeColumns = ['deleted_at', 'created_at', 'updated_at'];
        $result = [];
        if ($type == 'ila_data' || $with == 'ila_data') {
            $result['ila_data'] = Inspection::find($inspection_id);
        }
        if ($type == 'policy_details' || $with == 'policy_details') {
            //   $operating_office_options = DB::table('tbl_ms_client_branches')->where(['parent_admin_id' => Auth::user()->parent->id])->select('office_name')->distinct()->get();
            $operating_officer_options = DB::table('tbl_ms_inspection_policy_detail')
                ->select('operating_officer', 'client_branch_id')
                ->groupBy('operating_officer', 'client_branch_id')
                ->get();
            $HPA_options = DB::table('tbl_ms_inspection_policy_detail')->select('HPA')->whereNotNull('HPA')->where('HPA', '!=', '')->distinct()->get();
            $bank_name_options = DB::table('tbl_ms_inspection_policy_detail')->select('bank_id')->whereNotNull('bank_id')->where('bank_id', '!=', '')->distinct()->get();

            $inspectionPolicy = InspectionPolicyDetail::where(['inspection_id' => $inspection_id]);
            if ($inspectionPolicy->count() == 0) {
                $result['policy_details'] = Inspection::select('inspection_reference_no', 'claim_type', 'claim_no',
                    'policy_no', 'policy_valid_from', 'policy_valid_to', 'appointing_office_code'
                    , 'operating_office_code', 'client_branch_id', 'client_id', 'insured_name', 'insured_address', 'insured_mobile_no', 'workshop_branch_id'

                )->find($inspection_id);
            } else {
                $result['policy_details'] = $inspectionPolicy->first();
            }

            $result['policy_details']['operating_officer_options'] = $this->getdatatojson($operating_officer_options, 'operating_officer');
            //  $result['inspection_policy_details']['operating_office_options'] =  $this->getdatatojson($operating_office_options, 'office_name');
            $result['policy_details']['HPA_options'] = $this->getdatatojson($HPA_options, 'HPA');
            $result['policy_details']['bank_name_options'] = $this->getdatatojson($bank_name_options, 'bank_id');
        }
        if ($type == 'vehicle_details' || $with == 'vehicle_details') {
            $vehicle_make_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('vehicle_make')->whereNotNull('vehicle_make')->where('vehicle_make', '!=', '')->distinct()->get();
            $vehicle_variant_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('vehicle_variant')->whereNotNull('vehicle_variant')->where('vehicle_variant', '!=', '')->distinct()->get();
            $Engine_capacity_unit_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('Engine_capacity_unit')->whereNotNull('Engine_capacity_unit')->where('Engine_capacity_unit', '!=', '')->distinct()->get();
            $vehicle_color_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('vehicle_color')->whereNotNull('vehicle_color')->where('vehicle_color', '!=', '')->distinct()->get();
            $engine_capacity_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('engine_capacity')->whereNotNull('engine_capacity')->where('engine_capacity', '!=', '')->distinct()->get();
            $body_type = DB::table('tbl_ms_inspection_vehicle_detail')->select('body_type')->whereNotNull('body_type')->where('body_type', '!=', '')->distinct()->get();
            $anti_theft_fitted = DB::table('tbl_ms_inspection_vehicle_detail')->select('anti_theft_fitted')->whereNotNull('anti_theft_fitted')->where('anti_theft_fitted', '!=', '')->distinct()->get();
            $vehicle_class = DB::table('tbl_ms_inspection_vehicle_detail')->select('vehicle_class')->whereNotNull('vehicle_class')->where('vehicle_class', '!=', '')->distinct()->get();
            $pre_accident_cond = DB::table('tbl_ms_inspection_vehicle_detail')->select('pre_accident_cond')->whereNotNull('pre_accident_cond')->where('pre_accident_cond', '!=', '')->distinct()->get();
            $relation_with_insurer = DB::table('tbl_ms_inspection_vehicle_detail')->select('relation_with_insurer')->whereNotNull('relation_with_insurer')->where('relation_with_insurer', '!=', '')->distinct()->get();
            $issuing_authority = DB::table('tbl_ms_inspection_vehicle_detail')->select('issuing_authority')->whereNotNull('issuing_authority')->where('issuing_authority', '!=', '')->distinct()->get();
            $vehicle_allowed_to_drive = DB::table('tbl_ms_inspection_vehicle_detail')->select('vehicle_allowed_to_drive')->whereNotNull('vehicle_allowed_to_drive')->where('vehicle_allowed_to_drive', '!=', '')->distinct()->get();
            $permit_type_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('permit_type')->whereNotNull('permit_type')->where('permit_type', '!=', '')->distinct()->get();
            $route_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('route')->whereNotNull('route')->where('route', '!=', '')->distinct()->get();
            $Anti_theft_Type_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('Anti_theft_Type')->whereNotNull('Anti_theft_Type')->where('Anti_theft_Type', '!=', '')->distinct()->get();
            $dl = DB::table('tbl_ms_inspection_vehicle_detail')->select('type_of_dl')->whereNotNull('type_of_dl')->where('type_of_dl', '!=', '')->distinct()->get();

            $inspectionVechcleDetails = InspectionVehicleDetail::where(['inspection_id' => $inspection_id]);

            if ($inspectionVechcleDetails->count() == 0) {
                $result['vehicle_details'] = Inspection::select('date_of_registration', 'vehicle_reg_no as registration_no', 'chassis_no', 'engine_no',
                    'vehicle_make', 'vehicle_model', 'vehicle_type', 'odometer_reading')
                    ->find($inspection_id);
            } else {
                $result['vehicle_details'] = $inspectionVechcleDetails->first();
            }

            $result['vehicle_details']['vehicle_make_option'] = $this->getdatatojson($vehicle_make_options, 'vehicle_make');
            $result['vehicle_details']['body_type_options'] = $this->getdatatojson($body_type, 'body_type');
            $result['vehicle_details']['anti_theft_fitted_options'] = $this->getdatatojson($anti_theft_fitted, 'anti_theft_fitted');
            $result['vehicle_details']['anti_theft_type_options'] = $this->getdatatojson($Anti_theft_Type_options, 'Anti_theft_Type');
            $result['vehicle_details']['vehicle_class_options'] = $this->getdatatojson($vehicle_class, 'vehicle_class');
            $result['vehicle_details']['pre_accident_cond_options'] = $this->getdatatojson($pre_accident_cond, 'pre_accident_cond');
            $result['vehicle_details']['relation_with_insurer_options'] = $this->getdatatojson($relation_with_insurer, 'relation_with_insurer');
            $result['vehicle_details']['issuing_authority_options'] = $this->getdatatojson($issuing_authority, 'issuing_authority');
            $result['vehicle_details']['vehicle_allowed_to_drive_options'] = $this->getdatatojson($vehicle_allowed_to_drive, 'vehicle_allowed_to_drive');
            $result['vehicle_details']['engine_capacity_options'] = $this->getdatatojson($engine_capacity_options, 'engine_capacity');
            $result['vehicle_details']['vehicle_color_options'] = $this->getdatatojson($vehicle_color_options, 'vehicle_color');
            $result['vehicle_details']['Engine_capacity_unit_options'] = $this->getdatatojson($Engine_capacity_unit_options, 'Engine_capacity_unit');
            $result['vehicle_details']['vehicle_variant_options'] = $this->getdatatojson($vehicle_variant_options, 'vehicle_variant');
            $result['vehicle_details']['permit_type_options'] = $this->getdatatojson($permit_type_options, 'permit_type');
            // $result['vehicle_details']['accident_place_options'] = $this->getdatatojson($accident_place_options, 'accident_place');
            $result['vehicle_details']['route_options'] = $this->getdatatojson($route_options, 'route');
            $result['vehicle_details']['type_of_dl_options'] = $this->getdatatojson($dl, 'type_of_dl');
            $result["vehicle_details"]["date_of_purchase"]=$result["vehicle_details"]["date_purchase"]??Inspection::select("date_purchase")->find($inspection_id)->date_purchase;

        }

        if ($type == 'accident_details' || $with == 'accident_details') {

            $rc = DB::table('tbl_ms_inspection_accident_detail')->select('RC')->distinct()->whereNotNull('RC')->where('RC', '!=', '')->get();
            $dl = DB::table('tbl_ms_inspection_accident_detail')->select('DL')->whereNotNull('DL')->where('DL', '!=', '')->distinct()->get();
            $chassis_no = DB::table('tbl_ms_inspection_accident_detail')->select('chassis_no')->whereNotNull('chassis_no')->where('chassis_no', '!=', '')->distinct()->get();
            $engine_no = DB::table('tbl_ms_inspection_accident_detail')->select('engine_no')->whereNotNull('engine_no')->where('engine_no', '!=', '')->distinct()->get();
            $load_chalan_options = DB::table('tbl_ms_inspection_accident_detail')->select('load_chalan')->whereNotNull('load_chalan')->where('load_chalan', '!=', '')->distinct()->get();
            $permit_options = DB::table('tbl_ms_inspection_accident_detail')->select('permit')->whereNotNull('permit')->where('permit', '!=', '')->distinct()->get();
            $fitness_options = DB::table('tbl_ms_inspection_accident_detail')->select('fitness')->whereNotNull('fitness')->where('fitness', '!=', '')->distinct()->get();
            $inspectionAccDetails = InspectionAccidentDetail::where(['inspection_id' => $inspection_id]);
            if ($inspectionAccDetails->count() == 0) {
                $result['accident_details'] = Inspection::select('place_accident', 'date_time_accident', 'date_of_appointment',
                    'place_survey', 'workshop_id as workshop_name', 'workshop_branch_id', 'Survey_Date_time')
                    ->find($inspection_id);
            } else {
                $result['accident_details'] = $inspectionAccDetails->first();
            }
            $result['accident_details']['RC_options'] = $this->getdatatojson($rc, 'RC');
            $result['accident_details']['chassis_no_options'] = $this->getdatatojson($chassis_no, 'chassis_no');
            $result['accident_details']['engine_no_options'] = $this->getdatatojson($engine_no, 'engine_no');
            $result['accident_details']['fitness_options'] = $this->getdatatojson($fitness_options, 'fitness');
            $result['accident_details']['load_chalan_options'] = $this->getdatatojson($load_chalan_options, 'load_chalan');
            $result['accident_details']['permit_options'] = $this->getdatatojson($permit_options, 'permit');
            $result['accident_details']['DL_options'] = $this->getdatatojson($dl, 'DL');
            $result["accident_details"]["accident_date"] = date('Y-m-d', strtotime($result["accident_details"]["date_time_accident"]));
            $result["accident_details"]["accident_time"] = date('H:i:s', strtotime($result["accident_details"]["date_time_accident"]));
            $result["accident_details"]["survey_date"] = date('Y-m-d', strtotime($result["accident_details"]["Survey_Date_time"]));
            $result["accident_details"]["survey_time"] = date('H:i:s', strtotime($result["accident_details"]["Survey_Date_time"]));

        }
        if ($type == 'cause_detail' || $with == 'cause_detail') {
            $inspectionAccCauseDetails = InspectionAccecidentCause::where(['inspection_id' => $inspection_id]);
            if ($inspectionAccCauseDetails->count() == 0) {
                $result['cause_detail'] = Inspection::select('accident_brief_description')->find($inspection_id);
            } else {
                $result['cause_detail'] = $inspectionAccCauseDetails->first();
            }


        }
        if ($type == 'final_report_comment' || $with == 'final_report_comment') {

            $FinalReportComment = FinalReportComment::where(['inspection_id' => $inspection_id]);
            if ($FinalReportComment->count() == 0) {
                $result['final_report_comment'] = Inspection::select('*')->find($inspection_id);
            } else {
                $result['final_report_comment'] = $FinalReportComment->first();
            }


            //  $result['final_report_comment'] = Inspection::with('get_final_report_detail')->find($inspection_id);
        }

        if ($type == 'attachments' || $with == 'attachments') {

            $inspectionAttachments = InspectionAttachment::where(['inspection_id' => $inspection_id]);
            if ($inspectionAttachments->count() == 0) {
                $result['attachments'] = Inspection::select('*')->find($inspection_id);
            } else {
                $result['attachments'] = $inspectionAttachments->first();
            }
            $result['attachments']['custom_attachments_global'] = Store_Attachment_Master::where(['admin_id' => Auth::user()->id])->get();

        }


        if ($type == 'feebill' || $with == 'feebill') {

            $insection = Inspection::find($inspection_id);
            $result['fee_schedule'] = FeeSchedule::where(['client_id' => $insection->client_id])->first();
            if (empty($result['fee_schedule'])) {
                $columnNames = FeeSchedule::getTableColumns(['created_at', 'updated_at']); // Implement this method to get column names
                $result['fee_schedule'] = array_fill_keys($columnNames, '');
            }
            $bankDetails = BanksDetailsModel::select('id', 'bank_code', 'bank_name', 'branch_address', 'account_number', 'account_type', 'ifsc', 'micr')->whereNull('deleted_at')->where(['admin_id' => $insection->admin_id, 'admin_branch_id' => $insection->admin_branch_id])->get();
            $bankDetailsArray = ($bankDetails->count() > 0) ? $bankDetails->toArray() : [];
            $feebillArr = InspectionFeebillReport::where(['inspection_id' => $inspection_id])->get()->toArray();
            // $result['feebill'];
            $mappedArray = [];
            foreach ($feebillArr as $key => $feebill) {
                unset($feebill['bank_details']);
                foreach ($feebill as $key => $feebi) {
                    $jsonData = json_decode($feebi);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $mappedArray[$key] = $jsonData;
                    } else {
                        $mappedArray[$key] = $feebi;
                    }
                }
                $mappedArray['bank_details'] = $bankDetailsArray;
            }
            $result['feebill'] = $mappedArray;

            if (empty($result['feebill'])) {
                $columnNames = InspectionFeebillReport::getTableColumns(['created_at', 'updated_at']); // Implement this method to get column names
                $feebill = array_fill_keys($columnNames, '');
                $feebill['bank_details'] = !empty($bankDetailsArray) ? $bankDetailsArray : "";
                $result['feebill'] = $feebill;
            }
        }
        if ($type == 'reinspection' || $with == 'reinspection') {
            $insection = Inspection::find($inspection_id);

            $result['reinspection'] = ReInsepection::where(['inspection_id' => $inspection_id, 'admin_branch_id' => $insection->admin_branch_id])->first();
            if (empty($result['reinspection'])) {
                $columnNames = ReInsepection::getTableColumns(['created_at', 'updated_at']); // Implement this method to get column names
                $result['reinspection'] = array_fill_keys($columnNames, '');
                // Set allowed_parts to an empty array
                $result['reinspection']['allowed_parts'] = [];
            } else {
                // Assuming 'allowed_parts' is a field in the ReInsepection model
                $allowedPartsJson = $result['reinspection']->allowed_parts;

                // Check if allowed_parts is empty
                $result['reinspection']->allowed_parts = !empty($allowedPartsJson) ? json_decode($allowedPartsJson, true) : [];
            }
        }
        if ($type == 'fetch_allow_parts') {
            $result[$type] = AssismentDetailList::select('description', 'remarks')->where(['inspection_id' => $inspection_id])->where('ass_amt', '!=', 0)->get();
        }

        if ($type == 'report_dynamic_section') {

            $insection = Inspection::find($inspection_id);
            $DynamicSections = Dynamic_sections::where('inspection_id', $inspection_id)->get();

            $data = [];
            foreach ($DynamicSections as $dynm) {

                if ($dynm->section_type == 'master_section') {

                    $data[] = [
                        'SrNo' => $dynm->SrNo,
                        'id' => $dynm->id,
                        'master_section' => $dynm->Heading,
                        'add_details' => $dynm->Details,
                        'add_Report' => ($dynm->add_Report == 0) ? false : true
                    ];
                }
                if ($dynm->section_type == 'report_section') {

                    $data[] = [
                        'SrNo' => $dynm->SrNo,
                        'report_section' => $dynm->Heading,
                        'add_details' => $dynm->Details,
                        'id' => $dynm->id
                    ];
                }
            }

            // return  json_encode($data);

            $result['report_dynamic_section'] = Dynamic_sections_master::where(['admin_id' => $insection->admin_id])->get();
            $result['report_section'] = $data;

        }


        return $this->sendResponse($result, 'Inspection Data Fetched.');
    }

    public function StoreorUpdate(Request $request)
    {
        $tabData = stripslashes($request->input('data'));
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'inspection_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $type = $request->input('type');
        $inspection_id = $request->input('inspection_id');
        $mappedArray = json_decode($tabData, true);
        $allowPartsArray = [];

        if ($type == 'reinspection') {
            $allowPartsArray = json_encode($mappedArray['allowed_parts']);
        }

        if ($type == 'attachments') {
            $custom_attachments = json_encode($mappedArray['custom_attachments']);
            $mappedArray['custom_attachments'] = $custom_attachments;
        }

        $inspectionDetails = Inspection::find($inspection_id);

        $response = [];
        if (!empty($inspection_id) && $type == 'ila_data') { // ILA Data
            $response[$type] = Inspection::updateOrCreate(['id' => $inspection_id], $mappedArray);
        } else if (!empty($inspection_id) && $type == 'policy_details') {
            $response[$type] = InspectionPolicyDetail::updateOrCreate(['inspection_id' => $inspection_id], $mappedArray);
        } else if (!empty($inspection_id) && $type == 'vehicle_details') {
            $response[$type] = InspectionVehicleDetail::updateOrCreate(['inspection_id' => $inspection_id], $mappedArray);
        } else if (!empty($inspection_id) && $type == 'accident_details') {

            $date_time_accident=isset($mappedArray["accident_date"]) ? date('Y-m-d', strtotime($mappedArray["accident_date"])) : '';
            if(!empty($mappedArray["accident_time"])) {
                $date_time_accident .= " ".date('H:i:s', strtotime($mappedArray["accident_time"]));
            }
            $Survey_Date_time=isset($mappedArray["survey_date"]) ? date('Y-m-d', strtotime($mappedArray["survey_date"])) : '';
            if(!empty($mappedArray["survey_time"])) {
                $Survey_Date_time .= " ".date('H:i:s', strtotime($mappedArray["survey_time"]));
            }
            $spot_survey_date=isset($mappedArray["date_of_spot_survey"]) ? date('Y-m-d', strtotime($mappedArray["date_of_spot_survey"])) : '';
            $exclude=["date_accident","time_accident","accident_date","accident_time","survey_date","survey_time","date_of_spot_survey"];
            /***exclude some keys for creating or updating records */
            $mappedArray=Collect($mappedArray)->except($exclude)->toArray();
            $mappedArray['date_time_accident'] = $date_time_accident;
            $mappedArray['Survey_Date_time'] = $Survey_Date_time;
            $mappedArray["spot_survey_date"] = $spot_survey_date;

            /**for remove empty */
            $mappedArray = array_filter($mappedArray, function($v) {
                return !empty($v);
            });
            $response[$type] = InspectionAccidentDetail::updateOrCreate(['inspection_id' => $inspection_id], $mappedArray);
        } else if (!empty($inspection_id) && $type == 'cause_detail') {
            $response[$type] = InspectionAccecidentCause::updateOrCreate(['inspection_id' => $inspection_id], $mappedArray);
        } else if (!empty($inspection_id) && $type == 'final_report_comment') {
            $response[$type] = FinalReportComment::updateOrCreate(['inspection_id' => $inspection_id], $mappedArray);
        } else if (!empty($inspection_id) && $type == 'attachments') {
            foreach ($mappedArray as $key => $value) {
                if (empty($value)) {
                    $mappedArray[$key] = false;
                }
            }
            $response[$type] = InspectionAttachment::updateOrCreate(['inspection_id' => $inspection_id], $mappedArray);
        } else if (!empty($inspection_id) && $type == 'feebill') {
            unset($mappedArray['bank_details']);
            foreach ($mappedArray as $key => $feebill) {
                if (is_array($feebill) && $key != 'bank_details') {
                    $mappedArray[$key] = json_encode($feebill);
                }
            }
            if(empty($mappedArray['bank_id']) || !is_numeric($mappedArray['bank_id'])) {
                $mappedArray['bank_id'] = 0;
            }
            if ($mappedArray["generate_bill_no"] == true) {
                $jobms = Inspection::find($inspection_id);

                $adminId = $jobms->admin_id;
                $branchAdmin = $jobms->adminMs;
                $existingUnGeneratedBill = InspectionFeebillReport::where(['inspection_id' => $inspection_id, 'bill_no' => "", 'is_generated' => 0])->first();

                $maxIntBillNo = InspectionFeebillReport::join('tbl_ms_inspection_details', 'tbl_ms_inspection_details.id', '=', 'tbl_ms_inspection_feebill_report.inspection_id')
                    ->join('tbl_ms_admin', 'tbl_ms_admin.id', '=', 'tbl_ms_inspection_details.admin_id')
                    ->where(['tbl_ms_inspection_feebill_report.is_generated' => 1, 'tbl_ms_inspection_details.admin_id' => $adminId])
                    ->max("tbl_ms_inspection_feebill_report.int_bill_no");

                $dates = date("Y");
                $tdate = date("y");
                $datee = $tdate + 1;
                if ($maxIntBillNo) {

                    $bill_no_start_from = $maxIntBillNo + 1;
                    $billnumber = $branchAdmin->reference_no_prefix . '/' . $dates . '-' . $datee . '/' . $bill_no_start_from;

                    if (InspectionFeebillReport::where(['inspection_id' => $inspection_id, 'generate_bill_no' => 1])->exists()) {
                        $mappedArray["bill_no"] = (!empty($mappedArray["bill_no"])) ? $mappedArray["bill_no"] : $billnumber;
                        $mappedArray["int_bill_no"] = InspectionFeebillReport::where(['inspection_id' => $inspection_id, 'generate_bill_no' => 1])->first()->int_bill_no;
                        $mappedArray["is_generated"] = 1;
                    } else {
                        $mappedArray["bill_no"] = (!empty($mappedArray["bill_no"])) ? $mappedArray["bill_no"] : $billnumber;
                        $mappedArray["int_bill_no"] = $maxIntBillNo + 1;
                        $mappedArray["is_generated"] = 1;
                    }

                } else {
                    $mappedArray["bill_no"] = $branchAdmin->reference_no_prefix . '/' . $dates . '-' . $datee . '/' . $branchAdmin->report_no_start_from;
                    $mappedArray["int_bill_no"] = $branchAdmin->report_no_start_from;
                    $mappedArray["is_generated"] = 1;
                }

            }

            $response[$type] = InspectionFeebillReport::updateOrCreate(['inspection_id' => $inspection_id], $mappedArray);
        } else if (!empty($inspection_id) && $type == 'reinspection') {
            $inspectionData = Inspection::find($inspection_id);
            $insertArr = [
                'submission_date' => $mappedArray['submission_date'] ?? null,
                'reinspection_date' => $mappedArray['reinspection_date'] ?? null,
                'place_reinspection' => $mappedArray['place_reinspection'] ?? null,
                'observation' => $mappedArray['observation'] ?? null,
                'remarks' => $mappedArray['remarks'] ?? null,
                'allowed_parts' => $allowPartsArray,
                'list_allowed_parts' => $mappedArray['list_allowed_parts'] ?? null,
                'list_allow_status' => $mappedArray['list_allow_status'] ?? null,
                'remarks_status' => $mappedArray['remarks_status'] ?? null,
                'admin_branch_id' => $inspectionData->admin_branch_id
            ];
            $response[$type] = ReInsepection::updateOrCreate(['inspection_id' => $inspection_id], $insertArr);

        } else if (!empty($inspection_id) && $type == 'report_dynamic_section') {
            Dynamic_sections::where(['inspection_id' => $inspection_id])->delete();
            foreach ($mappedArray as $mapAr) {
                foreach ($mapAr as $ke => $madpAr) {

                    if ($ke == 'report_section') {
                        $datas = [
                            'inspection_id' => $inspection_id,
                            'SrNo' => (isset($mapAr['SrNo'])) ? $mapAr['SrNo'] : null,
                            'Heading' => (isset($mapAr['report_section'])) ? $mapAr['report_section'] : null,
                            'Details' => (isset($mapAr['add_details'])) ? $mapAr['add_details'] : null,
                            'created_by' => Auth::user()->id,
                            'section_type' => 'report_section',
                        ];
                        Dynamic_sections::create($datas);
                    }

                }
            }

        } else if (!empty($inspection_id) && $type == 'fetch_allow_parts') {
            $inspectionData = Inspection::find($inspection_id);
            $insertArr = [
                'submission_date' => $request->input('submission_date'),
                'reinspection_date' => $request->input('reinspection_date'),
                'place_reinspection' => $request->input('place_reinspection'),
                'observation' => $request->input('observation'),
                'remarks' => $request->input('remarks', null),
                'allowed_parts' => $allowPartsArray,
                'replacement' => $request->input('replacement'),
                'salvage' => $request->input('salvage'),
                'list_allowed_parts' => $request->input('list_allowed_parts'),
                'list_allow_status' => $request->input('list_allow_status'),
                'remarks_status' => $request->input('remarks_status'),
                'admin_branch_id' => $inspectionData->admin_branch_id
            ];
            $response[$type] = ReInsepection::updateOrCreate(['inspection_id' => $inspection_id], $insertArr);
        }
        return $this->sendResponse($response, 'Data Successfully Stored.');
    }

    public function ConvertURltoBase(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'url' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        $url = $request->input('url');

        $imageContent = Http::get($url)->body();
        $base64Image = base64_encode($imageContent);
        return response()->json([
            'status' => 'success',
            'message' => 'Url encoded success',
            'data' => $base64Image,
        ]);
    }

    public function getvehicledata(Request $request)
    {

        $vehicle_make = $request->vehicle_make;
        $vehicle_variant = $request->vehicle_variant;
        $vehicle_model = $request->vehicle_model;

        $client_branch_options = DB::table('tbl_ms_inspection_vehicle_detail')->where('vehicle_make', $vehicle_make)->where('vehicle_variant', $vehicle_variant)->where('vehicle_model', $vehicle_model)->latest()->first();

        if (!$client_branch_options) {
            return $this->sendError('Data not found', [], 404);
        } else {

            $data = [
                'vehicle_color' => $client_branch_options->vehicle_color,
                'engine_capacity' => $client_branch_options->engine_capacity,
                'body_type' => $client_branch_options->body_type,
                'seating_capacity' => $client_branch_options->seating_capacity,
                'fuel' => $client_branch_options->fuel,
                'fuel_kit' => $client_branch_options->fuel_kit,
                'vehicle_class' => $client_branch_options->vehicle_class,
                'Engine_Capacity_Unit' => $client_branch_options->Engine_Capacity_Unit,
            ];
            return $this->sendResponse($data, 'Data fetched successfully', 200);
        }
    }

    public function getdistinctvariant(Request $request)
    {

        $make = $request->make;

        $vehicle_variant_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('vehicle_variant')->where('vehicle_make', 'like', "%$make%")->whereNotNull('vehicle_variant')->where('vehicle_variant', '!=', '')->distinct()->get();


        $engine_capacity_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('engine_capacity')->where('vehicle_make', 'like', "%$make%")->whereNotNull('engine_capacity')->where('engine_capacity', '!=', '')->distinct()->get();


        $body_type_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('body_type')->where('vehicle_make', 'like', "%$make%")->whereNotNull('body_type')->where('body_type', '!=', '')->distinct()->get();


        $seating_capacity_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('seating_capacity')->where('vehicle_make', 'like', "%$make%")->whereNotNull('seating_capacity')->where('seating_capacity', '!=', '')->distinct()->get();


        $anti_theft_fitted_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('anti_theft_fitted')->where('vehicle_make', 'like', "%$make%")->whereNotNull('anti_theft_fitted')->where('anti_theft_fitted', '!=', '')->distinct()->get();


        $fuel_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('fuel')->where('vehicle_make', 'like', "%$make%")->whereNotNull('fuel')->where('fuel', '!=', '')->distinct()->get();


        $fuel_kit_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('fuel_kit')->where('vehicle_make', 'like', "%$make%")->whereNotNull('fuel_kit')->where('fuel_kit', '!=', '')->distinct()->get();

        $Anti_theft_Type_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('Anti_theft_Type')->where('vehicle_make', 'like', "%$make%")->whereNotNull('Anti_theft_Type')->where('Anti_theft_Type', '!=', '')->distinct()->get();


        $vehicle_class_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('vehicle_class')->where('vehicle_make', 'like', "%$make%")->whereNotNull('vehicle_class')->where('vehicle_class', '!=', '')->distinct()->get();


        if ($vehicle_variant_options->count() != 0) {
            $vehicle_variant_options = $this->getdatatojson($vehicle_variant_options, 'vehicle_variant');
        } else {
            $vehicle_variant_options = "";
        }


        if ($engine_capacity_options->count() != 0) {
            $engine_capacity_options = $this->getdatatojson($engine_capacity_options, 'engine_capacity');
        } else {
            $engine_capacity_options = "";
        }


        if ($body_type_options->count() != 0) {
            $body_type_options = $this->getdatatojson($body_type_options, 'body_type');
        } else {
            $body_type_options = "";
        }


        if ($seating_capacity_options->count() != 0) {
            $seating_capacity_options = $this->getdatatojson($seating_capacity_options, 'seating_capacity');
        } else {
            $seating_capacity_options = "";
        }


        if ($anti_theft_fitted_options->count() != 0) {
            $anti_theft_fitted_options = $this->getdatatojson($anti_theft_fitted_options, 'anti_theft_fitted');
        } else {
            $anti_theft_fitted_options = "";
        }


        if ($fuel_options->count() != 0) {
            $fuel_options = $this->getdatatojson($fuel_options, 'fuel');
        } else {
            $fuel_options = "";
        }
        if ($fuel_kit_options->count() != 0) {
            $fuel_kit_options = $this->getdatatojson($fuel_kit_options, 'fuel_kit');
        } else {
            $fuel_kit_options = "";
        }
        if ($Anti_theft_Type_options->count() != 0) {
            $Anti_theft_Type_options = $this->getdatatojson($Anti_theft_Type_options, 'Anti_theft_Type');
        } else {
            $Anti_theft_Type_options = "";
        }
        if ($vehicle_class_options->count() != 0) {
            $vehicle_class_options = $this->getdatatojson($vehicle_class_options, 'vehicle_class');
        } else {
            $vehicle_class_options = "";
        }


        $data['values'] =
            [
                'vehicle_variant_options' => $vehicle_variant_options,
                'engine_capacity_options' => $engine_capacity_options,
                'body_type_options' => $body_type_options,
                'seating_capacity_options' => $seating_capacity_options,
                'anti_theft_fitted_options' => $anti_theft_fitted_options,
                'fuel_options' => $fuel_options,
                'fuel_kit_options' => $fuel_kit_options,
                'Anti_theft_Type_options' => $Anti_theft_Type_options,
                'vehicle_class_options' => $vehicle_class_options,
            ];

        return $this->sendResponse($data, 'fetched  Sussessfully', 200);
    }


    public function jsonObject($key)
    {

        $response = [
            'ila_data' => json_decode('{"claim_type":"1","vehicle_reg_no":"RJ15F4545","insured_name":"Bal","place_survey":"qwe","workshop_id":1,"workshop_branch_id":1,"contact_no":null,"date_of_appointment":null,"admin_branch_id":1,"inspection_id":"1","claim_no":null,"policy_no":null,"policy_valid_from":null,"policy_valid_to":null,"client_name":1,"insured_address":null,"insured_mobile_no":null,"vehicle_type":null,"date_of_registration":null,"Survey_Date_time":null,"chassis_no":null,"engine_no":null,"vehicle_make":null,"vehicle_model":null,"odometer_reading":"0","date_time_accident":null,"place_accident":null,"workshop_name":1,"contact_person":null,"update_by":"2","client_branch_id":1,"ILA_discussed_with":"<p>ASas</p>","accident_brief_description":null,"accompanied_insurer_officer_details":null,"damage_corroborates_with_cause_of_loss":null,"major_physical_damages":null,"preexisting_old_damages":null,"preferred_mode_of_assessment":null,"suspected_Internal_damages":null,"spot_Survey_details":null,"surveyor_APP_Token_Number":null,"Vehicular_document_observation":null,"Reference_No":"aw/2024-25/1","Chassis_No_PV":null,"Engine_No_PV":null}', true),
            'policy_details' => json_decode('{"reference_no":"VIISLA/2024-25/1000","claim_type":"1","reportGeneratedOn":"2024-01-04","claim_no":"Claim No.","policy_no":"Policy No. Para","policy_valid_from":"2024-01-01","policy_valid_to":"2026-12-31","sum_insured":0,"policy_type":"Not Provided","status_of_64vb":"","status_of_pre_insp":"","status_of_NCB":"","payment_mode":"","settlement_type":"","client_id":3,"client_branch_id":3,"operating_officer":"Pareah","operating_office":3,"appointing_office_code":3,"insured_name":"Jasmin Singh","insured_address":"Insured Address3","insured_mobile_no":"9999999999","registured_owner":"","bank_id":"","bank_address":"","account_no":"","ifsc_code":"","HPA":"qe"}', true),
            'vehicle_details' => json_decode('{"date_of_purchase":"","date_of_registration":"","Transfer_SrNo":"","date_of_transfer":"","chassis_no":"Chassis no","engine_no":"Engine No","vehicle_make":"Vehicle Make","vehicle_variant":"","vehicle_model":"Vehicle Model","vehicle_color":"","engine_capacity":"","body_type":"","odometer_reading":"122222","seating_capacity":"","anti_theft_fitted":"","fuel":"","fuel_kit":"","vehicle_type":"1","vehicle_class":"","pre_accident_cond":"","tax_paid_from":"","tax_paid_to":"","fitness_number":"","fitness_valid_to":"","permit_number":"","permit_valid_from":"","permit_valid_to":"","permit_type":"","accident_place":"","route":"","authorization_no":"","authorization_valid_from":"","authorization_valid_to":"","carrying_capacity":"","registered_laden_weight":"","unladen_weight":"","cause_of_accident":"","driver_name":"","driver_dob":"","address":"","DL_renewal_no":"","DL_no":"","issuing_authority":"","issuing_date":"","DL_valid_upto":"","nt_dl_valid_from":"","nt_dt_valid_to":"","type_of_DL":"","vehicle_allowed_to_drive":"","endorsement_detail":"","badge_no":"","additional_details":"","valid_to":"","issued_on":"","temp_registration_no":"","relation_with_insurer":"","challan_no":"","PUC_certificate_no":"","PUC_valid_from":"","PUC_valid_to":"","RC_valid_to":"","fitness_valid_from":"","Anti_theft_Type":"","engine_capacity_unit":""}', true),
            'accident_details' => json_decode('{"place_of_accident":"Place Of Accident","date_time_of_accident":"2024-01-11 17:18","date_of_appointment":null,"survey_place":"Samara Hyundai","workshop_name":"3","date_time_survey":"2024-01-04","date_of_under_repair_visit":"","estimate_no":"","date_of_estimate":"","insured_rep_attending_survey":"","vehicle_left_unattended":"","vehicle_left_unattended_desc":"","accident_reported_to_police":"","panchnama":"","third_party_injury":"","injury_to_driver":"","previous_claim_details":"","spot_survey_by":"","spot_survey_date":"","passenger_detail":"","RC":"","DL":"","chassis_no":"","engine_no":"","fitness":"","load_chalan":"","permit":"","panchnama_description":"","fir_description":"","accident_additional_information":"","survey_additional_information":"","Date_of_Under_repair_Visits1":"","Date_of_Under_repair_Visits2":"","Date_of_Under_repair_Text":""}', true),
            'cause_detail' => json_decode('{"cause_nature_accident":"","action_of_survey":"","particular_of_damage":"","observation":""}', true),
            'final_report_comment' => json_decode('{"comment":""}', true),
            'attachments' => json_decode('{"affidavit":"","bill_invoice":"","copy_of_fire":"","copy_of_permit":"","copy_traffic":"","estimate_copy":"","report_in_duplicate":"","copy_of_fitness":"","copy_of_receipt":"","claim_form":"","copy_of_load_challan":"","copy_of_RC":"","insured_discharge_voucher":"","intimation_letter":"","survey_fee_bill":"","letter_by_insured":"","copy_of_DL":"","policy_note":"","generate_photosheet":"","medical_papers":"","dealer_inv":"","police_report":"","photographs":"","satisfaction_voucher":"","supporting_bills":"","towing_charge_slip":""}', true),
            'feebill' => json_decode('{"bill_date":"2024-01-04","bill_no":"","issued_to":"","payment_by":"","cgst_percentage":0,"sgst_percentage":0,"igst_percentage":0,"gst_amount":0,"amount_before_tax":0,"amount_after_tax":0,"cash_receipted":"","bank_code":"","comment":"","survey_fee_total":0,"conveyance_fee_total":0,"photographs_amount_total":0,"miscellaneous_amount_total":0,"surveyFee":[{"surveyType":"","feeAmount":""}],"conveyanceFee":[{"particular":"","noOfVisit":"","conveyance_unit":"","perVisitRate":"","totalAmount":""}],"vehiclePhotographs":[{"particular":"","noOfPhotos":"","CD":"","rate":"","totalAmount":""}],"miscellaneous":[{"description":"","amount":""}],"bank_details":[{"bank_code":"","bank_name":"","branch_address":"","account_number":"","account_type":"","ifsc":"","micr":""}]}', true),
            'report_dynamic_section' => json_decode('{}', true),
            'reinspection' => json_decode('{}', true),
        ];

        return $response[$key];
    }

    public function getdatatojson($data, $key)
    {


        if ($data != '') {
            $datas = [];

            foreach ($data as $policy) {
                array_push($datas, $policy->$key);
            }
            return $data = json_encode($datas);
        } else {
            return $data = '';
        }
    }

    public function surveyfee(Request $request)
    {

        $id = $request->id;
        $issued_to = $request->issued_to;
        $surveyFee = Feebill_report::where('inspection_id', $id)->first();
        if (isset($surveyFee)) {
            if (empty($surveyFee->surveyFee)) {
                $data_tab = DB::table('tbl_ms_inspection_policy_detail')->where('inspection_id', $id)->first();
                if (isset($data_tab)) {
                    $appointing_branch_id = $data_tab->appointing_branch_id;
                    $insurer_id = $data_tab->insurer_id;
                    $insurer_branch_id = $data_tab->insurer_branch_id;
                }
                if ($issued_to == 1) {
                    $data_tab1 = DB::table('tbl_ms_client_branches')->where('office_code', $appointing_branch_id)->first();
                    if (isset($data_tab1)) {
                        $within_state = $data_tab1->within_state;
                        echo $within_state;

                    }

                } elseif ($issued_to == 2 || $issued_to == 5) {
                    $data_tab2 = DB::table('tbl_ms_feebill')->where('client_id', $insurer_id)->first();
                    if (isset($data_tab2)) {
                        $cgst = $data_tab2->cgst;
                        $sgst = $data_tab2->sgst;
                        $igst = $data_tab2->igst;
                        // echo $cgst.'---'. $sgst. '---'.$igst;
                    }
                } elseif ($issued_to == 3) {
                    $data_tab2 = DB::table('tbl_ms_feebill')->where('client_id', $insurer_branch_id)->first();
                    if (isset($data_tab2)) {
                        $cgst = $data_tab2->cgst;
                        $sgst = $data_tab2->sgst;
                        $igst = $data_tab2->igst;
                        echo $cgst . '---' . $sgst . '---' . $igst;
                    }
                }

            } else {
                die('no data');
            }
        }
    }

    public function podTemplates(Request $request)
    {
        $validatedData = $request->validate([
            'admin_branch_id' => 'required|numeric',
            'particular_of_damage' => 'required|string',
        ]);

        try {
            $podTemplate = PODTemplate::firstOrNew([
                'admin_branch_id' => $validatedData['admin_branch_id']
            ]);

            $podTemplate->particular_of_damage = $validatedData['particular_of_damage'];
            $podTemplate->save();

            return $this->sendResponse($podTemplate, 'POD template ' . ($podTemplate->wasRecentlyCreated ? 'created' : 'updated') . ' successfully', 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function podTemplateList($adminBranchId)
    {

        $podList = PODTemplate::where('admin_branch_id', $adminBranchId)->first();

        return $this->sendResponse($podList, "POD List Fetched", 200);
    }

    public function replaceTagsData($insectionId)
    {

        $replaceTagsData = InspectionPolicyDetail::join('tbl_ms_inspection_vehicle_detail', 'tbl_ms_inspection_vehicle_detail.inspection_id', '=', 'tbl_ms_inspection_policy_detail.inspection_id')
            ->leftJoin('tbl_ms_client_branches', 'tbl_ms_inspection_policy_detail.appointing_office_code', '=', 'tbl_ms_client_branches.id')
            ->join('tbl_ms_inspection_accident_detail', 'tbl_ms_inspection_accident_detail.inspection_id', '=', 'tbl_ms_inspection_policy_detail.inspection_id')
            ->leftJoin('tbl_ms_workshop_branch', 'tbl_ms_inspection_accident_detail.workshop_branch_id', '=', 'tbl_ms_workshop_branch.id')
            ->select('tbl_ms_inspection_policy_detail.inspection_id', 'tbl_ms_client_branches.office_name', 'tbl_ms_client_branches.office_address', 'tbl_ms_inspection_policy_detail.insured_name', 'tbl_ms_inspection_vehicle_detail.address', 'tbl_ms_inspection_vehicle_detail.accident_place', 'tbl_ms_workshop_branch.workshop_branch_name', 'tbl_ms_workshop_branch.address as workshop_address')
            ->where('tbl_ms_inspection_policy_detail.inspection_id', $insectionId)
            ->first();

        return $this->sendResponse($replaceTagsData, "Replace Tags Fetched", 200);
    }
}
