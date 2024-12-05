<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsInspectionVehicleDetailTable extends Migration
{
	public function up()
	{
		Schema::create('tbl_ms_inspection_vehicle_detail', function (Blueprint $table) {

			$table->id();
			$table->unsignedBigInteger('inspection_id')->nullable(); 
			$table->foreign('inspection_id')->references('id')->on('tbl_ms_inspection_details');
			$table->text('temp_registration_no')->nullable();
			$table->text('registration_no')->nullable();
			$table->text('issued_on')->nullable();
			$table->text('valid_to')->nullable();
			$table->text('rc_valid_to')->nullable();
			$table->text('date_of_purchase')->nullable();
			$table->text('date_of_registration')->nullable();
			$table->text('date_of_transfer')->nullable();
			$table->text('chassis_no')->nullable();
			$table->text('engine_no')->nullable();
			$table->text('vehicle_make')->nullable();
			$table->text('vehicle_variant')->nullable();
			$table->text('vehicle_model')->nullable();
			$table->text('vehicle_color')->nullable();
			$table->text('engine_capacity')->nullable();
			$table->text('body_type')->nullable();
			$table->text('odometer_reading')->nullable();
			$table->text('seating_capacity')->nullable();
			$table->text('anti_theft_fitted')->nullable();
			$table->text('fuel')->nullable();
			$table->text('fuel_kit')->nullable();
			$table->text('vehicle_type')->nullable();
			$table->text('vehicle_class')->nullable();
			$table->text('pre_accident_cond')->nullable();
			$table->text('tax_paid_from')->nullable();
			$table->text('tax_paid_to')->nullable();
			$table->text('fitness_number')->nullable();
			$table->text('fitness_valid_from')->nullable();
			$table->text('fitness_valid_to')->nullable();
			$table->text('permit_number')->nullable();
			$table->text('permit_valid_from')->nullable();
			$table->text('permit_valid_to')->nullable();
			$table->text('permit_type')->nullable();
			$table->text('accident_place')->nullable();
			$table->text('route')->nullable();
			$table->text('challan_no')->nullable();
			$table->text('authorization_no')->nullable();
			$table->text('authorization_valid_from')->nullable();
			$table->text('authorization_valid_to')->nullable();
			$table->text('carrying_capacity')->nullable();
			$table->text('registered_laden_weight')->nullable();
			$table->text('unladen_weight')->nullable();
			$table->text('cause_of_accident')->nullable();
			$table->text('driver_name')->nullable();
			$table->text('driver_dob')->nullable();
			$table->text('address')->nullable();
			$table->text('relation_with_insurer')->nullable();
			$table->text('dl_renewal_no')->nullable();
			$table->text('dl_no')->nullable();
			$table->text('issuing_authority')->nullable();
			$table->text('issuing_date')->nullable();
			$table->text('dl_valid_upto')->nullable();
			$table->text('nt_dl_valid_from')->nullable();
			$table->text('nt_dt_valid_to')->nullable();
			$table->text('type_of_dl')->nullable();
			$table->text('vehicle_allowed_to_drive')->nullable();
			$table->text('endorsement_detail')->nullable();
			$table->text('badge_no')->nullable();
			$table->text('additional_details')->nullable();
			$table->text('puc_certificate_no')->nullable();
			$table->text('puc_valid_from')->nullable();
			$table->text('puc_valid_to')->nullable();
			$table->text('issuing_date_upto')->nullable();
			$table->text('transfer_SrNo')->nullable();
			$table->text('anti_theft_type')->nullable();
			$table->text('engine_capacity_unit')->nullable();
			$table->text('tax_valid_from_text')->nullable();
			$table->softDeletes();
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	} 

	public function down()
	{
		Schema::dropIfExists('tbl_ms_inspection_vehicle_detail');
	}

	
}
