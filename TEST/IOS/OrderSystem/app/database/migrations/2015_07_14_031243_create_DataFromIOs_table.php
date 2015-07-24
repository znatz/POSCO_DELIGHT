<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDataFromIOsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('DataFromIOs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('tanto');
			$table->text('goodsTitle');
			$table->TEXT('kosu');
			$table->text('time');
			$table->text('receiptNo');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('DataFromIOs');
	}

}
