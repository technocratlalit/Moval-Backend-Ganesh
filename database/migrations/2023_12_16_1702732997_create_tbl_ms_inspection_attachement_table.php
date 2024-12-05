<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsInspectionAttachementTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_ms_inspection_attachement', function (Blueprint $table) {

		$table->id();
		$table->unsignedBigInteger('inspection_id')->nullable(); 
		$table->foreign('inspection_id')->references('id')->on('tbl_ms_inspection_details');

			$table->boolean('affidavit')->default(false);
            $table->boolean('bill_invoice')->default(false);
            $table->boolean('copy_of_fire')->default(false);
            $table->boolean('copy_of_permit')->default(false);
            $table->boolean('copy_traffic')->default(false);
            $table->boolean('estimate_copy')->default(false);
            $table->boolean('report_in_duplicate')->default(false);
            $table->boolean('copy_of_fitness')->default(false);
            $table->boolean('copy_of_receipt')->default(false);
            $table->boolean('claim_form')->default(false);
            $table->boolean('copy_of_load_challan')->default(false);
            $table->boolean('copy_of_RC')->default(false);
            $table->boolean('insured_discharge_voucher')->default(false);
            $table->boolean('intimation_letter')->default(false);
            $table->boolean('survey_fee_bill')->default(false);
            $table->boolean('letter_by_insured')->default(false);
            $table->boolean('copy_of_DL')->default(false);
            $table->boolean('policy_note')->default(false);
            $table->boolean('generate_photosheet')->default(false);
            $table->boolean('medical_papers')->default(false);
            $table->boolean('dealer_inv')->default(false);
            $table->boolean('police_report')->default(false);
            $table->boolean('photographs')->default(false);
            $table->boolean('satisfaction_voucher')->default(false);
            $table->boolean('supporting_bills')->default(false);
            $table->boolean('towing_charge_slip')->default(false);
			$table->json('custom_attachments')->nullable();
  
		// $table->unsignedBigInteger('affidevit')->nullable();
		// $table->unsignedBigInteger('copy_of_RR')->nullable();
		// $table->unsignedBigInteger('copy_of_permit')->nullable();
		// $table->unsignedBigInteger('copy_of_traffic_challan')->nullable();
		// $table->unsignedBigInteger('estimate_copy')->nullable();
		// $table->unsignedBigInteger('report_in_duplicate')->nullable();
		// $table->unsignedBigInteger('invoice')->nullable();
		// $table->unsignedBigInteger('copy_of_fitness')->nullable();
		// $table->unsignedBigInteger('copy_of_receipt')->nullable();
		// $table->unsignedBigInteger('copy_of_letter_to_insured')->nullable();
		// $table->unsignedBigInteger('survey_fee_bill')->nullable();
		// $table->unsignedBigInteger('claim_form')->nullable();
		// $table->unsignedBigInteger('copy_of_load_challan')->nullable();
		// $table->unsignedBigInteger('copy_of_RC')->nullable();
		// $table->unsignedBigInteger('discharge_voucher')->nullable();
		// $table->unsignedBigInteger('letter_of_insured')->nullable();
		// $table->unsignedBigInteger('satisfaction_voucher')->nullable();
		// $table->unsignedBigInteger('copy_of_DL')->nullable();
		// $table->unsignedBigInteger('copy_of_policy')->nullable();
		// $table->unsignedBigInteger('generate_photosheet')->nullable();
		// $table->unsignedBigInteger('medical_papers')->nullable();
		// $table->unsignedBigInteger('supporting_bills')->nullable();
		// $table->unsignedBigInteger('copy_dealer_inv')->nullable();
		// $table->unsignedBigInteger('copy_of_police_report')->nullable();
		$table->text('photograps')->nullable();
		
		
		$table->softDeletes();
		$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));


        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_ms_inspection_attachement');
    }
}