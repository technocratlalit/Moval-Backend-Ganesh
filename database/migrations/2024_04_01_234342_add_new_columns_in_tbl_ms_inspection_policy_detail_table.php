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
        Schema::table('tbl_ms_inspection_policy_detail', function (Blueprint $table) {
            $table->string('thirdParty_insured_name')->nullable()->after('client_name');
            $table->string('thirdParty_insured_branch_name')->nullable()->after('thirdParty_insured_name');
            $table->string('thirdParty_policy_no')->nullable()->after('thirdParty_insured_branch_name');
            $table->string('thirdParty_policy_valid_from')->nullable()->after('thirdParty_policy_no');
            $table->string('thirdParty_policy_valid_to')->nullable()->after('thirdParty_policy_valid_from');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_ms_inspection_policy_detail', function (Blueprint $table) {
            $table->dropColumn('thirdParty_insured_name');
            $table->dropColumn('thirdParty_insured_branch_name');
            $table->dropColumn('thirdParty_policy_no');
            $table->dropColumn('thirdParty_policy_valid_from');
            $table->dropColumn('thirdParty_policy_valid_to');
        });
    }
};
