<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblJobTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_job', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_reg_no', 20);
            $table->string('owner_name', 250);
            $table->text('address');
            $table->text('inspection_place')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('contact_mobile_no', 20); 

            $table->unsignedBigInteger('requested_by')->comment('Client');

            $table->unsignedBigInteger('parent_admin_id')->default(1); 
		    $table->foreign('parent_admin_id')->references('id')->on('tbl_admin');

            $table->unsignedBigInteger('branch_id')->nullable(); // foreign key of tbl_client_branches  
			$table->foreign('branch_id')->references('id')->on('tbl_client_branches');

            $table->unsignedBigInteger('contact_person_id'); // foreign key of tbl_client_branches  
			$table->foreign('contact_person_id')->references('id')->on('tbl_branch_contact_person');


            // $table->bigInteger('branch_id')->nullable();
            // $table->bigInteger('contact_person_id');

            $table->unsignedBigInteger('approved_by')->comment('Connected To tbl_admin'); 
		    $table->foreign('approved_by')->references('id')->on('tbl_admin');

            $table->unsignedBigInteger('rejected_by')->comment('Connected To tbl_admin'); 
		    $table->foreign('rejected_by')->references('id')->on('tbl_admin');

            $table->unsignedBigInteger('submitted_by')->comment('Connected To tbl_admin'); 
		    $table->foreign('submitted_by')->references('id')->on('tbl_admin');


            $table->unsignedBigInteger('completed_by')->comment('Connected To tbl_admin'); 
		    $table->foreign('completed_by')->references('id')->on('tbl_admin');


            $table->unsignedBigInteger('created_by_id')->comment('Connected To tbl_admin'); 
		    $table->foreign('created_by_id')->references('id')->on('tbl_admin');

            $table->unsignedBigInteger('mail_send_by')->comment('Connected To tbl_admin'); 
		    $table->foreign('mail_send_by')->references('id')->on('tbl_admin');
 
       

            $table->datetime('assigned_date')->nullable();
            $table->datetime('submission_date')->nullable();
            $table->datetime('approval_date')->nullable();
            $table->datetime('rejected_date')->nullable(); 
            $table->datetime('completed_at')->nullable();
            $table->mediumText('admin_remark')->nullable();
            $table->enum('created_by', ['Admin', 'Employee'])->default('Admin');
            
            $table->enum('job_status', ['created', 'pending', 'submitted', 'approved', 'rejected', 'finalized'])->default('pending');
            $table->string('vehicle_owner_signature', 255)->nullable();
            $table->enum('mail_sent_to_client', ['1', '0'])->default('0');
            $table->enum('mail_sent_to_employee', ['1', '0'])->default('0');
            $table->datetime('mail_sent_date')->nullable();
            
            $table->tinyInteger('mail_sent_cnt');
            $table->date('photos_delete_date')->nullable();

            $table->integer('job_report_no')->default(1); // unknown
            $table->integer('payment_link_tracking_id')->nullable(); // unknown

            $table->datetime('payment_link_send_date')->nullable();
            $table->enum('payment_status', ['pending', 'completed'])->default('pending');
            $table->enum('is_offline', ['yes', 'no'])->default('no');
            $table->enum('is_outside_job', ['0', '1'])->default('0');
            $table->enum('status', ['0', '1', '2'])->default('1')->comment('0 -> Inactive, 1 -> Active, 2 -> Deleted');
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_job');
    }
}
