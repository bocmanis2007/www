<?php


class Userweight extends Eloquent {

	protected $softDelete = true;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'userweight';

	public static $rules = array(
		'user_id' => 'required|exists:users,id',
		'uniq_key' => 'required|size:10',
		'weight' => 'required|numeric|between:1,500',
		'date' => 'required|unique:userweight,date|before:NOW'
	);
	public static function validate( $data, $my_rules=FALSE )
	{
		if( !$my_rules )
			return Validator::make( $data, static::$rules );
		else
			return Validator::make( $data, $my_rules );
	}

	public function user()
	{
		return $this->belongsTo( 'User' );
	}


}