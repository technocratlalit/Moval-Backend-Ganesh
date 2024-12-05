<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsSopMasterTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_ms_sop_master', function (Blueprint $table) {

            $table->id();
            $table->text('sop_name');
            $table->unsignedBigInteger('admin_branch_id')->nullable(); 
            $table->foreign('admin_branch_id')->references('id')->on('tbl_ms_admin_branch');
            $table->unsignedBigInteger('super_admin_id')->default(1); 
            $table->foreign('super_admin_id')->references('id')->on('tbl_ms_super_admin');
            $table->unsignedBigInteger('admin_id')->default(1); 
            $table->foreign('admin_id')->references('id')->on('tbl_ms_admin');
            $table->unsignedBigInteger('can_record_video')->nullable();
            $table->longText('vehichle_images_field_label')->nullable();
            $table->longText('document_image_field_label')->nullable();
            $table->softDeletes();
            $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_ms_sop_master');
    }
}