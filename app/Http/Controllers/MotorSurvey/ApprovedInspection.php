<?php


namespace App\Http\Controllers\MotorSurvey;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jobms;
use App\Models\AssismentDetail;
use App\Models\AssismentDetailList;
use App\Models\TaxSetting;
use App\Models\Dynamic_sections;
use App\Models\Feebill_report;
use App\Models\Bank_Details;
use App\Models\BanksDetailsModel;
use App\Models\Attachement;
use App\Models\Store_Attachment;
use App\Models\Store_Attachment_Master;
use App\Models\Dynamic_sections_master;

use App\Models\Inspection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApprovedInspection extends BaseController
{
    public function get(Request $req, $id)
    {


        $dataadmin = Inspection::where('id', $id)->first();
        $mainadmin = $dataadmin->admin_id;
        $mainadminbranch = $dataadmin->admin_branch_id;
        $clid = $dataadmin->client_id;

        $ids = Auth::user()->id;

        $main_admin_id = getmainAdminIdIfAdminLoggedIn($ids);


        $where = ['admin_id' => $main_admin_id];

        // if(getBranchidbyAdminid($ids)!=0 && getBranchidbyAdminid($ids)!=''){
        //     $admin_branch_id=getBranchidbyAdminid($ids);
        //     $where1=['parent_admin_id'=>$mainadmin,'id'=>$admin_branch_id];
        // }
        // else{
        $where1 = ['parent_admin_id' => $mainadmin];
        // }


        $client_branch_options = DB::table('tbl_ms_client_branches')->where('client_id', $clid)->orwhere($where1)->get();

        if ($client_branch_options->count() != 0) {
            $client_branch_options = $this->getdatatojson($client_branch_options, 'office_name');
        } else {
            $client_branch_options = "";
        }

        $datas = Inspection::where('id', $id)->first();

        $datae['reference_no'] = $dataadmin['inspection_reference_no'];
        $datae['report_type'] = $dataadmin['claim_type'];
        $datae['reportGeneratedOn'] = date('Y-m-d');
        $datae['policy_number'] = $datas['policy_no'];
        $datae['policy_valid_from'] = $datas['policy_valid_from'];
        $datae['policy_valid_to'] = $datas['policy_valid_to'];
        $datae['claim_no'] = $datas['claim_no'];
        $datae['sum_insured'] = 0.00;
        $datae['policy_type'] = 'Not Provided';
        $datae['status_of_64vb'] = '';
        $datae['payment_mode'] = '';
        $datae['settlement_type'] = '';
        $datae['bank_name'] = '';
        $datae['status_of_pre_insp'] = '';
        $datae['status_of_NCB'] = '';
        $datae['bank_address'] = '';
        $datae['HPA'] = '';
        // $datae['insurer_id'] = getClientNameByClientid($datas['client_name']);
        $datae['client_id'] = getClientNameByClientid($datas['client_name']);
        $datae['insured_name'] = $datas['insured_name'];
        $datae['insured_address'] = $datas['insured_address'];
        $datae['insured_mobile_no'] = $datas['insured_mobile_no'];
        $data1 = json_encode($datae);


        $table = ['tbl_ms_inspection_policy_detail', 'tbl_ms_inspection_vehicle_detail', 'tbl_ms_inspection_accident_detail', 'tbl_ms_accident_cause', 'tbl_ms_final_report_comment', 'tbl_ms_inspection_attachement'];


        $insured_rep_attending_survey = DB::table('tbl_ms_inspection_accident_detail')->select('insured_rep_attending_survey')->whereNotNull('insured_rep_attending_survey')->where('insured_rep_attending_survey', '!=', '')->distinct()->get();

        $rc = DB::table('tbl_ms_inspection_accident_detail')->select('RC')->distinct()->whereNotNull('RC')->where('RC', '!=', '')->get();

        $dl = DB::table('tbl_ms_inspection_accident_detail')->select('DL')->whereNotNull('DL')->where('DL', '!=', '')->distinct()->get();

        $chassis_no = DB::table('tbl_ms_inspection_accident_detail')->select('chassis_no')->whereNotNull('chassis_no')->where('chassis_no', '!=', '')->distinct()->get();

        $engine_no = DB::table('tbl_ms_inspection_accident_detail')->select('engine_no')->whereNotNull('engine_no')->where('engine_no', '!=', '')->distinct()->get();


        $load_chalan_options = DB::table('tbl_ms_inspection_accident_detail')->select('load_chalan')->whereNotNull('load_chalan')->where('load_chalan', '!=', '')->distinct()->get();


        $permit_options = DB::table('tbl_ms_inspection_accident_detail')->select('permit')->whereNotNull('permit')->where('permit', '!=', '')->distinct()->get();

        $fitness_options = DB::table('tbl_ms_inspection_accident_detail')->select('fitness')->whereNotNull('fitness')->where('fitness', '!=', '')->distinct()->get();


        if ($load_chalan_options->count() != 0) {
            $load_chalan_options = $this->getdatatojson($load_chalan_options, 'load_chalan');
        } else {
            $load_chalan_options = "";
        }


        if ($permit_options->count() != 0) {
            $permit_options = $this->getdatatojson($permit_options, 'permit');
        } else {
            $permit_options = "";
        }


        if ($fitness_options->count() != 0) {
            $fitness_options = $this->getdatatojson($fitness_options, 'fitness');
        } else {
            $fitness_options = "";
        }


        if ($insured_rep_attending_survey->count() != 0) {
            $insured_rep_attending_survey = $this->getdatatojson($insured_rep_attending_survey, 'insured_rep_attending_survey');
        } else {
            $insured_rep_attending_survey = "";
        }


        if ($rc->count() != 0) {
            $rc = $this->getdatatojson($rc, 'RC');
        } else {
            $rc = "";
        }

        if ($dl->count() != 0) {
            $dl = $this->getdatatojson($dl, 'DL');
        } else {
            $dl = "";
        }


        if ($chassis_no->count() != 0) {
            $chassis_no = $this->getdatatojson($chassis_no, 'chassis_no');
        } else {
            $chassis_no = "";
        }

        if ($engine_no->count() != 0) {
            $engine_no = $this->getdatatojson($engine_no, 'engine_no');
        } else {
            $engine_no = "";
        }


        $vehicle_make_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('vehicle_make')->whereNotNull('vehicle_make')->where('vehicle_make', '!=', '')->distinct()->get();


        $vehicle_variant_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('vehicle_variant')->whereNotNull('vehicle_variant')->where('vehicle_variant', '!=', '')->distinct()->get();


        $Engine_capacity_unit_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('Engine_capacity_unit')->whereNotNull('Engine_capacity_unit')->where('Engine_capacity_unit', '!=', '')->distinct()->get();

        //print_r($Engine_capacity_unit_options); die;


        $vehicle_color_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('vehicle_color')->whereNotNull('vehicle_color')->where('vehicle_color', '!=', '')->distinct()->get();

        $engine_capacity_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('engine_capacity')->whereNotNull('engine_capacity')->where('engine_capacity', '!=', '')->distinct()->get();

        $anti_theft_fitted_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('anti_theft_fitted')->whereNotNull('anti_theft_fitted')->where('anti_theft_fitted', '!=', '')->distinct()->get();


        $body_type = DB::table('tbl_ms_inspection_vehicle_detail')->select('body_type')->whereNotNull('body_type')->where('body_type', '!=', '')->distinct()->get();


        $anti_theft_fitted = DB::table('tbl_ms_inspection_vehicle_detail')->select('anti_theft_fitted')->whereNotNull('anti_theft_fitted')->where('anti_theft_fitted', '!=', '')->distinct()->get();

        $vehicle_class = DB::table('tbl_ms_inspection_vehicle_detail')->select('vehicle_class')->whereNotNull('vehicle_class')->where('vehicle_class', '!=', '')->distinct()->get();

        $pre_accident_cond = DB::table('tbl_ms_inspection_vehicle_detail')->select('pre_accident_cond')->whereNotNull('pre_accident_cond')->where('pre_accident_cond', '!=', '')->distinct()->get();

        $relation_with_insurer = DB::table('tbl_ms_inspection_vehicle_detail')->select('relation_with_insurer')->whereNotNull('relation_with_insurer')->where('relation_with_insurer', '!=', '')->distinct()->get();

        $issuing_authority = DB::table('tbl_ms_inspection_vehicle_detail')->select('issuing_authority')->whereNotNull('issuing_authority')->where('issuing_authority', '!=', '')->distinct()->get();

        $vehicle_allowed_to_drive = DB::table('tbl_ms_inspection_vehicle_detail')->select('vehicle_allowed_to_drive')->whereNotNull('vehicle_allowed_to_drive')->where('vehicle_allowed_to_drive', '!=', '')->distinct()->get();


        $permit_type_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('permit_type')->whereNotNull('permit_type')->where('permit_type', '!=', '')->distinct()->get();

        $accident_place_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('accident_place')->whereNotNull('accident_place')->where('accident_place', '!=', '')->distinct()->get();


        $route_options = DB::table('tbl_ms_inspection_vehicle_detail')->select('route')->whereNotNull('route')->where('route', '!=', '')->distinct()->get();


        if ($route_options->count() != 0) {


            $route_options = $this->getdatatojson($route_options, 'route');
        } else {
            $route_options = "";
        }


        if ($accident_place_options->count() != 0) {


            $accident_place_options = $this->getdatatojson($accident_place_options, 'accident_place');
        } else {
            $accident_place_options = "";
        }


        if ($vehicle_variant_options->count() != 0) {


            $vehicle_variant_options = $this->getdatatojson($vehicle_variant_options, 'vehicle_variant');
        } else {
            $vehicle_variant_options = "";
        }


        if ($permit_type_options->count() != 0) {


            $permit_type_options = $this->getdatatojson($permit_type_options, 'permit_type');
        } else {
            $permit_type_options = "";
        }


        if ($vehicle_make_options->count() != 0) {


            $vehicle_make_options = $this->getdatatojson($vehicle_make_options, 'vehicle_make');
        } else {
            $vehicle_make_options = "";
        }

        if ($Engine_capacity_unit_options->count() != 0) {

            //  print_r($Engine_capacity_unit_options); die;
            $Engine_capacity_unit_options = $this->getdatatojson($Engine_capacity_unit_options, 'Engine_capacity_unit');
        } else {
            $Engine_capacity_unit_options = "";
        }


        if ($vehicle_color_options->count() != 0) {


            $vehicle_color_options = $this->getdatatojson($vehicle_color_options, 'vehicle_color');
        } else {
            $vehicle_color_options = "";
        }


        if ($anti_theft_fitted_options->count() != 0) {


            $anti_theft_fitted_options = $this->getdatatojson($anti_theft_fitted_options, 'anti_theft_fitted');
        } else {
            $anti_theft_fitted_options = "";
        }


        if ($engine_capacity_options->count() != 0) {


            $engine_capacity_options = $this->getdatatojson($engine_capacity_options, 'engine_capacity');
        } else {
            $engine_capacity_options = "";
        }


        if ($body_type->count() != 0) {
            $body_type = $this->getdatatojson($body_type, 'body_type');
        } else {
            $body_type = "";
        }

        /* $vehicle_make_options= $this->getdatatojson($vehicle_make_options,'body_type');*/

        if ($anti_theft_fitted->count() != 0) {

            $anti_theft_fitted = $this->getdatatojson($anti_theft_fitted, 'anti_theft_fitted');
        }

        if ($vehicle_class->count() != 0) {
            $vehicle_class = $this->getdatatojson($vehicle_class, 'vehicle_class');
        }

        if ($pre_accident_cond->count() != 0) {
            $pre_accident_cond = $this->getdatatojson($pre_accident_cond, 'pre_accident_cond');
        }

        if ($relation_with_insurer->count() != 0) {
            $relation_with_insurer = $this->getdatatojson($relation_with_insurer, 'relation_with_insurer');
        }
        if ($issuing_authority->count() != 0) {
            $issuing_authority = $this->getdatatojson($issuing_authority, 'issuing_authority');
        }

        /* if($pre_accident_cond->count()!=0){
         $pre_accident_cond= $this->getdatatojson($pre_accident_cond,'pre_accident_cond');
          }*/

        if ($vehicle_allowed_to_drive->count() != 0) {
            $vehicle_allowed_to_drive = $this->getdatatojson($vehicle_allowed_to_drive, 'vehicle_allowed_to_drive');
        }


        $policy_type_options = DB::table('tbl_ms_inspection_policy_detail')->select('policy_type')->whereNotNull('policy_type')->where('policy_type', '!=', '')->distinct()->get();
        $feebill_report = DB::table('tbl_ms_inspection_feebill_report')->select('surveyFee')->distinct()->get();
        if (isset($feebill_report)) {
            $survey = "Yes";
        }


        $bank_name_options = DB::table('tbl_ms_inspection_policy_detail')->select('bank_id')->whereNotNull('bank_id')->where('bank_id', '!=', '')->distinct()->get();


        /* $insurer_name_options= DB::table('tbl_ms_inspection_policy_detail')->select('insurer_name')->distinct()->get();*/


        $insurer_name_options = DB::table('tbl_ms_clients')->select('client_name')->where($where)->distinct()->get();


        $HPA_options = DB::table('tbl_ms_inspection_policy_detail')->select('HPA')->whereNotNull('HPA')->where('HPA', '!=', '')->distinct()->get();


        $insurer_officer_options = DB::table('tbl_ms_clients')->where($where)->select('client_name')->distinct()->get();


        $operating_office_options = DB::table('tbl_ms_client_branches')->where($where1)->select('office_name')->distinct()->get();


        $appointing_office_options = DB::table('tbl_ms_client_branches')->where($where1)->select('office_name')->distinct()->get();


        $operating_officer_options = DB::table('tbl_ms_inspection_policy_detail')->select('operating_officer')->distinct()->get();

        //  ->where($where1)
        if ($bank_name_options->count() != 0) {
            $bank_name_options = $this->getdatatojson($bank_name_options, 'bank_id');
        }


        if (count($policy_type_options) != 0) {

            $policy_type_options = $this->getdatatojson($policy_type_options, 'policy_type');
        }


        if ($insurer_name_options->count() != 0) {
            $insurer_name_options = $this->getdatatojson($insurer_name_options, 'client_name');
        }


        $HPA_options = $this->getdatatojson($HPA_options, 'HPA');

        $insurer_officer_options = $this->getdatatojson($insurer_officer_options, 'client_name');

        $operating_office_options = $this->getdatatojson($operating_office_options, 'office_name');

        $appointing_office_options = $this->getdatatojson($appointing_office_options, 'office_name');

        $operating_officer_options = $this->getdatatojson($operating_officer_options, 'operating_officer');


        $data_tab = DB::table('tbl_ms_tab_allaprovedata')->where('inspection_id', $id)->first();


        if ($data_tab) {
            $inspection_policy_detail = str_replace("\n", "", $data_tab->inspection_policy_detail);

            $data_tab1 = DB::table('tbl_ms_inspection_policy_detail')->where('inspection_id', $id)->first();

            //  $selected_policy_type=$data_tab1->policy_type;
            //  $selected_operating_officer=$data_tab1->operating_officer;
            //  $selected_appointing_office=$data_tab1->appointing_branch_id;
            //  $selected_insurer_office=$data_tab1->insurer_id;

            //  $selected_insured_name=$data_tab1->insured_name;
            //  $selected_bank_name=$data_tab1->bank_name;
            //  $selected_HPA=$data_tab1->HPA;

            /* $selected_policy_type=$inspection_policy_detail->policy_type;
           $selected_policy_type=$inspection_policy_detail->policy_type;*/

            $inspection_policy_detail = stripslashes($inspection_policy_detail);
            $data['values'] = [
                'inspection_policy_detail' => ($data_tab->inspection_policy_detail == '') ? $data1 : $data_tab->inspection_policy_detail,
                'policy_type_options' => $policy_type_options,
                'surveyFee' => $survey,
                'insurer_name_options' => $insurer_name_options,
                'bank_name_options' => $bank_name_options,
                'HPA_options' => $HPA_options,
                'insurer_officer_options' => $insurer_officer_options,
                'operating_office_options' => $operating_office_options,
                'appointing_office_options' => $appointing_office_options,
                'operating_officer_options' => $operating_officer_options,
                'inspection_vehicle_detail' => ($data_tab->inspection_vehicle_detail == '') ? $this->getvehicledetailbyvehicleid($id) : $data_tab->inspection_vehicle_detail,
                'vehicle_make_option' => $vehicle_make_options,
                'body_type_options' => $body_type,
                'anti_theft_fitted_options' => $anti_theft_fitted,
                'vehicle_class_options' => $vehicle_class,
                'pre_accident_cond_options' => $pre_accident_cond,
                'relation_with_insurer_options' => $relation_with_insurer,
                'inspection_vehicle_detail_option' => $issuing_authority,
                'vehicle_allowed_to_drive_options' => $vehicle_allowed_to_drive,
                'inspection_accident_detail' => ($data_tab->inspection_accident_detail == '') ? $this->getaccidentdetailbyjobid($id) : $data_tab->inspection_accident_detail,
                'client_branch_options' => $client_branch_options,
                'insured_rep_attending_survey_options' => $insured_rep_attending_survey,
                'RC_options' => $rc,
                'type_of_DL_options' => $dl,
                'chassis_no_options' => $chassis_no,
                'engine_no_options' => $engine_no,
                'inspection_cause_detail' => $data_tab->inspection_cause_detail,
                'inspection_attachments_detail' => $data_tab->inspection_attachments,
                'inspection_feebill_report' => $data_tab->inspection_feebill_report,
                'vehicle_color_options' => $vehicle_color_options,
                'engine_capacity_options' => $engine_capacity_options,
                'anti_theft_options' => $anti_theft_fitted_options,
                'Engine_capacity_unit_options' => $Engine_capacity_unit_options,
                'permit_type_options' => $permit_type_options,
                'accident_place_options' => $accident_place_options,
                'load_chalan_options' => $load_chalan_options,
                'permit_options' => $permit_options,
                'fitness_options' => $fitness_options,
                'route_options' => $route_options,
                'inspection_final_report_comment' => $data_tab->inspection_final_report_comment,
                'inspection_attachments' => $data_tab->inspection_attachments,
                'report_dynamic_section' => $this->formatteddynamicsection($id),
                'vehicle_variant_options' => $vehicle_variant_options,


                /*  'selected_policy_type'=>$selected_policy_type,
                            'selected_bank_name'=>$selected_bank_name,
                            'selected_HPA'=>$selected_HPA,
                            'selected_insured_name'=>$selected_insured_name,
                            'selected_bank_name'=>$selected_bank_name,
                            'selected_operating_officer'=>$selected_operating_officer,
                            'selected_appointing_office'=>$selected_appointing_office,
                            'selected_insurer_office'=>$selected_insurer_office,*/


            ];
        } else {
            $data['values'] = [
                'inspection_policy_detail' => $data1,
                'policy_type_options' => $policy_type_options,
                'insurer_name_options' => $insurer_name_options,
                'bank_name_options' => $bank_name_options,
                'HPA_options' => $HPA_options,
                'insurer_officer_options' => $insurer_officer_options,
                'operating_office_options' => $operating_office_options,
                'appointing_office_options' => $appointing_office_options,
                'operating_officer_options' => $operating_officer_options,
                'inspection_vehicle_detail' => $this->getvehicledetailbyvehicleid($id),
                'client_branch_options' => $client_branch_options,

                'vehicle_make_option' => $vehicle_make_options,
                'body_type_options' => $body_type,
                'anti_theft_fitted_options' => $anti_theft_fitted,
                'vehicle_class_options' => $vehicle_class,
                'pre_accident_cond_options' => $pre_accident_cond,
                'relation_with_insurer_options' => $relation_with_insurer,
                'inspection_vehicle_detail_options' => $issuing_authority,
                'vehicle_allowed_to_drive_options' => $vehicle_allowed_to_drive,
                'inspection_accident_detail' => $this->getaccidentdetailbyjobid($id),
                'insured_rep_attending_survey_options' => $insured_rep_attending_survey,
                'RC_options' => $rc,
                'DL_options' => $dl,
                'chassis_no_options' => $chassis_no,
                'engine_no_options' => $engine_no,
                '',
                'inspection_cause_detail' => '',
                'vehicle_color_options' => $vehicle_color_options,
                'engine_capacity_options' => $engine_capacity_options,
                'anti_theft_options' => $anti_theft_fitted_options,
                'Engine_capacity_unit_options' => $Engine_capacity_unit_options,
                'permit_type_options' => $permit_type_options,
                'accident_place_options' => $accident_place_options,
                'load_chalan_options' => $load_chalan_options,
                'permit_options' => $permit_options,
                'fitness_options' => $fitness_options,
                'route_options' => $route_options,
                'inspection_final_report_comment' => '',
                'inspection_attachments' => '',
                'report_dynamic_section' => '',
                'vehicle_variant_options' => $vehicle_variant_options,
            ];
        }


        return $this->sendResponse($data, 'Tab data Fetched', 200);
    }

    public function store_old(Request $req, $id)
    {


        $da = $req->validate([
            'inspection_policy_detail' => 'nullable|json',
            'inspection_vehicle_detail' => 'nullable|json',
            'inspection_accident_detail' => 'nullable|json',
            'inspection_cause_detail' => 'nullable|json',
            'inspection_final_report_comment' => 'nullable|json',
            'inspection_attachments' => 'nullable|json',
        ]);


        $arrFieldsToBeValidate1 = [
            'reference_no' => 'required|string',
            'report_type' => 'nullable|string',
            'reportGeneratedOn' => 'nullable|string',
            'claim_no' => 'nullable|string',
            'policy_number' => 'nullable|string',
            'policy_valid_from' => 'nullable|string',
            'policy_valid_to' => 'nullable|string',
            'sum_insured' => 'nullable|string',
            'policy_type' => 'nullable|string',
            'status_of_64vb' => 'nullable|string',
            'status_of_pre_insp' => 'nullable|string',
            'status_of_NCB' => 'nullable|string',
            'payment_mode' => 'nullable|string',
            'settlement_type' => 'nullable|string',
            'insurer_id' => 'required|numeric',
            'insurer_branch_id' => 'required|numeric',
            'operating_officer' => 'nullable|string',
            'appointing_branch_id' => 'nullable|string',
            'insured_name' => 'nullable|string',
            'insured_address' => 'nullable|string',
            'insured_mobile_no' => 'nullable|string',
            'registured_owner' => 'nullable|string',
            'bank_id' => 'nullable|numeric',
            'bank_address' => 'nullable|string',
            'account_no' => 'nullable|string',
            'ifsc_code' => 'nullable|string',
            'HPA' => 'nullable|string'
        ];

        $arrayfieldtovalidate2 = [
            'registration_no' => 'required|string',
            'issued_on' => 'nullable|string',
            'valid_to' => 'nullable|string',
            'RC_valid_to' => 'nullable|string',
            'date_of_purchace' => 'nullable|string',
            'date_of_registration' => 'nullable|string',
            'date_of_transfer' => 'nullable|string',
            'chassis_no' => 'nullable|string',
            'engine_no' => 'nullable|string',
            'vehicle_make_id' => 'nullable|string',
            'vehicle_varient' => 'nullable|string',
            'vehicle_model' => 'nullable|string',
            'vehicle_color' => 'nullable|string',
            'engine_capacity' => 'nullable|string',
            'body_type' => 'required|numeric',
            'odometer_reading' => 'required|numeric',
            'seating_capacity' => 'nullable|string',
            'anti_theft_fitted_id' => 'nullable|string',
            'fuel' => 'nullable|string',
            'fuel_kit' => 'nullable|string',
            'vehicle_type' => 'nullable|string',
            'vehicle_class' => 'nullable|string',
            'pre_accident_cond' => 'nullable|numeric',
            'tax_paid_from' => 'nullable|string',
            'tax_paid_to' => 'nullable|string',
            'fitness_number' => 'nullable|string',
            'fitness_valid_to' => 'nullable|string',
            'permit_number' => 'nullable|string',
            'permit_valid_from' => 'nullable|string',
            'permit_valid_to' => 'nullable|string',
            'permit_type' => 'nullable|string',
            'accident_place' => 'nullable|string',
            'route' => 'nullable|string',
            'authorization_no' => 'nullable|string',
            'authorization_valid_from' => 'nullable|string',
            'authorization_valid_to' => 'nullable|string',
            'carrying_capacity' => 'nullable|string',
            'registered_laden_weigh' => 'nullable|string',
            'unladen_weight' => 'nullable|string',
            'cause_of_accident' => 'nullable|string',
            'driver_name' => 'nullable|string',
            'driver_dob' => 'nullable|string',
            'address' => 'nullable|string',
            'DL_renewal_no' => 'nullable|string',
            'DL_no' => 'nullable|string',
            'issuing_authority' => 'nullable|string',
            'issuing_date' => 'nullable|string',
            'DL_valid_upto' => 'nullable|string',
            'nt_dl_valid_from' => 'nullable|string',
            'nt_dt_valid_to' => 'nullable|string',
            'type_of_dl' => 'nullable|string',
            'vehicle_allowed_to_drive' => 'nullable|string',
            'endorsement_detail' => 'nullable|string',
            'badge_no' => 'nullable|string',
            'additional_details' => 'nullable|string'
        ];

        $arrFieldsToBeValidate3 = [
            'place_of_accident' => 'required|string',
            'date_time_of_accident' => 'nullable|string',
            'date_of_appointment' => 'nullable|string',
            'survey_place' => 'nullable|string',
            'workshop_name' => 'nullable|string',
            'date_time_survey' => 'nullable|string',
            'date_of_under_repair_visit' => 'nullable|string',
            'estimate_no' => 'nullable|string',
            'date_of_estimate' => 'nullable|string',
            'insured_rep_attending_survey' => 'nullable|string',
            'vehicle_left_unattended' => 'nullable|string',
            'vehicle_left_unattended_desc' => 'nullable|string',
            'accident_reported_to_police' => 'nullable|string',
            'panchnama' => 'nullable|string',
            'third_party_injury' => 'required|numeric',
            'injury_to_driver' => 'required|numeric',
            'previous_claim_details' => 'nullable|string',
            'spot_survey_by' => 'nullable|string',
            'spot_survey_date' => 'nullable|string',
            'passenger_detail' => 'nullable|string',
            'RC' => 'nullable|string',
            'DL' => 'nullable|string',
            'chassis_no' => 'nullable|numeric',
            'fitness' => 'nullable|string',
            'load_chalan' => 'nullable|string',
            'permit' => 'nullable|string',
            'carrying_capacity' => 'nullable|string',
            'registered_laden_weight' => 'nullable|string',
            'unladen_weight' => 'nullable|string',
            'overloading_accident' => 'nullable|string',
        ];

        $arrFieldsToBeValidate4 = [
            'description' => 'required|string',
            'action_of_survey' => 'nullable|string',
            'particular_of_damage' => 'nullable|string',
            'observation' => 'nullable|string'
        ];

        $arrFieldsToBeValidate5 = ['comment' => 'required|string'];

        $arrFieldsToBeValidate6 = [
            'affidevit' => 'nullable|string',
            'copy_of_RR' => 'nullable|string',
            'copy_of_permit' => 'nullable|string',
            'copy_of_traffic_challan' => 'nullable|string',
            'estimate_copy' => 'nullable|string',
            'report_in_duplicate' => 'nullable|string',
            'invoice' => 'nullable|string',
            'copy_of_fitness' => 'nullable|string',
            'copy_of_receipt' => 'nullable|string',
            'copy_of_letter_to_inssured' => 'nullable|string',
            'survey_fee_bill' => 'nullable|string',
            'claim_form' => 'nullable|string',
            'copy_of_load_challan' => 'nullable|string',
            'copy_of_RC' => 'nullable|string',
            'discharge_voucher' => 'nullable|string',
            'letter_of_inssured' => 'nullable|string',
            'satisfaction_voucher' => 'nullable|string',
            'copy_of_DL' => 'nullable|string',
            'copy_of_policy' => 'nullable|string',
            'generate_photosheet' => 'nullable|string',
            'medical_papers' => 'nullable|string',
            'supporting_bills' => 'nullable|string',
            'copy_dealer_inv' => 'nullable|string',
            'intimation_letter' => 'nullable|string',
            'photograps' => '',
            'towing_charges_slip' => ''
        ];


        $table = ['tbl_ms_inspection_policy_detail', 'tbl_ms_inspection_vehicle_detail', 'tbl_ms_inspection_accident_detail', 'tbl_ms_accident_cause', 'tbl_ms_final_report_comment', 'tbl_ms_inspection_attachement'];


        DB::table($table[0])->where('inspection_id', $id)->delete();
        DB::table($table[1])->where('inspection_id', $id)->delete();
        DB::table($table[2])->where('inspection_id', $id)->delete();
        DB::table($table[3])->where('inspection_id', $id)->delete();
        DB::table($table[4])->where('inspection_id', $id)->delete();
        DB::table($table[5])->where('inspection_id', $id)->delete();


        /*  $tabkeys=['inspection_policy_detail','inspection_vehicle_detail','inspection_accident_detail','inspection_cause_detail','inspection_final_report_comment','inspection_attachments'];

            $values=json_decode($req->values);*/

        $final['inspection_id'] = $id;
        if (isset($req->inspection_policy_detail) && $req->inspection_policy_detail != '') {

            $final['inspection_policy_detail'] = $req->inspection_policy_detail;

            $values = json_decode($req->inspection_policy_detail);


            $data['inspection_id'] = $id;
            foreach ($values as $key => $valueall) {

                $data[$key] = $valueall;
            }


            $valuesvalidate = json_decode(json_encode($values), true);

            $validator = Validator::make($valuesvalidate, $arrFieldsToBeValidate1);

            if ($validator->fails()) {


                $errors = $validator->errors();
                return senderrormsg($errors);
            } else {

                DB::table($table[0])->insert($data);
            }
        }


        if (isset($req->inspection_vehicle_detail) && $req->inspection_vehicle_detail != '') {

            $final['inspection_vehicle_detail'] = $req->inspection_vehicle_detail;


            $values = json_decode($req->inspection_vehicle_detail);
            $data1['inspection_id'] = $id;
            foreach ($values as $key => $valueall) {

                $data1[$key] = $valueall;
            }


            $valuesvalidate = json_decode(json_encode($values), true);

            $validator = Validator::make($valuesvalidate, $arrayfieldtovalidate2);

            if ($validator->fails()) {

                $errors = $validator->errors();
                return senderrormsg($errors);
            } else {

                DB::table($table[1])->insert($data1);
            }
        }


        if (isset($req->inspection_accident_detail) && $req->inspection_accident_detail != '') {

            $final['inspection_accident_detail'] = $req->inspection_accident_detail;


            $values = json_decode($req->inspection_accident_detail);
            $data2['inspection_id'] = $id;
            foreach ($values as $key => $valueall) {

                $data2[$key] = $valueall;
            }


            $valuesvalidate = json_decode(json_encode($values), true);

            $validator = Validator::make($valuesvalidate, $arrFieldsToBeValidate3);

            if ($validator->fails()) {

                $errors = $validator->errors();
                return senderrormsg($errors);
            } else {

                DB::table($table[2])->insert($data2);
            }
        }


        if (isset($req->inspection_cause_detail) && $req->inspection_cause_detail != '') {

            $final['inspection_cause_detail'] = $req->inspection_cause_detail;

            $values = json_decode($req->inspection_cause_detail);
            $data3['inspection_id'] = $id;
            foreach ($values as $key => $valueall) {

                $data3[$key] = $valueall;
            }


            $valuesvalidate = json_decode(json_encode($values), true);

            $validator = Validator::make($valuesvalidate, $arrFieldsToBeValidate4);

            if ($validator->fails()) {

                $errors = $validator->errors();
                return senderrormsg($errors);
            } else {

                DB::table($table[3])->insert($data3);
            }
        }


        if (isset($req->inspection_final_report_comment) && $req->inspection_final_report_comment != '') {
            $final['inspection_final_report_comment'] = $req->inspection_final_report_comment;

            $values = json_decode($req->inspection_final_report_comment);
            $data4['inspection_id'] = $id;
            foreach ($values as $key => $valueall) {

                $data4[$key] = $valueall;
            }


            $valuesvalidate = json_decode(json_encode($values), true);

            $validator = Validator::make($valuesvalidate, $arrFieldsToBeValidate5);

            if ($validator->fails()) {

                $errors = $validator->errors();
                return senderrormsg($errors);
            } else {

                DB::table($table[4])->insert($data4);
            }
        }


        if (isset($req->inspection_attachments) && $req->inspection_attachments != '') {

            $final['inspection_attachments'] = $req->inspection_attachments;

            $values = json_decode($req->inspection_attachments);
            $data5['inspection_id'] = $id;
            foreach ($values as $key => $valueall) {

                $data5[$key] = $valueall;
            }


            $valuesvalidate = json_decode(json_encode($values), true);

            $validator = Validator::make($valuesvalidate, $arrFieldsToBeValidate6);

            if ($validator->fails()) {

                $errors = $validator->errors();
                return senderrormsg($errors);
            } else {

                DB::table($table[5])->insert($data5);
            }
        }

        DB::table('tbl_ms_tab_allaprovedata')->where('inspection_id', $id)->delete();


        DB::table('tbl_ms_tab_allaprovedata')->insert($final);
        return $this->sendResponse("sucess", 'Saved Sussessfully', 200);


        /*
         $i=0;
           foreach($values as $key=>$value){
     $vi=$tabkeys[$i];
             $value->$vi->job_id=$id;

            $data=[];

              foreach($value->$vi as $key=>$valueall){

                $data[$key]=$valueall;
              }

           print_r($data);

          DB::table($table[$i])->insert($data);
             $i++;

           }*/

        //  die("==============");


    }


    public function store(Request $req, $id)
    {


        $da = $req->validate([
            'inspection_policy_detail' => '',
            'inspection_vehicle_detail' => '',
            'inspection_accident_detail' => '',
            'inspection_cause_detail' => '',
            'inspection_final_report_comment' => '',
            'inspection_attachments' => '',
            'inspection_feebill_report' => '',
        ]);


        $arrFieldsToBeValidate1 = [
            'reference_no' => '',
            'report_type' => '',
            'reportGeneratedOn' => '',
            'claim_no' => '',
            'policy_number' => '',
            'policy_valid_from' => '',
            'policy_valid_to' => '',
            'sum_insured' => '',
            'policy_type' => '',
            'status_of_64vb' => '',
            'status_of_pre_insp' => '',
            'status_of_NCB' => '',
            'payment_mode' => '',
            'settlement_type' => '',
            'insurer_id' => '',
            'insurer_branch_id' => '',
            'operating_officer' => '',
            'appointing_branch_id' => '',
            'insured_name' => '',
            'insured_address' => '',
            'insured_mobile_no' => '',
            'registured_owner' => '',
            'bank_id' => 'nullable',
            'bank_address' => '',
            'account_no' => '',
            'ifsc_code' => '',
            'HPA' => ''
        ];

        $arrayfieldtovalidate2 = [
            'registration_no' => '',
            'issued_on' => '',
            'valid_to' => '',
            'RC_valid_to' => '',
            'date_of_purchace' => '',
            'date_of_registration' => '',
            'date_of_transfer' => '',
            'chassis_no' => '',
            'engine_no' => '',
            'vehicle_make_id' => '',
            'vehicle_varient' => '',
            'vehicle_model' => '',
            'vehicle_color' => '',
            'engine_capacity' => '',
            'body_type' => '',
            'odometer_reading' => '',
            'seating_capacity' => '',
            'anti_theft_fitted_id' => '',
            'fuel' => '',
            'fuel_kit' => '',
            'vehicle_type' => '',
            'vehicle_class' => '',
            'pre_accident_cond' => 'nullable',
            'tax_paid_from' => '',
            'tax_paid_to' => '',
            'fitness_number' => '',
            'fitness_valid_to' => '',
            'permit_number' => '',
            'permit_valid_from' => '',
            'permit_valid_to' => '',
            'permit_type' => '',
            'accident_place' => '',
            'route' => '',
            'authorization_no' => '',
            'authorization_valid_from' => '',
            'authorization_valid_to' => '',
            'carrying_capacity' => '',
            'registered_laden_weigh' => '',
            'unladen_weight' => '',
            'cause_of_accident' => '',
            'driver_name' => '',
            'driver_dob' => '',
            'address' => '',
            'DL_renewal_no' => '',
            'DL_no' => '',
            'issuing_authority' => '',
            'issuing_date' => '',
            'DL_valid_upto' => '',
            'nt_dl_valid_from' => '',
            'nt_dt_valid_to' => '',
            'type_of_dl' => '',
            'vehicle_allowed_to_drive' => '',
            'endorsement_detail' => '',
            'badge_no' => '',
            'additional_details' => '',
            'Tax_Valid_From_Text' => '',


        ];

        $arrFieldsToBeValidate3 = [
            'place_of_accident' => '',
            'date_time_of_accident' => '',
            'date_of_appointment' => '',
            'survey_place' => '',
            'workshop_name' => '',
            'date_time_survey' => '',
            'date_of_under_repair_visit' => '',
            'estimate_no' => '',
            'date_of_estimate' => '',
            'insured_rep_attending_survey' => '',
            'vehicle_left_unattended' => '',
            'vehicle_left_unattended_desc' => '',
            'accident_reported_to_police' => '',
            'panchnama' => '',
            'third_party_injury' => '',
            'injury_to_driver' => '',
            'previous_claim_details' => '',
            'spot_survey_by' => '',
            'spot_survey_date' => '',
            'passenger_detail' => '',
            'RC' => '',
            'DL' => '',
            'chassis_no' => 'nullable',
            'fitness' => '',
            'load_chalan' => '',
            'permit' => '',
            'Date_of_Under_repair_Visits1' => '',
            'Date_of_Under_repair_Visits2' => '',
            'Date_of_Under_repair_Text' => '',
        ];

        $arrFieldsToBeValidate4 = [
            'cause_nature_accident' => '',
            'action_of_survey' => '',
            'particular_of_damage' => '',
            'observation' => ''
        ];

        $arrFieldsToBeValidate5 = ['comment' => ''];

        $arrFieldsToBeValidate6 = [
            'affidevit' => '',
            'copy_of_RR' => '',
            'copy_of_permit' => '',
            'copy_of_traffic_challan' => '',
            'estimate_copy' => '',
            'report_in_duplicate' => '',
            'invoice' => '',
            'copy_of_fitness' => '',
            'copy_of_receipt' => '',
            'copy_of_letter_to_inssured' => '',
            'survey_fee_bill' => '',
            'claim_form' => '',
            'copy_of_load_challan' => '',
            'copy_of_RC' => '',
            'discharge_voucher' => '',
            'letter_of_inssured' => '',
            'satisfaction_voucher' => '',
            'copy_of_DL' => '',
            'copy_of_policy' => '',
            'generate_photosheet' => '',
            'medical_papers' => '',
            'supporting_bills' => '',
            'copy_dealer_inv' => '',
            'intimation_letter' => '',
            'photograps' => '',
            'towing_charges_slip' => ''
        ];


        $table = [
            'tbl_ms_inspection_policy_detail',
            'tbl_ms_inspection_vehicle_detail',
            'tbl_ms_inspection_accident_detail',
            'tbl_ms_accident_cause',
            'tbl_ms_final_report_comment',
            'tbl_ms_inspection_attachement',
            'tbl_ms_attachement',
            'tbl_ms_dynamic_sections',
            'tbl_ms_inspection_feebill_report'
        ];


        DB::table($table[0])->where('inspection_id', $id)->delete();
        DB::table($table[1])->where('inspection_id', $id)->delete();
        DB::table($table[2])->where('inspection_id', $id)->delete();
        DB::table($table[3])->where('inspection_id', $id)->delete();
        DB::table($table[4])->where('inspection_id', $id)->delete();
        DB::table($table[5])->where('inspection_id', $id)->delete();
        //DB::table($table[6])->where('inspection_id', $id)->delete();
        //DB::table($table[7])->where('inspection_id', $id)->delete();
        //DB::table($table[8])->where('inspection_id', $id)->delete();


        /*  $tabkeys=['inspection_policy_detail','inspection_vehicle_detail','inspection_accident_detail','inspection_cause_detail','inspection_final_report_comment','inspection_attachments'];

    $values=json_decode($req->values);*/


        $created_by = Auth::user()->id;
        $final['created_by'] = $created_by;
        $final['inspection_id'] = $id;

        if (isset($req->inspection_policy_detail) && $req->inspection_policy_detail != '') {

            $final['inspection_policy_detail'] = $req->inspection_policy_detail;

            $values = json_decode($req->inspection_policy_detail);


            $data['inspection_id'] = $id;
            foreach ($values as $key => $valueall) {

                $data[$key] = $valueall;
            }


            $valuesvalidate = json_decode(json_encode($values), true);

            $validator = Validator::make($valuesvalidate, $arrFieldsToBeValidate1);

            if ($validator->fails()) {


                $errors = $validator->errors();
                return senderrormsg($errors);
            } else {

                DB::table($table[0])->insert($data);
            }
        }


        if (isset($req->inspection_vehicle_detail) && $req->inspection_vehicle_detail != '') {

            $final['inspection_vehicle_detail'] = $req->inspection_vehicle_detail;


            $values = json_decode($req->inspection_vehicle_detail);
            $data1['inspection_id'] = $id;
            foreach ($values as $key => $valueall) {

                $data1[$key] = $valueall;
            }


            $valuesvalidate = json_decode(json_encode($values), true);

            $validator = Validator::make($valuesvalidate, $arrayfieldtovalidate2);

            if ($validator->fails()) {

                $errors = $validator->errors();
                return senderrormsg($errors);
            } else {

                DB::table($table[1])->insert($data1);
            }
        }


        if (isset($req->inspection_accident_detail) && $req->inspection_accident_detail != '') {

            $final['inspection_accident_detail'] = $req->inspection_accident_detail;


            $values = json_decode($req->inspection_accident_detail);
            $data2['inspection_id'] = $id;
            foreach ($values as $key => $valueall) {

                $data2[$key] = $valueall;
            }


            $valuesvalidate = json_decode(json_encode($values), true);

            $validator = Validator::make($valuesvalidate, $arrFieldsToBeValidate3);

            if ($validator->fails()) {

                $errors = $validator->errors();
                return senderrormsg($errors);
            } else {

                DB::table($table[2])->insert($data2);
            }
        }


        if (isset($req->inspection_cause_detail) && $req->inspection_cause_detail != '') {

            $final['inspection_cause_detail'] = $req->inspection_cause_detail;

            $values = json_decode($req->inspection_cause_detail);
            $data3['inspection_id'] = $id;
            foreach ($values as $key => $valueall) {

                $data3[$key] = $valueall;
            }


            $valuesvalidate = json_decode(json_encode($values), true);

            $validator = Validator::make($valuesvalidate, $arrFieldsToBeValidate4);

            if ($validator->fails()) {

                $errors = $validator->errors();
                return senderrormsg($errors);
            } else {

                DB::table($table[3])->insert($data3);
            }
        }


        if (isset($req->inspection_final_report_comment) && $req->inspection_final_report_comment != '') {
            $final['inspection_final_report_comment'] = $req->inspection_final_report_comment;

            $values = json_decode($req->inspection_final_report_comment);
            $data4['inspection_id'] = $id;
            foreach ($values as $key => $valueall) {

                $data4[$key] = $valueall;
            }


            $valuesvalidate = json_decode(json_encode($values), true);

            $validator = Validator::make($valuesvalidate, $arrFieldsToBeValidate5);

            if ($validator->fails()) {

                $errors = $validator->errors();
                return senderrormsg($errors);
            } else {

                DB::table($table[4])->insert($data4);
            }
        }


        if (isset($req->report_dynamic_section) && $req->report_dynamic_section != '') {

            $final['report_dynamic_section'] = $req->report_dynamic_section;

            $values = json_decode($req->report_dynamic_section);

            $job_id_req = $id;

            $this->savedynamicsection($values, $job_id_req);
        }


        if (isset($req->inspection_attachments) && $req->inspection_attachments != '') {

            $final['inspection_attachments'] = $req->inspection_attachments;

            $data6['inspection_id'] = $id;
            $data6['created_at'] = date('Y-m-d H:i:s');
            $data6['attachments'] = $req->inspection_attachments;
            $Attachement = Attachement::where('inspection_id', $id)->first();


            if (isset($Attachement)) {
                $jid = $Attachement->id;
                Attachement::where('id', $jid)->update($data6);
            } else {
                DB::table($table[6])->insert($data6);
            }
        }
        // inspection_feebill_report
        if (isset($req->inspection_feebill_report) && $req->inspection_feebill_report != '') {

            $final['inspection_feebill_report'] = $req->inspection_feebill_report;
            $values = json_decode($req->inspection_feebill_report);
            $job_id_req = $id;

            $this->feebill_report($values, $job_id_req);
        }
        //inspection_feebill_report end

        DB::table('tbl_ms_tab_allaprovedata')->where('inspection_id', $id)->delete();


        DB::table('tbl_ms_tab_allaprovedata')->insert($final);
        return $this->sendResponse("sucess", 'Saved Sussessfully', 200);
    }

    public function getbranchbyclientid(Request $request)
    {
        $created_by = Auth::user()->id;
        $id = $request->client_id;
        $cl = '';
        $cl = DB::table('tbl_ms_client_branches')->where('client_id', $id)->first();
        if ($cl) {
            $parentid = $cl->parent_admin_id;
            $cl = DB::table('tbl_ms_client_branches')->where('parent_admin_id', $parentid)->get();
            if ($cl->count() > 0) {
                $clboption = [];
                foreach ($cl as $policy) {

                    array_push($clboption, $policy->name);
                }
                $cl = json_encode($clboption);
            } else {
                $cl = '';
            }
        }

        $data['values'] = [
            'client_branch_options' => $cl
        ];


        return $this->sendResponse($data, 'branches fetched  Sussessfully', 200);
    }


    public function getvehicledetailbyvehicleid($id)
    {

        $data = DB::table('tbl_ms_inspection_vehicle_detail')->where('inspection_id', $id)->first();
        if (!$data) {
            $datas = Inspection::where('id', $id)->first();
            $datae['date_registration'] = $datas->date_of_registration;
            $datae['chassis_no'] = $datas->chassis_no;
            $datae['engine_no'] = $datas->engine_no;
            $datae['vehicle_make'] = $datas->vehicle_make;
            $datae['vehicle_model'] = $datas->vehicle_model;
            $datae['odometer_reading'] = $datas->odometer_reading;
            $datae['vehicle_type'] = $datas->vehicle_type;

            return json_encode($datae);
        } else {
            return "";
        }
    }


    public function getaccidentdetailbyjobid($id)
    {

        $data = DB::table('tbl_ms_inspection_accident_detail')->where('inspection_id', $id)->first();
        if (!$data) {
            $datas = Inspection::where('id', $id)->first();
            $datae['place_of_accident'] = $datas->place_accident;
            $datae['date_time_of_accident'] = $datas->date_time_accident;
            $datae['date_of_appointment'] = $datas->date_of_appointment;
            $datae['survey_place'] = $datas->place_survey;
            $datae['date_time_survey'] = $datas->Survey_Date_time;
            $datae['workshop_name'] = $datas->workshop_name;
            $datae['workshop_branch'] = $datas->workshop_branch;


            return json_encode($datae);
        } else {
            return "";
        }
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

    public function storeattachmentmaster(Request $request)
    {
        $created_by = Auth::user()->id;
        $admin_branch_id = Auth::user()->admin_branch_id;

        if ($request->user()->tokenCan('type:employee')) {
            $branch = getBranchidofemplyee($id);
            $parent_admin_id = getAdminIdIfEmployeeLoggedIn1($created_by);
        }

        if ($request->user()->tokenCan('type:admin')) {
            if (Auth::user()->id == 'admin') {
                $branch = 0;
            } else {
                $branch = getBranchidofadmin($created_by);
            }
        }


        $main_admin_id = getmainAdminIdIfAdminLoggedIn($created_by);
        $dynamicSections = Store_Attachment_Master::where('key', $request->key)->first();

        if (!$dynamicSections) {

            $data = [
                'admin_branch_id' => $admin_branch_id,
                'admin_id' => $main_admin_id,
                'key' => $request->key,
                'value' => $request->value,
                'created_by' => $created_by,
            ];

            $Dynamic_sections = Store_Attachment_Master::create($data);
        } else {
            if ($dynamicSections->admin_id == $main_admin_id) {

                $data = [

                    'admin_branch_id' => $admin_branch_id,
                    'admin_id' => $main_admin_id,
                    'value' => $request->value,
                    'created_by' => $created_by,
                ];
                Store_Attachment_Master::where('key', $request->key)->update($data);
            } else {

                $data = [
                    'admin_branch_id' => $admin_branch_id,
                    'admin_id' => $main_admin_id,
                    'key' => $request->key,
                    'value' => $request->value,
                    'created_by' => $created_by,
                ];
                $Dynamic_sections = Store_Attachment_Master::create($data);
            }
        }

        return $this->sendResponse('', "sucessfully inserted", 200);
    }


    public function storeattachment($data6)
    {
        $dynamicSections = Store_Attachment::where('inspection_id', $data6->job_id)->first();

        if (!isset($dynamicSections)) {
            $datas = [
                'inspection_id' => $data6->job_id,
                'attachments' => $data6->attachments,
            ];

            $dynamicSections = Store_Attachment::create($datas);
        } else {
            // If a record exists, update the existing one
            $datas = [
                'attachments' => $data6->attachments,
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            Store_Attachment::where('inspection_id', $data6->job_id)->update($datas);
            // $dynamicSections->update($data);
        }

        return $this->sendResponse($dynamicSections, "Successfully inserted", 200);
    }

    public function getstoreattachment(Request $request)
    {

        $logged_in_id = Auth::user()->id;

        if ($request->user()->tokenCan('type:employee')) {
            $branch = getBranchidofemplyee($id);
            $parent_admin_id = getAdminIdIfEmployeeLoggedIn1($logged_in_id);
        }

        if ($request->user()->tokenCan('type:admin')) {
            if (Auth::user()->id == 'admin') {
                $branch = '';
            } else {
                $branch = getBranchidofadmin($logged_in_id);
            }
        }

        // where('admin_id','')->
        if ($branch == '') {
            $dynamicSections = Store_Attachment_Master::where('admin_id', $logged_in_id)->get();
        } else {
            $dynamicSections = Store_Attachment_Master::where('admin_branch_id', $branch)->get();
        }


        $data = [];
        foreach ($dynamicSections as $dynamicSection) {
            $data[] = [
                'key' => $dynamicSection->key,
                'value' => $dynamicSection->value,

            ];
        }
        return $this->sendResponse($data, 'Data fetched successfully', 200);
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

    public function deletemasterattachment(Request $request)
    {

        $created_by = Auth::user()->id;
        $parent_admin_id = getmainAdminIdIfAdminLoggedIn($created_by);

        $key = $request->key;

        $Dynamic_sections = Store_Attachment_Master::where('key', $key)->where('admin_id', $parent_admin_id)->delete();


        return $this->sendResponse($Dynamic_sections, "Successfully deleted", 200);
    }


    // dynamic section data

    public function savedynamicsection($values, $job_id)
    {
        $created_by = Auth::user()->id;

        foreach ($values as $valueall) {

            if (isset($valueall->master_section)) {


                $datam = [
                    'inspection_id' => $job_id,
                    'SrNo' => $valueall->SrNo,
                    'Heading' => $valueall->master_section,
                    'Details' => $valueall->add_details,
                    'add_Report' => $valueall->add_Report,
                    'created_by' => $created_by,
                    'section_type' => 'master_section'
                ];

                Dynamic_sections::create($datam);
            }
            if (isset($valueall->report_section)) {

                $datas = [
                    'inspection_id' => $job_id,
                    'SrNo' => $valueall->SrNo,
                    'Heading' => $valueall->report_section,
                    'Details' => $valueall->add_details,
                    'created_by' => $created_by,
                    'section_type' => 'report_section',
                ];
                Dynamic_sections::create($datas);
            }
        }
    }

    public function feebill_report($valueall, $job_id)
    {
        $created_by = Auth::user()->id;
        $main_admin_id = getmainAdminIdIfAdminLoggedIn($created_by);
        $values = Inspection::where('id', $job_id)->first();
        if ($values) {
            $admin_branch_id = $values->admin_branch_id;
        }
        $Feebill_report = Feebill_report::where('inspection_id', $job_id)->first();
        BanksDetailsModel::where('admin_id', $main_admin_id)->where('admin_branch_id', $admin_branch_id)->delete();
        $surveyFee = json_encode($valueall->surveyFee);
        $conveyanceFee = json_encode($valueall->conveyanceFee);
        $vehiclePhotographs = json_encode($valueall->vehiclePhotographs);
        $miscellaneous = json_encode($valueall->miscellaneous);
        $bank_details = json_encode($valueall->bank_details);
        $bank_detail = json_decode($bank_details, true);
        //print_r($bank_detail);die('====');
        $i = 0;

        foreach ($bank_detail as $bank) {

            $data1 = [
                'bank_code' => $valueall->bank_details[$i]->bank_code,
                'bank_name' => $valueall->bank_details[$i]->bank_name,
                'branch_address' => $valueall->bank_details[$i]->branch_address,
                'account_number' => $valueall->bank_details[$i]->account_number,
                'account_type' => $valueall->bank_details[$i]->account_type,
                'ifsc' => $valueall->bank_details[$i]->ifsc,
                'micr' => $valueall->bank_details[$i]->micr,
                'created_by' => $created_by,
                'admin_id' => $main_admin_id,
                'admin_branch_id' => $admin_branch_id,
            ];
            $i++;
            //print_r($data1);die('=====');
            BanksDetailsModel::create($data1);
        }
        $data = [
            'bill_no' => $valueall->bill_no,
            'bill_date' => $valueall->bill_date,
            'issued_to' => $valueall->issued_to,
            'payment_by' => $valueall->payment_by,
            'surveyFee' => $surveyFee,
            'conveyanceFee' => $conveyanceFee,
            'vehiclePhotographs' => $vehiclePhotographs,
            'miscellaneous' => $miscellaneous,
            'survey_fee_total' => $valueall->survey_fee_total,
            'conveyance_fee_total' => $valueall->conveyance_fee_total,
            'photographs_amount_total' => $valueall->photographs_amount_total,
            'miscellaneous_amount_total' => $valueall->miscellaneous_amount_total,
            'amount_before_tax' => $valueall->amount_before_tax,
            'cash_receipted' => $valueall->cash_receipted,
            'cgst_percentage' => $valueall->cgst_percentage,
            'sgst_percentage' => $valueall->sgst_percentage,
            'igst_percentage' => $valueall->igst_percentage,
            'gst_amount' => $valueall->gst_amount,
            'amount_after_tax' => $valueall->amount_after_tax,
            'bank_details' => $bank_details,
            'bank_code' => $valueall->bank_code,
            'bank_id' => $valueall->bank_code,
            'comment' => $valueall->comment,
            'inspection_id' => $job_id,
            'created_by' => $created_by,
            'updated_by' => $created_by,
        ];
        if (isset($Feebill_report)) {
            $jid = $Feebill_report->id;
            Feebill_report::where('id', $jid)->update($data);
        } else {
            Feebill_report::create($data);
        }
    }


    public function getdynamicsectiondata(Request $request)
    {
        $dynamicSections = Dynamic_sections::all();
        $data = [];
        foreach ($dynamicSections as $dynamicSection) {
            $data[] = [

                'HeadingId' => $dynamicSection->HeadingId,
                'SrNo' => $dynamicSection->SrNo,
                'Heading' => $dynamicSection->Heading,
                'Details' => $dynamicSection->Details,
                'id' => $dynamicSection->id,
            ];
        }
        return $this->sendResponse($data, 'Data fetched successfully', 200);
    }


    public function updatedynamicsection(Request $request)
    {
        $id = $request->id;
        $data = [
            'HeadingId' => $request->HeadingId,
            'SrNo' => $request->SrNo,
            'Heading' => $request->Heading,
            'Details' => $request->Details,
        ];


        $Dynamic_sections = Dynamic_sections::where('id', $id)->update($data);
        return $this->sendResponse($Dynamic_sections, "sucessfully updated", 200);
    }


    public function deletedynamicsection(Request $request)
    {
        $id = $request->id;
        $Dynamic_sections = Dynamic_sections::where('id', $id)->delete();
        return $this->sendResponse($Dynamic_sections, "Successfully deleted", 200);
    }


    public function savedynamicsectionmaster(Request $request)
    {

        $created_by = Auth::user()->id;

        if ($request->user()->tokenCan('type:employee')) {
            $branch = getBranchidofemplyee($created_by);
            $parent_admin_id = getAdminIdIfEmployeeLoggedIn1($created_by);
        }

        if ($request->user()->tokenCan('type:admin')) {
            if (Auth::user()->role == 'admin') {
                $branch = 0;
            } else {
                $branch = getBranchidofadmin($created_by);
            }

            $parent_admin_id = getmainAdminIdIfAdminLoggedIn($created_by);
        }
        $data = [
            'admin_id' => $parent_admin_id,
            'SrNo' => $request->SrNo,
            'Heading' => $request->Heading,
            'Details' => $request->Details,
            'admin_branch_id' => $branch,
        ];

        $data['created_by'] = $created_by;
        $id = $request->id;
        $dynamicSections = Dynamic_sections_master::updateOrCreate(['id' => $id], $data);
        $data['id'] = $dynamicSections->id;
        // $dynamicSections = Dynamic_sections_master::where('Heading', $request->Heading)->first();

        // $data = [
        //   'admin_id' => $parent_admin_id,
        //   'SrNo'      => $request->SrNo,
        //   'Heading'   => $request->Heading,
        //   'Details'   => $request->Details,
        //   'admin_branch_id'   => $branch,
        // ];


        // if (!isset($dynamicSections)) {


        //   $data['created_by'] = $created_by;
        //   $Dynamic_sections = Dynamic_sections_master::create($data);
        // } else {


        //   $data['updated_by'] = $created_by;
        //   $Dynamic_sections = Dynamic_sections_master::where('id', $dynamicSections->id)->update($data);
        // }

        return $this->sendResponse($data, "sucessfully inserted", 200);
    }





    // public function savedynamicsectionmaster(Request $request)
    // {

    //   $created_by = Auth::user()->id;

    //   if ($request->user()->tokenCan('type:employee')) {
    //     $branch = getBranchidofemplyee($id);
    //     $parent_admin_id    = getAdminIdIfEmployeeLoggedIn1($created_by);
    //   }

    //   if ($request->user()->tokenCan('type:admin')) {
    //     if (Auth::user()->role == 'admin') {
    //       $branch = 0;
    //     } else {
    //       $branch = getBranchidofadmin($created_by);
    //     }

    //     $parent_admin_id = getmainAdminIdIfAdminLoggedIn($created_by);
    //   }

    //   $dynamicSections = Dynamic_sections_master::where('Heading', $request->Heading)->first();

    //   $data = [
    //     'admin_id' => $parent_admin_id,
    //     'SrNo'      => $request->SrNo,
    //     'Heading'   => $request->Heading,
    //     'Details'   => $request->Details,
    //     'admin_branch_id'   => $branch,
    //   ];


    //   if (!isset($dynamicSections)) {


    //     $data['created_by'] = $created_by;
    //     $Dynamic_sections = Dynamic_sections_master::create($data);
    //   } else {


    //     $data['updated_by'] = $created_by;
    //     $Dynamic_sections = Dynamic_sections_master::where('id', $dynamicSections->id)->update($data);
    //   }

    //   return $this->sendResponse($data, "sucessfully inserted", 200);
    // }


    public function getdynamicsectionmasterdata(Request $request)
    {
        $created_by = Auth::user()->id;

        if ($request->user()->tokenCan('type:employee')) {
            $branch = getBranchidofemplyee($id);
            $parent_admin_id = getAdminIdIfEmployeeLoggedIn1($created_by);
        }

        if ($request->user()->tokenCan('type:admin')) {
            if (Auth::user()->role == 'admin') {
                $branch = 0;
            } else {
                $branch = getBranchidofadmin($created_by);
            }

            $parent_admin_id = getmainAdminIdIfAdminLoggedIn($created_by);
        }

        $dynamicSections = Dynamic_sections_master::where(['admin_id' => $parent_admin_id]);

        if ($branch != 0) {

            $dynamicSections = $dynamicSections->where('admin_branch_id', $branch);
        }

        $dynamicSections = $dynamicSections->orderby('SrNo', 'asc')->get();

        $data = [];
        foreach ($dynamicSections as $dynamicSection) {
            $data[] = [
                'id' => $dynamicSection->id,
                'SrNo' => $dynamicSection->SrNo,
                'Heading' => $dynamicSection->Heading,
                'Details' => $dynamicSection->Details
            ];
        }
        return $this->sendResponse($data, 'Data fetched successfully', 200);
    }


    public function updatedynamicsectionmaster(Request $request)
    {
        $id = $request->id;
        $data = [
            'HeadingId' => $request->HeadingId,
            'SrNo' => $request->SrNo,
            'Heading' => $request->Heading,
            'Details' => $request->Details,
            'inspection_id' => $request->job_id,
        ];


        $Dynamic_sections = Dynamic_sections_master::where('id', $id)->update($data);
        return $this->sendResponse($Dynamic_sections, "sucessfully updated", 200);
    }


    public function deletedynamicsectionmaster(Request $request)
    {
        $id = $request->id;
        $Dynamic_sections = Dynamic_sections_master::where('id', $id)->forceDelete();

        return $this->sendResponse($Dynamic_sections, "Successfully deleted", 200);
    }


    public function formatteddynamicsection($job_id)
    {


        $values = Dynamic_sections::where('inspection_id', $job_id)->get();


        $data = [];
        foreach ($values as $valueall) {

            if ($valueall->section_type == 'master_section') {


                $data[] = [
                    'SrNo' => $valueall->SrNo,
                    'master_section' => $valueall->Heading,
                    'add_details' => $valueall->Details,
                    'add_Report' => ($valueall->add_Report == 0) ? false : true
                ];
            }
            if ($valueall->section_type == 'report_section') {

                $data[] = [
                    'SrNo' => $valueall->SrNo,
                    'report_section' => $valueall->Heading,
                    'add_details' => $valueall->Details
                ];
            }
        }

        return json_encode($data);
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

}
