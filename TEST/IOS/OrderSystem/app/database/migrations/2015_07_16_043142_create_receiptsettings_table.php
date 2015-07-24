<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReceiptsettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('receiptsettings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('tax');
			$table->integer('haveReceipt');
			$table->integer('haveStamp');
			$table->integer('haveComment');
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
		Schema::drop('receiptsettings');
	}

}
