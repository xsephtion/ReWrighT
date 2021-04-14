<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class user_types extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_types';

    /**
     * The primary key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'type';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'type',
    	'description'
    ];
}
