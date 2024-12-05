<?php

namespace App\Http\Controllers\MotorSurvey;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Http\Resources\feebill\FeebillResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\FeeSchedule;
use App\Models\Inspection;
use App\Models\InspectionPolicyDetail;
use App\Models\ClientBranchMs;
use App\Models\InspectionFeebillReport;
use App\Models\AssismentCalculation;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use PDF;

class FeebillmsController extends BaseController
{
/**
* Display a listing of the resource.
*
* @return \Illuminate\Http\Response
*/
public function index(Request $request)
{  
$TBL_ADMIN      = "tbl_ms_admin";
$created_by     = Auth::user()->id;
/*
if($request->user()->tokenCan('type:employee')) {
return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
}else if($request->user()->tokenCan('type:admin')) {
$loggedInAdmin = Admin_ms::where('id','=', $created_by)->where('status','!=', '2')->first();
if (is_null($loggedInAdmin)) {
return $this->sendError('Admin does not exist.');
}
if ($loggedInAdmin->parent_id != 0){
return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
}
}

$searchKeyword = "";
if ($request->exists('search_keyword')) {
$searchKeyword = $request->input('search_keyword');
if ($searchKeyword != '') {
$allAdmins =  $allAdmins->where(function($query) use ($TBL_ADMIN,$searchKeyword) {
$query->orWhere($TBL_ADMIN.'.name','LIKE','%'.$searchKeyword.'%')
->orWhere($TBL_ADMIN.'.email','LIKE','%'.$searchKeyword.'%')
->orWhere($TBL_ADMIN.'.address','LIKE','%'.$searchKeyword.'%')
->orWhere($TBL_ADMIN.'.id','LIKE','%'.$searchKeyword.'%');
});
}
} */
$wh=['created_by'=>$created_by];
$feebill=FeeSchedule::where($wh);

$allAdmins1['data'] = $request->exists('all')
? FeebillResource::collection($feebill->get())
: FeebillResource::collection($feebill->paginate(10));
if(!$request->exists('all')){
	  $alldata=$feebill->paginate(10);
	         $allAdmins1['pagination'] = 
			 ['total' => $alldata->total(),
                'count' => $alldata->count(),
                'per_page' => $alldata->perPage(),
                'current_page' => $alldata->currentPage(),
                'total_pages' => $alldata->lastPage(),
				];
}

return $this->sendResponse($allAdmins1, 'Posts fetched.');

}

/**
* Store a newly created resource in storage.
*
* @param  \Illuminate\Http\Request  $request
* @return \Illuminate\Http\Response
*/
public function store(Request $request)
{
    //$id=5;
 $TBL_ADMIN   = "tbl_ms_admin";
//$created_by = Auth::user()->id;

//$duraton_delete_photo = "0";
/*
if($request->user()->tokenCan('type:employee')) {
return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
}else if($request->user()->tokenCan('type:admin')) {
$loggedInAdmin = Admin_ms::where('id','=', $created_by)->where('status','!=', '2')->first();
if (is_null($loggedInAdmin)) {
return $this->sendError('Admin does not exist.');
}
if ($loggedInAdmin->parent_id != 0){
return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
}

}
*/
$arrFieldsToBeValidate = [
        'cgst' => 'required|numeric',
        'sgst' => 'required|numeric',
        'igst' => 'required|numeric',
        'level1' => 'required|numeric',
        'level2' => 'required|numeric',
        'level3' => 'required|numeric',
        'level4' => 'required|numeric',
        'level5' => 'required|numeric',
        'level5_percent' => 'required|numeric',
        'spot_survey_fee' => 'required|numeric',
        'reinspection_fee' => 'required|numeric',
        'verification_fee' => 'required|numeric',
        'conveyance_a' => 'required|numeric',
        'conveyance_b' => 'required|numeric',
        'conveyance_c' => 'required|numeric',
        'city_category' => 'required|string',
        'created_by' => 'required|numeric',
        'client_id'=> 'required|numeric',
];

$fields = $request->validate($arrFieldsToBeValidate);



$created_by = Auth::user()->id;

$wh=['client_id'=>$fields['client_id'],'created_by'=>$created_by];
$feebill = FeeSchedule::where($wh)->first();



if(isset($feebill)){
    
    
    $arrFieldsToBeAdd = [
'cgst' => $fields['cgst'],
'sgst' => $fields['sgst'],
'igst' => $fields['igst'],
'level1' => $fields['level1'],
'level2' => $fields['level2'],
'level3' => $fields['level3'],
'level4' => $fields['level4'],
'level5' => $fields['level5'],
'level5_percent' => $fields['level5_percent'],
'spot_survey_fee' => $fields['spot_survey_fee'],
'reinspection_fee' => $fields['reinspection_fee'],
'verification_fee' => $fields['verification_fee'],
'conveyance_a' => $fields['conveyance_a'],
'conveyance_b' => $fields['conveyance_b'],
'conveyance_c' => $fields['conveyance_c'],
'city_category' => $fields['city_category'],
'modified_by' => $fields['created_by'],
'client_id' => $fields['client_id'],
];

  $feebill = FeeSchedule::where('client_id', $fields['client_id'])->update($arrFieldsToBeAdd);
  return $this->sendResponse($feebill,"Fee schedule updated successfully",201);
  
}else{
    
  $arrFieldsToBeAdd = [
'cgst' => $fields['cgst'],
'sgst' => $fields['sgst'],
'igst' => $fields['igst'],
'level1' => $fields['level1'],
'level2' => $fields['level2'],
'level3' => $fields['level3'],
'level4' => $fields['level4'],
'level5' => $fields['level5'],
'level5_percent' => $fields['level5_percent'],
'spot_survey_fee' => $fields['spot_survey_fee'],
'reinspection_fee' => $fields['reinspection_fee'],
'verification_fee' => $fields['verification_fee'],
'conveyance_a' => $fields['conveyance_a'],
'conveyance_b' => $fields['conveyance_b'],
'conveyance_c' => $fields['conveyance_c'],
'city_category' => $fields['city_category'],
'created_by' => $fields['created_by'],
'client_id' => $fields['client_id'],
];

 $feebill=FeeSchedule::create($arrFieldsToBeAdd);
 return $this->sendResponse(new FeebillResource($feebill),"Fee schedule saved successfully",201);
}
//return $this->sendResponse("insert Sussessfully","success",201);


}

/**
* Display the specified resource.
*
* @param  int  $id
* @return \Illuminate\Http\Response
*/
public function show(Request $request,$id)
{
$created_by = Auth::user()->id;
$wh=['client_id'=>$id,'created_by'=>$created_by];
$feebill = FeeSchedule::where($wh)->first();

if (is_null($feebill)) {
return $this->sendError('fee Schedule does not exist.');
}
return $this->sendResponse(new FeebillResource($feebill), 'Bill fetched.');
} 

/**
* Update the specified resource in storage.
*
* @param  \Illuminate\Http\Request  $request
* @param  int  $id
* @return \Illuminate\Http\Response
*/
public function update(Request $request, $id)
{
$TBL_ADMIN   = "tbl_ms_admin";

 $update_by = Auth::user()->id; 
/*
if($request->user()->tokenCan('type:employee')) {
return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
}else if($request->user()->tokenCan('type:admin')) {
$loggedInAdmin = Admin_ms::where('id','=', $update_by)->where('status','!=', '2')->first();
if (is_null($loggedInAdmin)) {
return $this->sendError('Admin does not exist.');
}
if ($loggedInAdmin->parent_id != 0){
return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
}
}*/

$feebill = FeeSchedule::where('client_id',$id)->first();
if (is_null($feebill)) {
return $this->sendError('Bill does not exist.');
}

$arrFieldsToBeValidate = [
      'cgst' => 'required|numeric',
        'sgst' => 'required|numeric',
        'igst' => 'required|numeric',
        'level1' => 'required|numeric',
        'level2' => 'required|numeric',
        'level3' => 'required|numeric',
        'level4' => 'required|numeric',
        'level5' => 'required|numeric',
        'level5_percent' => 'required|numeric',
        'spot_survey_fee' => 'required|numeric',
        'reinspection_fee' => 'required|numeric',
        'verification_fee' => 'required|numeric',
        'conveyance_a' => 'required|numeric',
        'conveyance_b' => 'required|numeric',
        'conveyance_c' => 'required|numeric',
        'city_category' => 'required|string',
        'created_by' => 'required|numeric',
        'client_id' => 'required|numeric',
];

$fields = $request->validate($arrFieldsToBeValidate);

$feebill->cgst = $fields['cgst'];
$feebill->sgst = $fields['sgst'];
$feebill->igst = $fields['igst'];
$feebill->level1 = $fields['level1'];
$feebill->level2 = $fields['level2'];
$feebill->level3 = $fields['level3'];
$feebill->level4 = $fields['level4'];
$feebill->level5 = $fields['level5'];
$feebill->level5_percent = $fields['level5_percent'];
$feebill->spot_survey_fee = $fields['spot_survey_fee'];
$feebill->reinspection_fee = $fields['reinspection_fee'];
$feebill->verification_fee = $fields['verification_fee'];
$feebill->conveyance_a = $fields['conveyance_a'];
$feebill->conveyance_b = $fields['conveyance_b'];
$feebill->conveyance_c = $fields['conveyance_c'];
$feebill->city_category = $fields['city_category'];
$feebill->updated_by = $fields['created_by'];
$feebill->client_id = $fields['client_id'];
$feebill->update();

return $this->sendResponse(new FeebillResource($feebill), 'bill updated.',200);

}

/** 
* Remove the specified resource from storage.
*
* @param  int  $id
* @return \Illuminate\Http\Response
*/
public function destroy(Request $request, $id)
{
$update_by = Auth::user()->id;
/*
if($request->user()->tokenCan('type:employee')) {
return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
}else if($request->user()->tokenCan('type:admin')) {
$parent_admin_id    = getAdminIdIfAdminLoggedIn1($update_by);
if ($parent_admin_id != $update_by) {
return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
}
}
*/

$feebill = FeeSchedule::where('id',$id)->first();

if (is_null($feebill)) {
return $this->sendError('Bill  does not exist.');
}



$feebill->delete();


return $this->sendResponse([], 'Bill delete successfully.',200);
}


public function feeBillCalculation($inspectionId){

  $estAmount = AssismentCalculation::where('inspection_id', $inspectionId)->first()->totalest;
  $feeAmount = 0;
  $cgst =0;
  $sgst =0;
  $igst =0;
  $conveyanceAmount = 0;
  $percentageAmount=0;
  
  if (!empty($estAmount)) {
      if (Inspection::where('id', $inspectionId)->exists()) {

          $inspectionDeatils = Inspection::where('id', $inspectionId)->first();
          $inspectionPolicyDetail =InspectionPolicyDetail::where('inspection_id', $inspectionId)->first();
          //dd($inspectionDeatils->appointing_office_code);
          if(is_null($inspectionDeatils->appointing_office_code)){
             return $this->sendError('Appointing ID not found !!.');
          }

          if(is_null($inspectionPolicyDetail->client_id)){
            return $this->sendError('Client ID not found !!.');
          }

        if($inspectionPolicyDetail->client_id){
              if ($estAmount >= 0 && $estAmount <= 20000) {

                  $feeData = FeeSchedule::select('id', 'level1 as fee_amount','cgst','sgst','igst','conveyance_a','conveyance_b','conveyance_c','city_category')->where('client_id', $inspectionPolicyDetail->client_id)->first();
                  if ($feeData) {
                      $feeAmount = $feeData->fee_amount;
                  }
              } elseif ($estAmount > 20000 && $estAmount <= 50000) {
                      $feeData = FeeSchedule::select('id', 'level2 as fee_amount','cgst','sgst','igst','conveyance_a','conveyance_b','conveyance_c','city_category')->where('client_id', $inspectionPolicyDetail->client_id)->first();
                      if($feeData) {
                        $feeAmount = $feeData->fee_amount;
                      }
              } elseif ($estAmount > 50000 && $estAmount <= 100000) {
                        $feeData = FeeSchedule::select('id', 'level3 as fee_amount','cgst','sgst','igst','conveyance_a','conveyance_b','conveyance_c','city_category')->where('client_id', $inspectionPolicyDetail->client_id)->first();
                        if ($feeData) {
                            $feeAmount = $feeData->fee_amount;
                        }
              } elseif ($estAmount > 100000 && $estAmount <= 200000) {
                        $feeData = FeeSchedule::select('id', 'level4 as fee_amount','cgst','sgst','igst','conveyance_a','conveyance_b','conveyance_c','city_category')->where('client_id', $inspectionPolicyDetail->client_id)->first();
                        if ($feeData) {
                            $feeAmount = $feeData->fee_amount;
                        }
              } else {
                      $feeData = FeeSchedule::select('id', 'level5 as fee_amount','cgst','sgst','igst','conveyance_a','conveyance_b','conveyance_c','city_category')->where('client_id', $inspectionPolicyDetail->client_id)->first();

                      $lavel5MaxAmount = 200000;
                      $totaldeductionAmount =  $estAmount - $lavel5MaxAmount;
                      $percentage = 0.70;
                      $percentageAmount = ($totaldeductionAmount * $percentage) / 100;
                      if ($feeData) {
                          $feeAmount = $feeData->fee_amount + $percentageAmount;
                      }

              }
          }else{
               return $this->sendError('Client ID not found !!.');
          }
      } else {
          return $this->sendError('Inspection not found !!.');
      }
  }

  $feeData = FeeSchedule::select('id','cgst','sgst','igst','conveyance_a','conveyance_b','conveyance_c','city_category')->where('client_id', $inspectionPolicyDetail->client_id)->first();

      if ($feeData) {
        if ($feeData->city_category == "A") {
            $conveyanceAmount = $feeData->conveyance_a;
        } elseif ($feeData->city_category == "B") {
            $conveyanceAmount = $feeData->conveyance_b;
        } elseif ($feeData->city_category == "C") {
            $conveyanceAmount = $feeData->conveyance_c;
        }
    }

  $issueTo = InspectionFeebillReport::where('inspection_id',$inspectionId)->first();

  if (!empty($issueTo->issued_to == 2) || !empty($issueTo->issued_to == 5)) {
       
    $clientbranchId = ClientBranchMs::select('within_state')->where('id',$inspectionPolicyDetail->client_id)->first();

    $cgst = ($clientbranchId->within_state == '0') ? $feeData->cgst : 0;
    $sgst = ($clientbranchId->within_state == '0') ? $feeData->sgst : 0;
    $igst = ($clientbranchId->within_state == '0') ? 0 : $feeData->igst;
  }elseif(!empty($issueTo->issued_to == 1)){
    $clientbranchId = ClientBranchMs::select('within_state')->where('id',$inspectionPolicyDetail->appointing_office_code)->first();

    $cgst = ($clientbranchId->within_state == '0') ? $feeData->cgst : 0;
    $sgst = ($clientbranchId->within_state == '0') ? $feeData->sgst : 0;
    $igst = ($clientbranchId->within_state == '0') ? 0 : $feeData->igst;

  }elseif(!empty($issueTo->issued_to == 3)){

    $clientbranchId = ClientBranchMs::select('within_state')->where('id',$inspectionPolicyDetail->client_branch_id)->first();

    $cgst = ($clientbranchId->within_state == '0') ? $feeData->cgst : 0;
    $sgst = ($clientbranchId->within_state == '0') ? $feeData->sgst : 0;
    $igst = ($clientbranchId->within_state == '0') ? 0 : $feeData->igst;

  }elseif (!empty($issueTo->issued_to == 4)) {

    $clientbranchId = ClientBranchMs::select('within_state')->where('id',$inspectionPolicyDetail->operating_office_code)->first();

    $cgst = ($clientbranchId->within_state == '0') ? $feeData->cgst : 0;
    $sgst = ($clientbranchId->within_state == '0') ? $feeData->sgst : 0;
    $igst = ($clientbranchId->within_state == '0') ? 0 : $feeData->igst;

  }else{
    $cgst = 0;
    $sgst = 0;
    $igst = 0;
  }

  $data = [
      "fee_amount" => $feeAmount,
      "survey_type" => "Final Survey Fee (On Estimated Amt. {$estAmount})",
      "cgst" => $cgst,
      "sgst" => $sgst,
      "igst"=> $igst,
      "conveyance_amount" => $conveyanceAmount,
      
  ];
  
  return $this->sendResponse($data, 'Fee Schedule & GST settings saved successfully.', 200);
  
}

}