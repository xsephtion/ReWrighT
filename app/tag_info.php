<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tag_info extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tag_info';

    protected $fillable = [
    	'id',
    	'desc',
        'active'
    ];
}
