<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tags extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'task_tags';

    protected $fillable = [
    	'tasks_id',
        'tag_info_id',
        'active'
    ];
    /**
     * User Information
     *
     * @return \illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
        return $this->belongsTo('App\task');
    }
    /**
     * Project Information
     *
     * @return \illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tag_info()
    {
        return $this->belongsTo('App\tag_info');
    }
}
