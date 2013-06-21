<?php

class UserLoginController extends BaseController {


	public function showLoginPage()
	{
		return View::make( 'login' );
	}

	public function userLogin()
	{
		$user = array(
			'username' => Input::get( 'username' ),
			'password' => Input::get( 'password' )
		);

		if( Auth::attempt( $user ) ) {
			return Redirect::route( 'profile' )
				->with( 'notice', 'You are successfully logged in.' );
		}
		else {
			return Redirect::route( 'login' )
				->with( 'error', 'Your username/password combination was incorrect.' );
		}
	}

}