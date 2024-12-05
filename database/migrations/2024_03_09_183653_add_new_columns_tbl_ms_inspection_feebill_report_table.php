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
            $table->tinyInteger('gst_on_survey_fee')->default(0)->comment('0 - No,1 - Yes')->after('payment_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_ms_inspection_feebill_report', function (Blueprint $table) {
            $table->dropColumn('gst_on_survey_fee');
        });
    }
};
