<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return Redirect::route( 'login' );
});

Route::get( 'login', array( 'as' => 'login', 'uses' => 'UserLoginController@showLoginPage' ) )
	->before( 'guest' );

Route::post( 'login', array( 'uses' => 'UserLoginController@userLogin' ) );

Route::get( 'register', array( 'as' => 'register', 'uses' => 'UserLoginController@showRegisterPage' ) );

Route::get( 'logout', array( 'as' => 'logout', function(){

	Auth::logout();
	return Redirect::route( 'login' )
		->with( 'notice', 'You are successfully logged out.' );

 } ) )->before( 'auth' );

Route::group( array( 'before' => 'auth', 'prefix' => 'user' ), function()
{
	Route::get( 'profile', array( 'as' => 'profile', 'uses' => 'UserProfileController@showProfilePage' ) );

	Route::get( 'weightchart', array( 'as' => 'weightchart', 'uses' => 'UserWeightChartController@showWeightChartPage' ) );

	Route::get( 'weighttable', array( 'as' => 'weighttable', 'uses' => 'UserProfileController@showWeightTable' ) );

	Route::get( 'weighttable/new', array( 'as' => 'newweight', 'uses' => 'UserProfileController@showWeightPointNew' ) );
	Route::POST( 'weighttable/new', 'UserProfileController@saveNewWeightPoint' );

	Route::get( '{id}/weight/{uniq_key}', array( 'as' => 'editweight', 'uses' => 'UserProfileController@showWeightPoint' ) );

	Route::post( '{id}/weight/{uniq_key}', 'UserProfileController@editSaveWeightPoint' );

	Route::get( '{id}/weight/{uniq_key}/delete', array( 'as' => 'deleteweight', 'uses' => 'UserProfileController@deleteWeightPoint' ) );
});



