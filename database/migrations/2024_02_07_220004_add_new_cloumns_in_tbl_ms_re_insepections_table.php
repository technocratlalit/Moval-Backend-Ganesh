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
            $table->text('list_allowed_parts')->nullable()->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_ms_re_insepections', function (Blueprint $table) {
            $table->dropColumn('list_allowed_parts');
        });
    }
};
