<?php

class UserWeightChartController extends BaseUserController {


	public function showWeightChartPage()
	{
		$userweight = User::find( Auth::user()->id )->userweight()->select( array( 'weight', 'date', 'coment' ) )->orderBy( 'date' )->get();
		$dates = array(
			'start' => 	date( 'Y', strtotime( $userweight[ 0 ]->date ) ) . ',' .
						( intval( date( 'm', strtotime( $userweight[ 0 ]->date ) ) ) - 1 ) . ',' .
						( intval( date( 'd', strtotime( $userweight[ 0 ]->date ) ) ) - 2 ),
			'end' => 	date( 'Y', strtotime( $userweight[ count( $userweight ) - 1 ]->date ) ) . ',' .
						( intval( date( 'm', strtotime( $userweight[ count( $userweight ) - 1 ]->date ) ) ) - 1 ) . ',' .
						( intval( date( 'd', strtotime( $userweight[ count( $userweight ) - 1 ]->date ) ) ) + 1 )
		);
		$data = array(
			'sidebar' => $this->getSidebar(),
			'userweight' => $userweight,
			'dates' => $dates,
			'user' => $this->user_full_data( Auth::user()->id )
		);
		return View::make( 'user.weightchart.index', $data );
	}


}