<?php

class UserProfileController extends BaseUserController {


	public function showProfilePage()
	{
		$data = array(
			'sidebar' => $this->getSidebar(),
			'weight' => User::find( Auth::user()->id )->userweight()->count()
		);
		return View::make( 'user.profile.index', $data );
	}
	public function showWeightTable()
	{
		$data = array(
			'sidebar' => $this->getSidebar(),
			'weights' => User::find( Auth::user()->id )->userweight()->orderBy( 'date' )->get()
		);
		/*foreach ($data[ 'weights' ] as $key => &$value) {
			$value->color = $this->randomColor();
		}*/
		return View::make( 'user.profile.weighttable', $data );
	}
	public function showWeightPoint( $id, $uniq_key )
	{
		$data = array(
			'sidebar' => $this->getSidebar(),
			'weight' => User::find( $id )->userweight()->where( 'uniq_key', '=', $uniq_key )->first()
		);
		return View::make( 'user.profile.weight', $data );
	}
	public function showWeightPointNew()
	{
		$data = array(
			'sidebar' => $this->getSidebar()
		);
		return View::make( 'user.profile.weight', $data );
	}
	public function editSaveWeightPoint( $id, $uniq_key )
	{
		$userdata = array(
			'user_id' => $id,
			'uniq_key' => $uniq_key,
			'weight' => Input::get( 'weight' ),
			'date' => date( 'Y-m-d H:i:s', strtotime( Input::get( 'date' ) ) )
		);
		if( ! User::find( $id ) AND $id != Auth::user()->id ) {
			Session::flash('error', 'User id is defined incorect!');
			return Redirect::route( 'weighttable' );
		}
		$rules = array(
			'user_id' => 'required|exists:users,id',
			'uniq_key' => 'required|size:10',
			'weight' => 'required|numeric|between:1,500',
			'date' => 'required|unique:userweight,date,' . $uniq_key . ',uniq_key|before:NOW'
		);
		$valid = Userweight::validate( $userdata, $rules );
		if( $valid -> fails() ) {
			Session::flash('error', 'Incorect data!');
			return Redirect::route( 'editweight', array( 'id' => $id, 'uniq_key' => $uniq_key ) )->withInput()->withErrors( $valid );
		}
		else {
			DB::table( 'userweight' ) ->
				where( 'user_id', '=', $id ) ->
				where( 'uniq_key', '=', $uniq_key ) ->
				update( array(
					'weight' => $userdata[ 'weight' ],
					'updated_at' => date( 'Y-m-d H:i:s' ),
					'date' => $userdata[ 'date' ],
					'coment' => Input::get( 'coment' )
				) );
			Session::flash( 'notice' ,'Edited successfuly.' );
			return Redirect::route( 'weighttable' );
		}
	}
	public function saveNewWeightPoint()
	{
		$userdata = array(
			'user_id' => $id,
			'uniq_key' => $uniq_key,
			'weight' 	=> Input::get( 'weight' ),
			'date' 		=> date( 'Y-m-d H:i:s', strtotime( Input::get( 'date' ) ) )
		);
		$valid = Userweight::validate( $userdata );
		if( $valid -> fails() ) {
			Session::flash('error', 'Incorect data!');
			return Redirect::route( 'newweight' )->withInput()->withErrors( $valid );
		} else {
			$Weight = array(
				'user_id' => Auth::user()->id,
				'uniq_key' => $this->randomStr(),
				'weight' => $userdata[ 'weight' ],
				'date' =>  $userdata[ 'date' ],
				'coment' => Input::get( 'coment' ),
				'created_at' => date( 'Y-m-d H:i:s' ),
				'updated_at' => date( 'Y-m-d H:i:s' )
			);
			//return print_r($Weight);
			//$new_weight = new Userweight( $Weight );
			DB::table( 'userweight' )->insert( $Weight );
			//Userweight::create( $Weight );			//$new_weight->save();
			//$user = User::find( Auth::user()->id )->userweight()->save( $new_weight );

			/*$new_weight = new Userweight( array(
				'user_id' => Auth::user()->id,
				'uniq_key' => $this->randomStr(),
				'weight' => $userdata[ 'weight' ],
				'date' => $userdata[ 'date' ],
				'coment' => Input::get( 'coment' )
			) );*/
			//$new_weight = $user->userweight()->save( $new_weight );
			//if( $new_weight ){
				Session::flash( 'notice', 'Weight point successfuly created.' );
				return Redirect::route( 'weighttable' );
			//} else {
			//	Session::flash( 'error', 'Weight point creating error!' );
			//	return Redirect::route( 'newweight' )->withInput();
			//}
		}
	}
	public function deleteWeightPoint( $id, $uniq_key )
	{
		if( Auth::user()->id == $id  AND User::find( $id )->userweight()->where( 'uniq_key', '=', $uniq_key )->count() > 0 ) {
			User::find( $id )->userweight()->where( 'uniq_key', '=', $uniq_key )->delete();
			$ret = array(
				'error' => false,
				'notice' => 'successfuly deleted!'
			);
		} else {
			$ret = array(
				'error' => true,
				'notice' => 'Trying delete incorect record'
			);
		}
		return Response::json( $ret );
	}


}