<?php

namespace App\Http\Controllers\MotorSurvey;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InspectionPolicyDetail;
use App\Models\Admin_ms;
use App\Models\Feebill_report;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ReportsController extends BaseController
{
    
    public function previewReports(Request $request){
        $validator = Validator::make($request->all(),[
            'inspection_id' => 'required|integer',
            'report_type' => 'required|string',
        ]);
        if($validator->fails()){
            $errorMessages = $validator->errors()->all();
            return $this->sendError($errorMessages[0]);
        }
        $logged_in_id  = Auth::user()->id;
        if ($request->user()->tokenCan('type:employee')) {
          $parent_admin_id = getAdminIdIfEmployeeLoggedIn1($logged_in_id);
        } else {
          $parent_admin_id = getAdminIdIfAdminLoggedIn1($logged_in_id);
        }
        $main_admin_id = getmainAdminIdIfAdminLoggedIn($parent_admin_id);
       
        $adminHeaderFooter = Admin_ms::find($main_admin_id);

        $letter_head_img = "";
        if (isset($adminHeaderFooter->letter_head_img) && $adminHeaderFooter->letter_head_img != "") {
           $letter_head_img =  config('app.imageurl')."".$adminHeaderFooter->letter_head_img;
        }

        $letter_footer_img = "";
        if (isset($adminHeaderFooter->letter_footer_img) && $adminHeaderFooter->letter_footer_img != "") {
           $letter_footer_img =  config('app.imageurl')."".$adminHeaderFooter->letter_footer_img;
        }

        $signature_img = "";
        if (isset($adminHeaderFooter->signature_img) && $adminHeaderFooter->signature_img != "") {
           $signature_img =  config('app.imageurl')."".$adminHeaderFooter->signature_img;
        }
        //dd($letter_head_img);


        $policyDeatils = InspectionPolicyDetail::leftJoin('tbl_ms_inspection_vehicle_detail','tbl_ms_inspection_policy_detail.inspection_id','=','tbl_ms_inspection_vehicle_detail.inspection_id')
        ->leftJoin('tbl_ms_accident_cause','tbl_ms_inspection_policy_detail.inspection_id','=','tbl_ms_accident_cause.inspection_id')
        ->leftJoin('tbl_ms_client_branches','tbl_ms_inspection_policy_detail.client_id','=','tbl_ms_client_branches.id')
        ->leftJoin('tbl_ms_inspection_accident_detail','tbl_ms_inspection_policy_detail.inspection_id','=','tbl_ms_inspection_accident_detail.inspection_id')
        ->where('tbl_ms_inspection_policy_detail.inspection_id',$request->inspection_id)->first();
        if (is_null($policyDeatils)) {
           return "not found";
        }

        $pdf = PDF::loadView('preview-reports.survey_report',compact('policyDeatils','letter_head_img'));

                // Set A4 paper size (default is 'letter')
        $pdf->setPaper('a4');

        // You can also set custom width and height if needed
        // $pdf->setPaper('a4', 'landscape'); // For landscape orientation

        // Output the PDF or download it
        return $pdf->stream('final-survey-report.pdf');

    }

    public function feeBillReports(Request $request){
        $validator = Validator::make($request->all(),[
            'inspection_id' => 'required|integer',
        ]);
        if($validator->fails()){
            $errorMessages = $validator->errors()->all();
            return $this->sendError($errorMessages[0]);
        }
        $logged_in_id  = Auth::user()->id;
        if ($request->user()->tokenCan('type:employee')) {
          $parent_admin_id = getAdminIdIfEmployeeLoggedIn1($logged_in_id);
        } else {
          $parent_admin_id = getAdminIdIfAdminLoggedIn1($logged_in_id);
        }
        $main_admin_id = getmainAdminIdIfAdminLoggedIn($parent_admin_id);
       
        $adminHeaderFooter = Admin_ms::find($main_admin_id);

        $letter_head_img = "";
        if (isset($adminHeaderFooter->letter_head_img) && $adminHeaderFooter->letter_head_img != "") {
           $letter_head_img =  config('app.imageurl')."".$adminHeaderFooter->letter_head_img;
        }

        $letter_footer_img = "";
        if (isset($adminHeaderFooter->letter_footer_img) && $adminHeaderFooter->letter_footer_img != "") {
           $letter_footer_img =  config('app.imageurl')."".$adminHeaderFooter->letter_footer_img;
        }

        $signature_img = "";
        if (isset($adminHeaderFooter->signature_img) && $adminHeaderFooter->signature_img != "") {
           $signature_img =  config('app.imageurl')."".$adminHeaderFooter->signature_img;
        }
        //dd($letter_head_img);


        $policyDeatils = Feebill_report::leftJoin('tbl_ms_inspection_policy_detail','tbl_ms_inspection_feebill_report.inspection_id','=','tbl_ms_inspection_policy_detail.inspection_id')
        ->leftJoin('tbl_ms_accident_cause','tbl_ms_inspection_policy_detail.inspection_id','=','tbl_ms_accident_cause.inspection_id')
        ->leftJoin('tbl_ms_inspection_vehicle_detail','tbl_ms_inspection_policy_detail.inspection_id','=','tbl_ms_inspection_vehicle_detail.inspection_id')
        ->leftJoin('tbl_ms_client_branches','tbl_ms_inspection_policy_detail.client_id','=','tbl_ms_client_branches.id')
        ->leftJoin('tbl_ms_client_branches as app_tbl','tbl_ms_inspection_policy_detail.appointing_office_code','=','app_tbl.id')
        ->leftJoin('tbl_ms_client_branches as oper_tbl','tbl_ms_inspection_policy_detail.operating_office_code','=','oper_tbl.id')
        ->leftJoin('tbl_ms_inspection_accident_detail','tbl_ms_inspection_policy_detail.inspection_id','=','tbl_ms_inspection_accident_detail.inspection_id')
        ->leftJoin('tbl_ms_workshop_branch','tbl_ms_inspection_accident_detail.workshop_branch_id','=','tbl_ms_workshop_branch.id')
        ->where('tbl_ms_inspection_feebill_report.inspection_id',$request->inspection_id)->first();
        if (is_null($policyDeatils)) {
           return "not found";
        }
        //dd($policyDeatils->surveyFee);
        $pdf = PDF::loadView('preview-reports.fee-bill-reports',compact('policyDeatils','letter_head_img','adminHeaderFooter'));

                // Set A4 paper size (default is 'letter')
        $pdf->setPaper('a4');

        // You can also set custom width and height if needed
        // $pdf->setPaper('a4', 'landscape'); // For landscape orientation

        // Output the PDF or download it
        return $pdf->stream('fee-bill-report.pdf');
    }
}
