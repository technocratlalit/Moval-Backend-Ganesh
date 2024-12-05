<?php

namespace App\Http\Controllers\MotorSurvey;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\SopMaster;
use App\Models\SopField;
use App\Models\Employee;
use App\Models\Msbranchcontact;
use App\Models\Workshop;
use App\Mail\WelcomeEmail;
use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\branch\BranchResource;
use App\Http\Resources\sop\SopResource;
use App\Http\Resources\sop\SopCollection;
use App\Http\Resources\workshop\WorkshopResource;
use App\Http\Resources\workshop\WorkshopBranchResource;
use App\Jobs\SendMail;
use App\Models\Admin_ms;
use App\Models\Workshopbranchms;
use App\Traits\AdminUploadTrait;
// use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Mail;

class BranchMsController extends BaseController
{
    use AdminUploadTrait;

    public function index(Request $request)
    {
        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn1($logged_in_id);

        if (!$request->user()->tokenCan("type:admin")) {
            return $this->sendError(
                "Unauthorised.",
                ["error" => "Not authenticated"],
                401
            );
        }

        $TBL_ADMIN = "tbl_ms_admin";
        // $TBL_branch  = config('global.branch');
        $fields = $request->validate([
            "sop_name" => "required|string",
            "vehichle_images_field_label" => "required",
            //'document_image_field_label' => 'required',
            "admin_branch_id" => "required|numeric",
            "can_record_video" => "required|numeric",
            "admin_id" => "required|numeric|exists:" . $TBL_ADMIN . ",id",
        ]);

        if ($request->document_image_field_label) {
            $document_Data = json_encode($request->document_image_field_label);
        } else {
            $document_Data = "";
        }

        $main_admin_id = getmainAdminIdIfAdminLoggedIn($logged_in_id);

        $currentDate = Carbon::now();
        $data = [
            "super_admin_id" => $main_admin_id,
            "sop_name" => $fields["sop_name"],
            "admin_branch_id" => $fields["admin_branch_id"],
            "admin_id" => $fields["admin_id"],
            "created_at" => $currentDate,
            "can_record_video" => $fields["can_record_video"],
            "vehichle_images_field_label" => json_encode(
                $fields["vehichle_images_field_label"]
            ),
            "document_image_field_label" => $document_Data,
        ];

        $dataid1 = SopMaster::insertGetId($data);

        //	 print_r($fields['vehichle_images_field_label']); die;

        /*	 
                $da= json_decode($fields['vehichle_images_field_label']);
                foreach($da as $key=>$value){

                $data=['admin_branch_id'=>$dataid1,'type'=>1,'form_field_lable'=>$value,'created_at'=>$currentDate];
                $dataid=SopField::insertGetId($data);
                }*/

        /*	$dad= json_decode($fields['document_image_field_label']);
foreach($dad as $key=>$value){

$data=['admin_branch_id'=>$dataid1,'type'=>2,'form_field_lable'=>$value,'created_at'=>$currentDate];
$dataid=SopField::insertGetId($data);
}
*/

        if ($dataid1) {
            return $this->sendResponse("inserted", "success", 200);
        } else {
            return $this->sendError("Error occuered please try again later");
        }
    }

    public function updatesop(Request $request, $id)
    {
        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn($logged_in_id);

        if (!$request->user()->tokenCan("type:admin")) {
            return $this->sendError(
                "Unauthorised.",
                ["error" => "Not authenticated"],
                401
            );
        }

        $data["sop_master"] = SopMaster::where("id", $id)->first();

        if (!$data["sop_master"]) {
            return $this->sendResponse("error", "invalid update id", 200);
        }

        $TBL_ADMIN = "tbl_ms_admin";
        // $TBL_branch  = config('global.branch');
        $fields = $request->validate([
            "sop_name" => "required|string",
            "vehichle_images_field_label" => "required",
            //'document_image_field_label' => 'required',
            "admin_branch_id" => "required|numeric",
            "can_record_video" => "required|numeric",
            "admin_id" => "required|numeric|exists:" . $TBL_ADMIN . ",id",
        ]);

        if ($request->document_image_field_label) {
            $document_Data = $request->document_image_field_label;
        } else {
            $document_Data = "";
        }

        $currentDate = Carbon::now();
        $data = [
            "sop_name" => $fields["sop_name"],
            "admin_branch_id" => $fields["admin_branch_id"],
            "admin_id" => $fields["admin_id"],
            "updated_at" => $currentDate,
            "can_record_video" => $fields["can_record_video"],
            "vehichle_images_field_label" =>
                $fields["vehichle_images_field_label"],
            "document_image_field_label" => $document_Data,
        ];

        $dataid1 = SopMaster::where("id", $id)->update($data);
        // $data['sop_master']=SopMaster::where('id',$id)->first();

        /*	$da= json_decode($fields['vehichle_images_field_label']);
            foreach($da as $key=>$value){

            $data=['form_field_lable'=>$value,'updated_at'=>$currentDate];
            SopField::where(['id'=>$key,'admin_branch_id'=>$id])->update($data);
            }




            $dad= json_decode($fields['document_image_field_label']);
            foreach($dad as $key=>$value){

            $data=['form_field_lable'=>$value,'updated_at'=>$currentDate];
            SopField::where(['id'=>$key,'admin_branch_id'=>$id])->update($data);
            }*/

        if ($dataid1) {
            return $this->sendResponse("updated", "success", 200);
        } else {
            return $this->sendError("Error occuered please try again later");
        }
    }

