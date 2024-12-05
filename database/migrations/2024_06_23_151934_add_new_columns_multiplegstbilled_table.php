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
        Schema::table('tbl_ms_tax_dep_settings', function (Blueprint $table) {
            $table->integer('GSTBilledPartPer')->default(0)->after('GstonAssessedLab');
            $table->tinyInteger('MultipleGSTonBilled')->default(0)->comment('0 - No,1 - Yes')->after('MultipleGSTonLab');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_ms_tax_dep_settings', function (Blueprint $table) {
            $table->dropColumn('GSTBilledPartPer');
            $table->dropColumn('MultipleGSTonBilled');
        });
    }
};
