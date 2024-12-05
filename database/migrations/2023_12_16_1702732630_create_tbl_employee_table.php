<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblEmployeeTable extends Migration
{
	public function up()
	{
		Schema::create('tbl_employee', function (Blueprint $table) {

			$table->id();
			$table->string('user_id', 50);
			$table->enum('type', ['Normal', 'Guest'])->default('Normal');
			$table->string('password', 250);
			$table->string('name', 250);
			$table->string('email', 50);
			$table->string('mobile_no', 20);
			$table->text('address');
			$table->decimal('amount_per_job', 8, 2)->default('0.00');
			$table->text('firebase_token')->nullable();
			$table->string('device_id', 500)->nullable();
			$table->enum('status', ['0', '1', '2'])->default('1');
			$table->enum('is_set_password', ['yes', 'no'])->default('no');
			$table->enum('is_guest_employee', ['1', '0'])->default('0');
			$table->bigInteger('job_assigned_to_guest')->default('0');
			
			$table->string('otp_sent', 10)->nullable();
			$table->datetime('expiry_time')->nullable();

			$table->unsignedBigInteger('parent_admin_id')->default(1);
			$table->foreign('parent_admin_id')->references('id')->on('tbl_admin');

			$table->unsignedBigInteger('created_by')->default(1);
			$table->foreign('created_by')->references('id')->on('tbl_admin');

			$table->unsignedBigInteger('modified_by')->default(1);
			$table->foreign('modified_by')->references('id')->on('tbl_admin');

			$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

			$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

			$table->datetime('last_login_date')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}

	public function down()
	{
		Schema::dropIfExists('tbl_employee');
	}
}