    public function showsop(Request $request, $id)
    {
        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn1($logged_in_id);

        /*    if(!$request->user()->tokenCan('type:admin')) {
    return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
    }*/
        $data["sop_master"] = SopMaster::where("id", $id)->first();

        if (!$data["sop_master"]) {
            return $this->sendResponse("error", "invalid sop detail", 200);
        }

        return $this->sendResponse(new SopResource($data), "view", 200);
    }

    public function showsopall(Request $request, $id)
    {
        $logged_in_id = Auth::user()->id;
        //$parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);
        $TBL_sop = config("global.sop");
        //echo $request->id; die;
        if (!$request->user()->tokenCan("type:admin")) {
            return $this->sendError(
                "Unauthorised.",
                ["error" => "Not authenticated"],
                401
            );
        }

        if (Auth::user()->role == "admin") {
            // $wh2=['admin_id'=>$logged_in_id];

            if (!$request->exists("all")) {
                $allAdmins = SopMaster::where("admin_id", $logged_in_id)
                    ->orderBy("created_at", "desc")
                    ->paginate(10);
            } else {
                $allAdmins = SopMaster::where("admin_id", $logged_in_id)
                    ->orderBy("created_at", "desc")
                    ->get();
            }
        } else {
            $main_admin_id = getmainAdminIdIfAdminLoggedIn($logged_in_id);
            $admin_branch_id = getBranchidbyAdminid($logged_in_id);

            if (!$request->exists("all")) {
                $allAdmins = SopMaster::where("admin_id", $logged_in_id)
                    ->where("admin_branch_id", $admin_branch_id)
                    ->orderBy("created_at", "desc")
                    ->paginate(10);
            } else {
                $allAdmins = SopMaster::where("super_admin_id", $main_admin_id)
                    ->where("admin_branch_id", $admin_branch_id)
                    ->orderBy("created_at", "desc")
                    ->get();
            }
        }

        // $allAdmins2 = new SopCollection($allAdmins);


        //    print_r($allAdmins); die;

        if (!$request->exists("all")) {
            $data["pagination"] = [
                "total" => $allAdmins->total(),
                "count" => $allAdmins->count(),
                "per_page" => $allAdmins->perPage(),
                "current_page" => $allAdmins->currentPage(),
                "total_pages" => $allAdmins->lastPage(),
            ];
        }

        $data["values"] = [];
        $i = 0;
        foreach ($allAdmins as $sop_master) {
            $branch_name = getBranchbybranchid($sop_master->admin_branch_id);

            // print_r($all); die;
            $data["values"][$i] = [
                "id" => $sop_master->id,
                "sop_name" => $sop_master->sop_name,
                "admin_branch_id" => $sop_master->admin_branch_id,
                "admin_branch_name" => $branch_name,
                "admin_id" => $sop_master->admin_id,
                "can_record_video" => $sop_master->can_record_video,
                "created_by_name" => adminname($sop_master->created_by),
                "vehichle_images_field_label" => json_decode(
                    $sop_master->vehichle_images_field_label
                ),
                "document_image_field_label" => json_decode(
                    $sop_master->document_image_field_label
                ),
                "created_at" => $sop_master->created_at->format("Y-m-d H:i:s"),
                "updated_at" => $sop_master->updated_at
                    ? $sop_master->updated_at->format("Y-m-d H:i:s")
                    : "",
            ];

            $i++;
        }

        return $this->sendResponse($data, "sop Fetched", 200);

        //return $this->sendResponse($data,'sop fetched',200);
    }

    public function delsop(Request $request, $id)
    {
        $TBL_sop = config("global.sop");

        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn1($logged_in_id);

        if (!$request->user()->tokenCan("type:admin")) {
            return $this->sendError(
                "Unauthorised.",
                ["error" => "Not authenticated"],
                401
            );
        }

        SopMaster::where("id", $id)->delete();
        SopField::where("sop_id", $id)->delete();

        return $this->sendResponse("deleted", "sussess", 200);
    }

