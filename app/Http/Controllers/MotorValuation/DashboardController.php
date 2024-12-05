<?php

namespace App\Http\Controllers\MotorValuation;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Jobms;
use App\Models\Employee;
use App\Models\Client;
use App\Models\Employeems;
use App\Models\Admin_ms;
use App\Models\Clientms;
use App\Models\Admin;
use App\Models\Branch;
use App\Models\SopMaster;
use Illuminate\Support\Facades\Auth;
use App\Models\Workshop;
use App\Http\Controllers\BaseController;
use App\Models\Inspection;

class DashboardController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }

        $dashboardData = array();
        $created_by_id      = Auth::user()->id;
        $parent_admin_id    = getAdminIdIfAdminLoggedIn($created_by_id);
        if ($parent_admin_id == 0) {
            return $this->sendError('Unauthorised.', ['error' => 'please login with correct platform'], 401);
        }
        if ($parent_admin_id != $created_by_id) { // Means Sub Admin Logged In
            // return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }
        $arrJobStatus = array('created' => 0, 'pending' => 0, 'submitted' => 0, 'approved' => 0, 'rejected' => 0, 'finalized' => 0);
        foreach ($arrJobStatus as $jobStatus => $value) {
            if ($parent_admin_id != $created_by_id) {
                $jobCount = Job::where('job_status', '=', $jobStatus)->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->where('created_by_id', '=', $created_by_id)->count();
            } else {
                $jobCount = Job::where('job_status', '=', $jobStatus)->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->count();
            }

            $arrJobStatus[$jobStatus] =  $jobCount;
        }

        $dashboardData["jobs_count"] = $arrJobStatus;
        $dashboardData["employee_count"] = Employee::where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->count();
        $dashboardData["client_count"] = Client::where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->count();
        $dashboardData["subadmin_count"] = Admin::where('status', '!=', '2')->where('parent_id', '=', $parent_admin_id)->count();

        return $this->sendResponse($dashboardData, 'Posts fetched.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // public function indexms(Request $request)
    // {
    //         $responseArr = [];
    //         $admins = Admin_ms::with('parent')->find(Auth::user()->id); 
    //         $arrJobStatus = array('created' => 0, 'pending' => 0, 'submitted' => 0, 'approved' => 0, 'rejected' => 0, 'finalized' => 0);
    //         if (isset(Auth::user()->parent)) {
    //             $wh = ['admin_id' => Auth::user()->parent->id];
    //             $responseArr["client_count"] = $admins->parent->get_all_clients->count(); 
    //             $responseArr["branch_count"] = $admins->parent->get_all_branches->count();
    //         }else{
    //             $wh = ['admin_id' => Auth::user()->id];
    //             $responseArr["client_count"] = $admins->get_all_clients->count(); 
    //             $responseArr["branch_count"] = $admins->get_all_branches->count();
    //         }
    //         foreach ($arrJobStatus as $jobStatus => $value) {

    //             $jobCount = Inspection::where($wh)->where('job_status', $jobStatus)->count();
    //             $arrJobStatus[$jobStatus] =  $jobCount;
    //         }
 
    //         $responseArr["jobs_count"] = $arrJobStatus;
    //         $responseArr["employee_count"] = $admins->get_employees->count(); 
    //         $responseArr["subadmin_count"] = $admins->children->count();
    //         $responseArr["sop_count"] = $admins->get_sop->count();
    //         $responseArr["workshop_count"] = $admins->get_workshop->count();
    //   return $this->sendResponse($responseArr, "success", 200);
     

    // }


    public function indexms(Request $request)
    {
        if (!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }

        $dashboardData = array();
        $created_by_id      = Auth::user()->id;
        $parent_admin_id    = getAdminIdIfAdminLoggedIn1($created_by_id);
        if ($parent_admin_id == 0) {
            return $this->sendError('Unauthorised.', ['error' => 'please login with correct platform'], 401);
        }
        if ($parent_admin_id != $created_by_id) { // Means Sub Admin Logged In
            // return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }


        if (Auth::user()->role == 'admin') {

            $arrJobStatus = array('created' => 0, 'pending' => 0, 'submitted' => 0, 'approved' => 0, 'rejected' => 0, 'finalized' => 0);
            foreach ($arrJobStatus as $jobStatus => $value) {
                if ($parent_admin_id != $created_by_id) {
                    $jobCount = Inspection::where('admin_id', '=', $created_by_id)->where('job_status', $jobStatus)->count();
                } else {
                    $jobCount = Inspection::where('admin_id', '=', $parent_admin_id)->where('job_status', $jobStatus)->count();
                }

                $arrJobStatus[$jobStatus] =  $jobCount;
            }

            $dashboardData["jobs_count"] = $arrJobStatus;

            $dashboardData["employee_count"] = Employeems::where('status', '!=', '2')->where('admin_id', '=', $created_by_id)->count();

            $dashboardData["client_count"] = Clientms::where('status', '!=', '2')->where('admin_id', '=', $created_by_id)->count();

            $dashboardData["subadmin_count"] = Admin_ms::where('status', '!=', '2')->where('id', '=', Auth::user()->id)->count();
            $dashboardData["subadmin_count"] = Admin_ms::find(Auth::user()->id)->children->count();

            //tbl_ms_sop_branch

            $dashboardData["branch_count"] = Branch::where('created_by', '=', $created_by_id)->count();


            $dashboardData["sop_count"] = SopMaster::where('admin_id', '=', $created_by_id)->count();


            $dashboardData["workshop_count"] = Workshop::where('admin_id', '=', $created_by_id)->count();
            $dashboardData[Auth::user()->role] = Auth::user()->id;
        } else {

            $main_admin_id = getmainAdminIdIfAdminLoggedIn($created_by_id);
            $admin_branch_id = getBranchidbyAdminid($created_by_id);

            // $wh2 = ['admin_id' => $main_admin_id, 'admin_branch_id' => $admin_branch_id];
            // $wh = ['admin_id' => $main_admin_id, 'admin_branch_id' => $admin_branch_id];

            $wh2 = ['admin_branch_id' => Auth::user()->admin_branch_id];
            $wh = ['admin_branch_id' => $admin_branch_id];


            $arrJobStatus = array('created' => 0, 'pending' => 0, 'submitted' => 0, 'approved' => 0, 'rejected' => 0, 'finalized' => 0);
            foreach ($arrJobStatus as $jobStatus => $value) {

                $jobCount = Inspection::where($wh)->where('job_status', $jobStatus)->count();


                $arrJobStatus[$jobStatus] =  $jobCount;
            }
            $dashboardData[Auth::user()->role] = Auth::user()->id;

            $dashboardData["jobs_count"] = $arrJobStatus;

            $dashboardData["employee_count"] = Employeems::where($wh2)->count();

            $dashboardData["client_count"] = Clientms::where('status', '!=', '2')->where($wh2)->count();

            $dashboardData["subadmin_count"] = Admin_ms::where('status','!=', '2')->where('super_admin_id','=', $created_by_id)->count();

            // $dashboardData["subadmin_count"] = Admin_ms::where('status', '!=', '2')->where(['id' => $main_admin_id, 'admin_branch_id' => $admin_branch_id])->where('role','sub_admin')->count();

            //tbl_ms_sop_branch

            $dashboardData["branch_count"] = Branch::where('created_by', '=', $created_by_id)->count();


            $dashboardData["sop_count"] = SopMaster::where($wh2)->count();


            $dashboardData["workshop_count"] = Workshop::where($wh2)->count();
        }


        return $this->sendResponse($dashboardData, 'Posts fetched.');
    }
}
