<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_ms_estimate_structures', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->string('structure_name')->nullable();
            $table->string('gst')->nullable();
            $table->string('imt_23')->nullable();
            $table->string('qe')->nullable();
            $table->string('qa')->nullable();
            $table->string('est_rate')->nullable();
            $table->string('ass_rate')->nullable();
            $table->string('ai_part_amt')->nullable();
            $table->string('est_amt')->nullable();
            $table->string('ass_amt')->nullable();
            $table->string('hsn_code')->nullable();
            $table->string('category')->nullable();
            $table->string('labour_type')->nullable();
            $table->string('est_lab')->nullable();
            $table->string('ass_lab')->nullable();
            $table->string('lab_ai_amt')->nullable();
            $table->string('painting_lab')->nullable();
            $table->string('sac')->nullable();
            $table->text('remarks')->nullable();
            $table->string('e_sr_no')->nullable();
            $table->string('b_sr_no')->nullable();
            $table->string('billed_part_amt')->nullable();
            $table->string('billed_lab_amt')->nullable();
            $table->string('payable_amt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_ms_estimate_structures');
    }
};
