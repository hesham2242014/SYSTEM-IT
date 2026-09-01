<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_department_list_shows_the_employee_count(): void
    {
        $department = Department::factory()->create(['name' => 'تقنية المعلومات']);
        Employee::factory()->count(3)->create(['department_id' => $department->id]);

        $this->get(route('departments.index'))
            ->assertOk()
            ->assertSee('تقنية المعلومات', false)
            ->assertSee('3');
    }

    public function test_a_department_can_be_created(): void
    {
        $this->post(route('departments.store'), [
            'code' => 'IT',
            'name' => 'تقنية المعلومات',
            'location' => 'الدور الثالث',
            'description' => null,
        ])->assertRedirect(route('departments.index'));

        $this->assertDatabaseHas('departments', ['code' => 'IT']);
    }

    public function test_creating_a_department_requires_a_code_and_a_name(): void
    {
        $this->post(route('departments.store'), [])
            ->assertSessionHasErrors(['code', 'name']);
    }

    public function test_the_department_code_must_be_unique(): void
    {
        Department::factory()->create(['code' => 'IT']);

        $this->post(route('departments.store'), ['code' => 'IT', 'name' => 'قسم آخر'])
            ->assertSessionHasErrors('code');
    }

    public function test_a_department_can_be_updated(): void
    {
        $department = Department::factory()->create();

        $this->put(route('departments.update', $department), [
            'code' => $department->code,
            'name' => 'الموارد البشرية',
        ])->assertRedirect(route('departments.index'));

        $this->assertSame('الموارد البشرية', $department->refresh()->name);
    }

    public function test_an_empty_department_can_be_deleted(): void
    {
        $department = Department::factory()->create();

        $this->delete(route('departments.destroy', $department))
            ->assertRedirect(route('departments.index'));

        $this->assertDatabaseMissing('departments', ['id' => $department->id]);
    }

    public function test_a_department_with_employees_cannot_be_deleted(): void
    {
        $department = Department::factory()->create();
        Employee::factory()->create(['department_id' => $department->id]);

        $this->delete(route('departments.destroy', $department))
            ->assertRedirect(route('departments.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('departments', ['id' => $department->id]);
    }
}
