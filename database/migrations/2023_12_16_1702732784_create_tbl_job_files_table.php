<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblJobFilesTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_job_files', function (Blueprint $table) {

		$table->id();
		// $table->bigInteger('job_id',20);
        $table->unsignedBigInteger('job_id')->nullable(); 
		$table->foreign('job_id')->references('id')->on('tbl_job');


		$table->enum('type',['Chassis Number','Front View','Rear View','Right Side','Left Side','Odometer','Other','Video']);
		$table->string('name',250);
		$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

		$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));


        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_job_files');
    }
}