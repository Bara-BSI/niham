<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'code' => fake()->unique()->regexify('[A-Z0-9]{3,5}'),
            'address' => fake()->address(),
            'accent_color' => fake()->hexColor(),
            // Ensure no uuids are hardcoded, let models 'HasUuids' handle it
        ];
    }
}
