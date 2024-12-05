<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblAdminPaymentHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_admin_payment_history', function (Blueprint $table) {

		$table->id();
		
		$table->unsignedBigInteger('admin_id'); 
		$table->foreign('admin_id')->references('id')->on('tbl_admin');
		$table->unsignedBigInteger('invoice_id'); 
		$table->foreign('invoice_id')->references('id')->on('tbl_admin_invoice');

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
        Schema::dropIfExists('tbl_admin_payment_history');
    }
}