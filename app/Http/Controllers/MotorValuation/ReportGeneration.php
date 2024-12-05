<?php

namespace App\Http\Controllers\MotorValuation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Msbranchcontact;
use App\Mail\WelcomeEmail;
use App\Models\Jobms;
use App\Models\Inspection;
use App\Models\Branch;
use App\Models\InspectionPolicyDetail;
use App\Models\Feebill_report;
use App\Models\Dynamic_sections;
use App\Models\InspectionAttachment;
use App\Models\ReInsepection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Workshopbranchms;
use App\Models\Manuallupload;
use Illuminate\Support\Facades\Mail;
use Mpdf\Mpdf;
use PDF;
use App\Models\TaxSetting;
use App\Models\{Admin_ms, AssismentDetailList, AssismentDetail, InspectionEstimatesDetailsModel};
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController;
use App\Traits\S3UploadTrait;
use App\Models\CabinBodyTaxSettingModel;
class ReportGeneration extends BaseController
{
    use S3UploadTrait;

    public function index(Request $req)
    {
        $inspection_id = $req->inspection_id ?? 0;
        if(!empty($inspection_id) && is_numeric($inspection_id)) {
            $inspection = Inspection::where('id', $inspection_id)->first();
            if (!$inspection) {
                return $this->sendError('Details not saved yet');
            }
            $admin_id = $inspection->admin_id;
            $admin_branch_id = $inspection->admin_branch_id;

            if (Branch::where('id', $admin_branch_id)->exists() && !empty(Branch::where('id', $admin_branch_id)->first()->letter_head_img)) {
                $adminHeaderFooter = Branch::select(
                    'tbl_ms_admin_branch.*',
                    'tbl_ms_admin_branch.letter_head_img as branch_letter_head_img',
                    'tbl_ms_admin_branch.letter_footer_img as branch_letter_footer_img',
                    'tbl_ms_admin_branch.signature_img as branch_signature_img',
                    'tbl_ms_admin.*'
                )
                    ->leftJoin('tbl_ms_admin', 'tbl_ms_admin.id', '=', 'tbl_ms_admin_branch.admin_id')->where('tbl_ms_admin_branch.id', $admin_branch_id)->first();
            } else {
                $adminHeaderFooter = Admin_ms::where('id', $admin_id)->first();
            }
            $letter_head_img = "";
            if (isset($adminHeaderFooter->branch_letter_head_img) && $adminHeaderFooter->branch_letter_head_img != "") {
                $letter_head_img = $adminHeaderFooter->branch_letter_head_img;
            } else {
                $letter_head_img = $adminHeaderFooter->letter_head_img;
            }

            $letter_footer_img = "";
            if (isset($adminHeaderFooter->branch_letter_footer_img) && $adminHeaderFooter->branch_letter_footer_img != "") {
                $letter_footer_img = $adminHeaderFooter->branch_letter_footer_img;
            } else {
                $letter_footer_img = $adminHeaderFooter->letter_footer_img;
            }

            $signature_img = "";
            if (isset($adminHeaderFooter->branch_signature_img) && $adminHeaderFooter->branch_signature_img != "") {
                $signature_img = $adminHeaderFooter->branch_signature_img;
            } else {
                $signature_img = $adminHeaderFooter->signature_img;
            }

            $type = $req->type ?? null;
            $jid = $inspection_id;
            $path = public_path() . '/ila/job' . $jid . '.pdf';
            if (file_exists($path)) {
                unlink($path);
            }
            $footerImage = (!empty($letter_footer_img) ? '<img src="' . asset('public/storage/' . $letter_footer_img) . '" style="height: auto;">' : '');
            $mpdf = new Mpdf(['c', 'A4-L', 'margin_left' => 10, 'margin_right' => 10, 'margin_top' => 10, 'margin_bottom' => 10, 'margin_header' => 0, 'margin_footer' => 0, 'curlAllowUnsafeSslRequests' => true, 'font_family' => 'verdana']);
            if (!empty($type) && $type == 'ilarwol') {
                $mpdf->SetHTMLFooter('<div class="footer-container">' . $footerImage . '</div>');
            }
            $mpdf->simpleTables = true;
            $mpdf->packTableData = true;
            $html = View('ilapdf1', $inspection, compact('type', 'letter_head_img', 'signature_img', 'adminHeaderFooter'));
            $mpdf->WriteHTML($html);
            $mpdf->Output('ILAReport.pdf', 'I');
        } else {
            return $this->sendError('Inspection Id required');
        }
    }


    public function viewpdf(Request $req)
    {
        $insepection_id = $req->inspection_id;
        $type = "";

        $data['insepection'] = Inspection::where('id', $insepection_id)->first()->toArray();
        $data['files'] = Manuallupload::where('inspection_id', $insepection_id)->get();
        $data['assisment'] = AssismentDetailList::where(['inspection_id' => $insepection_id])->get();
        //   $data['assisment']=DB::table('tbl_ms_assessment_detail_list')->where('',)->get();
        $data['taxsetting'] = TaxSetting::where('inspection_id', $insepection_id)->first();
        $job = Inspection::where('id', $insepection_id)->first();


        /* if(!$data['taxsetting']){
          return $this->sendError('Details not saved yet');
      }*/

        $admin_id = $job->admin_id;

        $admin_details = Admin_ms::where('id', $admin_id)->first();
        $data['logo'] = (!empty($admin_details->letter_head_img)) ? $admin_details->letter_head_img : '';
        $data['email'] = $admin_details->email;


        $path = public_path() . '/was/job' . $insepection_id . '.pdf';

        // check if the file exists
        if (Storage::exists($path)) {
            // delete the file
            Storage::delete($path);
        }

        $mpdf = new Mpdf(['c', 'A4-L', 'curlAllowUnsafeSslRequests' => true]);
        try {
            $mpdf->WriteHTML(View('pdfview', $data));
        } catch (\Exception $th) {
            return $th;
        }

        $mpdf->Output($path);
        $mpdf->Output('WorkApprovalSheet.pdf', 'I');
    }


