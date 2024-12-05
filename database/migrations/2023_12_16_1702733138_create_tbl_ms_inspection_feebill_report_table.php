<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsInspectionFeebillReportTable extends Migration
{
	public function up()
	{
		Schema::create('tbl_ms_inspection_feebill_report', function (Blueprint $table) {

			$table->id();
			$table->unsignedBigInteger('inspection_id')->nullable(); 
			$table->foreign('inspection_id')->references('id')->on('tbl_ms_inspection_details');
			$table->string('bill_no')->nullable();
			$table->date('bill_date')->nullable();
			$table->text('issued_to')->nullable();
			$table->text('payment_by')->nullable();
			$table->text('surveyFee')->nullable();
			$table->text('conveyanceFee')->nullable();
			$table->text('vehiclePhotographs')->nullable();
			$table->text('miscellaneous')->nullable();
			$table->text('survey_fee_total')->nullable();
			$table->text('conveyance_fee_total')->nullable();
			$table->text('photographs_amount_total')->nullable();
			$table->text('miscellaneous_amount_total')->nullable();
			$table->text('amount_before_tax')->nullable();
			$table->text('cash_receipted')->nullable();
			$table->text('cgst_percentage')->nullable();
			$table->text('sgst_percentage')->nullable();
			$table->text('igst_percentage')->nullable();
			$table->text('gst_amount')->nullable();
			$table->text('amount_after_tax')->nullable();
			$table->text('bank_details')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->foreign('bank_id')->references('id')->on('tbl_ms_bank_details');
            $table->text('bank_code')->nullable();
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
			$table->softDeletes();
			$table->unsignedBigInteger('updated_by')->nullable();
			$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}

	public function down()
	{
		Schema::dropIfExists('tbl_ms_inspection_feebill_report');
	}
}
