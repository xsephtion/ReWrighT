<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class discussion_comment extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'discussion_comments';

    protected $fillable = [
    	'discussion_id',
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
    public function discussion()
    {
        return $this->belongsTo('App\discussion');
    }
     /**
     * Discussion Information
     *
     * @return \illuminate\Database\Eloquent\Relations\HasOne
     */
    public function vote()
    {
        return $this->hasOne('App\discussion_vote');
    }   
}
