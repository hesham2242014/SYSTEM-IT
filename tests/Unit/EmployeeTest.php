<?php

namespace Tests\Unit;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_builds_the_full_name_from_the_first_and_last_name(): void
    {
        $employee = new Employee(['first_name' => 'هشام', 'last_name' => 'عبد الرحمن']);

        $this->assertSame('هشام عبد الرحمن', $employee->full_name);
    }

    public function test_it_calculates_the_years_of_service(): void
    {
        $employee = Employee::factory()->make(['hire_date' => now()->subYears(5)->subMonths(3)]);

        $this->assertSame(5, $employee->years_of_service);
    }

    public function test_the_search_scope_ignores_an_empty_term(): void
    {
        Employee::factory()->count(2)->create();

        $this->assertSame(2, Employee::search('  ')->count());
        $this->assertSame(2, Employee::search(null)->count());
    }
}