    public function branch(Request $request)
    {

        $letter_head_img = "";
        $letter_footer_img = "";
        $signature_img = "";

        $TBL_ADMIN = "tbl_ms_admin";
        $fields = $request->validate([
            "admin_branch_name" => "required|string",
            "address" => "required|string",
            "contact_person" => "required|string",
            "mobile_no" => "required",
            "email" => "required|email",
            "admin_id" => "required|numeric|exists:" . $TBL_ADMIN . ",id",
            "created_by" => "required|numeric",
        ]);

        if ($request->hasFile('letter_head_img')) {
            $letter_head_img = $this->storeImage($request->file('letter_head_img'), 'admin_document/letter_head_img');
        }

        if ($request->hasFile('letter_footer_img')) {
            $letter_footer_img = $this->storeImage($request->file('letter_footer_img'), 'admin_document/letter_footer_img');
        }
        //$letter_footer_img = $this->storeImage($request->file('letter_footer_img'), 'admin_document/letter_footer_img');
        if ($request->hasFile('signature_img')) {
            $signature_img = $this->storeImage($request->file('signature_img'), 'admin_document/signature_img');
        }

        $currentDate = Carbon::now();
        $data = [
            "admin_branch_name" => $fields["admin_branch_name"],
            "address" => $fields["address"],
            "contact_person" => $fields["contact_person"],
            "mobile_no" => $fields["mobile_no"],
            "email" => $fields["email"],
            "admin_id" => $fields["admin_id"],
            "created_by" => $fields["created_by"],
            "created_at" => $currentDate,
            "letter_head_img" => $letter_head_img ?? null,
            "letter_footer_img" => $letter_footer_img ?? null,
            "signature_img" => $signature_img ?? null
        ];

        $dataid = Branch::insertGetId($data);

        if ($dataid) {
            return $this->sendResponse("inserted", "success", 200);
        } else {
            return $this->sendError("Error occuered please try again later");
        }
    }

    public function branchupdate(Request $request, $id)
    {
        $TBL_ADMIN = "tbl_ms_admin";
        $fields = $request->validate([
            "admin_branch_name" => "required|string",
            "address" => "required|string",
            "contact_person" => "required|string",
            "mobile_no" => "required",
            "email" => "required|email",
            "admin_id" => "required|numeric|exists:" . $TBL_ADMIN . ",id",
            "created_by" => "required|numeric",
        ]);

        $currentDate = Carbon::now();
        $data = [
            "admin_branch_name" => $fields["admin_branch_name"],
            "address" => $fields["address"],
            "contact_person" => $fields["contact_person"],
            "mobile_no" => $fields["mobile_no"],
            "email" => $fields["email"],
            "admin_id" => $fields["admin_id"],
            "created_by" => $fields["created_by"],
            "updated_at" => $currentDate,
        ];

        $dataid = Branch::where("id", $id)->update($data);

        $data["id"] = $id;

        $letter_head_img = "";
        $letter_footer_img = "";
        $signature_img = "";

        $admin = Branch::find($id);

        if ($admin) {
            if ($request->hasFile('letter_head_img')) {
                $letter_head_img = $this->storeImage($request->file('letter_head_img'), 'admin_document/letter_head_img');
            }

            if ($request->hasFile('letter_footer_img')) {
                $letter_footer_img = $this->storeImage($request->file('letter_footer_img'), 'admin_document/letter_footer_img');
            }
            if ($request->hasFile('signature_img')) {
                $signature_img = $this->storeImage($request->file('signature_img'), 'admin_document/signature_img');
            }

            // Delete existing letter_head_img if a new one is provided
            if ($letter_head_img && $admin->letter_head_img) {
                Storage::disk('public')->delete($admin->letter_head_img);
                $admin->letter_head_img = $letter_head_img;
            } else {
                if (!empty($letter_head_img)) {
                    $admin->letter_head_img = $letter_head_img;
                }

            }

            // Delete existing letter_footer_img if a new one is provided
            if ($letter_footer_img && $admin->letter_footer_img) {
                Storage::disk('public')->delete($admin->letter_footer_img);
                $admin->letter_footer_img = $letter_footer_img;
            } else {
                if (!empty($letter_footer_img)) {
                    $admin->letter_footer_img = $letter_footer_img;
                }

            }

            // Delete existing signature_img if a new one is provided
            if ($signature_img && $admin->signature_img) {
                Storage::disk('public')->delete($admin->signature_img);
                $admin->signature_img = $signature_img;
            } else {
                if (!empty($signature_img)) {
                    $admin->signature_img = $signature_img;
                }
            }
        }
        $admin->save();

        if ($dataid) {
            return $this->sendResponse(
                new BranchResource($data),
                "Branch updated.",
                200
            );
        } else {
            return $this->sendError("Error occuered please try again later");
        }
    }

