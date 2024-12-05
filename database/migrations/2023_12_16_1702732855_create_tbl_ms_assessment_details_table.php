<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsAssessmentDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_ms_assessment_details', function (Blueprint $table) {

                $table->id();
                $table->unsignedBigInteger('inspection_id')->nullable();
			    $table->foreign('inspection_id')->references('id')->on('tbl_ms_inspection_details');
                //$table->unsignedBigInteger('job_id')->nullable(); 
                // $table->foreign('job_id')->references('id')->on('tbl_ms_inspection_details')->onDelete('SET NULL');
                $table->text('alldetails')->nullable();
                $table->unsignedBigInteger('created_by')->nullable(); 
                $table->unsignedBigInteger('updated_by')->nullable(); 
                $table->softDeletes();
                $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_ms_assessment_details');
    }
}