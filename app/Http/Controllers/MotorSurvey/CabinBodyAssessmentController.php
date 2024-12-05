<?php

namespace App\Http\Controllers\MotorSurvey;

use App\Helpers\CabinBodyHelper;
use App\Http\Controllers\BaseController;
use App\Models\CabinBodyAssessmentModel;
use App\Models\CabinBodyTaxSettingModel;
use App\Models\Inspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CabinBodyAssessmentController extends BaseController
{
    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function saveUpdateCabinBodyAssessment(Request $request, $id)
    {
        $created_by = Auth::user()->id;
        $jobdetails = Inspection::where('id', $id)->first();

        if ($jobdetails) {
            $summary_assessment_loss = $request->summary_assessment_loss ?? [];
            $quantitiesArr = !empty($request->quantities) ? $request->quantities : [];

            $data['less_cabin_salvage']     = $summary_assessment_loss['less_cabin_salvage'] ?? 0;
            $data['less_load_body_salvage'] = $summary_assessment_loss['less_load_body_salvage'] ?? 0;
            $data['body_type']=$summary_assessment_loss['body_type'] ?? 'bus';
            $data['created_by']             = $created_by;
            $data['inspection_id']          = $id;
            $data['alldetails']             = !empty($quantitiesArr) ? json_encode($quantitiesArr, true) : '';

            // return $this->sendResponse("debug", $data, 200);
            // logger("json",[json_decode($data["alldetails"],true)]);
            CabinBodyAssessmentModel::where('inspection_id', $id)->delete();
            CabinBodyAssessmentModel::create($data);

            return $this->sendResponse("saved successfully", "Assessment details saved successfully", 200);
        } else {
            return $this->sendError('Job details unavailable.');
        }

    }



    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function getCabinBodyAssessmentList(Request $request, $id)
    {
        $jobdetails = Inspection::where('id', $id)->first();

        if ($jobdetails) {
            $data  = [];
            $query = CabinBodyAssessmentModel::where('inspection_id', $id)->first();

            if ($query) {
                $data['summary_assessment_loss'] = [
                    'less_cabin_salvage'     => $query->less_cabin_salvage,
                    'less_load_body_salvage' => $query->less_load_body_salvage,
                    'body_type'              => $query->body_type];

                $data['quantities'] = json_decode($query->alldetails, true);
            } else {
                $data['summary_assessment_loss'] = ["less_cabin_salvage" => 0, "less_load_body_salvage" => 0];
                $data["quantities"]              = [];
            }

            return $this->sendResponse($data, "fetched successfully", 200);
        } else {
            return $this->sendError('Job details unavailable.');
        }

    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function saveUpdateCabinBodyAssessmentTax(Request $request, $id)
    {
        $created_by = Auth::user()->id;
        $jobdetails = Inspection::where('id', $id)->first();

        if ($jobdetails) {
            $taxData = [
                "inspection_id"                 => $id,
                "gst_on_est_lab"                => $request->gst_on_est_lab ?? 0,
                "gst_on_ass_lab"                => $request->gst_on_ass_lab ?? 0,
                "est_ass_gst_lab_per"           => $request->est_ass_gst_lab_per ?? 0,
                "gst_parts_est_amt"             => $request->gst_parts_est_amt ?? 0,
                "gst_parts_ass_amt"             => $request->gst_parts_ass_amt ?? 0,
                "set_mutilple_gst_on_parts"     => $request->set_mutilple_gst_on_parts ?? 0,
                "set_mutilple_gst_on_labour"    => $request->set_mutilple_gst_on_labour ?? 0,
                "gst_parts_bill_amt"            => $request->gst_parts_bill_amt ?? 0,
                "multiple_gst_on_billed_amount" => $request->multiple_gst_on_billed_amount ?? 0,
            ];

            CabinBodyTaxSettingModel::updateOrCreate(
                ['inspection_id' => $id],
                $taxData
            );

            return $this->sendResponse("saved successfully", "Details saved successfully", 200);
        } else {
            return $this->sendError('Job details unavailable.');
        }

    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function getCabinBodyTaxSetting(Request $request, $id)
    {
        $jobdetails = Inspection::where('id', $id)->first();

        if ($jobdetails) {
            $data  = [];
            $query = CabinBodyTaxSettingModel::where('inspection_id', $id)->first();

            if ($query) {
                $data = $query->toArray();
            }

            return $this->sendResponse($data, "fetched successfully", 200);
        } else {
            return $this->sendError('Job details unavailable.');
        }

    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function getCabinBodyAssessmentSummaryData(Request $request, $id)
    {
        $jobDetails = Inspection::with('get_cabin_body', 'get_cabin_body_tax', 'get_tax_dep_settings')->where('id', $id)->first();

        if ($jobDetails) {
            $jobDetailsData       = $jobDetails->toArray();
            $get_cabin_body_tax   = !empty($jobDetailsData['get_cabin_body_tax']) ? $jobDetailsData['get_cabin_body_tax'] : [];
            $get_tax_dep_settings = !empty($jobDetailsData['get_tax_dep_settings']) ? $jobDetailsData['get_tax_dep_settings'] : [];

            $quantities    = !empty($jobDetailsData['get_cabin_body']['alldetails']) ? json_decode($jobDetailsData['get_cabin_body']['alldetails'], true) : [];
            $cabinGstApply = CabinBodyHelper::commonCabinBodyGstApply($quantities, $get_cabin_body_tax);
            $arrangeData   = CabinBodyHelper::arrangeQuantityAmountGstWise($cabinGstApply, $quantities);

            $calculateAmount = CabinBodyHelper::calculateQuantitiesAmountTaxBase($arrangeData['response'], $arrangeData['parts_gst'], $arrangeData['labour_gst'], $get_tax_dep_settings);

            $ass_metal_part_cabin       = (isset($calculateAmount[1]['parts_total']['assessed']['metal']) && !empty($calculateAmount[1]['parts_total']['assessed']['metal'])) ? number_format_custom($calculateAmount[1]['parts_total']['assessed']['metal']) : '0.00';
            $ass_rub_plast_part_cabin   = (isset($calculateAmount[1]['parts_total']['assessed']['rubberPlastic']) && !empty($calculateAmount[1]['parts_total']['assessed']['rubberPlastic'])) ? number_format_custom($calculateAmount[1]['parts_total']['assessed']['rubberPlastic']) : '0.00';
            $ass_glass_cabin            = (isset($calculateAmount[1]['parts_total']['assessed']['glass']) && !empty($calculateAmount[1]['parts_total']['assessed']['glass'])) ? number_format_custom($calculateAmount[1]['parts_total']['assessed']['glass']) : '0.00';
            $ass_fiber_part_cabin       = (isset($calculateAmount[1]['parts_total']['assessed']['fiber']) && !empty($calculateAmount[1]['parts_total']['assessed']['fiber'])) ? number_format_custom($calculateAmount[1]['parts_total']['assessed']['fiber']) : '0.00';
            $ass_recondition_part_cabin = (isset($calculateAmount[1]['parts_total']['assessed']['recondition']) && !empty($calculateAmount[1]['parts_total']['assessed']['recondition'])) ? number_format_custom($calculateAmount[1]['parts_total']['assessed']['recondition']) : '0.00';
            $total_parts_cabin          = ($ass_metal_part_cabin + $ass_rub_plast_part_cabin + $ass_glass_cabin + $ass_fiber_part_cabin + $ass_recondition_part_cabin);
            $total_est_labour_cabin     = (isset($calculateAmount[1]['labour_total']['estimated']) && !empty($calculateAmount[1]['labour_total']['estimated'])) ? number_format_custom($calculateAmount[1]['labour_total']['estimated']) : '0.00';
            $total_ass_labour_cabin     = (isset($calculateAmount[1]['labour_total']['assessed']) && !empty($calculateAmount[1]['labour_total']['assessed'])) ? number_format_custom($calculateAmount[1]['labour_total']['assessed']) : '0.00';

            $ass_metal_part_load       = (isset($calculateAmount[2]['parts_total']['assessed']['metal']) && !empty($calculateAmount[2]['parts_total']['assessed']['metal'])) ? number_format_custom($calculateAmount[2]['parts_total']['assessed']['metal']) : '0.00';
            $ass_rub_plast_part_load   = (isset($calculateAmount[2]['parts_total']['assessed']['rubberPlastic']) && !empty($calculateAmount[2]['parts_total']['assessed']['rubberPlastic'])) ? number_format_custom($calculateAmount[2]['parts_total']['assessed']['rubberPlastic']) : '0.00';
            $ass_glass_load            = (isset($calculateAmount[2]['parts_total']['assessed']['glass']) && !empty($calculateAmount[2]['parts_total']['assessed']['glass'])) ? number_format_custom($calculateAmount[2]['parts_total']['assessed']['glass']) : '0.00';
            $ass_fiber_part_load       = (isset($calculateAmount[2]['parts_total']['assessed']['fiber']) && !empty($calculateAmount[2]['parts_total']['assessed']['fiber'])) ? number_format_custom($calculateAmount[2]['parts_total']['assessed']['fiber']) : '0.00';
            $ass_recondition_part_load = (isset($calculateAmount[2]['parts_total']['assessed']['recondition']) && !empty($calculateAmount[2]['parts_total']['assessed']['recondition'])) ? number_format_custom($calculateAmount[2]['parts_total']['assessed']['recondition']) : '0.00';
            $total_parts_load          = ($ass_metal_part_load + $ass_rub_plast_part_load + $ass_glass_load + $ass_fiber_part_load + $ass_recondition_part_load);
            $total_est_labour_load     = (isset($calculateAmount[2]['labour_total']['estimated']) && !empty($calculateAmount[2]['labour_total']['estimated'])) ? number_format_custom($calculateAmount[2]['labour_total']['estimated']) : '0.00';
            $total_ass_labour_load     = (isset($calculateAmount[2]['labour_total']['assessed']) && !empty($calculateAmount[2]['labour_total']['assessed'])) ? number_format_custom($calculateAmount[2]['labour_total']['assessed']) : '0.00';

            $total_parts_est_cabin     = 0;
            $total_parts_est_load_body = 0;

            if (isset($calculateAmount[1]['parts_total']['estimated']) && !empty($calculateAmount[1]['parts_total']['estimated'])) {

                foreach ($calculateAmount[1]['parts_total']['estimated'] as $category) {
                    $total_parts_est_cabin += !empty($category) ? $category : 0;
                }

            }

            if (isset($calculateAmount[2]['parts_total']['estimated']) && !empty($calculateAmount[2]['parts_total']['estimated'])) {

                foreach ($calculateAmount[2]['parts_total']['estimated'] as $category) {
                    $total_parts_est_load_body += !empty($category) ? $category : 0;
                }

            }

            $total_ass_cabin = ($total_parts_cabin + $total_ass_labour_cabin);
            $total_est_cabin = ($total_parts_est_cabin + $total_est_labour_cabin);
            $total_ass_load  = ($total_parts_load + $total_ass_labour_load);
            $total_est_load  = ($total_parts_est_load_body + $total_est_labour_load);

            $total_after_salvage_deduction_cabin = (isset($jobDetailsData['get_cabin_body']['less_cabin_salvage']) && $jobDetailsData['get_cabin_body']['less_cabin_salvage'] > 0 && $total_ass_cabin > 0) ? ($total_ass_cabin - $jobDetailsData['get_cabin_body']['less_cabin_salvage']) : $total_ass_cabin;
            $total_after_salvage_deduction_load  = (isset($jobDetailsData['get_cabin_body']['less_load_body_salvage']) && $jobDetailsData['get_cabin_body']['less_load_body_salvage'] > 0 && $total_ass_load > 0) ? ($total_ass_load - $jobDetailsData['get_cabin_body']['less_load_body_salvage']) : $total_ass_load;

            $net_ass_loss_body_cabin = ($total_after_salvage_deduction_cabin + $total_after_salvage_deduction_load);
            $net_est_loss_body_cabin = ($total_est_cabin + $total_est_load);

            $data = [
                "cabin"                   => [
                    "metal"                         => $ass_metal_part_cabin,
                    "rub_plast"                     => $ass_rub_plast_part_cabin,
                    "glass"                         => $ass_glass_cabin,
                    "fiber"                         => $ass_fiber_part_cabin,
                    "recondition"                   => $ass_recondition_part_cabin,
                    "total_parts"                   => number_format_custom($total_parts_cabin),
                    "total_est_labour"              => $total_est_labour_cabin,
                    "labour_charge"                 => $total_ass_labour_cabin,
                    "total_after_salvage_deduction" => number_format_custom($total_after_salvage_deduction_cabin),
                    "total_parts_est"               => number_format_custom($total_parts_est_cabin),
                    "total_ass"                     => number_format_custom($total_ass_cabin),
                    "total_est"                     => number_format_custom($total_est_cabin),
                ],
                "load_body"               => [
                    "metal"                         => $ass_metal_part_load,
                    "rub_plast"                     => $ass_rub_plast_part_load,
                    "glass"                         => $ass_glass_load,
                    "fiber"                         => $ass_fiber_part_load,
                    "recondition"                   => $ass_recondition_part_load,
                    "total_parts"                   => number_format_custom($total_parts_load),
                    "total_est_labour"              => number_format_custom($total_est_labour_load),
                    "labour_charge"                 => number_format_custom($total_ass_labour_load),
                    "total_after_salvage_deduction" => number_format_custom($total_after_salvage_deduction_load),
                    "total_parts_est"               => number_format_custom($total_parts_est_load_body),
                    "total_ass"                     => number_format_custom($total_ass_load),
                    "total_est"                     => number_format_custom($total_est_load),
                ],
                "net_ass_loss_body_cabin" => number_format_custom($net_ass_loss_body_cabin),
                "net_est_loss_body_cabin" => number_format_custom($net_est_loss_body_cabin),
            ];
            CabinBodyAssessmentModel::where('inspection_id', $jobDetailsData['id'])->update(['details_calculation' => json_encode($calculateAmount, true), 'updated_at' => date('Y-m-d H:i:s')]);

            return $this->sendResponse($data, "fetched successfully", 200);
        } else {
            return $this->sendError('Job details unavailable.');
        }

    }

}
