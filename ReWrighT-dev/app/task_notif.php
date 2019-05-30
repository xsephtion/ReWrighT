<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class task_notif extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'task_notifs';

    protected $fillable = [
    	'task_id',
    	'user_id',
    	'seen',
    	'read',
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
    public function task()
    {
        return $this->belongsTo('App\task');
    }
}
