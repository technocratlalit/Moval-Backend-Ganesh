<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblJobPaymentHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_job_payment_history', function (Blueprint $table) {

		$table->id();
		// $table->bigInteger('parent_admin_id',20)->default('1');

		$table->unsignedBigInteger('job_id')->nullable(); 
		$table->foreign('job_id')->references('id')->on('tbl_job');

		$table->unsignedBigInteger('parent_admin_id')->default(1); 
		$table->foreign('parent_admin_id')->references('id')->on('tbl_admin');

		$table->unsignedBigInteger('client_id'); // foreign key of tbl_clients
		$table->foreign('client_id')->references('id')->on('tbl_clients');

		$table->unsignedBigInteger('client_branch_id'); // foreign key of tbl_client_branches  
		$table->foreign('client_branch_id')->references('id')->on('tbl_client_branches');




		// $table->bigInteger('job_id',20)->nullable();

		// $table->bigInteger('client_id',20)->nullable();
		// $table->bigInteger('branch_id',20)->nullable();
		$table->string('order_id',500)->nullable();
		$table->string('payment_link_reference_id',500)->nullable();
		$table->string('payment_id',500)->nullable();
		$table->string('payment_link_id',500)->nullable();
		$table->string('payment_link',500)->nullable();
		$table->text('callback_signature')->nullable();
		$table->string('link_status',250)->nullable();
		$table->enum('payment_status',['pending','completed','rejected','expired'])->default('pending');
		$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

		$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));


        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_job_payment_history');
    }
}