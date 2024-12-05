<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblVehicleClassTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_vehicle_class', function (Blueprint $table) {

		$table->id();
        $table->unsignedBigInteger('created_by_id'); 
        $table->foreign('created_by_id')->references('id')->on('tbl_admin');	
		// $table->integer('created_by_id',11);
		$table->string('name',100);
		$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

		$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));


        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_vehicle_class');
    }
}