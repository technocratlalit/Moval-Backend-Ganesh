<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_ms_inspection_links', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('inspection_id')->nullable(); 
			$table->foreign('inspection_id')->references('id')->on('tbl_ms_inspection_details');
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_ms_inspection_links');
    }
};
