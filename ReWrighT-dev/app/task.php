<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class task extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tasks_board';

    protected $fillable = [
    	'project_id',
    	'title',
    	'text',
    	'image',
    	'priority',
    	'user_id',
    	'assigned_to_id',
    	'dt_deadline',
    	'dt_started_on',
    	'dt_ended_on',
    	'active',
    	'status',
    	'hours_spent',
    	'update_ts'
    ];
    /**
     * User Information
     *
     * @return \illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignedBy()
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
        return $this->hasMany('App\task_comment');
    }
    /**
     * notifs
     *
     * @return \illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany('App\task_notif');
    }
}