    public function previewReports(Request $request)
    {
        ini_set('memory_limit', '2048M');
        ini_set("pcre.backtrack_limit", "5000000");

        $validator = Validator::make($request->all(), [
            'inspection_id' => 'required|integer',
            'report_type' => 'required|string',
        ]);
        if ($validator->fails()) {
            $errorMessages = $validator->errors()->all();
            return $this->sendError($errorMessages[0]);
        }

        $finalWithoutAss = ($request->report_type == 'final_without_ass_report') ? 'final_without_ass_report' : '';
        $finalAssessment = ($request->report_type == 'final_ass_report') ? 'final_ass_report' : '';

        if ($finalAssessment == 'final_ass_report' || $finalWithoutAss == 'final_without_ass_report') {

            $job = Inspection::where('id', $request->inspection_id)->first();

            $admin_id = $job->admin_id;
            $admin_branch_id = $job->admin_branch_id;

            if (Branch::where('id', $admin_branch_id)->exists() && !empty(Branch::where('id', $admin_branch_id)->first()->letter_head_img)) {
                $adminHeaderFooter = Branch::select(
                    'tbl_ms_admin_branch.*',
                    'tbl_ms_admin_branch.letter_head_img as branch_letter_head_img',
                    'tbl_ms_admin_branch.letter_footer_img as branch_letter_footer_img',
                    'tbl_ms_admin_branch.signature_img as branch_signature_img',
                    'tbl_ms_admin.*'
                )
                    ->leftJoin('tbl_ms_admin', 'tbl_ms_admin.id', '=', 'tbl_ms_admin_branch.admin_id')->where('tbl_ms_admin_branch.id', $admin_branch_id)->first();
            } else {
                $adminHeaderFooter = Admin_ms::where('id', $admin_id)->first();
            }


            $letter_head_img = "";
            if (isset($adminHeaderFooter->branch_letter_head_img) && $adminHeaderFooter->branch_letter_head_img != "") {
                $letter_head_img = $adminHeaderFooter->branch_letter_head_img;
            } else {
                $letter_head_img = $adminHeaderFooter->letter_head_img;
            }

            $letter_footer_img = "";
            if (isset($adminHeaderFooter->branch_letter_footer_img) && $adminHeaderFooter->branch_letter_footer_img != "") {
                $letter_footer_img = $adminHeaderFooter->branch_letter_footer_img;
            } else {
                $letter_footer_img = $adminHeaderFooter->letter_footer_img;
            }

            $signature_img = "";
            if (isset($adminHeaderFooter->branch_signature_img) && $adminHeaderFooter->branch_signature_img != "") {
                $signature_img = $adminHeaderFooter->branch_signature_img;
            } else {
                $signature_img = $adminHeaderFooter->signature_img;
            }

            $policyDetails = InspectionPolicyDetail::leftJoin('tbl_ms_accident_cause', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_accident_cause.inspection_id')
                ->leftJoin('tbl_ms_client_branches', 'tbl_ms_inspection_policy_detail.client_branch_id', '=', 'tbl_ms_client_branches.id')
                ->leftJoin('tbl_ms_client_branches as app_tbl', 'tbl_ms_inspection_policy_detail.appointing_office_code', '=', 'app_tbl.id')
                ->leftJoin('tbl_ms_client_branches as oper_tbl', 'tbl_ms_inspection_policy_detail.operating_office_code', '=', 'oper_tbl.id')
                ->leftJoin('tbl_ms_assisment_calculations', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_assisment_calculations.inspection_id')
                ->leftJoin('tbl_ms_inspection_accident_detail', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_inspection_accident_detail.inspection_id')
                ->leftJoin('tbl_ms_inspection_vehicle_detail', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_inspection_vehicle_detail.inspection_id')
                ->leftJoin('tbl_ms_workshop_branch', 'tbl_ms_inspection_accident_detail.workshop_branch_id', '=', 'tbl_ms_workshop_branch.id')
                ->with('get_cabin_load_body_ass', 'get_cabin_load_body_tax')
                ->select(
                    'tbl_ms_inspection_policy_detail.*',
                    'tbl_ms_inspection_vehicle_detail.chassis_no as vehicle_chassis_no', // Alias the conflicting column
                    'tbl_ms_inspection_vehicle_detail.engine_no as vehicle_engine_no', // Alias the conflicting column
                    'tbl_ms_inspection_vehicle_detail.*',
                    'tbl_ms_assisment_calculations.*',
                    'tbl_ms_accident_cause.*',
                    'tbl_ms_inspection_accident_detail.chassis_no as accident_chassis_no', // Alias the conflicting column
                    'tbl_ms_inspection_accident_detail.engine_no as accident_engine_no', // Alias the conflicting column
                    'tbl_ms_inspection_accident_detail.estimate_no as estimate_no',
                    'tbl_ms_inspection_accident_detail.date_of_estimate as date_of_estimate',
                    'tbl_ms_inspection_accident_detail.*',
                    'tbl_ms_workshop_branch.workshop_branch_name',
                    'tbl_ms_workshop_branch.address as workshop_branch_address',
                    'tbl_ms_client_branches.office_name',
                    'tbl_ms_client_branches.office_address',
                    'app_tbl.office_name as appointing_office_name',
                    'app_tbl.office_address as appointing_office_address',
                    'oper_tbl.office_name as operating_office_name',
                    'oper_tbl.office_address as operating_office_address'
                )
                ->where('tbl_ms_inspection_policy_detail.inspection_id', $request->inspection_id)
                ->first();
                // dd($policyDetails->toArray());


            $dynamicSection = Dynamic_sections::where('inspection_id', $request->inspection_id)->pluck('Details', 'heading')->toArray();
            $inspectionAttachment = InspectionAttachment::where('inspection_id', $request->inspection_id)->get()->toArray();
            $lossAssessment = AssismentDetail::join('tbl_ms_assisment_calculations', 'tbl_ms_assisment_calculations.inspection_id', '=', 'tbl_ms_assessment_details.inspection_id')
                ->leftJoin('tbl_ms_assessmentsheet_settings', 'tbl_ms_assessment_details.inspection_id', '=', 'tbl_ms_assessmentsheet_settings.inspection_id')
                ->leftJoin('tbl_ms_tax_dep_settings', 'tbl_ms_assessment_details.inspection_id', '=', 'tbl_ms_tax_dep_settings.inspection_id')
                ->leftJoin('tbl_ms_final_report_comment', 'tbl_ms_assessment_details.inspection_id', '=', 'tbl_ms_final_report_comment.inspection_id')
                ->where('tbl_ms_assessment_details.inspection_id', $request->inspection_id)
                ->get()
                ->toArray();

            if (is_null($policyDetails)) {
                return "Inspection Id not found";
            }

            $footerImage = (!empty($letter_footer_img) ? '<img src="' . asset('public/storage/' . $letter_footer_img) . '" style="height: auto;">' : '');
            $mpdf = new Mpdf(['c', 'A4-L', 'margin_footer' => 4, 'curlAllowUnsafeSslRequests' => true, 'setAutoBottomMargin' => 'stretch']);
            $mpdf->SetHTMLFooter('<div class="footer-container">' . $footerImage . '</div>');
            $mpdf->setBasePath(url('/'));
            $header = '<table width="100%">
                                  <tr>
                                     <td width="40%">' . (!empty($policyDetails->inspection_reference_no) ? $policyDetails->inspection_reference_no : '-') . '</td>
                                     <td width="25%" align="center">' . (!empty($policyDetails->registration_no) ? $policyDetails->registration_no : '-') . '</td>
                                     <td width="25%" align="right">Page {PAGENO} of {nb}</td>
                                  </tr>
                            </table>';

            $header .= '<hr style="border: 2px solid #000; margin: 0px;">'; // Add a horizontal line as a divider
            // $multiTaxes        = CabinBodyTaxSettingModel::where('inspection_id', request()->get("inspection_id"))->first()->toArray();

            $multiTaxes=[];

            if(!empty($policyDetails->get_cabin_load_body_tax()->get()->toArray())){

                $multiTaxes=$policyDetails->get_cabin_load_body_tax()->get()->toArray()[0];
            }
            $estimates = InspectionEstimatesDetailsModel::where(['inspection_id' => $request->inspection_id])->first();
            if(!empty($estimates)){
                $estimates = $estimates->toArray()["details"];
            }
            try {
                // Generate the first page and set the header
                $mpdf->AddPage('', '', '', '', '', '', '', '', '', '', '', '', '', '');

                $html = View('preview-reports.final-report.final_report', compact('policyDetails', 'letter_head_img', 'signature_img', 'finalWithoutAss', 'finalAssessment', 'dynamicSection', 'inspectionAttachment', 'lossAssessment', 'adminHeaderFooter','multiTaxes','estimates'));
                // return $html;
                $mpdf->SetHeader($header, 0);
                $mpdf->WriteHTML($html);

            } catch (\Exception $th) {
                return $th;
            }
            // Output the PDF
            $mpdf->Output('final-survey-report.pdf', 'I');
        } elseif ($request->report_type == "bill_check_report1") {

            return $this->billCheckReports($request->inspection_id);

        } elseif ($request->report_type == "scrutiny_report") {

            return $this->scrutinySheetReports($request->inspection_id);
        } elseif ($request->report_type == "motor_analysis_sheet") {

            return $this->motorAnalysisSheet($request->inspection_id);

        } else {
            return $this->sendError("Report Type Not Found. !!");
        }

    }

    public function feeBillReports(Request $request)
    {
        ini_set('memory_limit', '2048M');
        ini_set("pcre.backtrack_limit", "5000000");
        $validator = Validator::make($request->all(), [
            'inspection_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            $errorMessages = $validator->errors()->all();
            return $this->sendError($errorMessages[0]);
        }

        $job = Inspection::with('getClient')->where('id', $request->inspection_id)->first();

        $admin_id = $job->admin_id;
        $admin_branch_id = $job->admin_branch_id;

        if (Branch::where('id', $admin_branch_id)->exists() && !empty(Branch::where('id', $admin_branch_id)->first()->letter_head_img)) {
            $adminHeaderFooter = Branch::select(
                'tbl_ms_admin_branch.*',
                'tbl_ms_admin_branch.letter_head_img as branch_letter_head_img',
                'tbl_ms_admin_branch.letter_footer_img as branch_letter_footer_img',
                'tbl_ms_admin_branch.signature_img as branch_signature_img',
                'tbl_ms_admin.*'
            )
                ->leftJoin('tbl_ms_admin', 'tbl_ms_admin.id', '=', 'tbl_ms_admin_branch.admin_id')->where('tbl_ms_admin_branch.id', $admin_branch_id)->first();
        } else {
            $adminHeaderFooter = Admin_ms::where('id', $admin_id)->first();
        }

        if (isset($adminHeaderFooter->branch_letter_head_img) && $adminHeaderFooter->branch_letter_head_img != "") {
            $letter_head_img = $adminHeaderFooter->branch_letter_head_img;
        } else {
            $letter_head_img = $adminHeaderFooter->letter_head_img;
        }

        if (isset($adminHeaderFooter->branch_letter_footer_img) && $adminHeaderFooter->branch_letter_footer_img != "") {
            $letter_footer_img = $adminHeaderFooter->branch_letter_footer_img;
        } else {
            $letter_footer_img = $adminHeaderFooter->letter_footer_img;
        }

        if (isset($adminHeaderFooter->branch_signature_img) && $adminHeaderFooter->branch_signature_img != "") {
            $signature_img = $adminHeaderFooter->branch_signature_img;
        } else {
            $signature_img = $adminHeaderFooter->signature_img;
        }

        $policyDetails = Feebill_report::with('get_bank_details')
            ->leftJoin('tbl_ms_inspection_policy_detail', 'tbl_ms_inspection_feebill_report.inspection_id', '=', 'tbl_ms_inspection_policy_detail.inspection_id')
            ->leftJoin('tbl_ms_accident_cause', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_accident_cause.inspection_id')
            ->leftJoin('tbl_ms_inspection_vehicle_detail', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_inspection_vehicle_detail.inspection_id')
            ->leftJoin('tbl_ms_client_branches', 'tbl_ms_inspection_policy_detail.client_branch_id', '=', 'tbl_ms_client_branches.id')
            ->leftJoin('tbl_ms_client_branches as app_tbl', 'tbl_ms_inspection_policy_detail.appointing_office_code', '=', 'app_tbl.id')
            ->leftJoin('tbl_ms_client_branches as oper_tbl', 'tbl_ms_inspection_policy_detail.operating_office_code', '=', 'oper_tbl.id')
            ->leftJoin('tbl_ms_inspection_accident_detail', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_inspection_accident_detail.inspection_id')
            ->leftJoin('tbl_ms_workshop_branch', 'tbl_ms_inspection_accident_detail.workshop_branch_id', '=', 'tbl_ms_workshop_branch.id')
            ->select('tbl_ms_inspection_feebill_report.*', 'tbl_ms_inspection_policy_detail.inspection_id', 'tbl_ms_inspection_policy_detail.inspection_reference_no', 'tbl_ms_inspection_policy_detail.claim_type', 'tbl_ms_inspection_policy_detail.reportGeneratedOn', 'tbl_ms_inspection_policy_detail.claim_no', 'tbl_ms_inspection_policy_detail.policy_no', 'tbl_ms_inspection_policy_detail.policy_valid_from', 'tbl_ms_inspection_policy_detail.policy_valid_to', 'tbl_ms_inspection_policy_detail.sum_insured', 'tbl_ms_inspection_policy_detail.policy_type', 'tbl_ms_inspection_policy_detail.status_of_64vb', 'tbl_ms_inspection_policy_detail.status_of_pre_insp', 'tbl_ms_inspection_policy_detail.status_of_NCB', 'tbl_ms_inspection_policy_detail.payment_mode', 'tbl_ms_inspection_policy_detail.settlement_type', 'tbl_ms_inspection_policy_detail.client_id', 'tbl_ms_inspection_policy_detail.client_branch_id', 'tbl_ms_inspection_policy_detail.appointing_office_code', 'tbl_ms_inspection_policy_detail.operating_officer', 'tbl_ms_inspection_policy_detail.insured_name', 'tbl_ms_inspection_policy_detail.insured_address', 'tbl_ms_inspection_policy_detail.insured_mobile_no', 'tbl_ms_inspection_policy_detail.registured_owner', 'tbl_ms_inspection_policy_detail.bank_name', 'tbl_ms_inspection_policy_detail.bank_address', 'tbl_ms_inspection_policy_detail.account_no', 'tbl_ms_inspection_policy_detail.ifsc_code', 'tbl_ms_inspection_policy_detail.HPA', 'tbl_ms_inspection_policy_detail.insurer_name', 'tbl_ms_inspection_policy_detail.operating_office_code', 'tbl_ms_inspection_policy_detail.client_name', 'tbl_ms_inspection_policy_detail.thirdParty_insured_name', 'tbl_ms_inspection_policy_detail.thirdParty_insured_branch_name', 'tbl_ms_inspection_policy_detail.thirdParty_policy_no', 'tbl_ms_inspection_policy_detail.thirdParty_policy_valid_from', 'tbl_ms_inspection_policy_detail.thirdParty_policy_valid_to', 'tbl_ms_inspection_vehicle_detail.*', 'tbl_ms_accident_cause.*', 'tbl_ms_inspection_accident_detail.*', 'tbl_ms_workshop_branch.workshop_branch_name', 'tbl_ms_workshop_branch.address as workshop_branch_address', 'tbl_ms_workshop_branch.gst_no as workshop_gst_no', 'tbl_ms_client_branches.office_name', 'tbl_ms_client_branches.office_address', 'tbl_ms_client_branches.gst_no', 'app_tbl.office_name as appointing_office_name', 'app_tbl.office_address as appointing_office_address', 'app_tbl.gst_no as appointing_gst_no', 'oper_tbl.office_name as operating_office_name', 'oper_tbl.office_address as operating_office_address', 'oper_tbl.gst_no as operation_gst_no')
            ->where('tbl_ms_inspection_feebill_report.inspection_id', $request->inspection_id)->first();
        if (is_null($policyDetails)) {
            return "Inspection Id not found";
        }

        $footerImage = (!empty($letter_footer_img) ? '<img src="' . asset('public/storage/' . $letter_footer_img) . '" style="height: auto;">' : '');
        $mpdf = new Mpdf(['c', 'A4-L', 'margin_footer' => 4, 'curlAllowUnsafeSslRequests' => true, 'setAutoBottomMargin' => 'stretch']);
        $mpdf->SetHTMLFooter('<div class="footer-container">' . $footerImage . '</div>');
        $mpdf->setBasePath(url('/'));
        $header = '<table width="100%">
                                  <tr>
                                     <td width="40%">' . (!empty($policyDetails->inspection_reference_no) ? $policyDetails->inspection_reference_no : '-') . '</td>
                                     <td width="25%" align="center">' . (!empty($policyDetails->registration_no) ? $policyDetails->registration_no : '-') . '</td>
                                     <td width="25%" align="right">Page {PAGENO} of {nb}</td>
                                  </tr>
                            </table>';

        $header .= '<hr style="border: 2px solid #000; margin: 0px;">'; // Add a horizontal line as a divider
        try {
            // Generate the first page and set the header
            $mpdf->AddPage('', '', '', '', '', '', '', '', '', '', '', '', '', '');
            $mpdf->SetHeader($header, 0);
            $html = View('preview-reports.fee-bill-reports', compact('policyDetails', 'letter_head_img', 'adminHeaderFooter', 'letter_footer_img', 'signature_img', 'job'));
//            echo $html; die;
            $mpdf->WriteHTML($html);

        } catch (\Exception $th) {
            return $th;
        }
        $mpdf->Output('fee-bill-report.pdf', 'I');
    }


    public function reinspectionReports(Request $request)
    {
        ini_set('memory_limit', '2048M');
        ini_set("pcre.backtrack_limit", "5000000");
        $validator = Validator::make($request->all(), [
            'inspection_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            $errorMessages = $validator->errors()->all();
            return $this->sendError($errorMessages[0]);
        }

        $job = Inspection::where('id', $request->inspection_id)->first();

        $admin_id = $job->admin_id;
        $admin_branch_id = $job->admin_branch_id;

        if (Branch::where('id', $admin_branch_id)->exists() && !empty(Branch::where('id', $admin_branch_id)->first()->letter_head_img)) {
            $adminHeaderFooter = Branch::select(
                'tbl_ms_admin_branch.*',
                'tbl_ms_admin_branch.letter_head_img as branch_letter_head_img',
                'tbl_ms_admin_branch.letter_footer_img as branch_letter_footer_img',
                'tbl_ms_admin_branch.signature_img as branch_signature_img',
                'tbl_ms_admin.*'
            )
                ->leftJoin('tbl_ms_admin', 'tbl_ms_admin.id', '=', 'tbl_ms_admin_branch.admin_id')->where('tbl_ms_admin_branch.id', $admin_branch_id)->first();
        } else {
            $adminHeaderFooter = Admin_ms::where('id', $admin_id)->first();
        }


        $letter_head_img = "";
        if (isset($adminHeaderFooter->branch_letter_head_img) && $adminHeaderFooter->branch_letter_head_img != "") {
            $letter_head_img = $adminHeaderFooter->branch_letter_head_img;
        } else {
            $letter_head_img = $adminHeaderFooter->letter_head_img;
        }

        $letter_footer_img = "";
        if (isset($adminHeaderFooter->branch_letter_footer_img) && $adminHeaderFooter->branch_letter_footer_img != "") {
            $letter_footer_img = $adminHeaderFooter->branch_letter_footer_img;
        } else {
            $letter_footer_img = $adminHeaderFooter->letter_footer_img;
        }

        $signature_img = "";
        if (isset($adminHeaderFooter->branch_signature_img) && $adminHeaderFooter->branch_signature_img != "") {
            $signature_img = $adminHeaderFooter->branch_signature_img;
        } else {
            $signature_img = $adminHeaderFooter->signature_img;
        }

        $reinspectionDetails = ReInsepection::leftJoin('tbl_ms_inspection_policy_detail', 'tbl_ms_re_insepections.inspection_id', '=', 'tbl_ms_inspection_policy_detail.inspection_id')
            ->leftJoin('tbl_ms_client_branches', 'tbl_ms_inspection_policy_detail.client_branch_id', '=', 'tbl_ms_client_branches.id')
            ->leftJoin('tbl_ms_assisment_calculations', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_assisment_calculations.inspection_id')
            ->leftJoin('tbl_ms_inspection_vehicle_detail', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_inspection_vehicle_detail.inspection_id')
            ->leftJoin('tbl_ms_inspection_accident_detail', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_inspection_accident_detail.inspection_id')
            ->select(
                'tbl_ms_re_insepections.*',
                'tbl_ms_inspection_policy_detail.*',
                'tbl_ms_inspection_accident_detail.date_time_accident',
                'tbl_ms_assisment_calculations.alltotalass',
                'tbl_ms_assisment_calculations.SalvageAmt',
                'tbl_ms_inspection_vehicle_detail.registration_no',
                'tbl_ms_inspection_vehicle_detail.chassis_no as vehicle_chassis_no', // Alias the conflicting column
                'tbl_ms_inspection_vehicle_detail.engine_no as vehicle_engine_no' // Alias the conflicting column
            )
            ->where('tbl_ms_re_insepections.inspection_id', $request->inspection_id)
            ->first();

        if (is_null($reinspectionDetails)) {
            return "Inspection Id not found";
        }
        $footerImage = (!empty($letter_footer_img) ? '<img src="' . asset('public/storage/' . $letter_footer_img) . '" style="height: auto;">' : '');
        $mpdf = new Mpdf(['c', 'A4-L', 'margin_footer' => 4, 'curlAllowUnsafeSslRequests' => true]);
        $mpdf->SetHTMLFooter('<div class="footer-container">' . $footerImage . '</div>');
        $mpdf->setBasePath(url('/'));
        $header = '<table width="100%">
                            <tr>
                                <td align="right">Page {PAGENO} of {nb}</td>
                            </tr>
                        </table>';
        $header .= '<hr style="border: 2px solid #000; margin: 0;">'; // Add a horizontal line as a divider
        try {
            // Generate the first page and set the header
            $mpdf->AddPage('', '', '', '', '', '', '', '', '', '', '', '', '', '');
            $mpdf->SetHeader($header, 'O');
            $mpdf->WriteHTML(View('preview-reports.reinspection-reports', compact('reinspectionDetails', 'letter_head_img', 'adminHeaderFooter', 'signature_img')));
        } catch (\Exception $th) {
            return $th;
        }

        $mpdf->Output('reinspection-report.pdf', 'I');
    }

    private function billCheckReports($insepectionId)
    {

        $job = Inspection::where('id', $insepectionId)->first();

        $admin_id = $job->admin_id;
        $admin_branch_id = $job->admin_branch_id;

        if (Branch::where('id', $admin_branch_id)->exists() && !empty(Branch::where('id', $admin_branch_id)->first()->letter_head_img)) {
            $adminHeaderFooter = Branch::select(
                'tbl_ms_admin_branch.*',
                'tbl_ms_admin_branch.letter_head_img as branch_letter_head_img',
                'tbl_ms_admin_branch.letter_footer_img as branch_letter_footer_img',
                'tbl_ms_admin_branch.signature_img as branch_signature_img',
                'tbl_ms_admin.*'
            )
                ->leftJoin('tbl_ms_admin', 'tbl_ms_admin.id', '=', 'tbl_ms_admin_branch.admin_id')->where('tbl_ms_admin_branch.id', $admin_branch_id)->first();
        } else {
            $adminHeaderFooter = Admin_ms::where('id', $admin_id)->first();
        }


        $letter_head_img = "";
        if (isset($adminHeaderFooter->branch_letter_head_img) && $adminHeaderFooter->branch_letter_head_img != "") {
            $letter_head_img = $adminHeaderFooter->branch_letter_head_img;
        } else {
            $letter_head_img = $adminHeaderFooter->letter_head_img;
        }

        $letter_footer_img = "";
        if (isset($adminHeaderFooter->branch_letter_footer_img) && $adminHeaderFooter->branch_letter_footer_img != "") {
            $letter_footer_img = $adminHeaderFooter->branch_letter_footer_img;
        } else {
            $letter_footer_img = $adminHeaderFooter->letter_footer_img;
        }

        $signature_img = "";
        if (isset($adminHeaderFooter->branch_signature_img) && $adminHeaderFooter->branch_signature_img != "") {
            $signature_img = $adminHeaderFooter->branch_signature_img;
        } else {
            $signature_img = $adminHeaderFooter->signature_img;
        }


        $policyDetails = InspectionPolicyDetail::leftJoin('tbl_ms_accident_cause', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_accident_cause.inspection_id')
            ->leftJoin('tbl_ms_client_branches', 'tbl_ms_inspection_policy_detail.client_branch_id', '=', 'tbl_ms_client_branches.id')
            ->leftJoin('tbl_ms_client_branches as app_tbl', 'tbl_ms_inspection_policy_detail.appointing_office_code', '=', 'app_tbl.id')
            ->leftJoin('tbl_ms_client_branches as oper_tbl', 'tbl_ms_inspection_policy_detail.operating_office_code', '=', 'oper_tbl.id')
            ->leftJoin('tbl_ms_assisment_calculations', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_assisment_calculations.inspection_id')
            ->leftJoin('tbl_ms_inspection_accident_detail', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_inspection_accident_detail.inspection_id')
            ->leftJoin('tbl_ms_inspection_vehicle_detail', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_inspection_vehicle_detail.inspection_id')
            ->leftJoin('tbl_ms_workshop_branch', 'tbl_ms_inspection_accident_detail.workshop_branch_id', '=', 'tbl_ms_workshop_branch.id')
            ->select(
                'tbl_ms_inspection_policy_detail.*',
                'tbl_ms_inspection_vehicle_detail.chassis_no as vehicle_chassis_no', // Alias the conflicting column
                'tbl_ms_inspection_vehicle_detail.engine_no as vehicle_engine_no', // Alias the conflicting column
                'tbl_ms_inspection_vehicle_detail.*',
                'tbl_ms_assisment_calculations.*',
                'tbl_ms_accident_cause.*',
                'tbl_ms_inspection_accident_detail.chassis_no as accident_chassis_no', // Alias the conflicting column
                'tbl_ms_inspection_accident_detail.engine_no as accident_engine_no', // Alias the conflicting column
                'tbl_ms_inspection_accident_detail.*',
                'tbl_ms_workshop_branch.workshop_branch_name',
                'tbl_ms_workshop_branch.address as workshop_branch_address',
                'tbl_ms_client_branches.office_name',
                'tbl_ms_client_branches.office_address',
                'app_tbl.office_name as appointing_office_name',
                'app_tbl.office_address as appointing_office_address',
                'oper_tbl.office_name as operating_office_name',
                'oper_tbl.office_address as operating_office_address'
            )
            ->where('tbl_ms_inspection_policy_detail.inspection_id', $insepectionId)
            ->first();

        $lossAssessment = AssismentDetail::join('tbl_ms_assisment_calculations', 'tbl_ms_assisment_calculations.inspection_id', '=', 'tbl_ms_assessment_details.inspection_id')
            ->leftJoin('tbl_ms_assessmentsheet_settings', 'tbl_ms_assessment_details.inspection_id', '=', 'tbl_ms_assessmentsheet_settings.inspection_id')
            ->leftJoin('tbl_ms_tax_dep_settings', 'tbl_ms_assessment_details.inspection_id', '=', 'tbl_ms_tax_dep_settings.inspection_id')
            ->leftJoin('tbl_ms_final_report_comment', 'tbl_ms_assessment_details.inspection_id', '=', 'tbl_ms_final_report_comment.inspection_id')
            ->leftJoin('tbl_ms_assessment_reports', 'tbl_ms_assessment_details.inspection_id', '=', 'tbl_ms_assessment_reports.inspection_id')
            ->where('tbl_ms_assessment_details.inspection_id', $insepectionId)
            ->get()
            ->toArray();

        if (is_null($policyDetails)) {
            return "Inspection Id not found";
        }

        $footerImage = (!empty($letter_footer_img) ? '<div><img src="' . asset('public/storage/' . $letter_footer_img) . '" style="height: auto;"></div>' : '');
        $mpdf = new Mpdf(['c', 'A4-L', 'margin_footer' => 4, 'curlAllowUnsafeSslRequests' => true, 'setAutoBottomMargin' => 'stretch']);
        $mpdf->setBasePath(url('/'));
        $header = '<table width="100%">
                                  <tr>
                                     <td width="100%" align="right">Page {PAGENO} of {nb}</td>
                                  </tr>
                            </table>';

        $header .= '<hr style="border: 2px solid #000; margin: 0px;">'; // Add a horizontal line as a divider
        $mpdf->SetHTMLFooter('<div class="footer-container">' . $footerImage . '</div>');
        try {
            // Generate the first page and set the header
            $mpdf->AddPage('', '', '', '', '', '', '', '', '', '', '', '', '', '');
            $mpdf->SetHeader($header, 0);
            $mpdf->WriteHTML(View('preview-reports.bill-check.bill-check-reports', compact('policyDetails', 'letter_head_img', 'signature_img', 'adminHeaderFooter', 'lossAssessment')));
        } catch (\Exception $th) {
            return $th;
        }

        $mpdf->Output('bill-check-report.pdf', 'I');
    }

    private function motorAnalysisSheet($insepectionId)
    {

        $job = Inspection::where('id', $insepectionId)->first();

        $admin_id = $job->admin_id;
        $admin_branch_id = $job->admin_branch_id;

        if (Branch::where('id', $admin_branch_id)->exists() && !empty(Branch::where('id', $admin_branch_id)->first()->letter_head_img)) {
            $adminHeaderFooter = Branch::select(
                'tbl_ms_admin_branch.*',
                'tbl_ms_admin_branch.letter_head_img as branch_letter_head_img',
                'tbl_ms_admin_branch.letter_footer_img as branch_letter_footer_img',
                'tbl_ms_admin_branch.signature_img as branch_signature_img',
                'tbl_ms_admin.*'
            )
                ->leftJoin('tbl_ms_admin', 'tbl_ms_admin.id', '=', 'tbl_ms_admin_branch.admin_id')->where('tbl_ms_admin_branch.id', $admin_branch_id)->first();
        } else {
            $adminHeaderFooter = Admin_ms::where('id', $admin_id)->first();
        }


        $letter_head_img = "";
        if (isset($adminHeaderFooter->branch_letter_head_img) && $adminHeaderFooter->branch_letter_head_img != "") {
            $letter_head_img = $adminHeaderFooter->branch_letter_head_img;
        } else {
            $letter_head_img = $adminHeaderFooter->letter_head_img;
        }

        $letter_footer_img = "";
        if (isset($adminHeaderFooter->branch_letter_footer_img) && $adminHeaderFooter->branch_letter_footer_img != "") {
            $letter_footer_img = $adminHeaderFooter->branch_letter_footer_img;
        } else {
            $letter_footer_img = $adminHeaderFooter->letter_footer_img;
        }

        $signature_img = "";
        if (isset($adminHeaderFooter->branch_signature_img) && $adminHeaderFooter->branch_signature_img != "") {
            $signature_img = $adminHeaderFooter->branch_signature_img;
        } else {
            $signature_img = $adminHeaderFooter->signature_img;
        }



        $policyDetails = InspectionPolicyDetail::with('get_appointing_office', 'get_insurer')->leftJoin('tbl_ms_accident_cause', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_accident_cause.inspection_id')
            ->leftJoin('tbl_ms_client_branches', 'tbl_ms_inspection_policy_detail.client_branch_id', '=', 'tbl_ms_client_branches.id')
            ->leftJoin('tbl_ms_client_branches as app_tbl', 'tbl_ms_inspection_policy_detail.appointing_office_code', '=', 'app_tbl.id')
            ->leftJoin('tbl_ms_client_branches as oper_tbl', 'tbl_ms_inspection_policy_detail.operating_office_code', '=', 'oper_tbl.id')
            ->leftJoin('tbl_ms_assisment_calculations', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_assisment_calculations.inspection_id')
            ->leftJoin('tbl_ms_inspection_accident_detail', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_inspection_accident_detail.inspection_id')
            ->leftJoin('tbl_ms_inspection_vehicle_detail', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_inspection_vehicle_detail.inspection_id')
            ->leftJoin('tbl_ms_workshop_branch', 'tbl_ms_inspection_accident_detail.workshop_branch_id', '=', 'tbl_ms_workshop_branch.id')
            ->select(
                'tbl_ms_inspection_policy_detail.*',
                'tbl_ms_inspection_vehicle_detail.chassis_no as vehicle_chassis_no', // Alias the conflicting column
                'tbl_ms_inspection_vehicle_detail.engine_no as vehicle_engine_no', // Alias the conflicting column
                'tbl_ms_inspection_vehicle_detail.*',
                'tbl_ms_assisment_calculations.*',
                'tbl_ms_accident_cause.*',
                'tbl_ms_inspection_accident_detail.chassis_no as accident_chassis_no', // Alias the conflicting column
                'tbl_ms_inspection_accident_detail.engine_no as accident_engine_no', // Alias the conflicting column
                'tbl_ms_inspection_accident_detail.*',
                'tbl_ms_workshop_branch.workshop_branch_name',
                'tbl_ms_workshop_branch.address as workshop_branch_address',
                'tbl_ms_client_branches.office_name',
                'tbl_ms_client_branches.office_address',
                'app_tbl.office_name as appointing_office_name',
                'app_tbl.office_address as appointing_office_address',
                'oper_tbl.office_name as operating_office_name',
                'oper_tbl.office_address as operating_office_address'
            )
            ->where('tbl_ms_inspection_policy_detail.inspection_id', $insepectionId)
            ->first();

        $lossAssessment = AssismentDetail::join('tbl_ms_assisment_calculations', 'tbl_ms_assisment_calculations.inspection_id', '=', 'tbl_ms_assessment_details.inspection_id')
            ->leftJoin('tbl_ms_assessmentsheet_settings', 'tbl_ms_assessment_details.inspection_id', '=', 'tbl_ms_assessmentsheet_settings.inspection_id')
            ->leftJoin('tbl_ms_tax_dep_settings', 'tbl_ms_assessment_details.inspection_id', '=', 'tbl_ms_tax_dep_settings.inspection_id')
            ->leftJoin('tbl_ms_final_report_comment', 'tbl_ms_assessment_details.inspection_id', '=', 'tbl_ms_final_report_comment.inspection_id')
            ->leftJoin('tbl_ms_assessment_reports', 'tbl_ms_assessment_details.inspection_id', '=', 'tbl_ms_assessment_reports.inspection_id')
            ->where('tbl_ms_assessment_details.inspection_id', $insepectionId)
            ->get()
            ->toArray();

        if (is_null($policyDetails)) {
            return "Inspection Id not found";
        }
        $lossAssessment = isset($lossAssessment[0]) ? $lossAssessment[0] : [];

        $footerImage = (!empty($letter_footer_img) ? '<div><img src="' . asset('public/storage/' . $letter_footer_img) . '" style="height: auto;"></div>' : '');
        $mpdf = new Mpdf(['c', 'A4-L', 'margin_footer' => 4, 'curlAllowUnsafeSslRequests' => true, 'setAutoBottomMargin' => 'stretch']);
        $mpdf->setBasePath(url('/'));
        $header = '<table width="100%">
                                  <tr>
                                     <td width="100%" align="right">Page {PAGENO} of {nb}</td>
                                  </tr>
                            </table>';

        $header .= '<hr style="border: 2px solid #000; margin: 0px;">';
//        $mpdf->SetHTMLFooter('<div class="footer-container">' . $footerImage . '</div>');
        try {
            $mpdf->AddPage('', '', '', '', '', '', '', '', '', '', '', '', '', '');
//            $mpdf->SetHeader($header, 0);
            $mpdf->WriteHTML(View('preview-reports.motor-analysis-sheet', compact( 'policyDetails', 'lossAssessment', 'letter_head_img', 'signature_img', 'adminHeaderFooter')));
        } catch (\Exception $th) {
            return $th;
        }

        $mpdf->Output('motor-sheet-report.pdf', 'I');
    }

    private function scrutinySheetReports($insepectionId)
    {
        $job = Inspection::where('id', $insepectionId)->first();

        $admin_id = $job->admin_id;
        $admin_branch_id = $job->admin_branch_id;

        if (Branch::where('id', $admin_branch_id)->exists() && !empty(Branch::where('id', $admin_branch_id)->first()->letter_head_img)) {
            $adminHeaderFooter = Branch::select(
                'tbl_ms_admin_branch.*',
                'tbl_ms_admin_branch.letter_head_img as branch_letter_head_img',
                'tbl_ms_admin_branch.letter_footer_img as branch_letter_footer_img',
                'tbl_ms_admin_branch.signature_img as branch_signature_img',
                'tbl_ms_admin.*'
            )
                ->leftJoin('tbl_ms_admin', 'tbl_ms_admin.id', '=', 'tbl_ms_admin_branch.admin_id')->where('tbl_ms_admin_branch.id', $admin_branch_id)->first();
        } else {
            $adminHeaderFooter = Admin_ms::where('id', $admin_id)->first();
        }


        $letter_head_img = "";
        if (isset($adminHeaderFooter->branch_letter_head_img) && $adminHeaderFooter->branch_letter_head_img != "") {
            $letter_head_img = $adminHeaderFooter->branch_letter_head_img;
        } else {
            $letter_head_img = $adminHeaderFooter->letter_head_img;
        }

        $letter_footer_img = "";
        if (isset($adminHeaderFooter->branch_letter_footer_img) && $adminHeaderFooter->branch_letter_footer_img != "") {
            $letter_footer_img = $adminHeaderFooter->branch_letter_footer_img;
        } else {
            $letter_footer_img = $adminHeaderFooter->letter_footer_img;
        }

        $signature_img = "";
        if (isset($adminHeaderFooter->branch_signature_img) && $adminHeaderFooter->branch_signature_img != "") {
            $signature_img = $adminHeaderFooter->branch_signature_img;
        } else {
            $signature_img = $adminHeaderFooter->signature_img;
        }

        $policyDetails = InspectionPolicyDetail::leftJoin('tbl_ms_accident_cause', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_accident_cause.inspection_id')
            ->leftJoin('tbl_ms_client_branches', 'tbl_ms_inspection_policy_detail.client_branch_id', '=', 'tbl_ms_client_branches.id')
            ->leftJoin('tbl_ms_inspection_details', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_inspection_details.id')
            ->leftJoin('tbl_ms_admin', 'tbl_ms_inspection_details.admin_id', '=', 'tbl_ms_admin.id')
            ->leftJoin('tbl_ms_client_branches as app_tbl', 'tbl_ms_inspection_policy_detail.appointing_office_code', '=', 'app_tbl.id')
            ->leftJoin('tbl_ms_client_branches as oper_tbl', 'tbl_ms_inspection_policy_detail.operating_office_code', '=', 'oper_tbl.id')
            ->leftJoin('tbl_ms_assisment_calculations', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_assisment_calculations.inspection_id')
            ->leftJoin('tbl_ms_inspection_accident_detail', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_inspection_accident_detail.inspection_id')
            ->leftJoin('tbl_ms_inspection_vehicle_detail', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_inspection_vehicle_detail.inspection_id')
            ->leftJoin('tbl_ms_assessment_reports', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_assessment_reports.inspection_id')
            ->leftJoin('tbl_ms_inspection_feebill_report', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_inspection_feebill_report.inspection_id')
            ->select(
                'tbl_ms_inspection_policy_detail.*',
                'tbl_ms_inspection_vehicle_detail.chassis_no as vehicle_chassis_no', // Alias the conflicting column
                'tbl_ms_inspection_vehicle_detail.engine_no as vehicle_engine_no', // Alias the conflicting column
                'tbl_ms_inspection_vehicle_detail.*',
                'tbl_ms_admin.name as final_surveyor',
                'tbl_ms_assisment_calculations.*',
                'tbl_ms_accident_cause.*',
                'tbl_ms_inspection_accident_detail.chassis_no as accident_chassis_no', // Alias the conflicting column
                'tbl_ms_inspection_accident_detail.engine_no as accident_engine_no', // Alias the conflicting column
                'tbl_ms_inspection_accident_detail.*',
                'tbl_ms_assessment_reports.*',
                'tbl_ms_client_branches.office_name',
                'tbl_ms_client_branches.office_address',
                'app_tbl.office_name as appointing_office_name',
                'app_tbl.office_address as appointing_office_address',
                'oper_tbl.office_name as operating_office_name',
                'oper_tbl.office_address as operating_office_address',
                'tbl_ms_inspection_feebill_report.TotalAmountWithoutGST'
            )
            ->where('tbl_ms_inspection_policy_detail.inspection_id', $insepectionId)
            ->first();


        $footerImage = (!empty($letter_footer_img) ? '<img src="' . asset('public/storage/' . $letter_footer_img) . '" style="height: auto;">' : '');
        $mpdf = new Mpdf(['c', 'A4-L', 'curlAllowUnsafeSslRequests' => true]);
        $mpdf->setBasePath(url('/'));
        $mpdf->AddPage();
        $header = '<table width="100%">
                          <tr>
                             <td align="right">Page {PAGENO} of {nb}</td>
                          </tr>
                        </table>';
        $header .= '<hr style="border: 2px solid #000; margin: 0;">'; // Add a horizontal line as a divider
        $mpdf->SetHeader($header, 'O');
        try {
            $mpdf->WriteHTML(View('preview-reports.scrutiny-sheet', compact('policyDetails','letter_head_img', 'signature_img', 'adminHeaderFooter')));
            $mpdf->SetHTMLFooter('<div class="footer-container">' . $footerImage . '</div>');
        } catch (\Exception $th) {
            return $th;
        }

        $mpdf->Output('scrutiny-sheet-report.pdf', 'I');
    }

    public function photoSheetReport(Request $request)
    {
        ini_set('memory_limit', '2048M');
        ini_set("pcre.backtrack_limit", "5000000");
        $validator = Validator::make($request->all(), [
            'inspection_id' => 'required|integer',
            'photo_sheet_type' => 'required'
        ]);

        $photoSheetType = $request->photo_sheet_type;

        $job = Inspection::where('id', $request->inspection_id)->first();

        $admin_id = $job->admin_id;
        $admin_branch_id = $job->admin_branch_id;

        if (Branch::where('id', $admin_branch_id)->exists() && !empty(Branch::where('id', $admin_branch_id)->first()->letter_head_img)) {
            $adminHeaderFooter = Branch::select(
                'tbl_ms_admin_branch.*',
                'tbl_ms_admin_branch.letter_head_img as branch_letter_head_img',
                'tbl_ms_admin_branch.letter_footer_img as branch_letter_footer_img',
                'tbl_ms_admin_branch.signature_img as branch_signature_img',
                'tbl_ms_admin.*'
            )
                ->leftJoin('tbl_ms_admin', 'tbl_ms_admin.id', '=', 'tbl_ms_admin_branch.admin_id')->where('tbl_ms_admin_branch.id', $admin_branch_id)->first();
        } else {
            $adminHeaderFooter = Admin_ms::where('id', $admin_id)->first();
        }


        $letter_head_img = "";
        if (isset($adminHeaderFooter->branch_letter_head_img) && $adminHeaderFooter->branch_letter_head_img != "") {
            $letter_head_img = $adminHeaderFooter->branch_letter_head_img;
        } else {
            $letter_head_img = $adminHeaderFooter->letter_head_img;
        }


        $policyDetails = InspectionPolicyDetail::leftJoin('tbl_ms_inspection_accident_detail', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_inspection_accident_detail.inspection_id')
            ->leftJoin('tbl_ms_inspection_files', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_inspection_files.inspection_id')
            ->leftJoin('tbl_ms_inspection_vehicle_detail', 'tbl_ms_inspection_policy_detail.inspection_id', '=', 'tbl_ms_inspection_vehicle_detail.inspection_id')
            ->select(
                'tbl_ms_inspection_files.inspection_id',
                'tbl_ms_inspection_files.file_type',
                'tbl_ms_inspection_files.original_file_name',
                'tbl_ms_inspection_policy_detail.insured_name',
                'tbl_ms_inspection_vehicle_detail.registration_no', // Alias the conflicting column
                'tbl_ms_inspection_accident_detail.date_time_accident' // Alias the conflicting column
            )
            ->where('tbl_ms_inspection_policy_detail.inspection_id', $request->inspection_id)
            ->where('tbl_ms_inspection_files.photosheet_selected', 1)
            ->get()->toArray();

        $uploadedFiles = [];
        foreach ($policyDetails as $value) {
            $S3Path = $this->uploadToS3($value, $value['file_type'], $value['original_file_name']);
            $original_file_path = 'https://' .getAwsBaseUrl() . '/' . $S3Path;
            $uploadedFiles[] = $original_file_path; // Storing the URL in the $uploadedFiles array
        }

        if ($request->photo_sheet_type == '2X2_Photosheet') {
            $mpdf = new Mpdf(['orientation' => 'L', 'curlAllowUnsafeSslRequests' => true]);
            try {
                $mpdf->WriteHTML(View('preview-reports.photosheet', compact('photoSheetType', 'policyDetails', 'uploadedFiles')));
            } catch (\Exception $th) {
                return $th;
            }
        } else {

            $headerContent = '
         <div style="font-family: \'Verdana\' !important; font-size: 14px;">
            <div style="text-align: start;">
               Regn No.: <strong>' . (isset($policyDetails[0]['registration_no']) ? $policyDetails[0]['registration_no'] : '') . '</strong>,
               Insured Name: <strong>' . (isset($policyDetails[0]['insured_name']) ? $policyDetails[0]['insured_name'] : '') . '</strong>,
               Date of Accn.: <strong>' . (isset($policyDetails[0]['date_time_accident']) ? \Carbon\Carbon::parse($policyDetails[0]['date_time_accident'])->format('d/m/Y') : '') . '</strong>
            </div>
         </div>';

            // Initialize mPDF with correct options
            $mpdf = new Mpdf(['c', 'A4-L', 'mode' => 'utf-8', 'curlAllowUnsafeSslRequests' => true]);

            // Set the header content
            $mpdf->SetHTMLHeader($headerContent);

            try {
                // Render the view to HTML
                $mpdf->WriteHTML(View('preview-reports.photosheet', compact('photoSheetType', 'policyDetails', 'uploadedFiles', 'letter_head_img')));

                // Output the PDF
                $mpdf->Output();
            } catch (\Exception $th) {
                return $th;
            }
        }

        // $mpdf->Output($path);
        $mpdf->Output('photosheet-report.pdf', 'I');
    }
}
