<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminBranchIdToTblMsAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ms_admin', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_branch_id')->nullable();
            $table->foreign('admin_branch_id')->references('id')->on('tbl_ms_admin_branch');

            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('tbl_ms_clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_ms_admin', function (Blueprint $table) {
            $table->dropForeign(['admin_branch_id']);
            $table->dropColumn('admin_branch_id');

            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
        });
    }
}
