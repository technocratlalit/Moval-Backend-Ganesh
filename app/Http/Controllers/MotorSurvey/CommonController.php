<?php

namespace App\Http\Controllers\MotorSurvey;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssismentDetail;
use App\Models\Feebill_report;
use App\Models\BanksDetailsModel;

class CommonController extends Controller
{
    public function changeDetailsKey()
    {
        $results = AssismentDetail::all();
        foreach ($results->toArray() as $k => $item) {
            $decode = json_decode($item['alldetails'], true);
            $alldetails = [];
            foreach ($decode as $row) {
                $data = $row;
                if(isset($row['ap_labour_amt'])) {
                    unset($data['ap_labour_amt']);
                    $data['billed_lab_amt'] = $row['ap_labour_amt'];
                }
                $quantities = [];
                if(!empty($row['quantities'])) {
                    foreach ($row['quantities'] as $ind => $sub_detail) {
                        $quantities[$ind] = $sub_detail;
                        if(isset($sub_detail['ap_labour_amt'])) {
                            unset($quantities[$ind]['ap_labour_amt']);
                            $quantities[$ind]['billed_lab_amt'] = $sub_detail['ap_labour_amt'];
                        }
                    }
                }
                $data['quantities'] = $quantities;
                $alldetails[] = $data;
            }
            if(!empty($alldetails)) {
                AssismentDetail::where('id', $item['id'])->update(['alldetails' => json_encode($alldetails, true)]);
                unset($alldetails);
            }
        }
        exit("success");
    }

    public function updateBank()
    {
        $results = Feebill_report::with('get_inspection')->whereNotNull('bank_details')->where('bank_code', '!=', '')->get();
        if($results->count() > 0) {
            foreach ($results->toArray() as $k => $item) {
                if(!empty($item['bank_details'])) {
                    $admin_id = $item['get_inspection']['admin_id'] ?? null;
                    $admin_branch_id = $item['get_inspection']['admin_branch_id'] ?? null;
                    $bank_details = [];
                    foreach ($item['bank_details'] as $bank) {
                        if($bank['bank_code'] == $item['bank_code']) {
                            $bank_details = $bank;
                            break;
                        }
                    }
                    if(!empty($bank_details) && !empty($admin_id) && !empty($admin_branch_id)) {
                        $bank_details['admin_id'] = $admin_id;
                        $bank_details['created_by'] = $admin_id;
                        $bank_details['admin_branch_id'] = $admin_branch_id;
                        $bank_details['created_at'] = date('Y-m-d H:i:s');
                        $query = BanksDetailsModel::where(['admin_id' => $admin_id, 'admin_branch_id' => $admin_branch_id, 'account_number' => $bank_details['account_number']]);
                        if($query->count() < 1) {
                            $bank_create = BanksDetailsModel::create($bank_details);
                            Feebill_report::where('id', $item['id'])->update(['bank_id' => $bank_create->id]);
                        }
                    }
                }
            }
        }
        exit("success");
    }
}
