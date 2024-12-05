<?php

namespace App\Http\Controllers\MotorValuation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController;
use Illuminate\Validation\Rule;

use App\Models\Bank_Details;
use App\Models\BanksDetailsModel;

class BankController extends BaseController
{
    public function getBankList(Request $request, $admin_id=null, $admin_branch_id=null)
    {
        $data = [];
        if (!empty($admin_id)) {
            $where['admin_id'] = $admin_id;
            if(!empty($admin_branch_id)) {
                $where['admin_branch_id'] = $admin_branch_id;
            }
            $query = BanksDetailsModel::select('id', 'bank_code', 'bank_name')->whereNull('deleted_at')->where($where)->get();
            if($query->count() > 0) {
                $data = $query->toArray();
            }
        }
        return $this->sendResponse($data, "success", 200);
    }

    public function saveUpdateBankDetails(Request $request)
    {
        $request->validate([
            'admin_id' => 'required|numeric',
            'admin_branch_id' => 'required|numeric',
            'bank_details' => 'required|string',
        ]);
        $bank_details = stripslashes($request->input('bank_details'));
        $array_bank_details = json_decode($bank_details, true);
        if (!empty($array_bank_details)) {
            $newBankData = [];
            foreach ($array_bank_details as $ind => $bank) {
                $id = (isset($bank['id']) && !empty($bank['id'])) ? $bank['id'] : '';
                unset($bank['id']);
                if(!empty($id)) {
                    $bank['updated_at'] = date('Y-m-d H:i:s');
                    BanksDetailsModel::where('id', $id)->update($bank);
                } else {
                    $bank['admin_id'] = Auth::user()->id ?? 0;
                    $bank['admin_branch_id']= $request->admin_branch_id;
                    $bank['created_by']= Auth::user()->id ?? 0;
                    $newBankData[] = $bank;
                }
            }
            if(!empty($newBankData)) {
                BanksDetailsModel::insert($newBankData);
            }
        }
        $data = BanksDetailsModel::select('id', 'bank_code', 'bank_name', 'branch_address', 'account_number', 'account_type', 'ifsc', 'micr')->whereNull('deleted_at')->where(['admin_id' => Auth::user()->id, 'admin_branch_id' => $request->admin_branch_id])->get();
        if ($data->count() > 0) {
            return $this->sendResponse($data->toArray(), "success", 200);
        } else {
            return $this->sendError('Bank details unavailable.');
        }
    }

    public function deleteBank(Request $request, $id = null)
    {
        if (!empty($id)) {
            $data = [];
            $bank = BanksDetailsModel::find($id);
            $bank->deleted_at = date('Y-m-d H:i:s');
            if($bank->save()) {
                $query = BanksDetailsModel::select('id', 'bank_code', 'bank_name', 'branch_address', 'account_number', 'account_type', 'ifsc', 'micr')->whereNull('deleted_at')->where(['admin_id' => $bank->admin_id, 'admin_branch_id' => $bank->admin_branch_id])->get();
                if($query->count() > 0) {
                    $data = $query->toArray();
                }
            }
            return $this->sendResponse($data, "Successfully deleted", 200);
        } else {
            return $this->sendError('Bank details unavailable.');
        }
    }
}
