<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Info;
use App\Models\User;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Info>
 */
class InfoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Info::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'objectGUID' => fake()->uuid(),
            'user_id' => User::factory(),
            'lidnummer' => (string) fake()->numberBetween(10, 23) . "-" . (string) fake()->randomNumber(3,true),
            'relatienummer' => fake()->randomNumber(7,true),
            'name' => fake()->name(),
            'firstname' => fake()->firstname(),
            'email' => fake()->safeEmail(),
            'available' => fake()->boolean(),
            'autofill_name' => fake()->boolean()
        ];
    }
}
