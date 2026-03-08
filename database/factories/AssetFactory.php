<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Asset::class;

    public function definition(): array
    {
        $department = Department::inRandomOrder()->first() ?? Department::factory()->create();
        $category = Category::inRandomOrder()->first() ?? Category::factory()->create();

        $departmentId = $department->id;
        $categoryId = $category->id;

        static $counter = 1;

        return [
            'uuid' => Str::uuid(),
            'tag' => 'LPI/'
                .$category->code
                .'/'
                .$department->code
                .'/N/'
                .fake()->randomElement(['001', '002', '003']),
            'name' => fake()->randomElement(['PSU', 'UPS', 'Keyboard', 'Monitor']).' '.fake()->numerify('###'),
            'category_id' => $categoryId,
            'department_id' => $departmentId,
            'status' => fake()->randomElement(['out_of_service', 'disposed', 'in_service']),
            'serial_number' => strtoupper(fake()->bothify('SN###???')),
            'purchase_date' => now()->subYears(random_int(0, 5)),
            'purchase_cost' => fake()->randomFloat(2, 50, 1500),
            'vendor' => fake()->company(),
        ];
    }
}
