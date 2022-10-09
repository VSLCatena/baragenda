<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skill>
 */
class SkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => "Vaardigheid " . fake()->word(),
            'description' =>fake()->words(3,$asText = true),
            'color' =>Str::after(fake()->safeHexColor(),'#'),
            'visibility' => "visible"
        ];
    }
}
