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
        Schema::table('tbl_ms_admin_branch', function (Blueprint $table) {
            $table->string('letter_head_img')->nullable()->after('admin_id');
            $table->string('letter_footer_img')->nullable()->after('letter_head_img');
            $table->string('signature_img')->nullable()->after('letter_footer_img');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_ms_admin_branch', function (Blueprint $table) {
            $table->dropColumn('letter_head_img');
            $table->dropColumn('letter_footer_img');
            $table->dropColumn('signature_img');
        });
    }
};
