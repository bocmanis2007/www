<?

class BaseUserController extends BaseController {


	protected function getSidebar()
	{
		$data = array(
			'username' => Auth::user()->username,
			'user' => $this->user_full_data( Auth::user()->id )
		);
		Session::flash( 'user', $data );
		return View::make( 'user.sidebar', $data );
	}

	protected function user_full_data( $user_id ) {
		$user = User::find( $user_id )->first();

		$weight = $this->userNewestWeightData( $user->id );
		if( ! $weight ) {
			Session::flash('error', 'There no weight data! Pliese fill it in Profile');
			return FALSE;
		}
		$user->weight = $weight->weight;

		$calcData = $this->userNewestCalcData( $user->id );
		if( ! $calcData ) {
			Session::flash('error', 'There no user parameters data! Pliese fill it in Profile');
			return FALSE;
		}
		$user->height = $calcData->height;
		$user->waist = $calcData->waist;
		$user->neck = $calcData->neck;
		$user->hips = $calcData->hips;
		$user->minweight = $this->userMinWeight( $user->id );

		$stats = $this->user_stats( $user );
		foreach ( $stats as $key => $value ) {
			$user->$key = $value;
		}

		return $user;
	}

	protected function randomStr( $length = 10 ) {
		$characters = '_0123456789_abcdefghijklmnopqrstuvwxyz_ABCDEFGHIJKLMNOPQRSTUVWXYZ_';
		$randomString = '';
		for ( $i = 0; $i < $length; $i++ ) {
			$randomString .= $characters[ rand( 0, strlen( $characters ) - 1) ];
		}
		return $randomString;
	}

	protected function age( $b_date ) {
		$date = strtotime( date( 'Y-m-d H:i:s' ) );
		return floor ( ( $date - strtotime ( $b_date ) ) / 60 / 60 / 24 / 365 );
	}
	protected function userCalcData( $user_id ) {
		$user = User::find( $user_id );
		if ( $user ) {
			$data = DB::table( 'usercalc' )->where( 'user_id', '=', $user->id )->orderBy( 'date' )->get();
			if( ! $data ) return FALSE;
			return $data;
		}
		return FALSE;
	}
	protected function userNewestCalcData( $user_id ) {
		$data = $this->userCalcData( $user_id );
		if( ! $data ) return FALSE;
		return $data[ count( $data ) - 1 ];
	}
	protected function userWeightData( $user_id ) {
		$user = User::find( $user_id );
		if ( $user ) {
			$data = DB::table( 'userweight' )->where( 'user_id', '=', $user->id )->orderBy( 'date' )->get();
			if( $data )
				return $data;
		}
		return FALSE;
	}
	protected function userNewestWeightData( $user_id ) {
		$data = $this->userWeightData( $user_id );
		if( ! $data ) return FALSE;
		return $data[ count( $data ) - 1 ];
	}
	protected function userMinWeight( $user_id )
	{
		$data = $this->userWeightData( $user_id );
		$minweight = $data[ 0 ]->weight;
		foreach ($data as $value) {
			if( $minweight > $value->weight )
				$minweight = $value->weight;
		}
		return $minweight;
	}
	protected function user_stats( $user ) {
		$ht = floatval( $user->height );
		$nk = floatval( $user->neck );
		$wa = floatval( $user->waist );
		$hp = floatval( $user->hips );
		$wt = floatval( $user->weight );
		$ag = $this->age( $user->b_date );
		$sexf = $user->gender == 0 ? TRUE : FALSE;

		$fatOw = 18;
		$fatOb = 25;
		$bstate = true;
		$BMR = $fatPercent = $w1 = $s1 = '';
		$BMI = round( $wt / ( ( $ht / 100 ) * ( $ht / 100 ) ), 2 );
		if( $sexf ) {
			$fatOw = 25;
			$fatOb = 31;
			$BMR = 655 + ( 9.6 * $wt ) + ( 1.8 * $ht ) - ( 4.7 * $ag );
			$fatPercent = 495 / ( 1.29579 - 0.35004 * ( log( $wa + $hp - $nk, 10 ) ) + 0.221 * ( log( $ht, 10 ) ) ) - 450;
		}
		else {
			$BMR = 66 + ( 13.7 * $wt ) + ( 5 * $ht ) - ( 6.8 * $ag );
			$fatPercent = 495 / ( 1.0324 - 0.19077 * ( log( $wa - $nk, 10 ) ) + 0.15456 * ( log( $ht, 10 ) ) ) - 450;
		}
		$BMR = round( $BMR, 2 );
		$fatPercent = round( $fatPercent, 2 );
		$fat = round ( $wt * $fatPercent / 100, 2 );
		return array(
			'BMI' => $BMI,
			'BMR' => $BMR,
			'fatpercent' => $fatPercent,
			'age' => $ag,
			'fat' => $fat,
			'mass' => $wt - $fat,
			'perfect_weight' => round( ( ( $ht / 100 ) * ( $ht / 100 ) ) * 22, 2 )
		 );
		/*
			Ir vajadzígs noteikt cik ir aktívs cilvēks lai noteiktu viņa dailycalk
		function Calculate()
		{
			var dailyCal;
			if (document.cal_frm.opActivity.value == "0") {dailyCal = BMR * 1.2;}
			if (document.cal_frm.opActivity.value == "1") {dailyCal = BMR * 1.375;}
			if (document.cal_frm.opActivity.value == "2") {dailyCal = BMR * 1.55;}
			if (document.cal_frm.opActivity.value == "3") {dailyCal = BMR * 1.725;}
			if (document.cal_frm.opActivity.value == "4") {dailyCal = BMR * 1.9;}
			if (BMI < 18){
				w1 = (18 * ht * ht / 10000) - wt;
				s1 = "You are underweight by "}
			else{
				if (fatPercent <= fatOw){
					s1 = "Your weight is normal."
					bstate = false;}
				else{
					w1 = wt * (fatPercent - fatOw) / (100 - fatOw);
					if (fatPercent >= fatOb){
						s1 = "Your weight state is Obese."+ "<br />" + "You should reduce your weight by ";}
					else{
						s1 = "You are Overweight by ";}
				}
			}
			if (bstate){
				if (document.cal_frm.opWeight.value == 1){
					s1 = s1 + (Math.round((w1 / 0.4536)*10)/10) + " pound(s).";}
				else{
					s1 = s1 + (Math.round(w1*10)/10) + " Kg.";
				}
			}
			document.getElementById('feedback').innerHTML = "<p>Your BMR is: " + BMR + "<br />" + "Your BMI is: " + BMI +
			"<br />" + "Minimum Calorie requirement is: " + (Math.round(dailyCal)) + "<br />" + "Your Body Fat is " + fatPercent + "%." + "<br />" + s1+"</p>"
			}
			function logten(v){
				return (Math.log(v)/Math.log(10));
			}
			function resetAll(){
				document.getElementById('feedback').innerHTML ='';
			}*/
	}
	protected function randomColor() {
		$colors = array(
			'A200FF',
			'FF0097',
			'00ABA9',
			'8CBF26',
			'A05000',
			'E671B8',
			'339933',
			'F09609',
			'1BA1E2',
			'E51400'
		);
		return '#' . $colors[ rand( 0, count( $colors ) - 1) ];
	}


}