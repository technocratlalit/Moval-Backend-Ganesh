<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsInspectionAccidentDetailTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_ms_inspection_accident_detail', function (Blueprint $table) {

			$table->id();
			$table->unsignedBigInteger('inspection_id')->nullable(); 
			$table->foreign('inspection_id')->references('id')->on('tbl_ms_inspection_details');
			$table->text('place_accident')->nullable();
			$table->text('date_time_accident')->nullable();
			$table->text('date_of_appointment')->nullable();
			$table->text('place_survey')->nullable();
			$table->unsignedBigInteger('workshop_name')->nullable();
			$table->string('Survey_Date_time')->nullable();
			$table->text('date_of_under_repair_visit')->nullable();
			$table->text('estimate_no')->nullable();
			$table->text('date_of_estimate')->nullable();
			$table->text('insured_rep_attending_survey')->nullable();
			$table->text('vehicle_left_unattended')->nullable();
			$table->text('vehicle_left_unattended_desc')->nullable();
			$table->text('accident_reported_to_police')->nullable();
			$table->text('panchnama')->nullable();
			$table->text('third_party_injury')->nullable();
			$table->text('injury_to_driver')->nullable();
			$table->text('previous_claim_details')->nullable();
			$table->text('spot_survey_by')->nullable();
			$table->text('spot_survey_date')->nullable();
			$table->text('passenger_detail')->nullable();
			$table->text('RC')->nullable();
			$table->text('DL')->nullable();
			$table->text('chassis_no')->nullable();
			$table->text('fitness')->nullable();
			$table->text('load_chalan')->nullable();
			$table->text('permit')->nullable();
			$table->text('engine_no')->nullable();
			$table->text('panchnama_description')->nullable();
			$table->text('fir_description')->nullable(); 
			$table->text('carrying_capacity')->nullable();
			$table->text('registered_laden_weight')->nullable();
			$table->text('unladen_weight')->nullable();
			$table->text('overloading_accident')->nullable();
			$table->text('accident_additional_information')->nullable();
			$table->text('survey_additional_information')->nullable();
			$table->text('Date_of_Under_repair_Visits1')->nullable();
			$table->text('Date_of_Under_repair_Visits2')->nullable();
			$table->text('Date_of_Under_repair_Text')->nullable();
			$table->softDeletes();
			$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_ms_inspection_accident_detail');
    }
}