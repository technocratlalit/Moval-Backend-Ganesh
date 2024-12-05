<?php

namespace App\Http\Controllers\MotorSurvey;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jobms;
use App\Models\InspectionVehicleDetail;
use App\Models\AssismentDetail;
use App\Models\AssismentDetailList;
use App\Models\AssismentCalculation;
use App\Models\AssessmentSheetSettings;
use App\Models\TaxSetting;
use App\Models\AssessmentReport;
use Illuminate\Support\Facades\Log;
use DB;
use App\Models\Inspection;

class AssismentController extends BaseController
{
    public function index(Request $request){}

    public function store(Request $request, $id)
    {
        $created_by = Auth::user()->id;
        $jobdetails = Inspection::where('id', $id)->first();
        if ($jobdetails) {
            $data['alldetails'] = $request->quantities;
            $data['created_by'] = $created_by;
            $data['inspection_id'] = $id;
            $json_array1 = $request->quantities;
            $assessmentSettings = $request->assessment_settings;
            $data['alldetails'] = json_encode($request->quantities);
            AssismentDetailList::where('inspection_id', $id)->delete();
            AssismentDetail::where('inspection_id', $id)->delete();
            $assesmentData = [
                "inspection_id" => $id,
                "display_ai" => $assessmentSettings["display_ai"],
                "display_hsn" => $assessmentSettings["display_hsn"],
                "copy_est_amt" => $assessmentSettings["copy_est_amt"],
                "description_in_uppercase" => $assessmentSettings["description_in_uppercase"],
                "description_in_sentancecase" => $assessmentSettings["description_in_sentancecase"],
                "display_bill_sr_no" => $assessmentSettings["display_bill_sr_no"],
                "display_gst_summary" => $assessmentSettings["display_gst_summary"],
                "display_gst_summary_part_category_wise" => $assessmentSettings["display_gst_summary_part_category_wise"]
            ];
            // Insert or update records
            AssessmentSheetSettings::updateOrCreate(
                ['inspection_id' => $id], // Condition to find existing records
                $assesmentData // Data to insert or update
            );
            $insertid = AssismentDetail::create($data);
            $assessmentDetail = [];
            foreach ($json_array1 as $json_array) {
                $quantities = isset($json_array['quantities']) ? $json_array['quantities'] : [];
                unset($json_array['quantities']);
                $json_array['created_by'] = $created_by;
                $json_array['inspection_id'] = $id;
                $json_array['assisment_id'] = $insertid->id;
                $assessmentDetail[] = $json_array;
                if (!empty($quantities)) {
                    foreach ($quantities as $quantity) {
                        unset($quantity['quantities']);
                        $quantity['created_by'] = $created_by;
                        $quantity['inspection_id'] = $id;
                        $quantity['assisment_id'] = $insertid->id;
                        $assessmentDetail[] = $quantity;
                    }
                }
            }
            if (!empty($assessmentDetail)) {
                AssismentDetailList::insert($assessmentDetail);
            }
            return $this->sendResponse("saved successfully", "Assessment details saved successfully", 200);
        } else {
            return $this->sendError('Job details unavailable.');
        }
    }

    public function update(Request $request, $id)
    {
        $updated_by = Auth::user()->id;
        $jobdetails = Inspection::where('id', $id)->first();
        if ($jobdetails) {
            $assdetail = AssismentDetail::where('inspection_id', $id)->first();
            $assdetail->alldetails = json_encode($request->quantities);
            $assdetail->updated_by = $updated_by;
            $assdetail->inspection_id = $id;
            $assdetail->update();
            AssismentDetailList::where('inspection_id', $id)->delete();
            $json_array1 = $request->quantities;
            foreach ($json_array1 as $json_array) {
                //dd($json_array);
                $json_array['created_by'] = $created_by;
                $json_array['inspection_id'] = $id;
                $json_array['assisment_id'] = $insertid->id;
                if (!empty($json_array['quantities'])) {
                    foreach ($json_array['quantities'] as $quantity) {
                        // Assign 'assisment_id' to each item in 'quantities' array
                        $quantity['created_by'] = $created_by;
                        $quantity['inspection_id'] = $id;
                        $quantity['assisment_id'] = $insertid->id;
                        AssismentDetailList::create($quantity);
                    }
                }
                // Always insert the entire $json_array into AssismentDetailList
                AssismentDetailList::create($json_array);
            }
            //new AdminResource($admin)
            return $this->sendResponse("updated Successfully", "Assessment details updated Successfully", 200);
        } else {
            return $this->sendError('Job details unavailable.');
        }
    }

    public function show(Request $request, $id)
    {
        $created_by = Auth::user()->id;
        $jobdetails = Inspection::where('id', $id)->first();
        if ($jobdetails) {
            $assdetail = AssismentDetail::where('inspection_id', $id)->first();
            $assSettings = AssessmentSheetSettings::where('inspection_id', $id)->first();
            $vehicleType = $jobdetails->vehicle_type;
            $value = null;
            if ($assdetail) {
                $allvalues = json_decode($assdetail->alldetails);
                $value = $allvalues;
                $created_by = $assdetail->created_by;
                $updated_by = $assdetail->updated_by;
                $inspection_id = $assdetail->inspection_id;
                $data = [
                    'quantities' => $value,
                    'inspection_id' => $inspection_id,
                    'vehicle_type' => $vehicleType,
                    'created_by' => $created_by,
                    'updated_by' => $updated_by,
                    'created_at' => $assdetail->created_at,
                    'updated_at' => $assdetail->updated_at,
                    'display_ai' => null,
                    'display_hsn' => null,
                    'copy_est_amt' => null,
                    'description_in_uppercase' => null,
                    'description_in_sentancecase' => null,
                    "display_bill_sr_no" => null,
                    "display_gst_summary" => null,
                    "display_gst_summary_part_category_wise" => null
                ];

                if ($assSettings) {
                    $data['display_ai'] = $assSettings->display_ai;
                    $data['display_hsn'] = $assSettings->display_hsn;
                    $data['copy_est_amt'] = $assSettings->copy_est_amt;
                    $data['description_in_uppercase'] = $assSettings->description_in_uppercase;
                    $data['description_in_sentancecase'] = $assSettings->description_in_sentancecase;
                    $data["display_bill_sr_no"] = $assSettings->display_bill_sr_no;
                    $data["display_gst_summary"] = $assSettings->display_gst_summary;
                    $data["display_gst_summary_part_category_wise"] = $assSettings->display_gst_summary_part_category_wise;
                }

            } else {
                $data = ['quantities' => $value, 'inspection_id' => $id];
            }
            return $this->sendResponse($data, "success", 200);
        } else {
            return $this->sendError('Job details unavailable.');
        }
    }

    public function destroy(Request $request)
    {
        $created_by = Auth::user()->id;
        $jobdetails = Inspection::where('id', $id)->first();
        if ($jobdetails) {
            $assdetail = AssismentDetail::where('inspection_id', $id)->delete();
            return $this->sendResponse("deleted Sussessfully", "success", 200);
        } else {
            return $this->sendError('Job details unavailable.');
        }
    }

    public function storetax(Request $request, $inspection_id)
    {
        $request->inspection_id = $inspection_id;

        $MetalDepPer = 0;
        $GlassDepPer = 0;
        $RubberDepPer = 0;
        $FibreDepPer = 0;

        if ($request->IsZeroDep == false || $request->IsZeroDep == 0) {
            $MetalDepPer = getdeprecationpercentagebymonth($request->inspection_id, 'Metal');
            $GlassDepPer = getdeprecationpercentagebymonth($request->inspection_id, 'Glass');
            $RubberDepPer = getdeprecationpercentagebymonth($request->inspection_id, 'Rubber');
            $FibreDepPer = getdeprecationpercentagebymonth($request->inspection_id, 'Fibre');
        }

        $MutipleGSTonParts = false;
        $MultipleGSTonLab = false;
        $IGSTonPartsAndLab = false;

        $data = [
            'inspection_id' => $request->inspection_id,
            'IsZeroDep' => (isset($request->IsZeroDep) && !empty($request->IsZeroDep)) ? 1 : 0,
            'DepBasedOn' => $request->DepBasedOn,
            'MetalDepPer' => $MetalDepPer,
            'RubberDepPer' => $RubberDepPer,
            'GlassDepPer' => $GlassDepPer,
            'FibreDepPer' => $FibreDepPer,
            'GSTonEstimatedLab' => $request->GSTonEstimatedLab,
            'GstonAssessedLab' => $request->GstonAssessedLab,
            'GSTLabourPer' => $request->GSTLabourPer,
            'GSTEstimatedPartsPer' => $request->GSTEstimatedPartsPer,
            'GSTAssessedPartsPer' => $request->GSTAssessedPartsPer,
            'IMT23DepPer' => $request->IMT23DepPer,
            'MutipleGSTonParts' => $request->MutipleGSTonParts,
            'MultipleGSTonLab' => $request->MultipleGSTonLab,
            'IGSTonPartsAndLab' => $request->IGSTonPartsAndLab,
            'MultipleGSTonBilled' => $request->MultipleGSTonBilled ? 1 : 0,
            'GSTBilledPartPer' => $request->GSTBilledPartPer ?? 0,
        ];

        $dasend = [
            'MetalDepPer' => $MetalDepPer,
            'RubberDepPer' => $RubberDepPer,
            'GlassDepPer' => $GlassDepPer,
            'FibreDepPer' => $FibreDepPer
        ];
        $taxdetail = TaxSetting::where('inspection_id', $request->inspection_id)->first();
        if (!$taxdetail) {
            $taxdetail = TaxSetting::create($data);
            return $this->sendResponse("updated Successfully", "Tax & Depreciation settings saved successfully ", 200);
        } else {
            $taxdetail = TaxSetting::where('inspection_id', $request->inspection_id)->update($data);
            return $this->sendResponse($dasend, "Tax & Depreciation settings saved successfully ", 200);
        }
    }

    public function gettax(Request $request, $inspection_id)
    {
        $taxdetail = TaxSetting::where('inspection_id', $inspection_id)->first();
        if (!$taxdetail) {
            $MetalDepPer = getdeprecationpercentagebymonth($inspection_id, 'Metal');
            $GlassDepPer = getdeprecationpercentagebymonth($inspection_id, 'Glass');
            $RubberDepPer = getdeprecationpercentagebymonth($inspection_id, 'Rubber');
            $FibreDepPer = getdeprecationpercentagebymonth($inspection_id, 'Fibre');
            $data = [
                'inspection_id' => $inspection_id,
                'IsZeroDep' => false,
                'DepBasedOn' => "Automatic",
                'MetalDepPer' => $MetalDepPer,
                'RubberDepPer' => $RubberDepPer,
                'GlassDepPer' => $GlassDepPer,
                'FibreDepPer' => $FibreDepPer,
                'GSTonEstimatedLab' => 'Y',
                'GstonAssessedLab' => 'Y',
                'GSTLabourPer' => number_format_custom(0, 2),
                'GSTEstimatedPartsPer' => number_format_custom(0, 2),
                'GSTAssessedPartsPer' => number_format_custom(0, 2),
                'IMT23DepPer' => number_format_custom(0, 2),
                'MutipleGSTonParts' => false,
                'MultipleGSTonLab' => false,
                'IGSTonPartsAndLab' => false,
                'MultipleGSTonBilled' => false,
                'GSTBilledPartPer' => 0
            ];
            return $this->sendResponse($data, "fetched successfully", 200);
        }

        $MutipleGSTonParts = false;
        $MultipleGSTonLab = false;
        $IGSTonPartsAndLab = false;
        $data = [
            'inspection_id' => $taxdetail->inspection_id,
            'IsZeroDep' => $taxdetail->IsZeroDep,
            'DepBasedOn' => $taxdetail->DepBasedOn,
            'MetalDepPer' => $taxdetail->MetalDepPer,
            'RubberDepPer' => $taxdetail->RubberDepPer,
            'GlassDepPer' => $taxdetail->GlassDepPer,
            'FibreDepPer' => $taxdetail->FibreDepPer,
            'GSTonEstimatedLab' => $taxdetail->GSTonEstimatedLab,
            'GstonAssessedLab' => $taxdetail->GstonAssessedLab,
            'GSTLabourPer' => $taxdetail->GSTLabourPer,
            'GSTEstimatedPartsPer' => $taxdetail->GSTEstimatedPartsPer,
            'GSTAssessedPartsPer' => $taxdetail->GSTAssessedPartsPer,
            'IMT23DepPer' => $taxdetail->IMT23DepPer,
            'MutipleGSTonParts' => $taxdetail->MutipleGSTonParts,
            'MultipleGSTonLab' => $taxdetail->MultipleGSTonLab,
            'IGSTonPartsAndLab' => $taxdetail->IGSTonPartsAndLab,
            'MultipleGSTonBilled' => $taxdetail->MultipleGSTonBilled,
            'GSTBilledPartPer' => $taxdetail->GSTBilledPartPer
        ];
        return $this->sendResponse($data, "fetched successfully", 200);
    }

