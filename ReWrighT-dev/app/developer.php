<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class developer extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'developers';

    protected $fillable = [
    	'project_id',
    	'user_id',
    	'role',
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
    /**
     * Project information
     *
     * @return \illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function project(){
         return $this->belongsTo('App\project');
    }
}
