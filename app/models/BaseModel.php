<?php


class BaseModel extends Eloquent {



	public static $validationMessages = null;

	public static function baseValidate( $input=null, $roles_key $newRoles=null ) {
		if ( is_null( $input ) )
			$input = Input::all();
		$Roles = ( is_null( $newRoles ) ) ? static::$rules : $newRoles;
		return Validator::make( $input, $Roles );

		if ( $v->passes() ) {
			return true;
		} else {
			// save the input to the current session
			Input::flash();
			self::$validationMessages = $v->getMessages();
			return false;
		}
	}


}