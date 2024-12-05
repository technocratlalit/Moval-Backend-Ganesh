<?php

namespace App\Http\Controllers\MotorValuation;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\AppSetting;

class AppSettingController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$action){

        if($request->user()->tokenCan('type:employee')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }
        $validator = Validator::make(
            [
              'action' => $action,
            ],
            [
              'action' => ['required','in:razorpay'],
            ],
        );
        if($validator->fails()){
            return $this->sendLaravelFormatError("The given data was invalid",$validator->errors());
        }
        if ($action == 'razorpay'){
            return $this->getRazonPaymentSettings();
        }
    }

    private function getRazonPaymentSettings(){
        $settings = AppSetting::where('type','=', 'razorpay')->first();
        if (is_null($settings)) {
            return $this->sendError('Something went wrong');
        }

        $host               = request()->getSchemeAndHttpHost();
        $CALL_BACK_URL      = $host.config('global.razor_pay_admin_payment_call_back');

        $settingsData["call_back_url"]      =   $CALL_BACK_URL;
        $settingsData["payment_settings"]   =   [];
        if (!is_null($settings)) {
            $settingsData["payment_settings"] = json_decode($settings->message);
        }

        return $this->sendResponse($settingsData, 'Data fetched',200);
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
        $fields = $request->validate([
            'type' => 'required|string|in:razorpay',
            'settings' => 'required|string',
        ]);

        $settings = AppSetting::where('type','=', $fields['type'])->first();
        if (is_null($settings)) {
            return $this->sendError('Something went wrong');
        }
        $settings->type = $fields['type'];
        $settings->message = $fields['settings'];
        $settings->update();

        return $this->sendResponse([], 'Settings updated.',200);
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
    public function destroy($id)
    {
        //
    }
}
