<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class task_exer_data extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'task_exer_data';

    protected $fillable = [
        'id',
        'task_assignment_id',
        'exer_data_id',
        'patient_data_id',
        'freq_order',
        'resultScore',
        'adjustedResultScore',
        'created',
        'active'
    ];
    /**
     * Task assigned to
     *
     * @return \illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignedTo()
    {
        return $this->belongsTo('App\assigned_to');
    }
    /**
     * Exer Data information
     *
     * @return \illuminate\Database\Eloquent\Relations\HasOne
     */
    public function exerDataInformation()
    {
        return $this->hasOne('App\exer_data');
    }
}
