<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBasicZipcodeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('basic_zipcode', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('district_code', 6);
			$table->string('zipcode', 5);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ros_rbasicdata_zipcode');
	}

}
