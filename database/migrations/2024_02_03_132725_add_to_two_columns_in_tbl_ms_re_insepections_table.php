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
        Schema::table('tbl_ms_re_insepections', function (Blueprint $table) {
            $table->tinyInteger('list_allow_status')->default(0)->comment('0 - No,1 - Yes')->after('payment_by');
            $table->tinyInteger('remarks_status')->default(0)->comment('0 - No,1 - Yes')->after('list_allow_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_ms_re_insepections', function (Blueprint $table) {
            $table->dropColumn('list_allow_status');
            $table->dropColumn('remarks_status');
        });
    }
};
