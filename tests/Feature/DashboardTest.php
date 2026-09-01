<?php

namespace Tests\Feature;

use App\Enums\EmployeeStatus;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_dashboard_summarises_the_employee_data(): void
    {
        $department = Department::factory()->create(['name' => 'المبيعات']);
        Employee::factory()->count(2)->create([
            'department_id' => $department->id,
            'status' => EmployeeStatus::Active,
            'salary' => 10000,
        ]);
        Employee::factory()->create([
            'department_id' => $department->id,
            'status' => EmployeeStatus::Terminated,
            'salary' => 5000,
        ]);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('المبيعات', false)
            ->assertViewHas('employeesCount', 3)
            ->assertViewHas('activeCount', 2)
            ->assertViewHas('payroll', 20000.0);
    }

    public function test_the_dashboard_works_with_an_empty_database(): void
    {
        $this->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('employeesCount', 0);
    }
}
