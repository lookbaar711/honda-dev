<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCheckinCheckoutTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('checkin_checkout', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('event_id')->unsigned();
			$table->integer('dealer_id')->unsigned();
			$table->integer('sale_dealer_id')->unsigned();
			$table->date('event_date');
			$table->time('checkin_time')->nullable();
			$table->time('checkout_time')->nullable();
			$table->string('checkin_reason')->nullable();
			$table->string('checkout_reason')->nullable();
			$table->string('checkin_over_reason')->nullable();
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
		Schema::drop('checkin_checkout');
	}

}
