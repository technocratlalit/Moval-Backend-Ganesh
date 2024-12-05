<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblVehicleVarientTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_vehicle_varient', function (Blueprint $table) {


            $table->id();
            $table->unsignedBigInteger('created_by_id'); 
            $table->foreign('created_by_id')->references('id')->on('tbl_admin');
            $table->string('name', 100);
            $table->tinyInteger('seats');
            $table->unsignedBigInteger('maker_id')->nullable();
            $table->foreign('maker_id')->references('id')->on('tbl_vehicle_makers');
            // $table->integer('maker_id',11);
            $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_vehicle_varient');
    }
}