    public function branchshow(Request $request, $id)
    {
        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn1($logged_in_id);

        if (!$request->user()->tokenCan("type:admin")) {
            return $this->sendError(
                "Unauthorised.",
                ["error" => "Not authenticated"],
                401
            );
        }
        $data["sop_master"] = Branch::where("id", $id)->first();
        //  print_r($data['sop_master']); die;
        if (!$data["sop_master"]) {
            return $this->sendResponse("error", "invalid Branch id", 200);
        }

        return $this->sendResponse(
            new BranchResource($data),
            "Branch Fetched",
            200
        );
    }

    public function branchdelete(Request $request, $id)
    {
        $TBL_sop = config("global.branch_sop");

        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn1($logged_in_id);

        if (!$request->user()->tokenCan("type:admin")) {
            return $this->sendError(
                "Unauthorised.",
                ["error" => "Not authenticated"],
                401
            );
        }

        $d = Branch::where("id", $id)->delete();
        if ($d == 1) {
            return $this->sendResponse("Branch deleted", "success", 200);
        } else {
            return $this->sendError(
                "unable to delete",
                ["error" => "invalid id"],
                200
            );
        }
    }

    public function workshop(Request $request)
    {
        $admin_id = Auth::user()->id;
        /*
            $parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);
            if(!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
            }

            $loggedInAdmin = Admin::where('id','=', $created_by)->where('status','!=', '2')->first();
            if (is_null($loggedInAdmin)) {
            return $this->sendError('Admin does not exist.');
        }  */

        $main_admin_id = getmainAdminIdIfAdminLoggedIn($admin_id);

        $TBL_ADMIN = "tbl_ms_admin";
        $wordkshop = $request->validate([
            "workshop_name" => "required|string",
            "address" => "required|string",
            "gst_no" => "required|string",
            "contact_detail" => "required|string",
            "admin_branch_id" => "required|numeric",
            "admin_id" => "required|numeric|exists:" . $TBL_ADMIN . ",id",
        ]);

        $wordkshopbranch = $request->validate([
            "workshop_branch_name" => "required|string",
            "contact_details" => "required|string",
            "address" => "required|string",
            "workshop_address" => "required|string",
            "gst_no" => "required|string",
            "manager_name" => "required|string",
            "manager_mobile_num" => "required|numeric|digits:10",
            "manager_email" => "required|email",
            //'workshop_id'=> 'required|numeric|exists:'.$TBL_workshop.',id',
            "created_by" => "required|numeric",
        ]);

        $currentDate = Carbon::now();

        $currentDate = Carbon::now();
        $TBL_workshop_branch = "tbl_ms_workshop_branch";
        $TBL_workshop = "tbl_ms_workshop";

        $logged_in_id = Auth::user()->id;
        //  $parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

        $data = [
            "workshop_name" => $wordkshop["workshop_name"],
            "admin_branch_id" => $wordkshop["admin_branch_id"],
            "address" => $wordkshop["address"],
            "gst_no" => $wordkshop["gst_no"],
            "contact_detail" => $wordkshop["contact_detail"],
            "admin_id" => $wordkshop["admin_id"],
            "super_admin_id" => $main_admin_id,
            "created_at" => $currentDate,
        ];

        $dataid = Workshop::insertGetId($data);

        $employee = Workshopbranchms::create([
            "workshop_branch_name" => $wordkshopbranch["workshop_branch_name"],
            "contact_details" => $wordkshopbranch["contact_details"],
            "address" => $wordkshopbranch["workshop_address"],
            'gst_no' => $wordkshopbranch['gst_no'],
            "manager_mobile_num" => $wordkshopbranch["manager_mobile_num"],
            "manager_name" => $wordkshopbranch["manager_name"],
            "manager_email" => $wordkshopbranch["manager_email"],
            "workshop_id" => $dataid,
            "created_by" => $wordkshopbranch["created_by"],
            "created_at" => $currentDate,
        ]);

        //  return $this->sendResponse("sussessfully inserted","success",200);

        if ($dataid) {
            $result_array["workshop_id"] = $dataid;
            //  return $this->sendResponse(new BranchResource($data), 'Branch updated.',200);
            return $this->sendResponse(
                $result_array,
                "workshop created , Branch Created",
                200
            );
        } else {
            return $this->sendError("Error occuered please try again later");
        }
    }

