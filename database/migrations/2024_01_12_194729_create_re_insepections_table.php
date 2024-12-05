<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_ms_re_insepections', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('inspection_id')->nullable(); 
			$table->foreign('inspection_id')->references('id')->on('tbl_ms_inspection_details');
            $table->unsignedBigInteger('admin_branch_id')->nullable();
            $table->foreign('admin_branch_id')->references('id')->on('tbl_ms_admin_branch');
			$table->string('submission_date')->nullable();
			$table->string('reinspection_date')->nullable(); 
			$table->string('place_reinspection')->nullable();
			$table->text('observation')->nullable();
			$table->text('allowed_parts')->nullable();
            $table->text('remarks')->nullable();
            $table->text('payment_by')->nullable();
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
        Schema::dropIfExists('tbl_ms_re_insepections');
    }
};
