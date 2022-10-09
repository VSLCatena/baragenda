<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ShiftType;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shift>
 */
class ShiftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => "Dienst " . fake()->word(),
            'datetime' => fake()->dateTimeBetween('-1 week', '+1 week'),
            'datetime_end' => fake()->dateTimeBetween('+2 week', '+4 week'),
            'description'=> fake()->words(3,$asText = true),
        ];
    }
}
