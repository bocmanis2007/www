<?php


class Usercalc extends BaseModel {

	protected $softDelete = true;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'usercalc';
	public static $rules = array(
		'user_id' => 'required|exists:users,id',
		'uniq_key' => 'required|size:10',
		'height' => 'required|numeric|Between:30,300',
		'waist' => 'required|numeric|Between:20,200',
		'neck' => 'required|numeric|Between:5,100',
		'hips' => 'required|numeric|Between:30,300',
		'date' => 'required|unique:usercalc,date|before:NOW'
	);

	public function user()
	{
		return $this->belongsTo( 'User' );
	}
	/*required_if:field,value*/
}