<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('email', 191)->unique();
			$table->string('password', 191);
			$table->string('api_token', 100)->nullable();
			$table->text('permissions', 65535)->nullable();
			$table->dateTime('last_login')->nullable();
			$table->string('full_name', 191)->nullable();
			$table->string('tel_info')->nullable();
			$table->string('email_info')->nullable();
			$table->string('code', 100)->nullable();
			$table->integer('status_process')->default(0)->comment('0 = admin,1 = operation');
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
