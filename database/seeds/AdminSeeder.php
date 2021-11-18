<?php

use Faker\Factory;
use App\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends DatabaseSeeder {

	public function run()
	{
		DB::table('users')->truncate(); // Using truncate function so all info will be cleared when re-seeding.
		DB::table('roles')->truncate();
		DB::table('role_users')->truncate();
		DB::table('activations')->truncate();

		$system_admin = Sentinel::registerAndActivate(array(
			'email'       => 'admin@admin.com',
			'password'    => "123456",
			'full_name'  => 'SystemAdmin',
			'status_process'  => 0,
		));

		$super_admin = Sentinel::registerAndActivate(array(
			'email'       => 'admin@admin',
			'password'    => "123456",
			'full_name'  => 'SuperAdmin',
			'status_process'  => 0,
		));

		$operation = Sentinel::registerAndActivate(array(
			'email'       => 'admin',
			'password'    => "123456",
			'full_name'  => 'Operation',
			'status_process'  => 1,
		));

		$adminRole = Sentinel::getRoleRepository()->createModel()->create([
			'name' => 'Admin',
			'slug' => 'admin',
			'permissions' => array('admin' => 1),
		]);

        $userRole = Sentinel::getRoleRepository()->createModel()->create([
			'name'  => 'User',
			'slug'  => 'user',
		]);


		$system_admin->roles()->attach($adminRole);
		$super_admin->roles()->attach($adminRole);
		$operation->roles()->attach($adminRole);

		$this->command->info('Admin User created with username admin@admin.com and password 123456');
	}

}