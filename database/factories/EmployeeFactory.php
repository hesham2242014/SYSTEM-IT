<?php

namespace Database\Factories;

use App\Enums\EmployeeStatus;
use App\Enums\Gender;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_code' => strtoupper($this->faker->unique()->bothify('EMP-####')),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'national_id' => $this->faker->unique()->numerify('##############'),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->numerify('01#########'),
            'gender' => $this->faker->randomElement(Gender::cases()),
            'birth_date' => $this->faker->dateTimeBetween('-55 years', '-22 years'),
            'hire_date' => $this->faker->dateTimeBetween('-12 years', 'now'),
            'department_id' => Department::factory(),
            'job_title' => $this->faker->jobTitle(),
            'salary' => $this->faker->numberBetween(5000, 60000),
            'status' => $this->faker->randomElement(EmployeeStatus::cases()),
            'address' => $this->faker->address(),
            'notes' => null,
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['status' => EmployeeStatus::Active]);
    }
}
