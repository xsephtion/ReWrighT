<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class assigned_to extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'task_assignment';

    protected $fillable = [
        'id',
    	'task_id',
        'user_id',
        'training_data',
        'status',
    	'start',
    	'end',
        'active'
    ];
    /**
     * User Information of the patient
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
     * exer_datas
     *
     * @return \illuminate\Database\Eloquent\Relations\HasMany
     */
    
    public function exer_datas()
    {
        return $this->hasOne('App\task_exer_data');
    }
    
}
