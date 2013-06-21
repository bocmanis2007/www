<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('UserTableSeeder');
		$this->command->info('User table seeded!');


		$this->call('UserCalcTableSeeder');
		$this->command->info('Usercalc table seeded!');


		$this->call('UserWeightTableSeeder');
		$this->command->info('Userweight table seeded!');
	}


}

class UserTableSeeder extends Seeder {

	public function run()
	{
		DB::table( 'userweight' )->delete();
		DB::table( 'usercalc' )->delete();
		DB::table( 'users' )->delete();

		User::create( array(
			'username' => 'admin',
			'password' => Hash::make( '123' ),
			'email' => 'admin@sport.loc',
			'is_admin' => 1,
			'distribution' => 1,
			'b_date' => '1991-07-18 00:00:00',
			'gender' => 1,
			'created_at' => '0000-00-00 00:00:00',
			'updated_at' => '0000-00-00 00:00:00'
		) );
	}

}
class UserCalcTableSeeder extends Seeder {

	public function run()
	{
		DB::table( 'usercalc' )->delete();

		$admin = DB::table( 'users' )->where( 'is_admin', '=', 1 )->first();

		Usercalc::create( array(
			'user_id' => $admin->id,
			'uniq_key' => '1234567890',
			'height' => 185,
			'waist' => 88,
			'neck' => 38,
			'hips' => 80,
			'date' => date( 'Y-m-d H:i:s' ),
			'created_at' => '0000-00-00 00:00:00',
			'updated_at' => '0000-00-00 00:00:00'
		) );
	}

}

class UserWeightTableSeeder extends Seeder {

	public function run()
	{
		DB::table( 'userweight' )->delete();

		$admin = DB::table( 'users' )->where( 'is_admin', '=', 1 )->first();

		Userweight::create( array(
			'user_id' => $admin->id,
			'uniq_key' => '1234567890',
			'weight' => 80,
			'date' => date( 'Y-m-d H:i:s' ),
			'created_at' => '0000-00-00 00:00:00',
			'updated_at' => '0000-00-00 00:00:00'
		) );
	}

}