    public function getBranchAdmins(Request $request, $id)
    {
        try {
            $branchAdmin = Admin_ms::where(['admin_branch_id' => $id, 'role' => 'branch_admin'])->get(['id', 'name as admin_branch_name', 'email']);
            $perPage = $request->input('page', 10);
            if (!$branchAdmin) {
                return $this->sendError("Branch not found", 404);
            }

            $branchAdmins['values'] = $branchAdmin;

            $branchAdmins['pagination'] = [
                'total' => count($branchAdmin),
                'count' => count($branchAdmin),
                'per_page' => $perPage,
                'current_page' => 1, // Assuming you're returning the first page
                'total_pages' => 1,
            ];

            return $this->sendResponse($branchAdmins, "Branch Admins Fetched", 200);
        } catch (\Exception $e) {
            return $this->sendError("Internal Server Error", 500);
        }
    }

    public function branchshowall(Request $request, $id)
    {
        $page = ($request->exists('all') || (isset($request->page) && $request->page == 'all')) ? true : false;
        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn1($logged_in_id);
        if (Auth::user()->role != "employee") {
            $mainadmin = getmainAdminIdIfAdminLoggedIn($id);
            if (getadminrole($id) == "admin") {
                $Branches = Branch::where("admin_id", $id)->orwhere("admin_id", $mainadmin)->orderBy("id", "desc");
            } else {
                $branch = getBranchidofadmin($id);
                $Branches = Branch::where("admin_id", $mainadmin)->where("id", $branch)->orderBy("id", "desc");
            }
        } else {
            $branch = getBranchidofemplyee($id);
            $Branches = Branch::where("id", $branch);
        }

        if(!empty($page)) {
            $Branches = $Branches->get();
        } else {
            $Branches = $Branches->paginate(10);
        }

        if(empty($page)) {
            $data["pagination"] = [
                "total" => $Branches->total(),
                "count" => $Branches->count(),
                "per_page" => $Branches->perPage(),
                "current_page" => $Branches->currentPage(),
                "total_pages" => $Branches->lastPage(),
            ];
        }

        $data["values"] = [];
        foreach ($Branches as $i => $all) {
            $data["values"][$i] = [
                "branch_name" => $all->admin_branch_name,
                "admin_branch_name" => $all->admin_branch_name,
                "id" => $all->id,
                "email" => $all->email,
                "address" => $all->address,
                "branch_id" => $all->branch_id,
                "mobile_no" => $all->mobile_no,
                "contact_person" => $all->contact_person,
                "admin_id" => $all->admin_id,
                "created_by_name" => adminname($all->created_by),
                "created_at" => $all->created_at->format("Y-m-d H:i:s"),
                "updated_at" => $all->updated_at ? $all->updated_at->format("Y-m-d H:i:s") : "",
            ];
        }

        return $this->sendResponse($data, "Branch Fetched", 200);
    }


    public function workshopupdate(Request $request, $id)
    {
        $TBL_ADMIN = "tbl_ms_admin";
        $fields = $request->validate([
            "workshop_name" => "required|string",
            "address" => "required|string",
            "gst_no" => "required|string",
            "admin_branch_id" => "required|numeric",
            "contact_detail" => "required|string",
            "admin_id" => "required|numeric|exists:" . $TBL_ADMIN . ",id",
        ]);

        $currentDate = Carbon::now();
        $data = [
            "workshop_name" => $fields["workshop_name"],
            "admin_branch_id" => $fields["admin_branch_id"],
            "address" => $fields["address"],
            "gst_no" => $fields["gst_no"],
            "contact_detail" => $fields["contact_detail"],
            "admin_id" => $fields["admin_id"],
            "updated_at" => $currentDate,
        ];

        $dataid = Workshop::where("id", $id)->update($data);

        if ($dataid) {
            return $this->sendResponse("Success", "workshop updated.", 200);
        } else {
            return $this->sendError("Error occuered please try again later");
        }
    }

    public function workshopdelete(Request $request, $id)
    {
        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn1($logged_in_id);

        if (!$request->user()->tokenCan("type:admin")) {
            return $this->sendError(
                "Unauthorised.",
                ["error" => "Not authenticated"],
                401
            );
        }
        $dataid = Workshop::where("id", $id)->delete();

        if ($dataid) {
            return $this->sendResponse("Success", "workshop Deleted.", 200);
        } else {
            return $this->sendError("Error occuered please try again later");
        }
    }

    public function workshopshow(Request $request, $id)
    {
        /* $logged_in_id       = Auth::user()->id;
$parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

if(!$request->user()->tokenCan('type:admin')) {
return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
} */
        $data["Workshop"] = Workshop::where("id", $id)->first();

        if (!$data["Workshop"]) {
            return $this->sendResponse("error", "invalid Workshop id", 200);
        }
        $data["id"] = $id;
        return $this->sendResponse(
            new WorkshopResource($data),
            "Workshop Fetched",
            200
        );
    }

