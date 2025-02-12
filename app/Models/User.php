<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the isSupervisor.
     *
     * @return bool
     */
    public function getIsSupervisor()
    {
        return ($this->supervisor) ? true : false;
    }

    /**
     * Get the supervisor name.
     *
     * @return bool
     */
    public function getSupervisorId()
    {
        return ($this->supervisor) ? $this->supervisor->id : false;
    }

    /**
     * Get the superVisor for the user.
     */
    public function supervisor()
    {
        return $this->hasOne('App\Models\Supervisor');
    }
}
