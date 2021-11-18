<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDealerDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dealer_details', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('dealer_id')->unsigned()->index('dealer_id');
			$table->date('dealer_detail_date');
			$table->string('dealer_detail_type', 50);
			$table->integer('dealer_detail_amount')->nullable();
			$table->time('dealer_detail_brief_time');
			$table->time('dealer_detail_checkout_time');
			$table->integer('dealer_detail_status')->default(1)->comment('0=inactive / 1=active');
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
		Schema::drop('dealer_details');
	}

}
