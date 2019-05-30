<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class user_info extends Model
{
    public $primaryKey  = 'user_id';
	 /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users_info';

	protected $fillable = [
		'user_id',
		'profile',
		'banner',
		'employee_id',
		'student_id',
		'first_name',
		'middle_name',
		'last_name',
		'suffix_name',
		'sex',
		'perm_address',
		'tempo_address',
		'office_address',
        //github_id
		'update_ts'
	];
	/**
     * User's personal information
     *
     * @return \illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user(){
    	 return $this->belongsTo('App\User');
    }
}
