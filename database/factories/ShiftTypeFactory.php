<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShiftType>
 */
class ShiftTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'enabled' => fake()->boolean(),
            'common' => fake()->boolean(),
            'title' => "Type" . fake()->word(),
            'description' =>fake()->words(3,$asText = true)
        ];
    }
}
