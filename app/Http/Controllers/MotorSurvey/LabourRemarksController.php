<?php

namespace App\Http\Controllers\MotorSurvey;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\BanksDetailsModel;
use Illuminate\Http\Request;
use App\Models\LabourRemarksModel;
use Illuminate\Validation\Rule;

class LabourRemarksController extends BaseController
{
    public function labourRemarksList($admin_id=null, $type=null, $message = 'Data fetched successfully') {
        if(!empty($admin_id) && is_numeric($admin_id)) {
            $response = [];
            $where = ['status' => 1, 'admin_id' => $admin_id];
            if(empty($type) || $type == 1) {
                $where['type'] = 1;
                $response['labour'] = [];
                $query = LabourRemarksModel::select('id', 'title')->where($where)->get();
                if ($query->count() > 0) {
                    $response['labour'] = $query->toArray();
                }
            }
            if(empty($type) || $type == 2) {
                $where['type'] = 2;
                $response['remarks'] = [];
                $query = LabourRemarksModel::select('id', 'title')->where($where)->get();
                if ($query->count() > 0) {
                    $response['remarks'] = $query->toArray();
                }
            }
            $message = empty($message) ? 'Data empty' : $message;
            return $this->sendResponse($response, $message, 200);
        } else {
            return $this->sendError("Admin id required, Please try again with admin id.");
        }
    }

    public function saveUpdateLabourRemarks(Request $request, $id=null) {
        $where = ['admin_id' => $request->admin_id, 'type' => $request->type, 'title' => $request->title];
        $request->validate([
            'type' => 'required|numeric|in:1,2',
            'admin_id' => 'required|numeric',
            'admin_branch_id' => 'required|numeric',
            'title' => ['required', 'string', Rule::unique('tbl_labour_remarks')->where(function ($query) use($where) {
                return $query->where($where);
            })->ignore($id)]
        ]);
        $data = $request->all();
        $data['status'] = 1;
        if(!empty($id)) {
            $res = LabourRemarksModel::where('id', $id)->update($data);
        } else{
            $res = LabourRemarksModel::insert($data);
        }
        if($res) {
            $message = empty($id) ? 'Added Successfully.' : 'Updated Successfully.';
            return $this->labourRemarksList($request->admin_id, $request->type, $message);
        } else {
            $res_type = empty($id) ? 'added' : 'updated';
            return $this->sendError("Data not $res_type, Please try again!");
        }
    }


    public function deleteLabourRemarks($admin_id=null, $id=null) {
        if(!empty($id) && is_numeric($id) && !empty($admin_id) && is_numeric($admin_id)) {
            $labourRemarks = LabourRemarksModel::where("admin_id",$admin_id)->find($id);
            if($labourRemarks) {
                $labourRemarks->status = 7;
                $labourRemarks->deleted_at = date('Y-m-d H:i:s');
                $type = $labourRemarks->type;
                if ($labourRemarks->save()) {
                    return $this->labourRemarksList($admin_id, $type, 'Data deleted successfully.');
                }
            }
            return $this->sendError('Something went wrong, Please try again.');
        } else {
            return $this->sendError("Admin id required, Please try again with admin id.");
        }
    }
}
