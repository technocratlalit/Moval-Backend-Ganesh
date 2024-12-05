<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblSuperAdminTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_super_admin', function (Blueprint $table) {

		$table->id();
		$table->string('name',250);
		$table->string('email',50);
		$table->string('password',250);
		$table->text('address');
		$table->string('mobile_no',20);
		$table->string('otp_sent',10)->nullable();
		$table->datetime('expiry_time')->nullable();
		$table->enum('status',['0','1','2'])->default('1');
		$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

		$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));


        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_super_admin');
    }
}