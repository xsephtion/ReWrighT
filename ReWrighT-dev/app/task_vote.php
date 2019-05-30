<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class task_vote extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'task_votes';

    protected $fillable = [
    	'task_comment_id',
    	'user_id',
    	'vote',
    	'update_ts'
    ];
    /**
     * User Information
     *
     * @return \illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\user');
    }
    /**
     * Comment Information
     *
     * @return \illuminate\Database\Eloquent\Relations\HasOne
     */
    public function comment()
    {
        return $this->belongsTo('App\task_comment');
    }
}
