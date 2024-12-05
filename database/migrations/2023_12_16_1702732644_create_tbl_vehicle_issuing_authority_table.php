<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblVehicleIssuingAuthorityTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_vehicle_issuing_authority', function (Blueprint $table) {

		$table->id();
        $table->unsignedBigInteger('created_by_id'); 
        $table->foreign('created_by_id')->references('id')->on('tbl_admin');	
		$table->string('name',250);
		$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

		$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

		// $table->integer('created_by_id',11);

        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_vehicle_issuing_authority');
    }
}