<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePreemptionsDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('preemptions_details', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('event_id')->unsigned();
			$table->string('preemption_no', 100);
			$table->enum('preemption_type', array('TB','NORMAL'))->default('TB')->comment('1=turbo / 2=normal');
			$table->integer('sale_dealer_id')->unsigned()->nullable();
			$table->dateTime('request_at')->nullable();
			$table->dateTime('response_at')->nullable();
			$table->integer('preemption_status')->default(0)->comment('0=new / 1=เบิก / 2=คืน / 3=ยกเลิก/4 = ลบ');
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
		Schema::drop('preemptions_details');
	}

}
