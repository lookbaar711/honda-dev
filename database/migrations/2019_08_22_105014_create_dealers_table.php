<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDealersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dealers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('dealer_legacy_code', 50);
			$table->string('dealer_ids_code', 50)->nullable();
			$table->string('dealer_zone', 50);
			$table->string('dealer_area', 50);
			$table->string('dealer_dlr', 50);
			$table->string('dealer_name');
			$table->integer('dealer_vip');
			$table->integer('dealer_press');
			$table->integer('dealer_weekday');
			$table->integer('dealer_weekend');
			$table->integer('dealer_status')->default(1)->comment('0=inactive / 1=active');
			$table->integer('event_id')->unsigned();
			$table->integer('created_by')->nullable();
			$table->timestamps();
			$table->integer('updated_by')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('dealers');
	}

}
