<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'mobile',
        'mobile_2',
        'city_id',
        'address',
    ];


    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['username' , 'city'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the supervisor name.
     *
     * @return bool
     */
    public function getUsernameAttribute()
    {
        return ($this->user) ? $this->user->name : '-';
    }

    /**
     * Get the city name.
     *
     * @return bool
     */
    public function getCityAttribute()
    {
        if ( ! $this->city_id ) {
            return '';
        }
        $name = '';
        foreach (config('election.city') as $city) {
            if ( $city['id'] == $this->city_id ) {
                $name = $city['name'];
                break;
            }
        }
        return $name;
    }

    /**
     * Get the candidates for the supervisor.
     */
    public function candidates()
    {
        return $this->hasMany('App\Models\Candidate');
    }

    /**
     * Get the user for the supervisor.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
