<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'summary' => "Activiteit " . fake()->sentence(2),
            'description' => fake()->sentence(24),
            'datetime_start' => fake()->dateTimeBetween('-1 week', '+1 week'),
            'datetime_end' => fake()->dateTimeBetween('+2 week', '+4 week'),
            'date_start' => fake()->dateTimeBetween('-1 week', '+1 week'),
            'date_end' => fake()->dateTimeBetween('+2 week', '+4 week'),
            'all_day' => fake()->boolean(),
            'google_calendar_id' => fake()->numberBetween(1000, 2000),
            'google_event_id' => fake()->numberBetween(1000, 2000),
            'guests_caninviteothers' => fake()->boolean(),
            'guests_canmodify' => fake()->boolean(),
            'guests_canseeotherguests' => fake()->boolean(),
            'status' => "draft",
            'updated_by' => 10,
        ];
    }
}
