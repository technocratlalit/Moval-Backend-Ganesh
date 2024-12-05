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
        Schema::table('tbl_ms_assisment_calculations', function (Blueprint $table) {
            $table->decimal('total_labestAmtWithoutGST', 10, 2)->nullable()->after('total_labourAmtWithGst');
            $table->decimal('total_labassAmtWithoutGST', 10, 2)->nullable()->after('total_labestAmtWithoutGST');
            $table->decimal('total_paintingassAmtWithoutGST', 10, 2)->nullable()->after('total_labassAmtWithoutGST');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_ms_assisment_calculations', function (Blueprint $table) {
            $table->dropColumn('total_labestAmtWithoutGST');
            $table->dropColumn('total_labassAmtWithoutGST');
            $table->dropColumn('total_paintingassAmtWithoutGST');
        });
    }
};
