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
        Schema::table('tbl_ms_inspection_feebill_report', function (Blueprint $table) {
            $table->text('TotalAmountWithoutGST')->nullable()->after('amount_after_tax');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_ms_inspection_feebill_report', function (Blueprint $table) {
            $table->dropColumn('TotalAmountWithoutGST');
        });
    }
};