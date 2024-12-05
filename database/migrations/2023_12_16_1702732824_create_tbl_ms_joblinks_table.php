<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsJoblinksTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_ms_joblinks', function (Blueprint $table) {

			$table->id();
			$table->unsignedBigInteger('job_id')->nullable(); 
			$table->foreign('job_id')->references('id')->on('tbl_ms_job');
			$table->text('encoded_job_id')->nullable();
			$table->datetime('link_createdate')->nullable();
			$table->datetime('link_expdate')->nullable();
			$table->unsignedBigInteger('status')->nullable()->comment('0 pending , 1 submitted');
			$table->unsignedBigInteger('link_createdby')->nullable();
			$table->datetime('submitted_date')->nullable();
			$table->softDeletes();
			$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_ms_joblinks');
    }
}