    public function workshopshowall(Request $request, $id)
    {
        $logged_in_id = Auth::user()->id;


        $authrole = Auth::user()->role;
        if ($authrole == "branch_admin" || $authrole == "sub_admin") {
            $data["admin_branch_id"] = Auth::user()->admin_branch_id;
        }

        if (Auth::user()->role == "admin") {
            $Workshop = Workshop::where("super_admin_id", $logged_in_id)
                ->orderBy("id", "desc")
                ->paginate(10);
        } else {
            $main_admin_id = getmainAdminIdIfAdminLoggedIn($logged_in_id);
            $admin_branch_id = getBranchidbyAdminid($logged_in_id);

            $Workshop = Workshop::where("super_admin_id", $main_admin_id)
                ->where("admin_branch_id", $admin_branch_id)
                ->paginate(10);
        }

        $i = 0;
        if (!$request->exists("all")) {
            $data["pagination"] = [
                "total" => $Workshop->total(),
                "count" => $Workshop->count(),
                "per_page" => $Workshop->perPage(),
                "current_page" => $Workshop->currentPage(),
                "total_pages" => $Workshop->lastPage(),
            ];
        }
        $data["values"] = [];
        foreach ($Workshop as $sop_master) {
            $branch_name = getBranchbybranchid($sop_master->admin_branch_id);

            $data["values"][$i] = [
                "id" => $sop_master->id,
                "workshop_name" => $sop_master->workshop_name,
                "address" => $sop_master->address,
                "branch_name" => $branch_name,
                "admin_branch_id" => $sop_master->admin_branch_id,
                "gst_no" => $sop_master->gst_no,
                "contact_detail" => $sop_master->contact_detail,
                "admin_id" => $sop_master->admin_id,
                "created_at" => $sop_master->created_at->format("Y-m-d H:i:s"),
                "updated_at" => $sop_master->updated_at
                    ? $sop_master->updated_at->format("Y-m-d H:i:s")
                    : "",
            ];
            $i++;
        }

        return $this->sendResponse($data, "Workshop Fetched", 200);
    }

    public function workshopbranch(Request $request)
    {
        /* $admin_id       = Auth::user()->id;
$parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

if(!$request->user()->tokenCan('type:admin')) {
return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
}

$loggedInAdmin = Admin::where('id','=', $created_by)->where('status','!=', '2')->first();
if (is_null($loggedInAdmin)) {
return $this->sendError('Admin does not exist.');
}  */

        $TBL_worksp = "tbl_ms_workshop_contact_person";
        $TBL_ADMIN = "tbl_ms_admin";
        $TBL_emplyee = "tbl_ms_employee";
        $TBL_MS_SUPERADMIN = "tbl_ms_super_admin";
        $fields = $request->validate([
            "name" => "required|string",
            "mobile_no" =>
                "required|numeric|digits:10|unique:" .
                $TBL_ADMIN .
                ",mobile_no|unique:" .
                $TBL_emplyee .
                ",mobile_no|unique:" .
                $TBL_worksp .
                ",mobile_no|unique:" .
                $TBL_emplyee .
                ",mobile_no|unique:" .
                $TBL_MS_SUPERADMIN .
                ",mobile_no",
            "email" =>
                "required|email|unique:" .
                $TBL_worksp .
                ",email|unique:" .
                $TBL_ADMIN .
                ",email|unique:" .
                $TBL_MS_SUPERADMIN .
                ",email",
            "otp" => "",
            "created_by" => "required|numeric",
            "username" =>
                "required|string|unique:" .
                $TBL_worksp .
                ",username|unique:" .
                $TBL_emplyee .
                ",user_id",
            "workshopbranch_id" => "required|numeric",
        ]);

        $TBL_MS_SUPERADMIN = "tbl_ms_super_admin";
        $TBL_MS_ADMIN = "tbl_ms_admin";
        $TBL_MS_WK = "tbl_ms_workshop_contact_person";
        $TBL_MS_EMPLOYEE = "tbl_ms_employee";

        $fields1 = $request->validate([
            "email" =>
                "required|string|unique:" .
                $TBL_MS_SUPERADMIN .
                ",email|unique:" .
                $TBL_MS_ADMIN .
                "|unique:" .
                $TBL_MS_WK .
                "|unique:" .
                $TBL_MS_EMPLOYEE .
                "",
        ]);
        /*
if(checkEmailExistOrNot($fields['email'])!=""){
return $this->sendError('This Email Id is already used by the other user so please enter other email Id.');
}*/

        $currentDate = Carbon::now();
        $data = [
            "name" => $fields["name"],
            "mobile_no" => $fields["mobile_no"],
            "email" => $fields["email"],
            "otp" => bcrypt($fields["otp"]),
            "created_by" => $fields["created_by"],
            "username" => $fields["username"],
            "workshop_branch_id" => $fields["workshopbranch_id"],
            "created_at" => $currentDate,
        ];

        $email = $fields["email"];
        $maildata = [
            "subject" => "OTP for the Moval",
            "from_name" => config("app.from_name"),
            "fullName" => $fields["name"],
            "userId" => $fields["email"],
            "password" => $fields["otp"],
            "months" => 0,
            "adminLoginLink" => config("app.website_url"),
            "contactEmail" => config("app.from_email_address"),
            "mail_template" => "emails.admin-welcome-email",
        ];
        SendMail::dispatch($email, $maildata)->onQueue('send-email')->onConnection('database');
        // Mail::to($email)->send(new WelcomeEmail($maildata));

        $dataid = Msbranchcontact::insertGetId($data);

        // $data['id']=$id;

        if ($dataid) {
            //  return $this->sendResponse(new BranchResource($data), 'Branch updated.',200);
            return $this->sendResponse(
                "Success",
                "workshop Branch contact created.",
                200
            );
        } else {
            return $this->sendError("Error occuered please try again later");
        }
    }

