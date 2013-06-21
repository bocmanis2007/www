<?php

use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'users', function( $tbl ) {

			$tbl -> increments( 'id' );
			$tbl -> string( 'username', 150 );
			$tbl -> string( 'password', 64 );
			$tbl -> string( 'email', 150 );
			$tbl -> boolean( 'is_admin' ) -> default( FALSE );
			$tbl -> boolean( 'distribution' ) -> default( TRUE );
			$tbl -> date( 'b_date' );
			$tbl -> boolean( 'gender' );
			$tbl -> timestamps();

			$tbl -> date( 'last_login_date' ) -> nullable();
			$tbl -> string( 'firstname', 100 ) -> nullable();
			$tbl -> string( 'lastname', 150 ) -> nullable();

			$tbl -> string( 'ban_id', 7 ) -> nullable();
			$tbl -> date( 'ban_until' ) -> nullable();
			$tbl -> text( 'ban_reason' ) -> nullable();

			$tbl -> unique( 'username' );
			$tbl -> unique( 'email' );

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop( 'users' );
	}

}