<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password','user_type',
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * User's personal information
     *
     * @return \illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userInformation()
    {
        return $this->hasOne('App\user_info');
    }
    /**
     * Projects the User is included
     *
     * @return \illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this->hasMany('App\developer');
    }
    /**
     * User's created Discussion
     *
     * @return \illuminate\Database\Eloquent\Relations\HasMany
     */
    public function discussions()
    {
        return $this->hasMany('App\discussion');
    }
    /**
     * User's created Discussion Comments
     *
     * @return \illuminate\Database\Eloquent\Relations\HasMany
     */
    public function discussion_comments()
    {
        return $this->hasMany('App\discussion_comments');
    }
    /**
     * User's Discussion votes
     *
     * @return \illuminate\Database\Eloquent\Relations\HasMany
     */
    public function discussion_votes()
    {
        return $this->hasMany('App\discussion_vote');
    }
    /**
     * User's Discussion notifications
     *
     * @return \illuminate\Database\Eloquent\Relations\HasMany
     */
    public function discussion_notif()
    {
        return $this->hasMany('App\discussion_notif');
    }
    /**
     * User's created Tasks
     *
     * @return \illuminate\Database\Eloquent\Relations\HasMany
     */
    public function createdTask()
    {
        return $this->hasMany('App\task');
    }
}