    // START UPDATE

    public function workshopbranchupdte(Request $request, $id)
    {
        /* $admin_id       = Auth::user()->id;
$parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

if(!$request->user()->tokenCan('type:admin')) {
return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
}

$loggedInAdmin = Admin::where('id','=', $created_by)->where('status','!=', '2')->first();
if (is_null($loggedInAdmin)) {
return $this->sendError('Admin does not exist.');
}  */

        $TBL_ADMIN = "tbl_ms_admin";

        $fields = $request->validate([
            "name" => "required|string",
            "mobile_no" => "required|numeric|digits:10",
            "email" => "required|email",
            "otp" => "",
            "created_by" => "required|numeric",
            "username" => "required|string",
            "workshop_branch_id" => "required|numeric",
        ]);

        $currentDate = Carbon::now();
        $data = [
            "name" => $fields["name"],
            "mobile_no" => $fields["mobile_no"],
            "email" => $fields["email"],
            "otp" => bcrypt($fields["otp"]),
            "created_by" => $fields["created_by"],
            "username" => $fields["username"],
            "workshop_branch_id" => $fields["workshopbranch_id"],
            "updated_at" => $currentDate,
        ];

        $dataid = Msbranchcontact::where("id", $id)->update($data);

        if ($dataid) {
            //  return $this->sendResponse(new BranchResource($data), 'Branch updated.',200);
            return $this->sendResponse(
                "Success",
                "workshop Branch contact updated.",
                200
            );
        } else {
            return $this->sendError("Error occuered please try again later");
        }
    }

    // delete

    public function workshopbranchdelete(Request $request, $id)
    {
        /* $admin_id       = Auth::user()->id;
$parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

if(!$request->user()->tokenCan('type:admin')) {
return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
}

$loggedInAdmin = Admin::where('id','=', $created_by)->where('status','!=', '2')->first();
if (is_null($loggedInAdmin)) {
return $this->sendError('Admin does not exist.');
}  */

        $dataid = Msbranchcontact::where("id", $id)->delete();

        if ($dataid) {
            //  return $this->sendResponse(new BranchResource($data), 'Branch updated.',200);
            return $this->sendResponse(
                "Success",
                "workshop Branch deleted.",
                200
            );
        } else {
            return $this->sendError("Error occuered please try again later");
        }
    }

    // start

    public function workshopbranchshow(Request $request, $id)
    {
        /* $logged_in_id       = Auth::user()->id;
$parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

if(!$request->user()->tokenCan('type:admin')) {
return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
} */
        $data["Workshop"] = Msbranchcontact::where("id", $id)->first();

        if (!$data["Workshop"]) {
            return $this->sendResponse("error", "invalid Workshop id", 200);
        }
        $data["id"] = $id;
        return $this->sendResponse(
            new WorkshopBranchResource($data),
            "Workshop Branch Fetched",
            200
        );
    }

    public function workshopbranchshowall(Request $request)
    {
        /* $logged_in_id       = Auth::user()->id;
$parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

if(!$request->user()->tokenCan('type:admin')) {
return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
} */
        $Workshop = Msbranchcontact::paginate(10);

        $i = 0;
        $data["total"] = $Workshop->total();
        $data["last_page"] = $Workshop->lastPage();
        $data["items_per_page"] = $Workshop->perPage();

        foreach ($Workshop as $sop_master) {
            $data[$i] = [
                "id" => $sop_master->id,
                "name" => $sop_master->name,
                "mobile_no" => $sop_master->mobile_no,
                "email" => $sop_master->email,
                "otp" => $sop_master->otp,
                "created_by" => $sop_master->created_by,
                "username" => $sop_master->username,
                "workshop_branch_id" => $sop_master->workshopbranch_id,
                "created_at" => $sop_master->created_at->format("Y-m-d H:i:s"),
                "updated_at" => $sop_master->updated_at
                    ? $sop_master->updated_at->format("Y-m-d H:i:s")
                    : "",
            ];
            $i++;
        }

        return $this->sendResponse($data, "Workshop Branch Fetched", 200);
    }

