<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class project extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'projects';

    protected $fillable = [
        'owner_id',
    	'text',
        'size',
    	'active',
    	'update_ts'
    ];

    /**
     * Users 
     *
     * @return \illuminate\Database\Eloquent\Relations\HasMany
     */
    public function developers()
    {
        return $this->hasMany('App\developer');
    }
    /**
     * Discussions 
     *
     * @return \illuminate\Database\Eloquent\Relations\HasMany
     */
    public function discussions()
    {
        return $this->hasMany('App\discussion');
    }
}
