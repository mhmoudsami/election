<?php

namespace Database\Factories;

use App\Models\Candidate;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Supervisor;

class CandidateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Candidate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'mobile' => $this->faker->randomDigit(11),
            'uid' => $this->faker->randomDigit(14),
            'supervisor_id' => factory(\App\Models\Supervisor::class),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
