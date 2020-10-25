<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['supervisor_name'];

    /**
     * Get the supervisor name.
     *
     * @return bool
     */
    public function getSupervisorNameAttribute()
    {
        return ($this->supervisor) ? $this->supervisor->name : '-';
    }

	/**
	 * Create a new factory instance for the model.
	 *
	 * @return \Illuminate\Database\Eloquent\Factories\Factory
	 */
	protected static function newFactory()
	{
	    return \Database\Factories\CandidateFactory::new();
	}


    /**
     * Get the Supervisor that owns the Candidate.
     */
    public function supervisor()
    {
        return $this->belongsTo('App\Models\Supervisor');
    }
}
