<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('sqlite2')->create('Settings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('azmode');
			$table->text('tantou');
			$table->text('bumode');
			$table->text('picmode');
			$table->text('nimode');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('sqlite2')->drop('Settings');
	}

}
