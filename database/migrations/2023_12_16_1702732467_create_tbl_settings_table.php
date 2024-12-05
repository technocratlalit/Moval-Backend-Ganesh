<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_settings', function (Blueprint $table) {

		$table->id();
		$table->string('type',500);
		$table->text('message');
        $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

		$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));


        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_settings');
    }
}