<?php

namespace App\Http\Controllers\MotorValuation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
use App\Models\Workshopbranchms;
use Illuminate\Support\Facades\Mail;

// use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\BaseController;
use App\Models\Admin_ms;

class SopController extends BaseController
{
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
        $fields = $request->validate([
            "sop_name" => "required|string",
            "vehichle_images_field_label" => "required",
            "admin_branch_id" => "required|numeric",
            "can_record_video" => "required|numeric",
            "is_location_allowed" => "required|numeric",
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
            "admin_id" => $fields["admin_id"],
            "sop_name" => $fields["sop_name"],
            "admin_branch_id" => $fields["admin_branch_id"],
            "created_at" => $currentDate,
            "can_record_video" => $fields["can_record_video"],
            "is_location_allowed" => $fields["is_location_allowed"],
            "vehichle_images_field_label" => json_encode(
                $fields["vehichle_images_field_label"]
            ),
            "document_image_field_label" => $document_Data,
        ];

        $dataid1 = SopMaster::insertGetId($data);
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
        $fields = $request->validate([
            "sop_name" => "required|string",
            "vehichle_images_field_label" => "required",
            "is_location_allowed" => "required|numeric",
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
            "is_location_allowed" => $fields["is_location_allowed"],
            "can_record_video" => $fields["can_record_video"],
            "vehichle_images_field_label" =>
                $fields["vehichle_images_field_label"],
            "document_image_field_label" => $document_Data,
        ];
        $dataid1 = SopMaster::where("id", $id)->update($data);
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
        $data["sop_master"] = SopMaster::where("id", $id)->first();

        if (!$data["sop_master"]) {
            return $this->sendResponse("error", "invalid sop detail", 200);
        }
        return $this->sendResponse(new SopResource($data), "view", 200);
    }

    public function showsopall(Request $request, $id)
    {
        $logged_in_id = Auth::user()->id;
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
            $branchIds = getAdminBranchChilderns(Auth::user()->id);
            if (!$request->exists("all")) {
                $allAdmins = SopMaster::whereIn("admin_branch_id", $branchIds)
                    ->orWhere("admin_id", $logged_in_id)
                    ->orderBy("created_at", "desc")
                    ->paginate(10);
            } else {
                $allAdmins = SopMaster::orWhere("admin_id", $logged_in_id)
                    ->whereIn("admin_branch_id", $branchIds)
                    ->orderBy("created_at", "desc")
                    ->get();
            }
        } else if (Auth::user()->role == 'branch_admin') {

            $admin_id = Auth::user()->id;
            $admin_branch_id = Auth::user()->admin_branch_id;
            if (!$request->exists("all")) {
                $allAdmins = SopMaster::where(['admin_branch_id' => $admin_branch_id])
                    ->orWhere(["admin_id" => $admin_id])
                    ->orderBy("created_at", "desc")
                    ->paginate(10);
            } else {
                $allAdmins = SopMaster::where(['admin_branch_id' => $admin_branch_id])
                    ->orWhere(["admin_id" => $admin_id])
                    ->orderBy("created_at", "desc")
                    ->get();
            }


        } else if (Auth::user()->role == 'sub_admin') {
            $parent_admin_id = Auth::user()->parent_id;
            $admin_branch_id = Auth::user()->admin_branch_id;
            if (!$request->exists("all")) {
                $allAdmins = SopMaster::where(['admin_branch_id' => $admin_branch_id])
                    ->orWhere(["admin_id" => $parent_admin_id])
                    ->orderBy("created_at", "desc")
                    ->paginate(10);
            } else {
                $allAdmins = SopMaster::where(['admin_branch_id' => $admin_branch_id])
                    ->orWhere(["admin_id" => $parent_admin_id])
                    ->orderBy("created_at", "desc")
                    ->get();
            }
        }

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
                "branch_name" => $branch_name,
                "admin_id" => $sop_master->admin_id,
                "created_by" => $sop_master->admin_id,
                "created_by_name" => adminname($sop_master->admin_id),
                "can_record_video" => $sop_master->can_record_video,
                "vehichle_images_field_label" => json_decode($sop_master->vehichle_images_field_label),
                "document_image_field_label" => json_decode($sop_master->document_image_field_label),
                "created_at" => $sop_master->created_at->format("Y-m-d H:i:s"),
                "updated_at" => $sop_master->updated_at
                    ? $sop_master->updated_at->format("Y-m-d H:i:s")
                    : "",
            ];

