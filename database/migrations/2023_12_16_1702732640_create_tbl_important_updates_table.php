<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblImportantUpdatesTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_important_updates', function (Blueprint $table) {

		$table->id();
		$table->text('message');
		$table->text('to_admin_ids');
		$table->text('seen_admin_ids');
		$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

		$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));


        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_important_updates');
    }
}