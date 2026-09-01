<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->bothify('DP-##?')),
            'name' => $this->faker->unique()->words(2, true),
            'location' => $this->faker->city(),
            'description' => $this->faker->sentence(),
        ];
    }
}