    public function getsummary(Request $request, $inspection_id)
    {
        $totalmetalass = 0;
        $totalfiberass = 0;
        $totalrubberass = 0;
        $totalglassass = 0;
        $totalreconditionass = 0;
        $totalmetales = 0;
        $totalfiberes = 0;
        $totalrubberes = 0;
        $totalglasses = 0;
        $totalreconditiones = 0;
        $aimetal = 0;
        $aifiber = 0;
        $airubber = 0;
        $aiglass = 0;
        $airecondition = 0;
        $endoresmentass = 0;
        $endoresmentest = 0;
        $laboutass = 0;
        $labourest = 0;
        $totalpaintinglabour = 0;
        $lab_ai_amt = 0;
        $inspection = Inspection::where('id', $inspection_id)->first();
        $taxdetail = TaxSetting::where('inspection_id', $inspection_id)->first();
        if (!$taxdetail) {
            $data = [
                'inspection_id' => $inspection_id,
                'vehicle_reg_no' => vehicle_reg_no($inspection_id),
                'Insurerd_name' => insured_name($inspection_id),
                'partMetalAssamount' => number_format_custom(0, 2),
                'partRubberAssamount' => number_format_custom(0, 2),
                'partGlassAssamount' => number_format_custom(0, 2),
                'partFibreAssamount' => number_format_custom(0, 2),
                'totalendoresmentAss' => number_format_custom(0, 2),
                'totalassparts' => number_format_custom(0, 2),
                'totalestparts' => number_format_custom(0, 2),
                'totalreconditionAss' => number_format_custom(0, 2),
                'totalreconditionEst' => number_format_custom(0, 2),
                'totalAssWithReconditon' => number_format_custom(0, 2),
                'totalEstWithReconditon' => number_format_custom(0, 2),
                'totallabourass' => number_format_custom(0, 2),
                'totallabourest' => number_format_custom(0, 2),
                'paintinglabass' => number_format_custom(0, 2),
                'paintinglabes' => number_format_custom(0, 2),
                'netlabourAss' => number_format_custom(0, 2),
                'netlabourEst' => number_format_custom(0, 2),
                'totalass' => number_format_custom(0, 2),
                'totalest' => number_format_custom(0, 2),
                'ImposedClause' => number_format_custom(0, 2),
                'CompulsoryDeductable' => number_format_custom(0, 2),
                'SalvageAmt' => number_format_custom(0, 2),
                'CustomerLiability' => number_format_custom(0, 2),
                'TowingCharges' => number_format_custom(0, 2),
                /*'ScrapValue'=>number_format_custom($SalvageAmt, 2),*/
                'netbody' => number_format_custom(0, 2),
                'alltotalass' => number_format_custom(0, 2),
                'alltotalest' => number_format_custom(0, 2),
                'AIreconditionpart' => number_format_custom(0, 2),
                'AInetlabour' => number_format_custom(0, 2),
                'insurer_liability' => 0
            ];
            return $this->sendResponse($data, "fetched successfully", 200);
        }
        $assisment = DB::table('tbl_ms_assessment_detail_list')->where('inspection_id', $inspection_id)->get();

        if ($taxdetail['IsZeroDep'] == 'true' || $taxdetail['IsZeroDep'] == 1) {
            foreach ($assisment as $assismentdetail) {
                $lab_ai_amt = $lab_ai_amt + $assismentdetail->lab_ai_amt;
                $totalpaintinglabour = $totalpaintinglabour + $assismentdetail->painting_lab;
                $laboutass = $laboutass + $assismentdetail->ass_lab + labourgstass($inspection_id, $assismentdetail->ass_lab, $assismentdetail->gst);
                $labourest = $labourest + $assismentdetail->est_lab + labourgstest($inspection_id, $assismentdetail->est_lab, $assismentdetail->gst);
                $getgstest = checkgstpartEst($inspection_id, $assismentdetail->id);
                $getgstestass = checkgstpartAss($inspection_id, $assismentdetail->id);

                if ($assismentdetail->category == "Metal") {
                    $aimetal = $aimetal + $assismentdetail->ai_part_amt;
                    $totalmetalass = $totalmetalass + $assismentdetail->ass_amt + getgstamount($assismentdetail->ass_amt, $getgstestass);
                    $totalmetales = $totalmetales + $assismentdetail->est_amt + getgstamount($assismentdetail->est_amt, $getgstest);

                    if ($assismentdetail->imt_23 == "Yes") {
                        $ta = $assismentdetail->ass_amt + getgstamount($assismentdetail->ass_amt, $getgstestass);
                        $te = $assismentdetail->est_amt + getgstamount($assismentdetail->est_amt, $getgstest);
                        $endoresmentass = $endoresmentass + $ta - imt23dep($inspection_id, $ta);
                        $endoresmentest = $endoresmentest + $te - imt23dep($inspection_id, $te);
                    }
                }

                if ($assismentdetail->category == "Glass") {
                    $aiglass = $aiglass + $assismentdetail->ai_part_amt;
                    $totalglassass = $totalglassass + $assismentdetail->ass_amt + getgstamount($assismentdetail->ass_amt, $getgstestass);
                    $totalglasses = $totalglasses + $assismentdetail->est_amt + getgstamount($assismentdetail->est_amt, $getgstest);
                    if ($assismentdetail->imt_23 == "Yes") {
                        $ta = $assismentdetail->ass_amt + getgstamount($assismentdetail->ass_amt, $getgstestass);
                        $te = $assismentdetail->est_amt + getgstamount($assismentdetail->est_amt, $getgstest);
                        $endoresmentass = $endoresmentass + $ta - imt23dep($inspection_id, $ta);
                        $endoresmentest = $endoresmentest + $te - imt23dep($inspection_id, $te);
                    }
                }

                if ($assismentdetail->category == "Rubber") {
                    $airubber = $airubber + $assismentdetail->ai_part_amt;
                    $totalrubberass = $totalrubberass + $assismentdetail->ass_amt + getgstamount($assismentdetail->ass_amt, $getgstestass);
                    $totalrubberes = $totalrubberes + $assismentdetail->est_amt + getgstamount($assismentdetail->est_amt, $getgstest);
                    if ($assismentdetail->imt_23 == "Yes") {
                        $ta = $assismentdetail->ass_amt + getgstamount($assismentdetail->ass_amt, $getgstestass);
                        $te = $assismentdetail->est_amt + getgstamount($assismentdetail->est_amt, $getgstest);
                        $endoresmentass = $endoresmentass + $ta - imt23dep($inspection_id, $ta);
                        $endoresmentest = $endoresmentest + $te - imt23dep($inspection_id, $te);
                    }
                }


                if ($assismentdetail->category == "Fibre") {
                    $aifiber = $aifiber + $assismentdetail->ai_part_amt;
                    $airecondition = 0;
                    $totalfiberass = $totalfiberass + $assismentdetail->ass_amt + getgstamount($assismentdetail->ass_amt, $getgstestass);
                    $totalfiberes = $totalfiberes + $assismentdetail->est_amt + getgstamount($assismentdetail->est_amt, $getgstest);

                    if ($assismentdetail->imt_23 == "Yes") {
                        $ta = $assismentdetail->ass_amt + getgstamount($assismentdetail->ass_amt, $getgstestass);
                        $te = $assismentdetail->est_amt + getgstamount($assismentdetail->est_amt, $getgstest);
                        $endoresmentass = $endoresmentass + $ta - imt23dep($inspection_id, $ta);
                        $endoresmentest = $endoresmentest + $te - imt23dep($inspection_id, $te);
                    }
                }

                if ($assismentdetail->category == "Recondition") {
                    $airecondition = $airecondition + $assismentdetail->ai_part_amt;
                    $totalreconditionass = $totalreconditionass + $assismentdetail->ass_amt;
                    $totalreconditiones = $totalreconditionass + $assismentdetail->est_amt;
                    if ($assismentdetail->imt_23 == "Yes") {
                        $ta = $assismentdetail->ass_amt;
                        $te = $assismentdetail->est_amt;
                        $endoresmentass = $endoresmentass + $ta - imt23dep($inspection_id, $ta);
                        $endoresmentest = $endoresmentest + $te - imt23dep($inspection_id, $te);
                    }
                }
            }
        } else {
            foreach ($assisment as $assismentdetail) {
                $lab_ai_amt = $lab_ai_amt + $assismentdetail->lab_ai_amt;
                $totalpaintinglabour = $totalpaintinglabour + $assismentdetail->painting_lab;
                echo labourgstass($inspection_id, $assismentdetail->ass_lab, $assismentdetail->gst);
                $laboutass = $laboutass + $assismentdetail->ass_lab + labourgstass($inspection_id, $assismentdetail->ass_lab, $assismentdetail->gst);
                $labourest = $labourest + $assismentdetail->est_lab + labourgstest($inspection_id, $assismentdetail->est_lab, $assismentdetail->gst);
                $getgstest = checkgstpartEst($inspection_id, $assismentdetail->id);
                $getgstestass = checkgstpartAss($inspection_id, $assismentdetail->id);

                if ($assismentdetail->category == "Metal") {
                    $aimetal = $aimetal + $assismentdetail->ai_part_amt;
                    $dep_amountass = getdeprecationpercentagebymonth($inspection_id, "Metal", $assismentdetail->ass_amt);
                    $dep_amountes = getdeprecationpercentagebymonth($inspection_id, "Metal", $assismentdetail->est_amt);
                    $amountass = $assismentdetail->ass_amt - $dep_amountass;
                    $amountes = $assismentdetail->est_amt;
                    $totalmetalass = $totalmetalass + $amountass + getgstamount($amountass, $getgstestass);
                    $totalmetales = $totalmetales + $amountes + getgstamount($amountes, $getgstest);
                    if ($assismentdetail->imt_23 == "Yes") {
                        $ta = $amountass + getgstamount($amountass, $getgstestass);
                        $te = $amountes + getgstamount($amountes, $getgstest);
                        $endoresmentass = $endoresmentass + $ta - imt23dep($inspection_id, $ta);
                        $endoresmentest = $endoresmentest + $te - imt23dep($inspection_id, $te);
                    }
                }

                if ($assismentdetail->category == "Glass") {
                    $aiglass = $aiglass + $assismentdetail->ai_part_amt;
                    $dep_amountass = getdeprecationpercentagebymonth($inspection_id, "Glass", $assismentdetail->ass_amt);
                    $dep_amountes = getdeprecationpercentagebymonth($inspection_id, "Glass", $assismentdetail->est_amt);
                    $amountass = $assismentdetail->ass_amt - $dep_amountass;
                    $amountes = $assismentdetail->est_amt;
                    $totalglassass = $totalglassass + $amountass + getgstamount($amountass, $getgstestass);
                    $totalglasses = $totalglasses + $amountes + getgstamount($amountes, $getgstest);
                    if ($assismentdetail->imt_23 == "Yes") {
                        $ta = $amountass + getgstamount($amountass, $getgstestass);
                        $te = $amountes + getgstamount($amountes, $getgstest);
                        $endoresmentass = $endoresmentass + $ta - imt23dep($inspection_id, $ta);
                        $endoresmentest = $endoresmentest + $te - imt23dep($inspection_id, $te);
                    }
                }

                if ($assismentdetail->category == "Rubber") {
                    $airubber = $airubber + $assismentdetail->ai_part_amt;
                    $dep_amountass = getdeprecationpercentagebymonth($inspection_id, "Rubber", $assismentdetail->ass_amt);
                    $dep_amountes = getdeprecationpercentagebymonth($inspection_id, "Rubber", $assismentdetail->est_amt);
                    $amountass = $assismentdetail->ass_amt - $dep_amountass;
                    $amountes = $assismentdetail->est_amt;
                    $totalrubberass = $totalrubberass + $amountass + getgstamount($amountass, $getgstestass);
                    $totalrubberes = $totalrubberes + $amountes + getgstamount($amountes, $getgstest);
                    if ($assismentdetail->imt_23 == "Yes") {
                        $ta = $amountass + getgstamount($amountass, $getgstestass);
                        $te = $amountes + getgstamount($amountes, $getgstest);
                        $endoresmentass = $endoresmentass + $ta - imt23dep($inspection_id, $ta);
                        $endoresmentest = $endoresmentest + $te - imt23dep($inspection_id, $te);
                    }
                }


                if ($assismentdetail->category == "Fibre") {
                    $aifiber = $aifiber + $assismentdetail->ai_part_amt;
                    $dep_amountass = getdeprecationpercentagebymonth($inspection_id, "Fibre", $assismentdetail->ass_amt);
                    $dep_amountes = getdeprecationpercentagebymonth($inspection_id, "Fibre", $assismentdetail->est_amt);
                    $amountass = $assismentdetail->ass_amt - $dep_amountass;
                    $amountes = $assismentdetail->est_amt;
                    $totalfiberass = $totalfiberass + $amountass + getgstamount($amountass, $getgstestass);
                    $totalfiberes = $totalfiberes + $amountes + getgstamount($amountes, $getgstest);
                    if ($assismentdetail->imt_23 == "Yes") {
                        $ta = $amountass + getgstamount($amountass, $getgstestass);
                        $te = $amountes + getgstamount($amountes, $getgstest);
                        $endoresmentass = $endoresmentass + $ta - imt23dep($inspection_id, $ta);
                        $endoresmentest = $endoresmentest + $te - imt23dep($inspection_id, $te);
                    }
                }

                if ($assismentdetail->category == "Recondition") {
                    $airecondition = $airecondition + $assismentdetail->ai_part_amt;
                    $totalreconditionass = $totalreconditionass + $assismentdetail->ass_amt;
                    $totalreconditiones = $totalreconditiones + $assismentdetail->est_amt;
                    if ($assismentdetail->imt_23 == "Yes") {
                        $ta = $assismentdetail->ass_amt;
                        $te = $assismentdetail->est_amt;
                        $endoresmentass = $endoresmentass + $ta - imt23dep($inspection_id, $ta);
                        $endoresmentest = $endoresmentest + $te - imt23dep($inspection_id, $te);
                    }
                }
            }
        }

        $inspection = Inspection::where('id', $inspection_id)->first();
        $netpaintingass = $totalpaintinglabour - getgstamount($totalpaintinglabour, 12);
        $netpaintinges = $totalpaintinglabour;
        $netlabourchargesass = $netpaintingass + $laboutass;
        $netlabourchargesEst = $netpaintinges + $labourest;
        $totatlasspart = $totalmetalass + $totalrubberass + $totalglassass + $totalfiberass + $endoresmentass;
        $totalestpart = $totalmetales + $totalrubberes + $totalglasses + $totalfiberes + $endoresmentest + $endoresmentest;
        $totalamountwithreconditionass = $totalreconditionass + $totatlasspart;
        $totalamountwithreconditiones = $totalestpart + $totalreconditiones;
        $totalass = $totalamountwithreconditionass + $netlabourchargesass;
        $totalest = $totalamountwithreconditiones + $netlabourchargesEst;
        $SalvageAmt = (isset($inspection->SalvageAmt)) ? $inspection->SalvageAmt : 0;
        $ImposedClause = (isset($inspection->ImposedClause)) ? $inspection->ImposedClause : 0;
        $lesscompolsarydeductable = (isset($inspection->CompulsoryDeductable)) ? $inspection->CompulsoryDeductable : 0;
        $TowingCharges = (isset($inspection->TowingCharges)) ? $inspection->TowingCharges : 0;
        $CustomerLiability = (isset($inspection->CustomerLiability)) ? $inspection->CustomerLiability : 0;

        // $totalass=(isset($totalass))?number_format_custom($totalass, 2):0;
        $alltotalass = $totalass + $TowingCharges - $lesscompolsarydeductable - $SalvageAmt - $ImposedClause;
        $netlibality = ($inspection->NetLiabilityOnRepairBasis != '') ? $inspection->NetLiabilityOnRepairBasis : 0;
        $netlibality = $alltotalass - $netlibality;
        $insurerlibality = $alltotalass - $CustomerLiability;

        // $totalestpart=number_format_custom($totalmetales, 2)+
        $data = [
            'inspection_id' => $inspection_id,
            'vehicle_reg_no' => vehicle_reg_no($inspection_id),
            'Insurerd_name' => insured_name($inspection_id),
            'partMetalAssamount' => $this->floatvalue(number_format_custom($totalmetalass, 2)),
            'partRubberAssamount' => $this->floatvalue(number_format_custom($totalrubberass, 2)),
            'partGlassAssamount' => $this->floatvalue(number_format_custom($totalglassass, 2)),
            'partFibreAssamount' => $this->floatvalue(number_format_custom($totalfiberass, 2)),
            'totalendoresmentAss' => $this->floatvalue(number_format_custom($endoresmentass, 2)),
            'totalassparts' => $this->floatvalue(number_format_custom($totatlasspart, 2)),
            'totalestparts' => $this->floatvalue(number_format_custom($totalestpart, 2)),
            'totalreconditionAss' => $this->floatvalue(number_format_custom($totalreconditionass, 2)),
            'totalreconditionEst' => $this->floatvalue(number_format_custom($totalreconditiones, 2)),
            'totalAssWithReconditon' => $this->floatvalue(number_format_custom($totalamountwithreconditionass, 2)),
            'totalEstWithReconditon' => $this->floatvalue(number_format_custom($totalamountwithreconditiones, 2)),
            'totallabourass' => $this->floatvalue(number_format_custom($laboutass, 2)),
            'totallabourest' => $this->floatvalue(number_format_custom($labourest, 2)),
            'paintinglabass' => $this->floatvalue(number_format_custom($netpaintingass, 2)),
            'paintinglabest' => $this->floatvalue(number_format_custom($netpaintinges, 2)),
            'netlabourAss' => $this->floatvalue(number_format_custom($netlabourchargesass, 2)),
            'netlabourEst' => $this->floatvalue(number_format_custom($netlabourchargesEst, 2)),
            'totalass' => $this->floatvalue(number_format_custom($totalass, 2)),
            'totalest' => $this->floatvalue(number_format_custom($totalest, 2)),
            'ImposedClause' => $this->floatvalue(number_format_custom($ImposedClause, 2)),
            'CompulsoryDeductable' => $this->floatvalue(number_format_custom($lesscompolsarydeductable, 2)),
            'SalvageAmt' => $this->floatvalue(number_format_custom($SalvageAmt, 2)),
            'CustomerLiability' => $this->floatvalue(number_format_custom($CustomerLiability, 2)),
            'TowingCharges' => $this->floatvalue(number_format_custom($TowingCharges, 2)),
            /*'ScrapValue'=>number_format_custom($SalvageAmt, 2),*/
            'netbody' => $this->floatvalue(number_format_custom(0, 2)),
            'alltotalass' => $this->floatvalue(number_format_custom($alltotalass, 2)),
            'alltotalest' => $this->floatvalue(number_format_custom($totalest, 2)),
            'netliability' => $this->floatvalue(number_format_custom($netlibality, 2)),
            'insurer_liability' => $insurerlibality,
            /*   'partMetalEstamount'=>number_format_custom($totalmetales, 2),
              'partRubberEstamount'=>number_format_custom($totalrubberes, 2),
              'partGlassEstamount'=>number_format_custom($totalglasses, 2),
              'partFibreEstamount'=>number_format_custom($totalfiberes, 2),

              'totalendoresmentEst'=>number_format_custom($endoresmentest, 2),*/
            /*
              'AImetalpart'=>number_format_custom($aimetal, 2),
              'AIrubberpart'=>number_format_custom($airubber, 2),
              'AIglasspart'=>number_format_custom($aiglass, 2),
              'AIfiberpart'=>number_format_custom($aifiber, 2),*/
            'AIreconditionpart' => $this->floatvalue(number_format_custom($airecondition, 2)),
            'AInetlabour' => $this->floatvalue(number_format_custom($lab_ai_amt, 2)),


        ];
        $ds = [
            'loss_estimate' => $totalest,
            'insurer_liability' => $insurerlibality
        ];
        Inspection::where('id', $inspection_id)->update($ds);
        return $this->sendResponse($data, "fetched successfully", 200);
    }

