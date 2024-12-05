<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsAdminTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_ms_admin', function (Blueprint $table) {

				$table->id();
				$table->unsignedBigInteger('parent_id')->nullable();
				$table->string('name',250);
				$table->string('email',50);
				$table->string('password',250);
				$table->text('address');
				$table->enum('is_set_password',['yes','no'])->default('no');
				$table->string('mobile_no',20);
				$table->string('otp_sent',10)->nullable();
				$table->datetime('expiry_time')->nullable();
				$table->enum('can_download_report',['0','1'])->default('1');
				$table->string('authorized_person_name',250)->nullable();
				$table->string('letter_head_img',250)->nullable();
				$table->string('letter_footer_img',250)->nullable();
				$table->string('signature_img',250)->nullable();
				$table->string('reference_no_prefix',10)->nullable();
				$table->integer('report_no_start_from')->default('1');
				$table->string('designation',250)->nullable();
				$table->string('membership_no',50)->nullable();
				$table->float('each_report_cost')->nullable();
				$table->tinyInteger('number_of_photograph')->nullable();
				$table->smallInteger('duraton_delete_photo')->default('1');
				$table->date('billing_start_from')->nullable();
				$table->date('next_billing_date')->nullable();
				$table->enum('status',['0','1','2'])->default('1');
				$table->text('role')->nullable(); 
				$table->unsignedBigInteger('super_admin_id')->default(1); 
				$table->foreign('super_admin_id')->references('id')->on('tbl_ms_super_admin');
				$table->unsignedBigInteger('branch_admin_id')->default(0)->comment('Tell if Sub admin have any Main admin');
				$table->softDeletes();
				$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
				$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));


        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_ms_admin');
    }
}