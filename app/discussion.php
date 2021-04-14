<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class discussion extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'discussions_board';

    protected $fillable = [
    	'project_id',
    	'user_id',
    	'title',
    	'text',
    	'image',
    	'priority',
    	'active',
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
     * Project Information
     *
     * @return \illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo('App\project');
    }
    /**
     * Comments
     *
     * @return \illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\discussion_comment');
    }
    /**
     * notifs
     *
     * @return \illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany('App\discussion_notif');
    }
}
