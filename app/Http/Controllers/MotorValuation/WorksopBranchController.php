<?php

namespace App\Http\Controllers\MotorValuation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Workshopbranchms;
use App\Models\Workshop;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController;
use Illuminate\Validation\Rule;


class WorksopBranchController extends BaseController
{
    public function index(Request $request)
    {

    }

    public function store(Request $request)
    {
        $request->validate([
            'workshop_branch_name' => 'required|string',
//            'contact_details' => 'numeric',
            'address' => 'required|string',
//            'gst_no' => 'string',
//            'manager_name' => 'string',
//            'manager_mobile_num' => 'numeric|digits:10',
//            'manager_email' => 'email',
//            'workshop_id' => 'required|numeric',
//            'created_by' => 'numeric',
        ]);

        $workshop_id = (isset($request->workshop_id) && !empty($request->workshop_id) && is_numeric($request->workshop_id)) ? $request->workshop_id : '';
        $workshop_type = ['1' => 'Local WorkShop', '2' => 'Authorized Workshop'];
        $workshop_name = (isset($request->workshop_type) && isset($workshop_type[$request->workshop_type])) ? $workshop_type[$request->workshop_type] : '';
        $is_local_workshop = !empty($request->workshop_type) ? $request->workshop_type : 0;

        if(empty($workshop_id) && !empty($workshop_name) && !empty($request->admin_branch_id)) {
            $workshopExists = Workshop::whereRaw('LOWER(workshop_name) = ?', [strtolower($workshop_name)])->whereNull('deleted_at')->where(['admin_id' => $request->admin_id, 'admin_branch_id' => $request->admin_branch_id])->first();
            if ($workshopExists) {
                $workshop_id = $workshopExists->id;
            } else {
                $workshop = Workshop::create([
                    'workshop_name' => $workshop_name,
                    'admin_id' => $request->admin_id,
                    'admin_branch_id' => $request->admin_branch_id,
                    'is_local_workshop' => $is_local_workshop,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                $workshop_id = $workshop->id;
            }
        }

        if(!empty($workshop_id)) {
            $branch = Workshopbranchms::create([
                'workshop_branch_name' => $request->workshop_branch_name,
                'contact_details' => $request->contact_details,
                'address' => $request->address ?? NULL,
                'gst_no' => $request->gst_no ?? NULL,
                'manager_mobile_num' => $request->manager_mobile_num ?? NULL,
                'manager_name' => $request->manager_name ?? NULL,
                'manager_email' => $request->manager_email ?? NULL,
                'workshop_id' => $workshop_id,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            if($branch) {
                return $this->sendResponse("Created successfully ", "success", 200);
            }
        }
        return $this->sendError('Something went wrong, Please try again!.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'workshop_branch_name' => 'required|string',
//            'contact_details' => 'numeric',
            'address' => 'required|string',
//            'gst_no' => 'string',
//            'manager_name' => 'string',
//            'manager_mobile_num' => 'numeric|digits:10',
//            'manager_email' => 'email',
//            'workshop_id' => 'required|numeric|exists:tbl_ms_workshop,id',
//            'created_by' => 'numeric',
        ]);
        if(!empty($id) && is_numeric($id)) {
            $workshop_id = (isset($request->workshop_id) && !empty($request->workshop_id) && is_numeric($request->workshop_id)) ? $request->workshop_id : '';
            $workshop_type = ['1' => 'Local WorkShop', '2' => 'Authorized Workshop'];
            $workshop_name = (isset($request->workshop_type) && isset($workshop_type[$request->workshop_type])) ? $workshop_type[$request->workshop_type] : '';
            $is_local_workshop = !empty($request->workshop_type) ? $request->workshop_type : 0;
            if(empty($workshop_id) && !empty($workshop_name) && !empty($request->admin_branch_id)) {
                $workshopExists = Workshop::whereRaw('LOWER(workshop_name) = ?', [strtolower($workshop_name)])->whereNull('deleted_at')->where(['admin_id' => $request->admin_id, 'admin_branch_id' => $request->admin_branch_id])->first();
                if ($workshopExists) {
                    $workshop_id = $workshopExists->id;
                } else {
                    $workshop = Workshop::create([
                        'workshop_name' => $workshop_name,
                        'admin_id' => $request->admin_id,
                        'admin_branch_id' => $request->admin_branch_id,
                        'is_local_workshop' => $is_local_workshop,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    $workshop_id = $workshop->id;
                }
            }

            $workShopBranch = Workshopbranchms::find($id);
            if ($workShopBranch) {
                $workShopBranch->workshop_branch_name = $request->workshop_branch_name;
                $workShopBranch->contact_details = $request->contact_details;
                $workShopBranch->address = $request->address ?? NULL;
                $workShopBranch->gst_no = $request->gst_no ?? NULL;
                $workShopBranch->manager_name = $request->manager_name ?? NULL;
                $workShopBranch->manager_mobile_num = $request->manager_mobile_num ?? NULL;
                $workShopBranch->manager_email = $request->manager_email ?? NULL;
                $workShopBranch->modified_by = Auth::user()->id;
                $workShopBranch->workshop_id = $workshop_id;
                $workShopBranch->updated_at = date('Y-m-d H:i:s');
                if($workShopBranch->save()){
                    return $this->sendResponse("Updated Successfully.", "success", 200);
                }
            }
        }
        return $this->sendError('Workshop branch does not exist.');
    }


    public function list(Request $request)
    {
        $page = ($request->exists('all') || (isset($request->page) && $request->page == 'all')) ? true : false;
        if (!empty($page)) {
            $employee = Workshopbranchms::whereNull('deleted_at')->get();
        } else {
            $employee = Workshopbranchms::whereNull('deleted_at')->paginate(10);
            $data['total'] = $employee->total();
            $data['last_page'] = $employee->lastPage();
            $data['items_per_page'] = $employee->perPage();
        }
        foreach ($employee as $i => $emp_details) {
            $data['values'][$i] = [
                'id' => $emp_details->id,
                'workshop_branch_name' => $emp_details->workshop_branch_name,
                'manager_name' => $emp_details->manager_name,
                'contact_details' => $emp_details->contact_details,
                'gst_no' => $emp_details->gst_no,
                'manager_mobile_num' => $emp_details->manager_mobile_num,
                'manager_email' => $emp_details->manager_email,
                'address' => $emp_details->address,
                'workshop_id' => $emp_details->workshop_id,
                'created_at' => $emp_details->created_at,
                'updated_at' => $emp_details->updated_at,
                'created_by' => $emp_details->created_by,
                'modified_by' => $emp_details->modified_by,
            ];
        }
        return $this->sendResponse($data, 'workshop branch List Fetched', 200);
    }


    public function deletewsb(Request $request, $id)
    {

        $employee = Workshopbranchms::where('id', '=', $id)->first();
        if (is_null($employee)) {
            return $this->sendError('Workshop branch does not exist.');
        }
        $d = Workshopbranchms::where('id', $id)->delete();
        if ($d == 1) {
            return $this->sendResponse('Workshop branch deleted', "success", 200);
        } else {
            return $this->sendError('unable to delete', ['error' => 'invalid id'], 200);
        }
    }


    public function showbyid(Request $request, $id)
    {

        $emp_details = Workshopbranchms::where('id', '=', $id)->first();

        if (is_null($emp_details)) {
            return $this->sendError('Workshop branch does not exist.');
        }

        $data = [
            'id' => $emp_details->id,
            'workshop_branch_name' => $emp_details->workshop_branch_name,
            'manager_name' => $emp_details->manager_name,
            'contact_details' => $emp_details->contact_details,
            'gst_no' => $emp_details->gst_no,
            'manager_mobile_num' => $emp_details->manager_mobile_num,
            'manager_email' => $emp_details->manager_email,
            'address' => $emp_details->address,
            'workshop_id' => $emp_details->workshop_id,
            'created_at' => $emp_details->created_at,
            'updated_at' => $emp_details->updated_at,
            'created_by' => $emp_details->created_by,
            'modified_by' => $emp_details->modified_by,
        ];

        return $this->sendResponse($data, 'workshop branch Fetched', 200);
    }


    public function showbyid_wid(Request $request, $id)
    {
        $page = ($request->exists('all') || (isset($request->page) && $request->page == 'all')) ? true : false;
        $w_branchdetails = Workshopbranchms::with('get_workshop')->where('workshop_id', $id);
        if (!empty($page)) {
            $w_branchdetails = $w_branchdetails->get();
        } else {
            $w_branchdetails = $w_branchdetails->paginate(10);
        }

        if ($w_branchdetails->count() == 0) {
            return $this->sendError('Workshop branch does not exist.');
        }
        $data = [];
        foreach ($w_branchdetails as $i => $emp_details) {
            $data['values'][$i] = [
                'id' => $emp_details->id,
                'workshop_branch_name' => $emp_details->workshop_branch_name,
                'manager_name' => $emp_details->manager_name,
                'contact_details' => $emp_details->contact_details,
                'gst_no' => $emp_details->gst_no,
                'manager_mobile_num' => $emp_details->manager_mobile_num,
                'manager_email' => $emp_details->manager_email,
                'address' => $emp_details->address,
                'workshop_id' => $emp_details->workshop_id,
                'created_at' => $emp_details->created_at,
                'updated_at' => $emp_details->updated_at,
                'created_by' => $emp_details->created_by,
                'modified_by' => $emp_details->modified_by,
                'admin_id' => $emp_details->get_workshop->admin_id,
                'admin_branch_id' => $emp_details->get_workshop->admin_branch_id,
            ];
        }

        if (empty($page)) {
            $data['pagination'] = [
                'total' => $w_branchdetails->total(),
                'count' => $w_branchdetails->count(),
                'per_page' => $w_branchdetails->perPage(),
                'current_page' => $w_branchdetails->currentPage(),
                'total_pages' => $w_branchdetails->lastPage(),
            ];
        }
        return $this->sendResponse($data, 'workshop branch List Fetched', 200);
    }

    public function localWorkshop(Request $request)
    {
        $fields = $request->validate([
            'admin_branch_id' => 'required|numeric',
            'workshop_branch_name' => 'required|string',
            'address' => 'required|string',
        ]);

        $workshop_id = (isset($request->workshop_id) && !empty($request->workshop_id)) ? $request->workshop_id : '';
        $workshop_type = ['1' => 'Local WorkShop', '2' => 'Authorized Workshop'];
        $workshop_name = (isset($request->workshop_type) && isset($workshop_type[$request->workshop_type])) ? $workshop_type[$request->workshop_type] : '';
        $is_local_workshop = !empty($request->workshop_type) ? $request->workshop_type : 0;
        $admin_id = Auth::user()->id;
        $main_admin_id = getmainAdminIdIfAdminLoggedIn($admin_id);
        $workshopExists = !empty($workshop_id) ? Workshop::find($workshop_id) : false;
        if ($workshopExists && in_array($request->workshop_type, [1, 2]) && $workshopExists->is_local_workshop != $request->workshop_type && $workshopExists->is_local_workshop > 0) {
            $workshop_id = null;
        }

        if (empty($workshop_id)) {
            $workshopExists = Workshop::whereRaw('LOWER(workshop_name) = ?', [strtolower($workshop_name)])
                ->where(['admin_branch_id' => $request->admin_branch_id, 'is_local_workshop' => $is_local_workshop])
                ->first();
            if ($workshopExists) {
                $workshop_id = $workshopExists->id;
            } else {
                $workshop = new Workshop();
                $workshop->workshop_name = $workshop_name;
                $workshop->admin_id = Auth::user()->id;
                $workshop->admin_branch_id = $request->admin_branch_id;
                $workshop->is_local_workshop = $is_local_workshop;
                $workshop->created_at = $admin_id;
                if ($workshop->save()) {
                    $workshop_id = $workshop->id;
                }
            }
        }

        if (!empty($workshop_id)) {
            $emp_details = isset($request->workshop_branch_id) ? Workshopbranchms::where('id', '=', $request->workshop_branch_id)->first() : false;
            if (!empty($request->workshop_branch_id) && $emp_details) {
                $emp_details->workshop_id = $workshop_id;
                $emp_details->workshop_branch_name = $request->workshop_branch_name;
                $emp_details->address = $request->address;
                $emp_details->modified_by = $admin_id;
                $emp_details->updated_at = date('Y-m-d H:i:s');
                $emp_details->update();
                return $this->sendResponse("successfully updated", "success", 200);
            } else {
                $workshopBranch = new Workshopbranchms;
                $workshopBranch->workshop_branch_name = $request->workshop_branch_name;
                $workshopBranch->address = $request->address;
                $workshopBranch->workshop_id = $workshop_id;
                $workshopBranch->created_at = date('Y-m-d H:i:s');
                $workshopBranch->created_by = $admin_id;
                $workshopBranch->save();
                return $this->sendResponse("successfully created", "success", 200);
            }
        }
    }

    public function editworkshop($workshopBranchId)
    {

        $editWorkshop = Workshopbranchms::select('tbl_ms_workshop_branch.id as workshop_branch_id', 'tbl_ms_workshop_branch.workshop_branch_name', 'tbl_ms_workshop_branch.address', 'tbl_ms_workshop.is_local_workshop as workshop_type')->leftJoin('tbl_ms_workshop', 'tbl_ms_workshop.id', '=', 'tbl_ms_workshop_branch.workshop_id')->where('tbl_ms_workshop_branch.id', $workshopBranchId)->first();

        if (is_null($editWorkshop)) {
            return $this->sendError('Workshop branch not found.');
        }

        return $this->sendResponse($editWorkshop, "Workshop branch data retrieved", 200);

    }
}
