<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSaleDealersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sale_dealers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('dealer_id')->unsigned();
			$table->string('dealer_ids', 50)->nullable();
			$table->string('dealer_legacy_code', 50);
			$table->string('sale_dealer_code', 50);
			$table->string('sale_dealer_name')->nullable();
			$table->string('sale_dealer_nickname', 50)->nullable();
			$table->string('sale_dealer_tel', 10)->nullable();
			$table->integer('sale_dealer_status')->default(1)->comment('0=inactive / 1=active');
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
		Schema::drop('sale_dealers');
	}

}
