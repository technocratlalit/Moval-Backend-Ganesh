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
        Schema::create('tbl_ms_assessment_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inspection_id');
            $table->decimal('totalMetalNonIMT', 10, 2)->nullable();
            $table->decimal('totalRubberNonIMT', 10, 2)->nullable();
            $table->decimal('totalGlass', 10, 2)->nullable();
            $table->decimal('totalFiber', 10, 2)->nullable();
            $table->decimal('totalMetalIMT', 10, 2)->nullable();
            $table->decimal('totalRubberIMT', 10, 2)->nullable();
            $table->decimal('depMetalNonIMT', 10, 2)->nullable();
            $table->decimal('depRubberNonIMT', 10, 2)->nullable();
            $table->decimal('depGlass', 10, 2)->nullable();
            $table->decimal('depFiber', 10, 2)->nullable();
            $table->decimal('DepMetalIMT', 10, 2)->nullable();
            $table->decimal('DepRubberIMT', 10, 2)->nullable();
            $table->decimal('gstAmtMetal', 10, 2)->nullable();
            $table->decimal('gstAmtRubber', 10, 2)->nullable();
            $table->decimal('gstAmtGlass', 10, 2)->nullable();
            $table->decimal('gstAmtIMTMetal', 10, 2)->nullable();
            $table->decimal('gstAmtIMTRubber', 10, 2)->nullable();
            $table->decimal('totallabour', 10, 2)->nullable();
            $table->decimal('totalPainting', 10, 2)->nullable();
            $table->decimal('totalPaintingIMT', 10, 2)->nullable();
            $table->decimal('depAmtPainting', 10, 2)->nullable();
            $table->decimal('depAmtPaintingIMT', 10, 2)->nullable();
            $table->decimal('gstlabour', 10, 2)->nullable();
            $table->decimal('gstPainting', 10, 2)->nullable();
            $table->decimal('gstPaintingIMT', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_ms_assessment_reports');
    }
};