    public function workshopbranchshowbyid(Request $request, $id)
    {
        /* $logged_in_id       = Auth::user()->id;
$parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

if(!$request->user()->tokenCan('type:admin')) {
return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
} */
        $Workshop = Msbranchcontact::where("workshop_branch_id", $id)->get();

        if ($Workshop->count() == 0) {
            return $this->sendError("Workshop branch contact does not exist.");
        }
        $i = 0;

        foreach ($Workshop as $sop_master) {
            $data["values"][$i] = [
                "id" => $sop_master->id,
                "name" => $sop_master->name,
                "mobile_no" => $sop_master->mobile_no,
                "email" => $sop_master->email,
                "otp" => $sop_master->otp,
                //'role'=>'WS branch person',
                "created_by" => $sop_master->created_by,
                "username" => $sop_master->username,
                "workshop_branch_id" => $sop_master->workshopbranch_id,
                "created_at" => $sop_master->created_at->format("Y-m-d H:i:s"),
                "updated_at" => $sop_master->updated_at
                    ? $sop_master->updated_at->format("Y-m-d H:i:s")
                    : "",
            ];
            $i++;
        }

        return $this->sendResponse(
            $data,
            "Workshop Branch Contact Fetched",
            200
        );
    }

    public function worksopContactSetPassword(Request $request)
    {
        $id = Auth::user()->id;
        $Workshop = Msbranchcontact::where("id", $id)->first();
        if ($Workshop) {
            $password = $request->password;
            $cpassword = $request->password_confirmation;
            if ($cpassword == $password) {
                $Workshop->otp = bcrypt($cpassword);
                $Workshop->is_set_password = "yes";
                $Workshop->update();
                return $this->sendResponse("Success", "Password Updated", 200);
            } else {
                return $this->sendError(
                    "Password And confirm password is different"
                );
            }

            $oldotp = $Workshop->otp;
        } else {
            return $this->sendError("Somthing Went wrong.Please try again");
        }
    }

    public function deleteAdminBranchLetterhead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'letter_head_delete_type' => 'required|in:admin,admin_branch',
            'id' => 'required|integer',
            'delete_type' => 'required|in:1,2,3',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->letter_head_delete_type == 'admin') {

            if (Admin_ms::where('id', $request->id)->exists()) {

                $admin = Admin_ms::where('id', $request->id)->first();

                if ($request->delete_type == 1 && $admin->letter_head_img) {
                    Storage::disk('public')->delete($admin->letter_head_img);
                    $admin->letter_head_img = Null;
                }

                // Delete existing letter_footer_img if a new one is provided
                if ($request->delete_type == 2 && $admin->letter_footer_img) {
                    Storage::disk('public')->delete($admin->letter_footer_img);
                    $admin->letter_footer_img = NULL;
                }

                // Delete existing signature_img if a new one is provided
                if ($request->delete_type == 3 && $admin->signature_img) {
                    Storage::disk('public')->delete($admin->signature_img);
                    $admin->signature_img = NULL;
                }

                $admin->update();
            } else {
                return $this->sendError("admin Id not found.!!");
            }
            return $this->sendResponse([], 'Admin image deleted successfully.', 200);
        } else {

            if (Branch::where('id', $request->id)->exists()) {

                $branch = Branch::where('id', $request->id)->first();

                if ($request->delete_type == 1 && $branch->letter_head_img) {
                    Storage::disk('public')->delete($branch->letter_head_img);
                    $branch->letter_head_img = Null;
                }

                // Delete existing letter_footer_img if a new one is provided
                if ($request->delete_type == 2 && $branch->letter_footer_img) {
                    Storage::disk('public')->delete($branch->letter_footer_img);
                    $branch->letter_footer_img = NULL;
                }

                // Delete existing signature_img if a new one is provided
                if ($request->delete_type == 3 && $branch->signature_img) {
                    Storage::disk('public')->delete($branch->signature_img);
                    $branch->signature_img = NULL;
                }

                $branch->update();
            } else {
                return $this->sendError("Branch Id not found.!!");
            }

            return $this->sendResponse([], 'Branch image deleted successfully.', 200);
        }
    }
}
