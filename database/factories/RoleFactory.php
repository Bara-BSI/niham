<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->jobTitle(),
            'perm_assets' => 'no access',
            'perm_users' => 'no access',
            'perm_categories' => 'no access',
            'perm_departments' => 'no access',
            'perm_roles' => 'no access',
        ];
    }
}
