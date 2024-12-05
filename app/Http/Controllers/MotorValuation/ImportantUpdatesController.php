<?php

namespace App\Http\Controllers\MotorValuation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use App\Models\ImportantUpdates;
use App\Http\Resources\important_updates\ImportantUpdateResource;
use App\Http\Resources\important_updates\ImportantUpdateResourceCollection;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\BaseController;
use App\Jobs\SendMail;
use App\Models\Admin_ms;
class ImportantUpdatesController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $TBL_IMPORTANT_UPDATES      = config('global.tbl_important_updates');
        $loggedInAdmin              = Auth::user()->id;

        if($request->user()->tokenCan('type:employee')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }

        $allImportantUpdates = ImportantUpdates::select([$TBL_IMPORTANT_UPDATES.'.id',$TBL_IMPORTANT_UPDATES.'.message',$TBL_IMPORTANT_UPDATES.'.created_at'])->orderBy('created_at', 'desc');

        if(!$request->user()->tokenCan('type:super_admin')) {

            $allImportantUpdates =  $allImportantUpdates->where(function($query) use ($TBL_IMPORTANT_UPDATES,$loggedInAdmin) {
                $query->orWhere($TBL_IMPORTANT_UPDATES.'.to_admin_ids','LIKE','%'.$loggedInAdmin.',%')
                ->orWhere($TBL_IMPORTANT_UPDATES.'.to_admin_ids','LIKE','%,'.$loggedInAdmin.'%')
                ->orWhere($TBL_IMPORTANT_UPDATES.'.to_admin_ids','LIKE','%,'.$loggedInAdmin.',%');
            });


            $allImportantUpdatesForMarkSeen = ImportantUpdates::select([$TBL_IMPORTANT_UPDATES.'.id',$TBL_IMPORTANT_UPDATES.'.seen_admin_ids']);

            $allImportantUpdatesForMarkSeen =  $allImportantUpdatesForMarkSeen->where(function($query) use ($TBL_IMPORTANT_UPDATES,$loggedInAdmin) {
                $query->orWhere($TBL_IMPORTANT_UPDATES.'.to_admin_ids','LIKE','%'.$loggedInAdmin.',%')
                ->orWhere($TBL_IMPORTANT_UPDATES.'.to_admin_ids','LIKE','%,'.$loggedInAdmin.'%')
                ->orWhere($TBL_IMPORTANT_UPDATES.'.to_admin_ids','LIKE','%,'.$loggedInAdmin.',%');
            });

            $allImportantUpdatesForMarkSeen =  $allImportantUpdatesForMarkSeen->where($TBL_IMPORTANT_UPDATES.'.seen_admin_ids','NOT LIKE','%'.$loggedInAdmin.',%');
            $allImportantUpdatesForMarkSeen =  $allImportantUpdatesForMarkSeen->where($TBL_IMPORTANT_UPDATES.'.seen_admin_ids','NOT LIKE','%,'.$loggedInAdmin.',%');
            $allImportantUpdatesForMarkSeen =  $allImportantUpdatesForMarkSeen->where($TBL_IMPORTANT_UPDATES.'.seen_admin_ids','NOT LIKE','%,'.$loggedInAdmin.'%')->get();

            foreach($allImportantUpdatesForMarkSeen as $importantUpdatesObj) {
                if ($importantUpdatesObj->seen_admin_ids != ""){
                    $importantUpdatesObj->seen_admin_ids = $importantUpdatesObj->seen_admin_ids .",".$loggedInAdmin.",";
                }else{
                    $importantUpdatesObj->seen_admin_ids = $loggedInAdmin.",";
                }
                $importantUpdatesObj->update();
            }

        }

        $searchKeyword = "";
        if ($request->exists('search_keyword')) {
            $searchKeyword = $request->input('search_keyword');
            if ($searchKeyword != '') {
                $allImportantUpdates =  $allImportantUpdates->where(function($query) use ($TBL_IMPORTANT_UPDATES,$searchKeyword) {
                    $query->orWhere($TBL_IMPORTANT_UPDATES.'.message','LIKE','%'.$searchKeyword.'%');
                });
            }
        }

        $allImportantUpdates = $request->exists('all')
                ? ImportantUpdateResource::collection($allImportantUpdates->get())
                : new ImportantUpdateResourceCollection($allImportantUpdates->paginate(20));
        return $this->sendResponse($allImportantUpdates, 'Posts fetched.');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->user()->tokenCan('type:super_admin')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }
        $requestData   =   $request->json()->all();
        //$data = json_decode($request->payload, true);
        $rules = [
            'message' => 'required|string',
            'send_updates_to' => 'required'
        ];

        $validator = Validator::make($requestData, $rules);
        if($validator->fails()){
            return $this->sendLaravelFormatError("The given data was invalid",$validator->errors());
        }

        $message            =   $requestData["message"];
        $arrSendUpdatesTo   =   $requestData["send_updates_to"];
        $arrEmails          =   array();

        $arrAdminIds = [];
        $arrSubAdminByAdmin = [];
        foreach($arrSendUpdatesTo as $dataObj) {
            $adminId = $dataObj["admin_id"];
            $arrAdminIds[] = $adminId;
            $arrSubAdminByAdmin[$adminId] = $dataObj["send_to_sub_admin"];
            //$sendToSubAdmin = $dataObj["send_to_sub_admin"];
        }
        sort($arrAdminIds);
        if($request->input('platform')  == 1 ){
             $allAdmins  =  Admin::whereIn('id', $arrAdminIds)->where('status','!=', '2')->where('parent_id','=', '0')->orderBy('id', 'asc')->get();
        }else{
              $allAdmins =  Admin_ms::whereIn('id', $arrAdminIds)->where('status','!=', '2')->where('parent_id','=', '0')->orderBy('id', 'asc')->get();
        }

        if ($allAdmins->count() != count($arrAdminIds)) {
            return $this->sendError('Invalid request');
        }

        foreach($allAdmins as $adminObj) {
            //echo $adminObj->id."=======".$arrAdminIds[$index]."====".$arrSubAdminByAdmin[$adminObj->id];
            $arrEmails[] = $adminObj->email;
            if ($arrSubAdminByAdmin[$adminObj->id] == 1){
                $allSubAdmins      =   Admin::where('parent_id', '=', $adminObj->id)->where('status','!=', '2')->orderBy('id', 'asc')->get();
                foreach($allSubAdmins as $subAdminObj) {
                    $arrAdminIds[] =  $subAdminObj->id;
                    $arrEmails[] = $subAdminObj->email;
                }
            }
            //$index = $index + 1;
        }

        //print_r($arrAdminIds);

        $strAllAdminId = implode(',', $arrAdminIds);

        if (count($arrAdminIds) == 1){
            $strAllAdminId.= $strAllAdminId.",";
        }

        $arrFieldsToBeAdd = [
            'message' => $message,
            'to_admin_ids' => $strAllAdminId,
            'seen_admin_ids' => ''
        ];

        $importantUpdates = ImportantUpdates::create($arrFieldsToBeAdd);

        $subject = "Important updates from the Moval Platform";



        // Mail Send To Client
        $maildata = [
            'todayDate' => date("F j, Y"),
            'subject' => $subject,
            'from_name' => config('app.from_name'),
            'message' => $message,
            'contactEmail' => config('app.technical_support_email'),
            'mail_template' => "emails.important-updates"
        ];

        foreach ($arrEmails as $recipient) {
            SendMail::dispatch($recipient, $maildata)->onQueue('send-email')->onConnection('database');
            // Mail::to($recipient)->send(new WelcomeEmail($maildata));
        }

        return $this->sendResponse(new ImportantUpdateResource($importantUpdates),"success",201);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function destroy(Request $request, $id)
    {
        if(!$request->user()->tokenCan('type:super_admin')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }

        $importantUpdates = ImportantUpdates::where('id','=', $id)->first();
        if (is_null($importantUpdates)) {
            return $this->sendError('Record does not exist.');
        }

        $importantUpdates->delete();

        return $this->sendResponse([], 'Record deleted successfully.',200);

    }
}
