<?php

use Illuminate\Database\Migrations\Migration;

class CreateUserweightTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'userweight', function( $tbl ) {

			$tbl -> integer( 'user_id', FALSE ) -> unsigned();
			$tbl -> string( 'uniq_key', 10 );
			$tbl -> text( 'coment' ) -> nullable();
			$tbl -> decimal( 'weight', 5, 2 ) -> unsigned();
			$tbl -> timestamps();
			$tbl -> softDeletes();
			$tbl -> dateTime( 'date' );

			$tbl -> primary( array( 'user_id', 'uniq_key' ) );

			$tbl -> foreign( 'user_id' ) -> references( 'id' ) -> on( 'users' ) -> on_delete( 'CASCADE' ) -> on_update( 'CASCADE' );

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop( 'userweight' );
	}

}