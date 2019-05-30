<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class discussion_notif extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'discussion_notifs';

    protected $fillable = [
    	'discussion_id',
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
    public function discussion()
    {
        return $this->belongsTo('App\discussion');
    }
}