    function floatvalue($val)
    {
        $val = str_replace(",", "", $val);
        $val = preg_replace('/\.(?=.*\.)/', '', $val);
        return floatval($val);
    }

    public function get_summery_list(Request $request, $inspection_id)
    {

        $assessmentDetails = AssismentDetail::where('inspection_id', $inspection_id)->first();
        $alldetails = [];
        if (!empty($assessmentDetails)) {
            $alldetails = json_decode($assessmentDetails->alldetails, true);
        }

        $uniqueGSTValues = [];
        $data = [];
        // Loop through each item in $alldetails array
        if (!empty($alldetails) && is_array($alldetails)) {
            $getTaxDetails = TaxSetting::where('inspection_id', $inspection_id)->first();
            $getTaxDetailsArr = $getTaxDetails->toArray();

            $getUniquePartsAndLabourGST = getUniquePartsAndLabourGST($alldetails);
            $uniqueGstValue = $getUniquePartsAndLabourGST['uniqueGstValue'];
            $uniqueLabourGstValue = $getUniquePartsAndLabourGST['uniqueLabourGstValue'];
            sort($uniqueGstValue);

            $getPartsGstCondition = getPartsGstCondition($getTaxDetailsArr, $uniqueGstValue);
            $multipleEstGSTonParts = $getPartsGstCondition['MultipleEstPartsGst'];
            $nonMultipleEstGSTonParts = $getPartsGstCondition['nonMultipleEstPartsGst'];
            $multipleAssGSTonParts = $getPartsGstCondition['MultipleAssPartsGst'];
            $nonMultipleAssGSTonParts = $getPartsGstCondition['nonMultipleAssPartsGst'];
            $uniqueGstValue = array_unique(array_merge($multipleEstGSTonParts, $nonMultipleEstGSTonParts, $multipleAssGSTonParts, $nonMultipleAssGSTonParts));
            sort($uniqueGstValue);

            $gstIndexWiseAmt = [];
            //Sub Total Counting Variable Start
            $SubTotalEstAmt = 0;
            $SubTotalEstGstAmt = 0;
            $SubTotalEstWithGstAmt = 0;

            $SubTotalEstIMTAmt = 0;
            $SubTotalEstIMTGstAmt = 0;
            $SubTotalEstIMTWithGstAmt = 0;

            $subTotalEstimatedRecAmt = 0;
            $subTotalEstRecdGSTAmt = 0;
            $subTotalEstRecdWithGSTAmt = 0;

            $subTotalEstimatedRecIMTAmt = 0;
            $subTotalIMTEstRecdGSTAmt = 0;
            $subTotalIMTEstRecdWithGSTAmt = 0;

            $subTotalEstimatedExcludingRecAmt = 0;
            $subTotalEstExcludingRecdGSTAmt = 0;
            $subTotalEstExcludingRecdWithGSTAmt = 0;

            $subTotalEstimatedExcludingRecIMTAmt = 0;
            $subTotalIMTEstExcludingRecdGSTAmt = 0;
            $subTotalIMTEstExcludingRecdWithGSTAmt = 0;

            $SubTotalMetal = 0;
            $SubTotalMetalDep = 0;
            $SubTotalMetalGstAmt = 0;
            $SubTotalMetalWithGst = 0;

            $SubTotalIMTMetal = 0;
            $SubTotalIMTMetalDep = 0;
            $SubTotalIMTMetalLess = 0;
            $SubTotalIMTMetalGstAmt = 0;
            $SubTotalIMTMetalWithGst = 0;

            $SubTotalRubberPlast = 0;
            $SubTotalRubberPlastDep = 0;
            $SubTotalRubberPlastGstAmt = 0;
            $SubTotalRubberPlastWithGst = 0;

            $SubTotalIMTRubberPlast = 0;
            $SubTotalIMTRubberPlastDep = 0;
            $SubTotalIMTRubberPlastLess = 0;
            $SubTotalIMTRubberPlastGstAmt = 0;
            $SubTotalIMTRubberPlastWithGst = 0;

            $SubTotalGlass = 0;
            $SubTotalGlassDep = 0;
            $SubTotalGlassGstAmt = 0;
            $SubTotalGlassWithGst = 0;

            $SubTotalIMTGlass = 0;
            $SubTotalIMTGlassDep = 0;
            $SubTotalIMTGlassGstAmt = 0;
            $SubTotalIMTGlassWithGst = 0;

            $SubTotalFiber = 0;
            $SubTotalFiberDep = 0;
            $SubTotalFiberGstAmt = 0;
            $SubTotalFiberWithGst = 0;

            $SubTotalIMTFiber = 0;
            $SubTotalIMTFiberDep = 0;
            $SubTotalIMTFiberGstAmt = 0;
            $SubTotalIMTFiberWithGst = 0;

            $SubTotalRecond = 0;
            $SubTotalRecondGstAmt = 0;
            $SubTotalRecondWithGst = 0;

            $SubTotalIMTRecond = 0;
            $SubTotalIMTRecondGstAmt = 0;
            $SubTotalIMTRecondWithGst = 0;

            foreach($uniqueGstValue as $rate) {
                //Total Counting Variable Start
                $totalEstimatedAmt = 0;
                $totalEstimatedIMTAmt = 0;

                $totalEstimatedRecAmt = 0;
                $totalEstimatedRecIMTAmt = 0;

                $totalEstimatedExcludingRecAmt = 0;
                $totalEstimatedExcludingRecIMTAmt = 0;

                $totalAssessedMetalAmt = 0;
                $totalAssessedRubPlastAmt = 0;
                $totalAssessedGlassAmt = 0;
                $totalAssessedFiberAmt = 0;
                $totalAssessedReconditionAmt = 0;

                //IMT Total
                $totalAssessedMetalIMTAmt = 0;
                $totalAssessedRubPlastIMTAmt = 0;
                $totalAssessedGlassIMTAmt = 0;
                $totalAssessedFiberImtAmt = 0;
                $totalAssessedRecImtAmt = 0;
                //Total Counting Variable End
                foreach($alldetails as $detail) {
                    if(isset($detail['category']) && !empty($detail['category'])) {
                        $detailGst = !empty($detail['gst']) ? intval($detail['gst']) : 0;
                        $detailMetal = 0;
                        $detailRubber = 0;
                        $detailGlass = 0;
                        $detailFibre = 0;
                        $detailRecondition = 0;

                        $detailIMTMetal = 0;
                        $detailIMTRubber = 0;
                        $detailIMTGlass = 0;
                        $detailIMTFiber = 0;
                        $detailIMTRecondition = 0;

                        if(empty($detail['quantities']) && !empty($detail['category'])) {
                            switch ($detail['category']) {
                                case 'Metal':
                                    if(!empty($detail['imt_23']) && $detail['imt_23'] == 'Yes') {
                                        $detailIMTMetal = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                    } else {
                                        $detailMetal = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                    }
                                    break;
                                case 'Rubber':
                                    if(!empty($detail['imt_23']) && $detail['imt_23'] == 'Yes') {
                                        $detailIMTRubber = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                    } else {
                                        $detailRubber = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                    }
                                    break;
                                case 'Glass':
                                    if(!empty($detail['imt_23']) && $detail['imt_23'] == 'Yes') {
                                        $detailIMTGlass = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                    } else {
                                        $detailGlass = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                    }
                                    break;
                                case 'Fibre':
                                    if(!empty($detail['imt_23']) && $detail['imt_23'] == 'Yes') {
                                        $detailIMTFiber = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                    } else {
                                        $detailFibre = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                    }
                                    break;
                                case 'Recondition':
                                    if(!empty($detail['imt_23']) && $detail['imt_23'] == 'Yes') {
                                        $detailIMTRecondition = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                    } else {
                                        $detailRecondition = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                    }
                                    break;
                                default:break;
                            }

                            if(!empty($multipleAssGSTonParts) && isset($multipleAssGSTonParts[$rate]) && $detailGst==$rate) {
                                $totalAssessedMetalAmt += $detailMetal;
                                $totalAssessedRubPlastAmt += $detailRubber;
                                $totalAssessedGlassAmt += $detailGlass;
                                $totalAssessedFiberAmt += $detailFibre;
                                $totalAssessedReconditionAmt += $detailRecondition;

                                $totalAssessedMetalIMTAmt += $detailIMTMetal;
                                $totalAssessedRubPlastIMTAmt += $detailIMTRubber;
                                $totalAssessedGlassIMTAmt += $detailIMTGlass;
                                $totalAssessedFiberImtAmt += $detailIMTFiber;
                                $totalAssessedRecImtAmt += $detailIMTRecondition;
                            } elseif(!empty($nonMultipleAssGSTonParts) && isset($nonMultipleAssGSTonParts[$rate])) {
                                $totalAssessedMetalAmt += $detailMetal;
                                $totalAssessedRubPlastAmt += $detailRubber;
                                $totalAssessedGlassAmt += $detailGlass;
                                $totalAssessedFiberAmt += $detailFibre;
                                $totalAssessedReconditionAmt += $detailRecondition;

                                $totalAssessedMetalIMTAmt += $detailIMTMetal;
                                $totalAssessedRubPlastIMTAmt += $detailIMTRubber;
                                $totalAssessedGlassIMTAmt += $detailIMTGlass;
                                $totalAssessedFiberImtAmt += $detailIMTFiber;
                                $totalAssessedRecImtAmt += $detailIMTRecondition;
                            }
                        }

                        if(!empty($multipleEstGSTonParts) && isset($multipleEstGSTonParts[$rate]) && $detailGst==$rate) {
                            if(!empty($detail['imt_23']) && $detail['imt_23'] == 'Yes') {
                                $totalEstimatedIMTAmt += ($detail['est_amt'] > 0) ? $detail['est_amt'] : 0;
                                $totalEstimatedExcludingRecIMTAmt += ($detail['category'] != 'Recondition') ? $detail['est_amt'] : 0;
                                $totalEstimatedRecIMTAmt += ($detail['category'] == 'Recondition') ? $detail['est_amt'] : 0;
                            } else {
                                $totalEstimatedAmt += ($detail['est_amt'] > 0) ? $detail['est_amt'] : 0;
                                $totalEstimatedExcludingRecAmt += ($detail['category'] != 'Recondition') ? $detail['est_amt'] : 0;
                                $totalEstimatedRecAmt += ($detail['category'] == 'Recondition') ? $detail['est_amt'] : 0;
                            }
                        } elseif(!empty($nonMultipleEstGSTonParts) && isset($nonMultipleEstGSTonParts[$rate])) {
                            if(!empty($detail['imt_23']) && $detail['imt_23'] == 'Yes') {
                                $totalEstimatedIMTAmt += ($detail['est_amt'] > 0) ? $detail['est_amt'] : 0;
                                $totalEstimatedExcludingRecIMTAmt += ($detail['category'] != 'Recondition') ? $detail['est_amt'] : 0;
                                $totalEstimatedRecIMTAmt += ($detail['category'] == 'Recondition') ? $detail['est_amt'] : 0;
                            } else {
                                $totalEstimatedAmt += ($detail['est_amt'] > 0) ? $detail['est_amt'] : 0;
                                $totalEstimatedExcludingRecAmt += ($detail['category'] != 'Recondition') ? $detail['est_amt'] : 0;
                                $totalEstimatedRecAmt += ($detail['category'] == 'Recondition') ? $detail['est_amt'] : 0;
                            }
                        }
                    }

                    if(!empty($detail['quantities'])) {
                        foreach($detail['quantities'] as $quantity) {
                            $quantityGst = !empty($quantity['gst']) ? intval($quantity['gst']) : 0;
                            if(isset($quantity['category']) &&  !empty($quantity['category'])){
                                $quantityMetal = 0;
                                $quantityRubber = 0;
                                $quantityGlass = 0;
                                $quantityFiber = 0;
                                $quantityRecondition = 0;

                                $quantityIMTMetal = 0;
                                $quantityIMTRubber = 0;
                                $quantityIMTGlass = 0;
                                $quantityIMTFiber = 0;
                                $quantityIMTRecondition = 0;

                                switch ($quantity['category']) {
                                    case 'Metal':
                                        if(!empty($quantity['imt_23']) && $quantity['imt_23'] == 'Yes') {
                                            $quantityIMTMetal = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                        } else {
                                            $quantityMetal = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                        }
                                        break;
                                    case 'Rubber':
                                        if(!empty($quantity['imt_23']) && $quantity['imt_23'] == 'Yes') {
                                            $quantityIMTRubber = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                        } else {
                                            $quantityRubber = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                        }
                                        break;
                                    case 'Glass':
                                        if(!empty($quantity['imt_23']) && $quantity['imt_23'] == 'Yes') {
                                            $quantityIMTGlass = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                        } else {
                                            $quantityGlass = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                        }
                                        break;
                                    case 'Fibre':
                                        if(!empty($quantity['imt_23']) && $quantity['imt_23'] == 'Yes') {
                                            $quantityIMTFiber = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                        } else {
                                            $quantityFiber = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                        }
                                        break;
                                    case 'Recondition':
                                        if(!empty($quantity['imt_23']) && $quantity['imt_23'] == 'Yes') {
                                            $quantityIMTRecondition = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                        } else {
                                            $quantityRecondition = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                        }
                                        break;
                                    default:break;
                                }

                                if(!empty($multipleAssGSTonParts) && isset($multipleAssGSTonParts[$rate]) && $quantityGst==$rate) {
                                    $totalAssessedMetalAmt += $quantityMetal;
                                    $totalAssessedRubPlastAmt += $quantityRubber;
                                    $totalAssessedGlassAmt += $quantityGlass;
                                    $totalAssessedFiberAmt += $quantityFiber;
                                    $totalAssessedReconditionAmt += $quantityRecondition;

                                    $totalAssessedMetalIMTAmt += $quantityIMTMetal;
                                    $totalAssessedRubPlastIMTAmt += $quantityIMTRubber;
                                    $totalAssessedGlassIMTAmt += $quantityIMTGlass;
                                    $totalAssessedFiberImtAmt += $quantityIMTFiber;
                                    $totalAssessedRecImtAmt += $quantityIMTRecondition;
                                } elseif(!empty($nonMultipleAssGSTonParts) && isset($nonMultipleAssGSTonParts[$rate])) {
                                    $totalAssessedMetalAmt += $quantityMetal;
                                    $totalAssessedRubPlastAmt += $quantityRubber;
                                    $totalAssessedGlassAmt += $quantityGlass;
                                    $totalAssessedFiberAmt += $quantityFiber;
                                    $totalAssessedReconditionAmt += $quantityRecondition;

                                    $totalAssessedMetalIMTAmt += $quantityIMTMetal;
                                    $totalAssessedRubPlastIMTAmt += $quantityIMTRubber;
                                    $totalAssessedGlassIMTAmt += $quantityIMTGlass;
                                    $totalAssessedFiberImtAmt += $quantityIMTFiber;
                                    $totalAssessedRecImtAmt += $quantityIMTRecondition;
                                }
                            }
                        }
                    }
                }

                $metalAmtDep = 0;
                $metalIMTAmtDep = 0;
                if($getTaxDetails['MetalDepPer'] > 0 && empty($getTaxDetails['IsZeroDep'])) {
                    $metalAmtDep = (($totalAssessedMetalAmt * $getTaxDetails['MetalDepPer']) / 100);
                    $metalIMTAmtDep = (($totalAssessedMetalIMTAmt * $getTaxDetails['MetalDepPer']) / 100);
                }

                $rubberPlastAmtDep = 0;
                $rubberPlastIMTAmtDep = 0;
                if($getTaxDetails['RubberDepPer'] > 0 && empty($getTaxDetails['IsZeroDep'])) {
                    $rubberPlastAmtDep = (($totalAssessedRubPlastAmt * $getTaxDetails['RubberDepPer']) / 100);
                    $rubberPlastIMTAmtDep = (($totalAssessedRubPlastIMTAmt * $getTaxDetails['RubberDepPer']) / 100);
                }

                $glassAmtDep = 0;
                $glassIMTAmtDep = 0;
                if($getTaxDetails['GlassDepPer'] > 0 && empty($getTaxDetails['IsZeroDep'])) {
                    $glassAmtDep = (($totalAssessedGlassAmt * $getTaxDetails['GlassDepPer']) / 100);
                    $glassIMTAmtDep = (($totalAssessedGlassIMTAmt * $getTaxDetails['GlassDepPer']) / 100);
                }

                $fiberAmtDep = 0;
                $fiberIMTAmtDep = 0;
                if($getTaxDetails['FibreDepPer'] > 0 && empty($getTaxDetails['IsZeroDep']) && $totalAssessedFiberAmt > 0) {
                    $fiberAmtDep = (($totalAssessedFiberAmt * $getTaxDetails['FibreDepPer']) / 100);
                    $fiberIMTAmtDep = (($totalAssessedFiberImtAmt * $getTaxDetails['FibreDepPer']) / 100);
                }

                $addLessMetalImt = 0;
                $addLesRubPlastImt = 0;
                if($totalAssessedMetalIMTAmt > 0) {
                    $metalIMTAmtAfterDep = ($totalAssessedMetalIMTAmt - $metalIMTAmtDep);
                    $addLessMetalImt = (($metalIMTAmtAfterDep * $getTaxDetails['IMT23DepPer']) / 100);
                }

                if($totalAssessedRubPlastIMTAmt > 0) {
                    $rubberPlastIMTAmtAfterDep = ($totalAssessedRubPlastIMTAmt - $rubberPlastIMTAmtDep);
                    $addLesRubPlastImt = (($rubberPlastIMTAmtAfterDep * $getTaxDetails['IMT23DepPer']) / 100);
                }

                //Calculating|Adding GST(%)
                $totalEstGSTAmt = (($totalEstimatedAmt * $rate) / 100);
                $totalIMTEstGSTAmt = (($totalEstimatedIMTAmt * $rate) / 100);

                $totalEstRecdGSTAmt = (($totalEstimatedRecAmt * $rate) / 100);
                $totalIMTEstRecdGSTAmt = (($totalEstimatedRecIMTAmt * $rate) / 100);

                $totalEstExcludingRecdGSTAmt = (($totalEstimatedExcludingRecAmt * $rate) / 100);
                $totalIMTEstExcludingRecdGSTAmt = (($totalEstimatedExcludingRecIMTAmt * $rate) / 100);

                $metalAfterAllDepLess = ($totalAssessedMetalAmt - $metalAmtDep);
                $metalIMTAfterAllDepLess = ($totalAssessedMetalIMTAmt - ($metalIMTAmtDep + $addLessMetalImt));

                $metalGstAmt = (($metalAfterAllDepLess * $rate) / 100);
                $metalIMTGstAmt = (($metalIMTAfterAllDepLess * $rate) / 100);

                $rubberAfterAllDepLess = ($totalAssessedRubPlastAmt - $rubberPlastAmtDep);
                $rubberIMTAfterAllDepLess = ($totalAssessedRubPlastIMTAmt - ($rubberPlastIMTAmtDep + $addLesRubPlastImt));

                $rubberPlastGstAmt = (($rubberAfterAllDepLess * $rate) / 100);
                $rubberPlastIMTGstAmt = (($rubberIMTAfterAllDepLess * $rate) / 100);

                $glassAfterAllDepLess = ($totalAssessedGlassAmt - $glassAmtDep);
                $glassIMTAfterAllDepLess = ($totalAssessedGlassIMTAmt - $glassIMTAmtDep);

                $glassGstAmt = (($glassAfterAllDepLess * $rate) / 100);
                $glassIMTGstAmt = (($glassIMTAfterAllDepLess * $rate) / 100);

                $fiberAllDepLess = ($totalAssessedFiberAmt - $fiberAmtDep);
                $fiberIMTAllDepLess = ($totalAssessedFiberImtAmt - $fiberIMTAmtDep);

                $fiberGstAmt = (($fiberAllDepLess * $rate) / 100);
                $fiberIMTGstAmt = (($fiberIMTAllDepLess * $rate) / 100);

                $recGstAmt = (($totalAssessedReconditionAmt * $rate) / 100);
                $recIMTGstAmt = (($totalAssessedRecImtAmt * $rate) / 100);

                $SubTotalEstAmt += $totalEstimatedAmt;
                $SubTotalEstGstAmt += $totalEstGSTAmt;
                $SubTotalEstWithGstAmt += ($totalEstimatedAmt + $totalEstGSTAmt);

                $SubTotalEstIMTAmt += $totalEstimatedIMTAmt;
                $SubTotalEstIMTGstAmt += $totalIMTEstGSTAmt;
                $SubTotalEstIMTWithGstAmt += ($totalEstimatedIMTAmt + $totalIMTEstGSTAmt);

                $subTotalEstimatedRecAmt += $totalEstimatedRecAmt;
                $subTotalEstRecdGSTAmt += $totalEstRecdGSTAmt;
                $subTotalEstRecdWithGSTAmt += ($totalEstimatedRecAmt + $totalEstRecdGSTAmt);

                $subTotalEstimatedRecIMTAmt += $totalEstimatedRecIMTAmt;
                $subTotalIMTEstRecdGSTAmt += $totalIMTEstRecdGSTAmt;
                $subTotalIMTEstRecdWithGSTAmt += ($totalEstimatedRecIMTAmt + $totalIMTEstRecdGSTAmt);

                $subTotalEstimatedExcludingRecAmt += $totalEstimatedExcludingRecAmt;
                $subTotalEstExcludingRecdGSTAmt += $totalEstExcludingRecdGSTAmt;
                $subTotalEstExcludingRecdWithGSTAmt += ($totalEstimatedExcludingRecAmt + $totalEstExcludingRecdGSTAmt);

                $subTotalEstimatedExcludingRecIMTAmt += $totalEstimatedExcludingRecIMTAmt;
                $subTotalIMTEstExcludingRecdGSTAmt += $totalIMTEstExcludingRecdGSTAmt;
                $subTotalIMTEstExcludingRecdWithGSTAmt += ($totalEstimatedExcludingRecIMTAmt + $totalIMTEstExcludingRecdGSTAmt);


                $SubTotalMetal += $totalAssessedMetalAmt;
                $SubTotalMetalDep += $metalAmtDep;
                $SubTotalMetalGstAmt += $metalGstAmt;
                $SubTotalMetalWithGst += (($totalAssessedMetalAmt - $metalAmtDep) + $metalGstAmt);

                $SubTotalIMTMetal += $totalAssessedMetalIMTAmt;
                $SubTotalIMTMetalDep += $metalIMTAmtDep;
                $SubTotalIMTMetalLess += $addLessMetalImt;
                $SubTotalIMTMetalGstAmt += $metalIMTGstAmt;
                $SubTotalIMTMetalWithGst += (($totalAssessedMetalIMTAmt - ($metalIMTAmtDep + $addLessMetalImt)) + $metalIMTGstAmt);

                $SubTotalRubberPlast += $totalAssessedRubPlastAmt;
                $SubTotalRubberPlastDep += $rubberPlastAmtDep;
                $SubTotalRubberPlastGstAmt += $rubberPlastGstAmt;
                $SubTotalRubberPlastWithGst += (($totalAssessedRubPlastAmt - $rubberPlastAmtDep) + $rubberPlastGstAmt);

                $SubTotalIMTRubberPlast += $totalAssessedRubPlastIMTAmt;
                $SubTotalIMTRubberPlastDep += $rubberPlastIMTAmtDep;
                $SubTotalIMTRubberPlastLess += $addLesRubPlastImt;
                $SubTotalIMTRubberPlastGstAmt += $rubberPlastIMTGstAmt;
                $SubTotalIMTRubberPlastWithGst += (($totalAssessedRubPlastIMTAmt - ($rubberPlastIMTAmtDep + $addLesRubPlastImt)) + $rubberPlastIMTGstAmt);


                $SubTotalGlass += $totalAssessedGlassAmt;
                $SubTotalGlassDep += $glassAmtDep;
                $SubTotalGlassGstAmt += $glassGstAmt;
                $SubTotalGlassWithGst += (($totalAssessedGlassAmt - $glassAmtDep) + $glassGstAmt);

                $SubTotalIMTGlass += $totalAssessedGlassIMTAmt;
                $SubTotalIMTGlassDep += $glassIMTAmtDep;
                $SubTotalIMTGlassGstAmt += $glassIMTGstAmt;
                $SubTotalIMTGlassWithGst += (($totalAssessedGlassIMTAmt - $glassIMTAmtDep) + $glassIMTGstAmt);

                $SubTotalFiber += $totalAssessedFiberAmt;
                $SubTotalFiberDep += $fiberAmtDep;
                $SubTotalFiberGstAmt += $fiberGstAmt;
                $SubTotalFiberWithGst += (($totalAssessedFiberAmt - $fiberAmtDep) + $fiberGstAmt);

                $SubTotalIMTFiber += $totalAssessedFiberImtAmt;
                $SubTotalIMTFiberDep += $fiberIMTAmtDep;
                $SubTotalIMTFiberGstAmt += $fiberIMTGstAmt;
                $SubTotalIMTFiberWithGst += (($totalAssessedFiberImtAmt - $fiberIMTAmtDep) + $fiberIMTGstAmt);

                $SubTotalRecond += $totalAssessedReconditionAmt;
                $SubTotalRecondGstAmt += $recGstAmt;
                $SubTotalRecondWithGst += ($totalAssessedReconditionAmt + $recGstAmt);

                $SubTotalIMTRecond += $totalAssessedRecImtAmt;
                $SubTotalIMTRecondGstAmt += $recIMTGstAmt;
                $SubTotalIMTRecondWithGst += ($totalAssessedRecImtAmt + $recIMTGstAmt);

            }

            $getLabourGstCondition = getLabourGstCondition($getTaxDetails, $uniqueLabourGstValue);

            $labourEstMultipleGst = $getLabourGstCondition['labourEstMultipleGst'];
            $labourEstNoneMultipleGst = $getLabourGstCondition['labourEstNoneMultipleGst'];
            $labourAssMultipleGst = $getLabourGstCondition['labourAssMultipleGst'];
            $labourAssNoneMultipleGst = $getLabourGstCondition['labourAssNoneMultipleGst'];
            $labourUniqueGst = array_unique(array_merge($labourEstMultipleGst, $labourEstNoneMultipleGst, $labourAssMultipleGst, $labourAssNoneMultipleGst));
            sort($labourUniqueGst);

            $gstLabourIndexWiseAmt = [];
            //Sub Total Counting Variable Start
            $SubTotalEstimatedLabourAmt = 0;
            $SubTotalEstimatedLabourGstAmt = 0;
            $SubTotalEstimatedLabourAmtWithGst = 0;

            $SubTotalAssLabourAmt = 0;
            $SubTotalAssLabourGstAmt = 0;
            $SubTotalAssLabourAmtWithGst = 0;

            $SubTotalAssLabourImtAmt = 0;
            $SubTotalAssLabourImtGstAmt = 0;
            $SubTotalAssLabourImtAmtWithGst = 0;

            $SubTotalPaintingAmt = 0;
            $totalLess50DepPainting = 0;
            $totalPaintingAfterLess50Dep = 0;
            $SubTotalPaintingGstAmt = 0;
            $SubTotalPaintingAmtWithGst = 0;

            $SubTotalPaintingAmtImt = 0;
            $totalLess50DepPaintingImt = 0;
            $totalPaintingImtAfterLess50Dep = 0;
            $totalPaintingImtAfterLessAdd = 0;
            $totalPaintingImtGstAmt = 0;
            $totalPaintingImtAmtWithGst = 0;

            if(!empty($labourUniqueGst)) {
                foreach($labourUniqueGst as $gst) {
                    $totalEstimatedLabourAmt = 0;
                    $totalAssLabourAmt = 0;
                    $totalAssLabourImtAmt = 0;
                    $totalPaintingAmt = 0;
                    $totalPaintingAmtImt = 0;
                    foreach($alldetails as $labour) {
                        $labourGst = !empty($labour['gst']) ? $labour['gst'] : 0;
                        if($labour['ass_lab'] > 0 || $labour['est_lab'] > 0 || $labour['painting_lab'] > 0) {
                            if(!empty($labourEstMultipleGst) && isset($labourEstMultipleGst[$gst]) && $labourGst==$gst) {
                                $totalEstimatedLabourAmt += ($labour['est_lab'] > 0) ? $labour['est_lab'] : 0;
                            } elseif(!empty($labourEstNoneMultipleGst) && isset($labourEstNoneMultipleGst[$gst])){
                                $totalEstimatedLabourAmt += ($labour['est_lab'] > 0) ? $labour['est_lab'] : 0;
                            }
                            if(empty($labour['quantities'])){
                                if(!empty($labourAssMultipleGst) && isset($labourAssMultipleGst[$gst]) && $labourGst==$gst) {
                                    if($labour['imt_23'] == "Yes") {
                                        $totalPaintingAmtImt += ($labour['painting_lab'] > 0) ? $labour['painting_lab'] : 0;
                                        $totalAssLabourImtAmt += ($labour['ass_lab'] > 0) ? $labour['ass_lab'] : 0;
                                    } else {
                                        $totalPaintingAmt += ($labour['painting_lab'] > 0) ? $labour['painting_lab'] : 0;
                                        $totalAssLabourAmt += ($labour['ass_lab'] > 0) ? $labour['ass_lab'] : 0;
                                    }
                                } elseif(!empty($labourAssNoneMultipleGst) && isset($labourAssNoneMultipleGst[$gst])) {
                                    if($labour['imt_23'] == "Yes") {
                                        $totalPaintingAmtImt += ($labour['painting_lab'] > 0) ? $labour['painting_lab'] : 0;
                                        $totalAssLabourImtAmt += ($labour['ass_lab'] > 0) ? $labour['ass_lab'] : 0;
                                    } else {
                                        $totalPaintingAmt += ($labour['painting_lab'] > 0) ? $labour['painting_lab'] : 0;
                                        $totalAssLabourAmt += ($labour['ass_lab'] > 0) ? $labour['ass_lab'] : 0;
                                    }
                                }
                            }
                        }

                        if(!empty($labour['quantities'])) {
                            foreach ($labour['quantities'] as $quantities) {
                                if($quantities['ass_lab'] > 0 || $quantities['est_lab'] > 0 || $quantities['painting_lab'] > 0) {
                                    $labourQuntityGst = !empty($quantities['gst']) ? $quantities['gst'] : 0;
                                    if (!empty($labourAssMultipleGst) && isset($labourAssMultipleGst[$gst]) && $labourQuntityGst == $gst) {
                                        if ($quantities['imt_23'] == "Yes") {
                                            $totalPaintingAmtImt += ($quantities['painting_lab'] > 0) ? $quantities['painting_lab'] : 0;
                                            $totalAssLabourImtAmt += ($quantities['ass_lab'] > 0) ? $quantities['ass_lab'] : 0;
                                        } else {
                                            $totalPaintingAmt += ($quantities['painting_lab'] > 0) ? $quantities['painting_lab'] : 0;
                                            $totalAssLabourAmt += ($quantities['ass_lab'] > 0) ? $quantities['ass_lab'] : 0;
                                        }
                                    } elseif (!empty($labourAssNoneMultipleGst) && isset($labourAssNoneMultipleGst[$gst])) {
                                        if ($quantities['imt_23'] == "Yes") {
                                            $totalPaintingAmtImt += ($quantities['painting_lab'] > 0) ? $quantities['painting_lab'] : 0;
                                            $totalAssLabourImtAmt += ($quantities['ass_lab'] > 0) ? $quantities['ass_lab'] : 0;
                                        } else {
                                            $totalPaintingAmt += ($quantities['painting_lab'] > 0) ? $quantities['painting_lab'] : 0;
                                            $totalAssLabourAmt += ($quantities['ass_lab'] > 0) ? $quantities['ass_lab'] : 0;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $SubTotalEstimatedLabourAmt += $totalEstimatedLabourAmt;
                    $SubTotalAssLabourAmt += $totalAssLabourAmt;
                    $SubTotalAssLabourImtAmt += $totalAssLabourImtAmt;
                    $SubTotalPaintingAmt += $totalPaintingAmt;
                    $SubTotalPaintingAmtImt += $totalPaintingAmtImt;

                    $less50DepPainting = 0;
                    $less50DepPaintingImt = 0;
                    if(empty($getTaxDetails['IsZeroDep']) || $getTaxDetails['IsZeroDep']==0) {
                        $less50DepPainting = ((($totalPaintingAmt * 25) / 100) / 2);
                        $less50DepPaintingImt = ((($totalPaintingAmtImt * 25) / 100) / 2);
                    }
                    $totalLess50DepPainting += $less50DepPainting;
                    $totalLess50DepPaintingImt += $less50DepPaintingImt;
                    $totalPaintingAfterLess50Dep += ($totalPaintingAmt - $totalLess50DepPainting);
                    $totalPaintingImtAfterLess50Dep += ($totalPaintingAmtImt - $less50DepPaintingImt);
                    $addLessOnImtPainting = 0;
                    if($totalPaintingAmtImt > 0 && $getTaxDetails['IMT23DepPer'] > 0) {
                        $addLessOnImtPainting = ((($totalPaintingAmtImt - $less50DepPaintingImt) * $getTaxDetails['IMT23DepPer']) / 100);
                    }
                    $totalPaintingImtAfterLessAdd += $addLessOnImtPainting;

                    $addEstLabGstAmt = (($totalEstimatedLabourAmt * $gst) / 100);
                    $SubTotalEstimatedLabourGstAmt += $addEstLabGstAmt;
                    $SubTotalEstimatedLabourAmtWithGst += ($totalEstimatedLabourAmt + $addEstLabGstAmt);

                    $addAssLabourGstAmt = (($totalAssLabourAmt * $gst) / 100);
                    $SubTotalAssLabourGstAmt += $addAssLabourGstAmt;
                    $SubTotalAssLabourAmtWithGst += ($totalAssLabourAmt + $addAssLabourGstAmt);

                    $addAssImtLabourGstAmt = (($totalAssLabourImtAmt * $gst) / 100);
                    $SubTotalAssLabourImtGstAmt += $addAssImtLabourGstAmt;
                    $SubTotalAssLabourImtAmtWithGst += ($totalAssLabourImtAmt + $addAssImtLabourGstAmt);

                    $addPaintingGstAmt = ((($totalPaintingAmt - $less50DepPainting) * $gst) / 100);
                    $SubTotalPaintingGstAmt += $addPaintingGstAmt;
                    $SubTotalPaintingAmtWithGst += (($totalPaintingAmt + $addPaintingGstAmt) - $less50DepPainting);

                    $tempImtPaintingTotal = ($totalPaintingAmtImt - ($less50DepPaintingImt + $addLessOnImtPainting));
                    $addImtPaintingGstAmt = (($tempImtPaintingTotal * $gst) / 100);
                    $totalPaintingImtGstAmt += $addImtPaintingGstAmt;
                    $totalPaintingImtAmtWithGst += ($tempImtPaintingTotal + $addImtPaintingGstAmt);
                }
            }

            $netlabourAss = ($SubTotalAssLabourAmtWithGst + $SubTotalAssLabourImtAmtWithGst + $SubTotalPaintingAmtWithGst + $totalPaintingImtAmtWithGst);
            $totalendoresmentestonly = ($subTotalIMTEstRecdWithGSTAmt + $subTotalIMTEstExcludingRecdWithGSTAmt);
            $totalendoresmentAss = ($SubTotalIMTMetalWithGst + $SubTotalIMTRubberPlastWithGst + $SubTotalIMTGlassWithGst +$SubTotalIMTFiberWithGst + $SubTotalIMTRecondWithGst);
            $totalreconditionestonly = $subTotalEstRecdWithGSTAmt;
            $totalreconditionAss = $SubTotalRecondWithGst;
            $totalLabEstimatedAmtWithGst = ($SubTotalEstimatedLabourAmtWithGst + $SubTotalAssLabourImtAmtWithGst);
            $totalest = ($subTotalEstExcludingRecdWithGSTAmt + $totalendoresmentestonly + $totalreconditionestonly + $totalLabEstimatedAmtWithGst);
            $totalass = ($SubTotalMetalWithGst + $SubTotalRubberPlastWithGst + $SubTotalGlassWithGst + $SubTotalFiberWithGst + $totalendoresmentAss + $totalreconditionAss + $netlabourAss);

            $totalAssWithReconditon = ($SubTotalMetalWithGst + $SubTotalIMTMetalWithGst + $SubTotalRubberPlastWithGst + $SubTotalIMTRubberPlastWithGst + $SubTotalGlassWithGst + $SubTotalIMTGlassWithGst + $SubTotalFiberWithGst + $SubTotalIMTFiberWithGst + $SubTotalRecondWithGst + $SubTotalIMTRecondWithGst);
            $totalEstWithReconditon = ($SubTotalEstIMTWithGstAmt + $SubTotalEstWithGstAmt);
            $totalEstPartsAmtWithoutRecondition = ($subTotalEstExcludingRecdWithGSTAmt + $subTotalIMTEstExcludingRecdWithGSTAmt);
            $totalAssPartsAmtWithoutRecondition = ($SubTotalMetalWithGst + $SubTotalIMTMetalWithGst + $SubTotalRubberPlastWithGst + $SubTotalIMTRubberPlastWithGst + $SubTotalGlassWithGst + $SubTotalIMTGlassWithGst + $SubTotalFiberWithGst + $SubTotalIMTFiberWithGst);
            $totalreconditionEst = $subTotalEstRecdWithGSTAmt;

            // Inspection Details
            $inspection = Inspection::where('id', $inspection_id)->first();
            $SalvageAmt = (isset($inspection->SalvageAmt)) ? $inspection->SalvageAmt : 0;
            $ImposedClause = (isset($inspection->ImposedClause)) ? $inspection->ImposedClause : 0;
            $lesscompolsarydeductable = (isset($inspection->CompulsoryDeductable)) ? $inspection->CompulsoryDeductable : 0;
            $TowingCharges = (isset($inspection->TowingCharges)) ? $inspection->TowingCharges : 0;
            $less_voluntary_excess = (isset($inspection->less_voluntary_excess)) ? $inspection->less_voluntary_excess : 0;
            $additional_towing = (isset($inspection->additional_towing)) ? $inspection->additional_towing : 0;
            $CustomerLiability = (isset($inspection->CustomerLiability)) ? $inspection->CustomerLiability : 0;

            $totalLessFromNetAmt = ($ImposedClause + $lesscompolsarydeductable + $SalvageAmt + $less_voluntary_excess);
            $totalAdditionInNetAmt = ($TowingCharges + $additional_towing);

            $alltotalass = (($totalass + $totalAdditionInNetAmt) - $totalLessFromNetAmt);

            $data = [
                'inspection_id' => $inspection_id,
                'vehicle_reg_no' => vehicle_reg_no($inspection_id),
                'Insurerd_name' => insured_name($inspection_id),
                //Estimate Calculation
                'totalestonlypart' => number_format_custom($subTotalEstExcludingRecdWithGSTAmt),
                'totalendoresmentestonly' => number_format_custom($totalendoresmentestonly),
                'totalreconditionestonly' => number_format_custom($totalreconditionestonly),

                //Parts Assessment Calculation
                'partMetalAssamount' => number_format_custom($SubTotalMetalWithGst),
                'partRubberAssamount' => number_format_custom($SubTotalRubberPlastWithGst),
                'partGlassAssamount' => number_format_custom($SubTotalGlassWithGst),
                'partFibreAssamount' => number_format_custom($SubTotalFiberWithGst),

                //Recondition Calculation
                'totalreconditionAss' => number_format_custom($totalreconditionAss),
                //All IMT23 Assessed Amount Calculation
                'totalendoresmentAss' => number_format_custom($totalendoresmentAss),

                //Total Estimated|Assessed Parts Amount with IMT23 including Calculation.
                'totalestparts' => number_format_custom($totalEstPartsAmtWithoutRecondition),
                'totalassparts' => number_format_custom($totalAssPartsAmtWithoutRecondition),

                'totalEstWithReconditon' => number_format_custom($totalEstWithReconditon),
                'totalAssWithReconditon' => number_format_custom($totalAssWithReconditon),

                //Total Estimated None IMT23 Recondition Parts Amount Calculation.
                'totalreconditionEst' => number_format_custom($totalreconditionEst),

                //Total Labour Estimate Calculation
                'total_labestAmtWithoutGST' => number_format_custom($SubTotalEstimatedLabourAmt),
                'totallabourest' => number_format_custom($SubTotalEstimatedLabourAmtWithGst),

                //Total Labour Assessed Calculation
                'total_labassAmtWithoutGST' => number_format_custom(($SubTotalAssLabourAmt + $SubTotalAssLabourImtAmt)),
                'totallabourass' => number_format_custom(($SubTotalAssLabourAmtWithGst + $SubTotalAssLabourImtAmtWithGst)),
                'total_labourAmtWithGst' => number_format_custom($totalLabEstimatedAmtWithGst),


                'total_paintingassAmtWithoutGST' => number_format_custom(($SubTotalPaintingAmt + $SubTotalPaintingAmtImt)),

                //Total None IMT Painting Amount after Depreciation with GST
                'paintinglabass' => number_format_custom($SubTotalPaintingAmtWithGst),

                //Total IMT Painting Amount after Depreciation with GST
                'IMTPaintingLabAss' => number_format_custom($totalPaintingImtAmtWithGst),

                //Total Labour & IMT & Non IMT Painting After Depreciation With Gst.
                'netlabourAss' => number_format_custom($netlabourAss),

                //Total Labour EST Amt With Gst.
                'netlabourEst' => number_format_custom($SubTotalEstimatedLabourAmtWithGst),

                'totalest' => number_format_custom($totalest),
                'totalass' => number_format_custom($totalass),

                'ImposedClause' => number_format_custom($ImposedClause),
                'CompulsoryDeductable' => number_format_custom($lesscompolsarydeductable),
                'SalvageAmt' => number_format_custom($SalvageAmt),
                'CustomerLiability' => number_format_custom($CustomerLiability),
                'TowingCharges' => number_format_custom($TowingCharges),
                'less_voluntary_excess' => number_format_custom($less_voluntary_excess),
                'additional_towing' => number_format_custom($additional_towing),

                //Total Net Amount After less clause AMT & addition of towing and addition towing.
                'alltotalass' => number_format_custom($alltotalass),
                'insurer_liability' => number_format_custom($alltotalass),

                'totalmateriallab' => number_format_custom(0),
                'PaintingMaterialDepAmt' => number_format_custom(0),
                'PaitingMaterialAfterDep' => number_format_custom(0),

                //Total EST Parts Amt Without GST
                'total_EstAmt' => number_format_custom($SubTotalEstAmt),

                //Total Assessed Non IMT Parts Amt Without GST
                'totalMetalAmt' => number_format_custom($SubTotalMetal),
                'totalRubberAmt' => number_format_custom($SubTotalRubberPlast),
                'totalGlassAmt' => number_format_custom(($SubTotalGlass + $SubTotalIMTGlass)),
                'totalFibreAmt' => number_format_custom(($SubTotalFiber + $SubTotalIMTFiber)),
                'totalReconditionAmt' => number_format_custom(($SubTotalRecond + $SubTotalIMTRecond)),

                //Total Assessed IMT Parts Amt Without GST
                'totalMetalIMTAmt' => number_format_custom($SubTotalIMTMetal),
                'totalRubberIMTAmt' => number_format_custom($SubTotalIMTRubberPlast),

                //Total Dep AMT Non IMT Parts
                'DepAmtMetal' => number_format_custom($SubTotalMetalDep),
                'DepAmtRubber' => number_format_custom($SubTotalRubberPlastDep),
                'DepAmtGlass' => number_format_custom(($SubTotalGlassDep + $SubTotalIMTGlassDep)),
                'DepAmtFibre' => number_format_custom(($SubTotalFiberDep + $SubTotalIMTFiberDep)),

                //Total Dep AMT IMT Parts
                'DepAmtIMTMetal' => number_format_custom(($SubTotalIMTMetalDep + $SubTotalIMTMetalLess)),
                'DepAmtIMTRubber' => number_format_custom(($SubTotalRubberPlastDep + $SubTotalRubberPlastGstAmt)),

                //not done
                'paintinglabest' => number_format_custom(0),
                'IMTPaintingLabEst' => number_format_custom(0),
                'netbody' => number_format_custom(0, 2),
            ];

            $assessmentReports = [
                'inspection_id' => $inspection_id,
                'totalMetalNonIMT' => number_format_custom($SubTotalMetal),
                'totalRubberNonIMT' => number_format_custom($SubTotalRubberPlast),
                'totalGlass' => number_format_custom(($SubTotalGlass + $SubTotalIMTGlass)),
                'totalFiber' => number_format_custom(($SubTotalFiber + $SubTotalIMTFiber)),
                'totalMetalIMT' => number_format_custom($SubTotalIMTMetal),
                'totalRubberIMT' => number_format_custom($SubTotalIMTRubberPlast),
                'depMetalNonIMT' => number_format_custom($SubTotalMetalDep),
                'depRubberNonIMT' => number_format_custom($SubTotalRubberPlastDep),
                'depGlass' => number_format_custom(($SubTotalGlassDep + $SubTotalIMTGlassDep)),
                'depFiber' => number_format_custom(($SubTotalFiberDep + $SubTotalIMTFiberDep)),
                'DepMetalIMT' => number_format_custom(($SubTotalIMTMetalDep + $SubTotalIMTMetalLess)),
                'DepRubberIMT' => number_format_custom(($SubTotalIMTRubberPlastDep + $SubTotalIMTRubberPlastLess)),
                'gstAmtMetal' => number_format_custom($SubTotalMetalGstAmt),
                'gstAmtRubber' => number_format_custom($SubTotalRubberPlastGstAmt),
                'gstAmtGlass' => number_format_custom($SubTotalGlassGstAmt),
                'gstAmtIMTMetal' => number_format_custom($SubTotalIMTMetalGstAmt),
                'gstAmtIMTRubber' => number_format_custom($SubTotalIMTRubberPlastGstAmt),
                'totallabour' => number_format_custom(($SubTotalAssLabourAmt + $SubTotalAssLabourImtAmt)),
                'gstlabour' => number_format_custom(($SubTotalAssLabourGstAmt + $SubTotalAssLabourImtGstAmt)),
                'totalPainting' => number_format_custom($SubTotalPaintingAmt),
                'totalPaintingIMT' => number_format_custom($SubTotalPaintingAmtImt),
                'depAmtPainting' => number_format_custom($totalLess50DepPainting),
                'depAmtPaintingIMT' => number_format_custom(($totalLess50DepPaintingImt + $totalPaintingImtAfterLessAdd)),
                'gstPainting' => number_format_custom($SubTotalPaintingGstAmt),
                'gstPaintingIMT' => number_format_custom($totalPaintingImtGstAmt),
            ];

            $record = AssismentCalculation::createOrUpdate($data);
            $assessmentReports = AssessmentReport::createOrUpdate($assessmentReports);
            $loss_estimate = ($SubTotalEstWithGstAmt + $SubTotalEstIMTWithGstAmt + $totalLabEstimatedAmtWithGst);
            $insurer_liability = $alltotalass;
            $ds = [
                'loss_estimate' => number_format_custom($loss_estimate),
                'insurer_liability' => number_format_custom($insurer_liability),
            ];

            Inspection::where('id', $inspection_id)->update($ds);
        }

        return $this->sendResponse($data, "fetched successfully", 200);

    }

    private function labourCharges($laborCharges, $getTaxDetails)
    {
        $uniqueLabourGSTValues = [];
        // Loop through each item in $alldetails array
        foreach ($laborCharges as $detail) {
            if (isset($detail['est_lab']) && $detail['est_lab'] != "0.00" || ($detail['painting_lab'] != "0.00" || $detail['ass_lab'] != "0.00")) {
                // Check if the current GST value exists in $uniqueGSTValues array
                if (!in_array($detail['gst'], $uniqueLabourGSTValues)) {
                    // If not, add it to the $uniqueGSTValues array
                    $uniqueLabourGSTValues[] = $detail['gst'];
                }
            }

            if (isset($detail['quantities'])) {
                foreach ($detail['quantities'] as $subpartValue) {
                    if (isset($subpartValue['painting_lab']) || $subpartValue['ass_lab'] != "0.00") {
                        if (!in_array($subpartValue['gst'], $uniqueLabourGSTValues)) {
                            // If not, add it to the $uniqueLabourGSTValues array
                            $uniqueLabourGSTValues[] = $subpartValue['gst'];
                        }
                    }
                }
            }
        }
        $subUniqueGstValue = array_values(array_unique($uniqueLabourGSTValues));
        sort($subUniqueGstValue);
        $gstAmtEstLabour = 0;
        $gstAmtAssLabour = 0;
        $gstAmtPaintingLabour = 0;
        $totalIMT23PaintingLabourWithGST = 0;
        $paintingMaterialAmt = 0;
        $totalEstLabourAmtWithoutGST = 0;
        $totalAssLabourAmtWithoutGST = 0;
        $totalPaintingLabourAmtWithoutGST = 0;
        $totalMaterialLabourAmt = 0;
        $gstTotallabour = 0;
        $totalPainting = 0;
        $totalPaintingIMT = 0;
        $depAmtPainting = 0;
        $depAmtPaintingIMT = 0;
        $gstPainting = 0;
        $gstPaintingIMT = 0;
        $paitingDepAmt = 0;
        $IMT23PaintingDepAmt = 0;
        $paintingAmtAfterDep = 0;
        $totalLabEstAmtWithGST = 0;
        $totalLabAssAmtWithGST = 0;
        $totalPaintingLabAmtWithGST = 0;
        $totalIMT23PaintingLabAmtwithGST = 0;
        $tempAmt = 0;

        foreach ($subUniqueGstValue as $labourGSTvalue) {
            $totalEstlabAmt = 0; // Initialize total estimated amount
            $totalAsslabAmt = 0;
            $totalPaintinglabAmt = 0;
            $IMT23PaintingLabTotalEstAmt = 0;
            $imt23PaintinglabTotalAmount = 0;
            $uniqueGstRates[] = $labourGSTvalue; // Add the current GST rate to the list of unique rates

            foreach ($laborCharges as $ind => $detail) {
                if ($detail['gst'] == $labourGSTvalue) {
                    $totalEstlabAmt += !empty($detail['est_lab']) ? $detail['est_lab'] : 0;
                    if ($detail['imt_23'] == "Yes") {
                        $IMT23PaintingLabTotalEstAmt += !empty($detail['est_lab']) ? $detail['est_lab'] : 0;
                    }

                    if (isset($detail['quantities']) && sizeof($detail['quantities']) == 0) {
                        $tempAmt += $detail['painting_lab'];
                        if (!empty($detail['imt_23']) && $detail['imt_23'] == "Yes") {
                            $imt23PaintinglabTotalAmount += !empty($detail['painting_lab']) ? $detail['painting_lab'] : 0;
                        } else {
                            $totalPaintinglabAmt += !empty($detail['painting_lab']) ? $detail['painting_lab'] : 0;
                        }
                        $totalAsslabAmt += !empty($detail['ass_lab']) ? $detail['ass_lab'] : 0;
                    }
                }

                if (isset($detail['quantities'])) {
                    foreach ($detail['quantities'] as $partQuantity) {
                        $tempAmt += $partQuantity['painting_lab'];
                        if ($partQuantity['gst'] == $labourGSTvalue) {
                            if ($partQuantity['imt_23'] == "Yes") {
                                $imt23PaintinglabTotalAmount += !empty($partQuantity['painting_lab']) ? $partQuantity['painting_lab'] : 0;
                            } else {
                                $totalPaintinglabAmt += !empty($partQuantity['painting_lab']) ? $partQuantity['painting_lab'] : 0;
                            }
                            $totalAsslabAmt += !empty($partQuantity['ass_lab']) ? $partQuantity['ass_lab'] : 0;
                        }
                    }
                }
            }
            $totalMaterialLabourAmt += $totalPaintinglabAmt;
            $paintingMaterialAmt = getgstamount($totalPaintinglabAmt, 25);

            if ($getTaxDetails->IsZeroDep == 1) {
                $paitingDepAmt = 0;
            } else {
                $paitingDepAmt = getgstamount($paintingMaterialAmt, 50);
            }
            $paintingAmtAfterDep = ($totalPaintinglabAmt - $paitingDepAmt);
            // IMT 23 Painting
            $IMT23PaintingMaterialAmt = getgstamount($imt23PaintinglabTotalAmount, 25);
            if ($getTaxDetails->IsZeroDep == 1) {
                $IMT23PaintingDepAmt = 0;
            } else {
                $IMT23PaintingDepAmt = getgstamount($IMT23PaintingMaterialAmt, 50);
            }
            $IMT23PaintingAfterDep = ($imt23PaintinglabTotalAmount - $IMT23PaintingDepAmt);
            $IMT23DepAmt = getgstamount($IMT23PaintingAfterDep, $getTaxDetails->IMT23DepPer);
            $paintingLabourAfterIMT23Dep = ($IMT23PaintingAfterDep - $IMT23DepAmt);

            // Added GST Total Amount
            if ($getTaxDetails->GSTonEstimatedLab == "Y") {
                if ($getTaxDetails->MultipleGSTonLab == 1) {
                    $gstAmtEstLabour = getgstamount($totalEstlabAmt, $labourGSTvalue);
                } else {
                    $gstAmtEstLabour = getgstamount($totalEstlabAmt, $getTaxDetails->GSTLabourPer);
                }
            } else {
                $gstAmtEstLabour = getgstamount($totalEstlabAmt, 0);
            }

            if ($getTaxDetails->GstonAssessedLab == "Y") {
                if ($getTaxDetails->MultipleGSTonLab == 1) {
                    $gstAmtAssLabour = getgstamount($totalAsslabAmt, $labourGSTvalue);
                    $gstAmtPaintingLabour = getgstamount($paintingAmtAfterDep, $labourGSTvalue);
                    // IMT 23 Painting
                    $IMT23PaintingLabourGSTAmt = getgstamount($paintingLabourAfterIMT23Dep, $labourGSTvalue);
                } else {
                    $gstAmtAssLabour = getgstamount($totalAsslabAmt, $getTaxDetails->GSTLabourPer);
                    $gstAmtPaintingLabour = getgstamount($paintingAmtAfterDep, $getTaxDetails->GSTLabourPer);
                    // IMT 23 Painting
                    $IMT23PaintingLabourGSTAmt = getgstamount($paintingLabourAfterIMT23Dep, $getTaxDetails->GSTLabourPer);
                }
            } else {
                $gstAmtAssLabour = getgstamount($totalAsslabAmt, 0);
                $gstAmtPaintingLabour = getgstamount($paintingAmtAfterDep, 0);
                // IMT 23 Painting
                $IMT23PaintingLabourGSTAmt = getgstamount($paintingLabourAfterIMT23Dep, 0);
            }

            $totalLabEstAmtWithGST += $gstAmtEstLabour + $totalEstlabAmt;
            $totalLabAssAmtWithGST += $gstAmtAssLabour + $totalAsslabAmt;
            $totalPaintingLabAmtWithGST += $gstAmtPaintingLabour + $paintingAmtAfterDep;
            $totalIMT23PaintingLabAmtwithGST += $IMT23PaintingLabourGSTAmt + $paintingLabourAfterIMT23Dep;
            $totalEstLabourAmtWithoutGST += $totalEstlabAmt;
            $totalAssLabourAmtWithoutGST += $totalAsslabAmt;
            $totalPaintingLabourAmtWithoutGST += $totalPaintinglabAmt + $imt23PaintinglabTotalAmount;
            $gstTotallabour += $gstAmtAssLabour;
            $totalPainting += $totalPaintinglabAmt;
            $totalPaintingIMT += $imt23PaintinglabTotalAmount;
            $depAmtPainting += $paitingDepAmt;
            $depAmtPaintingIMT += $IMT23PaintingDepAmt + $IMT23DepAmt;
            $gstPainting += $gstAmtPaintingLabour;
            $gstPaintingIMT += $IMT23PaintingLabourGSTAmt;

        }

        return (object)[
            'totalLabourEstAmt' => $totalEstLabourAmtWithoutGST,
            'totalLabourAssAmt' => $totalAssLabourAmtWithoutGST,
            'totalLabourPaintingAmt' => $totalPaintingLabourAmtWithoutGST,
            'totalLabourEstAmtWithGST' => $totalLabEstAmtWithGST,
            'totalLabourAssAmtWithGST' => $totalLabAssAmtWithGST,
            'totalPaintingLabourAmtWithGST' => $totalPaintingLabAmtWithGST,
            'totalIMT23PaintingLabourWithGST' => $totalIMT23PaintingLabAmtwithGST,
            'paintingMaterialAmt' => $paintingMaterialAmt,
            'paitingDepAmt' => $paitingDepAmt,
            'paintingAmtAfterDep' => $paintingAmtAfterDep,
            'gstlabour' => $gstTotallabour,
            'total_painting' => $totalPainting,
            'total_paintingIMT' => $totalPaintingIMT,
            'depAmtPainting' => $depAmtPainting,
            'depAmtPaintingIMT' => $depAmtPaintingIMT,
            'gstPainting' => $gstPainting,
            'gstPaintingIMT' => $gstPaintingIMT,
        ];

    }

    public function getsummary1(Request $request, $inspection_id)
    {

        $totalmetalass = 0;
        $totalfiberass = 0;
        $totalrubberass = 0;
        $totalglassass = 0;
        $totalreconditionass = 0;
        $totalmetales = 0;
        $totalfiberes = 0;
        $totalrubberes = 0;
        $totalglasses = 0;
        $totalreconditiones = 0;

        $aimetal = 0;
        $aifiber = 0;
        $airubber = 0;
        $aiglass = 0;
        $airecondition = 0;


        $endoresmentass = 0;
        $endoresmentest = 0;
        $laboutass = 0;
        $labourest = 0;
        $totalpaintinglabour = 0;

        $lab_ai_amt = 0;

        $paintinglabourassimt23 = 0;
        $paintinglabourestimt23 = 0;
        $totalpaintinglabourestimate = 0;
        $totalmateriallab = 0;
        $PaintingMaterialDepAmt = 0;
        $PaitingMaterialAfterDep = 0;
        $totalmateriallabour = 0;
        $GSTamount = 0;

        $totalmateriallabIMT = 0;
        $totalmateriallabourIMT = 0;
        $PaintingMaterialDepAmtIMT = 0;
        $PaitingMaterialAfterDepIMT = 0;
        $GSTamountIMT = 0;
        $PaintingIMTDepAmount = 0;


        $inspection = Inspection::where('id', $inspection_id)->first();

        $taxdetail = TaxSetting::where('inspection_id', $inspection_id)->first();
        if (!$taxdetail) {

            $data = [
                'inspection_id' => $inspection_id,
                'vehicle_reg_no' => vehicle_reg_no($inspection_id),
                'Insurerd_name' => insured_name($inspection_id),
                'partMetalAssamount' => number_format_custom(0, 2),
                'partRubberAssamount' => number_format_custom(0, 2),
                'partGlassAssamount' => number_format_custom(0, 2),
                'partFibreAssamount' => number_format_custom(0, 2),
                'totalendoresmentAss' => number_format_custom(0, 2),
                'totalassparts' => number_format_custom(0, 2),
                'totalestparts' => number_format_custom(0, 2),
                'totalreconditionAss' => number_format_custom(0, 2),
                'totalreconditionEst' => number_format_custom(0, 2),
                'totalAssWithReconditon' => number_format_custom(0, 2),
                'totalEstWithReconditon' => number_format_custom(0, 2),
                'totallabourass' => number_format_custom(0, 2),
                'totallabourest' => number_format_custom(0, 2),
                'paintinglabass' => number_format_custom(0, 2),
                'paintinglabes' => number_format_custom(0, 2),
                'netlabourAss' => number_format_custom(0, 2),
                'netlabourEst' => number_format_custom(0, 2),
                'totalass' => number_format_custom(0, 2),
                'totalest' => number_format_custom(0, 2),
                'ImposedClause' => number_format_custom(0, 2),
                'CompulsoryDeductable' => number_format_custom(0, 2),
                'SalvageAmt' => number_format_custom(0, 2),
                'CustomerLiability' => number_format_custom(0, 2),
                'TowingCharges' => number_format_custom(0, 2),
                /*'ScrapValue'=>number_format_custom($SalvageAmt, 2),*/
                'netbody' => number_format_custom(0, 2),
                'alltotalass' => number_format_custom(0, 2),
                'alltotalest' => number_format_custom(0, 2),
                'AIreconditionpart' => number_format_custom(0, 2),
                'AInetlabour' => number_format_custom(0, 2),
                'insurer_liability' => 0
            ];


            return $this->sendResponse($data, "fetched successfully", 200);
        }


        $assisment = DB::table('tbl_ms_assessment_detail_list')->where('inspection_id', $inspection_id)->get();


        $dep = checkdep($inspection_id);

        $metaldep = $dep['metal'];
        $glassdep = $dep['Glass'];
        $rubberdep = $dep['Rubber'];
        $fibredep = $dep['Fibre'];
        $reconditiondep = $dep['Recondition'];
        $imt23 = 50;


        foreach ($assisment as $assismentdetail) {

            $lab_ai_amt = $lab_ai_amt + $assismentdetail->lab_ai_amt;

            $materiallab = getgstamount($assismentdetail->painting_lab, 25);

            $materiallabour = getgstamount($assismentdetail->painting_lab, 75);


            if ($taxdetail->IsZeroDep == 0 || $taxdetail->IsZeroDep == '') {
                $materiallabafterdep = $materiallab - getgstamount($materiallab, 50);
            } else {
                $materiallabafterdep = $materiallab;
            }


            if ($assismentdetail->imt_23 != "Yes") {

                $totalmateriallab = $totalmateriallab + $materiallab;

                $PaintingMaterialDepAmt = $PaintingMaterialDepAmt + getgstamount($materiallab, 50);

                $totalmateriallabour = $totalmateriallabour + $materiallabour;

                $PaitingMaterialAfterDep = $PaitingMaterialAfterDep + $materiallabafterdep;


                $pass = $materiallabafterdep + $materiallabour;
                /*
                    echo $totalpaintinglabour;
                    echo '----------';
                    echo $pass;
                    echo '----------';
                    echo labourgstass($inspection_id,$pass,$assismentdetail->gst);
                    die('xxxxxxxxxxx');*/
                $totalpaintinglabour = $totalpaintinglabour + $pass + labourgstass($inspection_id, $pass, $assismentdetail->gst);


                $GSTamount = $GSTamount + labourgstass($inspection_id, $pass, $assismentdetail->gst);


                $totalpaintinglabourestimate = $totalpaintinglabourestimate + $assismentdetail->painting_lab + labourgstest($inspection_id, $assismentdetail->painting_lab, $assismentdetail->gst);
            } else {
                $totalmateriallabIMT = $totalmateriallabIMT + $materiallab;
                $PaintingMaterialDepAmtIMT = $PaintingMaterialDepAmtIMT + getgstamount($materiallab, 50);


                $PaitingMaterialAfterDepIMT = $PaitingMaterialAfterDepIMT + $materiallabafterdep;

                $totalmateriallabourIMT = $totalmateriallabourIMT + $materiallabour;


                $pantingimt23 = $materiallabafterdep + $materiallabour;
                $pantinglabagetdep = $pantingimt23 - getgstamount($pantingimt23, $imt23);

                $PaintingIMTDepAmount = $PaintingIMTDepAmount + getgstamount($pantingimt23, $imt23);

                $paintinglabourassimt23 = $paintinglabourassimt23 + $pantinglabagetdep + labourgstass($inspection_id, $pantinglabagetdep, $assismentdetail->gst);


                $GSTamountIMT = $GSTamountIMT + labourgstass($inspection_id, $pantinglabagetdep, $assismentdetail->gst);


                $paintinglabourestimt23 = $paintinglabourestimt23 + $assismentdetail->painting_lab + labourgstest($inspection_id, $assismentdetail->painting_lab, $assismentdetail->gst);
            }


            $laboutass = $laboutass + $assismentdetail->ass_lab + labourgstass($inspection_id, $assismentdetail->ass_lab, $assismentdetail->gst);

            $labourest = $labourest + $assismentdetail->est_lab + labourgstest($inspection_id, $assismentdetail->est_lab, $assismentdetail->gst);

            $getgstest = checkgstpartEst($inspection_id, $assismentdetail->id);
            $getgstestass = checkgstpartAss($inspection_id, $assismentdetail->id);

            $gstper = checkgst($inspection_id, $assismentdetail->gst);

            $gstest = $gstper['GSTEstimatedPartsPer'];
            $gstass = $gstper['GSTAssessedPartsPer'];


            if ($assismentdetail->category == "Metal" && $assismentdetail->imt_23 != "Yes") {

                $aimetal = $aimetal + $assismentdetail->ai_part_amt;


                $dep_amountass = getgstamount($assismentdetail->ass_amt, $metaldep);

                $amountass = $assismentdetail->ass_amt - $dep_amountass;

                $amountes = $assismentdetail->est_amt;


                $totalmetalass = $totalmetalass + $amountass + getgstamount($amountass, $gstass);

                $totalmetales = $totalmetales + $amountes + getgstamount($amountes, $gstest);
            }

            if ($assismentdetail->category == "Glass" && $assismentdetail->imt_23 != "Yes") {

                $aiglass = $aiglass + $assismentdetail->ai_part_amt;


                $dep_amountass = getgstamount($assismentdetail->ass_amt, $glassdep);

                $amountass = $assismentdetail->ass_amt - $dep_amountass;

                $amountes = $assismentdetail->est_amt;


                $totalglassass = $totalglassass + $amountass + getgstamount($amountass, $gstass);

                $totalglasses = $totalglasses + $amountes + getgstamount($amountes, $gstest);
            }

            if ($assismentdetail->category == "Rubber" && $assismentdetail->imt_23 != "Yes") {

                $airubber = $airubber + $assismentdetail->ai_part_amt;


                $dep_amountass = getgstamount($assismentdetail->ass_amt, $rubberdep);

                $amountass = $assismentdetail->ass_amt - $dep_amountass;

                $amountes = $assismentdetail->est_amt;

                $totalrubberass = $totalrubberass + $amountass + getgstamount($amountass, $gstass);

                $totalrubberes = $totalrubberes + $amountes + getgstamount($amountes, $gstest);
            }


            if ($assismentdetail->category == "Fibre" && $assismentdetail->imt_23 != "Yes") {

                $aifiber = $aifiber + $assismentdetail->ai_part_amt;


                $dep_amountass = getgstamount($assismentdetail->ass_amt, $fibredep);

                $amountass = $assismentdetail->ass_amt - $dep_amountass;

                $amountes = $assismentdetail->est_amt;


                $totalfiberass = $totalfiberass + $amountass + getgstamount($amountass, $gstass);

                $totalfiberes = $totalfiberes + $amountes + getgstamount($amountes, $gstest);
            }


            if ($assismentdetail->category == "Recondition" && $assismentdetail->imt_23 != "Yes") {


                $airecondition = $airecondition + $assismentdetail->ai_part_amt;

                $totalreconditionass = $totalreconditionass + $assismentdetail->ass_amt;
                $totalreconditiones = $totalreconditiones + $assismentdetail->est_amt;
            }

            if ($assismentdetail->imt_23 == "Yes") {
                $amout = 0;
                if ($assismentdetail->category == "Metal") {

                    $dep_amountass = getgstamount($assismentdetail->ass_amt, $metaldep);
                    $amout = $assismentdetail->ass_amt - $dep_amountass;
                    $amout = $amout + getgstamount($amout, $gstass);
                }
                if ($assismentdetail->category == "Rubber") {
                    $dep_amountass = getgstamount($assismentdetail->ass_amt, $rubberdep);
                    $amout = $assismentdetail->ass_amt - $dep_amountass;
                    $amout = $amout + getgstamount($amout, $gstass);
                }
                if ($assismentdetail->category == "Fibre") {
                    $dep_amountass = getgstamount($assismentdetail->ass_amt, $fibredep);
                    $amout = $assismentdetail->ass_amt - $dep_amountass;
                    $amout = $amout + getgstamount($amout, $gstass);
                }
                if ($assismentdetail->category == "Glass") {
                    $dep_amountass = getgstamount($assismentdetail->ass_amt, $glassdep);
                    $amout = $assismentdetail->ass_amt - $dep_amountass;
                    $amout = $amout + getgstamount($amout, $gstass);
                }
                if ($assismentdetail->category == "Recondition") {
                    $amout = $assismentdetail->ass_amt;
                }


                $ta = $assismentdetail->ass_amt;
                $te = $assismentdetail->est_amt + getgstamount($assismentdetail->est_amt, $gstest);


                $endoresmentass = $endoresmentass + $amout - getgstamount($amout, $imt23);


                $endoresmentest = $endoresmentest + $te;
            }
        }


        $inspection = Inspection::where('id', $inspection_id)->first();

        $netpaintingass = $totalpaintinglabour;       //-getgstamount($totalpaintinglabour,12);
        $netpaintinges = $totalpaintinglabourestimate;


        $netlabourchargesass = $netpaintingass + $laboutass + $paintinglabourassimt23;
        $netlabourchargesEst = $netpaintinges + $labourest + $paintinglabourestimt23;


        $totatlasspart = $totalmetalass + $totalrubberass + $totalglassass + $totalfiberass + $endoresmentass;


        $totatlassonlypart = $totalmetalass + $totalrubberass + $totalglassass + $totalfiberass;

        $totalestonlypart = $totalmetales + $totalrubberes + $totalglasses + $totalfiberes;

        $totalestpart = $totalmetales + $totalrubberes + $totalglasses + $totalfiberes + $endoresmentest;


        $totalamountwithreconditionass = $totalreconditionass + $totatlasspart;
        $totalamountwithreconditiones = $totalestpart + $totalreconditiones;

        $totalass = $totalamountwithreconditionass + $netlabourchargesass;

        $totalest = $totalamountwithreconditiones + $netlabourchargesEst;


        $SalvageAmt = (isset($inspection->SalvageAmt)) ? $inspection->SalvageAmt : 0;


        $ImposedClause = (isset($inspection->ImposedClause)) ? $inspection->ImposedClause : 0;


        $lesscompolsarydeductable = (isset($inspection->CompulsoryDeductable)) ? $inspection->CompulsoryDeductable : 0;


        $TowingCharges = (isset($inspection->TowingCharges)) ? $inspection->TowingCharges : 0;
        $less_voluntary_excess = (isset($inspection->less_voluntary_excess)) ? $inspection->less_voluntary_excess : 0;
        $additional_towing = (isset($inspection->additional_towing)) ? $inspection->additional_towing : 0;

        $CustomerLiability = (isset($inspection->CustomerLiability)) ? $inspection->CustomerLiability : 0;


        // $totalass=(isset($totalass))?number_format_custom($totalass, 2):0;


        $alltotalass = $totalass + $TowingCharges - $lesscompolsarydeductable - $SalvageAmt - $ImposedClause;


        $insurerlibality = $alltotalass - $CustomerLiability;
        // $totalestpart=number_format_custom($totalmetales, 2)+

        $data = [
            'inspection_id' => $inspection_id,
            'vehicle_reg_no' => vehicle_reg_no($inspection_id),
            'Insurerd_name' => insured_name($inspection_id),
            'totalestonlypart' => $this->floatvalue(number_format_custom($totalestonlypart, 2)),
            'totalreconditionestonly' => $this->floatvalue(number_format_custom($totalreconditiones, 2)),
            'totalendoresmentestonly' => $this->floatvalue(number_format_custom($endoresmentest, 2)),
            'partMetalAssamount' => $this->floatvalue(number_format_custom($totalmetalass, 2)),
            'partRubberAssamount' => $this->floatvalue(number_format_custom($totalrubberass, 2)),
            'partGlassAssamount' => $this->floatvalue(number_format_custom($totalglassass, 2)),
            'partFibreAssamount' => $this->floatvalue(number_format_custom($totalfiberass, 2)),
            'totalendoresmentAss' => $this->floatvalue(number_format_custom($endoresmentass, 2)),
            'totalassparts' => $this->floatvalue(number_format_custom($totatlasspart, 2)),
            'totalestparts' => $this->floatvalue(number_format_custom($totalestpart, 2)),
            'totalreconditionAss' => $this->floatvalue(number_format_custom($totalreconditionass, 2)),
            'totalreconditionEst' => $this->floatvalue(number_format_custom($totalreconditiones, 2)),
            'totalAssWithReconditon' => $this->floatvalue(number_format_custom($totalamountwithreconditionass, 2)),
            'totalEstWithReconditon' => $this->floatvalue(number_format_custom($totalamountwithreconditiones, 2)),
            'totallabourass' => $this->floatvalue(number_format_custom($laboutass, 2)),
            'totallabourest' => $this->floatvalue(number_format_custom($labourest, 2)),
            'paintinglabass' => $this->floatvalue(number_format_custom($netpaintingass, 2)),
            'paintinglabest' => $this->floatvalue(number_format_custom($netpaintinges, 2)),
            'IMTPaintingLabAss' => $this->floatvalue(number_format_custom($paintinglabourassimt23, 2)),
            'IMTPaintingLabEst' => $this->floatvalue(number_format_custom($paintinglabourestimt23, 2)),
            'netlabourAss' => $this->floatvalue(number_format_custom($netlabourchargesass, 2)),
            'netlabourEst' => $this->floatvalue(number_format_custom($netlabourchargesEst, 2)),
            'totalass' => $this->floatvalue(number_format_custom($totalass, 2)),
            'totalest' => $this->floatvalue(number_format_custom($totalest, 2)),
            'ImposedClause' => $this->floatvalue(number_format_custom($ImposedClause, 2)),
            'CompulsoryDeductable' => $this->floatvalue(number_format_custom($lesscompolsarydeductable, 2)),
            'SalvageAmt' => $this->floatvalue(number_format_custom($SalvageAmt, 2)),
            'CustomerLiability' => $this->floatvalue(number_format_custom($CustomerLiability, 2)),
            'TowingCharges' => $this->floatvalue(number_format_custom($TowingCharges, 2)),
            'less_voluntary_excess' => $this->floatvalue(number_format_custom($less_voluntary_excess, 2)),
            'additional_towing' => $this->floatvalue(number_format_custom($additional_towing, 2)),
            /*'ScrapValue'=>number_format_custom($SalvageAmt, 2),*/
            'netbody' => $this->floatvalue(number_format_custom(0, 2)),
            'alltotalass' => $this->floatvalue(number_format_custom($alltotalass, 2)),

            'totalmateriallab' => $this->floatvalue(number_format_custom($totalmateriallab, 2)),
            'PaintingMaterialDepAmt' => $this->floatvalue(number_format_custom($PaintingMaterialDepAmt, 2)),
            'PaitingMaterialAfterDep' => $this->floatvalue(number_format_custom($PaitingMaterialAfterDep, 2)),
            'LabourAmt' => $this->floatvalue(number_format_custom($totalmateriallabour, 2)),


            'GSTamount' => $this->floatvalue(number_format_custom($GSTamount, 2)),
            'LabourAmtIMT' => $this->floatvalue(number_format_custom($totalmateriallabourIMT, 2)),
            'PaintingMaterialDepAmtIMT' => $this->floatvalue(number_format_custom($PaintingMaterialDepAmtIMT, 2)),
            'PaitingMaterialAfterDepIMT' => $this->floatvalue(number_format_custom($PaitingMaterialAfterDepIMT, 2)),
            'totalmateriallabIMT' => $this->floatvalue(number_format_custom($totalmateriallabIMT, 2)),
            'GSTamountIMT' => $this->floatvalue(number_format_custom($GSTamountIMT, 2)),
            'PaintingIMTDepAmount' => $this->floatvalue(number_format_custom($PaintingIMTDepAmount, 2)),
            'insurer_liability' => $insurerlibality,
            /* 'partMetalEstamount'=>number_format_custom($totalmetales, 2),
              'partRubberEstamount'=>number_format_custom($totalrubberes, 2),
              'partGlassEstamount'=>number_format_custom($totalglasses, 2),
              'partFibreEstamount'=>number_format_custom($totalfiberes, 2),

              'totalendoresmentEst'=>number_format_custom($endoresmentest, 2),*/
            /*
              'AImetalpart'=>number_format_custom($aimetal, 2),
              'AIrubberpart'=>number_format_custom($airubber, 2),
              'AIglasspart'=>number_format_custom($aiglass, 2),
              'AIfiberpart'=>number_format_custom($aifiber, 2), */
            'AIreconditionpart' => $this->floatvalue(number_format_custom($airecondition, 2)),
            'AInetlabour' => $this->floatvalue(number_format_custom($lab_ai_amt, 2)),


        ];

        $record = AssismentCalculation::createOrUpdate($data);

        $ds = [
            'loss_estimate' => $totalest,
            'insurer_liability' => $insurerlibality
        ];

        Inspection::where('id', $inspection_id)->update($ds);


        return $this->sendResponse($data, "fetched successfully", 200);
    }

    public function getdeptaxok(Request $request, $inspection_id)
    {

        $MetalDepPer = 0;
        $GlassDepPer = 0;
        $RubberDepPer = 0;
        $FibreDepPer = 0;

        if ($request->IsZeroDep == 1) {
            $MetalDepPer = getdeprecationpercentagebymonth($inspection_id, 'Metal');
            $GlassDepPer = getdeprecationpercentagebymonth($inspection_id, 'Glass');
            $RubberDepPer = getdeprecationpercentagebymonth($inspection_id, 'Rubber');
            $FibreDepPer = getdeprecationpercentagebymonth($inspection_id, 'Fibre');
        }

        $dasend = [
            'MetalDepPer' => $MetalDepPer,
            'RubberDepPer' => $RubberDepPer,
            'GlassDepPer' => $GlassDepPer,
            'FibreDepPer' => $FibreDepPer
        ];

        return $this->sendResponse($dasend, "fetched successfully", 200);
    }
}