            $i++;
        }
        return $this->sendResponse($data, "sop Fetched", 200);
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
        $TBL_ADMIN = "tbl_ms_admin";
        $fields = $request->validate([
            "branch" => "required|string",
            "address" => "required|string",
            "contact_person" => "required|string",
            "mobile_no" => "required",
            "email" => "required|email",
            "admin_id" => "required|numeric|exists:" . $TBL_ADMIN . ",id",
            "created_by" => "required|numeric",
        ]);
        $currentDate = Carbon::now();
        $data = [
            "branch_name" => $fields["branch"],
            "address" => $fields["address"],
            "contact_person" => $fields["contact_person"],
            "mobile_no" => $fields["mobile_no"],
            "email" => $fields["email"],
            "admin_id" => $fields["admin_id"],
            "created_by" => $fields["created_by"],
            "created_at" => $currentDate,
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
            "branch" => "required|string",
            "address" => "required|string",
            "contact_person" => "required|string",
            "mobile_no" => "required",
            "email" => "required|email",
            "admin_id" => "required|numeric|exists:" . $TBL_ADMIN . ",id",
            "created_by" => "required|numeric",
        ]);
        $currentDate = Carbon::now();
        $data = [
            "branch_name" => $fields["branch"],
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
        $main_admin_id = getmainAdminIdIfAdminLoggedIn($admin_id);
        $TBL_ADMIN = "tbl_ms_admin";
        $fields = $request->validate([
            "workshop_name" => "required|string",
            "address" => "required|string",
            "gst_no" => "required|string",
            "contact_detail" => "required|string",
            "admin_branch_id" => "required|numeric",
            "admin_id" => "required|numeric|exists:" . $TBL_ADMIN . ",id",
        ]);
        $currentDate = date('Y-m-d H:i:s');
        $data = [
            "workshop_name" => $fields["workshop_name"],
            "admin_branch_id" => $fields["admin_branch_id"],
            "address" => $fields["address"],
            "gst_no" => $fields["gst_no"],
            "contact_detail" => $fields["contact_detail"],
            "admin_id" => Auth::user()->id,
            "created_at" => $currentDate,
        ];

        $dataid = Workshop::insertGetId($data);

        $currentDate = Carbon::now();
        $TBL_workshop_branch = "tbl_ms_workshop_branch";
        $TBL_workshop = "tbl_ms_workshop";

        $logged_in_id = Auth::user()->id;
        $fields = $request->validate([
            "workshop_branch_name" => "required|string",
            "contact_details" => "required|string",
            "address" => "required|string",
            "workshop_address" => "required|string",
            "gst_no" => "required|string|",
            "manager_name" => "required|string",
            "manager_mobile_num" => "required|numeric|digits:10",
            "manager_email" => "required|email",
            //'workshop_id'=> 'required|numeric|exists:'.$TBL_workshop.',id',
            "created_by" => "required|numeric",
        ]);

        $employee = Workshopbranchms::create([
            "workshop_branch_name" => $fields["workshop_branch_name"],
            "contact_details" => $fields["contact_details"],
            "address" => $fields["workshop_address"],
            //'gst_no' => $fields['gst_no'],
            "manager_mobile_num" => $fields["manager_mobile_num"],
            "manager_name" => $fields["manager_name"],
            "manager_email" => $fields["manager_email"],
            "workshop_id" => $dataid,
            "created_by" => $fields["created_by"],
            "created_at" => $currentDate,
        ]);

        if ($dataid) {
            $result_array["workshop_id"] = $dataid;
            return $this->sendResponse(
                $result_array,
                "workshop created , Branch Created",
                200
            );
        } else {
            return $this->sendError("Error occuered please try again later");
        }
    }


    public function branchshowall(Request $request, $id)
    {
        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn1($logged_in_id);
        if (Auth::user()->role != "employee") {
            $mainadmin = getmainAdminIdIfAdminLoggedIn($id);
            if (getadminrole($id) == "admin") {
                $Branches = Branch::where("admin_id", $id)
                    ->orwhere("admin_id", $mainadmin)
                    ->orderBy("id", "desc")
                    ->paginate(10);
            } else {
                $branch = getBranchidofadmin($id);
                $Branches = Branch::where("admin_id", $mainadmin)
                    ->where("id", $branch)
                    ->orderBy("id", "desc")
                    ->paginate(10);
            }
        } else {
            $branch = getBranchidofemplyee($id);
            $Branches = Branch::where("id", $branch)->paginate(10);
        }

        if (!$request->exists("all")) {
            $data["pagination"] = [
                "total" => $Branches->total(),
                "count" => $Branches->count(),
                "per_page" => $Branches->perPage(),
                "current_page" => $Branches->currentPage(),
                "total_pages" => $Branches->lastPage(),
            ];
        }

        $data["values"] = [];
        $i = 0;
        foreach ($Branches as $all) {
            $data["values"][$i] = [
                "branch_name" => $all->branch_name,
                "id" => $all->id,
                "email" => $all->email,
                "address" => $all->address,
                "admin_branch_id" => $all->admin_branch_id,
                "mobile_no" => $all->mobile_no,
                "contact_person" => $all->contact_person,
                "admin_id" => $all->admin_id,
                "created_by" => $all->created_by,
                "created_at" => $all->created_at->format("Y-m-d H:i:s"),
                "updated_at" => $all->updated_at
                    ? $all->updated_at->format("Y-m-d H:i:s")
                    : "",
            ];
            $i++;
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

        $children = Auth::user()->children->pluck('id')->toArray();
        $subChildren = Admin_ms::whereIn('parent_id', $children)->pluck('id')->toArray();
        if (Auth::user()->role == "admin") {
            $Workshop = Workshop::where(function ($query) use ($logged_in_id, $children, $subChildren) {
                $query->where('admin_id', $logged_in_id)
                    ->orWhereIn('admin_id', $children)
                    ->orWhereIn('admin_id', $subChildren);
            })
                ->orderBy('id', 'desc')
                ->paginate(10);

        } else if (Auth::user()->role == 'branch_admin') {
            $logged_in_id = Auth::user()->parent_id;
            $admin_branch_id = Auth::user()->admin_branch_id;
            $Workshop = Workshop::where(function ($query) use ($logged_in_id, $children, $admin_branch_id) {
                $query->where('admin_id', $logged_in_id)
                    ->where(["admin_branch_id" => $admin_branch_id])
                    ->orWhereIn('admin_id', $children);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        } else if (Auth::user()->role == 'sub_admin') {
            $logged_in_id = Auth::user()->parent_id;
            $admin_branch_id = Auth::user()->admin_branch_id;
            $Workshop = Workshop::Where(["admin_id" => $logged_in_id])
                ->orWhere(["admin_branch_id" => $admin_branch_id])
                ->orderBy("id", "desc")
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
                "created_by_name" => adminname($sop_master->admin_id),
                "created_at" => $sop_master->created_at->format("Y-m-d H:i:s"),
                "updated_at" => $sop_master->updated_at ? $sop_master->updated_at->format("Y-m-d H:i:s") : "",
            ];
            $i++;
        }

        return $this->sendResponse($data, "Workshop Fetched", 200);
    }

    public function workshopbranch(Request $request)
    {
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
            "workshop_branch_id" => "required|numeric",
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


        $currentDate = Carbon::now();
        $data = [
            "name" => $fields["name"],
            "mobile_no" => $fields["mobile_no"],
            "email" => $fields["email"],
            "otp" => bcrypt($fields["otp"]),
            "created_by" => $fields["created_by"],
            "username" => $fields["username"],
            "workshop_branch_id" => $fields["workshop_branch_id"],
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
        $dataid = Msbranchcontact::insertGetId($data);

        if ($dataid) {
            return $this->sendResponse(
                "Success",
                "workshop Branch contact created.",
                200
            );
        } else {
            return $this->sendError("Error occuered please try again later");
        }
    }


    public function workshopbranchupdte(Request $request, $id)
    {
        $TBL_ADMIN = "tbl_ms_admin";
        $fields = $request->validate([
            "name" => "required|string",
            "mobile_no" => "required|numeric|digits:10",
            "email" => "required|email",
            // "otp" => "",
            "created_by" => "required|numeric",
            "username" => "required|string",
            "workshop_branch_id" => "required|numeric",
        ]);

        $currentDate = Carbon::now();
        $data = [
            "name" => $fields["name"],
            "mobile_no" => $fields["mobile_no"],
            "email" => $fields["email"],
            "created_by" => $fields["created_by"],
            "username" => $fields["username"],
            "workshop_branch_id" => $fields["workshop_branch_id"],
            "updated_at" => $currentDate,
        ];
        $dataid = Msbranchcontact::where("id", $id)->update($data);
        if ($dataid) {
            return $this->sendResponse(
                "Success",
                "workshop Branch contact updated.",
                200
            );
        } else {
            return $this->sendError("Error occuered please try again later");
        }
    }

    public function workshopbranchdelete(Request $request, $id)
    {
        $dataid = Msbranchcontact::where("id", $id)->delete();
        if ($dataid) {
            return $this->sendResponse(
                "Success",
                "workshop Branch deleted.",
                200
            );
        } else {
            return $this->sendError("Error occuered please try again later");
        }
    }

    public function workshopbranchshow(Request $request, $id)
    {
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
                "workshop_branch_id" => $sop_master->workshop_branch_id,
                "created_at" => $sop_master->created_at->format("Y-m-d H:i:s"),
                "updated_at" => $sop_master->updated_at
                    ? $sop_master->updated_at->format("Y-m-d H:i:s")
                    : "",
            ];
            $i++;
        }
        return $this->sendResponse($data, "Workshop Branch Fetched", 200);
    }

    public function workshopbranchshowbyid(Request $request)
    {
        $workShopArr = json_decode($request->input('id'), TRUE);
        $Workshop = Msbranchcontact::whereIn("workshop_branch_id", $workShopArr)->get();
        $data = [];
        foreach ($Workshop as $key => $workshop) {
            $data[$workshop->workshop_branch_id][] = [
                "id" => $workshop->id,
                "name" => $workshop->name,
                "mobile_no" => $workshop->mobile_no,
                "email" => $workshop->email,
                "otp" => $workshop->otp,
                "created_by" => $workshop->created_by,
                "username" => $workshop->username,
                "workshop_branch_id" => $workshop->workshop_branch_id,
                "created_at" => $workshop->created_at->format("Y-m-d H:i:s"),
                "updated_at" => $workshop->updated_at ? $workshop->updated_at->format("Y-m-d H:i:s") : "",
            ];

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
}
