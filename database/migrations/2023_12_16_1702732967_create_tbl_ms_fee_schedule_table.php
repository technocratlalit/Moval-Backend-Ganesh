<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateTblMsFeeScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ms_fee_schedule', function (Blueprint $table) {
            $table->id();
			$table->text('cgst')->nullable();
			$table->text('sgst')->nullable();
			$table->text('igst')->nullable();
			$table->text('level1')->nullable();
			$table->text('level2')->nullable();
			$table->text('level3')->nullable();
			$table->text('level4')->nullable();
			$table->text('level5')->nullable();
			$table->text('level5_percent')->nullable();
			$table->text('spot_survey_fee')->nullable();
			$table->text('reinspection_fee')->nullable();
			$table->text('verification_fee')->nullable();
			$table->text('conveyance_a')->nullable();
			$table->text('conveyance_b')->nullable();
			$table->text('conveyance_c')->nullable();
			$table->text('city_category')->nullable();
			$table->unsignedBigInteger('created_by')->nullable(); 
			$table->unsignedBigInteger('modified_by')->nullable(); 
			$table->unsignedBigInteger('client_id');
			$table->foreign('client_id')->references('id')->on('tbl_ms_clients');
			$table->softDeletes();
			$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_ms_fee_schedule');
    }
}
