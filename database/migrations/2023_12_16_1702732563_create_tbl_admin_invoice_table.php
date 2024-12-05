<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblAdminInvoiceTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_admin_invoice', function (Blueprint $table) {

		$table->id();
		$table->unsignedBigInteger('admin_id')->nullable(); 
		$table->foreign('admin_id')->references('id')->on('tbl_admin');
		$table->date('invoice_date');
		$table->integer('number_of_reports')->nullable();
		$table->float('report_cost');
		$table->float('bill_amount');
		$table->float('paid_amount')->default('0');
		$table->enum('payment_status',['pending','completed'])->default('pending');
		$table->datetime('payment_date')->nullable();
		$table->enum('payment_mode',['Cash','RazorPay'])->default('RazorPay');
		$table->date('last_date_of_payment')->nullable();
		$table->bigInteger('payment_link_tracking_id')->nullable();
		$table->datetime('payment_link_send_date')->default(DB::raw('CURRENT_TIMESTAMP'));
		$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

		$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));


        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_admin_invoice');
    }
}