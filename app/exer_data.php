<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class exer_data extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'exer_data';

    protected $fillable = [
        'user_id',
    	'desc',
    	'file',
        'created',
        'active'
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
}
