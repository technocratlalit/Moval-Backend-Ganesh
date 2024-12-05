<?php

namespace App\Http\Controllers\MotorSurvey;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\EstimateStructure;
use Illuminate\Support\Facades\Auth;

class EstimateController extends BaseController
{
    //
   public function estimateStructureList(){
       $getestimateStructrure = EstimateStructure::select('id','structure_name')->get();
       return $this->sendResponse($getestimateStructrure, 'Estimate Structure Retrieved successfully.');
   }

    
   public function estimateStore(Request $request){
        $validator = Validator::make($request->all(), [
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        $data = $request->all();
        
        if(isset($data['id'])) {
            // If ID is provided, update the existing record
            $estimateStructure = EstimateStructure::find($data['id']);
            if($estimateStructure) {
                $estimateStructure->update($data);
                return $this->sendResponse($estimateStructure,'Record updated successfully');
            } else {
                return $this->sendError('Record not found', [], 404);
            }
        } else {
            // If ID is not provided, create a new record
            $estimateStructure = EstimateStructure::create($data);
            return $this->sendResponse($estimateStructure, 'Estimate Structure created successfully.');
        }
   }

   public function estimateDeatils($id){

      $estimateDeatils = EstimateStructure::find($id);
      return $this->sendResponse($estimateDeatils, 'Estimate Structure Retrieved successfully.');
   }
}
