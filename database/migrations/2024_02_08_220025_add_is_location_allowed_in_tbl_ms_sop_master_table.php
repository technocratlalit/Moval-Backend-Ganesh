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
        Schema::table('tbl_ms_sop_master', function (Blueprint $table) {
            $table->tinyInteger('is_location_allowed')->default(0)->comment('0 - No,1 - Yes')->after('can_record_video');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_ms_sop_master', function (Blueprint $table) {
            $table->dropColumn('is_location_allowed');
        });
    }
};
