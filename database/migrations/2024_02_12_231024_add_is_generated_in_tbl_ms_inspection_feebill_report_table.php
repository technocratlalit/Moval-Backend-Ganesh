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
            $table->tinyInteger('is_generated')->default(0)->comment('0 - No,1 - Yes')->after('generate_bill_no');
            $table->unsignedBigInteger('int_bill_no')->after('bill_no')->nullable();  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_ms_inspection_feebill_report', function (Blueprint $table) {
            $table->dropColumn('is_generated');
            $table->dropColumn('int_bill_no');
        });
    }
};
