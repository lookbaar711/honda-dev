<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('events', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('event_name');
			$table->string('event_location');
			$table->date('event_start_date');
			$table->date('event_end_date');
			$table->time('staff_time')->nullable();
			$table->time('brief_time')->nullable();
			$table->time('customer_time')->nullable();
			$table->integer('event_status')->default(1)->comment('0=inactive / 1=active');
			$table->integer('file_dealer_id')->unsigned()->nullable()->comment('file Dealer NOw');
			$table->integer('file_sale_dealer_id')->unsigned()->nullable()->comment('file Sale NOw');
			$table->integer('created_by')->nullable();
			$table->integer('updated_by')->nullable();
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
		Schema::drop('events');
	}

}
