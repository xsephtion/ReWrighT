<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class task_comment extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'task_comments';

    protected $fillable = [
    	'task_id',
    	'user_id',
    	'text',
    	'image',
    	'upvote',
    	'downvote',
    	'update_ts'
    ];
    /**
     * User Information
     *
     * @return \illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->belongsTo('App\user');
    }
    /**
     * Discussion Information
     *
     * @return \illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function task()
    {
        return $this->belongsTo('App\task');
    }
     /**
     * Discussion Information
     *
     * @return \illuminate\Database\Eloquent\Relations\HasOne
     */
    public function vote()
    {
        return $this->hasOne('App\task_vote');
    }
    /**
     * Discussion Information
     *
     * @return \illuminate\Database\Eloquent\Relations\HasOne
     */
    public function notif()
    {
        return $this->hasMany('App\task_vote');
    }
}
