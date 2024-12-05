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
            $table->decimal('less_voluntary_excess',10,2)->nullable()->after('AInetlabour');
            $table->decimal('additional_towing',10,2)->nullable()->after('less_voluntary_excess');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_ms_assisment_calculations', function (Blueprint $table) {
            $table->dropColumn('less_voluntary_excess');
            $table->dropColumn('additional_towing');
        });
    }
};
