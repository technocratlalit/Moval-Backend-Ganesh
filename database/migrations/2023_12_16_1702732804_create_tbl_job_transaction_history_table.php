<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblJobTransactionHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_job_transaction_history', function (Blueprint $table) {

		$table->id();
        $table->unsignedBigInteger('job_id')->nullable(); 
		$table->foreign('job_id')->references('id')->on('tbl_job');
	 
		$table->enum('status',['created','pending','submitted','approved','rejected','finalized'])->default('created');
		$table->enum('user_type',['Admin','Employee']);
		// $table->bigInteger('user_id',20);
        $table->unsignedBigInteger('user_id')->default(1); 
        $table->foreign('user_id')->references('id')->on('tbl_admin');
		$table->datetime('on_date_time')->default(DB::raw('CURRENT_TIMESTAMP'));

        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_job_transaction_history');
    }
}